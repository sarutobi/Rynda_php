Приветствуем вас, <?php echo $userFirstName?>!
<br /><br />
Вы получили это письмо, потому что на сайте <a href="<?php echo $this->config->item('base_url');?>"><?php echo getBaseDomain();?></a> <b>было запрошено восстановление пароля</b> для этого e-mail.
<br /><br />
Чтобы восстановить пароль для этой учётной записи, пожалуйста, пройдите <b>по этой ссылке:</b> <?php echo site_url("auth/reset/$forgottenPasswordCode");?>
<br /><br />
Если вы не регистрировались на сайте <?php echo getBaseDomain();?>, просим вас проигнорировать это письмо.
<br /><br />
Удачи вам!<br />
Проект «<?php echo $this->config->item('project_basename');?>»