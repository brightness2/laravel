<?php

namespace App\Http\Controllers;

use App\Models\Phone;
use App\Models\Role;
use Illuminate\Support\Facades\DB;

class DBController extends Controller
{
    //

    public function test1()
    {
        $role = DB::table('role')->get();
        return $role;
    }

    public function test2()
    {
        $roles = Role::all();
        return $roles;
    }

    public function test3()
    {
        // $roles = Role::where('id', '>', 0)->get();//获取多条
        $role = Role::where('id', '>', 0)->first(); //获取一条
        return $role;
    }

    public function test4()
    {
        // 结果分块
        Role::where('id', '>', 0)->chunk(1, function ($rows) {
            foreach ($rows as $row) {
                var_dump($row->name);
            }
        });

        //使用游标
        foreach (Role::where('id', '>', 0)->cursor() as $row) {
            var_dump('role=>', $row->name);
        }
        // return $roles;
    }

    public function test5()
    {
        //高级查询-子查询
        $roles = Role::addSelect([
            'role_id' => Role::select('id')->orderBy('id', 'desc')->limit(1),
        ])->get();
        // $roles = Role::addSelect([
        //     'role_id' => Role::select('id')->orderBy('id', 'desc')->limit(1),
        // ])->toSql();
        //select `tp_role`.*, (select `id` from `tp_role` order by `id` desc limit 1) as `role_id` from `tp_role`
        /**
        {
            "id": 1,
            "name": "系统管理员",
            "remark": "",
            "role_id": 2
        },
        {
            "id": 2,
            "name": "普通用户",
            "remark": "测试",
            "role_id": 2
        }
         */
        return $roles;
    }

    public function test6()
    {
        //插入
        // $row = Role::create(['name' => 'test2'])->getKey(); //使用create方法 ,model 必须设置fillable属性或guarded属性
        //更新
        // $id = 5;
        // $row = Role::find($id);
        // $res = $row->update(['remark' => 'test']);
        //删除
        // $ids = [4, 5];
        // $res = Role::destroy($ids);//

        // $row = Role::find(3);
        // $res = $row->delete();
        // return $res;
    }

    public function test7()
    {
        /******关联查询********** */
        $res = Role::find(2)->phone;
        $res = Role::with(['phone'])->find(1);
        $res = Phone::find(1)->role;
        $res = Role::find(1)->roleMenus;
        $res = Role::with(['roleMenus'])->find(1);

        // 在 roleMenus 方法上添加额外的约束条件
        $res = Role::find(1)->roleMenus()->where('menu_id', '>', 2)->get();
        // select * from `tp_role_menu` where `tp_role_menu`.`role_id` = 1 and `tp_role_menu`.`role_id` is not null and `menu_id` > 2
        $res = Role::with(['menus'])->find(1);
        return $res;
    }

    public function test8()
    {
        $res = Role::find(1);
        return $res;
    }
}
