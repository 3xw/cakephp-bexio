<?php

namespace Trois\Bexio\Webservice\Driver;

use Cake\Http\Client;
use Muffin\Webservice\Webservice\Driver\AbstractDriver;

class Bexio extends AbstractDriver
{

  /**
  * {@inheritDoc}
  */
  public function initialize(): void
  {
    $this->setClient(new Client([
      'host' => 'api.bexio.com',
      'scheme' => 'https',
      'headers' => [
        'Authorization' => 'Bearer '.$this->getConfig('token'),
        'Content-Type' => 'application/json',
        'Accept' => 'application/json',
      ]
    ]));
  }
}
