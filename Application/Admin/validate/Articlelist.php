<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/21
 * Time: 17:23
 */

namespace app\admin\validate;
use think\Validate;


class Articlelist extends Validate
{
    protected $rule = [
        'typeid' => 'require|integer',
        'title' => 'require',
        'click' => 'require|integer|>=:0',
        'status' => 'require|in:0,1',
        'create_time' => 'require',
    ];

    protected $message = [
        'typeid' => '所属分类不能为空',
        'title' => '标题不能为空',
        'click' => '点击数必须为大于等于0数字整数',
        'status' => '创建时间不能为空',
        'create_time' => '状态必须为数字整数（0,1）',
    ];

}