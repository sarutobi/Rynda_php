<?php
/**
 * Файл содержит определение класса Rynda_Output (абстрактный класс, вершина иерархии
 * классов библиотек менеджеров вывода, предназначенных для интеграции платформы Rynda.org
 * с другими системами).
 *
 * @copyright  Copyright (c) 2011 Звягинцев Л. aka Ahaenor
 * @link       /application/libraries/Rynda_output.php
 * @version    0.1
 * @since      Файл доступен начиная с версии проекта 0.5
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/
 */

/**
 * Базовый класс библиотеки-менеджера вывода.
 */
abstract class Rynda_Output
{
    /**
     * @var object Объект CodeIgniter для операций с БД.
     */
    protected $_ci;
    /**
     * @var array Массив данных конкретного менеджера вывода, который представлен
     * классом-потомком.
     */
    protected $_manager;
    /**
     * @var array Массив параметров работы менеджера применимо к партнёрской системе.
     * Ключи массива - ID партнёрских систем, значения - ассоциативные массивы параметров
     * применимо к соответствующим партёрским системам.
     */
    protected $_params;
    /**
     * @var string Код ошибки последней выполненной операции.
     */
    protected $_errorCode;
    /**
     * @var string Сообщение об ошибке последней выполненной операции.
     */
    protected $_errorMessage;

    /**
     * Конструктор обязательно должен переопределяться в подклассах, чтобы реализовывать
     * соответствие между подклассом и соотв. ему сущностью менеджера вывода.
     * Это подразумевает заполнение поля _manager в подклассе.
     */
    protected function __construct()
    {
        $this->_manager = array();
        $this->_params = array();
        $this->_ci =& get_instance();
        $this->_errorCode = FALSE;
        $this->_errorMessage = '';

        $this->_ci->load->model('Partners_Model', 'partners', TRUE);
    }

    /**
     * Геттер для свойств класса, отвечающих за ошибки операций.
     *
     * @param $attrName string Название свойства, чьё значение необходимо вернуть.
     * @return mixed Значение указанного свойства.
     */
    public function __get($attrName)
    {
        switch($attrName) {
            case 'errorCode':
                return $this->_errorCode;
            case 'errorMessage':
                return $this->_errorMessage;
            default:
        }
    }

    /**
     * Получение массива с описанием параметров, возможных/необходимых для работы
     * менеджера вывода.
     * Описание каждого параметра включает название, тип, список возможных значений и пр.
     * Метод используется для формирования страницы в админке, служащей для настройки
     * взаимодействия между системой Rynda.org и какой-либо другой. Какой именно другой,
     * определяется в подклассе Rynda_Output.
     *
     * @return array Массив параметров, используемых менеджером вывода.
     */
    public function getParams()
    {
        return array();
    }

    /**
     * Вывод 1 сообщения Rynda.org. Например, отправка этого сообщения в систему-партнёр.
     *
     * @param $messageId integer ID выводимого сообщения.
     * @return boolean TRUE/FALSE в зависимости от успеха операции. При неудаче свойства
     * $_errorCode и $_errorMessage класса заполняются соответствующими значениями.
     */
    public function pushMessage(int $messageId)
    {
        return TRUE;
    }
}
