<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class CreateImages extends AbstractMigration
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
        $table = $this->table('images');
        $table->addColumn('image_name', 'string', [
            'limit' => 70,
        ]);

        $table->addColumn('image_type', 'string', [
            'limit' => 70,
        ]);

        $table->addColumn('file', 'string', [
            'limit' => 300,
            'default' => null,
        ]);

        $table->addColumn('product_id', 'integer')->addForeignKey('product_id', 'products', 'id');

        $table->addColumn('created_date', 'datetime', [
            'default' => null,
        ]);
        $table->addColumn('updated_date', 'datetime', [
            'default' => null,
        ]);
        $table->create();
    }
}
