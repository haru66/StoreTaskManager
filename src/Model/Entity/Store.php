<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Store Entity
 *
 * @property int $id
 * @property string $password
 * @property string $name
 * @property int $area
 *
 * @property \App\Model\Entity\Dailytask[] $dailytasks
 * @property \App\Model\Entity\Department[] $departments
 * @property \App\Model\Entity\Message[] $messages
 * @property \App\Model\Entity\Task[] $tasks
 */
class Store extends Entity
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
        'password' => true,
        'name' => true,
        'area' => true,
        'dailytasks' => true,
        'departments' => true,
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
