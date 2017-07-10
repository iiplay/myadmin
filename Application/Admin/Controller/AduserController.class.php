<?php
namespace Admin\Controller;
use Think\Controller;
class AduserController extends BaseController {
    public function index(){
        $data = M('Ad_user')->select();
        $this->assign('data',$data);
        $this->display();
    }
}