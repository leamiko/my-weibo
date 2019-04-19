<?php

use App\Models\Feed;
use Illuminate\Database\Seeder;

class FeedsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = app(Faker\Generator::class);
        $user_ids = ['1', '2', '3'];
        $feeds = factory(Feed::class)->times(100)->make()->each(function($feed) use($faker, $user_ids) {
            $feed->user_id = $faker->randomElement($user_ids);
        });
        \App\Models\Feed::insert($feeds->toArray());
    }
}
