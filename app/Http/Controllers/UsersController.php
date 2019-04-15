<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', [
            'except' => ['show', 'create', 'store', 'confirmEmail'] // 显示，注册，保存不需要登录
        ]);

        // 如果已经登录，访问登录页面，会使用guest中间件重新定位到home
        $this->middleware('guest', [
            'only' => ['create']
        ]);
    }

    public function index() {
        $users = User::paginate(10);
        return view('users.index', compact('users'));
    }

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

        $this->sendEmailConfirmationTo($user); // 注册成功后，发送确认邮件
        session()->flash('success', '验证邮件已发送到您的注册邮箱上，请注意查收。');
        return redirect('/');

        //        Auth::login($user); // 注册后自动登录
        // 全局消息提示 键有'danger', 'warning', 'success', 'info'
//        session()->flash('success', '欢迎,您将在这里开启一段新的旅程~');
//        return redirect()->route('users.show', [$user]);
    }

    public function edit(User $user) {
        $this->authorize('update', $user); // 是否满足更新策略
        return view('users.edit', compact('user'));
    }

    public function update(User $user, Request $request) {
        $this->authorize('update', $user); // 是否满足更新策略
        $this->validate($request, [
            'name'=>'required|max:50',
            'password'=>'nullable|confirmed|min:6'
        ]);

        $data = [];
        $data['name'] = $request->name;
        if ($request->password) { // 不输入密码也可以更新
            $data['password'] = bcrypt($request->password);
        }

        $user->update($data);

        session()->flash('success', '个人资料更新成功');

        return redirect()->route('users.show', $user);
    }

    public function destroy(User $user) {
        $this->authorize('destroy', $user); // 满足策略才能执行后续
        $user->delete();
        session()->flash('success', '成功删除用户!');
        return back();
    }

    public function confirmEmail($token) {
        $user = User::where('activation_token', $token)->firstOrFail();
        $user->activated = true;
        $user->activation_token = null;
        $user->save();
        Auth::login($user);
        session()->flash('success', '恭喜你，激活成功');
        return redirect()->route('users.show', [$user]);
//        echo $token;
    }

    private function sendEmailConfirmationTo($user) {
        $view = 'emails.confirm';
        $data = compact('user');
        $from = 'leamiko@qq.com';
        $name = 'leamiko.lin';
        $to = $user->email;
        $subject = "感谢注册". env('APP_NAME') ."应用,请确认您的邮箱。";
        Mail::send($view, $data, function($message) use($from, $name, $to, $subject) {
            $message->from($from, $name)->to($to)->subject($subject);
        });
    }
}
