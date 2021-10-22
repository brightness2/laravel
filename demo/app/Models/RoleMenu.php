<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoleMenu extends Model
{
    use HasFactory;

    protected $table = 'role_menu';
    /**
     * 是否主动维护时间戳
     *
     * @var bool
     */
    public $timestamps = false;

    #定义字段黑名单，不允许操作表中哪些字段
    protected $guarded = []; //如果所有字段都可以操作，黑名单为空数组

    /*****************关联****************************** */
}
