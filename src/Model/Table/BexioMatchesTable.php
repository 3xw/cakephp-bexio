<?php
declare(strict_types=1);

namespace Trois\Bexio\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
* BexioMatches Model
*
* @property \App\Model\Table\ForeignsTable&\Cake\ORM\Association\BelongsTo $Foreigns
* @property \App\Model\Table\BexiosTable&\Cake\ORM\Association\BelongsTo $Bexios
*
* @method \App\Model\Entity\BexioMatch newEmptyEntity()
* @method \App\Model\Entity\BexioMatch newEntity(array $data, array $options = [])
* @method \App\Model\Entity\BexioMatch[] newEntities(array $data, array $options = [])
* @method \App\Model\Entity\BexioMatch get($primaryKey, $options = [])
* @method \App\Model\Entity\BexioMatch findOrCreate($search, ?callable $callback = null, $options = [])
* @method \App\Model\Entity\BexioMatch patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
* @method \App\Model\Entity\BexioMatch[] patchEntities(iterable $entities, array $data, array $options = [])
* @method \App\Model\Entity\BexioMatch|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
* @method \App\Model\Entity\BexioMatch saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
* @method \App\Model\Entity\BexioMatch[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
* @method \App\Model\Entity\BexioMatch[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
* @method \App\Model\Entity\BexioMatch[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
* @method \App\Model\Entity\BexioMatch[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
*
* @mixin \Cake\ORM\Behavior\TimestampBehavior
*/
class BexioMatchesTable extends Table
{
  /**
  * Initialize method
  *
  * @param array $config The configuration for the Table.
  * @return void
  */
  public function initialize(array $config): void
  {
    parent::initialize($config);

    $this->setTable('bexio_matches');
    $this->setDisplayField('id');
    $this->setPrimaryKey('id');
    $this->addBehavior('Search.Search');
    $this->searchManager()
    ->add('q', 'Search.Like', [
      'before' => true,
      'after' => true,
      'mode' => 'or',
      'comparison' => 'LIKE',
      'wildcardAny' => '*',
      'wildcardOne' => '?',
      'fields' => ['id']
    ]);
    $this->addBehavior('Timestamp');
  }

  /**
  * Default validation rules.
  *
  * @param \Cake\Validation\Validator $validator Validator instance.
  * @return \Cake\Validation\Validator
  */
  public function validationDefault(Validator $validator): Validator
  {
    $validator
    ->uuid('id')
    ->allowEmptyString('id', null, 'create');

    $validator
    ->scalar('model')
    ->maxLength('model', 255)
    ->requirePresence('model', 'create')
    ->notEmptyString('model');

    return $validator;
  }
}
