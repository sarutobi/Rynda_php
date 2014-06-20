<?php if( !defined('BASEPATH') ) exit('No direct script access allowed');
/**
 * Файл содержит определение класса User (контроллер страниц)
 *
 * @copyright  Copyright (c) 2011 Звягинцев Л. aka Ahaenor
 * @link       /application/controllers/user.php
 * @version    0.1
 * @since      Файл доступен начиная с версии проекта 0.2
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/
 */

/**
 * Контроллер страниц для работы с данными пользователей системы.
 */
class User extends Rynda_Controller
{
    /**
     * Конструктор - подключение языкового файла для локализации сообщений на страницах.
     */
    public function __construct()
    {
        parent::__construct();
        $this->lang->load('rynda_forms');
    }

    public function index()
    {
        if($this->ion_auth->logged_in())
            redirect("/user/{$this->_user->id}");
        else
            redirect('/auth/login');
    }

    /**
     * Страница профиля пользователя.
     *
     * @param $id integer ID учётной записи пользователя, чья страница выводится.
     */
    public function personal($id = FALSE)
    {
        $id = (int)$id;
        if( !$id )
            redirect('/auth/login');

        $user = $this->ion_auth->user($id)->row();

        $this->load->library('Rynda_user', array('user' => $user));

        // Header's included in all cases:
        $this->load->view('commonHeader',
                          array('showAuth' => TRUE,
                                'user' => $this->_user,
                                'loginHrefOff' => empty($this->_user) ?
                                                      FALSE :
                                                      ($this->_user->id == $id),
                                'regions' => $this->regions->getList(),
                                'userRegion' => $this->_userRegion,
                                'subdomains' => $this->subdomains->getList(array('limit' => 15,
                                                                                 'status' => 1,)),
                                'title' => 'Страница пользователя '
                                          .$this->rynda_user->getReference($user),
                                'organizationTypes' => $this->organizations->getTypesList(),
                                'showRequestButton' => TRUE,
                                'showOfferButton' => TRUE,));

        if($id <= 0) {
            $this->load->view('commonError',
                              array('errorTitle' => 'Неизвестный пользователь',
                                    'errorText' => $this->lang->line('pages_userNotFound'),));
            $this->load->view('commonFooter');
            return;
        }

        if( !$user || !$user->active ) {
            $this->load->view('commonError',
                              array('errorTitle' => 'Неизвестный пользователь',
                                    'errorText' => $this->lang->line('pages_userNotFound'),));
            $this->load->view('commonFooter');
            return;
        }

        if((empty($this->_user) || $id != $this->_user->id) && $this->rynda_user->isPrivate($user)) {
            $this->load->view('commonError',
                              array('errorTitle' => 'Информация пользователя скрыта',
                                    'errorText' => $this->lang->line('pages_userDataIsPrivate'),));
            $this->load->view('commonFooter');
            return;
        }

        $this->load->model('Volunteer_Profiles_Model', 'profiles', TRUE);
//        $this->load->model('Users_Model', 'user', TRUE);
        $this->load->model('Media_Model', 'media', TRUE);
        $this->load->model('Social_Net_Model', 'socNets', TRUE);

        $this->lang->load('rynda_forms');
//        $this->lang->load('calendar');


        $isOwner = $this->_user ? ($id == $this->_user->id) : FALSE;
        $this->load->view('userPersonal',
                          array('user' => $user,
                                'isOwner' => $isOwner,
                                'avatar' => $this->media->getById($user->my_photo),
                                'messageTypeRequest' => 'request',
                                'messageTypeOffer' => 'offer',
                                'socNetProfiles' =>
                                    $this->socNets->getUserSocNetProfiles($user->id),
                                'volunteerProfiles' =>
                                    $this->profiles->getList(array('userId' => $user->id)),));
        $this->load->view('jsVars',
                          array('jsVars' => array('CONST_USER_ID' => $id,
                                                  'CONST_MESSAGE_STATUS_RECEIVED' =>
                                                      MESSAGE_STATUS_RECEIVED,
                                                  'CONST_MESSAGE_STATUS_MODERATED' =>
                                                      MESSAGE_STATUS_MODERATED,
                                                  'CONST_MESSAGE_STATUS_VERIFIED' =>
                                                      MESSAGE_STATUS_VERIFIED,
                                                  'CONST_MESSAGE_STATUS_REACTION' =>
                                                      MESSAGE_STATUS_REACTION,
                                                  'CONST_MESSAGE_STATUS_REACTED' =>
                                                      MESSAGE_STATUS_REACTED,
                                                  'CONST_MESSAGE_STATUS_CLOSED' => 
                                                      MESSAGE_STATUS_CLOSED,
                                                  'LANG_MESSAGES_NOT_FOUND' =>
                                                      $this->lang->line('forms_searchMessagesNotFound'),
                                                  'CONST_COOKIE_DOMAIN' =>
                                                      $this->config->item('cookie_domain'),
                                                  'CONST_USER_REGION_ID' =>
                                                      $this->input->cookie('ryndaorg_region'),
                                                  'CONST_REGION_SERVICE_URL' =>
                                                      $this->config->item('region_geolocation_url'),)));
        $this->load->view('commonFooter');
    }

    /**
     * Страница редактирования профиля пользователя.
     *
     * @param $id integer ID учётной записи пользователя, чей профиль редактируется.
     */
    public function edit($id = FALSE)
    {
        $id = (int)$id;
        if( !$id || !$this->ion_auth->logged_in() )
            redirect('/auth/login');

        if($id <= 0)
            show_error($this->lang->line('pages_userNotFound'), 404);
        if($id != $this->_user->id)
            show_error($this->lang->line('pages_infoPageNotFound'), 404);

        $user = $this->ion_auth->user($id)->row();
        if( !$user || !$user->active )
            show_error($this->lang->line('pages_userNotFound'), 404);

        $this->load->model('Categories_Model', 'categories', TRUE);
        $this->load->model('Volunteer_Profiles_Model', 'profiles', TRUE);
        $this->load->model('Media_Model', 'media', TRUE);
        $this->load->model('Social_Net_Model', 'socNets', TRUE);

        $this->load->library('Rynda_user', array('user' => $user));

        $this->lang->load('rynda_views');

        $this->load->view('commonHeader',
                          array('showAuth' => TRUE,
                                'user' => $this->_user,
                                'regions' => $this->regions->getList(),
                                'userRegion' => $this->_userRegion,
                                'subdomains' => $this->subdomains->getList(array('limit' => 15,
                                                                                 'status' => 1,)),
                                'title' => 'Редактирование учётной записи '
                                          .$this->rynda_user->getReference($user),
                                'organizationTypes' => $this->organizations->getTypesList(),
                                'showRequestButton' => TRUE,
                                'showOfferButton' => TRUE,));
        $socNetProfiles = array();
        foreach($this->socNets->getUserSocNetProfiles($user->id) as $socProfile) {
            $socNetProfiles[$socProfile['socNetId']] = $socProfile;
        }
        $this->load->view('userPersonalEdit',
                          array('user' => $user,
                                'avatar' => $this->media->getById($user->my_photo),
                                'categories' => $this->categories->getChilds(0, NULL),
                                'socNets' => $this->socNets->getList(),
                                'socNetProfiles' => $socNetProfiles,
                                'volunteerProfiles' =>
                                    $this->profiles->getList(array('userId' => $user->id)),));
        $this->load->view('jsVars',
                          array('jsVars' => array('CONST_USER_ID' => $id,
                                                  'CONST_COOKIE_DOMAIN' =>
                                                      $this->config->item('cookie_domain'),
                                                  'CONST_REGION_SERVICE_URL' =>
                                                      $this->config->item('region_geolocation_url'),
                                                  'CONST_PASSWORD_MIN_LENGTH' =>
                                                      $this->config->item('min_password_length', 'ion_auth'),
                                                  'CONST_PASSWORD_MAX_LENGTH' =>
                                                      $this->config->item('max_password_length', 'ion_auth'),
                                                  'CONST_VP_MAX' =>
                                                      $this->config->item('volunteer_profiles_max'),
                                                  'CONST_MEDIA_IS_TYPE_AVATAR' => MEDIA_IS_TYPE_AVATAR,
                                                  'LANG_PASSWORD_REQUIRED' =>
                                                      $this->config->item('auth_passwordRequired'),
                                                  'LANG_PASSWORD_SHORT' =>
                                                      $this->lang->line('auth_passwordTooShort'),
                                                  'LANG_PASSWORD_LONG' =>
                                                      $this->lang->line('auth_passwordTooLong'),
                                                  'LANG_PASSWORD_CONFIRM_MISMATCH' =>
                                                      $this->lang->line('auth_passwordConfirmMismatch'),
                                                  'LANG_PASSWORD_NOT_CHANGED' =>
                                                      $this->lang->line('auth_passwordNotChanged'),
                                                  'LANG_FIRST_NAME_REQUIRED' =>
                                                      $this->lang->line('auth_firstNameRequired'),
                                                  'LANG_FIRST_NAME_INVALID' =>
                                                      $this->lang->line('auth_firstNameInvalid'),
                                                  'LANG_LAST_NAME_REQUIRED' =>
                                                      $this->lang->line('auth_lastNameRequired'),
                                                  'LANG_LAST_NAME_INVALID' =>
                                                      $this->lang->line('auth_lastNameInvalid'),
                                                  'LANG_LOGIN_REQUIRED' =>
                                                      $this->lang->line('auth_registerEmailRequired'),
                                                  'LANG_LOGIN_INVALID' =>
                                                      $this->lang->line('auth_loginInvalid'),
                                                  'LANG_FIRST_NAME_REQUIRED' =>
                                                      $this->lang->line('forms_firstNameRequired'),
                                                  'LANG_FIRST_NAME_INVALID' =>
                                                      $this->lang->line('forms_firstNameInvalid'),
                                                  'LANG_LAST_NAME_REQUIRED' =>
                                                      $this->lang->line('forms_lastNameRequired'),
                                                  'LANG_LAST_NAME_INVALID' =>
                                                      $this->lang->line('forms_lastNameInvalid'),
                                                  'LANG_VP_TITLE_REQUIRED' => 
                                                      $this->lang->line('forms_vpTitleRequired'),
                                                  'LANG_PHONE_INVALID' =>
                                                      $this->lang->line('forms_phoneInvalid'),
                                                  'LANG_EMAIL_INVALID' =>
                                                      $this->lang->line('forms_emailInvalid'),
                                                  'LANG_AJAX_ERROR' =>
                                                      $this->lang->line('forms_ajaxProcessError'),
                                                  'LANG_ADDR_FOUND' =>
                                                      $this->lang->line('forms_addrGeolocateSuccess'),
                                                  'LANG_ADDR_NOT_FOUND' =>
                                                      $this->lang->line('forms_addrGeolocateFailure'),
                                                  'LANG_FIRST_NAME_REQUIRED' =>
                                                      $this->lang->line('forms_firstNameRequired'),
                                                  'LANG_FIRST_NAME_INVALID' =>
                                                      $this->lang->line('forms_firstNameInvalid'),
                                                  'LANG_LAST_NAME_INVALID' =>
                                                      $this->lang->line('forms_lastNameInvalid'),
                                                  'LANG_PHONE_INVALID' =>
                                                      $this->lang->line('forms_phoneInvalid'),
                                                  'LANG_EMAIL_INVALID' =>
                                                      $this->lang->line('forms_emailInvalid'),
                                                  'LANG_LOCATION_REQUIRED' =>
                                                      $this->lang->line('forms_locationRequired'),
                                                  'LANG_CATEGORY_REQUIRED' =>
                                                      $this->lang->line('forms_categoryRequired'),
                                                  'LANG_AIDING_DISTANCE_REQUIRED' =>
                                                      $this->lang->line('forms_aidingDistRequired'),
                                                  'LANG_HELP_DAYS_REQUIRED' =>
                                                      $this->lang->line('forms_helpDaysRequired'),
                                                  'LANG_AIDING_DISTANCE_MIN_LABEL' =>
                                                      $this->lang->line('forms_aidingDistMinLabel'),
                                                  'LANG_AIDING_DISTANCE_LABEL' =>
                                                      $this->lang->line('forms_aidingDistLabel'),
                                                  'LANG_AIDING_DISTANCE_MAX_LABEL' =>
                                                      $this->lang->line('forms_aidingDistMaxLabel'),
                                                  'LANG_EDIT_PROFILE_SUCCESS' =>
                                                      $this->lang->line('forms_editVpSuccess'),)));
        $this->load->view('commonFooter');
    }
}