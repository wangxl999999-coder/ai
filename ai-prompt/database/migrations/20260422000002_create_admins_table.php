<?php

use think\migration\Migrator;
use think\migration\db\Column;

class CreateAdminsTable extends Migrator
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {
        $table = $this->table('admins', ['engine' => 'InnoDB', 'collation' => 'utf8mb4_general_ci', 'comment' => '管理员表']);
        $table->addColumn('username', 'string', ['limit' => 50, 'null' => false, 'default' => '', 'comment' => '用户名'])
              ->addColumn('password', 'string', ['limit' => 255, 'null' => false, 'default' => '', 'comment' => '密码'])
              ->addColumn('nickname', 'string', ['limit' => 50, 'null' => false, 'default' => '', 'comment' => '昵称'])
              ->addColumn('avatar', 'string', ['limit' => 255, 'null' => false, 'default' => '/static/images/default_admin_avatar.png', 'comment' => '头像'])
              ->addColumn('status', 'integer', ['limit' => 1, 'null' => false, 'default' => 1, 'comment' => '状态：1正常 0禁用'])
              ->addColumn('last_login_time', 'integer', ['limit' => 11, 'null' => false, 'default' => 0, 'comment' => '最后登录时间'])
              ->addColumn('last_login_ip', 'string', ['limit' => 45, 'null' => false, 'default' => '', 'comment' => '最后登录IP'])
              ->addColumn('create_time', 'integer', ['limit' => 11, 'null' => false, 'default' => 0, 'comment' => '创建时间'])
              ->addColumn('update_time', 'integer', ['limit' => 11, 'null' => false, 'default' => 0, 'comment' => '更新时间'])
              ->addIndex(['username'], ['unique' => true, 'name' => 'username_unique'])
              ->create();
    }
}
