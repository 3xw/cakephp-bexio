<?php

namespace Trois\Bexio\Webservice;

use Muffin\Webservice\Model\Endpoint;

class ContactRelationsWebservice extends BexioWebservice
{
  public function getBaseUrl()
  {
    return '/2.0/' . $this->getEndpoint();
  }

  public function initialize(): void
  {
    parent::initialize();

    $this->setEndpoint('contact_relation');

    $this->addNestedResource('/2.0/contact_relation/:id', [
      'id',
    ]);
  }
}
