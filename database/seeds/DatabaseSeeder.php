<?php

use Illuminate\Database\Seeder;

use App\Entities\User;
use App\Entities\Type;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create(
        	[
        		'name' => 'test',
        		'email' => 'test@test.com',
        		'password' => Hash::make('secret')
        	]
        );

        $types = ['已完結', '少女', '百合', '日常', '玄幻', '遊戲', '治癒', '科幻', '搞笑', '鬼怪', '勵志', '運動', '格鬥', '熱血', '愛情', '校園', '耽美', '偽娘', '冒險', '職場', '後宮', '親情', '戰爭', '懸疑', '偵探', '奇幻', '魔法', '恐怖', '萌系', '歷史', '美食', '競技', '機戰'];
        foreach ($types as $type) {
	        Type::create(
	        	[
	        		'name' => $type,
	        		'create_by' => 0
	        	]
	        );
        }
    }
}
