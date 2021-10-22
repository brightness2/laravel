<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Role extends Model
{
    use HasFactory, Notifiable;

    protected $table = 'role';
    /**
     * 是否主动维护时间戳
     *
     * @var bool
     */
    public $timestamps = false;
    //设置时间戳字段
    // const CREATED_AT = 'creation_date';
    // const UPDATED_AT = 'last_update';

    /**
     * 模型属性的默认值
     *
     * @var array
     */
    protected $attributes = [
        'remark' => '无',
    ];
    #定义字段白名单，允许操作表中的哪些字段
    // protected $fillable = ['name'];
    #定义字段黑名单，不允许操作表中哪些字段
    protected $guarded = []; //如果所有字段都可以操作，黑名单为空数组


    // protected static function booted()
    // {

    // 查询作用域， 匿名全局作用域
    //     static::addGlobalScope('delete', function (Builder $builder) {
    //         $builder->where('delete', 0);
    //     });

    //created事件
    // static::created(function ($role) {
    //     //
    // });
    // }

    /**
     *只查询受欢迎的用户的作用域
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    // public function scopePopular($query)
    // {
    //     return $query->where('votes', '>', 100);
    // }

    /**
     * 只查询 active 用户的作用域
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    // public function scopeActive($query)
    // {
    //     return $query->where('active', 1);
    // }

    /*******************事件********************** */
    /**
     * 模型的事件映射
     *
     * @var array
     */
    // protected $dispatchesEvents = [
    //     'saved' => RoleSavedrole::class,//需要创建RoleSavedrole  event类
    // ];

    /******************关联***************** */
    //一对一
    /**
     * 一个role有一个phone
     * phone 表存在外键 role_id
     * @return void
     */
    public function phone()
    {
        return $this->hasOne('App\Models\Phone');
    }

    //一对多
    /**
     * role_menu 表存在外键 role_id
     *
     * @return void
     */
    public function roleMenus()
    {
        return $this->hasMany('App\Models\RoleMenu');
    }

    //远程一对多
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

    /*****************访问器 & 修改器******************** */

    //字段 id_name
    public function getIdNameAttribute($value)
    {
        return "{$this->id}--{$this->name}";
    }
    //定义好获取器之后，再把对应的属性名称加到模型里的 appends 属性
    protected $appends = ['id_name'];

    //插入修改时，传入的 数据会经过这里处理
    //remark字段
    public function setRemarkAttribute($value)
    {
        $this->attributes['remark'] = '备注:' + $value;
    }
}
