<?php

namespace Trois\Bexio\Webservice;

use Muffin\Webservice\Model\Endpoint;

class ProjectsWebservice extends BexioWebservice
{
  public function getBaseUrl()
  {
    return '/2.0/' . $this->getEndpoint();
  }

  public function initialize(): void
  {
    parent::initialize();

    $this->setEndpoint('pr_project');

    $this->addNestedResource('/2.0/pr_project/:id', [
      'id',
    ]);

  }
}
