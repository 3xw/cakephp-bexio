<?php

namespace Trois\Bexio\Model;

use Cake\Datasource\RepositoryInterface;
use Cake\Datasource\ModelAwareTrait;
use Cake\Core\InstanceConfigTrait;

class BexioModels
{
  use ModelAwareTrait;
  use InstanceConfigTrait;
  /** TODO

  **  Excepetion handler for 429 rate limit!
  */
  /*
  protected $_defaultConfig = [
    'service_' => 'embed',
    'file_field' => 'path'
  ];
  */

  public function getContacts(): RepositoryInterface
  {
    return $this->loadModel('Trois/Bexio.Contacts', 'Endpoint');
  }

  public function getContactsRelations(): RepositoryInterface
  {
    return $this->loadModel('Trois/Bexio.ContcatsRelations', 'Endpoint');
  }

  public function getUsers(): RepositoryInterface
  {
    return $this->loadModel('Trois/Bexio.Users', 'Endpoint');
  }

  public function getFictionalUsers(): RepositoryInterface
  {
    return $this->loadModel('Trois/Bexio.FictionalUsers', 'Endpoint');
  }

  public function getProjects(): RepositoryInterface
  {
    return $this->loadModel('Trois/Bexio.Projects', 'Endpoint');
  }

  public function getProjectStates(): RepositoryInterface
  {
    return $this->loadModel('Trois/Bexio.ProjectStates', 'Endpoint');
  }

  public function getProjectTypes(): RepositoryInterface
  {
    return $this->loadModel('Trois/Bexio.ProjectTypes', 'Endpoint');
  }
}
