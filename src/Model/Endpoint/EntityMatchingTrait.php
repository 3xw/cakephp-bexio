<?php
namespace Trois\Bexio\Model\Endpoint;

use Cake\Utility\Hash;
use Cake\Datasource\EntityInterface;
use Cake\Datasource\ModelAwareTrait;
use Muffin\Webservice\Model\Resource;
use Cake\ORM\Table;

trait EntityMatchingTrait
{
  use ModelAwareTrait;

  public $matches = [];

  public $ressources = [];

  public function convertData(array $data, string $model, array $options = []): array
  {
    return $data;
  }

  public function newEntityFromCakeEntity(array $data, string $model, array $options = []): Resource
  {
    debug("EntityMatchingTrait: newEntityFromCakeEntity $model");
    return $this->newEntity($this->convertData($data, $model, $options));
  }

  public function patchFromCakeEntity(Resource $res, array $data, string $model, array $options = []): Resource
  {
    return $this->patchEntity($res, $this->convertData($data, $model, $options));
  }

  public function getBexioMatches(): Table
  {
    if(!property_exists($this, 'BexioMatches')) $this->loadModel('Trois/Bexio.BexioMatches');
    return $this->BexioMatches;
  }

  public function findMatch(EntityInterface $entity, string $model)
  {
    if($match = Hash::get($this->matches,"$model.$entity->id")) return $match;
    if($match = $this->getBexioMatches()->find()->where(['model' => $model,'foreign_id' => $entity->id,])->first()) Hash::insert($this->matches,"$model.$entity->id", $match);
    return $match;
  }

  public function findRessource($id)
  {
    if($res = Hash::get($this->ressources, $id)) return $res;
    return $this->find()->where(['id' => $id])->first();
  }

  public function saveFromCakeEntity(EntityInterface $entity, string $model, array $options = [])
  {
    // create
    if($entity->isNew()) return $this->newFromEntity($entity, $model, $options);
    if(!$match = $this->findMatch($entity, $model)) return $this->newFromEntity($entity, $model, $options);


    // get ressource form cache or from API
    if(!$res = $this->findRessource($match->bexio_id)) throw new \Exception("Unable to retrive ressource for $model: (id) $match->bexio_id");
    //debug("EntityMatchingTrait: saveFromCakeEntity");
    //debug($res);

    // update if needed
    $res = $this->patchFromCakeEntity($res, $entity->toArray(), $model, $options);
    if( $entity->isDirty() && !$res = $this->save($res)) return false;

    Hash::insert($this->ressources, $match->bexio_id, $res);
    return [$res, $match];
  }

  public function newFromEntity(EntityInterface $entity, string $model, array $options = [])
  {
    $res = $this->newEntityFromCakeEntity($entity->toArray(), $model, $options);
    if(!$res = $this->save($res)) return false;
    $match = $this->saveMatch($entity, $model, $res);
    return [$res, $match];
  }

  public function saveMatch(EntityInterface $entity, string $model, Resource $ressource): EntityInterface
  {
    if(!$match = $this->getBexioMatches()->save($this->getBexioMatches()->newEntity([
      'model' => $model,
      'foreign_id' => $entity->id,
      'bexio_id' => $ressource->id
    ])
    )) throw new \Exception("Unable to save match for $model: (id)$entity->id");

    return $match;
  }
}
