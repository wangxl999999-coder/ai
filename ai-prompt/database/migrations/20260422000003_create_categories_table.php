<?php

use think\migration\Migrator;
use think\migration\db\Column;

class CreateCategoriesTable extends Migrator
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
        $table = $this->table('categories', ['engine' => 'InnoDB', 'collation' => 'utf8mb4_general_ci', 'comment' => '分类表']);
        $table->addColumn('name', 'string', ['limit' => 50, 'null' => false, 'default' => '', 'comment' => '分类名称'])
              ->addColumn('type', 'integer', ['limit' => 1, 'null' => false, 'default' => 1, 'comment' => '类型：1提示词 2工作流'])
              ->addColumn('sort', 'integer', ['limit' => 11, 'null' => false, 'default' => 0, 'comment' => '排序'])
              ->addColumn('status', 'integer', ['limit' => 1, 'null' => false, 'default' => 1, 'comment' => '状态：1启用 0禁用'])
              ->addColumn('create_time', 'integer', ['limit' => 11, 'null' => false, 'default' => 0, 'comment' => '创建时间'])
              ->addColumn('update_time', 'integer', ['limit' => 11, 'null' => false, 'default' => 0, 'comment' => '更新时间'])
              ->addIndex(['type', 'status'], ['name' => 'type_status'])
              ->create();
    }
}
