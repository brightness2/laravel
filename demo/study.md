# 路由使用

## 文件
1、api路由 /routes/api.php;无状态的，并且被分配了 api 中间件组
2、web 界面的路由 /routes/web.php;有会话状态和CSRF保护等功能

## 动词
Route::get($uri, $callback);
Route::post($uri, $callback);
Route::put($uri, $callback);
Route::patch($uri, $callback);
Route::delete($uri, $callback);
Route::options($uri, $callback);
Route::any($uri, $callback);
Route::match(['get', 'post'], '/', function () {
    //
});

## 重定向
Route::redirect('/here', '/there');

## 视图路由
Route::view('/welcome', 'welcome');
Route::view('/welcome', 'welcome', ['name' => 'Taylor']);//第三个参数是模板数据

## 回退路由 
注意：回退路由应始终是您应用程序注册的最后一个路由
Route::fallback(function () {
    //
});

# 中间件

## 定义中间件
执行 php artisan make:middleware ClassName
生成
/app/Http/Middleware/ClassName.php
## 前置中间件，后置中间件

public function handle($request, Closure $next)
{
    // Perform action

    return $next($request);
}
public function handle($request, Closure $next)
{
    $response = $next($request);

    // Perform action

    return $response;
}
## 注册中间件
1、全局，在 app/Http/Kernel.php 中的 $middleware 属性中列出这个中间件
2、路由，首先应该在 app/Http/Kernel.php 文件内$routeMiddleware为该中间件分配一个键
路由文件中,调用
Route::get('admin/profile', function () {
    //
})->middleware('auth');

# 控制器

## 定义
执行 php artisan make:controller ClassName
生成
/app/Http/Controllers/ClassName.php

## 资源控制器
执行 php artisan make:controller ClassName --resource

# 请求 request

## 获取输入的参数
request()->all();

## 获取cookie和响应cookie
\Illuminate\Support\Facades\Cookie::get('name');
\Illuminate\Support\Facades\Cookie::queue('name', 'value', $minutes);

# 响应 response

## 自动转化
string 自动转成 完整http响应
array 自动转成 json
Eloquent 集合(orm查询的数据) 自动转成 json

## 设置响应信息
1、响应头
return response($content)
            ->withHeaders([
                'Content-Type' => $type,
                'X-Header-One' => 'Header Value',
                'X-Header-Two' => 'Header Value',
            ]);
2、cookie 
//注意cookie是加密的，
//App\Http\Middleware\EncryptCookies 中间件的 $except 属性可以设置取消加密
return response($content)
                ->cookie('name', 'value', $minutes);

3、视图,自动转成完整http响应
return view('hello', $data, 200);

## 重定向
1、redirect('home/dashboard');
2、back()->withInput();//回退并携带输入的表单数据
3、 redirect()->route('login');//重定向到命名路由

## 文件下载
1、return response()->download($pathToFile);
2、流下载
return response()->streamDownload(function () {
    echo GitHub::api('repo')
                ->contents()
                ->readme('laravel', 'laravel')['contents'];
}, 'laravel-readme.md');

## 文件响应
//直接在用户浏览器显示一个图片或PDF之类的文件
return response()->file($pathToFile, $headers);


# 视图

## 响应视图数据
//注意：视图目录名中不应该包含 . 字符。
 return view('admin.profile', ['name' => 'James']);
 //对应的模板文件,/resources/views/admin/profile.blade.php
## 使用的模板引擎
Blade 模板引擎

## 所有视图共享数据
服务提供器
/app/Providers/AppServiceProvider.php 中 boot 方法 内添加
public function boot()
{
    View::share('key', 'value');
}

## 模板语法与技巧

1、模板继承

2、基本流程语句

3、CSRF 域 ,表单中增加 @csrf 指令生成一个 token 域，用于后台校验，字段固定为X-CSRF-TOKEN

4、方法域,表单中增加@method('PUT')指令 模拟这些 HTTP 动作 put

5、校验错误 与回显错误信息
```html
<label for="title">Post Title</label>

<input id="title" type="text" class="@error('title') is-invalid @enderror">

@error('title')
    <div class="alert alert-danger">{{ $message }}</div>
@enderror
```
6、组件与插槽
组件和插槽的作用与区块和布局的作用一致；

7、视图堆栈
将视图推送到堆栈中，它们可以在其他视图或布局中进行渲染。这在子视图中需要指定 JavaScript 库时非常有用
a.blade.php
```html
@push('scripts')
    <script src="/example.js"></script>
@endpush
```
b.blade.php
```html
<head>
    <!-- Head Contents,可多次推送 -->

    @stack('scripts')
</head>
```

# session

## 支持的驱动
file、Memcached、Redis,默认是file
Session 的配置文件存储在 config/session.php 文件中


# 表单验证

## 前端
1、传统form表单
```html
<!-- form.blade.php 文件 -->
<form action="url" method="post">
    <!-- csrf token 必须 -->
    @csrf
    <!-- 字段 -->
    <input type="text" name="name">
    <!-- 通过指令显示表单错误 -->
    @error('name')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror
    <button type="submit">提交</button>
</form>
```
2、ajax请求
```html
<!-- form.blade.php 文件 -->
<meta id="token" name="csrf-token" content="{{ csrf_token() }}">
<script>
    let token = document.querySelector('#token').attributes.content.value;
    fetch('./test8', {
        headers: {
            'X-CRSF-TOKEN': token,//必须,crsf
            'Accept': 'application/json' // 通过头指定，获取的数据类型是JSON
        }
    }).then(resp => {
        console.log(resp);
        return resp.json();
    }).then(res => {
        console.log(res);
    })
</script>    
```

## 后端
1、方法中直接设置
```php
/*************TestController.php******************/ 
$validateData = request()->validate([
    'name' => 'required|max:6',
]);
```
2、把验证抽离出来
执行 php artisan make:request Test
生成文件 /app/Http/Requests/Test.php


# 错误处理

## 所有异常都是由 App\Exceptions\Handler 处理
1、这个类包含了两个方法：report 和 render
report 方法用于记录异常或将它们发送给如 Flare，Bugsnag 或 Sentry 等外部服务；

# 数据库

## 配置数据库
config/database.php 文件

## 查询构造器 DB
$role = DB::table('role')->get();



# Eloquent ORM

## 创建模型
执行 php artisan make:model Role
/app/Models/Role.php

## CURD
```php
// 查询
$roles = Role::all();
$roles = Role::get();
$role = Role::first();
//结果块
//把查询到的数据分成每份10条
Role::chunk(10, function ($rows) {
    foreach ($rows as $row) {
        var_dump($row->name);
    }
});
//游标
 foreach (Role::cursor() as $row) {
    var_dump('role=>', $row->name);
}
//子查询
//select `tp_role`.*, (select `id` from `tp_role` order by `id` desc limit 1) as `role_id` from `tp_role`
$roles = Role::addSelect([
    'role_id' => Role::select('id')->orderBy('id', 'desc')->limit(1),
])->get();
//插入
Role::create(['name' => 'test2'])->getKey();//使用create方法 ,model 必须设置fillable属性或guarded属性
//更新
$row = Role::find($id);
$res = $row->update(['remark' => 'test']);
//删除
Role::destroy([4,5]);//删除多条

$row = Role::find(3);//删除一条
$res = $row->delete();
```

## 查询作用域
1、匿名全局作用域
```php
protected static function booted()
{
    static::addGlobalScope('delete', function (Builder $builder) {
        $builder->where('delete', 0);
    });
}
```
2、取消全局作用域
```php
User::withoutGlobalScope('delete')->get();
```
3、局部作用域
```php
// app/Models/Role.php
 /**
 *只查询受欢迎的用户的作用域
    *
    * @param  \Illuminate\Database\Eloquent\Builder  $query
    * @return \Illuminate\Database\Eloquent\Builder
    */
public function scopePopular($query)
{
    return $query->where('votes', '>', 100);
}

/**
 * 只查询 active 用户的作用域
 *
 * @param  \Illuminate\Database\Eloquent\Builder  $query
 * @return \Illuminate\Database\Eloquent\Builder
 */
public function scopeActive($query)
{
    return $query->where('active', 1);
}
```
使用
```php
    //
    $users = App\Models\User::popular()->active()->orderBy('created_at')->get();
    //
   $users = App\Models\User::popular()->orWhere->active()->get();
```

## 模型事件
 Eloquent 模型触发几个事件，允许你挂接到模型生命周期的如下节点： retrieved、creating、created、updating、updated、saving、saved、deleting、deleted、restoring 和 restored。事件允许你每当特定模型保存或更新数据库时执行代码。每个事件通过其构造器接受模型实例。

注意：通过 Eloquent 进行批量更新时，被更新模型的 saved 和 updated, deleting 和 deleted 事件不会被触发。这是因为批量更新时，并没有真的获取模型。

1、定义事件使用
```php
//模型需要使用trait
use Illuminate\Notifications\Notifiable;
//设置dispatchesEvents属性

```
2、禁用事件
```php
$user = User::withoutEvents();
//保存单个模型并禁用事件
$user = User::findOrFail(1);

$user->name = 'Victoria Faith';

$user->saveQuietly();
```

## 关联查询
1、一对一
/app/Models/Role.php 文件
```php
/**
     * 一个role有一个phone
     * phone 表存在外键 role_id
     * @return void
     */
    public function phone()
    {
        return $this->hasOne('App\Models\Phone');
    }
```
调用
```php
    $phone = Role::find(2)->phone;
    //附带关联信息
    $res = Role::with(['phone'])->find(2);
```

2、反向一对一
```php
/**
     * 反向一对一
     * Phone 存在外键role_id
     * @return void
     */
    public function role()
    {
        return $this->belongsTo('App\Models\Role');
    }
```
调用
```php
    $user = Phone::find(1)->role;
```
3、一对多
app/Models/Role.php 文件
```php
//一对多
    public function roleMenus()
    {
        return $this->hasMany('App\Models\RoleMenu');
    }
```
调用
```php
$res = Role::find(1)->roleMenus;
// 在 roleMenus 方法上添加额外的约束条件
$res = Role::find(1)->roleMenus()->where('menu_id', '>', 2)->get();
// select * from `tp_role_menu` where `tp_role_menu`.`role_id` = 1 and `tp_role_menu`.`role_id` is not null and `menu_id` > 2
//附带关联信息
$res = Role::with(['roleMenus'])->find(1);
```

4、远程一对多关联
```php
/**
     * role表通过role_menu表获取多条menu数据
     *
     * @return void
     */
    public function menus()
    {
        // SELECT `tp_menu`.*,  `tp_role_menu`.`menu_id` as `laravel_through_key` FROM `tp_menu`
        // INNER JOIN `tp_role_menu` ON `tp_role_menu`.`menu_id`=`tp_menu`.`id`
        // INNER JOIN `tp_role` ON `tp_role`.`id`=`tp_role_menu`.`role_id` WHERE  `tp_role_menu`.`role_id` = '1'  
        return $this->hasManyThrough('App\Models\Menu', 'App\Models\RoleMenu', 'role_id', 'id', 'id', 'menu_id');
        //第一个参数是最终查询的表，第二个参数是中间表，第三个参数是中间表与主表的外键，第四个参数表示最终模型的外键名。
        //第五个参数表示本地键名，而第六个参数表示中间模型的本地键名
    }
```
调用
```php
$res = Role::with(['menus'])->find(1);
```

## 插入 & 更新关联模型

## 访问器 & 修改器
1、访问器
```php
//字段 id_name
    public function getIdNameAttribute($value)
    {
        return "{$this->id}--{$this->name}";
    }
    //定义好获取器之后，再把对应的属性名称加到模型里的 appends 属性
    protected $appends = ['id_name'];//查询时自动添加的字段
```
2、修改器
```php
//插入修改时，传入的 数据会经过这里处理
    //remark字段
    public function setRemarkAttribute($value)
    {
        $this->attributes['remark'] = '备注:' + $value;
    }
```