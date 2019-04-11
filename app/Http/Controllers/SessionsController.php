<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SessionsController extends Controller
{
    public function create() {
        return view('sessions.create');
    }

    public function store(Request $request) {
        // 验证提交的数据
        $credentials = $this->validate($request, [
            'email' => 'required|email|max:255',
            'password' => 'required'
        ]);
//        dd($credentials);
        if (Auth::attempt($credentials)) {
//            dd(Auth::user());
            session()->flash('success', '欢迎回来');
            $user = Auth::user();
            return redirect()->route('users.show', [$user]);
        } else {
            session()->flash('danger', '很抱歉,您的邮箱和密码不匹配');
            return redirect()->back()->withInput();
        }
//        dd($request->all());
    }

    public function destory() {

    }
}
