<?php if( !defined('BASEPATH') ) exit('No direct script access allowed');
/**
 * Файл содержит шаблон представления шапки сайта для всех страниц общедоступной части
 *
 * @copyright  Copyright (c) 2011 Звягинцев Л. aka Ahaenor
 * @link       /application/views/commonHeader.php
 * @version    0.1
 * @since      Файл доступен начиная с версии проекта 0.1
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/
 */
?>
<!DOCTYPE HTML>
<html>
    <head>
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.5/jquery.min.js"></script>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <?php if( !empty($keywords) ) {?>
        <meta name="keywords" content="<?php echo is_array($keywords) ? implode(',', $keywords) : trim($keywords);?>" />
        <?php }
        if( !empty($description) ) {?>
        <meta name="description" content="<?php echo trim($description);?>" />
        <?php }?>
        <title>
            <?php echo empty($title) ? $this->config->item('project_basename') :
                                       $title.' | '.$this->config->item('project_basename');?>
        </title>
    <?php
    if( !empty($css) ) {
        if( is_array($css) ) { // Передан массив имён css-файлов
            foreach($css as $styleFileName) {
                if( !empty($styleFileName) ) {?>
        <link href="/css/<?php echo $styleFileName;?>.css?v=<?php echo sha1($this->config->item('css_version'));?>" type="text/css" rel="stylesheet" media="screen" />
    <?php
                }
            }
        } elseif( !empty($css) ) { // Передано имя css-файла
    ?>
        <link href="/css/<?php echo $css;?>.css?v=<?php echo sha1($this->config->item('css_version'));?>" type="text/css" rel="stylesheet" />
    <?php
        }
    } else { // Css-файл по умолчанию
    ?><link href="/css/style.css?v=<?php echo sha1($this->config->item('css_version'));?>" type="text/css" rel="stylesheet" /><?php
    }
    ?>
    <!--[if lte IE 7]>
		<link rel="stylesheet" type="text/css" href="/css/ie7.css" />
	<![endif]-->
    <!--[if lte IE 6]>
		<link rel="stylesheet" type="text/css" href="/css/ie6.css" />
	<![endif]-->
    <link rel="pingback" href="#" />
    <?php $currentSubdomain = getSubdomain();?>
	<link rel="alternate" type="application/rss+xml" title="RSS-лента проекта Rynda.org" href="http://feeds.rynda.org/<?php echo $currentSubdomain ? $currentSubdomain : 'general';?>" />
    <link rel="shortcut icon" type="image/x-icon" href="/images/favicon.ico">
    <link rel="image_src" href="http://rynda.org/images/rynda.png" />
    
    
</head>
<body>

<div class="auth_panel">
	<div class="auth_panel_cont">
    	<div class="mapTabs">
        <ul class="floatleft no_list tabs">
            <?php
              $subdomainsTotal = count($subdomains);
              if($subdomainsTotal > 5) {
                  $subdomainsTabsVisible = array_slice($subdomains, 0, 5);
                  $isVisible = FALSE;
                  for($i=0; $i<count($subdomainsTabsVisible); $i++) {
                      if($currentSubdomain == $subdomains[$i]['url']) {
                          $isVisible = TRUE;
                          break;
                      }
                  }
                  // Cur. subdomain tab will be placed next to main map tab, other tabs
                  // will be moved down the queue:
                  if( !$isVisible ) {
                      // Find the cur. subdomain tab position:
                      for($i=5; $i<count($subdomains); $i++) {
                          if($subdomains[$i]['url'] == $currentSubdomain) {
                              $currentSubdomainData = $subdomains[$i];
                              array_splice($subdomains, $i, 1); // Remove it from old pos.
                              array_splice($subdomains, 0, 1, // Place it next to base domain
                                           array($subdomains[0], $currentSubdomainData));
                          }
                      }
                  }
              }
              
              for($i=0; $i<($subdomainsTotal > 5 ? 5 : $subdomainsTotal); $i++) {
                if($currentSubdomain == $subdomains[$i]['url']) {?>
            <li class="tab_active <?php echo $subdomains[$i]['isCrysis'] ? 'tab_crysis' : '';?>">
                <span><?php echo $subdomains[$i]['title'];?></span>
            </li>
            <?php } else {?>
            <li class="<?php echo $subdomains[$i]['isCrysis'] ? 'tab_crysis' : '';?>"><div align="center" class="tabLink_container">
                <a href="<?php echo 'http://'.($subdomains[$i]['url'] ? $subdomains[$i]['url'].'.' : '')
                                             .getBaseDomain().'/'.(empty($subdomainTabsResetUrl) ? uri_string() : '');?>"
                   title="Перейти на карту помощи «<?php echo $subdomains[$i]['title'];?>»"><div>&nbsp;</div>
                    <span>
					<?php echo $subdomains[$i]['title'];?>
                </span>
				</a>
				</div>
            </li>
            <?php }
            }?>
            <?php if($subdomainsTotal > 5) {?>
            <li class="mapTabs-button-sub">
                <a href="#" class="mapTabs-button-sub-click"></a>
                <ul class="no_list mapTabs-sub">       
                <?php for($i=5; $i<($subdomainsTotal > 14 ? 14 : $subdomainsTotal); $i++) {
                    if($currentSubdomain == $subdomains[$i]['url']) {?>
                    <li class="subdomains_li_active <?php echo $subdomains[$i]['isCrysis'] ? 'li_crysis' : '';?>">
                        <?php echo $subdomains[$i]['title'];?>
                    </li>
                    <?php } else {?>
                    <li class="<?php echo $subdomains[$i]['isCrysis'] ? 'li_crysis' : '';?>"><nobr>
                        <a href="<?php echo 'http://'
                                           .($subdomains[$i]['url'] ? $subdomains[$i]['url'].'.' : '')
                                           .getBaseDomain().'/'.(empty($subdomainTabsResetUrl) ? 
                                               uri_string() : '');?>"
                        title="Перейти на карту помощи «<?php echo $subdomains[$i]['title'];?>»">
                            <?php echo $subdomains[$i]['title'];?>
                        </a>
                    </nobr>
                    </li>
                    <?php }
                    }

                    if($subdomainsTotal > 14) {?>
                    <li><a href="/" title="Все карты помощи в нашей системе">Другие карты помощи</a></li>
                    <?php }?>
                    </ul>
                </li>
                <?php }?>
        </ul>
    </div>
		<?php if( !empty($showAuth) )
            $this->load->view('widgets/auth', array('user' => $user,));
    ?>
    </div>
</div>
<div id="navigation">
    <div class="nav_cont"> 
        <ul class="top_nav_list">
            <li><a href="/vse" title="Все сообщения в системе">Сообщения</a>
            	<ul class="nav_list_children">
                	<li><a href="/vse" title="Все сообщения в системе">Все сообщения</a></li>
                	<li><a href="/pomogite" title="Сообщения с просьбами о помощи">Просьбы о помощи</a></li>
                	<li><a href="/pomogu" title="Сообщения, где предлагается помощь">Предложения помощи</a></li>
                    <li><a href="/pomogite/pomogli" title="Сообщения, по которым помощь нашлась">Помощь нашлась</a></li>
                    <li><a href="/info" title="Информационные сообщения">Информация</a></li>
                </ul>
            </li>
            <!--<li><a href="#" title="Список карт помощи">Карты помощи</a></li>
            <li><a href="#" title="Найти сообщения">Поиск</a></li>-->
			<li><a href="/org" title="Организации">Организации</a>
                <ul class="nav_list_children">
                    <li><a href="/org" title="Все организации в системе">Все организации</a></li>
                <?php foreach($organizationTypes as $orgType) {?>
                    <li><a href="/org/t/<?php echo $orgType['id'];?>" title="Все организации типа «<?php echo $orgType['name'];?>»"><?php echo $orgType['name'];?></a></li>    
                <?php }?>
                </ul>
            </li>
            <li><a href="/info/about" title="О проекте «<?php echo $this->config->item('project_basename');?>»">О проекте</a>
            	<ul class="nav_list_children">
                	<li><a href="/info/about" title="О проекте «<?php echo $this->config->item('project_basename');?>»">О проекте</a></li>
                    <li><a href="/info/team" title="Команда">Команда</a></li>
                    <li><a href="/info/thanks" title="Благодарности">Благодарности</a></li>
                    <li><a href="/info/friends" title="Наши друзья">Наши друзья</a></li>
                    <li><a href="/info/contacts" title="Наши контакты">Свяжитесь с нами</a></li>
                    <li><a href="/info/media" title="СМИ о &laquo;Рынде&raquo;">СМИ о &laquo;Рынде&raquo;</a></li>
                </ul>
            </li>
        </ul>
        <div class="clearfix"></div>
    </div>
</div>
<div class="main">
	<div class="g960" id="header">
		<div class="alignright ml40">	
			<?php if( !empty($showOfferButton) ) {?>
            <div id="want_to_help_button">
                <a href="/pomogu/dobavit" class="no_text" title="Хочу помочь">Хочу помочь</a>
            </div>
            <?php }
            if( !empty($showRequestButton) ) {?> 
            <div id="need_help_button">
                <a href="/pomogite/dobavit" class="no_text" title="Нужна помощь">Нужна помощь</a>
            </div>
            <?php }?>
        </div>
        <h1><a href="/" class="no_text" title="Rynda.org — портал добровольцев">Rynda</a></h1>
        <div class="clearfix"></div>        
    </div>
    <div class="clear1">&nbsp;</div>