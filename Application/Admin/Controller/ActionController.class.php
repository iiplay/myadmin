<?php
namespace Admin\Controller;
use Think\Controller;
class ActionController extends BaseController {
    public function index(){
        $data = M('Action_log')
            ->field('cx_action_log.*,cx_ad_user.ad_user')
            ->join('cx_ad_user on cx_action_log.user_id=cx_ad_user.id')
            ->select();
        $this->assign('data',$data);
        $this->display();
    }
}