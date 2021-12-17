<?php

namespace Trois\Bexio\Webservice;

use Muffin\Webservice\Model\Endpoint;

class ProjectStatesWebservice extends BexioWebservice
{
  public function getBaseUrl()
  {
    return '/2.0/' . $this->getEndpoint();
  }

  public function initialize(): void
  {
    parent::initialize();

    $this->setEndpoint('pr_project_state');
  }
}
