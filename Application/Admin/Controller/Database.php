<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/27
 * Time: 14:14
 */

namespace app\admin\controller;


class Database extends Common
{
    public function _initialize()
    {
        parent::_initialize();
    }

    public function  index()
    {
        $dataList = db()->query("SHOW TABLE STATUS");
        $this->assign('dataList', $dataList);
        return $this->fetch();
    }

    /*
     * 备份列表
     * @return mixed
     */
    public function reduction()
    {
        $sql = new \expand\Baksql(\think\Config::get("database"));
        $dataList = $sql->get_filelist();
        $this->assign('dataList', $dataList);
        return $this->fetch();
    }
    /**
     * 备份数据库
     */
    public function backup()
    {
        if (request()->isPost()){
            $id = input('id');
            if (isset($id) && !empty($id)){
                $table_arr = explode(',', $id);   //备份数据表
                $sql = new \expand\Baksql(\think\Config::get("database"));
                $res = $sql->backup($table_arr);
                return ajaxReturn($res, url('index'));
            }
        }
    }

    /**
     * 下载备份
     */
    public function dowonload()
    {
        $table = input('table');
        $sql = new \expand\Baksql(\think\Config::get("database"));
        $sql->downloadFile($table);
    }
    /*
     * 数据库还原
     */
    public function restore()
    {
        if (request()->isPost()){
            $name = input('id');
            $sql = new \expand\Baksql(\think\Config::get("database"));
            $res = $sql->restore($name);
            return ajaxReturn($res, url('reduction'));
        }
    }

    /**
     * 删除备份
     */
    public function delete()
    {
        if (request()->isPost()){
            $name = input('id');
            $sql = new \expand\Baksql(\think\Config::get("database"));
            $res = $sql->delfilename($name);
            return ajaxReturn($res, url('reduction'));
        }
    }

}