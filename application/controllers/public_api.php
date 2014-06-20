<?php if( !defined('BASEPATH') ) exit('No direct script access allowed');
/**
 * Файл содержит определение класса Api (контроллер)
 *
 * @copyright  Copyright (c) 2011 Звягинцев Л. aka Ahaenor
 * @link       /application/controllers/api.php
 * @since      Файл доступен начиная с версии проекта 0.4
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/
 */

require APPPATH.'libraries/REST_Controller.php';

/**
 * Контроллер внешнего API системы.
 */
class Public_Api extends REST_Controller
{
    /**
     * Конструктор - подключение скриптов локализации, нужных для сообщений об ошибках.
     */
    public function __construct()
    {
        parent::__construct();
        $this->lang->load('rynda_forms');
        $this->lang->load('rynda_api');
    }

    /**
     * Служебная функция для поддержки разных вариантов входных параметров для методов:
     * * camelCase (paramName),
     * * нижний регистр (paramname),
     * * с нижними подчёркиваниями (param_name).
     * 
     * @param $paramName string Название параметра, передаваемого при вызове метода API,.
     * Название должно передаваться в формате camelCase.
     * @return mixed Значение параметра. 
     */
    private function _getParamValue($paramName)
    {
        // Обычный вариант (предположительно, camelCase):
        if($this->get($paramName) !== FALSE)
            return $this->get($paramName);
        // Приведено к нижнему регистру:
        if($this->get(strtolower($paramName)) !== FALSE)
            return $this->get(strtolower($paramName));
        // Через нижнее подчёркивание:
        $paramNameParts = explode(' ', preg_replace('/(?!^)[A-Z]{2,}(?=[A-Z][a-z])|[A-Z][a-z]|[0-9]{1,}/', ' $0', $paramName));
        if($paramNameParts && count($paramNameParts) == 1)
            return $this->get($paramNameParts[0]);
        else if($paramNameParts)
            return $this->get( implode('_', array_map('strtolower', $paramNameParts)) );
        
        return FALSE;  
    }

    /**
     * Обработка запроса на получение списка сообщений, соотв. указанным параметрам.
     * 
     * Параметры фильтрации передаются методом GET. Возможные значения параметров:
     * * date_added_from integer Timestamp нижней границы даты/времени добавления сообщения.
     * Не обязателен. По умолчанию в фильтрации не участвует.
     * * date_added_to integer Timestamp верхней границы даты/времени добавления сообщения.
     * Не обязателен. По умолчанию в фильтрации не участвует.
     * * subdomain mixed ID или название раздела системы (дочерней карты, субдомена),
     * к которому относятся сообщения. Не обязателен. По умолчанию, при запросе к разделу
     * сайта принимает значение этого раздела; при запросе к корневому разделу системы
     * в фильтрации не участвует.
     * * type_id mixed ID типа сообщений. Можно передать один из ID типов сообщений или
     * массив из нескольких таких ID. Не обязателен. По умолчанию в фильтрации не участвует.
     * * type_slug mixed Короткое название (slug) типа сообщений. Можно передать один из
     * slug типов сообщений или массив из нескольких slug. Не обязателен. По умолчанию в
     * фильтрации не участвует.
     * * status_id mixed ID текущего статуса сообщения или массив ID статусов. Не обязателен.
     * По умолчанию в фильтрации не участвует.
     * * full_search string Строка поискового запроса для полнотекстового поиска по заголовку,
     * тексту и адресу сообщений. Не обязателен. По умолчанию в фильтрации не участвует.
     * * address string Адрес точки, к которой относится сообщение. Не обязателен.
     * По умолчанию в фильтрации не участвует.
     * * region_id integer ID региона, которому принадлежит точка сообщения. Не обязателен.
     * По умолчанию в фильтрации не участвует.
     * * category mixed ID категории сообщения или массив ID категорий.
     * * user_id integer ID пользователя, являющегося автором сообщения. Не обязателен.
     * По умолчанию в фильтрации не участвует.
     * * items_per_page integer Максимальное количество сообщений, которое будет возвращено.
     * По умолчанию, количество возвращаемых сообщений не ограничено.
     * * current_page integer Смещение в результирующей выборке сообщений, начиная с которой
     * выбираются сообщения, возвращаемые методом. Параметр используется только вместе с
     * параметром items_per_page. Фактически, смещение означает страницу в результирующей
     * выборке. По умолчанию, не используется.
     * 
     * В результате успешного запроса будет возвращён массив из объектов сообщений,
     * содержащих следующие поля:
     * * title string Заголовок сообщения.
     * * text string Текст сообщения.
     * * type_slug string Короткое название типа сообщения.
     * * type_name string Название типа сообщения.
     * * status string Статус сообщения.
     * * ryndaorg_subdomain string Название раздела системы Rynda.org, к которому относится
     * сообщение.
     * * lat float Широта точки сообщения.
     * * lng float Долгота точки сообщения.
     * * region_name string Название региона, к которому относится точка, привязанная к
     * сообщению.
     * * address string Адрес точки, к которой относится сообщение.
     * * url string URL страницы сообщения в системе Rynda.org
     * * author_last_name string Фамилия автора сообщения. Если автор потребовал закрытости
     * данных сообщения, поле не заполняется.
     * * author_first_name string Имя автора сообщения. Если автор потребовал закрытости
     * данных сообщения, поле не заполняется.
     * * author_phones array Массив телефонов автора сообщения. Если автор потребовал
     * закрытости данных сообщения, поле не заполняется.
     * * author_email string E-mail автора сообщения. Если автор потребовал закрытости
     * данных сообщения, поле не заполняется.
     */
    function messages_get()
    {
        // Начальная проверка формата:
        switch($this->get('format')) {
            case 'csv':
            case 'html':
            case 'xml':
            case 'json':
            case 'jsonp':
//            case 'serialize':
                break;
            default:
                if($this->get('format') !== FALSE)
                    $this->response($this->lang->line('api_unknownFormatError'), 500);
        }

        $this->load->model('Messages_Model', 'messages', TRUE);

        $filter = array('isActive' => 1,);

        // Раздел сайта (дочерний сайт, поддомен):
        $param = $this->_getParamValue('subdomain');
        if($param)
            $filter['subdomain'] = $param;
        else if($param !== FALSE)
            $filter['subdomain'] = '';
        else {
            $param = getSubdomain();
            if($param)
                $filter['subdomain'] = $param;
        }

        // ID пользователя, создавшего сообщения:
        $param = $this->_getParamValue('userId');
        if($param || $param === 0)
            $filter['userId'] = (int)$param;

        // Минимальная дата создания сообщений:
        $param = $this->_getParamValue('dateAddedFrom');
        if($param)
            $filter['dateAddedFrom'] = (int)$param;

        // Максимальная дата создания сообщений:
        $param = $this->_getParamValue('dateAddedTo');
        if($param)
            $filter['dateAddedTo'] = (int)$param;

        // ID региона, к которому относятся сообщения:
        $param = $this->_getParamValue('regionId');
        if($param)
            $filter['regionId'] = (int)$param;

        // Строка адреса, указанного в сообщениях:
        $param = $this->_getParamValue('address');
        if($param)
            $filter['address'] = trim($param);

        // Строка для полнотекстового поиска по заголовку, тексту и адресу сообщения:
        $param = $this->_getParamValue('fullSearch');
        if($param)
            $filter['full'] = trim($param);

        // ID типа сообщения:
        $param = $this->_getParamValue('typeId');
        if($param) {
            $filter['typeId'] = array();
            foreach((array)$param as $type) {
                if($type)
                    $filter['typeId'][] = (int)$type;
            }
        }

        // Короткое название (slug) типа сообщения:
        $param = $this->_getParamValue('typeSlug');
        if($param) {
            $filter['typeSlug'] = array();
            foreach((array)$param as $type) {
                if($type)
                    $filter['typeSlug'][] = trim($type);
            }
        }

        // Типы сообщений по умолчанию:
        if(empty($filter['typeId']) && empty($filter['typeSlug']))
            $filter['typeSlug'] = array('request', 'offer', 'info');

        // Статусы сообщений. По умолчанию, сообщения со статусами "не промодерировано" и
        // "закрыто" исключаются из результата:
        $param = $this->_getParamValue('statusId');
        if($param) {
            $filter['statusId'] = array();
            foreach((array)$param as $status) {
                if($status)
                    $filter['statusId'][] = trim($status);
            }
        } else {
            $filter['statusId'] = array(MESSAGE_STATUS_MODERATED, MESSAGE_STATUS_VERIFIED,
                                        MESSAGE_STATUS_REACTION, MESSAGE_STATUS_REACTED);
        }

        // Категории сообщений:
        $param = $this->_getParamValue('category');
        if($param) {
            $filter['category'] = array();
            foreach((array)$param as $cat) {
                if( !empty($cat) )
                    $filter['category'][] = (int)$cat;
            }
        }

        // Количество выбираемых сообщений и начальная позиция внутри выборки:
        $itemsPerPage = (int)$this->_getParamValue('itemsPerPage');

        if($itemsPerPage) {
            $currentPage = (int)$this->_getParamValue('currentPage');
            $currentPage = $currentPage ? (int)$currentPage : 1;
            $filter['limit'] = array(($currentPage-1)*$itemsPerPage, $itemsPerPage);
        }

        $messages = $this->messages->getList($filter, array('categories' => 'desc',
                                                            'dateModified' => 'desc',));
        if($messages !== FALSE) {
            $messagesResult = array();
            foreach($messages as $message) {
                $urlParts = getUrlComponents();
                $url = "{$urlParts['scheme']}://{$urlParts['host']}/info/s/{$message['id']}";
                $messagesResult[] = array('id' => $message['id'],
                                          'title' => $message['title'],
                                          'text' => $message['text'],
                                          'type_slug' => $message['typeSlug'],
                                          'type_name' => $message['typeName'],
                                          'status' => (string)$this->messages
                                                                   ->getStatusName($message['statusId']),
                                          'date_added' => (int)$message['dateAdded'],
                                          'ryndaorg_subdomain' => $message['subdomainName'],
                                          'lat' => $message['lat'],
                                          'lng' => $message['lng'],
                                          'region_name' => $message['regionName'],
                                          'address' => $message['address'],
                                          'url' => $url,
                                          'author_last_name' => $message['isPublic'] ?
                                                                    $message['lastName'] : '',
                                          'author_first_name' => $message['isPublic'] ?
                                                                     $message['firstName'] : '',
                                          'author_phones' => $message['isPublic'] ?
                                                                 $message['phones'] : '',
                                          'author_email' => $message['isPublic'] ?
                                                                $message['email'] : '',);
            }

            switch($this->get('format')) {
                case 'csv':
                case 'html':
                    array_walk($messagesResult, function(&$item){
                        // Обработка всех полей с текстом:
                        $item['title'] = $item['title'] ?
                                             str_replace(array("\r\n", "\n", "\r", '"'),
                                                         array('<br />', '<br />', '<br />', '""'),
                                                         $item['title'])
                                         : '';
                        $item['text'] = $item['text'] ?
                                            str_replace(array("\r\n", "\n", "\r", '"'),
                                                        array('<br />', '<br />', '<br />', '""'),
                                                        $item['text'])
                                        : '';
                        // Обработка всех полей-массивов:
                        if(is_array($item['author_phones']))
                            $item['author_phones'] = implode('|', $item['author_phones']);
                    });
                    $this->response($messagesResult, 200);
                    break;
                case 'xml':
                case 'json':
                case 'jsonp':
                case 'serialize':
                case FALSE:
                    $this->response(array('data' => $messagesResult,
                                          'total_data_count' => $this->messages->getCount($filter),),
                                    200);
                    break;
                default:
                    // Неизвестный формат уже обработан в начальной проверке
            }
        } else {
            $errorMessages = $this->messages->getErrorMessages() ?
                                 $this->messages->getErrorMessages() :
                                 $this->lang->line('api_getMessagesError');
            $this->response($errorMessages, 500);
        }
    }

    /**
     * Process the query to get list of users, according to given params.
     * 
     * Filter params are taken from GET. Possible filter values:
     * * region_id integer User selected region ID. No filtering by default.
     * 
     * If request succeeded, method will return an array of user objects with following
     * fields:
     * * id integer User ID.
     * * email User email.
     * * first_name string User first name.
     * * last_name string User last name.
     * * phones array User phones string, delimited by ",".
     * * date_created integer User registration timestamp.
     * * date_last_login integer User's last login timestamp.
     * * region_id integer User region ID.
     * * vp_categories array Array of category IDs selected in all of user's volunteer
     * proifiles. Category IDs are delimited by ",".
     * * about string "About me" text.
     * * social_profiles array Array of user socmedia profiles. Each entry has following
     * fields:
     * * * socmedia_name string Socmedia name/title.
     * * * profile_url string URL of user profile.
     */
    function users_get()
    {  
        // Initial format check:
        switch($this->get('format')) {
            case 'csv':
            case 'html':
            case 'xml':
            case 'json':
            case 'jsonp':
//            case 'serialize':
                break;
            default:
                if($this->get('format') !== FALSE)
                    $this->response($this->lang->line('api_unknownFormatError'), 500);
        }

        $this->load->model('Users_Model', 'users', TRUE);

        $filter = array('isActive' => 1,);

        // User region ID:
        $param = $this->_getParamValue('region_id');
        if($param)
            $filter['regionId'] = $param;
        $ussers = $this->users->getList($filter);
    }
}
