/**
 * **************************
 * jQuery-плагины для дизайна
 * **************************
 */

// jHtmlArea - http://jhtmlarea.codeplex.com - (c)2009 Chris Pietschmann
(function(b){b.fn.htmlarea=function(a){if(a&&"string"===typeof a){for(var c=[],b=1;b<arguments.length;b++)c.push(arguments[b]);var b=d(this[0]),f=b[a];if(f)return f.apply(b,c)}return this.each(function(){d(this,a)})};var d=window.jHtmlArea=function(a,b){return a.jquery?d(a[0]):a.jhtmlareaObject?a.jhtmlareaObject:new d.fn.init(a,b)};d.fn=d.prototype={jhtmlarea:"0.7.0",init:function(a,c){if("textarea"===a.nodeName.toLowerCase()){var e=b.extend({},d.defaultOptions,c);a.jhtmlareaObject=this;var f=this.textarea= b(a),j=this.container=b("<div/>").addClass("jHtmlArea").width(f.width()).insertAfter(f),l=this.toolbar=b("<div/>").addClass("ToolBar").appendTo(j);h.initToolBar.call(this,e);var k=this.iframe=b("<iframe/>").height(f.height());k.width(f.width()-(b.browser.msie?0:4));var i=this.htmlarea=b("<div/>").append(k);j.append(i).append(f.hide());h.initEditor.call(this,e);h.attachEditorEvents.call(this);k.height(k.height()-l.height());l.width(f.width()-2);e.loaded&&e.loaded.call(this)}},dispose:function(){this.textarea.show().insertAfter(this.container);this.container.remove();this.textarea[0].jhtmlareaObject=null},execCommand:function(a,b,e){this.iframe[0].contentWindow.focus();this.editor.execCommand(a,b||!1,e||null);this.updateTextArea()},ec:function(a,b,e){this.execCommand(a,b,e)},queryCommandValue:function(a){this.iframe[0].contentWindow.focus();return this.editor.queryCommandValue(a)},qc:function(a){return this.queryCommandValue(a)},getSelectedHTML:function(){if(b.browser.msie)return this.getRange().htmlText;var a=this.getRange().cloneContents();return b("<p/>").append(b(a)).html()},getSelection:function(){return b.browser.msie?this.editor.selection:this.iframe[0].contentDocument.defaultView.getSelection()},getRange:function(){var a=this.getSelection();return!a?null:a.getRangeAt?a.getRangeAt(0):a.createRange()},html:function(a){if(a)this.pastHTML(a);else return toHtmlString()},pasteHTML:function(a){this.iframe[0].contentWindow.focus();var c=this.getRange();b.browser.msie?c.pasteHTML(a):b.browser.mozilla?(c.deleteContents(),c.insertNode(b(0!= a.indexOf("<")?b("<span/>").append(a):a)[0])):(c.deleteContents(),c.insertNode(b(this.iframe[0].contentWindow.document.createElement("span")).append(b(0!=a.indexOf("<")?"<span>"+a+"</span>":a))[0]));c.collapse(!1);"function"==typeof c.select&&c.select()},cut:function(){this.ec("cut")},copy:function(){this.ec("copy")},paste:function(){this.ec("paste")},bold:function(){this.ec("bold")},italic:function(){this.ec("italic")},underline:function(){this.ec("underline")},strikeThrough:function(){this.ec("strikethrough")}, image:function(a){b.browser.msie&&!a?this.ec("insertImage",!0):this.ec("insertImage",!1,a||prompt("Image URL:","http://"))},removeFormat:function(){this.ec("removeFormat",!1,[]);this.unlink()},link:function(){b.browser.msie?this.ec("createLink",!0):this.ec("createLink",!1,prompt("Link URL:","http://"))},unlink:function(){this.ec("unlink",!1,[])},orderedList:function(){this.ec("insertorderedlist")},unorderedList:function(){this.ec("insertunorderedlist")},superscript:function(){this.ec("superscript")}, subscript:function(){this.ec("subscript")},p:function(){this.formatBlock("<p>")},h1:function(){this.heading(1)},h2:function(){this.heading(2)},h3:function(){this.heading(3)},h4:function(){this.heading(4)},h5:function(){this.heading(5)},h6:function(){this.heading(6)},heading:function(a){this.formatBlock(b.browser.msie?"Heading "+a:"h"+a)},indent:function(){this.ec("indent")},outdent:function(){this.ec("outdent")},insertHorizontalRule:function(){this.ec("insertHorizontalRule",!1,"ht")},justifyLeft:function(){this.ec("justifyLeft")}, justifyCenter:function(){this.ec("justifyCenter")},justifyRight:function(){this.ec("justifyRight")},increaseFontSize:function(){b.browser.msie?this.ec("fontSize",!1,this.qc("fontSize")+1):b.browser.safari?this.getRange().surroundContents(b(this.iframe[0].contentWindow.document.createElement("span")).css("font-size","larger")[0]):this.ec("increaseFontSize",!1,"big")},decreaseFontSize:function(){b.browser.msie?this.ec("fontSize",!1,this.qc("fontSize")-1):b.browser.safari?this.getRange().surroundContents(b(this.iframe[0].contentWindow.document.createElement("span")).css("font-size", "smaller")[0]):this.ec("decreaseFontSize",!1,"small")},forecolor:function(a){this.ec("foreColor",!1,a||prompt("Enter HTML Color:","#"))},formatBlock:function(a){this.ec("formatblock",!1,a||null)},showHTMLView:function(){this.updateTextArea();this.textarea.show();this.htmlarea.hide();b("ul li:not(li:has(a.html))",this.toolbar).hide();b("ul:not(:has(:visible))",this.toolbar).hide();b("ul li a.html",this.toolbar).addClass("highlighted")},hideHTMLView:function(){this.updateHtmlArea();this.textarea.hide();this.htmlarea.show();b("ul",this.toolbar).show();b("ul li",this.toolbar).show().find("a.html").removeClass("highlighted")},toggleHTMLView:function(){this.textarea.is(":hidden")?this.showHTMLView():this.hideHTMLView()},toHtmlString:function(){return this.editor.body.innerHTML},toString:function(){return this.editor.body.innerText},updateTextArea:function(){this.textarea.val(this.toHtmlString())},updateHtmlArea:function(){this.editor.body.innerHTML=this.textarea.val()}};d.fn.init.prototype=d.fn;d.defaultOptions= {toolbar:[["html"],"bold,italic,underline,strikethrough,|,subscript,superscript".split(","),["increasefontsize","decreasefontsize"],["orderedlist","unorderedlist"],["indent","outdent"],["justifyleft","justifycenter","justifyright"],["link","unlink","image","horizontalrule"],"p,h1,h2,h3,h4,h5,h6".split(","),["cut","copy","paste"]],css:null,toolbarText:{bold:"Bold",italic:"Italic",underline:"Underline",strikethrough:"Strike-Through",cut:"Cut",copy:"Copy",paste:"Paste",h1:"Heading 1",h2:"Heading 2", h3:"Heading 3",h4:"Heading 4",h5:"Heading 5",h6:"Heading 6",p:"Paragraph",indent:"Indent",outdent:"Outdent",horizontalrule:"Insert Horizontal Rule",justifyleft:"Left Justify",justifycenter:"Center Justify",justifyright:"Right Justify",increasefontsize:"Increase Font Size",decreasefontsize:"Decrease Font Size",forecolor:"Text Color",link:"Insert Link",unlink:"Remove Link",image:"Insert Image",orderedlist:"Insert Ordered List",unorderedlist:"Insert Unordered List",subscript:"Subscript",superscript:"Superscript", html:"Show/Hide HTML Source View"}};var h={toolbarButtons:{strikethrough:"strikeThrough",orderedlist:"orderedList",unorderedlist:"unorderedList",horizontalrule:"insertHorizontalRule",justifyleft:"justifyLeft",justifycenter:"justifyCenter",justifyright:"justifyRight",increasefontsize:"increaseFontSize",decreasefontsize:"decreaseFontSize",html:function(){this.toggleHTMLView()}},initEditor:function(a){var b=this.editor=this.iframe[0].contentWindow.document;b.designMode="on";b.open();b.write(this.textarea.val());b.close();if(a.css){var e=b.createElement("link");e.rel="stylesheet";e.type="text/css";e.href=a.css;b.getElementsByTagName("head")[0].appendChild(e)}},initToolBar:function(a){function c(c){for(var d=b("<ul/>").appendTo(e.toolbar),i=0;i<c.length;i++){var g=c[i];if("string"===(typeof g).toLowerCase())if("|"===g)d.append(b('<li class="separator"/>'));else{var j=function(a){var b=h.toolbarButtons[a]||a;return"function"===(typeof b).toLowerCase()?function(a){b.call(this,a)}:function(){this[b]();this.editor.body.focus()}}(g.toLowerCase()), m=a.toolbarText[g.toLowerCase()];d.append(f(g.toLowerCase(),m||g,j))}else d.append(f(g.css,g.text,g.action))}}var e=this,f=function(a,c,d){return b("<li/>").append(b("<a href='javascript:void(0);'/>").addClass(a).attr("title",c).click(function(){d.call(e,b(this))}))};if(0!==a.toolbar.length&&h.isArray(a.toolbar[0]))for(var d=0;d<a.toolbar.length;d++)c(a.toolbar[d]);else c(a.toolbar)},attachEditorEvents:function(){var a=this,c=function(){a.updateHtmlArea()};this.textarea.click(c).keyup(c).keydown(c).mousedown(c).blur(c);c=function(){a.updateTextArea()};b(this.editor.body).click(c).keyup(c).keydown(c).mousedown(c).blur(c);b("form").submit(function(){a.toggleHTMLView();a.toggleHTMLView()});if(window.__doPostBack){var d=__doPostBack;window.__doPostBack=function(){a&&a.toggleHTMLView&&(a.toggleHTMLView(),a.toggleHTMLView());return d.apply(window,arguments)}}},isArray:function(a){return a&&"object"===typeof a&&"number"===typeof a.length&&"function"===typeof a.splice&&!a.propertyIsEnumerable("length")}}})(jQuery);
/**
 * ******************************
 * jQuery-плагины для функционала
 * ******************************
 */

/**
 * AJAX Upload ( http://valums.com/ajax-upload/ )
 * Copyright (c) Andrew Valums
 * Licensed under the MIT license
 */
(function(){function d(a,b,c){if(a.addEventListener)a.addEventListener(b,c,!1);else if(a.attachEvent)a.attachEvent("on"+b,function(){c.call(a)});else throw Error("not supported or DOM not loaded");}function g(a,b){for(var c in b)b.hasOwnProperty(c)&&(a.style[c]=b[c])}function i(a,b){RegExp("\\b"+b+"\\b").test(a.className)||(a.className+=" "+b)}function f(a,b){a.className=a.className.replace(RegExp("\\b"+b+"\\b"),"")}function h(a){a.parentNode.removeChild(a)}var l=document.documentElement.getBoundingClientRect? function(a){var b=a.getBoundingClientRect(),c=a.ownerDocument,a=c.body,c=c.documentElement,j=c.clientTop||a.clientTop||0,d=c.clientLeft||a.clientLeft||0,e=1;a.getBoundingClientRect&&(e=a.getBoundingClientRect(),e=(e.right-e.left)/a.clientWidth);e>1&&(d=j=0);return{top:b.top/e+(window.pageYOffset||c&&c.scrollTop/e||a.scrollTop/e)-j,left:b.left/e+(window.pageXOffset||c&&c.scrollLeft/e||a.scrollLeft/e)-d}}:function(a){var b=0,c=0;do b+=a.offsetTop||0,c+=a.offsetLeft||0,a=a.offsetParent;while(a);return{left:c, top:b}},k=function(){var a=document.createElement("div");return function(b){a.innerHTML=b;return a.removeChild(a.firstChild)}}(),m=function(){var a=0;return function(){return"ValumsAjaxUpload"+a++}}();window.AjaxUpload=function(a,b){this._settings={action:"upload.php",name:"userfile",multiple:!1,data:{},autoSubmit:!0,responseType:!1,hoverClass:"hover",focusClass:"focus",disabledClass:"disabled",onChange:function(){},onSubmit:function(){},onComplete:function(){}};for(var c in b)b.hasOwnProperty(c)&& (this._settings[c]=b[c]);a.jquery?a=a[0]:typeof a=="string"&&(/^#.*/.test(a)&&(a=a.slice(1)),a=document.getElementById(a));if(!a||a.nodeType!==1)throw Error("Please make sure that you're passing a valid element");a.nodeName.toUpperCase()=="A"&&d(a,"click",function(a){if(a&&a.preventDefault)a.preventDefault();else if(window.event)window.event.returnValue=!1});this._button=a;this._input=null;this._disabled=!1;this.enable();this._rerouteClicks()};AjaxUpload.prototype={setData:function(a){this._settings.data= a},disable:function(){i(this._button,this._settings.disabledClass);this._disabled=!0;var a=this._button.nodeName.toUpperCase();(a=="INPUT"||a=="BUTTON")&&this._button.setAttribute("disabled","disabled");if(this._input&&this._input.parentNode)this._input.parentNode.style.visibility="hidden"},enable:function(){f(this._button,this._settings.disabledClass);this._button.removeAttribute("disabled");this._disabled=!1},_createInput:function(){var a=this,b=document.createElement("input");b.setAttribute("type", "file");b.setAttribute("name",this._settings.name);this._settings.multiple&&b.setAttribute("multiple","multiple");g(b,{position:"absolute",right:0,margin:0,padding:0,fontSize:"480px",fontFamily:"sans-serif",cursor:"pointer"});var c=document.createElement("div");g(c,{display:"block",position:"absolute",overflow:"hidden",margin:0,padding:0,opacity:0,direction:"ltr",zIndex:2147483583});if(c.style.opacity!=="0"){if(typeof c.filters=="undefined")throw Error("Opacity not supported by the browser");c.style.filter= "alpha(opacity=0)"}d(b,"change",function(){if(b&&b.value!==""){var c=b.value.replace(/.*(\/|\\)/,"");!1===a._settings.onChange.call(a,c,-1!==c.indexOf(".")?c.replace(/.*[.]/,""):"")?a._clearInput():a._settings.autoSubmit&&a.submit()}});d(b,"mouseover",function(){i(a._button,a._settings.hoverClass)});d(b,"mouseout",function(){f(a._button,a._settings.hoverClass);f(a._button,a._settings.focusClass);if(b.parentNode)b.parentNode.style.visibility="hidden"});d(b,"focus",function(){i(a._button,a._settings.focusClass)});d(b,"blur",function(){f(a._button,a._settings.focusClass)});c.appendChild(b);document.body.appendChild(c);this._input=b},_clearInput:function(){if(this._input)h(this._input.parentNode),this._input=null,this._createInput(),f(this._button,this._settings.hoverClass),f(this._button,this._settings.focusClass)},_rerouteClicks:function(){var a=this;d(a._button,"mouseover",function(){if(!a._disabled){a._input||a._createInput();var b=a._input.parentNode,c=a._button,d=l(c);g(b,{position:"absolute",left:d.left+ "px",top:d.top+"px",width:c.offsetWidth+"px",height:c.offsetHeight+"px"});b.style.visibility="visible"}})},_createIframe:function(){var a=m(),b=k('<iframe src="javascript:false;" name="'+a+'" />');b.setAttribute("id",a);b.style.display="none";document.body.appendChild(b);return b},_createForm:function(a){var b=this._settings,c=k('<form method="post" enctype="multipart/form-data"></form>');c.setAttribute("action",b.action);c.setAttribute("target",a.name);c.style.display="none";document.body.appendChild(c);for(var d in b.data)b.data.hasOwnProperty(d)&&(a=document.createElement("input"),a.setAttribute("type","hidden"),a.setAttribute("name",d),a.setAttribute("value",b.data[d]),c.appendChild(a));return c},_getResponse:function(a,b){var c=!1,f=this,g=this._settings;d(a,"load",function(){if(a.src=="javascript:'%3Chtml%3E%3C/html%3E';"||a.src=="javascript:'<html></html>';")c&&setTimeout(function(){h(a)},0);else{var e=a.contentDocument?a.contentDocument:window.frames[a.id].document;if(!(e.readyState&&e.readyState!= "complete")&&!(e.body&&e.body.innerHTML=="false")){var d;if(e.XMLDocument)d=e.XMLDocument;else if(e.body){if(d=e.body.innerHTML,g.responseType&&g.responseType.toLowerCase()=="json"){if(e.body.firstChild&&e.body.firstChild.nodeName.toUpperCase()=="PRE")e.normalize(),d=e.body.firstChild.firstChild.nodeValue;d=d?eval("("+d+")"):{}}}else d=e;g.onComplete.call(f,b,d);c=!0;a.src="javascript:'<html></html>';"}}})},submit:function(){var a=this._settings;if(this._input&&this._input.value!==""){var b=this._input.value.replace(/.*(\/|\\)/, "");if(!1===a.onSubmit.call(this,b,-1!==b.indexOf(".")?b.replace(/.*[.]/,""):""))this._clearInput();else{var a=this._createIframe(),c=this._createForm(a);h(this._input.parentNode);f(this._button,this._settings.hoverClass);f(this._button,this._settings.focusClass);c.appendChild(this._input);c.submit();h(c);h(this._input);this._input=null;this._getResponse(a,b);this._createInput()}}}}})();

/**
 * HTML Clean for jQuery
 * Anthony Johnston
 * http://www.antix.co.uk
 *
 * version 1.2.3
 *
 */
(function(D){D.fn.htmlClean=function(X){return this.each(function(){var Y=D(this);if(this.value){this.value=D.htmlClean(this.value,X)}else{this.innerHTML=D.htmlClean(this.innerHTML,X)}})};D.htmlClean=function(c,Y){Y=D.extend({},D.htmlClean.defaults,Y);var d=/<(\/)?(\w+:)?([\w]+)([^>]*)>/gi;var j=/(\w+)=(".*?"|'.*?'|[^\s>]*)/gi;var r;var l=new Q();var b=[l];var e=l;var k=false;if(Y.bodyOnly){if(r=/<body[^>]*>((\n|.)*)<\/body>/i.exec(c)){c=r[1]}}c=c.concat("<xxx>");var o;while(r=d.exec(c)){var t=new I(r[3],r[1],r[4],Y);var f=c.substring(o,r.index);if(f.length>0){var a=e.children[e.children.length-1];if(e.children.length>0&&M(a=e.children[e.children.length-1])){e.children[e.children.length-1]=a.concat(f)}else{e.children.push(f)}}o=d.lastIndex;if(t.isClosing){if(J(b,[t.name])){b.pop();e=b[b.length-1]}}else{var X=new Q(t);var Z;while(Z=j.exec(t.rawAttributes)){if(Z[1].toLowerCase()=="style"&&Y.replaceStyles){var g=!t.isInline;for(var n=0;n<Y.replaceStyles.length;n++){if(Y.replaceStyles[n][0].test(Z[2])){if(!g){t.render=false;g=true}e.children.push(X);b.push(X);e=X;t=new I(Y.replaceStyles[n][1],"","",Y);X=new Q(t)}}}if(t.allowedAttributes!=null&&(t.allowedAttributes.length==0||D.inArray(Z[1],t.allowedAttributes)>-1)){X.attributes.push(new A(Z[1],Z[2]))}}D.each(t.requiredAttributes,function(){var u=this.toString();if(!X.hasAttribute(u)){X.attributes.push(new A(u,""))}});for(var m=0;m<Y.replace.length;m++){for(var q=0;q<Y.replace[m][0].length;q++){var p=typeof (Y.replace[m][0][q])=="string";if((p&&Y.replace[m][0][q]==t.name)||(!p&&Y.replace[m][0][q].test(r))){t.render=false;e.children.push(X);b.push(X);e=X;t=new I(Y.replace[m][1],r[1],r[4],Y);X=new Q(t);X.attributes=e.attributes;m=Y.replace.length;break}}}var h=true;if(!e.isRoot){if(e.tag.isInline&&!t.isInline){h=false}else{if(e.tag.disallowNest&&t.disallowNest&&!t.requiredParent){h=false}else{if(t.requiredParent){if(h=J(b,t.requiredParent)){e=b[b.length-1]}}}}}if(h){e.children.push(X);if(t.toProtect){while(tagMatch2=d.exec(c)){var s=new I(tagMatch2[3],tagMatch2[1],tagMatch2[4],Y);if(s.isClosing&&s.name==t.name){X.children.push(RegExp.leftContext.substring(o));o=d.lastIndex;break}}}else{if(!t.isSelfClosing&&!t.isNonClosing){b.push(X);e=X}}}}}return V(l,Y).join("")};D.htmlClean.defaults={Only:true,allowedTags:[],removeTags:["basefont","center","dir","font","frame","frameset","iframe","isindex","menu","noframes","s","strike","u"],removeAttrs:[],allowedClasses:[],notRenderedTags:[],format:false,formatIndent:0,replace:[[["b","big"],"strong"],[["i"],"em"]],replaceStyles:[[/font-weight:\s*bold/i,"strong"],[/font-style:\s*italic/i,"em"],[/vertical-align:\s*super/i,"sup"],[/vertical-align:\s*sub/i,"sub"]]};function H(a,Z,Y,X){if(!a.tag.isInline&&Y.length>0){Y.push("\n");for(i=0;i<X;i++){Y.push("\t")}}}function V(b,h){var Y=[],e=b.attributes.length==0,Z;var c=this.name.concat(b.tag.rawAttributes==undefined?"":b.tag.rawAttributes);var g=b.tag.render&&(h.allowedTags.length==0||D.inArray(b.tag.name,h.allowedTags)>-1)&&(h.removeTags.length==0||D.inArray(b.tag.name,h.removeTags)==-1);if(!b.isRoot&&g){Y.push("<");Y.push(b.tag.name);D.each(b.attributes,function(){if(D.inArray(this.name,h.removeAttrs)==-1){var j=RegExp(/^(['"]?)(.*?)['"]?$/).exec(this.value);var k=j[2];var l=j[1]||"'";if(this.name=="class"){k=D.grep(k.split(" "),function(m){return D.grep(h.allowedClasses,function(n){return n[0]==m&&(n.length==1||D.inArray(b.tag.name,n[1])>-1)}).length>0}).join(" ");l="'"}if(k!=null&&(k.length>0||D.inArray(this.name,b.tag.requiredAttributes)>-1)){Y.push(" ");Y.push(this.name);Y.push("=");Y.push(l);Y.push(k);Y.push(l)}}})}if(b.tag.isSelfClosing){if(g){Y.push(" />")}e=false}else{if(b.tag.isNonClosing){e=false}else{if(!b.isRoot&&g){Y.push(">")}var Z=h.formatIndent++;if(b.tag.toProtect){var d=D.htmlClean.trim(b.children.join("")).replace(/<br>/ig,"\n");Y.push(d);e=d.length==0}else{var d=[];for(var a=0;a<b.children.length;a++){var X=b.children[a];var f=D.htmlClean.trim(C(M(X)?X:X.childrenToString()));if(P(X)){if(a>0&&f.length>0&&(U(X)||E(b.children[a-1]))){d.push(" ")}}if(M(X)){if(f.length>0){d.push(f)}}else{if(a!=b.children.length-1||X.tag.name!="br"){if(h.format){H(X,h,d,Z)}d=d.concat(V(X,h))}}}h.formatIndent--;if(d.length>0){if(h.format&&d[0]!="\n"){H(b,h,Y,Z)}Y=Y.concat(d);e=false}}if(!b.isRoot&&g){if(h.format){H(b,h,Y,Z-1)}Y.push("</");Y.push(b.tag.name);Y.push(">")}}}if(!b.tag.allowEmpty&&e){return[]}return Y}function J(X,Z,Y){Y=Y||1;if(D.inArray(X[X.length-Y].tag.name,Z)>-1){return true}else{if(X.length-(Y+1)>0&&J(X,Z,Y+1)){X.pop();return true}}return false}function Q(X){if(X){this.tag=X;this.isRoot=false}else{this.tag=new I("root");this.isRoot=true}this.attributes=[];this.children=[];this.hasAttribute=function(Y){for(var Z=0;Z<this.attributes.length;Z++){if(this.attributes[Z].name==Y){return true}}return false};this.childrenToString=function(){return this.children.join("")};return this}function A(X,Y){this.name=X;this.value=Y;return this}function I(Y,a,Z,X){this.name=Y.toLowerCase();this.isSelfClosing=D.inArray(this.name,K)>-1;this.isNonClosing=D.inArray(this.name,R)>-1;this.isClosing=(a!=undefined&&a.length>0);this.isInline=D.inArray(this.name,S)>-1;this.disallowNest=D.inArray(this.name,O)>-1;this.requiredParent=F[D.inArray(this.name,F)+1];this.allowEmpty=D.inArray(this.name,B)>-1;this.toProtect=D.inArray(this.name,G)>-1;this.rawAttributes=Z;this.allowedAttributes=L[D.inArray(this.name,L)+1];this.requiredAttributes=W[D.inArray(this.name,W)+1];this.render=X&&D.inArray(this.name,X.notRenderedTags)==-1;return this}function U(X){while(N(X)&&X.children.length>0){X=X.children[0]}return M(X)&&X.length>0&&D.htmlClean.isWhitespace(X.charAt(0))}function E(X){while(N(X)&&X.children.length>0){X=X.children[X.children.length-1]}return M(X)&&X.length>0&&D.htmlClean.isWhitespace(X.charAt(X.length-1))}function M(X){return X.constructor==String}function P(X){return M(X)||X.tag.isInline}function N(X){return X.constructor==Q}function C(X){return X.replace(/&nbsp;|\n/g," ").replace(/\s\s+/g," ")}D.htmlClean.trim=function(X){return D.htmlClean.trimStart(D.htmlClean.trimEnd(X))};D.htmlClean.trimStart=function(X){return X.substring(D.htmlClean.trimStartIndex(X))};D.htmlClean.trimStartIndex=function(X){for(var Y=0;Y<X.length-1&&D.htmlClean.isWhitespace(X.charAt(Y));Y++){}return Y};D.htmlClean.trimEnd=function(X){return X.substring(0,D.htmlClean.trimEndIndex(X))};D.htmlClean.trimEndIndex=function(Y){for(var X=Y.length-1;X>=0&&D.htmlClean.isWhitespace(Y.charAt(X));X--){}return X+1};D.htmlClean.isWhitespace=function(X){return D.inArray(X,T)!=-1};var S=["a","abbr","acronym","address","b","big","br","button","caption","cite","code","del","em","font","hr","i","input","img","ins","label","legend","map","q","samp","select","small","span","strong","sub","sup","tt","var"];var O=["h1","h2","h3","h4","h5","h6","p","th","td"];var B=["th","td"];var F=[null,"li",["ul","ol"],"dt",["dl"],"dd",["dl"],"td",["tr"],"th",["tr"],"tr",["table","thead","tbody","tfoot"],"thead",["table"],"tbody",["table"],"tfoot",["table"]];var G=["script","style","pre","code"];var K=["br","hr","img","link","meta"];var R=["!doctype","?xml"];var L=[["class"],"?xml",[],"!doctype",[],"a",["accesskey","class","href","name","title","rel","rev","type","tabindex"],"abbr",["class","title"],"acronym",["class","title"],"blockquote",["cite","class"],"button",["class","disabled","name","type","value"],"del",["cite","class","datetime"],"form",["accept","action","class","enctype","method","name"],"input",["accept","accesskey","alt","checked","class","disabled","ismap","maxlength","name","size","readonly","src","tabindex","type","usemap","value"],"img",["alt","class","height","src","width"],"ins",["cite","class","datetime"],"label",["accesskey","class","for"],"legend",["accesskey","class"],"link",["href","rel","type"],"meta",["content","http-equiv","name","scheme"],"map",["name"],"optgroup",["class","disabled","label"],"option",["class","disabled","label","selected","value"],"q",["class","cite"],"script",["src","type"],"select",["class","disabled","multiple","name","size","tabindex"],"style",["type"],"table",["class","summary"],"textarea",["accesskey","class","cols","disabled","name","readonly","rows","tabindex"]];var W=[[],"img",["alt"]];var T=[" "," ","\t","\n","\r","\f"]})(jQuery);

/**
 * ************************
 * Собственный код страницы
 * ************************
 */

 /**
 * Обновление всех фильтруемых данных на странице.
 * Примечание: эта функция имеет полиморфное поведение в зависимости от страницы.
 */
function refreshAll()
{
    // На странице нет данных, зависящих от региона
}

var $form = $('form#mainForm'),
    $message = $('#formResponseMessage', $form),
    $submitMessageBlock = $('#submitMessage'),
    map, // Объект карты для выбора координат
//    mapIsInitialized = false,
    marker, // Объект маркера на карте, указывающего координаты юзера
    geocoder, // Объект геолокатора
    $locationMap = $('#locationMap', $form),
    $ajaxLoading = $('img#mapResponseLoading', $locationMap),
    $mapMessage = $('#locationMapMessage', $locationMap),
    $mapMessageText = $('#mapResponseText', $locationMap),
    $mapSaveLocation = $('#mapSaveLocation', $locationMap),
    $searchControl = $('#locationMapControl', $locationMap),
    $addressField = $('input#locationAddress', $searchControl),
    $addressStatus = $('#locationAddressStatus', $form),
    $regionIdField = $('input#locationRegionId', $form);
    // Переменные для защиты от дабл-поста:
//    initFormData = $form.serialize(),
//    curFormData = initFormData;

/**
 * Валидация формы добавления просьбы/предложения о помощи.
 *
 * Для локализации сообщений об ошибках использует константы, которые должны передаваться
 * контроллером страницы в вид jsVars.php, создающий из этих констант js-переменные.
 *
 * @param $form object Объект формы добавления сообщения. Можно передать с jQuery-обёрткой.
 * @return boolean Возвращается true, если форма валидна, и false в противном случае.
 */
function validateMessagesAdd($form)
{
    if( !$form )
        return false;
    $form = $($form);

    var field = {},
        value = '',
        isFocused = false,
        result = true;

    // Согласие на обработку данных (обязательно):
    field = $('#agreed', $form);
    if(field.length) {
        value = field.attr('checked');
        if( !value ) {
            field.addClass('invalidField');
            $('#agreedError', $form).html(LANG_AGREE_REQUIRED).slideDown(250);
            result = result && false;
        } else {
            field.removeClass('invalidField');
            $('#agreedError', $form).slideUp(250).html('');
        }
        if( !result && !isFocused ) {
            field.focus();
            isFocused = true;
        }
    }

    // Имя (обязательно):
    field = $('#firstName', $form);
    value = $.trim(field.val());
    if(value.length <= 0) {
        field.addClass('invalidField');
        $('#firstNameError', $form).html(LANG_FIRST_NAME_REQUIRED).slideDown(250);
        result = result && false;
    } else if( !/^[ ]*[a-zа-я"']+[a-zа-яё "'-]*[a-zа-я"']+[ ]*$/i.test(value) ) {
        field.addClass('invalidField');
        $('#firstNameError', $form).html(LANG_FIRST_NAME_INVALID).slideDown(250);
        result = result && false;
    } else {
        field.removeClass('invalidField');
        $('#firstNameError', $form).slideUp(250).html('');
    }
    if( !result && !isFocused ) {
        field.focus();
        isFocused = true;
    }

    // Фамилия (обязательно):
    field = $('#lastName', $form);
    value = $.trim(field.val());
    if(value.length <= 0) {
        field.addClass('invalidField');
        $('#lastNameError', $form).html(LANG_LAST_NAME_REQUIRED).slideDown(250);
        result = result && false;
    } else if(value && !/^[ ]*[a-zа-я"']+[a-zа-яё "'-]*[a-zа-я"']+[ ]*$/i.test(value)) {
        field.addClass('invalidField');
        $('#lastNameError', $form).html(LANG_LAST_NAME_INVALID).slideDown(250);
        result = result && false;
    } else {
        field.removeClass('invalidField');
        $('#lastNameError', $form).slideUp(250).html('');
    }
    if( !result && !isFocused ) {
        field.focus();
        isFocused = true;
    }

    // Телефоны основной и дополнительные (необязательно):
    var phoneIsSet = false;
    $('#phonesList > li:not(#phonePrototype)', $form).find('.phone:not(.empty)').each(function(){
        var $this = $(this);

        field = $($this, $form);
        value = $.trim(field.val());
        if(value && !/^[ ]*[0-9-\+\(\) ]+[ ]*$/i.test(value)) {
            $($this, $form).addClass('invalidField');
            $this.parent().find('.phoneError').html(LANG_PHONE_INVALID).slideDown(250);
            result = result && false;
        } else {
            $($this, $form).removeClass('invalidField');
            $this.parent().find('.phoneError').slideUp(250).html('');
        }
        if( !result && !isFocused ) {
            field.focus();
            isFocused = true;
        }

        phoneIsSet = phoneIsSet || !!value;
    });

    // E-mail (необязательно на форме просьбы, обязательно на форме предложения):
    field = $('#email', $form);
    value = $.trim(field.val());
    var emailIsSet = !!value;

    if(field.hasClass('offerEmail')) { // Предложение
       if(value.length <= 0) {
           field.addClass('invalidField');
           $('#emailError', $form).html(LANG_EMAIL_REQUIRED).slideDown(250);
           result = result && false;
       } else if(value && !/^[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?$/i.test(value)) {
            field.addClass('invalidField');
            $('#emailError', $form).html(LANG_EMAIL_INVALID).slideDown(250);
            result = result && false;
        } else {
            field.removeClass('invalidField');
            $('#emailError', $form).slideUp(250).html('');
        }
    } else { // Просьба
        if(value && !/^[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?$/i.test(value)) {
            field.addClass('invalidField');
            $('#emailError', $form).html(LANG_EMAIL_INVALID).slideDown(250);
            result = result && false;
        } else {
            field.removeClass('invalidField');
            $('#emailError', $form).slideUp(250).html('');
        }

        if( !phoneIsSet && !emailIsSet ) {
            $('#contactsError', $form)
                .html(LANG_SOME_CONTACTS_REQUIRED)
                .slideDown(250);
            result = result && false;
            if( !isFocused ) {
                $('#phonesList > li:not(#phonePrototype) > .phone:first', $form).focus();
                isFocused = true;
            }
        } else {
            $('#contactsError', $form).slideUp(250).html('');
        }
    }

    if( !result && !isFocused ) {
        field.focus();
        isFocused = true;
    }

    // Описание проблемы/описание, чем волонтёр желает помочь - т.е. текст сообщения (обязательно):
    field = $('#messageText', $form);
    value = field.htmlarea('toHtmlString');
    if($.trim(value.replace(/<\/?[^>]+>/gi, '').replace('&nbsp;', '')).length <= 0) {
        field.addClass('invalidField');
        $('#messageTextError', $form).html(LANG_TEXT_REQUIRED).slideDown(250);
        result = result && false;
    } else {
        field.removeClass('invalidField');
        $('#messageTextError', $form).slideUp(250).html('');
    }
    if( !result && !isFocused ) {
        field.focus();
        isFocused = true;
    }

    // Координаты точки на карте (обязательно):
    if( !$('#locationLatField', $form).val() || !$('#locationLngField', $form).val() ) {
        $('#addressNotFound').addClass('invalidField');
        result = result && false;
    }
    if( !result && !isFocused ) {
        $('#locationMapSwitch').focus();
        isFocused = true;
    }

    field = $('#categoryListing');
    value = $('div.ez-checkbox.ez-checked', field);
    if(value.length <= 0) {
        field.addClass('invalidField');
        $('#categoryError', $form).html(LANG_CATEGORY_REQUIRED).slideDown(250);
        result = result && false;
    } else {
        field.removeClass('invalidField');
        $('#categoryError', $form).slideUp(250).html('');
    }
    if( !result && !isFocused ) {
        field.focus();
        isFocused = true;
    }

    if( !result )
        $('.width_fixer').animate({height: '740px'});

    return result;
}

/**
 * Создание маркера, привязывающего сообщение к точке на карте.
 * Точка имеет координаты, адрес, регион и т.д.
 *
 * @param lat float Широта точки маркера.
 * @param lng float Долгота точки маркера.
 * @param doGeocoding bool Если передано true, будет выполнено геокодирование адреса по
 * указанным координатам. По умолчанию false.
 */
function createMessageMarker(lat, lng, doGeocoding)
{
    if( !lat || !lng )
        return;
    else {
        lat = parseFloat(lat);
        lng = parseFloat(lng);
    }

    doGeocoding = !!doGeocoding;

    if(marker) {
        marker.setMap(null);
        marker = '';
    }

    // Геолокация региона точки:
    $.getJSON(CONST_REGION_SERVICE_URL+'?lat='+lat+'&lng='+lng+'&callback=?')
     .success(function(resp){
//         console.log(resp);
         if(resp.length == 0)
             $regionIdField.val('-');
         else
             $regionIdField.val(resp[0].id);
//         alert($regionIdField.val());
     })
     .error(function(jqXHR, textStatus){
//         console.log('Error', textStatus);
         $regionIdField.val('-');
     });

    // Установка маркера:
    var markerLatLng = new google.maps.LatLng(lat, lng);
    marker = new google.maps.Marker({
        position: markerLatLng,
        icon: $('input#messageType', $form).val() == 'request' ? '/images/nh_icon.png' :
                                                                 '/images/wh_icon.png',
        map: map,
        draggable: true,
        animation: google.maps.Animation.DROP,
        title: 'Ваше местоположение'
    });

    google.maps.event.addListener(marker, 'dragend', function(){
        var newPosition = this.getPosition();

        $addressField.slideDown(200);
        $('#locationLatField', $form).val(newPosition.lat());
        $('#locationLngField', $form).val(newPosition.lng());

        $('#locationCoords', $mapMessage).html(newPosition.lat()+', '+newPosition.lng());

        if( !$addressField.data('filledByUser') )
            geocode(newPosition.lat().toFixed(2), newPosition.lng().toFixed(2));
    });

    map.panTo(markerLatLng);

    $addressField.slideDown(200);
    $('#locationLatField', $form).val(lat);
    $('#locationLngField', $form).val(lng);

    $('#locationCoords', $mapMessage).html(lat.toFixed(2)+', '+lng.toFixed(2));

    if(doGeocoding)
        geocode(markerLatLng.lat(), markerLatLng.lng());
}

/**
 * Прямая геолокация - получение адреса по координатам.
 *
 * @param lat float Широта точки.
 * @param lng float Долгота точки.
 */
function geocode(lat, lng)
{
    if( !lat || !lng )
        return;

    $ajaxLoading.show();

    // Геолокация OSM:
    //
    // Примечание: ответ возвращается в виде jsonp, при его получении
    // функция geocodeHandleResult будет вызвана автоматически:
//    $.ajax('http://nominatim.openstreetmap.org/reverse',
//           {type: 'get',
//            dataType: 'jsonp',
//            data: {format: 'json', json_callback: 'geocodeHandleResult',
//                   lat: parseFloat(lat), lon: parseFloat(lng)}});

    // Геолокация гугла:
    geocoder.geocode({'latLng': new google.maps.LatLng(parseFloat(lat), parseFloat(lng))},
                     function(results, status){
                         if(status == google.maps.GeocoderStatus.OK) {
                             geocodeHandleResult(results);
                         } else {
                             // @todo Здесь геолокация яндекса. Нужен API-ключ.
                             // Но пока для простоты вывести сообщение о ненайденном адресе:
                             $ajaxLoading.hide();
                             $mapMessageText.html(LANG_ADDR_NOT_FOUND);
//                             console.log('Failure:', status);
                         }
                     });
}

/**
 * Обработка результата геолокации (адреса по координатам) после выбора пользователем
 * своего местоположения на карте.
 * Если геолокационный запрос использует формат jsonp, функция вызывается автоматически.
 *
 * @param resp object JSON-объект с результатом геолокации.
 */
function geocodeHandleResult(resp)
{
    $ajaxLoading.hide();

    if( !resp || resp.length == 0 ) {
        $mapMessageText.html(LANG_ADDR_NOT_FOUND);
        return;
    }

    if(resp[0] && resp[0].formatted_address) { // Обрабатывается результат гугла
        resp = resp[0];
//        createMessageMarker(resp.geometry.location.lat(), resp.geometry.location.lng(), false);
        var addrFoundText = LANG_ADDR_FOUND+': '+resp.formatted_address;
        $mapMessageText.html(addrFoundText);
        $('#addressFound', $addressStatus).html(addrFoundText);
        $('#changeAddress', $addressStatus).show();
        $('#addressNotFound', $addressStatus).hide();
        $addressField.val(resp.formatted_address);
        $mapSaveLocation.show();
    }
//    else if() { // @todo Здесь обрабатывается результат яндекса. Нужен API-ключ.
//
//    }
    else if(resp.display_name && resp.display_name != 'undefined') { // Обрабатывается результат OSM
        // Почему-то иногда OSM-сервис Nominatim дописывает "США"
        // в строку адреса:
        resp = resp.display_name.replace(/, United States of America/i, '');
        var addrFoundText = LANG_ADDR_FOUND+': '+resp;
        $mapMessageText.html(addrFoundText);
        $('#addressFound', $addressStatus).html(addrFoundText);
        $('#changeAddress', $addressStatus).show();
        $('#addressNotFound', $addressStatus).hide();
        $addressField.val(resp);
        $mapSaveLocation.show();
    } else { // Вывести сообщение о ненайденном адресе:
        $mapMessageText.html(LANG_ADDR_NOT_FOUND);
    }
}

/**
 * Обратная геолокация - получение координат по адресу.
 *
 * @param address string Строка адреса.
 */
function geocodeReverse(address)
{
    if( !address || address.length == 0 )
        return;

    // Обратная геолокация гугла:
    geocoder.geocode({'address': $addressField.val()},
                     function(results, status){
        if(status == google.maps.GeocoderStatus.OK) {
            geocodeReverseHandleResult(results);
        } else {
            // @todo Здесь геолокация яндекса. Нужен API-ключ.
            // Но пока для простоты вывести сообщение о ненайденном адресе:
            $ajaxLoading.hide();
            $mapMessageText.html(LANG_ADDR_NOT_FOUND);
//            console.log('Reverse geolocation failure! Reason:', status);
        }
    });
}

/**
 * Обработка результата обратной геолокации (координат по адресу) после использования
 * виджета текстового поиска по карте.
 * Если геолокационный запрос использует формат jsonp, функция вызывается автоматически.
 *
 * @param resp object JSON-объект с результатом обратной геолокации.
 */
function geocodeReverseHandleResult(resp)
{
    $ajaxLoading.hide();

    if( !resp || resp.length == 0 ) {
        $mapMessageText.html(LANG_ADDR_NOT_FOUND);
        return;
    }

//    console.log('Reverse geolocation success:', resp);
    if(resp[0] && resp[0].formatted_address) { // Обрабатывается результат гугла
        resp = resp[0];
        createMessageMarker(resp.geometry.location.lat(), resp.geometry.location.lng(), false);
        var addrFoundText = LANG_ADDR_FOUND+': '+resp.formatted_address;
        $mapMessageText.html(addrFoundText);
        $('#addressFound', $addressStatus).html(addrFoundText);
        $('#changeAddress', $addressStatus).show();
        $('#addressNotFound', $addressStatus).hide();
        $addressField.val(resp.formatted_address);
        $mapSaveLocation.show();
    }
}

/**
 * Получение текстового пояснения к значению полей-слайдеров "кол-во возможной помощи" на
 * форме добавления предложения помощи:
 */
function getAidingTimeLabel(sliderValue)
{
    var label = '';
    switch(sliderValue) {
        case 1:
            label = LANG_AIDING_TIME_SOMETIMES;
            break;
        case 2:
            label = LANG_AIDING_TIME_MONTH;
            break;
        case 3:
            label = LANG_AIDING_TIME_MONTH_MORE;
            break;
        case 4:
            label = LANG_AIDING_TIME_WEEK;
            break;
        case 5:
            label = LANG_AIDING_TIME_WEEK_MORE;
            break;
        case 6:
            label = LANG_AIDING_TIME_WORKDAYS;
            break;
        case 7:
            label = LANG_AIDING_TIME_HOLIDAYS;
            break;
        case 8:
            label = LANG_AIDING_TIME_EVERYDAY;
            break;
        case 9:
            label = LANG_AIDING_TIME_ALLTIME;
            break;
        default:
            label = LANG_AIDING_TIME_SOMETIMES;
            break;
    }
    return label;
}

$(function(){ // Готовность DOM
    /**
     * Начало применения кода для дизайна
     */
	$('input:checkbox', $form).ezMark(); // Применение стилей к чекбоксам

    // Уравниловка колонок:
    $('.column_container').css('height', Math.max($('#nh_1_column_container').height(),
                                                  $('#nh_2_column_container').height()));
    $('.column_container_offer').css('height', Math.max($('#ch_1_column_container').height(),
                                                        $('#ch_2_column_container').height()));

    // Виз.редактор для текста сообщения:
    $('#messageText', $form).htmlarea({
		css: '../css/jHtmlArea.Editor.css',
        toolbar: ['p', '|',
                  {css: 'bold',
                   text: 'Полужирный',
                   action: function(button){
                       this.pasteHTML('<b>'+this.getSelectedHTML()+'</b>');
                   }},
                  {css: 'italic',
                   text: 'Курсив',
                   action: function(button){
                       this.pasteHTML('<i>'+this.getSelectedHTML()+'</i>');
                   }},
                  {css: 'underline',
                   text: 'Подчёркнутый',
                   action: function(button){
                       this.pasteHTML('<u>'+this.getSelectedHTML()+'</u>');
                   }},
                  {css: 'strikethrough',
                   text: 'Зачёркнутый',
                   action: function(button){
                       this.pasteHTML('<strike>'+this.getSelectedHTML()+'</strike>');
                   }}, '|',
                  'orderedlist', 'unorderedlist', '|',
                  'link', 'unlink']
    });

    // Шаблон для вывода списков пост-сабмитных рекомендаций:
    $('#possibleDataTmpl').template('possibleDataTemplate');
	/**
     * Конец применения кода для дизайна
     */

    // Защита от дабл-поста: регистрировать изменение состояния формы
//    $(':input', $form).live('change', function(){
//        curFormData = $form.serialize();
//    });

    // Кнопка "добавить ещё телефон":
    $('#addPhone', $form).click(function(){
        var $this = $(this);
        if($this.data('isOff'))
            return;

        $('#phonePrototype', $form)
            .clone(true)
            .removeAttr('id').removeAttr('style')
            .show()
            .appendTo( $('#phonesList', $form) )
            .find('input')
                .removeAttr('disabled');

        // Скрыть кнопку "добавить телефон", если на форме 3 и больше номеров:
        if($('#phonesList li:visible', $form).length >= 3)
            $this.animate({opacity: 0.25}).css('cursor', 'default').data('isOff', true);
    });
    // Выключить добавление телефона уже при инициализации страницы, если нужно:
    if($('#phonesList li:visible', $form).length >= 3)
        $('#addPhone', $form).css({opacity: 0.25, cursor: 'default'}).data('isOff', true);

    // Кнопки "удалить телефон":
    $('.removePhone', $form).click(function(){
        $(this).parent().slideUp(200, function(){
            $(this).remove();

            // Вывести кнопку "добавить телефон", если на форме менее 3 номеров:
            if($('#phonesList li:visible', $form).length < 3)
                $('#addPhone', $form).animate({opacity: 1.0})
                                     .css('cursor', 'pointer')
                                     .removeData('isOff');
        });
    });

    /**
     * Начало карты для выбора координат
     */
    $('#regionModal', '#auth').bind('regionChanged', function(e, newRegionId){
        if( !marker )
            $('#locationRegion', $searchControl).val(newRegionId).change();
    });

    // Собственно карта:
    $('.locationMapSwitch').click(function(e){
        e.preventDefault();

        if( !$locationMap.data('windowOpened') ) { // Инициализировать карту
            $locationMap.data('windowOpened', true);

            $(this).colorbox({inline: true,
                              href: $locationMap,
                              fixed: true,
    //                          transition: 'fade'
                              overlayClose: false,
                              scrolling: false,
                              onComplete: function(){
                                  if(marker)
                                      map.setCenter(marker.getPosition());
                              },
                              onCleanup: function(){
                                  $locationMap.data('windowOpened', false);
                              }});

            // Виджет вып.списка регионов для быстрого перемещения по ним на карте:
            $('#locationRegion', $searchControl).change(function(){
                var $this = $(this),
                    $optionSelected = $this.find('option:selected');

//                if(mapIsInitialized) {
                    map.setCenter(new google.maps.LatLng($optionSelected.data('center-lat'),
                                                         $optionSelected.data('center-lng')));
                    map.setZoom($optionSelected.data('zoom-level'));
//                }
            });

            // Виджет поисковой строки для поиска координат сообщения:
            $('#locationSearchSubmit', $searchControl).click(function(e){
                e.preventDefault();

                var addressEntered = $.trim($addressField.val());
                if($addressField.data('addressLastEntered') != addressEntered) {
                    geocodeReverse(addressEntered);
                    $addressField.data('addressLastEntered', addressEntered);
                }
            });

            // Див "сохранить положение на карте":
            $mapSaveLocation.click(function(e){
                e.preventDefault();

                $locationMap.data('mapDisplayed', false);
                $.colorbox.close();
            });

            // Местоположение карты по умолчанию:
//            var mapInitZoom = 3,
//                mapInitCenter =;

            // Попытка геолокации текущего местоположения юзера по его IP:
            if(google && google.loader && google.loader.clientLocation) {
//                console.log(google.loader.ClientLocation);
//                mapInitZoom = ;
//                mapInitCenter = ;
            }

            if(map)
                return;
            // Повторно карту не инициализировать

            var mapTypeIds = [];
            for(var type in google.maps.MapTypeId) {
                mapTypeIds.push(google.maps.MapTypeId[type]);
            }
            mapTypeIds.push('OSM');
            map = new google.maps.Map($('#locationMapCanvas', $locationMap).get(0), {
                mapTypeId: 'OSM',
                mapTypeControlOptions: {mapTypeIds: mapTypeIds},
                zoom: 3,
                noClear: true,
                overviewMapControl: false,
                scaleControl: true,
                zoomControl: true,
                zoomControlOptions: {style: google.maps.ZoomControlStyle.LARGE},
                panControl: true,
                rotateControl: false,
                streetViewControl: false,
                minZoom: 3,
                maxZoom: 20,
                center: new google.maps.LatLng(66.4166, 94.25),
                mapTypeId: google.maps.MapTypeId.ROADMAP
            });
            map.mapTypes.set('OSM', new google.maps.ImageMapType({
                getTileUrl: function(coord, zoom) {
                    return 'http://tile.openstreetmap.org/'+zoom+'/'+coord.x+'/'+coord.y+'.png';
                },
                tileSize: new google.maps.Size(256, 256),
                name: 'OpenStreetMap',
                maxZoom: 20
            }));

            google.maps.event.addListener(map, 'tilesloaded', function(){
                google.maps.event.trigger(map, 'resize');
                if( !marker && typeof userRegionCookie() != 'undefined' && !$locationMap.data('mapCentered') ) {
                    $locationMap.data('mapCentered', true);
                    $('#locationRegion', $searchControl).val(userRegionCookie()).change();
                }
            });

            google.maps.event.addListener(map, 'click', function(event){
                createMessageMarker(event.latLng.lat(), event.latLng.lng(), true);
            });

            geocoder = new google.maps.Geocoder(); // Объект для геолокации гугла

//            mapIsInitialized = true;
        }
    });

    // Восстановить поля адреса, если поля координат при загрузке страницы уже заполнены
    // (напр., если координаты были выбраны, но на странице формы нажали "назад", а затем вернулись):
//    if($('#locationLatField', $form).val() && $('#locationLngField', $form).val())
//        createMessageMarker($('#locationLatField', $form).val(), $('#locationLngField', $form).val());
    /**
     * Конец карты для выбора координат
     */

    // Кнопка "найти меня" в виджете карты для выбора координат:
    $('#locationMapLocateClient', '#locationMap').click(function(){
        /**
         * @todo Нужно получить API-ключ для сайта и подключить скрипт google.loader
         */
        /*if(google.loader.ClientLocation) {
            var marker = new google.maps.Marker({
                position: new google.maps.LatLng(google.loader.ClientLocation.latitude,
                                                 google.loader.ClientLocation.longitude),
                map: map,
                title: 'Кажется, Вы здесь!'
            });
        } else {
            $('#locationMapMessage').html('Увы, мы не можем найти Вас :( Пожалуйста, укажите Ваше местоположение сами.');
        }*/

    });

    // Аякс-список категорий:
    $('.categoryGroup', $form).click(function(){
        var $this = $(this);
        var $parent = $this.parent();

        $this.find('.categoryListIcon').toggleClass('collapsed');
        if( !$this.data('isLoaded') ) {
            var catParentId = $this.find('.categoryListIcon').attr('id').split('_').splice(2, 1);
            $('img.subCatsLoading', $parent).show();
            $.ajax('/ajax_views/getCategoryChildren/'+catParentId,
                   {type: 'get',
                    dataType: 'json',
                    cache: true,
                    success: function(resp){
                        $('img.subCatsLoading', $parent).hide();
                        $this
                            .data('isLoaded', true)
                            .data('isDisplayed', true);

                        if(resp && resp.length) {
                            var subCatsHtml = '';
                            for(var i=0; i<resp.length; i++) {
                                subCatsHtml += '<li id="'+resp[i].id+'">\
                                        <label class="label_show alignleft pr10 mt4">\
                                            <input type="checkbox" name="category[]" value="'+
                                            resp[i].id+'" />'+resp[i].name+'\
                                        </label>\
                                    </li>';
                            }

                            subCatsHtml = $(subCatsHtml);
                            $('input[type="checkbox"]', subCatsHtml).ezMark(); // Apply checkbox style

                            $('.catChilds', $parent)
                                .html('')
                                .append(subCatsHtml)
                                .slideDown(200);
                        }
                    }, error: function(){
                        $('img.subCatsLoading', $parent).hide();
            }});
        } else if( !$this.data('isDisplayed') ) {
            $this.data('isDisplayed', true);
            $('.catChilds', $parent).slideDown(200);
        } else {
            $this.data('isDisplayed', false);
            $('.catChilds', $parent).slideUp(200);
        }
    });

    /**
     * Функции загрузки фоток и ссылок на видео.
     *
     * Всё, что ниже, полностью отлажено и работает. Просто в версию 1.0 проекта функция
     * загрузки фоток и видео не входит, так что поля убраны с формы до следующего этапа.
     */

    // Поле для загрузки фоток:
    if($('input[type="file"]#photoField').length) {
        new AjaxUpload('photoField', {
            action: '/ajax_forms/addMessageImageUpload',
            name: 'photo',
            autoSubmit: true,
            responseType: 'json', // Возможные значения: json, text/html (по умолч.)
    //        data: {
    //            someVar : ''
    //        },

            // Fired after the file is selected.
            // Useful when autoSubmit is disabled.
            // You can return false to cancel upload
            // @param fileName basename of uploaded file
            // @param fileExt of that file
            // onChange: function(fileName, fileExt){},

            onSubmit: function(fileName, fileExt){
                var $message = $('#photoMessage', '#photoFieldInfo');
                if(fileExt && /^(jpg|jpeg|png|gif)$/i.test(fileExt)) {
                    $message.stop(true).fadeOut(200);
                    $('#photoLoading').show();
                    return true;
                } else {
                    $message
                        .html('<div class="errorMessage">Вы должны выбрать файл фотки!</div>')
                        .stop(true).fadeIn(200).fadeTo(5000, 1.0).fadeOut(200);
                    return false;
                }
            },

            onComplete: function(userFileName, resp){
                $photoFieldInfo = $('#photoFieldInfo');

                $('#photoLoading').hide();

                if(resp.status && resp.status == 'success') {
                    $('#photoUploadedList', $photoFieldInfo)
                        .append('<span class="clip_attachment">'+userFileName
                               +' <span class="photoDeleteDiv red pointer" id="'
                               +resp.fileData.id+'">Удалить</span>'+'</span><br />');
                    $('#photoUploadedList', $photoFieldInfo)
                        .append('<input type="hidden" name="photoAttached[]" value="'+
                                resp.fileData.id+'">');

                    if(resp.message) {
                        $('#photoMessage', $photoFieldInfo)
                            .html('<div class="lightGreen">'+resp.message+'</div>')
                            .stop(true).fadeIn(200).fadeTo(5000, 1.0).fadeOut(200);
                    }
                } else {
                    if(resp.message) {
                        $('#photoMessage', $photoFieldInfo)
                            .html('<div class="red">'+resp.message+'</div>')
                            .stop(true).fadeIn(200).fadeTo(5000, 1.0).fadeOut(200);
                    } else {
                        $('#photoMessage', $photoFieldInfo)
                            .html('<div class="red">Ошибка при загрузке файла</div>')
                            .stop(true).fadeIn(200).fadeTo(5000, 1.0).fadeOut(200);
                    }
                }
            }

        });

        // Кнопки для удаления фоток из списка уже загруженных:
        $('.photoDeleteDiv', '#photoUploadedList').live('click', function(){
            var $this = $(this);
            $.ajax('/ajax_forms/addMessageImageRemove',
                   {data: {id: $this.attr('id')},
                    type: 'post',
                    async: true,
                    dataType: 'json',
                    cache: false})
             .success(function(resp){
                 // ...
             })
             .error(function(jqXHR, textStatus){
                 // ...
             });

            $this.parent()
                 .slideUp(200, function(){ // Удалить файл из списка загруженных
                     $(this) // Также удалить соответствующее файлу скрытое поле
                         .parents('#photoUploadedList')
                         .find('input[name^="photoAttached"][value="'+$this.attr('id')+'"]')
                             .remove();
                     $(this).remove(); // И саму строку файла в списке, безусловно
                 });
        });
    }

//    // Кнопка "добавить ещё видео":
//    $('#moreVideo', $form).click(function(){
//        $('#videoPrototype', $form)
//            .clone(true)
//            .removeAttr('id').removeAttr('style')
//            .show()
//            .appendTo( $('#videosList', $form) )
//            .find('input')
//                .removeAttr('disabled');
//    });
//
//    // Кнопки "удалить видео":
//    $('.removeVideo', $form).click(function(){
//        $(this).parent().slideUp(200, function(){
//            $(this).remove();
//        });
//    });

    /**
     * Функции загрузки фоток и ссылок на видео - конец.
     */

    // Поля-слайдеры для формы добавления предложения помощи:
    if($.ui) {
        $('#aidingTimesSlider', $form)
            .slider({animate: 'normal', min: 1, max: 9, range: 'min', step: 1,
                     slide: function(event, ui){
            $('#aidingTimesCurrent', $form).html(getAidingTimeLabel(ui.value));
            $('#aidingTimes', $form).val(ui.value);
        }});
        $('#aidingDistanceEmergencySlider', $form).slider({animate: 'normal', min: 0, max: 105,
                                                           range: 'min', step: 5,
                                                           slide: function(event, ui){
            switch(ui.value) {
                case 0:
                    $('#distEmergCurrent', $form).html(LANG_AIDING_DISTANCE_MIN_LABEL);
                    break;
                case 105:
                    $('#distEmergCurrent', $form).html(LANG_AIDING_DISTANCE_MAX_LABEL);
                    break;
                default:
                    $('#distEmergCurrent', $form).html(LANG_AIDING_DISTANCE_LABEL.replace('#DISTANCE#',
                                                                                          ui.value));
            }
            $('input#aidingDistanceEmergency').val(ui.value);
        }});
        $('#aidingDistanceSlider', $form).slider({animate: 'normal', min: 0, max: 105,
                                                  range: 'min', step: 5,
                                                  slide: function(event, ui){
            switch(ui.value) {
                case 0:
                    $('#distCurrent', $form).html(LANG_AIDING_DISTANCE_MIN_LABEL);
                    break;
                case 105:
                    $('#distCurrent', $form).html(LANG_AIDING_DISTANCE_MAX_LABEL);
                    break;
                default:
                    $('#distCurrent', $form).html(LANG_AIDING_DISTANCE_LABEL.replace('#DISTANCE#', ui.value));
            }
            $('input#aidingDistance').val(ui.value);
        }});
    }

    // Отправка формы:
    $form.submit(function(e){
        e.preventDefault();

    //удаление из текста всех тэгов кроме CONST_ALLOWED_TAGS
    var tags = [];
    $.each(JSON.parse(CONST_ALLOWED_TAGS), function(key, val){
      tags[key] = val.substr(1, val.length-2);
    });
    var textareaField = $('#messageText', $form);
    var value = textareaField.htmlarea('toHtmlString');
    var formatted = $.htmlClean(value,{allowedTags : tags});
    $('#messageText').val(formatted);

        if( !validateMessagesAdd($form) ) // Отменить сабмит, если валидация не пройдена
            return;
        // Защита от дабл-поста - отменить сабмит, если форма не изменилась:
//        if(curFormData == initFormData)
//            return;

        var $ajaxLoading = $('img#formResponseLoading', $form);
        var $formSubmit = $('input#addSubmit', $form);

        $ajaxLoading.show();
        $formSubmit.attr('disabled', 'disabled').animate({opacity: 0.25});

        var controllerMethod = $('input#messageType', $form).val() == 'request' ?
                                   'addRequestProcess' :
                                   'addOfferProcess';
        $.ajax('/ajax_forms/'+controllerMethod,
               {data: $form.serialize(),
                type: 'post',
                async: true,
                dataType: 'json',
                cache: false})
            .success(function(resp){
                // Спрятать форму:
                $('#formTotal').slideUp(500, function(){
                    if(resp && resp.status && resp.status == 'success') {
                        // Выезжание сверху панели "Ок", снизу - панели с возможно интересным:
                        $submitMessageBlock.fadeIn(500, function(){
                                $('<div class="successMessageCustom" style="display: none;">'+
                                  LANG_ADD_MESSAGE_SUCCESS+
                                  '</div>').appendTo($submitMessageBlock).fadeIn(500);

                            $('#possibleHelp')
                                .empty()
                                .append( $.tmpl('possibleDataTemplate', resp.possibleHelp) )
                                .slideDown(1000);
                        });
                    } else {
                        $submitMessageBlock.fadeIn(500, function(){
                            if(resp && resp.errors) {
                                $('<div class="errorMessage" style="display: none;">'+
                                  resp.errors+
                                  '</div>').appendTo($submitMessageBlock).fadeIn(500);
                            } else if(resp && resp.message) {
                                $('<div class="errorMessage" style="display: none;">'+
                                  resp.message+
                                  '</div>').appendTo($submitMessageBlock).fadeIn(500);
                            } else {
                                // Неизвестная ошибка на сервере. Параметры запроса занесены в лог.
                                // А нежную психику юзера мы будем беречь:
                                $submitMessageBlock.fadeIn(500, function(){
                                    $('<div class="successMessageCustom" style="display: none;">'+
                                      LANG_ADD_MESSAGE_SUCCESS+
                                      '</div>').appendTo($submitMessageBlock).fadeIn(500);
                                });
                            }
                        });
                    }

                    $formSubmit.removeAttr('disabled').css('opacity', 1.0);
                });

                $ajaxLoading.hide();
            })
            .error(function(jqXHR, textStatus){
                $('#formTotal').slideUp(500, function(){
                    $submitMessageBlock.fadeIn(500, function(){
                        $('<div class="errorMessage" style="display: none;">'+
                          resp.errors+
                          '</div>').appendTo($submitMessageBlock).fadeIn(500);
                    });
                    $formSubmit.removeAttr('disabled').css('opacity', 1.0);
                });
                $ajaxLoading.hide();
            });
    });
});