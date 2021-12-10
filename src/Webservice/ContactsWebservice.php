<?php

namespace Trois\Bexio\Webservice;

use Muffin\Webservice\Model\Endpoint;

class ContactsWebservice extends BexioWebservice
{
  public function initialize()
  {
    parent::initialize();

    $this->setEndpoint('contact');

    $this->addNestedResource('/v3/contact', []);

    $this->addNestedResource('/v3/contact/:contactId', [
      'contactId',
    ]);
  }
}
