<?php
declare(strict_types=1);

use Migrations\AbstractSeed;

/**
 * Users seed.
 */
class UsersSeed extends AbstractSeed
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
                'username' => $faker->userName,
                'avatar' => 'detail.jpg',
                'password' => sha1($faker->password),
                'email' => $faker->email,
                'phonenumber' => 123,
                'point_user' => 1,
                'role_id' => 1,
                'created_date' => date('Y-m-d H:i:s'),
                'updated_date' => date('Y-m-d H:i:s'),
            ];

        }
        $table = $this->table('users');
        $table->insert($data)->save();
    }
}
