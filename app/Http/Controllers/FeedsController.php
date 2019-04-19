<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeedsController extends Controller
{
    public function __construct()
    {
        // 加载中间件
        $this->middleware('auth');
    }

    // 新增
    public function store(Request $request) {
        // 数据检验
        $this->validate($request, [
            'content' => 'required|max:140'
        ]);

        Auth::user()->feeds()->create([
            'content' => $request['content']
        ]);

        session()->flash('success', '发布成功!');
        return redirect()->back();
    }

    // 删除
    public function destroy() {}
}