<?php

use think\migration\Migrator;
use think\migration\db\Column;

class CreateVerificationCodesTable extends Migrator
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
        $table = $this->table('verification_codes', ['engine' => 'InnoDB', 'collation' => 'utf8mb4_general_ci', 'comment' => '验证码表']);
        $table->addColumn('phone', 'string', ['limit' => 11, 'null' => false, 'default' => '', 'comment' => '手机号'])
              ->addColumn('code', 'string', ['limit' => 6, 'null' => false, 'default' => '', 'comment' => '验证码'])
              ->addColumn('type', 'string', ['limit' => 50, 'null' => false, 'default' => '', 'comment' => '类型：register注册、reset_password重置密码'])
              ->addColumn('used', 'integer', ['limit' => 1, 'null' => false, 'default' => 0, 'comment' => '是否已使用：1是 0否'])
              ->addColumn('expire_time', 'integer', ['limit' => 11, 'null' => false, 'default' => 0, 'comment' => '过期时间'])
              ->addColumn('create_time', 'integer', ['limit' => 11, 'null' => false, 'default' => 0, 'comment' => '创建时间'])
              ->addIndex(['phone', 'type'], ['name' => 'phone_type'])
              ->addIndex(['expire_time'], ['name' => 'expire_time'])
              ->create();
    }
}
