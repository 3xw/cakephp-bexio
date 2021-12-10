<?php

namespace Trois\Bexio\Webservice;

use Cake\Network\Http\Response;
use Cake\Utility\Hash;
use Muffin\Webservice\Model\Endpoint;
use Muffin\Webservice\Query;
use Muffin\Webservice\ResultSet;
use Muffin\Webservice\Webservice\Webservice;

/**
* Class GitHubWebservice
*
* @package CvoTechnologies\GitHub\Webservice
*/
class BexioWebservice extends Webservice
{

  protected $limit = 500;
  protected $offset = 0;

  /**
  * Returns the base URL for this endpoint
  *
  * @return string Base URL
  */
  public function getBaseUrl()
  {
    return '/v3/' . $this->endpoint();
  }

  /**
  * {@inheritDoc}
  */
  protected function _executeReadQuery(Query $query, array $options = [])
  {
    $url = $this->getBaseUrl();

    $queryParameters = [];

    // Result limit has been set, add to query parameters
    if ($query->limit()) {
      $queryParameters['limit'] = $query->limit();
    }

    // Page number has been set, add to query parameters
    if ($query->page()) {
      $queryParameters['offset'] = $query->limit()? $query->page() * $query->limit(): $query->page() * $this->limit;
    }

    // Order
    if ($query->order()) {
      foreach($query->order() as $field => $sort) {
        if(is_numeric($field)) $queryParameters['order_by'] = $sort;
        else $queryParameters['order_by'] = $field.'_'.strtolower($sort);
      }
    }

    $search = false;
    $searchParameters = [];
    if ($query->clause('where')) {
      foreach ($query->clause('where') as $field => $value) {
        switch ($field) {
          case 'id':
          default:
          // Add the condition as search parameter
          $searchParameters[$field] = $value;

          // Mark this query as a search
          $search = true;
        }
      }
    }

    // Check if this query could be requested using a nested resource.
    if ($nestedResource = $this->nestedResource($query->clause('where'))) {
      $url = $nestedResource;

      // If this is the case turn search of
      $search = false;
    }

    if ($search) {
      $url = $url. '/search';

      $searchBody = [];
      foreach ($searchParameters as $parameter => $value)
      {
        $terms = explode(' ', $parameter);
        $searchBody[] = [
          'field' => $terms[0],
          'value' => $value,
          'criteria' => empty($terms[1])? '=': strtolower($terms[1])
        ];
      }
    }

    /* @var Response $response */
    $response = $search?
      $this->driver()->client()->get($url, $queryParameters):
      $this->driver()->client()->post($url, json_encode($searchBody), $queryParameters);
    $results = $response->getJson();
    if (!$response->isOk())
    {
      debug($response->getJson());
      throw new \Exception($response->getJson()['message']);
    }

    // Turn results into resources
    $resources = $this->_transformResults($query->endpoint(), $results);

    return new ResultSet($resources, count($resources));
  }

  protected function _transformResults(Endpoint $endpoint, array $results)
  {
    $resources = [];
    foreach ($results as $key =>$result)
    {
      if(!is_numeric($key)) return [$this->_transformResource($endpoint, $results)];
      $resources[] = $this->_transformResource($endpoint, $result);
    }

    return $resources;
  }

  protected function _executeCreateQuery(Query $query, array $options = [])
  {
    return $this->_write($query, $options);
  }

  protected function _executeUpdateQuery(Query $query, array $options = [])
  {
    return $this->_write($query, $options);
  }

  protected function _write(Query $query, array $options = [])
  {
    $url = $this->getBaseUrl();
    if (
    $query->getOptions() &&
    !empty($query->getOptions()['nested']) &&
    $nestedResource = $this->nestedResource($query->getOptions()['nested'])
    ) $url = $nestedResource;

    switch ($query->action())
    {
      case Query::ACTION_CREATE:
      $response = $this->driver()->client()->post($url, json_encode($query->set()));
      break;

      case Query::ACTION_UPDATE:
      $response = $this->driver()->client()->put($url, json_encode($query->set()));
      break;

      case Query::ACTION_DELETE:
      $response = $this->driver()->client()->delete($url);
      break;
    }

    if (!$response->isOk())
    {
      debug($response);
      debug($response->getStringBody());
      throw new \Exception($response->getJson()['err']);
    }

    return $this->_transformResource($query->endpoint(), $response->getJson());
  }

}
