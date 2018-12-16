function singwaapp_save(form) {
    var data = $(form).serialize();
    url = $(form).attr('url');

    $.post(url,data,function (res) {
        if(res.code == 0){
            layer.msg(res.msg,{icon:5,time:3000});
        }else if(res.code == 1){
            self.location=res.data.jump_url;
        }
    },'JSON');
}

function selecttime(flag){
    if(flag==1){
        var endTime = $("#countTimeend").val();
        if(endTime != ""){
            WdatePicker({dateFmt:'yyyy-MM-dd HH:mm',maxDate:endTime})}else{
            WdatePicker({dateFmt:'yyyy-MM-dd HH:mm'})}
    }else{
        var startTime = $("#countTimestart").val();
        if(startTime != ""){
            WdatePicker({dateFmt:'yyyy-MM-dd HH:mm',minDate:startTime})}else{
            WdatePicker({dateFmt:'yyyy-MM-dd HH:mm'})}
    }
}

//通用化删除
function del(obj){
    url = $(obj).attr('del_url');
    layer.confirm('确认要删除吗？',function(index){
        $.ajax({
            type: 'POST',
            url: url,
            dataType: 'json',
            success: function(res){
                if(res.code == 1){
                    self.location = res.data.jump_url;
                }else if(res.code == 0){
                    layer.msg(res.msg,{icon:5,time:3000});
                }
            },
            error:function(res) {
                console.log(res.msg);
            },
        });
    });
}

//通用化修改状态
function status(obj){
    url = $(obj).attr('status_url');
    layer.confirm('确认要修改吗？',function(index){
        $.ajax({
            type: 'POST',
            url: url,
            dataType: 'json',
            success: function(res){
                if(res.code == 1){
                    self.location = res.data.jump_url;
                }else if(res.code == 0){
                    layer.msg(res.msg,{icon:5,time:3000});
                }
            },
            error:function(res) {
                console.log(res.msg);
            },
        });
    });
}

/*通用化编辑*/
function edit(title,url,id,w,h){
    var index = layer.open({
        type: 2,
        title: title,
        content: url
    });
    layer.full(index);
}

