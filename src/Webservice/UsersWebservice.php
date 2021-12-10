<?php

namespace Trois\Bexio\Webservice;

use Muffin\Webservice\Model\Endpoint;

class UsersWebservice extends BexioWebservice
{
  public function initialize(): void
  {
    parent::initialize();

    $this->setEndpoint('users');

    $this->addNestedResource('users/:userId', [
      'userId',
    ]);
  }
}
