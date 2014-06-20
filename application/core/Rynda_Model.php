<?php if( !defined('BASEPATH') ) exit('No direct script access allowed');
/**
 * Файл содержит определение класса RyndaModel (модель данных)
 *
 * @copyright  Copyright (c) 2011 Звягинцев Л. aka Ahaenor
 * @link       /application/core/RyndaModel.php
 * @version    0.1
 * @since      Файл доступен начиная с версии проекта 0.1
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/
 */

/**
 * Базовый класс модели, расширяющий поведение стандартного для проекта Rynda.org.
 */
class Rynda_Model extends CI_Model
{
    public function  __construct()
    {
        parent::__construct();
        $this->lang->load('rynda_models'); // Тексты сообщений об ошибках при работе с БД
    }

    /**
     * @var array Список ошибок при последней операции работы с БД
     */
    protected $_lastErrors = array();

    /**
     * @var array Список возможных кодов ошибок и соответствуюих им сообщений. Локализация
     * сообщений в файле /application/language/someLanguage/ryndaModels_lang.php
     */
    protected static $_errorTypes = array();

    /**
     * Очистка списка ошибок последней операции. Вызывается в начале каждого метода,
     * работающего с БД.
     */
    protected function _clearErrors()
    {
        $this->_lastErrors = array();
    }

    /**
     * Зарегистрировать ошибку при работе с БД.
     *
     * @param $errorCode integer Код ошибки.
     * @param $isFatal boolean Останавливать ли операцию при ошибке.
     */
    protected function _addError($errorCode, $errorData = '', $isFatal = FALSE)
    {
        $errorCode = (int)$errorCode;
        if( $errorCode && isset(self::$_errorTypes[$errorCode]) )
            $this->_lastErrors[] = $errorCode;

        if($errorData)
            log_message('error', "Error №$errorCode in the model: ".print_r($errorData, TRUE));
        
        /**
         * @todo Сделать обработку этого параметра. Возможно, будет нужно расширить
         * системный класс исключения (CI_Exceptions)
         */
//        if($isFatal) {
//
//        }
    }

    /**
     * Получение кодов ошибок последней операции в виде массива.
     *
     * @return array Массив кодов ошибок последней операции.
     */
    public function getErrorCodes()
    {
        $errorCodes = array();
        foreach($this->_lastErrors as $error) {
            $errorCodes[] = $error['code'];
        }
        return $errorCodes;
    }

    /**
     * Получение сообщений об ошибках последней операции в виде массива.
     *
     * @return array Массив сообщений об ошибках последней операции.
     */
    public function getErrorMessages()
    {
        $errorMessages = array();
        foreach($this->_lastErrors as $errorCode) {
            $errorMessages[] = self::$_errorTypes[$errorCode];
        }
        return $errorMessages;
    }

    /**
     * Получение полной информации об ошибках последней операции в виде массива.
     *
     * @return array Массив информации об ошибках последней операции.
     */
    public function getErrors()
    {
        $errorInfo = array();
        foreach($this->_lastErrors as $errorCode) {
            $errorInfo[] = array('code' => $errorCode,
                                 'message' => self::$_errorTypes[$errorCode]);
        }
        return $errorInfo;
    }

    /**
     * Удаление HTML-тэгов из строки перед её вставкой в БД. Имеет смысл применять перед
     * экранированием ($this->db->escape()).
     *
     * @param $str string Строка текста для обработки.
     * @param $saveAllowedTags boolean Если передано TRUE, то из переданной строки
     * не будут удаляться разрешённые HTML-тэги. Список разрешённых тэгов - в
     * конфигурационной переменной allowed_html.
     * @return string Текст с отрезанными пробелами и удалёнными HTML-тэгами.
     */
    protected function _cleanText($str, $saveAllowedTags = FALSE)
    {
        if($saveAllowedTags) {
//            echo '<pre>' . print_r(implode('', (array)$this->config->item('allowed_html')), TRUE) . '</pre>';
//            echo '<pre>' . print_r("Before: ".$str, TRUE) . '</pre>
//                ---------------------------
//                <pre>' . print_r("After: ".strip_tags( trim($str), implode('', (array)$this->config->item('allowed_html')) ), TRUE) . '</pre>';
        }

        return $saveAllowedTags ?
                   // Удалить все тэги, кроме списка разрешённых:
                   strip_tags( trim($str), implode('', (array)$this->config->item('allowed_html')) ) :
                   strip_tags(trim($str)); // Удалить все тэги
    }

    /**
     * Добавление к текущему объекту запроса к БД параметра LIMIT.
     * В отдельный метод вынесено во избежание дублирования кода.
     *
     * @param $limit mixed Кол-во строк в результате запроса к БД. Можно передать
     * целое положительное число, либо строку вида 'начальный_индекс,кол-во_эл-тов',
     * либо массив вида array(начальный_индекс, кол-во_эл-тов). По умолчанию не ограничено.
     * @param $queryObj object Объект запроса к БД. Если передан, то параметр LIMIT будет
     * добавлен к нему, а сам объект будет возвращён. Если не передан, то параметр будет
     * добавлен к текущему запросу к БД ($this->db), и метод вернёт NULL. Аргумент не
     * обязателен.
     * @return mixed Если параметр LIMIT был добавлен успешно, возвращается объект $this->db
     * для дальнейшей работы с ним. Иначе, возвращается NULL.
     */
    protected function _addLimit($limit, $queryObj = NULL)
    {
        if( !empty($limit) ) {
            if( !$queryObj )
                $queryObj = $this->db;

            if(is_int($limit) && $limit > 0) // Передано число
                $queryObj->limit($limit);
            else if(is_array($limit)) { // Передан массив
                if(count($limit) == 1) // Дурак передал массив, но с 1 элементом вместо 2-х
                    $queryObj->limit((int)$limit[0]);
                else // Передан нормальный массив - используются первые 2 элемента
                    $queryObj->limit((int)$limit[1], (int)$limit[0]);
            }
            else { // Передана строка
                $limit = explode(',', $limit);
                // Дурак передал строку, но или бредовую, или из 1 числа вместо 2-х:
                if(count($limit) <= 1 || !trim($limit[1]) )
                    $queryObj->limit((int)trim($limit[0]));
                else
                    $queryObj->limit((int)trim($limit[1]),
                                     (int)trim($limit[0]));
            }
        }

        return $queryObj ? $queryObj : NULL;
    }
}