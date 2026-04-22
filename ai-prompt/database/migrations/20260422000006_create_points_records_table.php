<?php

use think\migration\Migrator;
use think\migration\db\Column;

class CreatePointsRecordsTable extends Migrator
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
        $table = $this->table('points_records', ['engine' => 'InnoDB', 'collation' => 'utf8mb4_general_ci', 'comment' => '积分记录表']);
        $table->addColumn('user_id', 'integer', ['limit' => 11, 'null' => false, 'default' => 0, 'comment' => '用户ID'])
              ->addColumn('type', 'integer', ['limit' => 1, 'null' => false, 'default' => 1, 'comment' => '类型：1收入 2支出'])
              ->addColumn('points', 'integer', ['limit' => 11, 'null' => false, 'default' => 0, 'comment' => '积分数量'])
              ->addColumn('balance', 'integer', ['limit' => 11, 'null' => false, 'default' => 0, 'comment' => '变动后余额'])
              ->addColumn('source_type', 'string', ['limit' => 50, 'null' => false, 'default' => '', 'comment' => '来源类型：register注册、publish_prompt发布提示词、publish_workflow发布工作流、view_prompt查看提示词、view_workflow查看工作流'])
              ->addColumn('source_id', 'integer', ['limit' => 11, 'null' => false, 'default' => 0, 'comment' => '来源ID（如提示词ID、工作流ID等）'])
              ->addColumn('description', 'string', ['limit' => 255, 'null' => false, 'default' => '', 'comment' => '描述'])
              ->addColumn('create_time', 'integer', ['limit' => 11, 'null' => false, 'default' => 0, 'comment' => '创建时间'])
              ->addIndex(['user_id'], ['name' => 'user_id'])
              ->addIndex(['type'], ['name' => 'type'])
              ->addIndex(['source_type', 'source_id'], ['name' => 'source'])
              ->create();
    }
}
