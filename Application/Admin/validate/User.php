<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/26
 * Time: 17:28
 */

namespace app\admin\validate;
use think\Validate;


class User extends Validate
{
    protected $rule = [
        'username' => 'require|min:1|unique:user',
        'password' => 'require|min:6',
        'repassword' => 'require|confirm:password',
        'email' => 'email|unique:user',
        'moblie' => '/^1[34578]\d{9}$/|unique:user',
        'sex' => 'require|in:0,1',
        'status' => 'require|in:0,1',
    ];

    protected $message = [
        'username.require' => '用户名不能为空',
        'username.min' => '用户名长度不能少于1位',
        //'username' => '用户名只能使用字母和数字',
        'username.unique' => '用户名已经存在，请重新填写',
        'password' => '密码不能为空',
        'password.min' => '密码长度不能少于6位',
        'repassword' => '确认密码不正确',
        'email' => '请填写正确的邮箱格式',
        'email.unique' => '邮箱已经被使用，请重新填写',
        'moblie' => '请填写正确的手机号码格式',
        'moblie.unique' => '手机号码已经被使用，请重新填写',
        'sex' => '性别必须为数字整数（0,1）',
        'status' => '状态必须为数字整数（0,1）',
    ];
    protected $scene = [
        'add'   => ['username', 'password', 'repassword', 'email', 'moblie', 'sex', 'status'],
        'edit'  => ['email', 'moblie', 'sex', 'status'],
        'password' => ['password', 'repassword'],
        'status' => ['status'],
        'name' => ['name'],
    ];
}