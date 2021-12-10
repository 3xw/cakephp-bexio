<?php
namespace Trois\Bexio\Model\Endpoint;

class UsersEndpoint extends BexioEndpoint
{
  public function initialize(array $config): void
  {
    parent::initialize($config);
    $this->setPrimaryKey('id');
    $this->setDisplayField('firstmane');
    //$this->setWebservice('Space', new \App\Webservice\ClickUp\SpaceWebservice);
    //debug($this->getWebservice());
  }
}
