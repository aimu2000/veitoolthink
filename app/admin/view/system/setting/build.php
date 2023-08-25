<div class="layui-fluid">
    <div class="layui-card">
        <div class="layui-tab layui-tab-admin" lay-filter="settings_top_tab">
            <ul class="layui-tab-title" id="settings_tab"></ul>
        </div>
        <div class="layui-card-body">
            <div class="layui-card-box">
                <div class="layui-btn-group">
                    <button class="layui-btn" id="top-settings-add"><i class="layui-icon layui-icon-add-circle"></i> 添加</button>
                    <button class="layui-btn" id="top-settings-del"><i class="layui-icon layui-icon-delete"></i> 删除</button>
                </div>
            </div>
            <table lay-filter="settings" id="settings"></table>
        </div>
    </div>
</div>
<!--JS部分-->
<script>
layui.use(['buildItems'],function(){
    var app_root = layui.cache.maps + 'system.setting/';
    var table=layui.table,form=layui.form,admin=layui.admin;
    var datas = <?=$datas?>;
    var types = {}; $.each(datas.types,function(k,v){types[k] = v +'：'+ k;}); /*重构[配置类型]数据*/
    /*解析顶部分组选项*/
    var tab = $("#settings_tab");
    layui.each(datas.groups,function(k,v){tab.append('<li group="'+ k +'">'+ v +'</li>');});
    var tab1 = tab.children("li").first();tab1.addClass('layui-this');
    var group = tab1.attr('group');
    /**/
    /*渲染数据*/
    table.render({
        elem: '#settings',
        url: app_root+"build?do=json",
        where:{group:group},
        cols: [[
            {type:'checkbox',fixed:'left'},
            {field:"name",edit:'text',title:"名称",width:180},
            {field:"title",edit:'text',title:"标题"},
            {field:"relation",edit:'text',title:"关联"},
            {field:"typename",align:'center',width:120,title:"类型",sort:!0},
            {field:"addtime",align:'center',title:"添加时间",width:160,sort:!0,templet:function(d){return layui.util.toDateString(d.addtime*1000)}},
            {field:'listorder',edit:'text',width:60,align:'center',title:'排序'},
            {field:'private',width:80,align:'center',templet:function(d){return '<input type="checkbox" name="private" lay-skin="switch" lay-text="是|否" lay-filter="settings-chang" value="'+d.private+'" data-json="'+encodeURIComponent(JSON.stringify(d))+'"'+(d.private==1 ? ' checked' : '')+'>';},unresize:true,title:'文本隐私'},
            {field:'state',width:80,align:'center',templet:function(d){return '<input type="checkbox" name="state" lay-skin="switch" lay-text="启用|禁用" lay-filter="settings-chang" value="'+d.state+'" data-json="'+encodeURIComponent(JSON.stringify(d))+'"'+(d.state==1 ? ' checked' : '')+'>';},unresize:true,title:'启用状态'},
            {fixed:'right',width:95,align:'center',toolbar:'<div><a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a><a class="layui-btn layui-btn-xs layui-btn-danger" lay-event="del">删除</a></div>',title:'操作'}
        ]],
        page: true,
        limit:{$limit}
    });/**/
    /*顶部选项卡监听*/
    layui.element.on('tab(settings_top_tab)',function(){
        group = this.getAttribute("group");
        table.reloadData('settings',{where:{group:group},page:{curr:1}});
    });/**/
    /*顶部添加按钮*/
    $('#top-settings-add').on('click',function(){settingsOpen();});
    /**/
    /*顶部删除按钮*/
    $('#top-settings-del').on('click',function(){
        var checkRows = table.checkStatus('settings').data;
        if(checkRows.length === 0){return layer.msg('请选择需删除的配置项');}
        var ids = checkRows.map(function(d){return d.id;});
        del(ids);
    });/**/
    /*快编监听*/
    table.on('edit(settings)',function(obj){
        admin.req(app_root+"bedit?do=up",{id:obj.data.id,av:obj.value,af:obj.field},function(res){
            layer.msg(res.msg,{shade:[0.4,'#000'],time:1500},function(){
                if(res.code==1 && obj.field=='listorder') table.reloadData('settings');
            });
        },'post',{headersToken:true});
    });/**/
    /*是否隐私、启用状态*/
    form.on('switch(settings-chang)',function(obj){
        var json = JSON.parse(decodeURIComponent($(this).data('json')));
        var av = obj.elem.checked ? 1 : 0;
        admin.req(app_root+"bedit?do=up",{id:json.id,av:av,af:obj.elem.name},function(res){
            layer.tips(res.msg,obj.othis,{time:2000});
        },'post',{headersToken:true});
    });/**/
    /*工具条监听*/
    table.on('tool(settings)', function(obj){
        var data = obj.data;
        if(obj.event === 'edit'){
            settingsOpen(data);
        }else if(obj.event === 'del'){
            del(data.id);
        }
    });/**/
    /*删除*/
    function del(ids){
        layer.confirm('确定要删除所选配置项吗？',function(){
            admin.req(app_root+"bdel",{id:ids},function(res){
                layer.msg(res.msg,{shade:[0.4,'#000'],time:1500},function(){
                    if(res.code===1) table.reloadData('settings');
                });
            },'post',{headersToken:true});
        });
    }/**/
    /*弹出窗*/
    function settingsOpen(Dt){
        admin.open({
            type: 1,
            bid: 'settings_items',
            btn: ['保存', '取消'],
            area: ['800px', '90%'],
            title: Dt ? '编辑配置项' : '添加配置项',
            success: function(l,index){
                layui.buildItems.build({
                    bid: 'settings_items',
                    data: [
                        {name:"id",type:"hidden"},
                        {name:"group",title:"配置分组",type:"radio",options:datas.groups,value:group,must:true},
                        {name:"type",title:"配置类型",type:"select",options:types,value:'',verify:'required',reqtext:'请选择配置类型',must:true},
                        {name:"name",title:"配置名称",type:"text",value:'',verify:'required',placeholder:"请输入配置名称",must:true},
                        {name:"title",title:"配置标题",type:"text",value:'',verify:'required',placeholder:"请输入配置标题",must:true},
                        {name:"value",title:"配置初值",type:"textarea",value:'',placeholder:"请输入配置初值"},
                        {name:"options",title:"配置选项",type:"textarea",value:'',placeholder:"用于单选、多选、下拉、联动等类型时请输入；时间选择器时用于配置range参数；文件上传时用于filetype[image、file、video、audio]参数"},
                        {name:"tips",title:"配置说明",type:"text",value:'',placeholder:"请输入配置说明"},
                        {name:"listorder",title:"排序编号",type:"number",value:10,verify:'required',placeholder:"请输入排序数字",must:true}
                    ]
                });
                form.val('settings_items_form',Dt);
                $("input").blur(function(){ /*输入框内容监测 判断配置名称是否已被占用*/
                    var o = $(this);
                    var obj = o.attr('name');
                    var val = o.val();
                    if(obj==='name' && val.length>0){
                        return admin.req(app_root+"build/do/check",{name:val,id:(Dt ? Dt.id : 0)},function(res){
                            if(res.code==0){
                                layer.msg(res.msg,{shade:[0.4,'#000'],time:1500},function(){o.val('');o.focus();});
                            }
                        },'post');
                    }
                });
                form.on('submit(settings_items)',function(data){
                    var btn = $(this);
                    if (btn.attr('stop')){return false}else{btn.attr('stop',1)}
                    admin.req(app_root + (data.field.id ? 'bedit' : 'badd'),data.field,function(res){
                        layer.msg(res.msg,{shade:[0.4,'#000'],time:1500},function(){
                            if(res.code==1){
                                layer.close(index);
                                table.reload('settings');
                            }
                            btn.removeAttr('stop');
                        });
                    },'post',{headersToken:true});
                    return false;
                });
            }
        });
    }/**/
});
</script>