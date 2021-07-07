<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use App\Models\Thread;

class ThreadsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now();

        Thread::insert([
            [
                'user_id' => 2,
                'title' => '総合スレッド',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'user_id' => 4,
                'title' => '吾輩は猫である。名前はまだ無い。どこで生れたかとんと見当がつかぬ。何でも薄暗いじめじめした所でニャーニャー泣いていた事だけは記憶している。吾輩はここで始めて人間というものを見た。しかもあとで聞くとそれは書生という人間中で一番獰悪な種族であったそうだ。この書',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
