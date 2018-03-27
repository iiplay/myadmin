<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/21
 * Time: 11:33
 */

namespace app\admin\controller;
use app\admin\model\Articlemod as Articlemods;


class Articlemod extends Common
{
    private $cModel;   //当前控制器关联模型

    public function _initialize()
    {
        parent::_initialize();
        $this->cModel = new Articlemods;   //别名：避免与控制名冲突
    }
    public function index()
    {
        $where = [];
        if (input('get.search')){
            $where['name|mod'] = ['like', '%'.input('get.search').'%'];
        }
        if (input('get._sort')){
            $order = explode(',', input('get._sort'));
            $order = $order[0].' '.$order[1];
        }else{
            $order = 'sorts asc,id asc';
        }

        //分页
        $page = request()->get('page')?request()->get('page'):1;
        $curr = request()->get('curr')?request()->get('curr'):10; //默认10条每页
        $this->assign('page',$page);
        $this->assign('curr',$curr);
        $star = ($page - 1) * $curr;
        $end = $page * $curr;
        $dataList = $this->cModel->where($where)->order($order)->limit("$star,$end")->select();
        $this->assign('dataList', $dataList);

        //数据条数
        $data_count = $this->cModel->where($where)->count();
        $this->assign('data_count',$data_count);

        return $this->fetch();
    }

    /**
     * 新增模型
     * @return mixed|void
     */
    public function create()
    {
        if (request()->isPost()){

            $data = input('post.');
            $result = $this->cModel->validate(CONTROLLER_NAME.'.add')->allowField(true)->save($data);
            if ($result){
                return ajaxReturn('添加成功', url('index'));
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
     * 文章模型编辑
     */
    public function edit($id)
    {
        if (request()->isPost()){
            $data = input('post.');
            if(in_array($data['id'], ['20','21','22'])){
                return ajaxReturn('系统默认文章模型不可操作');
            }

            if (count($data) == 2){
                foreach ($data as $k =>$v){
                    $fv = $k!='id' ? $k : '';
                }
                $result = $this->cModel->validate(CONTROLLER_NAME.'.'.$fv)->allowField(true)->save($data, $data['id']);
            }else{
                $result = $this->cModel->validate(CONTROLLER_NAME.'.edit')->allowField(true)->save($data, $data['id']);
            }
            if ($result){
                return ajaxReturn(lang('操作成功'), url('index'));
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
     * 文章模型删除
     */
    public function delete()
    {
        if (request()->isPost()){
            $id = input('id');
            if (isset($id) && !empty($id)){
                $id_arr = explode(',', $id);
                if(in_array(20, $id_arr) || in_array(21, $id_arr) || in_array(22, $id_arr)){
                    return ajaxReturn('系统默认文章模型不可操作');
                }
                $where = [ 'id' => ['in', $id_arr] ];
                $result = $this->cModel->where($where)->delete();
                if ($result){
                    return ajaxReturn('删除成功', url('index'));
                }else{
                    return ajaxReturn($this->cModel->getError());
                }
            }
        }
    }



}