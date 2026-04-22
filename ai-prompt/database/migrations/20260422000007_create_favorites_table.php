<?php

use think\migration\Migrator;
use think\migration\db\Column;

class CreateFavoritesTable extends Migrator
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
        $table = $this->table('favorites', ['engine' => 'InnoDB', 'collation' => 'utf8mb4_general_ci', 'comment' => '收藏表']);
        $table->addColumn('user_id', 'integer', ['limit' => 11, 'null' => false, 'default' => 0, 'comment' => '用户ID'])
              ->addColumn('target_type', 'string', ['limit' => 50, 'null' => false, 'default' => '', 'comment' => '目标类型：prompt提示词、workflow工作流'])
              ->addColumn('target_id', 'integer', ['limit' => 11, 'null' => false, 'default' => 0, 'comment' => '目标ID'])
              ->addColumn('create_time', 'integer', ['limit' => 11, 'null' => false, 'default' => 0, 'comment' => '创建时间'])
              ->addIndex(['user_id', 'target_type', 'target_id'], ['unique' => true, 'name' => 'user_target_unique'])
              ->addIndex(['target_type', 'target_id'], ['name' => 'target'])
              ->create();
    }
}
