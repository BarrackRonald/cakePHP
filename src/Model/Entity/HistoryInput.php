<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * HistoryInput Entity
 *
 * @property int $id
 * @property string $username
 * @property string $product_name
 * @property int $quantity_input
 * @property int $product_id
 * @property int $user_id
 * @property bool $status
 * @property \Cake\I18n\FrozenTime $created_date
 * @property \Cake\I18n\FrozenTime $updated_date
 *
 * @property \App\Model\Entity\Product $product
 * @property \App\Model\Entity\User $user
 */
class HistoryInput extends Entity
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
        'product_name' => true,
        'quantity_input' => true,
        'product_id' => true,
        'user_id' => true,
        'status' => true,
        'created_date' => true,
        'updated_date' => true,
        'product' => true,
        'user' => true,
    ];
}
