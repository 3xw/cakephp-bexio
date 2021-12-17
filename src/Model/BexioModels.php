<?php

namespace Trois\Bexio\Model;

use Cake\Datasource\RepositoryInterface;
use Cake\Datasource\ModelAwareTrait;

class BexioModels
{
  use ModelAwareTrait;

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
