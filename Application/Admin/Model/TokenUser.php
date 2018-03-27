<?php
namespace app\admin\model;

use think\Model;

class TokenUser extends Model
{
    /**
     * @param $uid
     * @param string $type
     * @param string $token_time
     * @return string
     */

    public function createToken($uid, $type = '1', $token_time = '')
    {
        $token = md5($uid.uniqid().rand(100000000, 999999999));
        $token_time = time() + $token_time;
        $where = [
            'uid' => $uid,
            'type' => $type,
        ];
        $isToken = $this->where($where)->find();
        if (!empty($isToken)){   //存在token
            $data = [
                'token' => $token,
                'token_time' => $token_time,
            ];
            $this->where($where)->update($data);
        }else{
            $data = [
                'uid' => $uid,
                'type' => $type,
                'token' => $token,
                'token_time' => $token_time,
            ];
            $this->create($data);
        }
        return $token;
    }
}