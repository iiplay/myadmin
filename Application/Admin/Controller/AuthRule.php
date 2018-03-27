<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/28
 * Time: 16:00
 */

namespace app\admin\controller;

use app\admin\model\AuthRule as AuthRules;

class AuthRule extends Common
{
    private $cModel;   //当前控制器关联模型
    private $module = 'admin';

    public function _initialize()
    {
        parent::_initialize();
        $this->cModel = new AuthRules;   //别名：避免与控制名冲突
    }
    public function index()
    {
        $dataList = $this->cModel->treeList($this->module);
        $this->assign('module', $this->module);
        $this->assign('dataList', $dataList);
        return $this->fetch();
    }

    /**
     * @return mixed|void
     * 新增
     */
    public function create()
    {
        if (request()->isPost()){
            $data = input('post.');
            $result = $this->cModel->validate(CONTROLLER_NAME.'.add')->allowField(true)->save($data);
            if ($result){
                return ajaxReturn('action_success', url('index'));
            }else{
                return ajaxReturn($this->cModel->getError());
            }
        }else{
            $treeList = $this->cModel->treeList($this->module);
            $this->assign('module', $this->module);
            $this->assign('treeList', $treeList);
            return $this->fetch('edit');
        }
    }

    /**
     * @param $id
     * @return mixed|void
     * 编辑
     */
    public function edit($id)
    {
        if (request()->isPost()){
            $data = input('post.');
            if (count($data) == 2){
                foreach ($data as $k =>$v){
                    $fv = $k!='id' ? $k : '';
                }
                $result = $this->cModel->validate(CONTROLLER_NAME.'.'.$fv)->allowField(true)->save($data, $data['id']);
            }else{
                $result = $this->cModel->validate(CONTROLLER_NAME.'.edit')->allowField(true)->save($data, $data['id']);
            }
            if ($result){
                return ajaxReturn(lang('action_success'), url('index'));
            }else{
                return ajaxReturn($this->cModel->getError());
            }
        }else{
            $data = $this->cModel->get($id);
            $this->assign('data', $data);

            $this->assign('module', $this->module);

            $treeList = $this->cModel->treeList($this->module);
            $this->assign('treeList', $treeList);
            return $this->fetch();
        }
    }

    /**
     * 删除
     */
    public function delete()
    {
        if (request()->isPost()){
            $id = input('id');
            $module = $this->module;
            if (isset($id) && !empty($id) && $module){
                $id_arr = explode(',', $id);
                $where = [ 'id' => ['in', $id_arr] ];
                $result = $this->cModel->where($where)->delete();
                if ($result){
                    return ajaxReturn(lang('action_success'), url('index'));
                }else{
                    return ajaxReturn($this->cModel->getError());
                }
            }
        }

    }

}