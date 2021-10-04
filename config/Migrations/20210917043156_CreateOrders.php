<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class CreateOrders extends AbstractMigration
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
        $table = $this->table('orders');
        $table->addColumn('order_name', 'string', [
            'limit' => 70,
        ]);
        $table->addColumn('email', 'string', [
            'limit' => 100,
        ]);
        $table->addColumn('phonenumber', 'integer', [
            'limit' => 10,
        ]);
        $table->addColumn('address', 'string', [
            'limit' => 100,
        ]);
        $table->addColumn('date_order', 'datetime');
        $table->addColumn('total_point', 'integer');
        $table->addColumn('total_quantity', 'integer', [
            'limit' => 20,
        ]);
        $table->addColumn('total_amount', 'integer', [
            'limit' => 20,
        ]);
        $table->addColumn('status', 'integer', [
            'limit' => 5,
            'default' => 0,
        ]);
        $table->addColumn('user_id', 'integer')->addForeignKey('user_id', 'users', 'id');


        $table->addColumn('created_date', 'datetime', [
            'default' => null,
        ]);
        $table->addColumn('updated_date', 'datetime', [
            'default' => null,
        ]);
        $table->create();

    }
}
