<?php
declare(strict_types=1);

namespace Trois\Bexio\Model\Entity;

use Cake\ORM\Entity;

/**
 * BexioMatch Entity
 *
 * @property string $id
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 * @property string $model
 * @property string $foreign_id
 * @property string $bexio_id
 *
 * @property \App\Model\Entity\Foreign $foreign
 * @property \App\Model\Entity\Bexio $bexio
 */
class BexioMatch extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
     protected $_accessible = [
       '*' => true,
      'id' => false,
            ];
}
