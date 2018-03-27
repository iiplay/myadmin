<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/21
 * Time: 11:17
 */

namespace app\admin\controller;
use think\Controller;
use think\Image;
use app\admin\model\UserInfo;

class Uploads extends Controller
{
    public $file_move_path;   //上传文件移动服务器位置
    public $file_back_path;   //上传文件返回文件地址
    public function _initialize()
    {
        parent::_initialize();
        if (!session('userId')){
            exit();
        }
        $this->file_move_path = WEB_PATH.DS.'uploads'.DS.'article/';
        $this->file_back_path = DS.'uploads/article'.DS.$this->up_type;
    }

    /**
     * 文件上传
     */
    public function upload()
    {
        $up_config = cache('DB_UP_CONFIG');   //获取数据库中的上传文件配置信息缓存
        $file = request()->file('imgFile');
        if ($file){
            $info = $file->validate(['size'=>$up_config['image_size'], 'ext' => $up_config[$this->up_type.'_format'] ])
                ->move($this->file_move_path);
            if($info){
                if ( $this->up_type == 'image' && $up_config['isprint'] == 1){   //上传图片，加水印
                    $file = Image::open( $this->file_move_path.DS.$info->getSaveName() );   //打开上传的图片
                    //水印图片、水印位置、水印透明度 -> 保存同名图片覆盖
                    $file->water(WEB_PATH.$up_config['image_print'], $up_config['print_position'], $up_config['print_blur'])
                        ->save( $this->file_move_path.DS.$info->getSaveName() );
                }
                $file_path = $this->file_back_path.DS.$info->getSaveName();
                $file_path = $up_config['image_url'].str_replace('\\', '/', $file_path);
                echo json_encode(['error' => 0, 'url' => $file_path]);
            }else{
                $this->_alert($file->getError());
            }
        }else{
            $this->_alert('请选择文件');
        }
    }
    /**
     * @return string
     * 上传图片裁剪
     */
    public function cropper()
    {
        $file = request()->file('avatar_file');dump($file);
        if ($file){
            $data = input('post.');
            $id = $data['id'];   //用户ID
            $avatar_data = json_decode(htmlspecialchars_decode($data['avatar_data']), true);
            $start_x = $avatar_data['x'];       //起始x
            $start_y = $avatar_data['y'];       //起始y
            $end_x   = $avatar_data['width'];   //结束x
            $end_y   = $avatar_data['height'];  //结束y
            $rotate  = $avatar_data['rotate'];  //旋转角度[有正负]

            $uiModel = new UserInfo();
            $data = $uiModel->where('uid', $id)->find();
            $oldAvatar = $data['avatar'];   //旧头像

            $image = Image::open( $file );   //打开上传的图片
            $extension = end(explode('.', $_FILES["avatar_file"]["name"])); //后缀
            $name = DS.date('Ymd', time()).DS.date('YmdHis', time())."_".rand(100000, 999999).".".$extension;
            $path = $this->file_move_path.$name;
            $back = str_replace('\\', '/', $this->file_back_path.$name);
            //生成目录
            if ( !file_exists($this->file_move_path.DS.date('Ymd', time())) ){
                mkdir($this->file_move_path);
                mkdir($this->file_move_path.DS.date('Ymd', time()));
            }
            $image->rotate($rotate)->crop($end_x, $end_y, $start_x, $start_y, 200, 200)->save($path, null, 100);

            if (file_exists($path)){    //检测图片是否保存成功
                $data = ['avatar' => $back];
                $where = ['uid' => $id];
                $result = $uiModel->allowField(true)->where($where)->update($data);
                if ($result){   //保存成功再删除旧头像
                    if ($oldAvatar != '/static/global/face/default.png'){      //删除之前头像
                        unlink(WEB_PATH.$oldAvatar);
                    }
                    $res = [
                        'state'  => 200,
                        'message' => 'success',
                        'result' => $back
                    ];
                }else{
                    $res = [
                        'state'  => 200,
                        'message' => '头像数据保存失败',
                        'result' => ''
                    ];
                }
            }else{
                $res = [
                    'state'  => 200,
                    'message' => '图片保存失败，请检查目录是否生成',
                    'result' => ''
                ];
            }
            return json_encode($res);
        }
    }
    public function _alert($msg)
    {
        header('Content-type: text/html; charset=UTF-8');
        echo json_encode(['error' => 1, 'message' => $msg]);
        exit;
    }

}