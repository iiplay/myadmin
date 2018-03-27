<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/17
 * Time: 9:59
 */

namespace app\admin\controller;
use app\admin\model\Config as Configs;


class Config extends Common
{
    private $cModel;   //当前控制器关联模型

    public function _initialize()
    {
        parent::_initialize();
        $this->cModel = new Configs;   //别名：避免与控制名冲突
    }
    /**
     * 系统配置字段
     */
    public function index()
    {
        $where = [];
        if (input('get.search')){
            $where['k|v|desc|type|texttype'] = ['like', '%'.input('get.search').'%'];
        }
        if (input('get._sort')){
            $order = explode(',', input('get._sort'));
            $order = $order[0].' '.$order[1];
        }else{
            $order = 'type asc,status desc,sorts asc,id asc';
        }
        $dataList = $this->cModel->where($where)->order($order)->paginate('', false, page_param());
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
                return ajaxReturn('action_success', url('index'));
            }else{
                return ajaxReturn($this->cModel->getError());
            }
        }else{
            $data = $this->cModel->get($id);
            $this->assign('data', $data);
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
            if (isset($id) && !empty($id)){
                $id_arr = explode(',', $id);
                $where = [ 'id' => ['in', $id_arr] ];
                $result = $this->cModel->where($where)->delete();
                if ($result){
                    return ajaxReturn('action_success', url('index'));
                }else{
                    return ajaxReturn($this->cModel->getError());
                }
            }
        }
    }
    /**
     * 站点配置
     */
    public function web()
    {
        $type = ACTION_NAME;
        $where = ['type' => $type, 'status'=>1];
        $data = $this->cModel->where($where)->order('sorts ASC,id ASC')->select();
        $this->assign('data', $data);
        $this->assign('type', $type);
        return $this->fetch();
    }

    /**
     * @return mixed
     * 系统配置
     */
    public function system()
    {
        $type = ACTION_NAME;
        $where = ['type' => $type, 'status'=>1];
        $data = $this->cModel->where($where)->order('sorts ASC,id ASC')->select();
        $this->assign('data', $data);
        $this->assign('type', $type);
        return $this->fetch();
    }
    /**
     * 循环保存数据
     */
    public function save()
    {
        if (request()->isPost()){
            $data = input('post.');
            $type = $data['type'];   //取出类型
            unset($data['type']);
            if(!empty($type)){
                if(is_array($data) && !empty($data)){
                    foreach ($data as $k=>$val) {
                        $where = array('type' => $type, 'k'=>$k);
                        $this->cModel->where($where)->update(['v' => $val]);
                    }
                    return ajaxReturn(lang('action_success'), url('Config/'.$type));
                }else{
                    return ajaxReturn($this->cModel->getError());
                }
            }else{
                return ajaxReturn($this->cModel->getError());
            }
        }
    }



}