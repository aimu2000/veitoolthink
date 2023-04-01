layui.define(["admin"],function(a){var e=layui.jquery,i=layui.element,t=layui.cache.bins,l=layui.admin,n=l.router,r=".layui-layout-admin>.layui-header",s=".layui-layout-admin>.layui-side>.layui-side-scroll",d=".layui-layout-admin>.layui-body",u=d+">.layui-tab",o=d+">.layui-body-header",c="admin-pagetabs",h=!1,y={mTabList:[],regRouter:function(a,i){e.each(a,function(a,t){i&&(t=i(t)),t.url&&0===t.url.indexOf("#")&&n.reg(t.url,function(a){y.changeView(e.extend(a,{name:t.name,iframe:t.iframe}))}),t.subMenus&&y.regRouter(t.subMenus,i)})},changeView:function(a){var r=y.getHashPath(a),o=d+">div[lay-id]";if(t.pageTabs){var m;e(u+">.layui-tab-title>li").each(function(){e(this).attr("lay-id")===r&&(m=!0)}),m||(y.mTabList.length+1>=t.maxTabNum&&l.closeThisTabs(y.mTabList[0].path.join("/")),h=!0,i.tabAdd(c,{id:r,title:'<span class="title">'+(a.name||"")+"</span>",content:'<div lay-id="'+r+'" lay-url="'+a.href+'"></div>'}),r!==n.index&&y.mTabList.push(a),t.cacheTab&&l.putTempData("indexTabs",y.mTabList));var b=e(o=u+'>.layui-tab-content>.layui-tab-item>div[lay-id="'+r+'"]').attr("lay-url");if(a.href!==b){layui.event.call(this,"admin","destroy("+r+")"),e(o).attr("lay-url",a.href),m=!1;for(var f=0;f<y.mTabList.length;f++)y.mTabList[f].href===b&&(y.mTabList[f]=a);t.cacheTab&&l.putTempData("indexTabs",y.mTabList)}m&&!a.refresh||(a.refresh&&layui.event.call(this,"admin","destroy("+r+")"),y.renderView(a,o)),a.noChange||a.refresh||i.tabChange(c,r)}else l.activeNav(a.href),0===e(o).length?e(d).html(['<div class="layui-body-header">','<span class="layui-body-header-title"></span>','<span class="layui-breadcrumb pull-right" lay-filter="admin-body-breadcrumb" style="visibility: visible;"></span>',"</div>",'<div lay-id="'+r+'" lay-url="'+a.href+'"></div>'].join("")):(layui.event.call(this,"admin","destroy("+e(o).attr("lay-id")+")"),e(o).attr("lay-id",r).attr("lay-url",a.href)),e('[lay-filter="admin-body-breadcrumb"]').html(y.getBreadcrumbHtml(r)),y.mTabList.splice(0,y.mTabList.length),r===n.index?y.setTabTitle(e(a.name).text()||e(s+' [href="#/'+n.index+'"]').text()||"主页"):(y.mTabList.push(a),y.setTabTitle(a.name)),y.renderView(a,o),t.cacheTab&&l.putTempData("indexTabs",y.mTabList);l.getPageWidth()<=768&&l.flexible(!0),e(".layui-table-tips-c").trigger("click")},renderView:function(a,i,n){var r=e(i),s=a.iframe||"",d=s.indexOf("@");if(n||(n=r.parent()),""===s||"#"==s||d>-1){var u=t.viewPath||layui.cache.maps.replace(/\/*$/,"");d>-1&&(u=u.substring(0,u.lastIndexOf("/"))),l.showLoading({elem:n,size:""}),l.ajax({url:u+"/"+a.path.join("/")+t.viewSuffix,data:a.search,dataType:"html",success:function(d){if(l.removeLoading(n),"string"!=typeof d&&(d=JSON.stringify(d)),0===d.indexOf("<tpl")){var u=e("<div>"+d+"</div>"),o={};for(var c in u.find("script,[tpl-ignore]").each(function(a){var i=e(this);o["temp_"+a]=i[0].outerHTML,i.after("${temp_"+a+"}").remove()}),d=layui.laytpl(u.html()).render(a),o)d=d.replace("${"+c+"}",o[c])}0===s.indexOf("#")?l.open({type:1,area:["100%","100%"],title:a.name,content:d,cancel:function(){t.pageTabs?l.closeThisTabs(y.getHashPath(a)):location.href="#"}}):(r.html(d),l.renderTpl(i+" [v-tpl]"))}})}else 0===s.indexOf("#")?l.open({type:2,area:["100%","100%"],title:a.name,content:s.substr(1),cancel:function(){t.pageTabs?l.closeThisTabs(y.getHashPath(a)):location.href="#"}}):r.html(['<div class="admin-iframe" style="-webkit-overflow-scrolling: touch;">','<iframe src="',s,'" class="admin-iframe" frameborder="0"></iframe>',"</div>"].join(""))},loadHome:function(a){var i=l.getTempData("indexTabs");if(y.regRouter([a]),t.pageTabs){var r=n.routerInfo(a.url);if(n.index=y.getHashPath(r),y.changeView(e.extend(r,{name:a.name,iframe:a.iframe,noChange:!0})),!1!==a.loadSetting&&t.cacheTab&&i)for(var s=0;s<i.length;s++)y.changeView(e.extend(i[s],{noChange:!0}))}l.removeLoading(void 0,!1),n.init({index:a.url,notFound:l.routerNotFound})},openNewTab:function(a){y.regRouter([a]),n.go(a.url)},closeTab:function(a){i.tabDelete(c,y.getHashPath(a))},getHashPath:function(a){return a&&"string"!=typeof a||(a=n.routerInfo(a)),a.path.join("/")},go:function(a){n.go(a)},clearTabCache:function(){l.putTempData("indexTabs",null)},setTabTitle:function(a,i){t.pageTabs?(i||(i=y.getHashPath()),i&&e(u+'>.layui-tab-title>li[lay-id="'+i+'"] .title').html(a||"")):a?(e(o+">.layui-body-header-title").html(a),e(o).addClass("show"),e(r).css("box-shadow","0 1px 0 0 rgba(0, 0, 0, .03)")):(e(o).removeClass("show"),e(r).css("box-shadow",""))},setTabTitleHtml:function(a){if(!t.pageTabs){if(!a)return e(o).removeClass("show");e(o).html(a),e(o).addClass("show")}},getBreadcrumb:function(a){a||(a=e(d+">div[lay-id]").attr("lay-id"));var i=[],t=e(s).find('[href="#/'+a+'"]');for(t.length>0&&i.push(t.text().replace(/(^\s*)|(\s*$)/g,""));0!==(t=t.parent("dd").parent("dl").prev("a")).length;)i.unshift(t.text().replace(/(^\s*)|(\s*$)/g,""));return i},getBreadcrumbHtml:function(a){for(var e=y.getBreadcrumb(a),i=a===n.index?"":'<a href="#/'+n.index+'">首页</a>',t=0;t<e.length-1;t++)i&&(i+='<span lay-separator="">/</span>'),i+="<a><cite>"+e[t]+"</cite></a>";return i},toNav:function(a,e,i){let t="",l="";return a.forEach(n=>{n.pid!=e||i&&i!=n.catid||(l='<a href="'+(n.url||"javascript:;")+'" target="'+(n.target||"_self")+'"><i class="layui-icon '+n.icon+'"></i>',t+=0==e?'<li class="layui-nav-item">'+l+"&emsp;<cite>"+n.name+"</cite></a>"+y.toNav(a,n.id,i)+"</li>":"<dd>"+l+n.name+"</a>"+y.toNav(a,n.id,i)+"</dd>")}),e>0&&(t=t?'<dl class="layui-nav-child">'+t+"</dl>":""),t},buildLeftMenus:function(a,n){if(a.hasOwnProperty("cat")){var d="",u="";e(s).html(""),a.cat.forEach(i=>{d='<ul nav-id="'+i.catid+'" class="layui-nav layui-nav-tree '+(t.navArrow||"")+'" lay-filter="admin-side-nav" lay-shrink="_all">'+y.toNav(a.menus,0,i.catid)+"</ul>",e(s).append(d),u+='<li class="layui-nav-item big-nav"><a nav-bind="'+i.catid+'">'+(i.icon?'<i class="layui-icon '+i.icon+' hicon"></i>':"")+i.title+"</a></li>"}),e(r+">.layui-layout-left .big-nav").remove(),e(r+">.layui-layout-left").append(u),a=a.menus}else e(s+">.layui-nav").html(y.toNav(a,0));y.regRouter(a),i.render("nav","admin-side-nav"),y.loadHome({url:n||"#/index/main",name:'<i class="layui-icon layui-icon-home"></i>'}),l.removeLoading()}},m=".layui-layout-admin .site-mobile-shade";0===e(m).length&&e(".layui-layout-admin").append('<div class="site-mobile-shade"></div>'),e(m).click(function(){l.flexible(!0)}),t.pageTabs&&0===e(u).length&&e(d).html(['<div class="layui-tab" lay-allowClose="true" lay-filter="',c,'" lay-autoRefresh="',1==t.tabAutoRefresh,'">','<ul class="layui-tab-title"></ul><div class="layui-tab-content"></div></div>','<div class="layui-icon admin-tabs-control layui-icon-prev" v-event="leftPage"></div>','<div class="layui-icon admin-tabs-control layui-icon-next" v-event="rightPage"></div>'].join("")),i.on("nav(admin-side-nav)",function(a){var i=e(a).attr("href");0===i.indexOf("#")&&"true"==e(u).attr("lay-autoRefresh")&&(h||l.refresh(i.substring(1),!0))}),i.on("tab("+c+")",function(){var a=e(this).attr("lay-id"),i=e(u+'>.layui-tab-content>.layui-tab-item>div[lay-id="'+a+'"]').attr("lay-url");l.activeNav(i),l.rollPage("auto"),"true"!=e(u).attr("lay-autoRefresh")||h?n.go(i):l.refresh(i,!0),h=!1,l.resizeTable(0),layui.event.call(this,"admin","show("+a+")"),layui.event.call(this,"admin","tab({*})",{layId:a})}),i.on("tabDelete("+c+")",function(a){if(a.index>0&&a.index<=y.mTabList.length){var i=y.getHashPath(y.mTabList[a.index-1].href);layui.event.call(this,"admin","destroy("+i+")"),y.mTabList.splice(a.index-1,1),t.cacheTab&&l.putTempData("indexTabs",y.mTabList),layui.event.call(this,"admin","tabDelete({*})",{layId:i})}0===e(u+">.layui-tab-title>li.layui-this").length&&e(u+">.layui-tab-title>li:last").trigger("click")}),e(document).off("click.navMore").on("click.navMore","[nav-bind]",function(){var a=e(this).attr("nav-bind");e('ul[lay-filter="admin-side-nav"]').addClass("layui-hide"),e('ul[nav-id="'+a+'"]').removeClass("layui-hide"),e(r+">.layui-nav .layui-nav-item").removeClass("layui-this"),e(this).parent(".layui-nav-item").addClass("layui-this"),l.getPageWidth()<=768&&l.flexible(!1),layui.event.call(this,"admin","nav({*})",{navId:a})}),t.openTabCtxMenu&&t.pageTabs&&e(u+">.layui-tab-title").off("contextmenu.tab").on("contextmenu.tab","li",function(a){var t=e(this).attr("lay-id");return layui.dropdown.render({elem:e(this),trigger:"contextmenu",style:"width:110px",show:!0,data:[{title:"刷新当前",templet:'<i class="layui-icon layui-icon-refresh"></i> {{d.title}}',id:"tab_mt1"},{title:"关闭其他",templet:'<i class="layui-icon layui-icon-unlink"></i> {{d.title}}',id:"tab_mt2"},{title:"关闭全部",templet:'<i class="layui-icon layui-icon-close"></i> {{d.title}}',id:"tab_mt3"}],click:function(a){if("tab_mt1"===a.id){i.tabChange(c,t);var n=e(u+'>.layui-tab-content>.layui-tab-item>div[lay-id="'+t+'"]').attr("lay-url");"true"!=e(u).attr("lay-autoRefresh")&&l.refresh(n)}else"tab_mt2"===a.id?l.closeOtherTabs(t):"tab_mt3"===a.id&&l.closeAllTabs()}}),!1}),a("index",y)});