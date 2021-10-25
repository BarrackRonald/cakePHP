<?php

declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Product Entity
 *
 * @property int $id
 * @property string $product_name
 * @property string $description
 * @property int $amount_product
 * @property int $point_product
 * @property bool $del_flag
 * @property bool $status
 * @property int $category_id
 * @property \Cake\I18n\FrozenTime $created_date
 * @property \Cake\I18n\FrozenTime $updated_date
 *
 * @property \App\Model\Entity\Category $category
 * @property \App\Model\Entity\Image[] $images
 * @property \App\Model\Entity\Orderdetail[] $orderdetails
 */
class Product extends Entity
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
		'product_name' => true,
		'description' => true,
		'amount_product' => true,
		'point_product' => true,
		'del_flag' => true,
		'status' => true,
		'category_id' => true,
		'created_date' => true,
		'updated_date' => true,
		'category' => true,
		'images' => true,
		'orderdetails' => true,
	];
}
