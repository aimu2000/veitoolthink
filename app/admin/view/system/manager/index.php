<style>
#organizationTreeBar{padding:10px 15px;border:1px solid #e6e6e6;background-color:#f2f2f2}
#organizationTree{border:1px solid #e6e6e6;border-top:none;padding:10px 5px;overflow:auto;height:-webkit-calc(100vh - 260px);height:-moz-calc(100vh - 260px);height:calc(100vh - 260px)}
.layui-tree-entry .layui-tree-txt{padding:0 5px;border:1px transparent solid;text-decoration:none!important}
.layui-tree-entry.organ-tree-click .layui-tree-txt{background-color:#fff3e0;border:1px #ffe6b0 solid}
</style>
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md3">
            <div class="layui-card">
                <div class="layui-card-body" style="padding:10px;">
                    <!-- 树工具栏 -->
                    <div class="layui-form toolbar" id="organizationTreeBar">
                        <div class="layui-btn-group">
                            <button id="organ-add" class="layui-btn layui-btn-sm icon-btn"><i class="layui-icon">&#xe654;</i>添加</button>
                            <button id="organ-edit" class="layui-btn layui-btn-sm icon-btn"><i class="layui-icon">&#xe642;</i>修改</button>
                            <button id="organ-del" class="layui-btn layui-btn-sm icon-btn"><i class="layui-icon">&#xe640;</i>删除</button>
                        </div>
                    </div>
                    <!-- 左树 -->
                    <div id="organizationTree"></div>
                </div>
            </div>
        </div>
        <div class="layui-col-md9">
            <div class="layui-card">
                <div class="layui-card-header">
                    <form class="layui-form" lay-filter="manager-form-search">
                        <div class="layui-form-item">
                            <div class="layui-inline" style="width:72px;">
                                <select name="fields">
                                    <option value="">属性</option>
                                    <option value="0">帐号</option>
                                    <option value="1">姓名</option>
                                    <option value="2">电话</option>
                                    <option value="3">IP</option>
                                </select>
                            </div>
                            <div class="layui-inline" style="width:120px;"><input type="text" name="kw" placeholder="关键词" autocomplete="off" class="layui-input" lay-affix="clear"></div>
                            <div class="layui-inline" style="margin-right:0;">
                                <div class="layui-input-inline" style="width:192px;"><input type="text" name="sotime" id="manager-search-time" placeholder="登录时间" autocomplete="off" class="layui-input" lay-affix="clear"></div>
                            </div>
                            <div class="layui-inline" style="width:110px;"><select name="roleid" id="search_roles_select"></select></div>
                            <div class="layui-inline" style="width:72px;">
                                <select name="state">
                                    <option value="">状态</option>
                                    <option value="1">正常</option>
                                    <option value="0">禁用</option>
                                </select>
                            </div>
                            <div class="layui-inline">
                                <div class="layui-btn-group">
                                    <button class="layui-btn" lay-submit lay-filter="top-manager-search"><i class="layui-icon layui-icon-search layuiadmin-button-btn"></i> 搜索</button>
                                    <button class="layui-btn" lay-submit lay-filter="top-manager-all"><i class="layui-icon layui-icon-light"></i>全部</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="layui-card-body">
                    <div class="layui-card-box">
                        <div class="layui-btn-group">
                            <button class="layui-btn" id="top-manager-add"><i class="layui-icon layui-icon-add-circle"></i> 添加</button>
                            <button class="layui-btn" id="top-manager-del"><i class="layui-icon layui-icon-delete"></i> 删除</button>
                            <a class="layui-btn" href="#/system.manager/index/action=info"><i class="layui-icon layui-icon-username"></i> 个人中心</a>
                        </div>
                    </div>
                    <table lay-filter="manager" id="manager"></table>
                </div>
            </div>
        </div>
    </div>
</div>
<!--JS部分-->
<script type="text/javascript">
layui.use(['vinfo', 'xmSelect', 'buildItems'], function(){
    var groupid = '';
    var map_root = layui.cache.maps;
    var app_root = map_root + 'system.manager/';
    var layer=layui.layer,table=layui.table,form=layui.form,admin=layui.admin;
    var Roles = {$roles|raw};
    var roles_select = '<option value="">选择角色</option>'; $.each(Roles,function(k,v){roles_select += '<option value="'+ k +'">'+ v +'</option>';});
    /*==============左树结构===============*/
    var organObj,organData; //左树 选中数据 和 总树数据
    function renderTree(data){
        if(data){
            organData = toTree(data);
            doTree(organData);
        }else{
            admin.req(app_root + "index?do=organ",function(res){
                organData = toTree(res);
                doTree(organData);
            });
        }
    }
    function doTree(data){
        layui.tree.render({
            id: 'organTree',
            elem: '#organizationTree',
            onlyIconControl: true,
            data: data,
            click: function(obj){
                $('#organizationTree').find('.organ-tree-click').removeClass('organ-tree-click');
                $(obj.elem).children('.layui-tree-entry').addClass('organ-tree-click');
                groupid = obj.data.id;
                if(organObj){
                    table.reloadData('manager',{where:{groupid:groupid},page:{curr:1}});
                }
                organObj = obj;
            }
        });
        $('#organizationTree').find('.layui-tree-entry:first>.layui-tree-main>.layui-tree-txt').trigger('click');
    }
    renderTree(<?=$organ?>);
    /*左树添加按钮*/
    $('#organ-add').on('click',function(){organOpen();});/**/
    /*左树编辑按钮*/
    $('#organ-edit').on('click',function(){organOpen(organObj.data);});/**/
    /*左树删除按钮*/
    $('#organ-del').on('click', function(){
        if(!organObj) return layer.msg('未选择机构');
        layer.confirm('确定要删除所选机构吗？',function(index){
            layer.close(index);
            admin.req(app_root+"odel",{id:organObj.data.id},function(res){
                layer.msg(res.msg,{shade:[0.4,'#000'],time:1500},function(){
                    if(res.code==1){
                        organObj = null;
                        renderTree(res.data);
                    }
                });
            },'post');
        });
    });/**/
    /*树形编辑弹窗*/
    function organOpen(Dt){
        admin.open({
            type: 1,
            bid: 'organ_items',
            btn: ['保存', '取消'],
            area: ['500px', '500px'],
            title: (Dt ? '修改' : '添加') + '机构',
            success: function(l,index){
                layui.buildItems.build({
                    bid: 'organ_items',
                    data: [
                        {name:"id",type:"hidden"},
                        {name:"parentid",title:"上级机构",type:"html",html:'<div id="organ-list-tree" class="v-xmselect-tree"></div>',must:true},
                        {name:"title",title:"机构简称",type:"text",value:'',verify:'required',placeholder:"请输入机构简称",must:true},
                        {name:"titles",title:"机构全称",type:"text",value:'',verify:'required',placeholder:"请输入机构全称",must:true},
                        {name:"note",title:"备注说明",type:"textarea",value:'',placeholder:"请输入备注说明(选填)"},
                        {name:"listorder",title:"排序编号",type:"number",value:'100',verify:'required',placeholder:"请输入排序数字"}
                    ]
                });
                form.val('organ_items_form',Dt);
                /*渲染下拉树 https://maplemei.gitee.io/xm-select/#/component/options*/
                var data = JSON.parse(JSON.stringify(organData));
                if(Dt) Exitem(data,Dt.id,true);
                layui.xmSelect.render({
                    el: '#organ-list-tree',
                    name: 'parentid',
                    tips: '顶级机构',
                    height: '240px',
                    data: data,
                    filterable: true,
                    radio: true,
                    clickClose: true,
                    model: {label:{type:'text'}},
                    initValue: [Dt ? Dt.parentid : (organObj ? organObj.data.id : 0)],
                    prop: {name:'title',value:'id',disabled:'disabled'},
                    tree: {show:true,indent:25,strict:false,expandedKeys:true}
                });
                form.on('submit(organ_items)',function(data){
                    var btn = $(this);
                    if (btn.attr('stop')){return false}else{btn.attr('stop',1)}
                    var post_url = data.field.id ? app_root+'oedit' : app_root+'oadd';
                    admin.req(post_url,data.field,function(res){
                        layer.msg(res.msg,{shade:[0.4,'#000'],time:1500},function(){
                            if(res.code==1){
                                layer.close(index);
                                organObj = null;
                                renderTree(res.data);
                            }
                            btn.removeAttr('stop');
                        });
                    },'post');
                    return false;
                });
            }
        });
    }/**/
    /*二维数组转为树形结构*/
    function toTree(data){
        let result = []
        let map = {};
        if(!Array.isArray(data)){return result;}
        data.forEach(item =>{
            delete item.children;
            item.spread = true;
            map[item.id] = item;
        });
        data.forEach(item =>{
            let parent = map[item.parentid];
            if(parent){
                (parent.children || (parent.children = [])).push(item);
            } else {
                result.push(item);
            }
        });
        return result;
    }/**/
    /*机构本身和子类不可选为上级*/
    function Exitem(data, id, flag){
        for(var a in data){
            if(flag){
                if(data[a].id == id){
                    data[a].disabled = true;
                    if(data[a].hasOwnProperty('children')) Exitem(data[a].children, id, false);
                }else{
                    if(data[a].hasOwnProperty('children')) Exitem(data[a].children, id, flag);
                }
            }else{
                data[a].disabled = true;
                if(data[a].hasOwnProperty('children')) Exitem(data[a].children, id, false);
            }
        }
    }/**/
    /*==============左树结构END==============*/
    //顶部类别
    $('#search_roles_select').html(roles_select);
    //渲染搜索元素
    form.render(null, 'manager-form-search');
    layui.laydate.render({elem:'#manager-search-time',range:true,format:'yyyy/MM/dd',done:function(){$('#manager-search-time').trigger('input')}});
    /*渲染数据*/
    table.render({
        elem: '#manager',
        size: 'sm',
        css: '.layui-table[lay-size=sm] td .layui-table-cell{height:38px;line-height:28px;}',
        url: app_root+"index?do=json",
        height: 'full-313',
        cols: [[
            {type:'checkbox',fixed:'left'},
            {field:'userid',width:50,unresize:true,align:'center',title:'ID',sort:!0},
            {field:"username",title:"帐号",toolbar:'<div><a style="cursor:pointer;" lay-event="info">{{d.username}}</a></div>'},
            {field:"truename",edit:'text',title:"姓名"},
            {field:"mobile",width:110,edit:'text',title:"手机"},
            {field:"email",edit:'text',title:"邮箱"},
            {field:"roleid",width:80,align:'center',title:"角色",templet:function(d){return Roles[d.roleid]}},
            {field:"logins",width:68,align:'center',title:"登录次数"},
            {field:"loginip",width:110,align:'center',title:"登录IP",toolbar:'<div><a style="cursor:pointer;" lay-event="showip">{{d.loginip}}</a></div>'},
            {field:"logintime",width:100,align:'center',title:"最近登录",sort:!0,templet:function(d){return layui.util.toDateString(d.logintime*1000,'yyyy-MM-dd')}},
            {field:"edit",width:68,align:'center',title:"操作帐号"},
            {field:'state',width:80,align:'center',templet:function(d){return '<input type="checkbox" name="state" lay-skin="switch" lay-text="正常|禁用" lay-filter="manager-chang" value="'+d.state+'" data-json="'+encodeURIComponent(JSON.stringify(d))+'"'+(d.state==1 ? ' checked' : '')+'>';},unresize:true,title:'状态'},
            {fixed:'right',width:130,align:'center',toolbar:'<div><a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a><a class="layui-btn layui-btn-xs layui-btn-warm" lay-event="reset">重置</a><a class="layui-btn layui-btn-xs layui-btn-danger" lay-event="del">删除</a></div>',title:'操作'}
        ]],
        page: true,
        limit:{$limit}
    });
    /*监听搜索*/
    form.on('submit(top-manager-search)', function(data){
        data.field.groupid = groupid;
        table.reloadData('manager',{where:data.field,page:{curr:1}});
        return false;
    });/**/
    /*监听全部按钮*/
    form.on('submit(top-manager-all)', function(){
        groupid = ''
        table.reloadData('manager',{where:'',page:{curr:1}});
        return false;
    });/**/
    /*顶部添加按钮*/
    $('#top-manager-add').on('click',function(){managerOpen();});/**/
    /*顶部删除按钮*/
    $('#top-manager-del').on('click', function(){
        var checkRows = table.checkStatus('manager').data;
        if(checkRows.length === 0){return layer.msg('请选择需删除的管理帐号');}
        var ids = checkRows.map(function(d){return d.userid;});
        del(ids);
    });/**/
    /*快编监听*/
    table.on('edit(manager)',function(obj){
        admin.req(app_root+"edit?do=up",{userid:obj.data.userid,av:obj.value,af:obj.field},function(res){
            layer.msg(res.msg,{shade:[0.4,'#000'],time:500});
        },'post');
    });/**/
    /*状态*/
    form.on('switch(manager-chang)',function(obj){
        var json = JSON.parse(decodeURIComponent($(this).data('json')));
        var av = obj.elem.checked ? 1 : 0;
        admin.req(app_root+"edit?do=up",{userid:json.userid,av:av,af:obj.elem.name},function(res){
            layer.tips(res.msg,obj.othis,{time:2000});
        },'post');
    });/**/
    /*工具条监听*/
    table.on('tool(manager)', function(obj){
        var data = obj.data;
        var userid = data.userid;
        if(obj.event === 'edit'){
            managerOpen(data);
        }else if(obj.event === 'del'){
            del(userid);
        }else if(obj.event === 'info'){
            layui.vinfo.open({
                type: 'muser',
                title: '用户详细',
                url: app_root + 'index?do=info&username=' + data.username
            });
        }else if(obj.event === 'reset'){
            admin.open({
                type: 1,
                bid: 'manager_reset',
                btn: ['确认重置', '取消'],
                area: ['400px', '230px'],
                title: '重置密码【'+data.username+'】',
                success: function(l,index){
                    layui.buildItems.build({
                        bid: 'manager_reset',
                        data: [
                            {name:"newPassword",title:"新设密码",type:"password",tid:'npass',value:'',verify:'nepass',vertype:'tips',placeholder:"请输入6-16位新设密码",must:true},
                            {name:"rePassword",title:"确认密码",type:"password",value:'',verify:'repass',vertype:'tips',placeholder:"请再次输入新密码",must:true}
                        ]
                    });
                    form.verify({
                        nepass: function(v){if(!/^[\S]{6,16}$/.test(v)){ return '密码必须6-16位，且不能出现空格';}},
                        repass: function(v){if(!v){return "请再次输入新密码";}else if(v !== $("#npass").val()){return "两次密码输入不一致";}}
                    });
                    form.on('submit(manager_reset)',function(data){
                        var btn = $(this);
                        if (btn.attr('stop')){return false}else{btn.attr('stop',1)}
                        data.field.userid = userid;
                        admin.req(app_root+"resetpwd",data.field,function(res){
                            layer.msg(res.msg,{shade:[0.4,'#000'],time:1500},function(){
                                if(res.code==1){layer.close(index)}
                                btn.removeAttr('stop');
                            });
                        },'post');
                        return false;
                    });
                }
            });
        }else if(obj.event === 'showip'){
            getipInfo(data.loginip);
        }
    });/**/
    /*删除*/
    function del(ids){
        layer.confirm('确定要删除所选管理帐号吗？', function(index){
            layer.close(index);
            admin.req(app_root+"del",{userid:ids},function(res){
                layer.msg(res.msg,{shade:[0.4,'#000'],time:1500},function(){
                    if(res.code==1) table.reloadData('manager');
                });
            },'post');
        });
    }/**/
    /*弹出窗*/
    function managerOpen(Dt){
        admin.open({
            type: 1,
            bid: 'manager_items',
            btn: ['保存', '取消'],
            area: ['500px','560px'],
            title: (Dt ? '编辑' : '添加') + '管理员',
            success: function(l,index){
                layui.buildItems.build({
                    bid: 'manager_items',
                    data: [
                        {name:"userid",type:"hidden"},
                        {name:"groupid",title:"所属机构",type:"html",html:'<div id="organ-list-tree" class="v-xmselect-tree"></div>',must:true},
                        {name:"roleid",title:"所属角色",type:"html",html:'<select name="roleid" lay-verify="mroleid">'+roles_select+'</select>',must:true},
                        {name:"username",title:"管理帐号",type:"text",value:'',verify:'username',placeholder:"请输入4-30位管理帐号",must:true},
                        {name:"password",title:"登录密码",type:"password",id:'m_pwd',value:'',verify:'pass',placeholder:"请输入6-16位登录密码",must:true},
                        {name:"repassword",title:"确认密码",type:"password",id:'m_repwd',value:'',verify:'repass',placeholder:"请重复登录密码",must:true},
                        {name:"truename",title:"真实姓名",type:"text",value:''},
                        {name:"mobile",title:"手机/电话",type:"text",value:''},
                        {name:"email",title:"电子邮箱",type:"text",value:''}
                    ]
                });
                form.val('manager_items_form',Dt);
                /*密码处理*/
                if(Dt){
                    $('#m_pwd').parent().parent().hide();
                    $('#m_repwd').parent().parent().hide();
                }
                form.verify({
                    mgroupid: function(v){ if(!v) return "请选择所属机构";},
                    mroleid: function(v){ if(!v) return "请选择所属角色";},
                    username: function(v){ if(!/^[\S]{4,30}$/.test(v)){return '请输入4-30位管理帐号';}},
                    pass: function(v){ if(!/^[\S]{6,16}$/.test(v) && !Dt){return '密码必须6-16位，且不能出现空格';}},
                    repass: function(v){ if (v !== $("#m_pwd").val() && !Dt) return "两次密码输入不一致";}
                });
                /*判断帐号是否已被占用*/
                $("input").blur(function(){
                    var o = $(this);
                    var obj = o.attr('name');
                    var val = o.val();
                    if(obj=='username' && val.length>2){
                        admin.req(app_root+"index?do=check",{username:val,userid:(Dt ? Dt.userid : 0)},function(res){
                            if(res.code==1){
                                layer.msg(res.msg,{shade:[0.4,'#000'],time:1500},function(){o.val('');o.focus();});
                            }
                        },'post');
                        return ;
                    }
                });
                /*渲染下拉树 https://maplemei.gitee.io/xm-select/#/component/options*/
                var data = JSON.parse(JSON.stringify(organData));
                layui.xmSelect.render({
                    el: '#organ-list-tree',
                    name: 'groupid',
                    tips: '所属机构',
                    height: '300px',
                    data: data,
                    filterable: true,
                    radio: true,
                    clickClose: true,
                    layVerify: 'mgroupid',
                    model: {label:{type:'text'}},
                    initValue: [Dt ? Dt.groupid : 0],
                    prop: {name:'title',value:'id',disabled:'disabled'},
                    tree: {show:true,indent:25,strict:false,expandedKeys:true}
                });
                form.on('submit(manager_items)',function(data){
                    var btn = $(this);
                    if (btn.attr('stop')){return false}else{btn.attr('stop',1)}
                    var post_url = data.field.userid ? app_root+'edit' : app_root+'add';
                    admin.req(post_url,data.field,function(res){
                        layer.msg(res.msg,{shade:[0.4,'#000'],time:1500},function(){
                            if(res.code==1){
                                layer.close(index);
                                table.reloadData('manager');
                            }
                            btn.removeAttr('stop');
                        });
                    },'post');
                    return false;
                });
            }
        });
    }/**/
});
</script>