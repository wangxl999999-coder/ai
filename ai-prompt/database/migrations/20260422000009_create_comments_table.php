<?php

use think\migration\Migrator;
use think\migration\db\Column;

class CreateCommentsTable extends Migrator
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
        $table = $this->table('comments', ['engine' => 'InnoDB', 'collation' => 'utf8mb4_general_ci', 'comment' => '评论表']);
        $table->addColumn('user_id', 'integer', ['limit' => 11, 'null' => false, 'default' => 0, 'comment' => '评论用户ID'])
              ->addColumn('target_type', 'string', ['limit' => 50, 'null' => false, 'default' => '', 'comment' => '目标类型：prompt提示词、workflow工作流'])
              ->addColumn('target_id', 'integer', ['limit' => 11, 'null' => false, 'default' => 0, 'comment' => '目标ID'])
              ->addColumn('parent_id', 'integer', ['limit' => 11, 'null' => false, 'default' => 0, 'comment' => '父评论ID，0为顶级评论'])
              ->addColumn('reply_user_id', 'integer', ['limit' => 11, 'null' => false, 'default' => 0, 'comment' => '回复用户ID'])
              ->addColumn('content', 'text', ['null' => false, 'comment' => '评论内容'])
              ->addColumn('like_count', 'integer', ['limit' => 11, 'null' => false, 'default' => 0, 'comment' => '点赞数'])
              ->addColumn('status', 'integer', ['limit' => 1, 'null' => false, 'default' => 1, 'comment' => '状态：1正常 0禁用'])
              ->addColumn('create_time', 'integer', ['limit' => 11, 'null' => false, 'default' => 0, 'comment' => '创建时间'])
              ->addColumn('update_time', 'integer', ['limit' => 11, 'null' => false, 'default' => 0, 'comment' => '更新时间'])
              ->addIndex(['user_id'], ['name' => 'user_id'])
              ->addIndex(['target_type', 'target_id'], ['name' => 'target'])
              ->addIndex(['parent_id'], ['name' => 'parent_id'])
              ->addIndex(['status'], ['name' => 'status'])
              ->create();
    }
}
