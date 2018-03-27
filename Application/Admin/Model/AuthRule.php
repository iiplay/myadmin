<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/28
 * Time: 10:19
 */

namespace app\admin\model;
use think\Model;


class AuthRule extends Model
{

    public function treeList($module = '', $status = '')
    {
        if ($module != ''){
            $where = [
                'module' => $module
            ];
        }
        if ($status != ''){
            $where['status'] = $status;
        }
        $list = $this->where($where)->order('sorts ASC,id ASC')->select();
        $treeClass = new \expand\Tree();
        $list = $treeClass->create($list);
        return $list;
    }
}