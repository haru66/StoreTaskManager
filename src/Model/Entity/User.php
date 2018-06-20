<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * User Entity
 *
 * @property int $id
 * @property int $role
 * @property int $require_password
 * @property string $department
 * @property string $name
 * @property string $password
 * @property int $deleted
 * @property string $memo
 * @property int $store
 *
 * @property \App\Model\Entity\Dailytask[] $dailytasks
 * @property \App\Model\Entity\Message[] $messages
 * @property \App\Model\Entity\Task[] $tasks
 */
class User extends Entity
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
        'role' => true,
        'require_password' => true,
        'department' => true,
        'name' => true,
        'password' => true,
        'deleted' => true,
        'memo' => true,
        'store' => true,
        'dailytasks' => true,
        'messages' => true,
        'tasks' => true
    ];

    /**
     * Fields that are excluded from JSON versions of the entity.
     *
     * @var array
     */
    protected $_hidden = [
        'password'
    ];
}
