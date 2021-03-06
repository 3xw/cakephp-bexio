<?php

namespace Trois\Bexio\Webservice\Driver;

use Cake\Http\Client;
use Cake\Http\Client\Request;
use Cake\Http\Client\Response;
use Muffin\Webservice\Webservice\Driver\AbstractDriver;

class Bexio extends AbstractDriver
{

  protected $rateLimitRetryBreak = 20; // in sec

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

  public function get(string $url, $data = [], array $options = []): Response
  {
    return $this->_doRequest(Request::METHOD_GET, $url, $data , $options);
  }

  public function post(string $url, $data = [], array $options = []): Response
  {
    return $this->_doRequest(Request::METHOD_POST, $url, $data , $options);
  }

  public function put(string $url, $data = [], array $options = []): Response
  {
    return $this->_doRequest(Request::METHOD_PUT, $url, $data , $options);
  }

  public function delete(string $url, $data = [], array $options = []): Response
  {
    return $this->_doRequest(Request::METHOD_DELETE, $url, $data , $options);
  }

  protected function _doRequest(string $method, string $url, $data, $options): Response
  {
    // debug("Bexio driver: $url ($method)");
    $rsp = $this->getClient()->{$method}($url, $data , $options);
    // debug("Bexio driver: $url ($method): ".$rsp->isOk());
    if (!$rsp->isOk())
    {
      switch($rsp->getStatusCode())
      {
        case 429: // rate limit
        debug("sleep for $this->rateLimitRetryBreak...");
        sleep($this->rateLimitRetryBreak);
        return $this->_doRequest($method, $url, $data, $options);

        default:
        debug($url);
        debug($rsp->getJson());
        debug(json_decode($data));
        throw new \Exception($rsp->getJson()['message']);
      }
    }
    return $rsp;
  }
}
