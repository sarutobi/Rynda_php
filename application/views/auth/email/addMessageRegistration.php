<?php if( !defined('BASEPATH') ) exit('No direct script access allowed');
/**
 * File contains the email template to notice the users that leaved a request/offer
 * message about their registration in the sistem.
 *
 * @copyright  Copyright (c) 2012 Звягинцев Л. aka Ahaenor
 * @link       /application/views/email/addMessageRegistration.tpl.php
 * @since      0.7
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/
 */?>
<!DOCTYPE html>
<html>
<head>

 </head>
 <body>
 <!--  <style type="text/css">
   body {background-color: #EFF6F8; font-family: Arial, Helvetica, sans-serif; color: #58585A;}
   a {color: #ED5A29; text-decoration: none;}
   h1 {font-size: 24px; text-align: center;}
   h2 {text-align: center;}
   h2 a {font-size: 18px; color: #ED5A29; text-decoration: none;}
   h3 {font-size: 15px; margin: 2px 0; overflow: }
   h3 a {color: #ED5A29; text-decoration: none; font-weight: normal;}
   ul {list-style-type: none; padding-left: 0;}
   .message_details {display: block; float: left; color: #818181; font-size: 10px;}
   .message_details a {color: #7497A2;}
   .message_details_container {padding-left: 30px;}
   #sign_container {margin: 0 0 0 240px; color: #818181; font-size: 10px; font-style: italic;}
   #params_4mailing a, #params_underliner a {color: #7497A2; text-decoration: none;}
   #params_underliner {border-top: solid 1px #C9C9C9; font-size: 12px; margin: 20px 0 0 0; padding: 10px 0 0;}
   ul li h3 {background: url(<?php echo site_url('/css/i/ringer-rynda.jpg');?>) no-repeat 0 -8px; padding-left: 30px;}
   
  </style>
  <font face="Arial">
  <div align="center" style="position: relative; display: block;"> 
  <div style="margin: 50px 0 0">
      <img src="<?php echo site_url('/css/i/logo.png');?>" />
  </div>
  </div>-->
     Приветствуем вас, <?php echo $userFirstName;?>!
     <br /><br />
     Вы оставили сообщение на сайте <a href="<?php echo site_url();?>">«<?php echo $this->config->item('project_basename');?>»</a>, и мы создали для вас <b>профиль пользователя сайта</b>.
     <br />Для того, чтобы <b>активировать ваш профиль</b>, пройдите, пожалуйста, по <a href="<?php echo site_url("auth/activate/$userId/$activation");?>">этой ссылке</a>.

     <br /><br /><b>Временный пароль для вашей учётной записи на сайте</b>: <?php echo $tmpPassword;?>
     <br />После того, как учётная запись будет активирована, вы сможете поменять его на любой другой на странице настроек вашего профиля.

     <br /><br />Если вам не нужна учётная запись на нашем сайте или вы получили это письмо по ошибке, мы приносим свои извинения и просим проигнорировать это письмо. Ваша учётная запись будет автоматически удалена через 3 дня.

     <br /><br />Спасибо, и удачи вам!
     <br />Проект «<?php echo $this->config->item('project_basename');?>»
</body>
</html>