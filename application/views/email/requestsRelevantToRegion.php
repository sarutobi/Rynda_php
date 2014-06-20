<?php if( !defined('BASEPATH') ) exit('No direct script access allowed');
/**
 * Файл содержит шаблон для писем пользователям системы, содержащих просьбы о помощи,
 * подходящие к профилям волонтёрства пользователей.
 *
 * @copyright  Copyright (c) 2011 Звягинцев Л. aka Ahaenor
 * @link       /application/views/email/requestsRelevantToVp.php
 * @version    0.1
 * @since      Файл доступен начиная с версии проекта 0.7
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
   
  </style>-->
  <font face="Arial">
  <div align="center" style="position: relative; display: block;"> 
  <div style="margin: 50px 0 0">
      <img src="<?php echo site_url('/css/i/logo.png');?>" />
  </div>
  </div>
<div align="center" style="display: block; margin: 0 0 30px 0;"> 
<div style="width: 500px; text-align: left; padding: 40px; background: #FFFFFF; position: relative; top: 30px; -moz-border-radius: 10px; border-radius: 10px; border: solid 1px #CFE5E9">

<font color="#818181"><h1 style="font-size: 24px; text-align: center;"><?php echo $userFirstName;?>, вы сможете помочь!</h1>
<br />
<font color="#818181">У на сайте появились сообщения с просьбами о помощи, которую вы, возможно, сможете оказать:</font>
<br />
	<div style="margin: 0 0 0 40px;">
    <?php foreach($regionsData as $regionData) {
        if($regionData['messages']) {?>
        <h1><?php echo $regionData['name'];?></h1>
        <ul>
        <?php foreach($regionData['messages'] as $message) {?>
            <li>
                <h3><a href="<?php echo site_url('/info/s/'.$message['id']);?>">
                <?php echo $message['title'];?></a>
                </h3>
                Добавлено: <?php echo date('d.m.Y, H:i', $message['dateAdded']);?>
                <br />
                <br />
                <div>
                <?php echo formatTextTrimmed($message['text'], 100);?>
                </div>
                <a href="<?php echo site_url('/info/s/'.$message['id']);?>">Страница сообщения >></a>
                <hr />
            </li>
        <?php }?>
        </ul>
        <?php }?>
    <?php }?>
	</div>
	
    <div style= "margin: 20px 0 0 240px; color: #818181; font-size: 10px; font-style: italic;">
    Администрация портала <a style="color: #ED5A29; text-decoration: none;" href="<?php echo site_url();?>">«<?php echo $this->config->item('project_fullname');?>»</a><br />
    </div>
</font>
<br />
<br />


</div>
</div>
</body>
</html>