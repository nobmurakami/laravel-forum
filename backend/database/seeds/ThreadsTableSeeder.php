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
                'title' => '総合スレッド',
                'created_at' => '2021-06-02 10:00:00',
                'updated_at' => '2021-06-02 10:00:00',
            ],
            [
                'user_id' => 3,
                'title' => '吾輩は猫である。名前はまだ無い。どこで生れたかとんと見当がつかぬ。何でも薄暗いじめじめした所でニャーニャー泣いていた事だけは記憶している。吾輩はここで始めて人間というものを見た。しかもあとで聞くとそれは書生という人間中で一番獰悪な種族であったそうだ。この書',
                'created_at' => '2021-06-02 11:00:00',
                'updated_at' => '2021-06-02 11:00:00',
            ],
        ]);
    }
}
