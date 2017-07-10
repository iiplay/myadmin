<?php if (!defined('THINK_PATH')) exit();?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>H+ 后台主题UI框架 - Bootstrap Table</title>
    <link rel="shortcut icon" href="favicon.ico"> <link href="/myadmin/Public/Admin/css/bootstrap.min.css?v=3.3.5" rel="stylesheet">
    <link href="/myadmin/Public/Admin/css/font-awesome.min.css?v=4.4.0" rel="stylesheet">
    <link href="/myadmin/Public/Admin/css/plugins/bootstrap-table/bootstrap-table.min.css" rel="stylesheet">
    <link href="/myadmin/Public/Admin/css/animate.min.css" rel="stylesheet">
    <link href="/myadmin/Public/Admin/css/style.min.css?v=4.0.0" rel="stylesheet">
    <base target="_blank">
</head>
<body class="gray-bg">
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="ibox float-e-margins">
        <div class="col-sm-12">
            <!-- Example Events -->
            <div class="example-wrap">
                <div class="example">
                    <div class="row">
                        <div class="col-sm-12">

                                <div class="ibox float-e-margins">
                                    <div class="ibox-title">
                                        <h5>操作日志</h5>
                                        <div class="ibox-tools"> <a class="collapse-link"> <i class="fa fa-chevron-up"></i> </a> <a class="dropdown-toggle" data-toggle="dropdown" href="table_basic.html#"> <i class="fa fa-wrench"></i> </a>
                                            <ul class="dropdown-menu dropdown-user">
                                                <li><a href="table_basic.html#">选项1</a> </li>
                                                <li><a href="table_basic.html#">选项2</a> </li>
                                            </ul>
                                            <a class="close-link"> <i class="fa fa-times"></i> </a> </div>
                                    </div>
                                    <div class="ibox-content">
                                        <table class="table table-bordered">
                                            <thead>
                                            <tr>
                                                <th>操作</th>
                                                <th>操作人</th>
                                                <th>行为</th>
                                                <th>时间</th>
                                                <th>IP</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                                <?php if(is_array($data)): $i = 0; $__LIST__ = $data;if( count($__LIST__)==0 ) : echo "没有数据" ;else: foreach($__LIST__ as $key=>$data): $mod = ($i % 2 );++$i;?><tr>
                                                        <td></td>
                                                        <td><?php echo ($data['ad_user']); ?></td>
                                                        <td><?php echo ($data['remark']); ?></td>
                                                        <td><?php echo (date('Y-m-d H:i:s',$data['create_time'])); ?> </td>
                                                        <td><?php echo ($data['action_ip']); ?></td>
                                                    </tr><?php endforeach; endif; else: echo "没有数据" ;endif; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                        </div>
                    </div>
                </div>
            </div>
            <!-- End Example Events -->
        </div>
    </div>
</div>
</div>
<!-- End Panel Other -->
</div>
<script src="/myadmin/Public/Admin/js/jquery.min.js?v=2.1.4"></script>
<script src="/myadmin/Public/Admin/js/bootstrap.min.js?v=3.3.5"></script>
<script src="/myadmin/Public/Admin/js/content.min.js?v=1.0.0"></script>
<script src="/myadmin/Public/Admin/js/plugins/bootstrap-table/bootstrap-table.min.js"></script>
<script src="/myadmin/Public/Admin/js/plugins/bootstrap-table/bootstrap-table-mobile.min.js"></script>
<script src="/myadmin/Public/Admin/js/plugins/bootstrap-table/locale/bootstrap-table-zh-CN.min.js"></script>
<script src="/myadmin/Public/Admin/js/demo/bootstrap-table-demo.min.js"></script>
<script type="text/javascript" src="http://tajs.qq.com/stats?sId=9051096" charset="UTF-8"></script>

</body>

</html>