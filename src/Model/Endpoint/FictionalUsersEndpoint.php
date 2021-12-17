<?php
namespace Trois\Bexio\Model\Endpoint;

class FictionalUsersEndpoint extends BexioEndpoint
{
  public function initialize(array $config): void
  {
    parent::initialize($config);
    $this->setPrimaryKey('id');
    $this->setDisplayField('email');
    //$this->setWebservice('Space', new \App\Webservice\ClickUp\SpaceWebservice);
    //debug($this->getWebservice());
  }
}
