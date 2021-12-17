<?php

namespace Trois\Bexio\Model\Endpoint\Schema;

use Muffin\Webservice\Model\Schema;

class ProjectSchema extends Schema
{
  /**
  * {@inheritDoc}
  */
  public function initialize(): void
  {
    parent::initialize();

    $this->addColumn('id', [
      'type' => 'integer',
      'primaryKey' => true
    ]);
    $this->addColumn('pr_state_id', [
      'type' => 'integer',
    ]);
    $this->addColumn('pr_project_type_id', [
      'type' => 'integer',
    ]);
    $this->addColumn('contact_id', [
      'type' => 'integer',
    ]);
    $this->addColumn('user_id', [
      'type' => 'integer',
    ]);
  }
}
