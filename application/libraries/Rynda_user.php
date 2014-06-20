<?php if ( !defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Файл содержит определение класса Rynda_user (обёртка для объекта пользователя, которым
 * оперирует библиотека Ion_auth).
 *
 * @copyright  Copyright (c) 2011 Звягинцев Л. aka Ahaenor
 * @link       /application/libraries/Rynda_user.php
 * @version    0.1
 * @since      Файл доступен начиная с версии проекта 0.3
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/
 */

/**
 * Класс библиотеки.
 */
class Rynda_user
{
    /**
     * @var object Объект CodeIgniter.
     */
    protected $_ci;

    /**
     * Получение строки "обращения" к пользователю сайта по данным его аккаунта.
     *
     * @param $user object Объект учётной записи пользователя. Возвращается функцией $this->ion_auth->user().
     * @return string Возвращается FALSE, если передан пустой или некорректный объект, и строка
     * обращения к пользователю, если объект корректен.
     */
    public function getReference($user)
    {
        if( !$user )
            return FALSE;

        $userReference = '';
        switch((int)$user->ref_type) {
    //        case 1:
    //            $userReference = $user[''];
    //            break;
            case 0: // Обращение - логин-почта
            default: // и оно же по умолчанию
                $userReference = empty($user->email) ? FALSE : $user->email;
        }

        return $userReference;
    }

    /**
     * Получение списка телефонов пользователя сайта в виде массива.
     *
     * @param $user object Объект учётной записи пользователя. Возвращается функцией $this->ion_auth->user().
     * @return array Массив строк телефонов пользователя. Может быть пустым.
     */
    public function getPhones($user)
    {
        if( !$user )
            return FALSE;

        return explode(',', trim($user->phones, '{}'));
    }

    /**
     * Проверка флага приватности контактов пользователя.
     *
     * @param $user object Объект учётной записи пользователя. Возвращается функцией $this->ion_auth->user().
     * @return boolean True, если пользователь желает скрыть свои контакты на сайте, иначе false.
     */
    public function isPrivate($user)
    {
        return $user ? ($user->flags & USER_DATA_IS_PRIVATE) : FALSE;
    }

    /**
     * Проверка флага рассылки уведомлений об ответах на сообщения пользователя.
     *
     * @param $user object Объект учётной записи пользователя. Возвращается функцией $this->ion_auth->user().
     * @return boolean True, если пользователь желает скрыть свои контакты на сайте, иначе false.
     */
    public function isNotifyOnResponses($user)
    {
        return $user ? !($user->flags & USER_RESPONSES_NOTIFY_OFF) : FALSE;
    }
}
