<?php

use think\migration\Seeder;

class CategorySeeder extends Seeder
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
            // 提示词分类
            ['name' => '通用写作', 'type' => 1, 'sort' => 1, 'status' => 1, 'create_time' => time(), 'update_time' => time()],
            ['name' => '文章创作', 'type' => 1, 'sort' => 2, 'status' => 1, 'create_time' => time(), 'update_time' => time()],
            ['name' => '代码生成', 'type' => 1, 'sort' => 3, 'status' => 1, 'create_time' => time(), 'update_time' => time()],
            ['name' => '创意灵感', 'type' => 1, 'sort' => 4, 'status' => 1, 'create_time' => time(), 'update_time' => time()],
            ['name' => '营销文案', 'type' => 1, 'sort' => 5, 'status' => 1, 'create_time' => time(), 'update_time' => time()],
            ['name' => '翻译助手', 'type' => 1, 'sort' => 6, 'status' => 1, 'create_time' => time(), 'update_time' => time()],
            ['name' => '教育学习', 'type' => 1, 'sort' => 7, 'status' => 1, 'create_time' => time(), 'update_time' => time()],
            ['name' => '数据分析', 'type' => 1, 'sort' => 8, 'status' => 1, 'create_time' => time(), 'update_time' => time()],
            
            // 工作流分类
            ['name' => '内容创作流程', 'type' => 2, 'sort' => 1, 'status' => 1, 'create_time' => time(), 'update_time' => time()],
            ['name' => '产品设计流程', 'type' => 2, 'sort' => 2, 'status' => 1, 'create_time' => time(), 'update_time' => time()],
            ['name' => '软件开发流程', 'type' => 2, 'sort' => 3, 'status' => 1, 'create_time' => time(), 'update_time' => time()],
            ['name' => '市场运营流程', 'type' => 2, 'sort' => 4, 'status' => 1, 'create_time' => time(), 'update_time' => time()],
            ['name' => '数据分析流程', 'type' => 2, 'sort' => 5, 'status' => 1, 'create_time' => time(), 'update_time' => time()],
            ['name' => '客服服务流程', 'type' => 2, 'sort' => 6, 'status' => 1, 'create_time' => time(), 'update_time' => time()],
        ];

        $table = $this->table('categories');
        $table->insert($data)->save();
    }
}
