<?php

namespace Trois\Bexio\Webservice;

use Muffin\Webservice\Model\Endpoint;

class ContactsRelationsWebservice extends BexioWebservice
{
  public function initialize(): void
  {
    parent::initialize();

    $this->setEndpoint('contact_relation');

    $this->addNestedResource('/3.0/contact_relation/:id', [
      'id',
    ]);
  }
}
