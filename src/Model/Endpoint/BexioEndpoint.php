<?php
namespace Trois\Bexio\Model\Endpoint;

use Cake\Datasource\EntityInterface;
use Muffin\Webservice\Model\Endpoint;

class BexioEndpoint extends Endpoint
{
  use EntityMatchingTrait;

  public static function defaultConnectionName(): string
  {
    return 'bexio';
  }

  public function save(EntityInterface $resource, $options = [])
  {
    //check errors
    if($resource->hasErrors()) return false;

    // evt
    $event = $this->dispatchEvent('Model.beforeSave', compact('resource', 'options'));
    if ($event->isStopped()) return $event->result;

    // set data
    //$data = $resource->toArray(); // differs from original
    $data = $resource->extract($this->getSchema()->columns(), true);

    if($resource->isNew()) $query = $this->query()->create();
    else $query = $query = $this->query()->update();
    $query->set($data);
    $query->applyOptions($options); // differs from original

    // HTTP
    $result = $query->execute();

    // hande response
    if (!$result) return false;
    if (($resource->isNew()) && ($result instanceof EntityInterface)) return $result;
    $className = get_class($resource);
    return new $className($resource->toArray(), ['markNew' => false, 'markClean' => true]);
  }
}
