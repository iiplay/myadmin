<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/20
 * Time: 16:30
 */

namespace app\admin\model;
use think\Model;


class Articlelist extends model
{
    public function articleclassify()
    {
        return $this->hasOne('Articleclassify', 'id', 'typeid')->field('typename, mid, dirs');
    }
    public function User()
    {
        return $this->hasOne('User', 'id', 'writer')->field('name');
    }
    /**
     * 文章模型关联表
     */
    public function addonarticle()
    {
        return $this->hasOne('addonarticle', 'aid', 'id');
    }

}