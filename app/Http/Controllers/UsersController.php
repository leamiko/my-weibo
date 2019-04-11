<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UsersController extends Controller
{
    public function create() {
        return view('users.create');
    }

    public function show(User $user) {
//        dd($user);
        return view('users.show', compact('user'));
    }

    public function store(Request $request) {
        $this->validate($request, [
            'name' => 'required|max:50',
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required|confirmed|min:6'
        ]);
//        dd($request);
        // 存到数据库
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        Auth::login($user); // 注册后自动登录

        // 全局消息提示 键有'danger', 'warning', 'success', 'info'
        session()->flash('success', '欢迎,您将在这里开启一段新的旅程~');
        return redirect()->route('users.show', [$user]);
    }
}
