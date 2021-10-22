<?php

namespace App\Http\Controllers;

class TestController extends Controller
{
    public function __construct()
    {
        /******调用中间件******* */
        // $this->middleware('auth');

        // $this->middleware('log')->only('index');

        // $this->middleware('subscribed')->except('store');)
    }
    //
    public function test1()
    {
        return 'test1';
    }

    public function test2()
    {
        $uri = request()->path(); //返回请求的路径信息,Test/test2
        $flag = request()->is('admin/*'); //验证请求的路径是否与给定的模式匹配
        $url = request()->url(); //获取完整的请求 URL,http://192.168.174.135/public/index.php/Test/test2
        $url = request()->fullUrl(); //http://192.168.174.135/public/index.php/Test/test2?name=ddd
        $method = request()->getMethod(); //HTTP 动词
        $flag = request()->isMethod('GET');
        $input = request()->all(); //获取输入
        $b = request()->boolean('checked'); //获取输入的布尔值
        $only = request()->only(['name']); //
        $ex = request()->except(['age']); //
        $input = request()->query(); //从查询字符串获取输入
        return $input;
    }

    public function test3()
    {
        $val = \Illuminate\Support\Facades\Cookie::get('name');
        if (!$val) {
            // Cookie::queue('name', 'value', $minutes);
            // \Illuminate\Support\Facades\Cookie::queue(\Illuminate\Support\Facades\Cookie::make('name', 'value', $minutes,$path, $domain, $secure, $httpOnly));
            return response('hello Brightness')->cookie('name', 'Brightness', 1); //键,值,有效时间(分钟)
        }
        return $val;
    }

    public function test4()
    {
        $file = request()->file('photo');
        // $path = $request->photo->path();
        // $extension = $request->photo->extension();
        /**存储上传文件 */
        $path = request()->photo->store('images', 'dirname');
        var_dump($file);
        return $file;
    }

    public function test5()
    {
        return view('main');
    }

    public function test6()
    {
        $key = session('key', 'defaultValue');
        session(['key' => 'value']); //set
        $all = request()->session()->all();
        $has = request()->session()->has('key');
        // $request->session()->forget(['key1', 'key2']);//删除多个值
        return $all;
    }

    public function test7()
    {
        return view('form');
    }

    public function test8()
    {
        //会自动将用户重定向到之前的位置。另外，所有的验证错误信息会自动存储到 闪存 session 中
        $validateData = request()->validate([
            'name' => 'required|max:6',
            // 'name' => 'bail|required|max:6',/bail规则时当有一个错误时不会校验其它
            // 'user.age' => 'required',//可以是嵌套参数
            // 'size' => 'nullable|max:20',//nullable规则表示字段可选
        ]);
        return $validateData;
    }

    /**
     * 通过注入获取request
     *
     * @param \App\Http\Requests\TestRequest $request
     * @return void
     */
    public function test9(\App\Http\Requests\TestRequest $request)
    {
        $validateData = $request->validated(); //
        return $validateData;
    }
}
