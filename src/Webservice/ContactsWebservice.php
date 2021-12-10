<?php

namespace Trois\Bexio\Webservice;

use Muffin\Webservice\Model\Endpoint;

class ContactsWebservice extends BexioWebservice
{
  public function initialize(): void
  {
    parent::initialize();

    $this->setEndpoint('contact');

    $this->addNestedResource('contact/:contactId', [
      'contactId',
    ]);
  }
}
