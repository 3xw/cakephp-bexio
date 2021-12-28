<?php
namespace Trois\Bexio\Model\Endpoint;

use Muffin\Webservice\Model\Resource;

class ContactsEndpoint extends BexioEndpoint
{
  protected $matchingFields = [
    'name' => 'name_1',
    'id' => 'nr',
    'phone' => 'phone_fixed',
    'billing_street' => 'address',
    'billing_city' => 'city',
    'billing_zip' => 'postcode',
    // 'billing_country' => 'country'
  ];

  public function initialize(array $config): void
  {
    parent::initialize($config);
    $this->setPrimaryKey('id');
    $this->setDisplayField('name_1');
    //$this->setWebservice('Space', new \App\Webservice\ClickUp\SpaceWebservice);
    //debug($this->getWebservice());
  }

  public function convertData(array $data, string $model, array $associations = []): array
  {
    $extracted = [];
    foreach($this->matchingFields as $lookFor => $field) if(!empty($data[$lookFor])) $extracted[$field] = $data[$lookFor];

    // fixed
    $extracted = array_merge($extracted, [
      'contact_type_id' => 1,
      'country_id' => 1,
    ]);

    // user stuff
    $ownerMatch = $this->getBexioMatches()->find()->where(['foreign_id' => $data['owner_id'], 'model' => 'Users'])->firstOrFail();
    $extracted = array_merge($extracted, [
      'user_id' => $ownerMatch->bexio_id,
      'owner_id' => $ownerMatch->bexio_id
    ]);

    // groups
    if(!empty($associations['ContactGroups'])) $extracted = array_merge($extracted, [
      'contact_group_ids' => $associations['ContactGroups'],
    ]);

    return $extracted;
  }
}
