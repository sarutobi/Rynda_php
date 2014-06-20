<?php if( !defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Функции-хелперы для форматирования контента при отображении.
 *
 * @copyright  Copyright (c) 2011 Звягинцев Л. aka Ahaenor
 * @link       /application/helpers/views_helper.php
 * @version    0.1
 * @since      Файл доступен начиная с версии проекта 0.1
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/
 */

/**
 * Отладочная функция для вывода значения переменной.
 *
 * @param mixed $arg Переменная, которую требуется вывести.
 * @param boolean $onFlag Если равно true, вывод будет выполнен только при наличии
 * GET-параметра dbg=1; иначе, вывод будет произведён в любом случае. По умолчанию false.
 * @return NULL
 */
if( !function_exists('show') ) {
    function show($arg, $onFlag = FALSE)
    {
        @ob_end_flush();
        @ob_flush();
        @flush();
        @ob_start();

        if( !$onFlag || ($onFlag && !empty($_GET['dbg'])) )
            echo '<pre>'.print_r($arg, TRUE).'</pre>';
        return;
    }
}

/**
 * Форматирование телефонного номера для вывода в интерфейс.
 *
 * @param $phoneStr string Телефонный номер в виде строки чисел.
 * @return string Телефонный номер, отформатированный для восприятия человеком.
 */
if( !function_exists('formatPhone') ) {
    function formatPhone($phoneStr)
    {
        /**
         * @todo Возможности:
         * 1. сделать универсальный вариант форматирования на основе http://habrahabr.ru/blogs/php/102352/
         * 1.1. Поместить код в библиотеку, а не в хелпер. Класс+статич.переменная был бы кошернее.
         * 2. Сделать более простой вариант - в конфиги добавить константу "формат тел.номера",
         * в функции парсить номер по ней. Недостатки: надо будет допиливать валидацию тел.номеров
         * на всех формах под этот формат (автоматически?). Но это сильно на будущее.
         */

        return trim($phoneStr, '"');
    }
}

/**
 * Форматирование текстовой строки с учётом максимально допустимой длины. Если она
 * превышена, добавляется троеточие.
 *
 * @param $string string Строка текста для форматирования.
 * @param $maxLength integer Максмально допустимая длина текста (в символах). Если она
 * превышена, текст укорачивается до неё и к нему приписывается троеточие. По умолчанию 30.
 * @param $trimByWords boolean Если передано true, строка будет укорачиваться с точностью
 * до 1 слова, иначе - с точностью до 1 символа. По умолчанию true.
 * @return string Отформатированная строка.
 */
if( !function_exists('formatTextTrimmed') ) {
    function formatTextTrimmed($string, $maxLength = 30, $trimByWords = TRUE)
    {
        $string = trim($string);
        if( !$string )
            return;
        $maxLength = (int)$maxLength > 0 ? (int)$maxLength : 30;
        $trimByWords = (bool)$trimByWords;

        $stringRes = '';
        if(mb_strlen($string) <= $maxLength)
            $stringRes = $string;
        else if($trimByWords) {
            foreach(explode(' ', $string) as $word) {
                if(mb_strlen("$stringRes $word") > $maxLength)
                    break;
                $stringRes .= " $word";
            }
            $stringRes .= '…';
        } else
            $stringRes = mb_substr($string, 0, $maxLength).'…';

        return $stringRes;
    }
}

/**
 * Приведение первой буквы строки (в UTF-8) к верхнему регистру.
 * 
 * @param $str string Строка для обработки.
 * @return string Строка с первой буквой, приведённой к верхнему регистру.
 */
if( !function_exists('mb_ucfirst') ) {
    function mb_ucfirst($str)
    {
        return mb_strtoupper(mb_substr($str, 0, 1)).mb_substr($str, 1);
    }
}

/**
 * Построение HTML-текста списка категорий на основании массива этих категорий.
 *
 * @param $categories array Массив категорий. Каждый элемент массива должен содержать поля:
 * * id integer Id категории.
 * * name string Название категории.
 * * slug string Короткое название категории.
 * @param $options array Массив параметров строки списка категорий. Может содержать поля:
 * * delimiter string Разделитель между категориями в списке. По умолчанию используется ", ".
 * @return string Строка списка категорий, содержащихся в массиве.
 */
if( !function_exists('formatCategoryList') ) {
    function formatCategoryList($categories = array(), $options = array())
    {
        $options = $options + array('delimiter' => ', ',);
        $catLinks = array();
        foreach($categories as $category) {
            $catLinks[] = '<a href="/info/c/'.$category['id'].'" title="Все сообщения категории «'.$category['name'].'»">'.$category['name'].'</a>';
        }

        return implode($options['delimiter'], $catLinks);
    }
}

/**
 * Форматирование списка дней недели для вывода в интерфейс.
 *
 * @param $days mixed Номер дня недели либо массив таких номеров.
 * @param $options array Массив параметров. Может содержать поля:
 * * isUcFirst boolean Приводить ли к верхнему регистру названия дней. По умолчанию false.
 * * delimiter string Разделитель между названиями дней в списке. Используется только
 * в случае, когда $days - массив. По умолчанию используется ", ".
 * @return string Если передан номер дня недели, возвращается строка его названия.
 * Если передан массив номеров дней, возвращается отформатированная строка их списка.
 */
if( !function_exists('formatWeekDays') ) {
    function formatWeekDays($days, array $options = array())
    {
        $options = $options + array('isUcFirst' => FALSE, 'delimiter' => ', ',);
        $str = '';
        if((int)$days === $days) {
            switch((int)$days) {
                case 1:
                    $str = 'понедельник';
                    break;
                case 2:
                    $str = 'вторник';
                    break;
                case 3:
                    $str = 'среда';
                    break;
                case 4:
                    $str = 'четверг';
                    break;
                case 5:
                    $str = 'пятница';
                    break;
                case 6:
                    $str = 'суббота';
                    break;
                case 7:
                    $str = 'воскресенье';
                    break;
                case 0:
                default:
                    $str = 'время нужно согласовывать со мной';
            }
            $str = $options['isUcFirst'] ? mb_ucfirst($str) : $str;
        } else if((array)$days) {
            array_walk($days, function(&$value, $key, $isUcFirst){
                switch((int)$value) {
                    case 1:
                        $value = 'понедельник';
                        break;
                    case 2:
                        $value = 'вторник';
                        break;
                    case 3:
                        $value = 'среда';
                        break;
                    case 4:
                        $value = 'четверг';
                        break;
                    case 5:
                        $value = 'пятница';
                        break;
                    case 6:
                        $value = 'суббота';
                        break;
                    case 7:
                        $value = 'воскресенье';
                        break;
                    case 0:
                    default:
                        $value = 'время нужно согласовывать со мной';
                }
                $value = $isUcFirst ? mb_ucfirst($value) : $value;
            }, $options['isUcFirst']);
            $str = implode($options['delimiter'], $days);
        }

        return $str;
    }
}

/**
 * Форматирование расстояния, на котором пользователь готов оказывать помощь.
 *
 * @param $distance integer Расстояние оказания помощи, в км или в метрах.
 * @param $isKm boolean TRUE, если передано расстояние в километрах, иначе FALSE.
 * По умолчанию FALSE.
 * @return string Форматированное расстояние оказания помощи.
 */
if( !function_exists('formatAidingDistance') ) {
    function formatAidingDistance($distance, $isKm = FALSE)
    {
        $distance = $isKm ? (int)$distance : (int)$distance/1000;
        $ci =& get_instance();
        $ci->lang->load('rynda_forms');
        
        switch($distance) {
            case 0:
                return $ci->lang->line('forms_aidingDistMinLabel');
            case 105:
                return $ci->lang->line('forms_aidingDistMaxLabel');
            default:
                return str_replace('#DISTANCE#', $distance, $ci->lang->line('forms_aidingDistLabel'));
        }
    }
}