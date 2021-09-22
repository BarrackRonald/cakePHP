<?php
declare(strict_types=1);

use Migrations\AbstractSeed;

/**
 * Images seed.
 */
class ImagesSeed extends AbstractSeed
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
                'image_name' => $faker->userName,
                'image_type' => 'Slider',
                'file' => 'https://cdn.tgdd.vn/Products/Images/42/230529/iphone-13-pro-max-sierra-blue-600x600.jpg',
                'user_id' => 1,
                'product_id' => 1,
                'created_date' => date('Y-m-d H:i:s'),
                'updated_date' => date('Y-m-d H:i:s'),
            ];

        }

        $table = $this->table('images');
        $table->insert($data)->save();
    }
}
