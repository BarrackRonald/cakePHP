<?php

declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Orders Model
 *
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 * @property \App\Model\Table\OrderdetailsTable&\Cake\ORM\Association\HasMany $Orderdetails
 *
 * @method \App\Model\Entity\Order newEmptyEntity()
 * @method \App\Model\Entity\Order newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Order[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Order get($primaryKey, $options = [])
 * @method \App\Model\Entity\Order findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Order patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Order[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Order|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Order saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Order[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Order[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Order[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Order[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class OrdersTable extends Table
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

		$this->setTable('orders');
		$this->setDisplayField('id');
		$this->setPrimaryKey('id');

		$this->belongsTo('Users', [
			'foreignKey' => 'user_id',
			'joinType' => 'INNER',
		]);
		$this->hasMany('Orderdetails', [
			'foreignKey' => 'order_id',
		]);
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
			->integer('id')
			->allowEmptyString('id', null, 'create');

		$validator
			->scalar('order_name')
			->add('order_name', [
				'length' => [
					'rule' => ['maxLength', 70],
					'message' => 'Tên Đơn hàng dưới 70 ký tự.',
				],
			])
			->requirePresence('order_name', 'create')
			->notEmptyString('order_name', 'Tên đơn hàng không được để trống.');

		$validator
			->email('email')
			->requirePresence('email', 'create')
			->notEmptyString('email', 'Email không được để trống.');

		$validator
			->integer('phonenumber')
			->requirePresence('phonenumber', 'create')
			->notEmptyString('phonenumber', 'Số điện thoại không được để trống.')
			->add('phonenumber', [
				'length' => [
					'rule' => ['maxLength', 10],
					'message' => 'Số điện thoại phải là 10 ký tự.',
				],
				'length' => [
					'rule' => ['minLength', 10],
					'message' => 'Số điện thoại phải là 10 ký tự.',
				]
			]);

		$validator
			->scalar('address')
			->add('address', [
				'length' => [
					'rule' => ['maxLength', 100],
					'message' => 'Địa chỉ phải dưới 100 ký tự.',
				],
			])
			->requirePresence('address', 'create')
			->notEmptyString('address', 'Địa chỉ không được để trống.');

		$validator
			->dateTime('date_order')
			->requirePresence('date_order', 'create')
			->notEmptyDateTime('date_order', 'Ngày Đặt hàng không được để trống.');

		$validator
			->integer('total_point')
			->requirePresence('total_point', 'create')
			->notEmptyString('total_point', 'Tổng point không được để trống.');

		$validator
			->integer('total_quantity')
			->requirePresence('total_quantity', 'create')
			->notEmptyString('total_quantity', 'Tổng số lượng không được để trống.');

		$validator
			->integer('total_amount')
			->requirePresence('total_amount', 'create')
			->notEmptyString('total_amount', 'Tổng giá không được để trống.');

		$validator
			->integer('status')
			->notEmptyString('status', 'Trạng thái không được để trống');

		$validator
			->dateTime('created_date')
			->requirePresence('created_date', 'create')
			->notEmptyDateTime('created_date');

		$validator
			->dateTime('updated_date')
			->requirePresence('updated_date', 'create')
			->notEmptyDateTime('updated_date');

		return $validator;
	}

	/**
	 * Returns a rules checker object that will be used for validating
	 * application integrity.
	 *
	 * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
	 * @return \Cake\ORM\RulesChecker
	 */
	public function buildRules(RulesChecker $rules): RulesChecker
	{
		$rules->add($rules->existsIn(['user_id'], 'Users'), ['errorField' => 'user_id']);
		return $rules;
	}
}
