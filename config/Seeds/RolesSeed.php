<?php
declare(strict_types=1);

use Migrations\AbstractSeed;

/**
 * Roles seed.
 */
class RolesSeed extends AbstractSeed
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
        $data = [];
        for ($i = 0; $i < 10; $i++) {
            $data[] = [
                'role_name'      => $faker->userName,
                'created_at'       => date('Y-m-d H:i:s'),
                'updated_at'       => date('Y-m-d H:i:s'),
                // 'username'      => $faker->userName,
                // 'password'      => sha1($faker->password),
                // 'password_salt' => sha1('foo'),
                // 'email'         => $faker->email,
                // 'first_name'    => $faker->firstName,
                // 'last_name'     => $faker->lastName,
                // 'created'       => date('Y-m-d H:i:s'),
            ];

        }

        $this->insert('roles', $data);
    }
}
