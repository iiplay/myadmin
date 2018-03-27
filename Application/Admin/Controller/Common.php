<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/17
 * Time: 9:38
 */

namespace app\admin\controller;


use think\Controller;
use app\admin\model\Config;
use app\admin\model\TokenUser;
use expand\Auth;

class Common extends Controller
{
    /**
     * 基础控制器初始化
     */
    public function _initialize()
    {
        $this->restLogin();
        $userId = session('userId');
        define('UID', $userId);   //设置登陆用户ID常量

        define('MODULE_NAME', request()->module());
        define('CONTROLLER_NAME', request()->controller());
        define('ACTION_NAME', request()->action());

        //判断是否为PJAX
        $box_is_pjax = $this->request->isPjax();
        $this->assign('box_is_pjax', $box_is_pjax);

//        $treeMenu = $this->treeMenu();
//        $this->assign('treeMenu', $treeMenu);

        //加载多语言相应控制器对应字段
//        if($_COOKIE['think_var']){
//            $langField = $_COOKIE['think_var'];
//        }else{
//            $langField = config('default_lang');
//        }
//        Lang::load(APP_PATH . 'admin/lang/'.$langField.'/'.CONTROLLER_NAME.'.php');

        //权限判断
        if(UID == -1){ return true; }   //配置账号跳过权限

        //跳过权限
        $jump_auth = [
            'Index/icons',
            'Index/forms',
            'Index/box',
            'Index/tab',
            'Index/tables',
            'Index/question',
            'Config/sendemail',
            'Index/show', //todo
        ];
        if (in_array(CONTROLLER_NAME.'/'.ACTION_NAME, $jump_auth)){
            return true;
        }

        $isbrowse = Config::getByK('isbrowse');
        if ( $isbrowse['v'] == 1){   //是否开启浏览模式
            if (input('post.')){
                return ajaxReturn(lang('isbrowse'));
            }
        }

        $auth = new Auth();
        if (!$auth->check(CONTROLLER_NAME.'/'.ACTION_NAME, UID)){
            $this->error(lang('auth_no_exist'), url('Login/index'));
        }
    }
    private function restLogin()
    {

        $login = new Login();
        $userId = session('userId');
        if (empty($userId)){   //未登录
            $login->loginOut();
        }
        $config = new Config();
        $login_time = $config->where(['type'=>'system', 'k'=>'login_time'])->value('v');
        $now_token = session('user_token');   //当前token

        $tkModel = new TokenUser();
        $db_token = $tkModel->where(['uid'=>$userId, 'type'=>'1'])->find();   //数据库token
        if ($db_token['token'] != $now_token){   //其他地方登录
            $this->loginBox(lang('login_other'));
        }else{
            if ($db_token['token_time'] < time()){   //登录超时
                session('userId', null);
                session('user_token', null);
                cookie('name', null);
                cookie('uname', null);
                cookie('uid', null);
                cookie('avatar', null);
                $this->error(lang('login_timeout'), url('Login/index'));
//                $this->loginBox(lang('login_timeout'));
            }else{
                $token_time = time() + $login_time;
                $data = ['token_time' => $token_time];
                $tkModel->where(['uid'=>$userId, 'type'=>'1'])->update($data);
            }
        }
        return;
    }
    private function loginBox($info='')
    {
        if (request()->isGet()){
            $rest_login = 1;
            $this->assign('rest_login_info', $info);
            $this->assign('rest_login', $rest_login);
        }else{
            ajaxReturn($info, '', 2);
        }
    }

}