<?php if( !defined('BASEPATH') ) exit('No direct script access allowed');
/**
 * Файл содержит шаблон представления дружественной страницы с сообщением об ошибке.
 *
 * @copyright  Copyright (c) 2011 Звягинцев Л. aka Ahaenor
 * @link       /application/views/commonError.php
 * @version    0.1
 * @since      Файл доступен начиная с версии проекта 0.9
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/
 */ ?>
 <div class="breadcrumbs mb10 ml10 "><a title="Главная" href="/">Главная</a> &raquo; Произошла ошибка</div>

 <div id="errPage_contentContainer" class="g720 ml110 rounded_all shaddowGrey ">
	 <div class="header_container rounded_top">
		<div id="errorIco">&nbsp;</div>
		<h3 class="mb0 rounded_top regulartext_header"><?php echo $errorTitle;?></h3>
		
	</div>
	<div class="bgblue pa20">
		<div class="ml25 mr25 fs1_2e semiGrey bgwhite pa20 rounded_all">
			<?php echo $errorText;?>
			<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5/jquery.min.js" type="text/javascript"></script>
			<script src="/javascript/commonError.js?v=<?php echo sha1($this->config->item('js_version'));?>" type="text/javascript"></script>
		</div>
	</div>
</div>