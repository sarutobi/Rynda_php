<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Файл содержит определение класса Auth (контроллер служебных скриптов)
 *
 * @copyright  Copyright (c) 2011 Звягинцев Л. aka Ahaenor
 * @link       /application/controllers/auth.php
 * @version    0.1
 * @since      Файл доступен начиная с версии проекта 0.2
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/
 */

/**
 * Контроллер операций по аутентификации пользователей на сайте.
 */
class Auth extends Rynda_Controller
{
    function __construct()
	{
        parent::__construct();
        $this->load->library('ion_auth');
        $this->load->library('session');
        $this->load->library('form_validation');
        $this->load->helper('url');
        // Load MongoDB library instead of native db driver if required:
        $this->config->item('use_mongodb', 'ion_auth') ?
            $this->load->library('mongo_db') : $this->load->database();
	}

    /**
     * Индексная страница. Служит для редиректов в зав. от полномочий пользователя.
     *
     * Здесь и далее в контроллере редиректы выполняются методом location
     * (быстрее, чем refresh, но может не работать на windows-серверах).
     */
    function index()
    {
        if( !$this->ion_auth->logged_in() ) // Пользователь - гость
            redirect('auth/login'); // Редирект на страницу логина
        else if($this->ion_auth->is_admin()) // Юзер - админ
            redirect($this->config->item('admin_url')); // Редирект на админку
        else // Юзер - зарегистрированный
            redirect($this->config->item('base_url')); // Редирект на главную
    }

    /**
     * Страница аутентификации.
     */
    function login()
    {
        if($this->ion_auth->logged_in()) // Юзер уже аутентифицирован, вернуть на главную страницу
            redirect('/');
        
        $this->load->view('commonHeader',
                          array('showAuth' => FALSE,
                                'user' => $this->_user,
                                'regions' => $this->regions->getList(),
                                'userRegion' => $this->_userRegion,
                                'subdomains' => $this->subdomains->getList(array('limit' => 15,
                                                                                 'status' => 1,)),
                                'organizationTypes' => $this->organizations->getTypesList(),
                                'title' => 'Вход',
                                'showRequestButton' => TRUE,
                                'showOfferButton' => TRUE,));
        $this->load->view('auth/login', array('activationSuccess' => $this->session->flashdata('activationSuccess'),
                                              'activationError' => $this->session->flashdata('activationError'),
                                              'resetPasswordComplete' =>
                                                  $this->session->flashdata('resetPasswordComplete'),));

        $jsVars = array('LANG_LOGIN_REQUIRED' => $this->lang->line('auth_loginRequired'),
                        'LANG_LOGIN_INVALID' => $this->lang->line('auth_loginInvalid'),
                        'LANG_ACCOUNT_INACTIVE' => $this->lang->line('auth_accountInactive'),
//                        'LANG_ACCOUNT_BANNED' => $this->lang->line('auth_accountBanned'),
                        'LANG_ACCOUNT_UNKNOWN' => $this->lang->line('auth_accountUnknown'),
                        'LANG_PASSWORD_REQUIRED' => $this->lang->line('auth_passwordRequired'),
                        'LANG_PASSWORD_INVALID' => $this->lang->line('auth_passwordInvalid'),
                        /*'LANG_PASSWORD_WRONG' => $this->lang->line('auth_passwordWrong'),
                        'LANG_AUTH_FAILED' => $this->lang->line('auth_loggingInFailed'),*/);
        $this->load->view('jsVars', array('jsVars' => $jsVars));
        $this->load->view('commonFooter');
    }

    /**
     * Обработка формы аутентификации.
     */
    public function loginProcess()
    {
        $login = filter_var($this->input->post('loginField'), FILTER_VALIDATE_EMAIL);
        if($login === FALSE) // Передана не почта. Вход по логину не обрабатывается
            echo jsonResponse('error', $this->lang->line('auth_loginInvalid'));
        else if( !$this->ion_auth->email_check($this->input->post('loginField')) )
            echo jsonResponse('error', $this->lang->line('auth_accountUnknown'));
        else {
            $userIsActive = $this->db->select('active')
                                     ->from('users')
                                     ->where(array('email' => $this->input->post('loginField')))
                                     ->limit(1)->get()->row()->active;
            if( !$userIsActive )
                echo jsonResponse('error', $this->lang->line('auth_accountInactive'));
            else if( !$this->ion_auth->login($this->input->post('loginField'),
                                             $this->input->post('passwordField'),
                                             (int)$this->input->post('rememberField')) )
                echo jsonResponse('error', $this->ion_auth->errors());
            else
                echo jsonResponse('success', $this->lang->line('auth_loggingInSucceeded'));
        }
    }

    /**
     * Выход из системы
     */
    function logout()
    {
        $this->ion_auth->logout();
    }

    /**
     * Обработка смены пользователем пароля для своего аккаунта.
     */
    function changePasswordProcess()
    {
        if($this->ion_auth->change_password($this->_user->email, $this->input->post('oldPass'), $this->input->post('newPass')))
            die( jsonResponse('success', $this->lang->line('auth_changePasswordSucceeded')) );
        else
            die( jsonResponse('error', $this->ion_auth->errors()) );
    }

    /**
     * Страница сброса пароля.
     */
    public function forgotPassword()
    {
        if($this->ion_auth->logged_in()) // Юзер уже аутентифицирован, вернуть на главную страницу
            redirect('/');
        
        $this->load->view('commonHeader',
                          array('showAuth' => TRUE,
                                'user' => $this->_user,
                                'regions' => $this->regions->getList(),
                                'userRegion' => $this->_userRegion,
                                'subdomains' => $this->subdomains->getList(array('limit' => 15,
                                                                                 'status' => 1,)),
                                'organizationTypes' => $this->organizations->getTypesList(),
                                'title' => 'Пароль забыт',
                                'showRequestButton' => TRUE,
                                'showOfferButton' => TRUE,));
        $this->load->view('auth/forgotPassword');

        $jsVars = array('CONST_COOKIE_DOMAIN' => $this->config->item('cookie_domain'),
                        'CONST_REGION_SERVICE_URL' => $this->config->item('region_geolocation_url'),
                        'LANG_LOGIN_REQUIRED' => $this->lang->line('auth_registerEmailRequired'),
                        'LANG_LOGIN_INVALID'  => $this->lang->line('auth_loginInvalid'),);
        $this->load->view('jsVars', array('jsVars' => $jsVars));
        $this->load->view('commonFooter');
    }

    /**
     * Обработка формы сброса пароля.
     */
    public function forgotPasswordProcess()
    {
        if($this->ion_auth->logged_in()) // Юзер уже аутентифицирован, вернуть на главную страницу
            redirect('/');
        if( !$_POST ) // Только ajax-запрос
            redirect('/');

        $email = $this->input->post('loginField');
        if( !$this->ion_auth->email_check($email) )
            die( jsonResponse('error', $this->lang->line('auth_accountUnknown')) );

        // Preparing pass to reset. Creating forgotten pass code and sending email
        // to user:
        if($this->ion_auth->forgotten_password($email)) {
            // Check the domain of the user email for security:
            $loginEmailDomain = explode('@', $email);
            $loginEmailDomain = $loginEmailDomain[1];

            if($loginEmailDomain == $this->config->item('system_email_domain')) {
                /** * @todo Решить, что делать в этом случае. */
//                    die( jsonResponse('error', $this->lang->line('auth_inappropriateEmail')) );
            }

            $user = $this->db->from('users')
                             ->where(array('email' => $email,
                                           'active' => '1'))->get()->row();
            if($user) {
                // Email to notice the user of his new pass:
                $this->email->clear();
                $this->email->initialize(array('mailtype' => 'html'));
                $this->email->from($this->config->item('admin_email'),
                                   $this->config->item('project_basename'));
                $this->email->to($email);
                $this->email->subject('Восстановление пароля для вашей учётной записи на '
                                     .$this->config->item('base_url'));
                $this->email->message($this->load->view('auth/email/forgotPassword',
                                                        array('userFirstName' =>
                                                                  $user->first_name,
                                                              'forgottenPasswordCode' =>
                                                                  $user->forgotten_password_code,),
                                                        TRUE));
                $this->email->send();
                die( jsonResponse('success', $this->lang->line('auth_forgotPasswordSucceded')) );
            } else { // // Send error report
                $errorMessages = "Some error occures while user password resetting. User data: \n".print_r($user, TRUE);
                $this->email->from($this->config->item('admin_email'), $this->config->item('project_basename'));
                $this->email->to($this->config->item('admin_email'));
                $this->email->subject($this->config->item('base_url').' - error in user registration while creating the message');
                $this->email->message($errorMessages);
                $this->email->send();

                log_message('error', $errorMessages);
                die( jsonResponse('error', $this->lang->line('auth_forgotPasswordFailed')) );
            }
        } else
            die( jsonResponse('error', $this->lang->line('auth_forgotPasswordFailed')) );
    }

    /**
     * Завершение операции сброса пароля - изменение пароля в БД.
     * 
     * @param $code string Псевдослучайная строка - код подтверждения сброса пароля.
     */
    public function resetPassword($code)
    {
        $user = $this->db->from('users')
                         ->where('forgotten_password_code', $code)->get()->row();
        $userNewPassword = $this->ion_auth->forgotten_password_complete($code);
        if($userNewPassword && $user) {
            // Email to notice the user of his new pass:
            $this->email->clear();
            $this->email->initialize(array('mailtype' => 'html'));
            $this->email->from($this->config->item('admin_email'),
                               $this->config->item('project_basename'));
            $this->email->to($user->email);
            $this->email->subject('Новый пароль для учётной записи на '
                                 .$this->config->item('base_url'));
            $this->email->message($this->load->view('auth/email/newPassword',
                                                    array('userFirstName' =>
                                                              $user->first_name,
                                                          'newPassword' =>
                                                              $userNewPassword['new_password'],),
                                                    TRUE));
            $this->email->send();
            $this->session->set_flashdata('resetPasswordComplete',
                                          $this->lang->line('auth_resetPasswordSucceded'));
            redirect('auth/login');
        } else {
            $this->session->set_flashdata('resetPasswordError', $this->ion_auth->errors());
            redirect('forgot');
        }
    }

    /**
     * Активация учётной записи пользователя.
     * 
     * @param $userId integer ID учётной записи, которую необходимо активировать.
     * @param $code string Код активации.
     */
    function activate($userId, $code)
    {
        $userData = $this->db->query('SELECT active, email FROM users WHERE id = ?',
                                     array($userId));
        if( !$userData->num_rows() )
            show_error($this->lang->line('auth_accountUnknown'));
        else if($userData->row()->active) {
            // Для вывода сообщения об ошибке активации:
            $this->session->set_flashdata('activationError', $this->lang->line('auth_accountAlreadyActive'));
            redirect('/auth/login');
        }

        if($this->ion_auth->activate($userId, $code)) {
            // Для вывода сообщения об успешной активации:
            $this->session->set_flashdata('activationSuccess', $this->lang->line('auth_activationSucceded'));

            $this->load->library('email');
            $this->email->initialize(array('charset' => 'utf-8',
                                           'mailtype' => 'html',));
            $this->email->to($userData->row()->email);
            $this->email->from($this->config->item('mailout_email_from'),
                               $this->config->item('project_basename'));
            $this->email->subject($this->lang->line('auth_thnx4regMailTitle'));
            $this->email->message($this->load->view('auth/email/thnx4reg', array(), TRUE));    
            $this->email->send();

            redirect('/auth/login');
        } else
            show_error($this->ion_auth->errors());
    }

    /**
     * Страница регистрации пользователя в системе.
     */
    function register()
    {
        if($this->ion_auth->logged_in()) // Юзер уже аутентифицирован, вернуть на главную страницу
            redirect('/');
        
        $this->load->view('commonHeader',
                          array('showAuth' => FALSE,
                                'user' => $this->_user,
                                'regions' => $this->regions->getList(),
                                'userRegion' => $this->_userRegion,
                                'subdomains' => $this->subdomains->getList(array('limit' => 15,
                                                                                 'status' => 1,)),
                                'title' => 'Регистрация',
                                'organizationTypes' => $this->organizations->getTypesList(),
                                'showRequestButton' => TRUE,
                                'showOfferButton' => TRUE,));
        $this->load->view('auth/register');
        
        $this->config->load('ion_auth', TRUE);
        

        $jsVars = array('LANG_PASSWORD_SHORT' => $this->lang->line('auth_passwordTooShort'),
                        'LANG_PASSWORD_LONG' => $this->lang->line('auth_passwordTooLong'),
                        'LANG_PASSWORD_CONFIRM_MISMATCH' => $this->lang->line('auth_passwordConfirmMismatch'),
                        'LANG_FIRST_NAME_REQUIRED' => $this->lang->line('auth_firstNameRequired'),
                        'LANG_FIRST_NAME_INVALID' => $this->lang->line('auth_firstNameInvalid'),
                        'LANG_LAST_NAME_REQUIRED' => $this->lang->line('auth_lastNameRequired'),
                        'LANG_LAST_NAME_INVALID' => $this->lang->line('auth_lastNameInvalid'),
                        'LANG_LOGIN_REQUIRED' => $this->lang->line('auth_registerEmailRequired'),
                        'LANG_LOGIN_INVALID' => $this->lang->line('auth_loginInvalid'),
//                        'LANG_' => $this->lang->line('auth_'),
                        'CONST_PASSWORD_MIN_LENGTH' => $this->config->item('min_password_length', 'ion_auth'),
                        'CONST_PASSWORD_MAX_LENGTH' => $this->config->item('max_password_length', 'ion_auth'),);
        $this->load->view('jsVars', array('jsVars' => $jsVars));
        $this->load->view('commonFooter');
    }
    
    /**
     * Обработка формы регистрации.
     */
    function registerProcess()
    {
        if($this->ion_auth->logged_in())
            jsonResponse('error', 'Вы уже вошли на сайт!');

        if( !$this->input->post('loginField') )
            die(jsonResponse('error', $this->lang->line('auth_registerEmailRequired')));
        else {
            $loginEmailDomain = explode('@', $this->input->post('loginField'));
            $loginEmailDomain = $loginEmailDomain[1];

            if($loginEmailDomain == $this->config->item('system_email_domain'))
                die( jsonResponse('error', $this->lang->line('auth_inappropriateEmail')) );
            else if( $this->ion_auth->email_check($this->input->post('loginField')) )
                die( jsonResponse('error', $this->lang->line('auth_accountExists')) );
            else {
                $user = $this->ion_auth->register('',
                                                  $this->input->post('passwordField'),
                                                  $this->input->post('loginField'),
                                                  array('first_name' =>
                                                            $this->input->post('firstNameField'),
                                                        'last_name' =>
                                                            $this->input->post('lastNameField')));

                if( !$user )
                    die(jsonResponse('error', $this->lang->line('auth_registerError')));
                else {
                    // Email to tell the user to activate his account:
                    $this->email->clear();
                    $this->email->from($this->config->item('admin_email'),
                                       $this->config->item('project_basename'));
                    $this->email->to($this->input->post('loginField'));
                    $this->email->subject('Ваша новая учётная запись на '
                                         .$this->config->item('base_url'));
                    $this->email->message($this->load->view('auth/email/activate',
                                                            array('activation' =>
                                                                      $user['activation'],
                                                                  'userId' => $user['id'],
                                                                  'userFirstName' =>
                                                                      $this->input->post('firstNameField'),),
                                                            TRUE));
                    $this->email->send();

                    die(jsonResponse('success',
                                    str_replace('%emailService',
                                                end(explode('@', $this->input->post('loginField'))),
                                                $this->lang->line('auth_registerSucceeded'))));
                }
            }
        }
    }

//    function _get_csrf_nonce()
//    {
//        $this->load->helper('string');
//        $key = random_string('alnum', 8);
//        $value = random_string('alnum', 20);
//        $this->session->set_flashdata('csrfkey', $key);
//        $this->session->set_flashdata('csrfvalue', $value);
//
//        return array($key => $value);
//    }
//
//    function _valid_csrf_nonce()
//    {
//        if($this->input->post($this->session->flashdata('csrfkey')) !== FALSE
//        && $this->input->post($this->session->flashdata('csrfkey')) == $this->session->flashdata('csrfvalue'))
//            return TRUE;
//        else
//            return FALSE;
//    }
}
