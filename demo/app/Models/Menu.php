<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    //除非明确地指定了其它名称，否则将使用类的复数形式「蛇形命名」来作为表名
    /**
     * 与模型关联的表名
     *
     * @var string
     */
    protected $table = 'menu';

    /**
     * 是否主动维护时间戳
     *
     * @var bool
     */
    public $timestamps = false;

    #定义字段黑名单，不允许操作表中哪些字段
    protected $guarded = []; //如果所有字段都可以操作，黑名单为空数组


}
