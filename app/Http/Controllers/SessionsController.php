<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SessionsController extends Controller
{
    public function __construct()
    {
        // 如果已经登录，访问登录页面，会使用guest中间件重新定位到home
        $this->middleware('guest', [
            'only' => ['create']
        ]);
    }

    public function create()
    {
        return view('sessions.create');
    }

    public function store(Request $request)
    {
//        dd($request->all());
        // 验证提交的数据
        $credentials = $this->validate($request, [
            'email' => 'required|email|max:255',
            'password' => 'required'
        ]);
//        dd($credentials);
        if (Auth::attempt($credentials, $request->has('remember'))) {
//            dd(Auth::user());
            if(Auth::user()->activated) {
                session()->flash('success', '欢迎回来');
                $user = Auth::user();
//            return redirect()->route('users.show', [$user]);
//            $fallback = route('users.show', Auth::user());
                $fallback = route('users.show', [$user]);
                return redirect()->intended($fallback);
            } else {
                Auth::logout();
                session()->flash('warning', '您的账号未激活，请检查邮箱中的注册邮件进行激活。');
                return redirect('/');
            }

        } else {
            session()->flash('danger', '很抱歉,您的邮箱和密码不匹配');
            return redirect()->back()->withInput();
        }
//        dd($request->all());
    }

    public function destory()
    {
        Auth::logout();
        session()->flash('success', '您已成功退出！');
        return redirect('login');
    }
}
