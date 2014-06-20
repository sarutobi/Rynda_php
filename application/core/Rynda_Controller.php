<?php if( !defined('BASEPATH') ) exit('No direct script access allowed');
/**
 * Файл содержит определение класса RyndaController (класс ядра CI)
 *
 * @copyright  Copyright (c) 2011 Звягинцев Л. aka Ahaenor
 * @link       /application/core/RyndaController.php
 * @version    0.1
 * @since      Файл доступен начиная с версии проекта 0.1
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/
 */

/**
 * Класс базового контроллера, изменяющий поведение стандартного для проекта Rynda.org.
 *
 * Причина создания класса: необходимость проверки версии схемы БД при загрузке каждой
 * страницы системы.
 */
class Rynda_Controller extends CI_Controller
{
    /**
     * @var object Текущий пользователь, аутентифицированный в системе.
     */
    protected $_user;
    
    /**
     * @var object Регион текущего пользователя. Значение FALSE, если регион не определён.
     */
    protected $_userRegion;

    public function __construct()
    {
        parent::__construct();

        $this->_user = $this->ion_auth->user()->row();

        $this->load->database();
        
        $this->lang->load('rynda_pages');
        $this->lang->load('rynda_auth');
        $this->lang->load('rynda_forms');
        
        // Проверка настройки web-сервера.
        // Для работы системы необходимо, чтобы у сервера был указан параметр SERVER_NAME:
        $_SERVER['SERVER_NAME'] = empty($_SERVER['SERVER_NAME']) ?
                                      php_uname('n') : $_SERVER['SERVER_NAME'];
        if(empty($_SERVER['SERVER_NAME']))
            show_error($this->lang->line('pages_missingWebServerName'));

        // Проверка актуальности используемой схемы БД:
        $dbVersionRequired = $this->config->item('db_version_required');
        $dbVersionRequiredArr = explode('.', $dbVersionRequired);
        $dbVersionUsed = $this->db->select('value')
                                  ->from('config')
                                  ->where('key', 'db_version')->get();
        if( !$dbVersionUsed || $dbVersionUsed->num_rows() == 0 )
            show_error($this->lang->line('pages_dbVersionMissing'));

        $dbVersionUsed = $dbVersionUsed->row()->value;
        $dbVersionUsedArr = explode('.', $dbVersionUsed);
        // Поле "0" - номер версии системы, поле "1" - номер изменения схемы БД
        // в рамках версии системы:
        if($dbVersionUsedArr[0] < $dbVersionRequiredArr[0]
        || ($dbVersionUsedArr[0] >= $dbVersionRequiredArr[0]
         && $dbVersionUsedArr[1] < $dbVersionRequiredArr[1])) {
            show_error( str_replace(array('%dbVersionCurrent', '%dbVersionRequired'),
                                    array($dbVersionUsed, $dbVersionRequired),
                                    $this->lang->line('pages_dbVersionOutOfDate')) );
        }
        
        // Проверка существования текущего субдомена:
        $this->load->model('Subdomains_Model', 'subdomains', TRUE);
        if( !$this->subdomains->subdomainExists() )
            redirect( site_url(uri_string()) );
        
        /** *
         * Подключение модели организаций - для формирования списка типов организаций
         * в верхнем меню (на каждой странице сайта).
         */
        $this->load->model('Organizations_Model', 'organizations', TRUE);
        /**
         * Подключение модели регионов - для формирования списка регионов, из которых юзер
         * выбирает свой (на каждой странице сайта).
         */
        $this->load->model('Regions_Model', 'regions', TRUE);
        
        if($this->input->cookie('ryndaorg_region')
        && $this->input->cookie('ryndaorg_region') != -1)
            $this->_userRegion = $this->regions->getById($this->input->cookie('ryndaorg_region'));
        else
            $this->_userRegion = FALSE;
    }

    /**
     * Получение ключа для Google API, соответствующего текущему домену. Ключи для всех
     * возможных доменов (корневого уровня) хранятся в конфигурационном файле системы.
     *
     * @return mixed Возвращается строка ключа Google API для текущего домена, если ключ
     * найден. В противном случае, возвращается false.
     */
    protected function _getGoogleApiKey()
    {
        $googleApiKeys = $this->config->item('google_api_key');
        $baseDomain = getBaseDomain();
        return empty($googleApiKeys[$baseDomain]) ?
                   FALSE : $googleApiKeys[$baseDomain];
    }

    /**
     * Send an email notice about newly created user accout with activation link.
     *
     * @param $params array Email params:
     * * 1st param - user email.
     * * 2nd - user ID.
     * * 3rd - user account activation code.
     * @return boolean TRUE if mailing succeded, FALSE otherwise.
     */
    public function _mailoutAccountActivation($params)
    {
        echo '<pre>' . print_r($params, TRUE) . '</pre>';
//        $this->email->clear();
//        $this->email->from($this->config->item('admin_email'),
//                            $this->config->item('project_basename'));
//        $this->email->to($this->input->post('loginField'));
//        $this->email->subject('Ваша новая учётная запись на '
//                                .$this->config->item('base_url'));
//        $this->email->message($this->load->view('auth/email/activate.tpl',
//                                                array('identity' => $params[0],
//                                                      'id' => $params[1],
//                                                      'activation' => $params[2],),
//                                                TRUE));
//        $this->email->send();
    }
}