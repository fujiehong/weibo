<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class SessionsController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest',[
            'only'=>['create']]);
    }
    public function create()
    {
        return view('sessions.create');
    }
    public function store(Request $request)
    {
        $this->validate($request,[
            'email'=>'required|email|max:255',
            'password' => 'required',
            'captcha'=>'required|captcha'
        ]);



        if (Auth::attempt(['email'=>$request->email,'password'=>$request->password],$request->has('remember'))){

            if(Auth::user()->activated) {
               session()->flash('success', '欢迎回来！');
               $fallback = route('users.show', Auth::user());
               return redirect()->intended($fallback);
           } else {
               Auth::logout();
               session()->flash('warning', '你的账号未激活，请检查邮箱中的注册邮件进行激活。');
               return redirect('/');
           }

        }else{

            session()->flash('danger','很抱歉，您的邮箱和密码不匹配!');
            return redirect()->back()->withInput();

        }

        return;
    }

    public function destroy()
    {
        Auth::logout();
        session()->flash('success','你已成功退出！');
        return redirect('login');

    }
}
