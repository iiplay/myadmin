<?php
/**
 * @Title: ajaxReturn
 * @Description: todo(ajax提交返回状态信息)
 * @param string $info
 * @param url $url
 * @param string $status
 * @author duqiu
 * @date 2016-5-12
 */
function ajaxReturn($info='', $url='', $status='', $data = ''){
    if(!empty($url)){   //操作成功
        $result = array( 'info' => '操作成功', 'status' => 1, 'url' => $url, );
    }else{   //操作失败
        $result = array( 'info' => '操作失败', 'status' => 0, 'url' => '', );
    }
    if(!empty($info)){$result['info'] = $info;}
    if(!empty($status)){$result['status'] = $status;}
    if(!empty($data)){$result['data'] = $data;}
    echo json_encode($result);
    exit();
}
/**
 * @Title: page_param
 * @Description: todo(分页额外参数)
 * @return array
 * @throws
 */
function page_param(){
    $param = request()->param();
    $res['query'] = $param;
    return $res;
}
/**
 * @Title: deldir
 * @Description: todo(删除文件-清理缓存)
 * @param string $dir
 * @param string $folder
 * @return boolean
 * @author duqiu
 * @date 2016-5-24
 */
/**
 * 删除文件。清理缓存
 * @param $dir
 * @param string $folder
 * @return bool
 */
function deldir($dir,$folder='n'){
    //删除当前文件夹下得文件（并不删除文件夹）：
    $dh=opendir($dir);
    while ($file=readdir($dh)) {
        if($file!="." && $file!="..") {
            $fullpath=$dir."/".$file;
            if(!is_dir($fullpath)) {
                unlink($fullpath);
            } else {
                deldir($fullpath,$folder);
            }
        }
    }
    closedir($dh);
    //删除当前文件夹
    if($folder=='y'){
        if(rmdir($dir)){
            return true;
        } else {
            return false;
        }
    }
}
function auto_description($d, $c){
    if( empty($d) ){
        if( !empty($c) ){
            $c = trimall(strip_tags(htmlspecialchars_decode($c)));   //转换标签-去掉HTML标签
            $c = csubstr($c, 250, '', 0, false);
            $result = $c;
        }else{
            $result = '';
        }
    }else{
        $result = $d;
    }
    return $result;
}
