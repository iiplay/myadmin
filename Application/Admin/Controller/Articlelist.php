<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/20
 * Time: 14:54
 */

namespace app\admin\controller;
use app\admin\model\Articlelist as Articlelists;
use app\admin\model\Articleclassify;
use app\admin\model\Articlemod;
use think\Db;


class Articlelist extends Common
{
    private $cModel;   //当前控制器关联模型

    public function _initialize()
    {
        parent::_initialize();
        $this->cModel = new Articlelists;   //别名：避免与控制名冲突
    }
    public function index(){
        $where = [];
        if (input('get.search')){
            $where['title|keywords|description'] = ['like', '%'.input('get.search').'%'];
        }
        if (input('get._sort')){
            $order = explode(',', input('get._sort'));
            $order = $order[0].' '.$order[1];
        }else{
            $order = 'id desc';
        }
        $datalist = $this->cModel->where($where)->order($order)->paginate('', false, page_param());
        foreach ($datalist as $k => $v){
            if(!empty($v['flag'])){ $datalist[$k]['flag'] = explode(',', $v['flag']); }
            $v->Articleclassify;   //关联栏目数据
            $v->User;
            if($datalist[$k]['flag'] && in_array('j', $datalist[$k]['flag']) && !empty($v['jumplink'])){
                $datalist[$k]['arcurl'] = $v['jumplink'];
            }else{
                if(isset($v->Articleclassify->dirs)){
                    $datalist[$k]['arcurl'] = url('detail/'.$v->Articleclassify->dirs.'/'.$v['id']);
                }else{
                    $datalist[$k]['arcurl'] = '';
                }
            }
            $addonMod = $v['mod'];
            $v->$addonMod;
            $datalist[$k]['addondata'] = $v->$addonMod;
            unset($datalist[$k][$addonMod]);
        }
        $this->assign('datalist', $datalist);
        return $this->fetch();
    }

    /*
     * @param $typeid
     * @return mixed
     * 新增文章
     */
    public function create($typeid)
    {
        if (request()->isPost()){
            Db::startTrans();
            try{
                $data = input('post.');
                $data['create_time'] = strtotime($data['create_time']);
                if (isset($data['flag']) || isset($data['litpic'])){
                    $data['flag'] = $this->_flag($data['flag'], $data['litpic']);
                }
                $result = $this->cModel->validate(CONTROLLER_NAME.'.add')->allowField(true)->save($data);
                $data['aid'] = $this->cModel->getLastInsID();
                $mod = $data['mod'];
                $addonData = db($mod)->field('id', true)->strict(false)->insert($data);   //新增关联表数据
                // 提交事务
                if ($result && $addonData){
                    Db::commit();
                    return ajaxReturn(lang('action_success'), url('Articlelist/index'));
                }else{
                    return ajaxReturn($this->cModel->getError());
                }
            } catch (\Exception $e) {
                // 回滚事务
                Db::rollback();
                return ajaxReturn($e->getMessage());
            }
        }else{
            $atModel = new Articleclassify();
            $arctypeList = $atModel->treeList();
            $this->assign('arctypeList', $arctypeList);

            $arcData = $atModel->where(['id' => $typeid])->find();   //栏目数据
            $atmModel = new Articlemod();
            $where = [ 'id' => $arcData['mid'] ];
            $mod = $atmModel->where($where)->field('mod')->find();
            $mod = $mod['mod'];
            $this->assign('mods', $mod);   //文章拓展表模型

            $data['typeid'] = $arcData['id'];
            $data['create_time'] = date('Y-m-d H:i:s', time());
            $data['mid'] = $arcData['mid'];
            $this->assign('data', $data);

            return $this->fetch('edit');
        }
    }

    /*
     * @param $id
     * @return mixed
     * 文章编辑
     */
    public function edit($id)
    {
        if (request()->isPost()){
            $data = input('post.');
            if (isset($data['create_time'])){
                $data['create_time'] = strtotime($data['create_time']);
            }
            if (isset($data['flag']) || isset($data['litpic'])){
                $data['flag'] = $this->_flag($data['flag'], $data['litpic']);
            }
            if (count($data) == 2){
                foreach ($data as $k =>$v){
                    $fv = $k!='id' ? $k : '';
                }
                $result = $this->cModel->validate(CONTROLLER_NAME.'.'.$fv)->allowField(true)->save($data, $data['id']);
            }else{
                $result = $this->cModel->validate(CONTROLLER_NAME.'.edit')->allowField(true)->save($data, $data['id']);
                $mod = $data['mod'];
                $addonData = db($mod)->field('id', true)->strict(false)->where( 'aid='.$data['id'] )->update($data);   //关联表数据
            }
            if ($result){
                return ajaxReturn(lang('action_success'), url('index'));
            }else{
                return ajaxReturn($this->cModel->getError());
            }
        }else{
            $atModel = new Articleclassify();
            $arctypeList = $atModel->treeList();

            $this->assign('arctypeList', $arctypeList);

            $data = $this->cModel->get($id);
            $addonMod = $data['mod'];
            $data['addondata'] = $data->$addonMod;   //拓展表数据
            unset($data[$data['mod']]);

            $atmModel = new Articlemod();
            $data['mid'] = $atmModel->where(['mod' => $addonMod])->value('id');

            $this->assign('mods', $addonMod);

            if (!empty($data['flag'])){
                $data['flag'] = explode(',', $data['flag']);
            }
            $this->assign('data', $data);
            return $this->fetch();
        }
    }

    /**
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     * 删除文章
     */
    public function delete()
    {
        if (request()->isPost()){
            $id = input('id');
            if (isset($id) && !empty($id)){
                $id_arr = explode(',', $id);
                if (!empty($id_arr)){

                    foreach ($id_arr as $val){
                        $addonMod = $this->cModel->where(['id' => $val])->value('mod');
                        $this->cModel->where('id='.$val)->delete();
                        db($addonMod)->where('aid='.$val)->delete();   //关联表数据
                    }
                    return ajaxReturn(lang('action_success'), url('index'));
                }else{
                    return ajaxReturn(lang('action_fail'));
                }
            }
        }
    }

    /*
     * 属性选择
     */
    private function _flag($flag, $litpic)
    {
        if(empty($flag)){ $flag=array(); }
        if($litpic != ''){
            array_push($flag, "p");
        }else{
            $flag = unset_array("p", $flag);
        }
        $flag_arr = array_unique($flag);
        $result = implode(',', $flag_arr );
        return $result;
    }


}