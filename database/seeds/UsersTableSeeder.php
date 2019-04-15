<?php
use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder {
    public function run() {
        $users = factory(User::class)->times(50)->make();
        $usersArr = $users->makeVisible(['password', 'remember_token'])->toArray();
//        print_r($usersArr);
        User::insert($usersArr);

        $user = User::find(1);
        $user->name = 'leamiko';
        $user->email = 'leamiko@qq.com';
        $user->is_admin = true;
        $user->save();
    }
}