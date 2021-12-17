<?php

namespace Trois\Bexio\Model\Endpoint\Schema;

use Muffin\Webservice\Model\Schema;

class ContactSchema extends Schema
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
    $this->addColumn('firstname', [
      'type' => 'string',
    ]);
    $this->addColumn('lastname', [
      'type' => 'string',
    ]);
    $this->addColumn('email', [
      'type' => 'string',
    ]);
  }
}
