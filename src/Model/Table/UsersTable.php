<?php

declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Users Model
 *
 * @property \App\Model\Table\RolesTable&\Cake\ORM\Association\BelongsTo $Roles
 * @property \App\Model\Table\ImagesTable&\Cake\ORM\Association\HasMany $Images
 * @property \App\Model\Table\OrdersTable&\Cake\ORM\Association\HasMany $Orders
 *
 * @method \App\Model\Entity\User newEmptyEntity()
 * @method \App\Model\Entity\User newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\User[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\User get($primaryKey, $options = [])
 * @method \App\Model\Entity\User findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\User patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\User[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\User|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\User saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class UsersTable extends Table
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

		$this->setTable('users');
		$this->setDisplayField('id');
		$this->setPrimaryKey('id');

		$this->belongsTo('Roles', [
			'foreignKey' => 'role_id',
			'joinType' => 'INNER',
		]);
		$this->hasMany('Images', [
			'foreignKey' => 'user_id',
		]);
		$this->hasMany('Orders', [
			'foreignKey' => 'user_id',
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
			->scalar('username')
			->add('username', [
				'length' => [
					'rule' => ['maxLength', 100],
					'message' => 'Tên tối đa 100 ký tự.',
				],
			])
			->requirePresence('username', 'create')
			->notEmptyString('username', 'Tên không thể để trống');

		$validator
			->scalar('avatar')
			->maxLength('avatar', 200)
			->requirePresence('avatar', 'create')
			->notEmptyString('avatar', 'Avatar không thể để trống');

		$validator
			->scalar('address')
			->maxLength('address', 200)
			->requirePresence('address', 'create')
			->notEmptyString('address', 'Địa chỉ không thể để trống.');

		$validator
			->scalar('password')
			->add('password', [
				'length' => [
					'rule' => ['maxLength', 90],
					'message' => 'Mật khẩu tối đa 90 ký tự.',
				],
			])
			->requirePresence('password', 'create')
			->notEmptyString('password', 'Mật khẩu không thể để trống.')
			->add('password', [
				'validFormat' => [
					'rule' => ['custom', '/^(?=.*[0-9])(?=.*[A-Z])(?=.*[!@#$%^&*])[a-zA-Z0-9!@#$%^&*]{8,}$/'],
					'message' => 'Mật khẩu phải ít nhất 8 ký tự (có chữ hoa, chữ thường và số và ký tự đặc biệt).'
				]
			]);

		$validator
			->requirePresence('email', 'create')
			->notEmptyString('email', 'Email không thể để trống.')
			->add('email', [
				'validFormat' => [
					'rule' => ['custom', '/^[a-zA-Z0-9]+@[a-z0-9]+\.[a-z]{2,5}$/'],
					'message' => 'Email không đúng định dạng( Email không chứa ký tự tiếng việt).'
				]
			])
			->add(
                'email',
                ['unique' => [
                    'rule' => 'validateUnique',
                    'provider' => 'table',
                    'message' => 'Địa chỉ Email đã tồn tại.']
                ]
            );

		$validator
			->integer('phonenumber')
			->requirePresence('phonenumber', 'create')
			->notEmptyString('phonenumber', 'Số điện thoại không thể để trống.')
			->add('phonenumber', [
				'validFormat' => [
					'rule' => ['custom', '/^(0)([0-9]){9}$/'],
					'message' => 'Số điện thoại không đúng định dạng(10 ký tự và bắt đầu bằng "0").'
				]
			]);

		$validator
			->integer('point_user')
			->notEmptyString('point_user', 'Point không thể để trống.');

		$validator
			->integer('del_flag')
			->notEmptyString('del_flag');

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
		$rules->add($rules->isUnique(['email']), ['errorField' => 'email']);
		$rules->add($rules->existsIn(['role_id'], 'Roles'), ['errorField' => 'role_id']);
		return $rules;
	}

	public function validationCustom()
	{
		$validator = new Validator();
		$validator
			->integer('id')
			->allowEmptyString('id', null, 'create');

		$validator
			->scalar('username')
			->add('username', [
				'length' => [
					'rule' => ['maxLength', 100],
					'message' => 'Tên tối đa 100 ký tự.',
				],
			])
			->requirePresence('username', 'create')
			->notEmptyString('username', 'Tên không thể để trống');

		$validator
			->scalar('avatar')
			->maxLength('avatar', 200)
			->requirePresence('avatar', 'create')
			->notEmptyString('avatar', 'Avatar không thể để trống');

		$validator
			->scalar('address')
			->maxLength('address', 200)
			->requirePresence('address', 'create')
			->notEmptyString('address', 'Địa chỉ không thể để trống.');

		$validator
			->scalar('password')
			->add('password', [
				'length' => [
					'rule' => ['maxLength', 90],
					'message' => 'Mật khẩu tối đa 90 ký tự.',
				],
			])
			->requirePresence('password', 'create')
			->notEmptyString('password', 'Mật khẩu không thể để trống.');

		$validator
			->requirePresence('email', 'create')
			->notEmptyString('email', 'Email không thể để trống.')
			->add('email', [
				'validFormat' => [
					'rule' => ['custom', '/^[a-zA-Z0-9]+@[a-z0-9]+\.[a-z]{2,5}$/'],
					'message' => 'Email không đúng định dạng( Email không chứa ký tự tiếng việt).'
				]
			])
			->add(
                'email',
                ['unique' => [
                    'rule' => 'validateUnique',
                    'provider' => 'table',
                    'message' => 'Địa chỉ Email đã tồn tại.']
                ]
            );

		$validator
			->integer('phonenumber')
			->requirePresence('phonenumber', 'create')
			->notEmptyString('phonenumber', 'Số điện thoại không thể để trống.')
			->add('phonenumber', [
				'validFormat' => [
					'rule' => ['custom', '/^(0)([0-9]){9}$/'],
					'message' => 'Số điện thoại không đúng định dạng(10 ký tự và bắt đầu bằng "0").'
				]
			]);

		$validator
			->integer('point_user')
			->notEmptyString('point_user', 'Point không thể để trống.');

		$validator
			->integer('del_flag')
			->notEmptyString('del_flag');

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
}
