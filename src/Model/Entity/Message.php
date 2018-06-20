<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Message Entity
 *
 * @property int $id
 * @property int $department
 * @property string $detail
 * @property string $date_time
 * @property int $user_id
 * @property \Cake\I18n\FrozenDate $date
 * @property int $store_id
 *
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\Store $store
 */
class Message extends Entity
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
        'department' => true,
        'detail' => true,
        'date_time' => true,
        'user_id' => true,
        'date' => true,
        'store_id' => true,
        'user' => true,
        'store' => true
    ];
}
