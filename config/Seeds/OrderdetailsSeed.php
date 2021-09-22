<?php
declare(strict_types=1);

use Migrations\AbstractSeed;

/**
 * Orderdetails seed.
 */
class OrderdetailsSeed extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeds is available here:
     * https://book.cakephp.org/phinx/0/en/seeding.html
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();
        for ($i = 0; $i < 10; $i++) {
            $data = [
                'quantity_orderDetails' => 123,
                'amount_orderDetails' => 123,
                'point_orderDetail' => 123,
                'product_id' => 1,
                'order_id' => 1,
                'created_date' => date('Y-m-d H:i:s'),
                'updated_date' => date('Y-m-d H:i:s'),
            ];

        }

        $table = $this->table('orderdetails');
        $table->insert($data)->save();
    }
}
