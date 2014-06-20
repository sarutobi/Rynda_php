<?php
/**
 * Файл содержит шаблон представления специального js-блока для подключения переменных.
 * Они могут использоваться для передачи любых данных из контроллера в js-скрипт
 * представления страницы. Напр., для локализации.
 *
 * @copyright  Copyright (c) 2011 Звягинцев Л. aka Ahaenor
 * @link       /application/views/jsVars.php
 * @version    0.1
 * @since      Файл доступен начиная с версии проекта 0.1
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/
 */
if( !empty($jsVars) ) { ?>
<script type="text/javascript">
<?php
        foreach($jsVars as $varName => $value) {
?>
    var <?php echo $varName;?> = <?php echo is_int($value) || is_float($value) ? $value : "'$value'";?>;
<?php
        }
?>
</script>
<?php }
