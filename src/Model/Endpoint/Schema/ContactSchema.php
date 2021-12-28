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
    $this->addColumn('name_1', [
      'type' => 'string',
    ]);
    $this->addColumn('nr', [
      'type' => 'string',
    ]);
    $this->addColumn('phone_fixed', [
      'type' => 'string',
    ]);
    $this->addColumn('address', [
      'type' => 'string',
    ]);
    $this->addColumn('city', [
      'type' => 'string',
    ]);
    $this->addColumn('postcode', [
      'type' => 'string',
    ]);

    $this->addColumn('contact_group_ids', [
      'type' => 'string',
    ]);
    $this->addColumn('country_id', [
      'type' => 'string',
    ]);
    $this->addColumn('contact_type_id', [
      'type' => 'integer',
    ]);
    $this->addColumn('user_id', [
      'type' => 'integer',
    ]);
    $this->addColumn('owner_id', [
      'type' => 'integer',
    ]);
  }
}
