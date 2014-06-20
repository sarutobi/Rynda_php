Приветствуем вас, <?php echo $userFirstName;?>!
<br /><br />
Вы получили это письмо, потому что на сайте <a href="<?php echo $this->config->item('base_url');?>"><?php echo getBaseDomain();?></a> <b>было подтвеждено восстановление пароля</b> для этого e-mail.
<br /><br />
<b>Новый пароль для вашей учётной записи:</b> <?php echo $newPassword;?>
<br /><br />
Если вы не регистрировались на сайте <?php echo getBaseDomain();?>, просим вас проигнорировать это письмо.
<br /><br />
Удачи вам!<br />
Проект «<?php echo $this->config->item('project_basename');?>»