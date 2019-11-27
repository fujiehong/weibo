<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Statuses;
use Auth;

class StatusesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function store(Request $request)
    {
        $this->validate($request, [
            'content' => 'required|max:140'
        ]);
        //一对多情况下，一个用户拥有N个微博。所以发布的时候用下面方法进行创建新微博。
        Auth::user()->statuses()->create([
            'content' => $request['content']
        ]);

        session()->flash('success', '发布成功！');
        return redirect()->back();

    }

    public function destroy(Status $status)
    {
        $this->authorize('destroy');
        $status->delete();
        session()->flash('success', '微博已被成功删除！');
        return redirect()->back();

    }
}
