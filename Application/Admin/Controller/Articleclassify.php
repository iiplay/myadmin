<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/17
 * Time: 11:21
 */

namespace app\admin\controller;
use app\admin\model\Articleclassify as Articleclassifys;
use app\admin\model\Articlemod;

class Articleclassify extends Common
{
    private $cModel;   //当前控制器关联模型

    public function _initialize()
    {
        parent::_initialize();
        $this->cModel = new Articleclassifys;   //别名：避免与控制名冲突

    }
    public function index()
    {
        $dataList = $this->cModel->treeList();
        foreach ($dataList as $k=>$v){
            if ($v['articleMod']['mod'] == 'addonjump' && !empty($v['jumplink'])){
                $dataList[$k]['typelink'] = $v['jumplink'];
            }else{
                $dataList[$k]['typelink'] = url('@category/'.$v['dirs']);
            }
        }
        $this->assign('dataList', $dataList);
        return $this->fetch();
    }

    /*
     * 新增分类
     */
    public function create()
    {
        if (request()->isPost()){
            $data = input('post.');
            $result = $this->cModel->validate(CONTROLLER_NAME.'.add')->allowField(true)->save($data);
            cache('DB_TREE_ARETYPE', null);   //删除栏目缓存
            if ($result){
                return ajaxReturn('操作成功', url('index'));
            }else{
                return ajaxReturn($this->cModel->getError());
            }
        }else{
            $arctypeList = $this->cModel->treeList();
            $this->assign('arctypeList', $arctypeList);

            $amModel = new Articlemod();
            $modList = $amModel->where(['status' => 1])->order('sorts ASC,id ASC')->select();
            $this->assign('modList', $modList);
            return $this->fetch('edit');
        }
    }

    /**
     * @param $id
     * @return mixed|void
     * 分类编辑
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
            cache('DB_TREE_ARETYPE', null);   //删除栏目缓存
            if ($result){
                return ajaxReturn(lang('action_success'), url('index'));
            }else{
                return ajaxReturn($this->cModel->getError());
            }
        }else{
            $arctypeList = $this->cModel->treeList();
            $this->assign('arctypeList', $arctypeList);

            $amModel = new Articlemod();
            $modList = $amModel->where(['status' => 1])->order('sorts ASC,id ASC')->select();
            $this->assign('modList', $modList);

            $data = $this->cModel->get($id);
            $this->assign('data', $data);
            return $this->fetch();
        }
    }

    /**
     * 删除分类
     */
    public function delete()
    {
        if (request()->isPost()){
            $id = input('id');
            if (isset($id) && !empty($id)){
                $id_arr = explode(',', $id);
                $where = [ 'id' => ['in', $id_arr] ];
                $result = $this->cModel->where($where)->delete();
                cache('DB_TREE_ARETYPE', null);   //删除栏目缓存
                if ($result){
                    return ajaxReturn(lang('action_success'), url('index'));
                }else{
                    return ajaxReturn($this->cModel->getError());
                }
            }
        }
    }

}