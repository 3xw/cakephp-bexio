<?php
namespace Trois\Bexio\Model\Behavior;

use ArrayObject;
use Cake\Event\Event;
use Cake\Datasource\EntityInterface;
use Cake\ORM\Behavior;
use Cake\ORM\Table;
use Cake\Utility\Inflector;
use Trois\Bexio\Utility\ModelLoader;

class SyncWithBexioBehavior extends Behavior
{
  //use \Cake\Datasource\ModelAwareTrait;

  protected $_defaultConfig = [
    'endpoint' => 'Trois/Bexio.Projetcs',
    'delete' => false
  ];

  protected function loadModel($modelClass = null, $modelType = null)
  {
    return (new ModelLoader)->loadModel($modelClass, $modelType);
  }

  public function afterSave(Event $event, EntityInterface $entity, ArrayObject $options)
  {
    if(empty($options['EnableBexioSync'])) return;

    $this->loadModel($this->getConfig('endpoint'),'Endpoint')->saveFromCakeEntity($entity, $entity->getSource(), $options->getArrayCopy());
  }
}
