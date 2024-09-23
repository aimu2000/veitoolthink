layui.define(["jquery"],function(e){var a=layui.jquery;a("#v-css-cascader").length<=0&&layui.link(layui.cache.base+"cascader/cascader.css");var i=[],s={render:function(e){var t=(e=a.extend({renderFormat:function(e,a){return e.join(" / ")},clearable:!0,clearAllActive:!1,disabled:!1,trigger:"click",changeOnSelect:!1,filterable:!1,notFoundText:"没有匹配数据"},e)).elem,o=e.data,r=e.renderFormat,c=e.clearable,d=e.clearAllActive,v=e.disabled,h=e.trigger,u=e.changeOnSelect,f=e.reqData,p=e.filterable,g=e.notFoundText,m=e.reqSearch,C=e.onChange,w=e.onVisibleChange,y=e.itemHeight,b=!0,k=a(t);if(k.next().hasClass("v-cascader-group")){k.next().remove();for(var x=0;x<i.length;x++)if(t==i[x].elem){i.splice(x,1);break}}i.push({elem:t,onVisibleChange:w}),k.addClass("v-cascader-hide");k.after('<div class="v-cascader-group">      <div class="v-cascader-input-group">         <input class="layui-input v-cascader-input" readonly/>         <input class="layui-input v-cascader-input-search"/>         <i class="layui-icon layui-icon-triangle-d v-icon-arrow"></i>         <i class="layui-icon layui-icon-loading-1 layui-anim layui-anim-rotate layui-anim-loop v-icon-loading"></i>         <i class="layui-icon layui-icon-close-fill v-icon-clear"></i>      </div>      <div class="v-cascader-dropdown layui-anim layui-anim-upbit"></div>      <div class="v-cascader-search-list"></div>   </div>');var _=k.next(),P=_.children(".v-cascader-input-group"),A=P.children(".v-cascader-input"),S=P.children(".v-cascader-input-search"),W=_.children(".v-cascader-dropdown"),H=_.children(".v-cascader-search-list");A.attr("placeholder",k.attr("placeholder")),v&&A.addClass("layui-disabled");var V={data:o,getData:function(){return o},open:function(){_.hasClass("v-cascader-open")||(s.hideAll(),_.addClass("v-cascader-open"),s.checkWidthPosition(W),s.checkHeightPosition(W),w&&w(!0),p&&(P.addClass("show-search"),S.focus()))},hide:function(){_.hasClass("v-cascader-open")&&(_.removeClass("v-cascader-open"),_.removeClass("dropdown-show-top"),_.removeClass("dropdown-show-left"),s.hideAllSearch(),w&&w(!1))},removeLoading:function(){_.removeClass("show-loading"),W.find(".v-cascader-dropdown-list-item").removeClass("show-loading")},setDisabled:function(e){v=e,e?(A.addClass("layui-disabled"),V.hide()):A.removeClass("layui-disabled")},getValue:function(){return k.val()},getLabel:function(){return A.val()},setValue:function(e){if(null==e||null==e||!e.toString())return A.val(""),k.val(""),d||u?(W.children(".v-cascader-dropdown-list").not(":first").remove(),W.find(".v-cascader-dropdown-list-item").removeClass("active"),s.checkWidthPosition(W)):W.find(".v-cascader-dropdown-list-item.is-last").removeClass("active"),void P.removeClass("show-clear");e=e.toString().split(",");var a=[];!function e(i,s,l,n,t){function r(s){for(var o=0;o<s.length;o++)if(s[o].value==n[l]){a[l]=s[o].text,i[l]=s[o].value,l<n.length-1?e(i,s[o],l+1,n,t):t();break}}!i&&s?(i=[],r(s)):i&&s&&s.children?r(s.children):(_.addClass("show-loading"),f(i,function(e){i?s.children=e:(o=e,i=[]),r(e)},s))}(void 0,o,0,e,function(){_.removeClass("show-loading"),A.val(r(a,e)),k.val(e.join(","))})}};return V.setValue(k.val()),P.off("click").on("click",function(e){function i(){var e=k.val().toString();if(e){e=e.split(",");for(var a=0;a<e.length;a++){W.children(".v-cascader-dropdown-list").eq(a).children('.v-cascader-dropdown-list-item[data-value="'+e[a]+'"]').trigger("click")}}else V.setValue()}A.hasClass("layui-disabled")||_.hasClass("show-loading")||(_.hasClass("v-cascader-open")?p||V.hide():(b?(b=!1,o?(l(W,o,void 0,y),i(),V.open()):(_.addClass("show-loading"),f(void 0,function(e){o=e,l(W,e,void 0,y),_.removeClass("show-loading"),V.open()},void 0))):(i(),V.open()),W.find(".v-cascader-dropdown-list").each(function(){var e=a(this).find(".active"),i=a(this).innerHeight();e.length&&e.position().top>i&&a(this).scrollTop(e.position().top+a(this).scrollTop())})))}),P.children(".v-icon-arrow").off("click").on("click",function(e){_.hasClass("v-cascader-open")&&(V.hide(),e.stopPropagation())}),W.off("click").on("click",".v-cascader-dropdown-list-item",function(){var e=a(this);if(e.hasClass("active"))e.hasClass("is-last")&&V.hide();else if(!e.hasClass("v-cascader-disabled")&&!e.parent().parent().find(".v-cascader-dropdown-list-item").hasClass("show-loading")){for(var i=e.data("index").toString(),n=i.split("-"),t=o[parseInt(n[0])],c=[t.value],d=[t.text],v=1;v<n.length;v++)t=t.children[parseInt(n[v])],c[v]=t.value,d[v]=t.text;t.haveChildren?(t.children?(e.parent().nextAll().remove(),s.checkWidthPosition(W),h(),l(W,t.children,i,y)):(e.addClass("show-loading"),f(c,function(a){t.children=a,e.parent().nextAll().remove(),s.checkWidthPosition(W),h(),l(W,a,i,y),e.removeClass("show-loading")},t)),u&&(h(),p())):(e.parent().nextAll().remove(),h(),p(),V.hide())}function h(){e.parent().children(".v-cascader-dropdown-list-item").removeClass("active"),e.addClass("active")}function p(){A.val(r(d,c)),k.val(c.join(",")),k.removeClass("layui-form-danger"),C&&C(c,t)}}),"hover"==h&&W.off("mouseenter").on("mouseenter",".v-cascader-dropdown-list-item",function(){a(this).hasClass("is-last")||a(this).trigger("click")}),c&&(P.off("mouseenter").on("mouseenter",function(){k.val().toString()&&!A.hasClass("layui-disabled")&&a(this).addClass("show-clear")}),P.off("mouseleave").on("mouseleave",function(){a(this).removeClass("show-clear")}),P.children(".v-icon-clear").off("click").on("click",function(e){e.stopPropagation(),V.setValue(),C&&C()})),p&&(S.off("input").on("input",function(){var e=a(this).val();if(!e)return _.removeClass("show-search-list"),void P.removeClass("have-value");if(P.addClass("have-value"),m)m(e,function(e){n(H,e,g),_.addClass("show-search-list")},o);else{var i=[],s=[];!function e(a,s,l,n){for(var t=0;t<a.length;t++){var o=a[t];o.__label=s?s+" / "+o.text:o.text,o.__value=l?l+","+o.value:o.value,o.__disabled=o.disabled?o.disabled:n,o.children&&o.children.length?(e(o.children,o.__label,o.__value,o.__disabled),delete o.__label,delete o.__value):i.push({text:o.__label,value:o.__value,disabled:o.__disabled})}}(o);for(var l=0;l<i.length;l++){var t=i[l];t.text.indexOf(e)>-1&&(t.text=t.text.replace(new RegExp(e,"g"),'<span class="search-keyword">'+e+"</span>"),s.push(t))}n(H,s,g),_.addClass("show-search-list")}}),H.off("click").on("click",".v-cascader-search-list-item",function(e){if(e.stopPropagation(),!a(this).hasClass("v-cascader-disabled")){var i=a(this).data("value").toString();V.hide(),V.setValue(i);for(var s=i.split(","),l=V.getData(),n=0;n<s.length;n++)for(var t=0;t<l.length;t++)if(l[t].value==s[n]){l=n===s.length-1?l[t]:l[t].children;break}C&&C(s,l)}})),V},hideAll:function(){s.hideAllSearch();for(var e=0;e<i.length;e++){var l=i[e].elem,n=i[e].onVisibleChange,t=a(l).next();t.hasClass("v-cascader-open")&&(t.removeClass("v-cascader-open"),t.removeClass("dropdown-show-top"),t.removeClass("dropdown-show-left"),n&&n(!1))}},hideAllSearch:function(){a(".v-cascader-input-group").removeClass("show-search"),a(".v-cascader-group").removeClass("show-search-list"),a(".v-cascader-input-group").removeClass("have-value"),a(".v-cascader-input-search").val("")},getPageHeight:function(){return document.documentElement.clientHeight||document.body.clientHeight},getPageWidth:function(){return document.documentElement.clientWidth||document.body.clientWidth},checkWidthPosition:function(e){e.offset().left+e.outerWidth()>s.getPageWidth()?e.parent().addClass("dropdown-show-left"):e.parent().removeClass("dropdown-show-left")},checkHeightPosition:function(e){e.offset().top+e.outerHeight()>s.getPageHeight()&&(e.parent().addClass("dropdown-show-top"),e.offset().top<0&&e.parent().removeClass("dropdown-show-top"))},getCityData:function(e){for(var a=0;a<e.length;a++)e[a].value=e[a].text,e[a].children&&(e[a].children=s.getCityData(e[a].children));return e},getCity:function(e){for(var a=0;a<e.length;a++)for(var i=0;i<e[a].children.length;i++)delete e[a].children[i].children;return e},getProvince:function(e){for(var a=0;a<e.length;a++)delete e[a].children;return e}},l=function(e,a,i,l){for(var n='<div class="v-cascader-dropdown-list" '+(l?' style="height:'+l+';"':"")+">",t=0;t<a.length;t++){var o=a[t],r=null==i?t:i+"-"+t;null==o.haveChildren&&(o.haveChildren=!!o.children);var c=o.haveChildren?"":" is-last";o.disabled&&(c+=" v-cascader-disabled"),n+='   <div class="v-cascader-dropdown-list-item'+c+'" data-index="'+r+'" data-value="'+o.value+'">'+o.text+'<i class="layui-icon layui-icon-right v-icon-right"></i><i class="layui-icon layui-icon-loading-1 layui-anim layui-anim-rotate layui-anim-loop v-icon-loading"></i></div>'}n+="   </div>",e.append(n),s.checkWidthPosition(e)},n=function(e,a,i){var s="";if(0==a.length)s='<div class="v-cascader-search-list-empty">'+i+"</div>";else for(var l=0;l<a.length;l++){var n=a[l];s+='<div class="v-cascader-search-list-item'+(n.disabled?" v-cascader-disabled":"")+'" data-value="'+n.value+'">'+n.text+"</div>"}e.html(s)};a(document).off("click.cascader").on("click.cascader",function(e){try{var a=e.target.className.split(" "),i=["v-cascader-group","v-cascader-input","v-icon-arrow","v-cascader-dropdown","v-cascader-dropdown-list","v-cascader-dropdown-list-item","v-icon-right","v-cascader-input-search","v-cascader-search-list","v-cascader-search-list-item"];for(var l in a)for(var n in i)if(a[l]==i[n])return}catch(e){}s.hideAll()}),e("cascader",s)});