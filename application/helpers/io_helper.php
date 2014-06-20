<?php if( !defined('BASEPATH') ) exit('No direct script access allowed');
/**
 * Хэлпер для работы с данными приходящими от пользователя
 * администрирования наполнения
 * 
 * @version 1.1
 * @author Ibragimov "MpaK" Renat <info@mrak7.com>
 */

/**
 * Формирование JSON-ответа для клиента.
 * 
 * @param $status string Строка статуса операции на сервере. По умолчанию "success".
 * @param $message string Текст сообщения о результате операции на сервере.
 * @param $customData array Ассоциативный массив доп.данных, входящих в результат.
 * @param $type integer Флаги для php-функции json_encode().
 */
function jsonResponse($status = 'success', $message = '', array $customData = NULL, $type = FALSE)
{
    die(json_encode(array_merge((array)$customData, array('status' => $status, 'message' => $message)),
                    $type ? $type : JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP));
}

/**
 * Получение сессионной куки. Обёртка для работы с параметрами, отвечающими за имя этой куки.
 */
function getSessionCookie()
{
    $ci =& get_instance();
    return $ci->input->cookie($ci->config->item('cookie_prefix')
                             .$ci->config->item('sess_cookie_name'));
}