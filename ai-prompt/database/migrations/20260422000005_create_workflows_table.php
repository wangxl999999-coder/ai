<?php

use think\migration\Migrator;
use think\migration\db\Column;

class CreateWorkflowsTable extends Migrator
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
        $table = $this->table('workflows', ['engine' => 'InnoDB', 'collation' => 'utf8mb4_general_ci', 'comment' => '工作流表']);
        $table->addColumn('user_id', 'integer', ['limit' => 11, 'null' => false, 'default' => 0, 'comment' => '发布用户ID'])
              ->addColumn('category_id', 'integer', ['limit' => 11, 'null' => false, 'default' => 0, 'comment' => '分类ID'])
              ->addColumn('title', 'string', ['limit' => 255, 'null' => false, 'default' => '', 'comment' => '标题'])
              ->addColumn('description', 'text', ['null' => true, 'comment' => '简介'])
              ->addColumn('content', 'text', ['null' => false, 'comment' => '工作流内容'])
              ->addColumn('preview', 'text', ['null' => true, 'comment' => '预览内容（部分展示）'])
              ->addColumn('tags', 'string', ['limit' => 255, 'null' => false, 'default' => '', 'comment' => '标签，逗号分隔'])
              ->addColumn('view_count', 'integer', ['limit' => 11, 'null' => false, 'default' => 0, 'comment' => '查看次数'])
              ->addColumn('like_count', 'integer', ['limit' => 11, 'null' => false, 'default' => 0, 'comment' => '点赞数'])
              ->addColumn('favorite_count', 'integer', ['limit' => 11, 'null' => false, 'default' => 0, 'comment' => '收藏数'])
              ->addColumn('comment_count', 'integer', ['limit' => 11, 'null' => false, 'default' => 0, 'comment' => '评论数'])
              ->addColumn('status', 'integer', ['limit' => 1, 'null' => false, 'default' => 1, 'comment' => '状态：1待审核 2已通过 3已拒绝 0禁用'])
              ->addColumn('is_recommend', 'integer', ['limit' => 1, 'null' => false, 'default' => 0, 'comment' => '是否推荐：1是 0否'])
              ->addColumn('points_reward', 'integer', ['limit' => 11, 'null' => false, 'default' => 3, 'comment' => '发布获得的积分奖励'])
              ->addColumn('points_fee', 'integer', ['limit' => 11, 'null' => false, 'default' => 1, 'comment' => '查看需要支付的积分'])
              ->addColumn('create_time', 'integer', ['limit' => 11, 'null' => false, 'default' => 0, 'comment' => '创建时间'])
              ->addColumn('update_time', 'integer', ['limit' => 11, 'null' => false, 'default' => 0, 'comment' => '更新时间'])
              ->addIndex(['user_id'], ['name' => 'user_id'])
              ->addIndex(['category_id'], ['name' => 'category_id'])
              ->addIndex(['status'], ['name' => 'status'])
              ->addIndex(['is_recommend'], ['name' => 'is_recommend'])
              ->create();
    }
}
