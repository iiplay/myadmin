<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/21
 * Time: 14:24
 */

namespace app\admin\validate;
use think\Validate;

class Articleclassify extends Validate
{
    protected $rule = [
        'pid' => 'require|integer',
        'typename' => 'require',
        'mid' => 'require|integer',
        'dirs' => 'require|/^[a-zA-Z0-9\-\_]+$/',
        'target' => 'require',
        'templist' => 'require|/^[a-zA-Z0-9\_]+$/',
        'temparticle' => 'require|/^[a-zA-Z0-9\_]+$/',
        'pagesize' => 'require|integer|>=:1',
        'sorts' => 'require|integer|>=:1',
        'status' => 'require|in:0,1',
    ];

    protected $message = [
        'pid' => '上级分类必须为数字整数',
        'typename' => '分类名称不能为空',
        'mid' => '分类模型必须为数字整数',
        'dirs.require' => '分类目录不能为空',
        'dirs' => '分类目录必须为（数字字母-_）',
        'target' => '弹出方式不能为空',
        'templist' => '列表页模板必须为（数字字母_）',
        'temparticle' => '内容页模板必须为（数字字母_）',
        'pagesize' => '分页条数必须为大于0数字整数',
        'sorts' => '排序必须为大于0数字整数',
        'status' => '状态必须为数字整数（0,1）',
    ];
}