<?php

namespace Trois\Bexio\Webservice;

use Muffin\Webservice\Model\Endpoint;

class ContactGroupsWebservice extends BexioWebservice
{
  public function getBaseUrl()
  {
    return '/2.0/' . $this->getEndpoint();
  }

  public function initialize(): void
  {
    parent::initialize();

    $this->setEndpoint('contact_group');

    $this->addNestedResource('/2.0/contact_group/:id', [
      'id',
    ]);
  }
}
