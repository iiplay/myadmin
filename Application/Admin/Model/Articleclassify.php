<?php
namespace app\admin\model;

use think\Model;

class Articleclassify extends Model
{
    protected $insert  = ['description'];
    protected $update = [];

    public function articleMod()
    {
        return $this->hasOne('articlemod', 'id', 'mid');
    }

    protected function setDescriptionAttr($value)
    {
        return auto_description($value, input('param.content'));
    }

    public function getStatusTurnAttr($value, $data)
    {
        $turnArr = [0=>lang('status0'), 1=>lang('status1')];
        return $turnArr[$data['status']];
    }

    public function treeList()
    {
        $list = cache('DB_TREE_ARETYPE');
        if(!$list){
            $list = $this->order('sorts ASC,id ASC')->select();
            foreach ($list as $k => $v){
                $v->articleMod;
            }
            $treeClass = new \expand\Tree();
            $list = $treeClass->create($list);
            cache('DB_TREE_ARETYPE', $list);
        }
        return $list;
    }
}