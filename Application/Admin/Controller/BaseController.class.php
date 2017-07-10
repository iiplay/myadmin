<?php
namespace Admin\Controller;
use Think\Controller;
class BaseController extends Controller {
    public function _initialize(){
       $is_login = user_islogin();
       if (empty($is_login['uid'])) {
            header("Location: " . U('Login/index'));
            die;
       }
    }

}