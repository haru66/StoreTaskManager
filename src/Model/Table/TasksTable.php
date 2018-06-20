<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Tasks Model
 *
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\BelongsTo $Users
 * @property \App\Model\Table\StoresTable|\Cake\ORM\Association\BelongsTo $Stores
 *
 * @method \App\Model\Entity\Task get($primaryKey, $options = [])
 * @method \App\Model\Entity\Task newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Task[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Task|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Task|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Task patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Task[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Task findOrCreate($search, callable $callback = null, $options = [])
 */
class TasksTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('tasks');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id'
        ]);
        $this->belongsTo('Stores', [
            'foreignKey' => 'store_id'
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmpty('id', 'create')
            ->add('id', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        $validator
            ->scalar('caption')
            ->requirePresence('caption', 'create')
            ->notEmpty('caption');

        $validator
            ->scalar('detail')
            ->allowEmpty('detail');

        $validator
            ->scalar('situation')
            ->allowEmpty('situation');

        $validator
            ->date('update_date')
            ->allowEmpty('update_date');

        $validator
            ->scalar('update_date_time')
            ->allowEmpty('update_date_time');

        $validator
            ->scalar('update_user')
            ->allowEmpty('update_user');

        $validator
            ->scalar('department')
            ->allowEmpty('department');

        $validator
            ->date('created_date')
            ->allowEmpty('created_date');

        $validator
            ->scalar('created_date_time')
            ->allowEmpty('created_date_time');

        $validator
            ->date('due_date')
            ->allowEmpty('due_date');

        $validator
            ->integer('author')
            ->allowEmpty('author');

        $validator
            ->scalar('worker')
            ->requirePresence('worker', 'create')
            ->notEmpty('worker');

        $validator
            ->allowEmpty('completed');

        $validator
            ->integer('completed_user')
            ->requirePresence('completed_user', 'create')
            ->notEmpty('completed_user');

        $validator
            ->integer('priority')
            ->allowEmpty('priority');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->isUnique(['id']));
        $rules->add($rules->existsIn(['user_id'], 'Users'));
        $rules->add($rules->existsIn(['store_id'], 'Stores'));

        return $rules;
    }
}
