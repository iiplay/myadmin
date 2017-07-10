<?php
namespace Admin\Controller;
use Think\Model;
use Think\Controller;
class LoginController extends Controller {
    public function index(){
        $this->display();
    }
    public function cheklogin()
    {
    	//获取账号密码
    	$user = I('post.user');
    	//加密密码  详情请看 coment文件下function.php
        $pass = I('post.pass');
        if(empty($user)||empty($pass)){
            $res['error']=1;
            $res['msg']="请输入帐号密码";
            $this->ajaxReturn($res);
        }

        list($uid, $username, $password) =   getPwd(I('post.user'),I('post.pass')) ;

        if ($uid=='-1') {
            $res['error']=2;
            $res['msg']="用户不存在";
            $this->ajaxReturn($res);
        }elseif($uid=='-2'){
            $res['error']=2;
            $res['msg']="密码不正确";
            $this->ajaxReturn($res);
        }elseif($uid>0){
            $setarr = array(
                'uid' => $uid,
                'username' => addslashes($username),
                'password' => addslashes($password)
            );
            $cookietime = time() + 30*60;
            cookie('auth', cx_authcode("$setarr[password]\t$setarr[uid]", 'ENCODE'), $cookietime);
            //记录行为
            action_log('user_login', 'member', $uid, $uid);

            $res['error']=0;
            $res['msg']="正在登陆中...";
            $res['url']=U('Index/index');
            $this->ajaxReturn($res);
        }

    	
    }
}