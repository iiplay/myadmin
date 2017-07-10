<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">


<!-- Mirrored from www.zi-han.net/theme/hplus/login_v2.html by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 03 Mar 2016 12:30:00 GMT -->
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">

    <title>H+ 后台主题UI框架 - 登录</title>
    <meta name="keywords" content="H+后台主题,后台bootstrap框架,会员中心主题,后台HTML,响应式后台">
    <meta name="description" content="H+是一个完全响应式，基于Bootstrap3最新版本开发的扁平化主题，她采用了主流的左右两栏式布局，使用了Html5+CSS3等现代技术">
    <link href="/myadmin/Public/Admin/css/bootstrap.min.css" rel="stylesheet">
    <link href="/myadmin/Public/Admin/css/font-awesome.min93e3.css?v=4.4.0" rel="stylesheet">
    <link href="/myadmin/Public/Admin/css/animate.min.css" rel="stylesheet">
    <link href="/myadmin/Public/Admin/css/style.min.css" rel="stylesheet">
    <link href="/myadmin/Public/Admin/css/login.min.css" rel="stylesheet">
	<script type="text/javascript" src="/myadmin/Public/Admin/layer/jquery-1.8.3.min.js"></script>
    <script type="text/javascript" src="/myadmin/Public/Admin/layer/jquery.form.js"></script>
    <script type="text/javascript" src="/myadmin/Public/Admin/layer/layer.js"></script>
    <!--[if lt IE 9]>
    <meta http-equiv="refresh" content="0;ie.html" />
    <![endif]-->
    <script>
        if(window.top!==window.self){window.top.location=window.location};
    </script>
	
	    <script type="text/javascript">
        $(function(){
            var optinos = {
                url: "<?php echo U('Login/cheklogin');?>",
                type: "post",
                dataType:'json',
                error:function(){
                            layer.msg('无法访问数据库，请检查你的数据库配置',function(){})
                        },
                success:function(res){
                    if(res.error==1){
                        layer.msg(res.msg);
                        $('#loging').html("<button class='btn btn-warning btn-block'>重新登陆</button>")
                    }else if(res.error == 2){
                        layer.msg(res.msg);
                        $('#loging').html("<button class='btn btn-info btn-block'>重新登陆</button>")
                    }else{
                        layer.msg(res.msg);
                        setTimeout(function(){window.location=res.url;},2000);
                        $('#loging').html("<button class='btn btn-danger btn-block'>正在登陆</button>")
                    }
                }
            };
            $('#doLogin').ajaxForm(optinos);
        });
    </script>

</head>

<body class="signin">
    <div class="signinpanel">
        <div class="row">
            <div class="col-sm-7">
                <div class="signin-info">
                    <div class="logopanel m-b">
                        <h1>[ H+ ]</h1>
                    </div>
                    <div class="m-b"></div>
                    <h4>欢迎使用 <strong>H+ 后台主题UI框架</strong></h4>
                    <ul class="m-b">
                        <li><i class="fa fa-arrow-circle-o-right m-r-xs"></i> 优势一</li>
                        <li><i class="fa fa-arrow-circle-o-right m-r-xs"></i> 优势二</li>
                        <li><i class="fa fa-arrow-circle-o-right m-r-xs"></i> 优势三</li>
                        <li><i class="fa fa-arrow-circle-o-right m-r-xs"></i> 优势四</li>
                        <li><i class="fa fa-arrow-circle-o-right m-r-xs"></i> 优势五</li>
                    </ul>
                    <strong>还没有账号？ <a href="#">立即注册&raquo;</a></strong>
                </div>
            </div>
            <div class="col-sm-5">
                <form method="post" action="" id="doLogin">
                    <h4 class="no-margins">登录：</h4>
                    <p class="m-t-md">登录到H+后台主题UI框架</p>
                    <input type="text" class="form-control uname" placeholder="用户名" name="user"/>
                    <input type="password" class="form-control pword m-b" placeholder="密码" name="pass"/>
                    <a href="#">忘记密码了？</a>
                    <span id="loging">
						<button class="btn btn-success btn-block">登录</button>
					</span>
                </form>
            </div>
        </div>
        <div class="signup-footer">
            <div class="pull-left">
                &copy; 2015 All Rights Reserved. H+
            </div>
        </div>
    </div>
</body>


<!-- Mirrored from www.zi-han.net/theme/hplus/login_v2.html by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 03 Mar 2016 12:30:01 GMT -->
</html>