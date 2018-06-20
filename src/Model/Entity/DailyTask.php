<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Dailytask Entity
 *
 * @property int $id
 * @property string $department
 * @property \Cake\I18n\FrozenDate $date
 * @property string $date_time
 * @property string $caption
 * @property string $detail
 * @property int $work_time_h
 * @property int $work_time_m
 * @property int $user_id
 * @property int $store_id
 *
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\Store $store
 */
class Dailytask extends Entity
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
        'date' => true,
        'date_time' => true,
        'caption' => true,
        'detail' => true,
        'work_time_h' => true,
        'work_time_m' => true,
        'user_id' => true,
        'store_id' => true,
        'user' => true,
        'store' => true
    ];
}
