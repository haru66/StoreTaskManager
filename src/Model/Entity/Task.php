<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Task Entity
 *
 * @property int $id
 * @property string $caption
 * @property string $detail
 * @property string $situation
 * @property \Cake\I18n\FrozenDate $update_date
 * @property string $update_date_time
 * @property string $update_user
 * @property string $department
 * @property \Cake\I18n\FrozenDate $created_date
 * @property string $created_date_time
 * @property \Cake\I18n\FrozenDate $due_date
 * @property int $author
 * @property string $worker
 * @property int $completed
 * @property int $completed_user
 * @property int $priority
 * @property string $user_id
 * @property int $store_id
 *
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\Store $store
 */
class Task extends Entity
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
        'caption' => true,
        'detail' => true,
        'situation' => true,
        'update_date' => true,
        'update_date_time' => true,
        'update_user' => true,
        'department' => true,
        'created_date' => true,
        'created_date_time' => true,
        'due_date' => true,
        'author' => true,
        'worker' => true,
        'completed' => true,
        'completed_user' => true,
        'priority' => true,
        'user_id' => true,
        'store_id' => true,
        'user' => true,
        'store' => true
    ];
}
