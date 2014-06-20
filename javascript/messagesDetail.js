/**
 * **************************
 * jQuery-плагины для дизайна
 * **************************
 */

(function(b){b.fn.htmlarea=function(a){if(a&&"string"===typeof a){for(var c=[],b=1;b<arguments.length;b++)c.push(arguments[b]);var b=d(this[0]),f=b[a];if(f)return f.apply(b,c)}return this.each(function(){d(this,a)})};var d=window.jHtmlArea=function(a,b){return a.jquery?d(a[0]):a.jhtmlareaObject?a.jhtmlareaObject:new d.fn.init(a,b)};d.fn=d.prototype={jhtmlarea:"0.7.0",init:function(a,c){if("textarea"===a.nodeName.toLowerCase()){var e=b.extend({},d.defaultOptions,c);a.jhtmlareaObject=this;var f=this.textarea= b(a),j=this.container=b("<div/>").addClass("jHtmlArea").width(f.width()).insertAfter(f),l=this.toolbar=b("<div/>").addClass("ToolBar").appendTo(j);h.initToolBar.call(this,e);var k=this.iframe=b("<iframe/>").height(f.height());k.width(f.width()-(b.browser.msie?0:4));var i=this.htmlarea=b("<div/>").append(k);j.append(i).append(f.hide());h.initEditor.call(this,e);h.attachEditorEvents.call(this);k.height(k.height()-l.height());l.width(f.width()-2);e.loaded&&e.loaded.call(this)}},dispose:function(){this.textarea.show().insertAfter(this.container);this.container.remove();this.textarea[0].jhtmlareaObject=null},execCommand:function(a,b,e){this.iframe[0].contentWindow.focus();this.editor.execCommand(a,b||!1,e||null);this.updateTextArea()},ec:function(a,b,e){this.execCommand(a,b,e)},queryCommandValue:function(a){this.iframe[0].contentWindow.focus();return this.editor.queryCommandValue(a)},qc:function(a){return this.queryCommandValue(a)},getSelectedHTML:function(){if(b.browser.msie)return this.getRange().htmlText;var a=this.getRange().cloneContents();return b("<p/>").append(b(a)).html()},getSelection:function(){return b.browser.msie?this.editor.selection:this.iframe[0].contentDocument.defaultView.getSelection()},getRange:function(){var a=this.getSelection();return!a?null:a.getRangeAt?a.getRangeAt(0):a.createRange()},html:function(a){if(a)this.pastHTML(a);else return toHtmlString()},pasteHTML:function(a){this.iframe[0].contentWindow.focus();var c=this.getRange();b.browser.msie?c.pasteHTML(a):b.browser.mozilla?(c.deleteContents(),c.insertNode(b(0!= a.indexOf("<")?b("<span/>").append(a):a)[0])):(c.deleteContents(),c.insertNode(b(this.iframe[0].contentWindow.document.createElement("span")).append(b(0!=a.indexOf("<")?"<span>"+a+"</span>":a))[0]));c.collapse(!1);"function"==typeof c.select&&c.select()},cut:function(){this.ec("cut")},copy:function(){this.ec("copy")},paste:function(){this.ec("paste")},bold:function(){this.ec("bold")},italic:function(){this.ec("italic")},underline:function(){this.ec("underline")},strikeThrough:function(){this.ec("strikethrough")}, image:function(a){b.browser.msie&&!a?this.ec("insertImage",!0):this.ec("insertImage",!1,a||prompt("Image URL:","http://"))},removeFormat:function(){this.ec("removeFormat",!1,[]);this.unlink()},link:function(){b.browser.msie?this.ec("createLink",!0):this.ec("createLink",!1,prompt("Link URL:","http://"))},unlink:function(){this.ec("unlink",!1,[])},orderedList:function(){this.ec("insertorderedlist")},unorderedList:function(){this.ec("insertunorderedlist")},superscript:function(){this.ec("superscript")}, subscript:function(){this.ec("subscript")},p:function(){this.formatBlock("<p>")},h1:function(){this.heading(1)},h2:function(){this.heading(2)},h3:function(){this.heading(3)},h4:function(){this.heading(4)},h5:function(){this.heading(5)},h6:function(){this.heading(6)},heading:function(a){this.formatBlock(b.browser.msie?"Heading "+a:"h"+a)},indent:function(){this.ec("indent")},outdent:function(){this.ec("outdent")},insertHorizontalRule:function(){this.ec("insertHorizontalRule",!1,"ht")},justifyLeft:function(){this.ec("justifyLeft")}, justifyCenter:function(){this.ec("justifyCenter")},justifyRight:function(){this.ec("justifyRight")},increaseFontSize:function(){b.browser.msie?this.ec("fontSize",!1,this.qc("fontSize")+1):b.browser.safari?this.getRange().surroundContents(b(this.iframe[0].contentWindow.document.createElement("span")).css("font-size","larger")[0]):this.ec("increaseFontSize",!1,"big")},decreaseFontSize:function(){b.browser.msie?this.ec("fontSize",!1,this.qc("fontSize")-1):b.browser.safari?this.getRange().surroundContents(b(this.iframe[0].contentWindow.document.createElement("span")).css("font-size", "smaller")[0]):this.ec("decreaseFontSize",!1,"small")},forecolor:function(a){this.ec("foreColor",!1,a||prompt("Enter HTML Color:","#"))},formatBlock:function(a){this.ec("formatblock",!1,a||null)},showHTMLView:function(){this.updateTextArea();this.textarea.show();this.htmlarea.hide();b("ul li:not(li:has(a.html))",this.toolbar).hide();b("ul:not(:has(:visible))",this.toolbar).hide();b("ul li a.html",this.toolbar).addClass("highlighted")},hideHTMLView:function(){this.updateHtmlArea();this.textarea.hide();this.htmlarea.show();b("ul",this.toolbar).show();b("ul li",this.toolbar).show().find("a.html").removeClass("highlighted")},toggleHTMLView:function(){this.textarea.is(":hidden")?this.showHTMLView():this.hideHTMLView()},toHtmlString:function(){return this.editor.body.innerHTML},toString:function(){return this.editor.body.innerText},updateTextArea:function(){this.textarea.val(this.toHtmlString())},updateHtmlArea:function(){this.editor.body.innerHTML=this.textarea.val()}};d.fn.init.prototype=d.fn;d.defaultOptions= {toolbar:[["html"],"bold,italic,underline,strikethrough,|,subscript,superscript".split(","),["increasefontsize","decreasefontsize"],["orderedlist","unorderedlist"],["indent","outdent"],["justifyleft","justifycenter","justifyright"],["link","unlink","image","horizontalrule"],"p,h1,h2,h3,h4,h5,h6".split(","),["cut","copy","paste"]],css:null,toolbarText:{bold:"Bold",italic:"Italic",underline:"Underline",strikethrough:"Strike-Through",cut:"Cut",copy:"Copy",paste:"Paste",h1:"Heading 1",h2:"Heading 2", h3:"Heading 3",h4:"Heading 4",h5:"Heading 5",h6:"Heading 6",p:"Paragraph",indent:"Indent",outdent:"Outdent",horizontalrule:"Insert Horizontal Rule",justifyleft:"Left Justify",justifycenter:"Center Justify",justifyright:"Right Justify",increasefontsize:"Increase Font Size",decreasefontsize:"Decrease Font Size",forecolor:"Text Color",link:"Insert Link",unlink:"Remove Link",image:"Insert Image",orderedlist:"Insert Ordered List",unorderedlist:"Insert Unordered List",subscript:"Subscript",superscript:"Superscript", html:"Show/Hide HTML Source View"}};var h={toolbarButtons:{strikethrough:"strikeThrough",orderedlist:"orderedList",unorderedlist:"unorderedList",horizontalrule:"insertHorizontalRule",justifyleft:"justifyLeft",justifycenter:"justifyCenter",justifyright:"justifyRight",increasefontsize:"increaseFontSize",decreasefontsize:"decreaseFontSize",html:function(){this.toggleHTMLView()}},initEditor:function(a){var b=this.editor=this.iframe[0].contentWindow.document;b.designMode="on";b.open();b.write(this.textarea.val());b.close();if(a.css){var e=b.createElement("link");e.rel="stylesheet";e.type="text/css";e.href=a.css;b.getElementsByTagName("head")[0].appendChild(e)}},initToolBar:function(a){function c(c){for(var d=b("<ul/>").appendTo(e.toolbar),i=0;i<c.length;i++){var g=c[i];if("string"===(typeof g).toLowerCase())if("|"===g)d.append(b('<li class="separator"/>'));else{var j=function(a){var b=h.toolbarButtons[a]||a;return"function"===(typeof b).toLowerCase()?function(a){b.call(this,a)}:function(){this[b]();this.editor.body.focus()}}(g.toLowerCase()), m=a.toolbarText[g.toLowerCase()];d.append(f(g.toLowerCase(),m||g,j))}else d.append(f(g.css,g.text,g.action))}}var e=this,f=function(a,c,d){return b("<li/>").append(b("<a href='javascript:void(0);'/>").addClass(a).attr("title",c).click(function(){d.call(e,b(this))}))};if(0!==a.toolbar.length&&h.isArray(a.toolbar[0]))for(var d=0;d<a.toolbar.length;d++)c(a.toolbar[d]);else c(a.toolbar)},attachEditorEvents:function(){var a=this,c=function(){a.updateHtmlArea()};this.textarea.click(c).keyup(c).keydown(c).mousedown(c).blur(c);c=function(){a.updateTextArea()};b(this.editor.body).click(c).keyup(c).keydown(c).mousedown(c).blur(c);b("form").submit(function(){a.toggleHTMLView();a.toggleHTMLView()});if(window.__doPostBack){var d=__doPostBack;window.__doPostBack=function(){a&&a.toggleHTMLView&&(a.toggleHTMLView(),a.toggleHTMLView());return d.apply(window,arguments)}}},isArray:function(a){return a&&"object"===typeof a&&"number"===typeof a.length&&"function"===typeof a.splice&&!a.propertyIsEnumerable("length")}}})(jQuery);

/**
 * ******************************
 * jQuery-плагины для функционала
 * ******************************
 */

/**
 * HTML Clean for jQuery   
 * Anthony Johnston
 * http://www.antix.co.uk    
 *   
 * version 1.2.3
 * 
 * !!! плагин правленый, не обновлять !!!
 *
 */
(function(D){D.fn.htmlClean=function(X){return this.each(function(){var Y=D(this);if(this.value){this.value=D.htmlClean(this.value,X)}else{this.innerHTML=D.htmlClean(this.innerHTML,X)}})};D.htmlClean=function(c,Y){Y=D.extend({},D.htmlClean.defaults,Y);var d=/<(\/)?(\w+:)?([\w]+)([^>]*)>/gi;var j=/(\w+)=(".*?"|'.*?'|[^\s>]*)/gi;var r;var l=new Q();var b=[l];var e=l;var k=false;if(Y.bodyOnly){if(r=/<body[^>]*>((\n|.)*)<\/body>/i.exec(c)){c=r[1]}}c=c.concat("<xxx>");var o;while(r=d.exec(c)){var t=new I(r[3],r[1],r[4],Y);var f=c.substring(o,r.index);if(f.length>0){var a=e.children[e.children.length-1];if(e.children.length>0&&M(a=e.children[e.children.length-1])){e.children[e.children.length-1]=a.concat(f)}else{e.children.push(f)}}o=d.lastIndex;if(t.isClosing){if(J(b,[t.name])){b.pop();e=b[b.length-1]}}else{var X=new Q(t);var Z;while(Z=j.exec(t.rawAttributes)){if(Z[1].toLowerCase()=="style"&&Y.replaceStyles){var g=!t.isInline;for(var n=0;n<Y.replaceStyles.length;n++){if(Y.replaceStyles[n][0].test(Z[2])){if(!g){t.render=false;g=true}e.children.push(X);b.push(X);e=X;t=new I(Y.replaceStyles[n][1],"","",Y);X=new Q(t)}}}if(t.allowedAttributes!=null&&(t.allowedAttributes.length==0||D.inArray(Z[1],t.allowedAttributes)>-1)){X.attributes.push(new A(Z[1],Z[2]))}}D.each(t.requiredAttributes,function(){var u=this.toString();if(!X.hasAttribute(u)){X.attributes.push(new A(u,""))}});for(var m=0;m<Y.replace.length;m++){for(var q=0;q<Y.replace[m][0].length;q++){var p=typeof (Y.replace[m][0][q])=="string";if((p&&Y.replace[m][0][q]==t.name)||(!p&&Y.replace[m][0][q].test(r))){t.render=false;e.children.push(X);b.push(X);e=X;t=new I(Y.replace[m][1],r[1],r[4],Y);X=new Q(t);X.attributes=e.attributes;m=Y.replace.length;break}}}var h=true;if(!e.isRoot){if(e.tag.isInline&&!t.isInline){h=false}else{if(e.tag.disallowNest&&t.disallowNest&&!t.requiredParent){h=false}else{if(t.requiredParent){if(h=J(b,t.requiredParent)){e=b[b.length-1]}}}}}if(h){e.children.push(X);if(t.toProtect){while(tagMatch2=d.exec(c)){var s=new I(tagMatch2[3],tagMatch2[1],tagMatch2[4],Y);if(s.isClosing&&s.name==t.name){X.children.push(RegExp.leftContext.substring(o));o=d.lastIndex;break}}}else{if(!t.isSelfClosing&&!t.isNonClosing){b.push(X);e=X}}}}}return V(l,Y).join("")};D.htmlClean.defaults={Only:true,allowedTags:[],removeTags:["basefont","center","dir","font","frame","frameset","iframe","isindex","menu","noframes","s"],removeAttrs:[],allowedClasses:[],notRenderedTags:[],format:false,formatIndent:0,replace:[[["b","big"],"strong"],[["i"],"em"]],replaceStyles:[[/text-decoration:\s*underline/i,"u"],[/text-decoration:\s*line-through/i,"strike"],[/font-weight:\s*bold/i,"strong"],[/font-style:\s*italic/i,"em"],[/vertical-align:\s*super/i,"sup"],[/vertical-align:\s*sub/i,"sub"]]};function H(a,Z,Y,X){if(!a.tag.isInline&&Y.length>0){Y.push("\n");for(i=0;i<X;i++){Y.push("\t")}}}function V(b,h){var Y=[],e=b.attributes.length==0,Z;var c=this.name.concat(b.tag.rawAttributes==undefined?"":b.tag.rawAttributes);var g=b.tag.render&&(h.allowedTags.length==0||D.inArray(b.tag.name,h.allowedTags)>-1)&&(h.removeTags.length==0||D.inArray(b.tag.name,h.removeTags)==-1);if(!b.isRoot&&g){Y.push("<");Y.push(b.tag.name);D.each(b.attributes,function(){if(D.inArray(this.name,h.removeAttrs)==-1){var j=RegExp(/^(['"]?)(.*?)['"]?$/).exec(this.value);var k=j[2];var l=j[1]||"'";if(this.name=="class"){k=D.grep(k.split(" "),function(m){return D.grep(h.allowedClasses,function(n){return n[0]==m&&(n.length==1||D.inArray(b.tag.name,n[1])>-1)}).length>0}).join(" ");l="'"}if(k!=null&&(k.length>0||D.inArray(this.name,b.tag.requiredAttributes)>-1)){Y.push(" ");Y.push(this.name);Y.push("=");Y.push(l);Y.push(k);Y.push(l)}}})}if(b.tag.isSelfClosing){if(g){Y.push(" />")}e=false}else{if(b.tag.isNonClosing){e=false}else{if(!b.isRoot&&g){Y.push(">")}var Z=h.formatIndent++;if(b.tag.toProtect){var d=D.htmlClean.trim(b.children.join("")).replace(/<br>/ig,"\n");Y.push(d);e=d.length==0}else{var d=[];for(var a=0;a<b.children.length;a++){var X=b.children[a];var f=D.htmlClean.trim(C(M(X)?X:X.childrenToString()));if(P(X)){if(a>0&&f.length>0&&(U(X)||E(b.children[a-1]))){d.push(" ")}}if(M(X)){if(f.length>0){d.push(f)}}else{if(a!=b.children.length-1||X.tag.name!="br"){if(h.format){H(X,h,d,Z)}d=d.concat(V(X,h))}}}h.formatIndent--;if(d.length>0){if(h.format&&d[0]!="\n"){H(b,h,Y,Z)}Y=Y.concat(d);e=false}}if(!b.isRoot&&g){if(h.format){H(b,h,Y,Z-1)}Y.push("</");Y.push(b.tag.name);Y.push(">")}}}if(!b.tag.allowEmpty&&e){return[]}return Y}function J(X,Z,Y){Y=Y||1;if(D.inArray(X[X.length-Y].tag.name,Z)>-1){return true}else{if(X.length-(Y+1)>0&&J(X,Z,Y+1)){X.pop();return true}}return false}function Q(X){if(X){this.tag=X;this.isRoot=false}else{this.tag=new I("root");this.isRoot=true}this.attributes=[];this.children=[];this.hasAttribute=function(Y){for(var Z=0;Z<this.attributes.length;Z++){if(this.attributes[Z].name==Y){return true}}return false};this.childrenToString=function(){return this.children.join("")};return this}function A(X,Y){this.name=X;this.value=Y;return this}function I(Y,a,Z,X){this.name=Y.toLowerCase();this.isSelfClosing=D.inArray(this.name,K)>-1;this.isNonClosing=D.inArray(this.name,R)>-1;this.isClosing=(a!=undefined&&a.length>0);this.isInline=D.inArray(this.name,S)>-1;this.disallowNest=D.inArray(this.name,O)>-1;this.requiredParent=F[D.inArray(this.name,F)+1];this.allowEmpty=D.inArray(this.name,B)>-1;this.toProtect=D.inArray(this.name,G)>-1;this.rawAttributes=Z;this.allowedAttributes=L[D.inArray(this.name,L)+1];this.requiredAttributes=W[D.inArray(this.name,W)+1];this.render=X&&D.inArray(this.name,X.notRenderedTags)==-1;return this}function U(X){while(N(X)&&X.children.length>0){X=X.children[0]}return M(X)&&X.length>0&&D.htmlClean.isWhitespace(X.charAt(0))}function E(X){while(N(X)&&X.children.length>0){X=X.children[X.children.length-1]}return M(X)&&X.length>0&&D.htmlClean.isWhitespace(X.charAt(X.length-1))}function M(X){return X.constructor==String}function P(X){return M(X)||X.tag.isInline}function N(X){return X.constructor==Q}function C(X){return X.replace(/&nbsp;|\n/g," ").replace(/\s\s+/g," ")}D.htmlClean.trim=function(X){return D.htmlClean.trimStart(D.htmlClean.trimEnd(X))};D.htmlClean.trimStart=function(X){return X.substring(D.htmlClean.trimStartIndex(X))};D.htmlClean.trimStartIndex=function(X){for(var Y=0;Y<X.length-1&&D.htmlClean.isWhitespace(X.charAt(Y));Y++){}return Y};D.htmlClean.trimEnd=function(X){return X.substring(0,D.htmlClean.trimEndIndex(X))};D.htmlClean.trimEndIndex=function(Y){for(var X=Y.length-1;X>=0&&D.htmlClean.isWhitespace(Y.charAt(X));X--){}return X+1};D.htmlClean.isWhitespace=function(X){return D.inArray(X,T)!=-1};var S=["a","abbr","acronym","address","b","big","br","button","caption","cite","code","del","em","font","hr","i","input","img","ins","label","legend","map","q","samp","select","small","span","strong","strike", "sub","sup","tt", "u","var"];var O=["h1","h2","h3","h4","h5","h6","p","th","td"];var B=["th","td"];var F=[null,"li",["ul","ol"],"dt",["dl"],"dd",["dl"],"td",["tr"],"th",["tr"],"tr",["table","thead","tbody","tfoot"],"thead",["table"],"tbody",["table"],"tfoot",["table"]];var G=["script","style","pre","code"];var K=["br","hr","img","link","meta"];var R=["!doctype","?xml"];var L=[["class"],"?xml",[],"!doctype",[],"a",["accesskey","class","href","name","title","rel","rev","type","tabindex"],"abbr",["class","title"],"acronym",["class","title"],"blockquote",["cite","class"],"button",["class","disabled","name","type","value"],"del",["cite","class","datetime"],"form",["accept","action","class","enctype","method","name"],"input",["accept","accesskey","alt","checked","class","disabled","ismap","maxlength","name","size","readonly","src","tabindex","type","usemap","value"],"img",["alt","class","height","src","width"],"ins",["cite","class","datetime"],"label",["accesskey","class","for"],"legend",["accesskey","class"],"link",["href","rel","type"],"meta",["content","http-equiv","name","scheme"],"map",["name"],"optgroup",["class","disabled","label"],"option",["class","disabled","label","selected","value"],"q",["class","cite"],"script",["src","type"],"select",["class","disabled","multiple","name","size","tabindex"],"style",["type"],"table",["class","summary"],"textarea",["accesskey","class","cols","disabled","name","readonly","rows","tabindex"]];var W=[[],"img",["alt"]];var T=[" "," ","\t","\n","\r","\f"]})(jQuery);


/**
 * ************************
 * Собственный код страницы
 * ************************
 */

var map, // Объект карты
    marker; // Объект маркера на карте, указывающего координаты юзера

/**
 * Обновление всех фильтруемых данных на странице.
 * Примечание: эта функция имеет полиморфное поведение в зависимости от страницы.
 */
function refreshAll()
{
    // На странице нет данных, зависящих от региона
}

$(function(){ // Готовность DOM
    $('a.photoHref').colorbox();

    // Карта для показа координат сообщения:
    var markerCoords = new google.maps.LatLng( parseFloat($('#messageLat').text()),
                                               parseFloat($('#messageLng').text()) );
    map = new google.maps.Map($('#locationMapCanvas').get(0), {
        center: markerCoords,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        zoom: 7,
        zoomControl: true,
        zoomControlOptions: {style: google.maps.ZoomControlStyle.SMALL},
        noClear: true,
        overviewMapControl: false,
        scaleControl: true,
        panControl: true,
        rotateControl: false,
        streetViewControl: false
    });
    
    var markerIcon = '';
    switch(VAR_MESSAGE_TYPE) {
        case 'request':
            markerIcon = '/images/nh_icon.png';
            break;
        case 'offer':
            markerIcon = '/images/wh_icon.png';
            break;
        default:
            markerIcon = '';
     }
    new google.maps.Marker({
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        position: markerCoords,
        map: map,
        icon: markerIcon,
        draggable: false,
        title: 'Координаты сообщения'
    });

    // Show contacts:
    $('#showContacts').click(function(){
        $(this).parent().slideUp(250);
        $('#contactsData').slideDown(250);
    });
});