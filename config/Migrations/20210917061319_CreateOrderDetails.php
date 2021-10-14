<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class CreateOrderDetails extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     * @return void
     */
    public function change()
    {
        $table = $this->table('orderDetails');
        $table->addColumn('quantity_orderDetails', 'integer', [
            'limit' => 15,
            'default' => 0,
        ]);
        $table->addColumn('amount_orderDetails', 'integer', [
            'limit' => 15,
            'default' => 000,
        ]);
        $table->addColumn('point_orderDetail', 'integer', [
            'limit' => 15,
            'default' => 0,
        ]);
        $table->addColumn('product_id', 'integer')->addForeignKey('product_id', 'products', 'id');
        $table->addColumn('order_id', 'integer')->addForeignKey('order_id', 'orders', 'id');


        $table->addColumn('created_date', 'timestamp', [
            'default' => 'CURRENT_TIMESTAMP'
        ]);
        $table->addColumn('updated_date', 'timestamp', [
            'default' => 'CURRENT_TIMESTAMP'
        ]);
        $table->create();
    }
}
