<?php
//配置文件
return [
    // 默认AJAX 数据返回格式,可选json xml ...
    'default_ajax_return'    => 'html',
    'view_replace_str' => [
        '__STATIC__' =>'/static',
        '__CSS__' => '/static/css',
        '__JS__' => '/static/js',
        '__LAYUI__' => '/static/layui',
        '__IMAGES__' => '/static/images',
    ],
];