<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Dailytasks Model
 *
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\BelongsTo $Users
 * @property \App\Model\Table\StoresTable|\Cake\ORM\Association\BelongsTo $Stores
 *
 * @method \App\Model\Entity\Dailytask get($primaryKey, $options = [])
 * @method \App\Model\Entity\Dailytask newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Dailytask[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Dailytask|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Dailytask|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Dailytask patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Dailytask[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Dailytask findOrCreate($search, callable $callback = null, $options = [])
 */
class DailytasksTable extends Table
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

        $this->setTable('dailytasks');
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
            ->scalar('department')
            ->allowEmpty('department');

        $validator
            ->date('date')
            ->requirePresence('date', 'create')
            ->notEmpty('date');

        $validator
            ->scalar('date_time')
            ->requirePresence('date_time', 'create')
            ->notEmpty('date_time');

        $validator
            ->scalar('caption')
            ->requirePresence('caption', 'create')
            ->notEmpty('caption');

        $validator
            ->scalar('detail')
            ->allowEmpty('detail');

        $validator
            ->integer('work_time_h')
            ->allowEmpty('work_time_h');

        $validator
            ->integer('work_time_m')
            ->requirePresence('work_time_m', 'create')
            ->notEmpty('work_time_m');

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
