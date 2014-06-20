<?php if( !defined('BASEPATH') ) exit('No direct script access allowed');
/**
 * Файл содержит определение класса Messages (контроллер страниц)
 *
 * @copyright  Copyright (c) 2011 Звягинцев Л. aka Ahaenor
 * @link       /application/controllers/messages.php
 * @version    0.1
 * @since      Файл доступен начиная с версии проекта 0.1
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/
 */

/**
 * Контроллер страниц для работы с сообщениями, поступающими в систему.
 */
class Messages extends Rynda_Controller
{
    /**
     * Конструктор - подключение языкового файла для локализации сообщений на страницах.
     */
    public function __construct()
    {
        parent::__construct();
        $this->lang->load('rynda_forms');
    }

    /**
     * Страница списка всех сообщений.
     */
    public function index()
    {
        $this->load->model('Categories_Model', 'categories', TRUE);
        $this->load->model('Messages_Model', 'messages', TRUE);

        $this->load->view('commonHeader',
                          array('showAuth' => TRUE,
                                'user' => $this->_user,
                                'regions' => $this->regions->getList(),
                                'userRegion' => $this->_userRegion,
                                'subdomains' => $this->subdomains->getList(array('limit' => 15,
                                                                                 'status' => 1,)),
                                'title' => 'Сообщения',
                                'organizationTypes' => $this->organizations->getTypesList(),
                                'showRequestButton' => TRUE,
                                'showOfferButton' => TRUE,));
        $this->load->view('messagesListSingle', array('messageTypes' =>
                                                          $this->messages->getTypesList(array('advise',
                                                                                              'comment')),
                                                      'regions' => $this->regions->getList(),
                                                      'categories' => $this->categories->getChilds(),
                                                      'listTitleShort' => 'Cообщения',
                                                      'listTitle' => 'Последние сообщения',
                                                      'filterShowFields' => array('messageType',
                                                                                  'region',
                                                                                  'category',
                                                                                  'isUntimed',
                                                                                  'searchString',),
                                                      'messageTypeRequest' => 'request',
                                                      'messageTypeOffer' => 'offer',
                                                      'messageTypeInfo' => 'info',
        ));
        $jsVars = array('LANG_MESSAGES_NOT_FOUND' => $this->lang->line('forms_searchMessagesNotFound'),
                        'CONST_COOKIE_DOMAIN' => $this->config->item('cookie_domain'),
                        'CONST_REGION_SERVICE_URL' => $this->config->item('region_geolocation_url'),);
        $this->load->view('jsVars', array('jsVars' => $jsVars));
        $this->load->view('commonFooter');
    }

    /**
     * Страница добавления нового сообщения с просьбой помощи.
     */
    public function addRequest()
    {
        // Sometimes session ID is unset right after user logout. To fix it, refresh the page:
//        if( !getSessionCookie() )
//            header('Location: '.current_url());

        $this->load->model('Categories_Model', 'categories', TRUE);

        $this->load->view('commonHeader',
                          array('showAuth' => TRUE,
                                'user' => $this->_user,
                                'regions' => $this->regions->getList(),
                                'userRegion' => $this->_userRegion,
                                'subdomains' => $this->subdomains->getList(array('limit' => 15,
                                                                                 'status' => 1,)),
                                'title' => 'Новая просьба о помощи',
                                'organizationTypes' => $this->organizations->getTypesList(),));
        $this->load->view('messagesAddRequest',
                          array('messageTypeSlug' => 'request',
                                'regions' => $this->regions->getList(),
                                'categories' => $this->categories->getChilds(),));
        $jsVars = array('CONST_COOKIE_DOMAIN' => $this->config->item('cookie_domain'),
                        'CONST_REGION_SERVICE_URL' => $this->config->item('region_geolocation_url'),
                        'LANG_ADDR_FOUND' => $this->lang->line('forms_addrGeolocateSuccess'),
                        'LANG_ADDR_NOT_FOUND' => $this->lang->line('forms_addrGeolocateFailure'),
                        'LANG_FIRST_NAME_REQUIRED' => $this->lang->line('forms_firstNameRequired'),
                        'LANG_FIRST_NAME_INVALID' => $this->lang->line('forms_firstNameInvalid'),
                        'LANG_LAST_NAME_REQUIRED' => $this->lang->line('forms_lastNameRequired'),
                        'LANG_LAST_NAME_INVALID' => $this->lang->line('forms_lastNameInvalid'),
                        'LANG_PHONE_INVALID' => $this->lang->line('forms_phoneInvalid'),
                        'LANG_EMAIL_INVALID' => $this->lang->line('forms_emailInvalid'),
                        'LANG_SOME_CONTACTS_REQUIRED' => $this->lang->line('forms_someContactsRequired'),
                        'LANG_TEXT_REQUIRED' => $this->lang->line('forms_requestTextRequired'),
                        'LANG_CATEGORY_REQUIRED' => $this->lang->line('forms_categoryRequired'),
                        'LANG_AGREE_REQUIRED' => $this->lang->line('forms_dataProcessAgreeRequired'),
                        'LANG_ADD_MESSAGE_SUCCESS' => $this->lang->line('forms_addRequestSuccess'),
                        'CONST_ALLOWED_TAGS' => json_encode($this->config->item('allowed_html')));
        $this->load->view('jsVars', array('jsVars' => $jsVars));
        $this->load->view('commonFooter');
    }

    /**
     * Страница добавления нового сообщения с предложением помощи.
     */
    public function addOffer()
    {
        // Sometimes session ID is unset right after user logout. To fix it, refresh the page:
//        if( !getSessionCookie() )
//            header('Location: '.current_url());

        $this->load->model('Categories_Model', 'categories', TRUE);

        $this->load->view('commonHeader',
                          array('showAuth' => TRUE,
                                'user' => $this->_user,
                                'regions' => $this->regions->getList(),
                                'userRegion' => $this->_userRegion,
                                'subdomains' => $this->subdomains->getList(array('limit' => 15,
                                                                                 'status' => 1,)),
                                'title' => 'Новое предложение помощи',
                                'organizationTypes' => $this->organizations->getTypesList(),));
        $this->load->view('messagesAddOffer',
                          array('messageTypeSlug' => 'offer',
                                'regions' => $this->regions->getList(),
                                'categories' => $this->categories->getChilds(),));
        $jsVars = array('CONST_COOKIE_DOMAIN' => $this->config->item('cookie_domain'),
                        'CONST_REGION_SERVICE_URL' => $this->config->item('region_geolocation_url'),
                        'LANG_ADDR_FOUND' => $this->lang->line('forms_addrGeolocateSuccess'),
                        'LANG_ADDR_NOT_FOUND' => $this->lang->line('forms_addrGeolocateFailure'),
                        'LANG_AIDING_TIME_SOMETIMES' => $this->lang->line('forms_aidingTimeSometimes'),
                        'LANG_AIDING_TIME_MONTH' => $this->lang->line('forms_aidingTimeMonth'),
                        'LANG_AIDING_TIME_MONTH_MORE' => $this->lang->line('forms_aidingTimeMonthMore'),
                        'LANG_AIDING_TIME_WEEK' => $this->lang->line('forms_aidingTimeWeek'),
                        'LANG_AIDING_TIME_WEEK_MORE' => $this->lang->line('forms_aidingTimeWeekMore'),
                        'LANG_AIDING_TIME_WORKDAYS' => $this->lang->line('forms_aidingTimeWorkDays'),
                        'LANG_AIDING_TIME_HOLIDAYS' => $this->lang->line('forms_aidingTimeHolidays'),
                        'LANG_AIDING_TIME_EVERYDAY' => $this->lang->line('forms_aidingTimeEveryday'),
                        'LANG_AIDING_TIME_ALLTIME' => $this->lang->line('forms_aidingTimeAllTime'),
                        'LANG_AIDING_DISTANCE_MIN_LABEL' =>
                            $this->lang->line('forms_aidingDistMinLabel'),
                        'LANG_AIDING_DISTANCE_LABEL' =>
                            $this->lang->line('forms_aidingDistLabel'),
                        'LANG_AIDING_DISTANCE_MAX_LABEL' =>
                            $this->lang->line('forms_aidingDistMaxLabel'),
                        'LANG_FIRST_NAME_REQUIRED' => $this->lang->line('forms_firstNameRequired'),
                        'LANG_FIRST_NAME_INVALID' => $this->lang->line('forms_firstNameInvalid'),
                        'LANG_LAST_NAME_REQUIRED' => $this->lang->line('forms_lastNameRequired'),
                        'LANG_LAST_NAME_INVALID' => $this->lang->line('forms_lastNameInvalid'),
                        'LANG_PHONE_INVALID' => $this->lang->line('forms_phoneInvalid'),
                        'LANG_EMAIL_REQUIRED' => $this->lang->line('forms_emailRequired'),
                        'LANG_EMAIL_INVALID' => $this->lang->line('forms_emailInvalid'),
                        'LANG_SOME_CONTACTS_REQUIRED' => $this->lang->line('forms_someContactsRequired'),
                        'LANG_TEXT_REQUIRED' => $this->lang->line('forms_volunteerAboutRequired'),
                        'LANG_CATEGORY_REQUIRED' => $this->lang->line('forms_categoryRequired'),
                        'LANG_ADD_MESSAGE_SUCCESS' => $this->lang->line('forms_addOfferSuccess'),
                        'CONST_ALLOWED_TAGS' => json_encode($this->config->item('allowed_html')));
        $this->load->view('jsVars', array('jsVars' => $jsVars));
        $this->load->view('commonFooter');
    }

    /**
     * Страница просмотра сообщения.
     *
     * @param $id integer Id сообщения в БД.
     */
    public function detail($id)
    {
        $id = (int)$id;
        if($id <= 0)
            show_error($this->lang->line('pages_messageNotFound'), 404);

        $this->load->model('Messages_Model', 'messages', TRUE);
        $this->load->model('Media_Model', 'media', TRUE);
        $this->load->model('Subscriber_Model', 'subscriber', TRUE);

        $message = $this->messages->getById($id);


        if( !$message )
            show_error($this->lang->line('pages_messageNotFound'), 404);
        else if($message['subdomainName'] !== getSubdomain())
            // Попытка открыть страницу сообщения в пределах не его субдомена, редирект:
            redirect(changeUrlSubdomain($message['subdomainName']));

        $this->lang->load('calendar');

        $photo = array();
        foreach($message['photo'] as $photoId) {
            $photo[] = $this->media->getById($photoId);
        }
        $categoryIds = $categoryNames = array();
        foreach($message['categories'] as $category) {
            $categoryIds[] = $category['id'];
            $categoryNames[] = $category['name'];
        }
        $message['statusName'] = $this->messages->getStatusName($message['statusId']);

        $this->load->view('commonHeader',
                          array('showAuth' => TRUE,
                                'user' => $this->_user,
                                'regions' => $this->regions->getList(),
                                'userRegion' => $this->_userRegion,
                                'subdomains' => $this->subdomains->getList(array('limit' => 15,
                                                                                 'status' => 1,)),
                                'subdomainTabsResetUrl' => TRUE,
                                'title' => 'Сообщение '
                                          .($message['title'] ? "«{$message['title']}»" :
                                                                "№{$message['id']}"),
                                'keywords' => $categoryNames,
                                'description' => formatTextTrimmed(strip_tags($message['text']), 200),
                                'organizationTypes' => $this->organizations->getTypesList(),
                                'showRequestButton' => TRUE,
                                'showOfferButton' => TRUE,));
        switch($message['typeSlug']) {
            case 'request':
                $this->load->view('messagesDetailRequest',
                                  array('message' => $message,
                                        'photo' => $photo,
                                        'isSubscribed' =>
                                             $this->subscriber->isSubscribed(
                                                     $this->_user? $this->_user->id: null,
                                                     $message['id']),
                                        'advises' => $this->messages->getList(array('category' => $categoryIds,
                                                                                    'typeSlug' => 'advise',
                                                                                    'limit' => 4)),));
                break;
            case 'offer':
                $this->load->view('messagesDetailOffer',
                                  array('message' => $message,
                                        'photo' => $photo,
                                        'advises' => $this->messages->getList(array('category' => $categoryIds,
                                                                                    'typeSlug' => 'advise',
                                                                                    'limit' => 4)),));
                break;
            case 'info':
                $this->load->view('messagesDetailInfo', array('message' => $message,
                                                              'photo' => $photo,
                                                        ));
                break;
            case 'advise':
                $this->load->view('messagesDetailAdvise', array('message' => $message,
                                                                'photo' => $photo,));
                break;
            default:
                show_error($this->lang->line('pages_messageTypeNotFound'), 404);
        }

        $jsVars = array('CONST_COOKIE_DOMAIN' => $this->config->item('cookie_domain'),
                        'CONST_REGION_SERVICE_URL' => $this->config->item('region_geolocation_url'),
                        'VAR_MESSAGE_TYPE' => $message['typeSlug'],
                        'VAR_MESSAGE_ID' => $message['id'],
                        'VAR_COMMENTS_EXPAND' => $this->input->get('expand')
                  );

        $this->load->view('jsVars', array('jsVars' => $jsVars));
        $this->load->view('commonFooter');
    }

    /**
     * Страница списка сообщений по категории.
     *
     * @param $category mixed Либо ID категории, либо её короткое название.
     */
    public function category($category)
    {
        if(empty($category))
            show_error($this->lang->line('pages_categoryNotFound'), 404);

        $this->load->model('Categories_Model', 'categories', TRUE);
        $this->load->model('Messages_Model', 'messages', TRUE);

        if((int)$category > 0) // Передан ID категории
            $category = $this->categories->getById((int)$category);
        else if($category) // Передано короткое название категории
            $category = $this->categories->getBySlug( urlencode(trim($category)) );

        if( !$category ) // Категория не найдена
            show_error($this->lang->line('pages_categoryNotFound'), 404);

        $this->load->view('commonHeader',
                          array('showAuth' => TRUE,
                                'user' => $this->_user,
                                'regions' => $this->regions->getList(),
                                'userRegion' => $this->_userRegion,
                                'subdomains' => $this->subdomains->getList(array('limit' => 15,
                                                                                 'status' => 1,)),
                                'title' => "Категория «{$category['name']}»",
                                'organizationTypes' => $this->organizations->getTypesList(),
                                'showRequestButton' => TRUE,
                                'showOfferButton' => TRUE,));
        $this->load->view('messagesListSingle',
                          array('messageTypes' =>
                                    $this->messages->getTypesList(array('advise', 'comment')),
                                'regions' => $this->regions->getList(),
                                'listTitleShort' => $category['name'],
                                'listTitle' => "{$category['name']}: сообщения по категории",
                                'filterShowFields' => array('messageType',
                                                            'region',
                                                            'isUntimed',
                                                            'searchString'),
                                'filterPersistVars' => array(
                                    'category' => array($category['id']),
                                    'isActive' => TRUE,
                                    'typeSlug' => array('info', 'request', 'offer')),
                                'messageTypeRequest' => 'request',
                                'messageTypeOffer' => 'offer',));

        $jsVars = array('LANG_MESSAGES_NOT_FOUND' => $this->lang->line('forms_searchMessagesNotFound'),
                        'CONST_COOKIE_DOMAIN' => $this->config->item('cookie_domain'),
                        'CONST_REGION_SERVICE_URL' => $this->config->item('region_geolocation_url'),
                        'CONST_LIST_TYPE' => 'category',);
        $this->load->view('jsVars', array('jsVars' => $jsVars));
        $this->load->view('commonFooter');
    }

    /**
     * Страница списка сообщений по региону.
     *
     * @param $region mixed Либо ID региона, либо его короткое название.
     */
    public function region($region)
    {
        if(empty($region))
            show_error($this->lang->line('pages_regionNotFound'), 404);

        $this->load->model('Categories_Model', 'categories', TRUE);
        $this->load->model('Messages_Model', 'messages', TRUE);

        if((int)$region > 0) // Передан ID региона
            $region = $this->regions->getById((int)$region);
        else if($region) // Передано короткое название региона
            $region = $this->regions->getBySlug( urlencode(trim($region)) );

        if( !$region ) // Регион не найден
            show_error($this->lang->line('pages_regionNotFound'), 404);

        $this->load->view('commonHeader',
                          array('showAuth' => TRUE,
                                'user' => $this->_user,
                                'regions' => $this->regions->getList(),
                                'userRegion' => $this->_userRegion,
                                'subdomains' => $this->subdomains->getList(array('limit' => 15,
                                                                                 'status' => 1,)),
                                'title' => "Регион «{$region['name']}»",
                                'organizationTypes' => $this->organizations->getTypesList(),
                                'showRequestButton' => TRUE,
                                'showOfferButton' => TRUE,));
        $this->load->view('messagesListSingle',
                          array('messageTypes' =>
                                    $this->messages->getTypesList(array('advise', 'comment')),
                                'categories' => $this->categories->getChilds(),
                                'listTitleShort' => $region['name'],
                                'listTitle' => "{$region['name']}: сообщения по региону",
                                'filterShowFields' => array('messageType',
                                                            'category',
                                                            'isUntimed',
                                                            'searchString'),
                                'filterPersistVars' => array('regionId' => $region['id'],
                                                             'isActive' => TRUE,
                                                             'typeSlug' => array('info',
                                                                                 'request',
                                                                                 'offer')),
                                  'messageTypeRequest' => 'request',
                                  'messageTypeOffer' => 'offer',));

        $jsVars = array('LANG_MESSAGES_NOT_FOUND' => $this->lang->line('forms_searchMessagesNotFound'),
                        'CONST_COOKIE_DOMAIN' => $this->config->item('cookie_domain'),
                        'CONST_REGION_SERVICE_URL' => $this->config->item('region_geolocation_url'),
                        'CONST_LIST_REGION' => $region['id'],);
        $this->load->view('jsVars', array('jsVars' => $jsVars));
        $this->load->view('commonFooter');
    }

    /**
     * Страница списка сообщений со статусом "закрыто", т.е. тех, по которым помощь была
     * оказана.
     */
    public function helped()
    {
        $this->load->model('Categories_Model', 'categories', TRUE);
        $this->load->model('Messages_Model', 'messages', TRUE);

        $this->load->view('commonHeader',
                          array('showAuth' => TRUE,
                                'user' => $this->_user,
                                'regions' => $this->regions->getList(),
                                'userRegion' => $this->_userRegion,
                                'subdomains' => $this->subdomains->getList(array('limit' => 15,
                                                                                 'status' => 1,)),
                                'title' => 'Уже оказанная помощь',
                                'organizationTypes' => $this->organizations->getTypesList(),
                                'showRequestButton' => TRUE,
                                'showOfferButton' => TRUE,));
        $this->load->view('messagesListSingle',
                          array('regions' => $this->regions->getList(),
                                'categories' => $this->categories->getChilds(),
                                'listTitleShort' => 'Помощь нашлась',
                                'listTitle' => 'В данном разделе приводятся случаи, когда пользователи смогли помочь друг другу',
                                'filterShowFields' => array('region', 'category', 'searchString'),
                                'filterPersistVars' => array(
                                    'limit' => 10, // Кол-во сообщений на странице
                                    'statusId' => array(MESSAGE_STATUS_REACTED, MESSAGE_STATUS_CLOSED),
                                    'typeSlug' => array('request', 'offer')),
                                'messageTypeRequest' => 'request',
                                'messageTypeOffer' => 'offer',));

        $jsVars = array('LANG_MESSAGES_NOT_FOUND' => $this->lang->line('forms_searchMessagesNotFound'),
                        'CONST_COOKIE_DOMAIN' => $this->config->item('cookie_domain'),
                        'CONST_REGION_SERVICE_URL' => $this->config->item('region_geolocation_url'),);
        $this->load->view('jsVars', array('jsVars' => $jsVars));
        $this->load->view('commonFooter');
    }

    /**
     * Страница списка сообщений определённого типа (просьба/предложение/информация/...).
     */
    public function type($typeName)
    {
        $pageTitle = $listTitle = $listTitleShort = $filterType = '';
        switch($typeName) {
            case 'request':
                $pageTitle = 'Просьбы о помощи';
                $listTitle = $listTitleShort = 'Просьбы о помощи';
                $filterType = 'request';
                break;
            case 'offer':
                $pageTitle = 'Предложения помощи';
                $listTitle = $listTitleShort = 'Предложения помощи';
                $filterType = 'offer';
                break;
            case 'info':
            default:
                $pageTitle = 'Информационные сообщения';
                $listTitle = $listTitleShort = 'Информационные сообщения';
                $filterType = 'info';
                break;
        }

        $this->load->model('Categories_Model', 'categories', TRUE);

        $this->load->view('commonHeader',
                          array('showAuth' => TRUE,
                                'user' => $this->_user,
                                'regions' => $this->regions->getList(),
                                'userRegion' => $this->_userRegion,
                                'subdomains' => $this->subdomains->getList(array('limit' => 15,
                                                                                 'status' => 1,)),
                                'title' => $pageTitle,
                                'organizationTypes' => $this->organizations->getTypesList(),
                                'showRequestButton' => TRUE,
                                'showOfferButton' => TRUE,));
        $this->load->view('messagesListSingle',
                          array('regions' => $this->regions->getList(),
                                'categories' => $this->categories->getChilds(),
                                'listTitleShort' => $listTitleShort,
                                'listTitle' => $listTitle,
                                'filterShowFields' => array('region',
                                                            'category',
                                                            'isUntimed',
                                                            'searchString'),
                                'filterPersistVars' => array(
                                    'limit' => 10, // Кол-во сообщений на странице
                                    'statusId' => array(MESSAGE_STATUS_MODERATED,
                                                        MESSAGE_STATUS_VERIFIED,
                                                        MESSAGE_STATUS_REACTION,
                                                        MESSAGE_STATUS_REACTED,),
                                    'typeSlug' => $filterType),
                                'messageTypeRequest' => 'request',
                                'messageTypeOffer' => 'offer',
                                'messageTypeInfo' => 'info',));

        $jsVars = array('LANG_MESSAGES_NOT_FOUND' => $this->lang->line('forms_searchMessagesNotFound'),
                        'CONST_COOKIE_DOMAIN' => $this->config->item('cookie_domain'),
                        'CONST_REGION_SERVICE_URL' => $this->config->item('region_geolocation_url'),);
        $this->load->view('jsVars', array('jsVars' => $jsVars));
        $this->load->view('commonFooter');
    }
}
