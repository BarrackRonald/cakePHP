<?php

declare(strict_types=1);

use Migrations\AbstractMigration;

class CreateHistoryInput extends AbstractMigration
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
		$table = $this->table('history_input');
		$table->addColumn('username', 'string', [
			'limit' => 70,
		]);
		$table->addColumn('product_name', 'string', [
			'limit' => 70,
		]);
		$table->addColumn('quantity_input', 'integer', [
			'limit' => 12,
			'default' => 0,
		]);

		$table->addColumn('product_id', 'integer')->addForeignKey('product_id', 'products', 'id');

		$table->addColumn('user_id', 'integer')->addForeignKey('user_id', 'users', 'id');

		$table->addColumn('status', 'boolean', [
			'default' => 0,
		]);

		$table->addColumn('created_date', 'timestamp', [
			'default' => 'CURRENT_TIMESTAMP'
		]);
		$table->addColumn('updated_date', 'timestamp', [
			'default' => 'CURRENT_TIMESTAMP'
		]);
		$table->create();
	}
}
