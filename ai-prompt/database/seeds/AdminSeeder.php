<?php

use think\migration\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     */
    public function run()
    {
        $data = [
            [
                'username' => 'admin',
                'password' => password_hash('admin123456', PASSWORD_DEFAULT),
                'nickname' => '超级管理员',
                'avatar' => '/static/images/default_admin_avatar.png',
                'status' => 1,
                'last_login_time' => 0,
                'last_login_ip' => '',
                'create_time' => time(),
                'update_time' => time(),
            ],
        ];

        $table = $this->table('admins');
        $table->insert($data)->save();
    }
}
