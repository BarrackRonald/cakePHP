<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class CreateProducts extends AbstractMigration
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
        $table = $this->table('products');
        $table->addColumn('product_name', 'string', [
            'limit' => 70,
        ]);
        $table->addColumn('description', 'string', [
            'limit' => 300,
            'default' => null,
        ]);
        $table->addColumn('amount_product', 'integer', [
            'limit' => 12,
            'default' => 000,
        ]);
        $table->addColumn('point_product', 'integer', [
            'limit' => 12,
            'default' => 0,
        ]);
        $table->addColumn('del_flag', 'boolean', [
            'default' => 0,
        ]);

        $table->addColumn('status', 'boolean', [
            'default' => 1,
        ]);
        $table->addColumn('category_id', 'integer')->addForeignKey('category_id', 'categories', 'id');


        $table->addColumn('created_date', 'datetime', [
            'default' => null,
        ]);
        $table->addColumn('updated_date', 'datetime', [
            'default' => null,
        ]);
        $table->create();
    }
}
