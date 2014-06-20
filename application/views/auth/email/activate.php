Приветствуем вас, <?php echo $userFirstName;?>!
<br /><br />
Вы получили это письмо, потому что на сайте <a href="<?php echo site_url();?>">«<?php echo $this->config->item('project_basename');?>»</a> была зарегистрирована учётная запись на этот e-mail.
<br /><br />
Чтобы активировать эту учётную запись, пожалуйста, <b>нажмите эту ссылку:</b> <?php echo site_url("auth/activate/$userId/$activation");?>.
<br /><br />
Если вы не регистрировались на нашем сайте, просим вас проигнорировать это письмо.
<br /><br />
Спасибо, и удачи вам!<br />
Проект «<?php echo $this->config->item('project_basename');?>»