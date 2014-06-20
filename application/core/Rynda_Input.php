<?php if( !defined('BASEPATH') ) exit('No direct script access allowed');
/**
 * Файл содержит определение класса RyndaInput (класс ядра CI)
 *
 * @copyright  Copyright (c) 2011 Звягинцев Л. aka Ahaenor
 * @link       /application/core/RyndaInput.php
 * @version    0.1
 * @since      Файл доступен начиная с версии проекта 0.1
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/
 */

/**
 * Класс обработки данных форм и сверхглобальных переменных, изменяющий поведение
 * стандартного для проекта Rynda.org.
 *
 * Причина создания класса: баги в CI 2.0. В частности:
 * 1. Если фреймворк работает на Windows, во всех значениях полей форм типа textarea
 * дублируются символы "\n" при отправке на сервер.
 * 2. Создание кук с символами "#" в ключе приводит к аварийному заверешению работы сайта
 * и ошибке "Disallowed key characters".
 *
 * Перечисленные баги могут быть исправлены в CI > 2.0.0.
 * В таком случае, этот файл можно просто удалить.
 */
class Rynda_Input extends CI_Input
{
    /**
     * Исправление бага №1
     */
    public function __construct()
    {
        if(PHP_EOL == "\r\n") {
            $this->_standardize_newlines = FALSE;
        }

        parent::__construct();
    }

    /**
     * Исправление бага №2
     */
    public function _clean_input_keys($str)
    {
//      if( !preg_match('/^[a-z0-9:#_\/@:-><]+$/i', $str))
//          exit('<h3>Всем привееет! >^^<</h3>
//                  Это Ваша любимая страница ошибки "Disallowed key characters".<br /><br />
//                  Мы, т.е. персонал проекта, сейчас заняты тем, что постепенно ищем и исправляем то, что вызывает
//                  эту ошибку. Мы очень надеемся на Ваше понимание.<br /><br />
//                  Если Вы видите эту страницу, то <s>меня уже нет в живых</s>просьба как можно скорее
//                  написать нам в <a href="mailto:support@rynda.org">техподдержку проекта</a> и указать в письме
//                  следующее: <pre>'.$str.'</pre><br /><br />
//                  <h6>Заранее большое спасибо за помощь, и доброго Вам дня @}->--</h6>');

        if(UTF8_ENABLED === TRUE)
            $str = $this->uni->clean_string($str);

        return $str;
    }
}
