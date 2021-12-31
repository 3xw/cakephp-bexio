<?php

namespace Trois\Bexio\Webservice;

use Cake\Network\Http\Response;
use Cake\Utility\Hash;
use Muffin\Webservice\Model\Endpoint;
use Muffin\Webservice\Datasource\Query;
use Muffin\Webservice\Datasource\ResultSet;
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
    return '/3.0/' . $this->getEndpoint();
  }

  /**
  * {@inheritDoc}
  */
  protected function _executeReadQuery(Query $query, array $options = [])
  {
    $url = $this->getBaseUrl();

    $queryParameters = [];

    // Result limit has been set, add to query parameters
    if ($query->clause('limit')) {
      $queryParameters['limit'] = $query->clause('limit');
    }

    // Page number has been set, add to query parameters
    if ($query->clause('offset')) {
      $queryParameters['offset'] = $query->clause('offset');
    }

    // Order
    if ($query->clause('order')) {
      foreach($query->clause('order') as $field => $sort) {
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
          $searchParameters[$field] = $value;
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
    $this->getDriver()->post($url, json_encode($searchBody), $queryParameters):
    $this->getDriver()->get($url, $queryParameters);

    // Turn results into resources
    $resources = $this->_transformResults($query->getEndpoint(), $response->getJson());

    return new ResultSet($resources, count($resources));
  }

  protected function _transformResults(Endpoint $endpoint, array $results): array
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

  protected function _executeDeleteQuery(Query $query, array $options = [])
  {
    return $this->_write($query, $options);
  }

  protected function _write(Query $query, array $options = [])
  {
    $url = $this->getBaseUrl();

    $nested = false;
    if ($query->clause('where')) $nested = $query->clause('where');
    if(!empty($query->getOptions()['nested'])) $nested = $query->getOptions()['nested'];
    if ($nested && $nestedResource = $this->nestedResource($nested)) $url = $nestedResource;


    switch ($query->clause('action'))
    {
      case Query::ACTION_CREATE:
      $response = $this->getDriver()->post($url, json_encode($query->set()));
      break;

      case Query::ACTION_UPDATE:
      $response = $this->getDriver()->put($url, json_encode($query->set()));
      break;

      case Query::ACTION_DELETE:
      $response = $this->getDriver()->delete($url);
      break;
    }

    return $this->_transformResource($query->getEndpoint(), $response->getJson());
  }

}
