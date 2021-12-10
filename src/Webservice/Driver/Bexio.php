<?php

namespace Trois\Bexio\Webservice\Driver;

use Cake\Network\Http\Client;
use Muffin\Webservice\AbstractDriver;

class Bexio extends AbstractDriver
{

  /**
  * {@inheritDoc}
  */
  public function initialize()
  {
    $this->client(new Client([
      'host' => 'api.bexio.com',
      'scheme' => 'https',
      'headers' => [
        'Authorization' => $this->getConfig('token'),
        'Content-Type' => 'application/json'
      ]
    ]));
  }
}
