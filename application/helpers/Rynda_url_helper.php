<?php if( !defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Расширение хелпера для работы с URL-ами.
 *
 * @copyright  Copyright (c) 2011 Звягинцев Л. aka Ahaenor
 * @link       /application/helpers/ryndaurl_helper.php
 * @version    0.1
 * @since      Файл доступен начиная с версии проекта 0.1
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/
 */

/**
 * Получение URL-а текущей страницы. Переопределение функции из URL-хэлпера CI.
 *
 * @return string Строка URL-а текущей страницы.
 */
function current_url()
{
    return (empty($_SERVER['HTTPS']) ? 'http://' : 'https://').$_SERVER['SERVER_NAME'].'/'.uri_string();
}

/**
 * Получение базового домена системы (на основании настройки base_url в конфигурации).
 *
 * @param $аргумент тип Описание_аргумента
 * @return string Базовый домен системы.
 */
function getBaseDomain()
{
    $ci =& get_instance();
    return reset(array_slice(explode('/', $ci->config->item('base_url')), 2, 1));
}

/**
 * Проверка наличия субдомена в URL-е текущей страницы.
 * 
 * @param $url string URL, субдомен которого требуется получить. Если не указан, будет
 * использоваться URL текущей страницы.
 * @return mixed Если в переданном URL-е есть субдомен 3-го уровня, он будет возвращён.
 * Если переданный URL относится к базовому домену системы, возвращается пустая строка.
 * Если переданный URL относится к поддомену больше чем 3-го уровня, ситуация считается
 * ошибкой и возвращается FALSE.
 */
function getSubdomain($url = NULL)
{
    $url = empty($url) ? current_url() : (string)$url;
    
    // Получить полный домен из текущего URL-а и разделить его на субдомены:
    $currentDomainParts = explode( '.', reset(array_slice(explode('/', $url), 2, 1)) );
    
    if(count($currentDomainParts) == 3) // Субдомен есть, вернуть его
        return $currentDomainParts[0];
    else // Используется базовый URL без субдомена или субдомен больше чем 3-го уровня:
        return count($currentDomainParts) > 3 ? FALSE : '';
}

/**
 * Получение компонентов переданного URL-а.
 *
 * @param $url string Строка URL-а, компоненты которого требуется получить. Если аргумент
 * не передан, используется текущий URL.
 * @param $component integer Компонент URL-а, который требуется извлечь. Должна быть передана
 * одна из констант PHP_URL_*. Если не аргумент не передан, возвращаются все компоненты в виде массива.
 * @return mixed Если передан аргумент $fragment, возвращается значение соответствующего компонента
 * URL-а. Если аргумент $component не передан, возвращается массив компонентов URL-а.
 * Если URL некорректен, возвращается FALSE.
 */
function getUrlComponents($url = NULL, $component = NULL)
{
    if( !$url )
        $url = current_url();
    
    return $component ? parse_url($url, $component) : parse_url($url);
}

/**
 * Получение URL-а с другим поддоменом.
 *
 * @param $newSubdomain string Новый поддомен, которому принадлежит URL.
 * @param $url string URL, в котором требуется изменить поддомен. По умолчанию используется
 * текущий URL.
 * @return string URL, принадлежащий указанному поддомену.
 */
function changeUrlSubdomain($newSubdomain, $url = NULL)
{
    if( !$url )
        $url = current_url();

    $url = getUrlComponents($url);
//    echo '<pre>' . print_r($url, TRUE) . '</pre>';
    $domainParts = explode('.', $url['host']);
    if(count($domainParts) == 3) { // $url принадлежит к поддомену системы (домен 3-го уровня)
        $url = $url['scheme'].'://'.($newSubdomain ? $newSubdomain.'.' : '')
              .$domainParts[1].'.'.$domainParts[2].$url['path'];
    } else { // $url принадлежит к главному домену системы (домен 2-го уровня)
        $url = $url['scheme'].'://'.($newSubdomain ? $newSubdomain.'.' : '').$url['host'].$url['path'];
    }
//    echo '<pre>' . print_r($url, TRUE) . '</pre>';
    return $url;
}