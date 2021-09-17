<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Orderdetail Entity
 *
 * @property int $id
 * @property int $quantity_orderDetails
 * @property int $amount_orderDetails
 * @property int $orderDetail_point
 * @property int $product_id
 * @property int $order_id
 * @property \Cake\I18n\FrozenTime $created_at
 * @property \Cake\I18n\FrozenTime $updated_at
 *
 * @property \App\Model\Entity\Product $product
 * @property \App\Model\Entity\Order $order
 */
class Orderdetail extends Entity
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
        'quantity_orderDetails' => true,
        'amount_orderDetails' => true,
        'orderDetail_point' => true,
        'product_id' => true,
        'order_id' => true,
        'created_at' => true,
        'updated_at' => true,
        'product' => true,
        'order' => true,
    ];
}
