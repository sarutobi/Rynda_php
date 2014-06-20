<?php if( !defined('BASEPATH') ) exit('No direct script access allowed');
/**
 * Файл содержит шаблон представления футера сайта для всех страниц общедоступной части
 *
 * @copyright  Copyright (c) 2011 Звягинцев Л. aka Ahaenor
 * @link       /application/views/commonFooter.php
 * @version    0.1
 * @since      Файл доступен начиная с версии проекта 0.1
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/
 */
?>
		<div id="footer" class="g960">
        	<div class="alignright" id="socnetIcons">
            	<div class="soc_net_label">
                	Мы в социальных сетях:
                </div>
            	<ul class="no_list floatleft">
                	<li><a href="http://vkontakte.ru/club25595604" title="Виртуальная рында во Вконтакте" class="vk">Вконтакте</a></li>
                    <li><a href="http://www.facebook.com/pages/Ryndaorg/163264230398695?created" title="Виртуальная рында в Фейсбуке" class="fb">Фейсбук</a></li>
                    <li><a href="http://community.livejournal.com/rynda_org/" title="Виртуальная рында в ЖЖ" class="lj">Живой Журнал</a></li>
                    <li><a href="http://twitter.com/#!/Ryndaorg" title="Виртуальная рында в Твиттере" class="tw">Твиттер</a></li>
                    <!--<li><a href="#" title="Лента Виртуальной рынды" class="rss">RSS</a></li>-->
                </ul>
            </div>
            <ul id="footer_nav" class="no_list top_nav_list">
                <li class="pl0"><a href="/" title="На главную">На главную</a></li>
                <!--<li><a href="#" title="Список карт помощи">Карты помощи</a></li>-->
                <!--<li><a href="#" title="Найти сообщения">Поиск</a></li>-->
                <li><a href="/info/about" title="О проекте «<?php echo $this->config->item('project_basename')?>»">О проекте</a></li>
                <li><a href="/pomogite/pomogli" title="Сообщения, по которым помощь нашлась">Помощь нашлась</a></li>
                <li><a href="/org" title="Организации">Организации</a></li>
                <li><a class="rss_feed" href="http://feeds.rynda.org/<?php $currentSubdomain = getSubdomain(); echo $currentSubdomain ? $currentSubdomain : '';?>">RSS-лента</a></li>
                <li class="no_bg"><a href="/info/contacts" title="Свяжитесь с нами">Свяжитесь с нами</a></li>
        	</ul>
            <div class="clearfix mb10">&nbsp;</div>
            
           <div class="g960 inside" id="license">© - Платформа «Виртуальная Рында - Атлас помощи», 2011. Лицензия <a href="http://creativecommons.org/licenses/by-nc-sa/3.0/">Creative Commons 3.0 (Атрибуция — Некоммерческое использование — С сохранением условий)</a></div>
        </div>
	</div><!-- // Конец MAIN div'а -->
<script id="geolocateRegionMessageTmpl" type="text/x-jquery-tmpl">
    {{if regionName}}
        Мы думаем, что Ваш регион — ${regionName}. Если это не так, пожалуйста, выберите регион в списке выше.
    {{else}}
        Мы не смогли найти Ваш регион :( Пожалуйста, выберите его в списке выше.
    {{/if}}
</script>
<?php $jsVars = array('LANG_LOGIN_REQUIRED' => $this->lang->line('auth_loginRequired'),
                      'LANG_LOGIN_INVALID' => $this->lang->line('auth_loginInvalid'),
                      'LANG_ACCOUNT_INACTIVE' => $this->lang->line('auth_accountInactive'),
//                      'LANG_ACCOUNT_BANNED' => $this->lang->line('auth_accountBanned'),
                      'LANG_ACCOUNT_UNKNOWN' => $this->lang->line('auth_accountUnknown'),
                      'LANG_PASSWORD_REQUIRED' => $this->lang->line('auth_passwordRequired'),
                      'LANG_PASSWORD_INVALID' => $this->lang->line('auth_passwordInvalid'),
                      'LANG_PASSWORD_SHORT' => $this->lang->line('auth_passwordTooShort'),
                      'LANG_PASSWORD_LONG' => $this->lang->line('auth_passwordTooLong'),
                      'LANG_PASSWORD_CONFIRM_MISMATCH' => $this->lang->line('auth_passwordConfirmMismatch'),
                      'LANG_FIRST_NAME_REQUIRED' => $this->lang->line('auth_firstNameRequired'),
                      'LANG_FIRST_NAME_INVALID' => $this->lang->line('auth_firstNameInvalid'),
                      'LANG_LAST_NAME_REQUIRED' => $this->lang->line('auth_lastNameRequired'),
                      'LANG_LAST_NAME_INVALID' => $this->lang->line('auth_lastNameInvalid'),
                      'LANG_AGREE_REQUIRED' => $this->lang->line('forms_dataProcessAgreeRequired'),
//                      'LANG_LOGIN_REQUIRED' => $this->lang->line('auth_registerEmailRequired'),
//                      'LANG_LOGIN_INVALID' => $this->lang->line('auth_loginInvalid'),
//                      'LANG_' => $this->lang->line('auth_'),
                      'CONST_PASSWORD_MIN_LENGTH' => $this->config->item('min_password_length', 'ion_auth'),
                      'CONST_PASSWORD_MAX_LENGTH' => $this->config->item('max_password_length', 'ion_auth'),);
        $this->load->view('jsVars', array('jsVars' => $jsVars)); ?>
<script type="text/javascript" src="/javascript/common.js"></script>

<?php if($this->config->item('production_site')) {?>
<!-- Google Analytics counter -->
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-511139-42']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>

<!-- Yandex.Metrika counter -->
<script type="text/javascript">
(function (d, w, c) {
    (w[c] = w[c] || []).push(function() {
        try {
            w.yaCounter13421083 = new Ya.Metrika({id:13421083,
                    clickmap:true,
                    accurateTrackBounce:true, webvisor:true});
        } catch(e) {}
    });
    
    var n = d.getElementsByTagName("script")[0],
        s = d.createElement("script"),
        f = function () { n.parentNode.insertBefore(s, n); };
    s.type = "text/javascript";
    s.async = true;
    s.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//mc.yandex.ru/metrika/watch.js";

    if (w.opera == "[object Opera]") {
        d.addEventListener("DOMContentLoaded", f);
    } else { f(); }
})(document, window, "yandex_metrika_callbacks");
</script>

<noscript><div><img src="//mc.yandex.ru/watch/13421083" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->
<?php }?>
<script type="text/javascript">
reformal_wdg_domain    = "rynda";
reformal_wdg_mode    = 0;
reformal_wdg_title   = "Rynda.org - Атлас помощи";
reformal_wdg_ltitle  = "Оставьте свой отзыв";
reformal_wdg_lfont   = "";
reformal_wdg_lsize   = "";
reformal_wdg_color   = "#629ca3";
reformal_wdg_bcolor  = "#516683";
reformal_wdg_tcolor  = "#FFFFFF";
reformal_wdg_align   = "left";
reformal_wdg_charset = "utf-8";
reformal_wdg_waction = 0;
reformal_wdg_vcolor  = "#9FCE54";
reformal_wdg_cmline  = "#E0E0E0";
reformal_wdg_glcolor  = "#105895";
reformal_wdg_tbcolor  = "#FFFFFF";
 
reformal_wdg_bimage = "7688f5685f7701e97daa5497d3d9c745.png";
 
</script>
<script type="text/javascript" language="JavaScript" src="http://reformal.ru/tab6.js"></script><noscript><a href="http://rynda.reformal.ru">Rynda.org - Атлас помощи feedback </a> <a href="http://reformal.ru"><img src="http://reformal.ru/i/logo.gif" /></a></noscript>
</body>
</html>