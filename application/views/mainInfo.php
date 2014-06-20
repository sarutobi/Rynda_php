<?php if( !defined('BASEPATH') ) exit('No direct script access allowed');
/**
 * Файл содержит шаблон представления для информационной страницы системы.
 *
 * @copyright  Copyright (c) 2011 Звягинцев Л. aka Ahaenor
 * @link       /application/views/mainInfo.php
 * @version    0.1
 * @since      Файл доступен начиная с версии проекта 0.1
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/
 */ ?>
<div class="g960 regularpage rounded_all mb35" id="content">
    <div class="ml160 g640">
    	<div class="breadcrumbs mb10">
        	<a title="Главная" href="/">Главная</a> &raquo; <?php echo $page['title'];?>
    	</div>
        <h2 class="h24 allcaps mb10"><?php echo $page['title'];?></h2>
		<?php echo nl2br($page['text']);?>
	</div>
</div>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5/jquery.min.js" type="text/javascript"></script>
<script src="/javascript/mainInfo.js?v=<?php echo sha1($this->config->item('js_version'));?>" type="text/javascript"></script>