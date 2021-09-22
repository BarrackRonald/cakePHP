<?php
declare(strict_types=1);

use Migrations\AbstractSeed;

/**
 * Orders seed.
 */
class OrdersSeed extends AbstractSeed
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
                'order_name' => $faker->userName,
                'email' => $faker->email,
                'phonenumber' => 1234567,
                'address' => $faker->address,
                'date_order' => date('Y-m-d H:i:s'),
                'user_id' => 1,
                'created_date' => date('Y-m-d H:i:s'),
                'updated_date' => date('Y-m-d H:i:s'),
            ];

        }

        $table = $this->table('orders');
        $table->insert($data)->save();
    }
}
