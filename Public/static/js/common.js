layui.use(['form','layer'], function(){
    var form = layui.form
        , layer = layui.layer;

    //全选
    form.on('checkbox(allChoose)', function(data){
        var child = $(data.elem).parents('table').find('tbody input[type="checkbox"]');
        child.each(function(index, item){
            item.checked = data.elem.checked;
        });
        form.render('checkbox');
    });

    //提交
    $('body').off('click', '.submits');
    $('body').on("click", '.submits', function(event){
        var _this = $(this);
        layer.load(2);
        var form = _this.closest('form');
        if(form.length){
            var ajax_option={
                dataType:'json',
                timeout:0,
                success:function(data){
                    layer.closeAll('loading');
                    if(data.status == '1'){
                        layer.msg(data.info, {icon: 1,time: 2000});
                        setTimeout(function(){
                            var url = data.url;
                            window.location.href = url;
                        },2000);
                    }else if(data.status == '2'){
                        layer.msg(data.info, {icon: 5,time: 2000});
                    }else{
                        layer.msg(data.info, {icon: 5,time: 2000});
                    }
                }
            }
            form.ajaxSubmit(ajax_option);
            layer.closeAll('loading');
        }
    });


    //单条删除-批量删除
    $('body').off('click', '.delete-one,.delete-all');
    $('body').on("click", '.delete-one,.delete-all', function(event){
        event.preventDefault();
        var _this = $(this);
        var title = _this.data('title')?_this.data('title'):'删除';
        var url_del = _this.data('url')||'';
        var message = _this.data('message')?_this.data('message'):'确认操作？';
        if(_this.hasClass('delete-all')){   //批量删除
            var id = '';
            var str = '';
            var table_box = _this.closest('.w_bg').find(".layui-table tbody tr td input[name='id[]']");
            $(table_box).each(function(){
                if(true == $(this).is(':checked')){
                    str += $(this).val() + ",";
                }
            });
            if(str.substr(str.length-1)== ','){
                id = str.substr(0, str.length-1);
            }
        }else{                              //单条删除
            var id = _this.data('id')||'';
        }
        if(id && url_del){
            layer.confirm(message, {
                title: title,
                btn: ['确认','取消'] //按钮
            }, function(){
                layer.load(2);
                $.ajax({
                    type : "post",
                    url : url_del,
                    dataType : 'json',
                    data : { id:id, },
                    success : function(data) {
                        layer.closeAll('loading');
                        if(data.status == '1'){
                            layer.msg(data.info, {icon: 1,time: 2000});
                            setTimeout(function(){
                                var url = data.url;
                                window.location.href = url;
                            },2000);
                        }else{
                            layer.msg(data.info, {icon: 5,time: 2000});
                        }
                    }
                });
            })
        }
    });

    //上传
    $('body').off('click', '.up-btn');
    $('body').on("click", '.up-btn', function(event){
        var _this_up_btn = $(this);   //当前上传按钮
        var up_url = _this_up_btn.data('url');   //上传地址
        var message = '<form method="POST" action="'+up_url+'" enctype="multipart/form-data" ><input type="file" name="imgFile" class="Uploads" /></form>';
        layer.confirm(message, {
            title: "上传 - Upload",
            btn: ['确认','取消'] //按钮
        }, function(){
            var form = $('.layui-layer-dialog').find('form');
            var ajax_option={
                dataType:'json',
                success:function(data){
                    if(data.error == '0'){
                        _this_up_btn.prev().attr("href", data.url);
                        _this_up_btn.prev().find('img').attr("src", data.url);
                        _this_up_btn.closest('.input-group').find('input').val(data.url);
                        layer.msg('上传成功', {icon: 1,time: 2000});
                    }else{
                        layer.msg(data.message, {icon: 5,time: 2000});
                    }
                }
            }
            form.ajaxSubmit(ajax_option);
        })
    });

    //跳转选择
    form.on('checkbox(jumpcheck)', function(data){
        if(data.elem.checked){
            $('#jumpurl').show();
        }else{
            $('#jumpurl').hide();
        }
    });

});
