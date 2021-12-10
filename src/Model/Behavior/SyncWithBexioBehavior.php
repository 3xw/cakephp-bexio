<?php
namespace Trois\Bexio\Model\Behavior;

use ArrayObject;
use Cake\Event\Event;
use Cake\Datasource\EntityInterface;
use Cake\ORM\Behavior;
use Cake\ORM\Table;
use Cake\Utility\Inflector;

class SyncWithBexioBehavior extends Behavior
{
  use \Cake\Datasource\ModelAwareTrait;

  protected $_defaultConfig = [
    'endpoint' => 'Folders',
    'joinTable' => [
      'modelName' => 'BexioMatches',
      'foreignKey' => 'foreign_id',
      'bexioKey' => 'bexio_id',
      'conditions' => ['BexioMatches.model' => 'Accounts'],
    ],
    'staticMatching' => [],
    'mapping' => [],
    'delete' => false
  ];

  protected function patchResource($resource, EntityInterface $entity, ArrayObject $options)
  {
    $data = [];
    foreach($this->getConfig('staticMatching') as $field => $value) $data[$filed] = $value;
    foreach($this->getConfig('mapping') as $field => $mapping) $data[$field] = $this->getValueOrCallable($mapping, $entity);

    return $this->getEndpoint()->patchEntity($resource, $data, $options->getArrayCopy());
  }

  public function afterSave(Event $event, EntityInterface $entity, ArrayObject $options)
  {

    if(empty($options['EnableBexioSync'])) return;
    if(empty($options['EnableBexioSync']['nested'])) throw new \Exception('Need nested options to create records on Bexio');

    // create empty
    $bexioId = false;
    $resource =  $this->getEndpoint()->newEntity();

    // check if one exists
    if(
      !$entity->isNew() &&
      $bexioId = $this->getBexioId($entity, $options['EnableBexioSync']['nested'])
    ){
      if(
        $resourceExists = $this->getEndpoint()->find()
        ->where([$this->getNestedVarName($this->getConfig('endpoint')) => $bexioId])
        ->first()
      ) $resource = $resourceExists;
    }

    // warm
    $resource = $this->patchResource($resource, $entity, $options);
    $nested = $this->getNestedOptionsForResource($resource, $options['EnableBexioSync']['nested']);

    // save
    if(!$resource = $this->getEndpoint()->save($resource, ['nested' => $nested])) return;

    // set entity
    $entity->set('resource', $resource);

    // save relation
    if(!$bexioId)
    {
      $this->getJoinTable()->save($this->getJoinTable()->newEntity([
         'model' => $this->getTable()->getAlias(),
         'foreign_id' =>$entity->get($this->getTable()->getPrimaryKey()),
         'bexio_id' => $resource->id
       ]));
    }
  }

  public function afterDelete(Event $event, EntityInterface $entity, ArrayObject $options)
  {
    // check
    if(!$this->getConfig('delete')) return;
    if(empty($options['EnableBexioSync'])) return;

    // related
    if(!$bexioId = $this->getBexioId($entity)) return;
    $nested = $this->getNestedOptions([$this->getConfig('endpoint') => $bexioId]);

    // delete
    $this->getEndpoint()->delete($entity, ['nested' => $nested]);
  }

  /* UTILS */
  public function getBexioId(EntityInterface $entity, $nested = null)
  {
    // get
    if(!$joinEntity = $this->getJoinEntity($entity))
    {
      if(!$nested) return false;
      if(!$joinEntity = $this->lookupOnBexioAndCreate($entity, $nested)) return false;
    }

    // return ID
    $join = (object) $this->getConfig('joinTable');
    return $joinEntity->get($join->bexioKey);
  }

  public function lookupOnBexioAndCreate(EntityInterface $entity, $nested)
  {
    $items = $this->getEndpoint()->find()->where($nested)->toArray();
    $entityId4Digi = sprintf('%04d', $entity->number);

    foreach ($items as $itm)
    {
      $parts = explode(" ", trim($itm->name));
      if($entityId4Digi == sprintf('%04d',end($parts)))
      {
        if(
          !$joinEntity = $this->getJoinTable()->save($this->getJoinTable()->newEntity([
            'model' => $this->getTable()->alias(),
            'foreign_id' => $entity->id,
            'bexio_id' => $itm->id
          ]))
        ) throw new \Exception("Error Processing Request", 1);

        return $joinEntity;
      }
    }

    return false;
  }

  protected function getEndpoint($endpoint = null)
  {
    if(!$endpoint) $endpoint = $this->getConfig('endpoint');
    if(property_exists($this, $endpoint)) return $this->{$endpoint};
    return $this->loadModel("Trois/Bexio.$endpoint", 'Endpoint');
  }

  public static function getValueOrCallable($value, ...$args)
  {
    if(is_callable($value)) return call_user_func_array($value, $args);
    else if(!empty($args) && is_subclass_of($args[0], 'Cake\Datasource\EntityInterface')) return $args[0]->{$value};
    else return $value;
  }

  protected function getJoinTable()
  {
    $mn = $this->getConfig('joinTable.modelName');
    if(property_exists($this, $mn)) return $this->{$mn};
    else return $this->loadModel($mn);
  }

  protected function getJoinEntity(EntityInterface $entity)
  {
    // set
    $join = (object) $this->getConfig('joinTable');
    $key = $entity->get($this->getTable()->getPrimaryKey());
    $join->conditions["$join->modelName.$join->foreignKey"] = $key;

    // get
    return $this->getJoinTable()->find()->where($join->conditions)->first();
  }

  protected function getNestedVarName($endpointName)
  {
    return Inflector::singularize(strtolower($endpointName)).'Id';
  }

  protected function getNestedOptions(array $nested)
  {
    $opt = [];
    foreach($nested as $endpointName => $id) $opt[$this->getNestedVarName($endpointName)] = $id;
    return $opt;
  }

  protected function getNestedOptionsForResource($resource, $nested = [])
  {
    if($resource->isNew()) return $nested;
    return [
      $this->getNestedVarName($this->getConfig('endpoint')) => $resource->id
    ];
  }
}
