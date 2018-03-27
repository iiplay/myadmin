<?php
namespace app\admin\controller;

    class Index extends Common
{
    public function index()
    {
        return $this->fetch();
    }

    public function show()
    {
        $systemConfig = $this->systemConfig();              //服务器信息配置
        $this->assign('systemConfig', $systemConfig);
//        halt($systemConfig);
        return $this->fetch();
    }
    /**
     * @Title: 服务器信息配置
     * @date 2017年11月14日
     */
    private function systemConfig()
    {
        return $config = [
            '操作系统' => PHP_OS,
            '服务器时间' => date("Y-m-d H:i:s"),
            '运行环境' => $_SERVER["SERVER_SOFTWARE"],
            'PHP运行方式' => php_sapi_name(),
            '上传附件限制' => ini_get('upload_max_filesize'),
            '执行时间限制' => ini_get('max_execution_time').'秒',
        ];
    }

        /**
         * 清理缓存
         * @return mixed|void
         */
    public function cleanCache()
    {
        if (request()->isPost()){
            deldir(RUNTIME_PATH);
            return '清理成功';
        }else{
            deldir(RUNTIME_PATH);
            return $this->fetch();
        }
    }
}
