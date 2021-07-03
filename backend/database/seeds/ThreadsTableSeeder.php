<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ThreadsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('threads')->insert([
            [
                'user_id' => 1,
                'title' => '総合掲示板',
            ],
            [
                'user_id' => 3,
                'title' => '吾輩は猫である。名前はまだ無い。どこで生れたかとんと見当がつかぬ。何でも薄暗いじめじめした所でニャーニャー泣いていた事だけは記憶している。吾輩はここで始めて人間というものを見た。しかもあとで聞くとそれは書生という人間中で一番獰悪な種族であったそうだ。この書',
            ],
        ]);
    }
}
