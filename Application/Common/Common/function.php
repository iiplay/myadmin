<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/26 0026
 * Time: 16:13
 */

//处理密码
function getPwd($username='', $password=''){

    $post = array(
        'ad_user' => $username,
    );
    $User = M('ad_user');
    $data = $User->where($post)->find();
    $passwordmd5 = preg_match('/^\w{32}$/', $password) ? $password : md5($password);

    if(empty($data)) {
        $status = -1;   //找不到该用户
    } elseif($data['ad_password'] != md5($passwordmd5.$data['salt'])) {
        $status = -2;   //密码错误
    }  else {
        $status = $data['id'];
    }
    return array($status, $data['username'], $data['password']);
}

//加密解密
function cx_authcode($string, $operation = 'DECODE', $key = '', $expiry = 0) {
    $ckey_length = 4;
    $key = md5($key ? $key : UC_KEY);
    $keya = md5(substr($key, 0, 16));
    $keyb = md5(substr($key, 16, 16));
    $keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : '';

    $cryptkey = $keya.md5($keya.$keyc);
    $key_length = strlen($cryptkey);

    $string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;
    $string_length = strlen($string);

    $result = '';
    $box = range(0, 255);

    $rndkey = array();
    for($i = 0; $i <= 255; $i++) {
        $rndkey[$i] = ord($cryptkey[$i % $key_length]);
    }

    for($j = $i = 0; $i < 256; $i++) {
        $j = ($j + $box[$i] + $rndkey[$i]) % 256;
        $tmp = $box[$i];
        $box[$i] = $box[$j];
        $box[$j] = $tmp;
    }

    for($a = $j = $i = 0; $i < $string_length; $i++) {
        $a = ($a + 1) % 256;
        $j = ($j + $box[$a]) % 256;
        $tmp = $box[$a];
        $box[$a] = $box[$j];
        $box[$j] = $tmp;
        $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
    }

    if($operation == 'DECODE') {
        date_default_timezone_set('PRC');
        if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {
            return substr($result, 26);
        } else {
            return '';
        }
    } else {
        return $keyc.str_replace('=', '', base64_encode($result));
    }
}

//判断用户登录
function user_islogin()
{
    if(@cookie('auth')) {
        @list($password, $uid) = explode("\t", cx_authcode(cookie('auth'), 'DECODE'));
        $_HGLOBAL['cx_uid'] = intval($uid);

        $post = array(
            'id' => $_HGLOBAL['cx_uid'],
        );
        $User = M('ad_user');
        $data = $User->where($post)->find();

        if($data) {
            if($data['password'] == $password) {
                $_HGLOBAL['cx_username'] = addslashes($data['ad_user']);
            } else {
                $_HGLOBAL['cx_uid']='';
            }
        } else {
            cookie(null);
        }
        $login=array('uid'=>$_HGLOBAL['cx_uid'],'username'=>$_HGLOBAL['cx_username']);
        return $login;
    }
}

/**
 * 根据用户ID获取用户名
 * @param  integer $uid 用户ID
 * @return string       用户名
 */
function get_username($uid = 0){
        $condition = array(
            'id' => $uid,
        );
        $info=M('ad_user')->where($condition)->getField('ad_user');
        if(!empty($info)){
            $name = $info;
        } else {
            $name = '';
        }
    return $name;
}

/**
 * 时间戳格式化
 * @param int $time
 * @return string 完整的时间显示
 */
function time_format($time = NULL,$format='Y-m-d H:i'){
    $time = $time === NULL ? NOW_TIME : intval($time);
    return date($format, $time);
}

/**
 * 记录行为日志，并执行该行为的规则
 * @param string $action 行为标识
 * @param string $model 触发行为的模型名
 * @param int $record_id 触发行为的记录id
 * @param int $user_id 执行行为的用户id
 * @return boolean
 */
function action_log($action = null, $model = null, $record_id = null, $user_id = null){
    //参数检查
    if(empty($action) || empty($model) || empty($record_id)){
        return '参数不能为空';
    }
    if(empty($user_id)){
        $user_id = is_login();
    }
    //查询行为,判断是否执行
    $where['name'] = $action;
    $action_info = M('Action')->where($where)->find();

    if($action_info['status'] != 1){
        return '该行为被禁用或删除';
    }

    $now_time=time();
    //插入行为日志
    $data['action_id']      =   $action_info['id'];
    $data['user_id']        =   $user_id;
    $data['action_ip']      =   ip2long(get_client_ip());
    $data['model']          =   $model;
    $data['record_id']      =   $record_id;
    $data['create_time']    =   $now_time;
    //解析日志规则,生成日志备注
    if(!empty($action_info['log'])){
        if(preg_match_all('/\[(\S+?)\]/', $action_info['log'], $match)){
            $log['user']    =   $user_id;
            $log['record']  =   $record_id;
            $log['model']   =   $model;
            $log['time']    =   $now_time;
            $log['data']    =   array('user'=>$user_id,'model'=>$model,'record'=>$record_id,'time'=>$now_time);
            foreach ($match[1] as $value){
                $param = explode('|', $value);
                if(isset($param[1])){
                    $replace[] = call_user_func($param[1],$log[$param[0]]);
                }else{
                    $replace[] = $log[$param[0]];
                }
            }
            $data['remark'] =   str_replace($match[0], $replace, $action_info['log']);
        }else{
            $data['remark'] =   $action_info['log'];
        }
    }else{
        //未定义日志规则，记录操作url
        $data['remark']     =   '操作url：'.$_SERVER['REQUEST_URI'];
    }
	M('ActionLog')->add($data);  //返回数据ID
    if(!empty($action_info['rule'])){
        //解析行为
        //$rules = parse_action($action, $user_id);
        //执行行为
        //$res = execute_action($rules, $action_info['id'], $user_id);
    }
}