<?php

namespace Trois\Bexio\Webservice;

use Muffin\Webservice\Model\Endpoint;

class ContactsRelationsWebservice extends BexioWebservice
{
  public function initialize(): void
  {
    parent::initialize();

    $this->setEndpoint('contact_relation');
  }
}
