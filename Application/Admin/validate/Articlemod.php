<?php
namespace app\admin\validate;

use think\Validate;

class Articlemod extends Validate
{
    protected $rule = [
        'name' => 'require',
        'mod' => 'require|alpha',
        'sorts' => 'require|integer|>=:1',
        'status' => 'require|in:0,1',
    ];

    protected $message = [
        'name' => '文章模型名称不能为空',
        'mod.alpha' => '文章模型操作必须为字母',
        'mod' => '文章模型操作不能为空',
        'sorts' => '排序必须为大于0数字整数',
        'status' => '状态必须为数字整数（0,1）',
    ];

    protected $scene = [
        'add'   => ['name', 'mod', 'sorts', 'status'],
        'edit'  => ['name', 'mod', 'sorts', 'status'],
        'status' => ['status'],
    ];
}