<?php
namespace Trois\Bexio\Model\Endpoint;

use ArrayObject;
use Cake\Datasource\EntityInterface;
use Muffin\Webservice\Model\Endpoint;

class BexioEndpoint extends Endpoint
{
  use EntityMatchingTrait;

  public $defaultUserId = 1;

  public static function defaultConnectionName(): string
  {
    return 'bexio';
  }

  public function patchEntity(EntityInterface $entity, array $data, array $options = []): EntityInterface
  {
    $marshaller = $this->marshaller();
    if(empty($data['user_id'])) $data['user_id'] = $this->defaultUserId;

    return $marshaller->merge($entity, array_merge($entity->toArray(), $data), $options);
  }

  public function save(EntityInterface $resource, $options = [])
  {
    $options = new ArrayObject((array)$options + [
      'checkRules' => true,
      'checkExisting' => false,
    ]);

    //check errors
    if($resource->hasErrors()) return false;

    // evt
    $event = $this->dispatchEvent('Model.beforeSave', compact('resource', 'options'));
    if ($event->isStopped()) return $event->result;

    // set data
    //$data = $resource->toArray(); // differs from original
    $data = $resource->extract($this->getSchema()->columns(), true);

    if($resource->isNew()) $query = $this->query()->create();
    else $query = $query = $this->query()->update()->where(['id' => $data['id']]);
    $query->set($data);
    $query->applyOptions($options->getArrayCopy()); // differs from original

    // HTTP
    $result = $query->execute();

    // hande response
    if (!$result) return false;


    if (($resource->isNew()) && ($result instanceof EntityInterface)) $result->setNew(true);

    // after event!
    $event = $this->dispatchEvent('Model.afterSaveCommit', compact('result', 'options'));
    if ($event->isStopped()) return $event->result;

    if (($resource->isNew()) && ($result instanceof EntityInterface)) return $result;
    $className = get_class($resource);
    return new $className($resource->toArray(), ['markNew' => false, 'markClean' => true]);
  }
}
