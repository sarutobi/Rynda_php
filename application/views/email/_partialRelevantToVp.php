<br />
<div>
    <h2 style="text-align: center;"><a style="font-size: 18px; color: #ED5A29; text-decoration: none;" href="<?php echo site_url('/user/edit/'.$user->id);?>"><?php echo $profileTitle;?></a></h2>
    <div style="float: left; display: block; padding-left: 0;">
    <?php
        foreach($messages as $message) {
            $messageTitle = '';
            switch($message['message_typeslug']) {
                case 'request':
                    $messageTitle .= 'Просьба о помощи';
                    break;
                case 'offer':
                    $messageTitle .= 'Предложение помощи';
                    break;
                case 'info':
                    $messageTitle .= 'Информационное сообщение';
                    break;
                default:
            }
            $messageTitle .= " №{$message['message_id']}".($message['message_title'] ? ": {$message['message_title']}" : "");
    ?>
        <div style="display: block; float: left;">
            <h3 style="font-size: 15px; margin: 2px 0; background: url(<?php echo site_url('/css/i/ringer-rynda.jpg');?>) no-repeat 0 -8px; padding-left: 30px;">
                <a style="color: #ED5A29; text-decoration: none; font-weight: normal;" href="<?php echo site_url('/info/m/'.$message['message_id']);?>" title="Страница сообщения"><?php echo $messageTitle;?></a>
            </h3>
          <div style="padding-left: 30px;">
              <div style="display: block; float: left;"><font size="1" color="#818181">Дата: <?php echo date('d.m.Y, H:i', strtotime($message['message_date']));?></font></div>
              <div style="display: block; float: left;"><font size="1" color="#818181">&nbsp;|&nbsp;Автор: 
          <?php echo $message['is_public'] ?
              ($message['sender_last_name'] ? $message['sender_last_name'].' ' : '').$message['sender_first_name'] :
              ($message['sender_last_name'] ? mb_strtoupper(mb_substr($message['sender_last_name'], 0, 1)).'.'.' ' : '').$message['sender_first_name'];?>
              </font></div>
			  <div style="display: block; float: left;"><font size="1" color="#818181">&nbsp;|&nbsp;<?php echo $message['distanceToVp'];?> км от координат в «<a style="color: #ED5A29; text-decoration: none;" href="<?php echo site_url('/user/edit/'.$user->id);?>"><?php echo $profileTitle;?></a>»</font></div> 
           </div>
          <br />
        </div>
		<div style="clear:both"></div>
    <?php }?>
    </div>
	<div style="clear:both"></div>
</div>
<br />