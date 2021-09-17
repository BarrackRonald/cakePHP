<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Order Entity
 *
 * @property int $id
 * @property string $order_name
 * @property string $email
 * @property int $phonenumber
 * @property string $address
 * @property \Cake\I18n\FrozenTime $date_order
 * @property int $total_point
 * @property int $total_quantity
 * @property int $total_amount
 * @property bool $status
 * @property int $user_id
 * @property \Cake\I18n\FrozenTime $created_at
 * @property \Cake\I18n\FrozenTime $updated_at
 *
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\Orderdetail[] $orderdetails
 */
class Order extends Entity
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
        'order_name' => true,
        'email' => true,
        'phonenumber' => true,
        'address' => true,
        'date_order' => true,
        'total_point' => true,
        'total_quantity' => true,
        'total_amount' => true,
        'status' => true,
        'user_id' => true,
        'created_at' => true,
        'updated_at' => true,
        'user' => true,
        'orderdetails' => true,
    ];
}
