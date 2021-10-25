<?php

declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * User Entity
 *
 * @property int $id
 * @property string $username
 * @property string $avatar
 * @property string $password
 * @property string $email
 * @property int $phonenumber
 * @property int $point_user
 * @property bool $del_flag
 * @property int $role_id
 * @property \Cake\I18n\FrozenTime $created_date
 * @property \Cake\I18n\FrozenTime $updated_date
 *
 * @property \App\Model\Entity\Role $role
 * @property \App\Model\Entity\Image[] $images
 * @property \App\Model\Entity\Order[] $orders
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
		'username' => true,
		'avatar' => true,
		'address' => true,
		'password' => true,
		'email' => true,
		'phonenumber' => true,
		'point_user' => true,
		'del_flag' => true,
		'role_id' => true,
		'created_date' => true,
		'updated_date' => true,
		'role' => true,
		'images' => true,
		'orders' => true,
	];

	/**
	 * Fields that are excluded from JSON versions of the entity.
	 *
	 * @var array
	 */
	protected $_hidden = [
		// 'password',
	];
}
