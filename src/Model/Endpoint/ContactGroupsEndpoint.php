<?php
namespace Trois\Bexio\Model\Endpoint;

use Muffin\Webservice\Model\Resource;

class ContactGroupsEndpoint extends BexioEndpoint
{
  public function initialize(array $config): void
  {
    parent::initialize($config);
    $this->setPrimaryKey('id');
    $this->setDisplayField('name');
    //$this->setWebservice('Space', new \App\Webservice\ClickUp\SpaceWebservice);
    //debug($this->getWebservice());
  }

  public function convertData(array $data, string $model, array $associations = []): array
  {
    return [
      'name' => $data['name']
    ];
  }
}
