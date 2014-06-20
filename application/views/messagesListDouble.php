<?php if( !defined('BASEPATH') ) exit('No direct script access allowed');
/**
 * Файл содержит шаблон представления для страницы списка сообщений в виде двух панелей
 * (a la TotalCommander).
 *
 * @copyright  Copyright (c) 2011 Звягинцев Л. aka Ahaenor
 * @link       /application/views/messagesListDouble.php
 * @version    0.1
 * @since      Файл доступен начиная с версии проекта 0.1
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/
 */

$this->load->view('widgets/auth', array('user' => $user,));?>
<div class="g960" id="content">
	<div class="breadcrumbs mb10">
        <a title="Главная" href="/">Главная</a> &raquo;
        <a href="/vse" title="Сообщения">Сообщения</a>
    </div>
    <div class="column mainPageColumn mr25 mt25">
    	<h3 class="mb0 helprequests no_text rounded_top">Просьбы о помощи</h3>
        <div class="column_container" id="mp1">
        	<div class="breadcrumbs mb10">
                <a title="За сегодня" href="#">За сегодня</a> |
                <a href="#" title="За 3 дня">За 3 дня</a> |
                <a href="#" title="За неделю">За неделю</a> |
                <a href="#" title="За все время">За все время</a>
    		</div>	
            <ul class="no_list datelist grayDivider">
<?php foreach((array)$requests as $message) {
    $title = "Просьба о помощи №{$message['id']}".(empty($message['title']) ? '' : ": {$message['title']}");
?>
                <li>
                    <a href="/pomogite/s/<?php echo $message['id'];?>" title="<?php echo $title;?>" class="orange">#<?php echo $message['id'];?></a>
                    <strong><a href="/pomogite/s/<?php echo $message['id'];?>" title="<?php echo $title;?>"><?php echo $message['title'];?></a></strong>
                    <br /><br />

                    <?php echo nl2br(formatTextTrimmed($message['text'], 50));?>
                    <br />
                    <a href="/pomogite/s/<?php echo $message['id'];?>" title="<?php echo $title;?>">Читать далее</a>
                    <br />
                    <span class="mp_date_color fs10">
<?php echo date('d ', $message['dateAdded']).
           $this->lang->line( 'cal_'.strtolower(date('M', $message['dateAdded'])) ).
           date(' Y г., H:i', $message['dateAdded']);
?>
                   
                        <?php if(count($message['categories']) > 0) {?>
                        | <?php $this->load->view('widgets/categoryList',
                                                  array('categories' => $message['categories']));
                        }?> </span>
                </li>
<?php }?>
            </ul>
            <div class="clearfix"></div>
       </div>    
    </div>
    <div class="column mainPageColumn mt25">
    	<h3 class="mb0 helpoffers no_text rounded_top">Предложения помочь</h3>
        <div class="column_container" id="mp2">
        	<div class="breadcrumbs mb10">
                <a title="За сегодня" href="#">За сегодня</a> |
                <a href="#" title="За 3 дня">За 3 дня</a> |
                <a href="#" title="За неделю">За неделю</a> |
                <a href="#" title="За все время">За все время</a>
    		</div>		
          	<ul class="no_list datelist grayDivider">
<?php foreach((array)$offers as $message) {
    $title = "Предложение помощи №{$message['id']}".(empty($message['title']) ? '' : ": {$message['title']}");
?>
                <li>
                    <a href="/pomogu/s/<?php echo $message['id'];?>" title="<?php echo $title;?>" class="lightGreen">#<?php echo $message['id'];?></a>
                    <strong><a href="/pomogu/s/<?php echo $message['id'];?>" title="<?php echo $title;?>"><?php echo $title;?></a></strong><br /><br />

                    <?php echo nl2br(formatTextTrimmed($message['text'], 50));?>
                    <br />
                    <a href="/pomogu/s/<?php echo $message['id'];?>" title="<?php echo $title;?>">Читать далее</a>
                    <br />
                    <span class="mp_date_color fs10">
<?php echo date('d ', $message['dateAdded']).
           $this->lang->line( 'cal_'.strtolower(date('M', $message['dateAdded'])) ).
           date(' Y г., H:i', $message['dateAdded']);
?>
                   <?php if(count($message['categories']) > 0) {?>
                        | <?php $this->load->view('widgets/categoryList',
                                                  array('categories' => $message['categories']));
                        }?> </span>
                </li>
<?php }?>
          	</ul>
            <div class="clearfix"></div>
       </div>     
    </div>
    <div class="clearfix "></div>
</div>


<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5/jquery.min.js" type="text/javascript"></script>
<script src="http://maps.google.com/maps/api/js?sensor=false" type="text/javascript"></script>

<script src="/javascript/mainIndex.js" type="text/javascript"></script>