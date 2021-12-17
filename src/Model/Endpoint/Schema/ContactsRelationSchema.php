<?php

namespace Trois\Bexio\Model\Endpoint\Schema;

use Muffin\Webservice\Model\Schema;

class getContactsRelationSchema extends Schema
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
    $this->addColumn('contact_id', [
      'type' => 'integer',
    ]);
    $this->addColumn('contact_sub_id', [
      'type' => 'integer',
    ]);
  }
}
