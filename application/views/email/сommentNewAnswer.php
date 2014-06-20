<?php if( !defined('BASEPATH') ) exit('No direct script access allowed');?>

<!DOCTYPE html>
<html>
<head>

 </head>
 <body>
    <div>
      <font face="Arial">
        <div align="center" style="position:relative;display:block"> 
        <div style="margin:50px 0 0">
          <img src="http://dev-rynda.ru/css/i/logo.png">
        </div>
        </div>
        <div align="center" style="display:block;margin:0 0 30px 0"> 
          <div style="width:500px;text-align:left;padding:40px;background:#ffffff;position:relative;top:30px;border-radius:10px;border:solid 1px #cfe5e9">
            <font color="#818181">
              <h1 style="font-size:24px;text-align:center"><?php echo $subscriber->name ?>, появился новый комментарий!</h1>
              <br>
              Это письмо отправлено автоматической системой портала <a style="color:#ed5a29;text-decoration:none" href="http://dev-rynda.ru/" target="_blank">«Виртуальная рында: Атлас помощи в чрезвычайных ситуациях»</a>, поэтому не требует ответа.
              <br>
              <h2 style="text-align:center;font-size:18px;text-decoration:none">
                У на сайте появились новые комментарии к записи:
              </h2>
              <div style="margin:0 0 0 40px">
                <br>
                <div>
                  <div style="float:left;display:block;padding-left:0">
                    <div style="display:block;float:left">
                      <h3 style="font-size:17px;margin:2px 0;background:url('http://dev-rynda.ru/css/i/ringer-rynda.jpg') no-repeat 0 -8px;padding-left:30px">
                        <a style="color:#ed5a29;text-decoration:none;font-weight:normal" href="/info/s/<?php echo $message['id']?>" title="Страница сообщения"><?php echo $message['title']?></a>
                      </h3>
                      <div style="font-size:15px;font-weight:normal">
                          <?php echo formatTextTrimmed(strip_tags($parentComment['text']))?>
                      </div>
                      <div>
                        <div style="display:block;float:left"><font size="1" color="#818181">Дата: <?php echo date($this->config->item('comments_date_format'), $parentComment['dateAdded'])?></font></div>
                        <div style="display:block;float:left"><font size="1" color="#818181"> | Автор: <?php echo $parentComment['userName']?> </font></div>
                      </div>
                      <br>
                    </div>
                    <div style="clear:both"></div>
                  </div>
                  <div style="clear:both"></div>
                </div>
                <br>
              </div>

              <div style="margin:0 0 0 40px">
                <h3 style="font-size:17px;font-weight:normal;margin:2px 0">
                  <a style="color:#ed5a29;text-decoration:none;font-weight:normal"  title="Профиль пользователя"><?php echo $comment['userName']?></a> ответил на это сообщение:
                </h3>
                <div style="font-size:15px;font-weight:normal">
                    <?php echo formatTextTrimmed(strip_tags($comment['text']), 300) ?>
                </div>
                <div style="margin-left:280px">
                  <a style="color:#ed5a29;text-decoration:none;font-weight:normal;font-size:10px" href="/info/s/<?php echo $message['id'].'?expand=1#comment'.$comment['id']?>" title="Ссылка на комментарий">Прочитать комментарий целиком...</a>
                </div>
              </div>
              <div style="border-top:solid 1px #c9c9c9;font-size:12px;margin:20px 0 0 0;padding:10px 0 0">
                <font color="#818181">
                  Если вы получаете эти сообщения по ошибке или хотите не хотите получать извещения о новых комментариях к этой записи по иным причинам, пожалуйста, <a style="color:#ed5a29;text-decoration:none" href="<?php echo $this->config->item('base_url')?>subscriber/unsubscribeProcess?subscriberId=<?php echo $subscriber->id ?>&token=<?php echo $subscriber->token ?>">перейдите по этой ссылке</a>.
                  (отписка от ответов заданием не предусмотрена)
                </font>
                <div style="margin:20px 0 0 240px;color:#818181;font-size:10px;font-style:italic">
                  Администрация портала <a style="color:#ed5a29;text-decoration:none" href="http://dev-rynda.ru/" target="_blank">«Виртуальная рында: Атлас помощи в чрезвычайных ситуациях»</a>
                </div>

              </div>
            </font>
            <br>
            <br>


          </div>
          <br>
        </div>
      </font>
    </div>
 </body>
