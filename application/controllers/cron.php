<?php if( !defined('BASEPATH') ) exit('No direct script access allowed');
/**
 * Файл содержит определение класса Cron (контроллер служебных скриптов)
 *
 * @link       /application/controllers/cron.php
 * @since      Файл доступен начиная с версии проекта 0.7
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/
 */

/**
 * Контроллер скриптов, выполняющих задачи для cron.
 */
class Cron extends Rynda_Controller
{
    /**
     * Конструктор - запрет на доступ из браузера
     */
    public function __construct()
    {
        parent::__construct();
        if( !$this->input->is_cli_request() )
            show_error($this->lang->line('pages_infoPageNotFound'), 404);
    }

    /**
     * Рассылка уведомлений о наличии просьб о помощи, подходящих к региону
     * пользователей.
     *
     * @param $print boolean Если TRUE, печатать письмо в стандартный вывод. Иначе,
     * отсылать его на почту, как и должно быть по идее. По умолчанию FALSE.
    */
    public function mailoutRegionRequests($print = FALSE)
    {
        $res = $this->db->select('users.id,  volunteer_profile_view.region_id,
                                  volunteer_profile_view.region_name')
                        ->from('users')
                        ->join('volunteer_profile_view',
                               'users.id = volunteer_profile_view.user_id')
                        ->where('users.active', 1)
                        ->where('volunteer_profile_view.vp_active', 1)->get()->result();
        $usersToMail = array();
        foreach($res as $user) {
            $usersToMail[$user->id][] = array('id' => $user->region_id,
                                              'name' => $user->region_name);
        }

        $this->load->model('Messages_Model', 'messages', TRUE);
        $this->load->helper('views');
        foreach($usersToMail as $userId => &$userRegions) {
            $haveMessagesToSend = FALSE;
            foreach($userRegions as &$region) {
                $region['messages'] =
                    $this->messages->getList(array('dateAddedFrom' => strtotime('-1 day'),
                                                   'isActive' => TRUE,
                                                   'statusId' => array(MESSAGE_STATUS_MODERATED,
                                                                       MESSAGE_STATUS_VERIFIED,
                                                                       MESSAGE_STATUS_REACTION, 
                                                                       MESSAGE_STATUS_REACTED),
                                                   'type' => 1,
                                                   'regionId' => $region['id']));
                if( !empty($region['messages']) )
                    $haveMessagesToSend = TRUE;
            }

            $userContacts = $this->db->select('first_name, email')
                                     ->from('users')
                                     ->where('id', $userId)->get()->row();
            if($haveMessagesToSend) {
                if($print) {
                    /**
                    * Режим для отладки шаблона письма (вывод шаблона в stdout).
                    */
                    $this->output->set_header('Content-type: text/html; charset=utf-8');
                    $this->load->view('email/requestsRelevantToRegion',
                                    array('userFirstName' => $userContacts->first_name,
                                            'regionsData' => $userRegions));
                    /**
                    * Режим отладки шаблона письма завершён.
                    */
                } else {
                    $this->email->to($userContacts->email);
                    $this->email->from($this->config->item('mailout_email_from'),
                                    $this->config->item('project_basename'));
                    $this->email->subject('Просьбы о помощи по регионам');
                    $this->email->message($this->load->view('email/requestsRelevantToRegion',
                                                            array('userFirstName' => $userContacts->first_name,
                                                                  'regionsData' => $userRegions), TRUE));
                    $this->email->send();
                    //echo $this->email->print_debugger(). '<br/>';
                    $this->email->clear();
                }
            }
        }
    }

    /**
     * Рассылка уведомлений о наличии просьб о помощи, подходящих к профилям волонтёрства
     * пользователей.
     * 
     * @param $print boolean Если TRUE, печатать письмо в стандартный вывод. Иначе,
     * отсылать его на почту, как и должно быть по идее. По умолчанию FALSE.
     */
    function mailoutVpRelevantRequests($print = FALSE)
    {
        $this->load->model('Volunteer_Profiles_Model', 'profiles', TRUE);
        $this->load->model('Locations_Model', 'locations', TRUE);

        $this->load->library('email');

        $this->email->initialize(array('charset' => 'utf-8',
                                       'mailtype' => 'html',));

        $profiles = $this->profiles->getListForMailout('email');

        $mailText = '';
        $users = array();
        $sentMessageIds = array();
        foreach($profiles as $i => $profile) {
            /**
             * флаг для проверки, есть ли в этом профиле сообщения, которых не было в предыдущем, 
             * т.е. которые остались после удаления дубликатов
             */
            $profileIsEmpty = false;
          
            $profile['messages'] = $this->profiles->getRelevantMessages($profile['id'], 5);

            if($profile['messages']) {
                foreach($profile['messages'] as $j => &$message) {
                    // было ли уже послано это сообщение?
                    if(in_array($message['message_id'], $sentMessageIds)){
                        unset($profile['messages'][$j]);
                        $profileIsEmpty = true;
                        continue;
                    } else {
                        $sentMessageIds[] = $message['message_id'];
                        $message['distanceToVp'] = $this->locations->getDistance($profile['locationId'],
                            $message['location_id']);
                        $profileIsEmpty = false;
                    }
                    
                }

                if( empty($users[$profile['userId']]) )
                    $users[$profile['userId']] = $this->ion_auth->user($profile['userId'])->row();

                if(!$profileIsEmpty) {
                    $mailText .= $this->load->view('email/_partialRelevantToVp', 
                                               array('user' => $users[$profile['userId']],
                                                     'profileId' => $profile['id'],
                                                     'profileTitle' => $profile['title'],
                                                     'email' => $profile['email'],
                                                     'messages' => $profile['messages']),
                                               TRUE);
                }
            }

            if( ($i+1 == count($profiles) || $profiles[$i+1]['email'] != $profile['email'])
             && $mailText) {
                if($print) {
                    /**
                     * Режим для отладки шаблона письма (вывод шаблона в stdout).
                     */
                    $this->output->set_header('Content-type: text/html; charset=utf-8');
                    $this->load->view('email/requestsRelevantToVp', array('mailText' => $mailText));
                    $mailText = '';
                    /**
                     * Режим отладки шаблона письма завершён.
                     */
                } else {
                    $this->email->to($profile['email']);
                    $this->email->from($this->config->item('mailout_email_from'),
                                       $this->config->item('project_basename'));
                    $this->email->subject($this->config->item('volunteer_profiles_email_subject'));
                    $mailText = $this->load->view('email/requestsRelevantToVp',
                                                  array('mailText' => $mailText), TRUE);
                    $this->email->message($mailText);    
                    $this->email->send();
                    //echo $this->email->print_debugger(). '<br/>';
                    $mailText = '';
                    $this->email->clear();
                }
                $sentMessageIds = array();
            }
        }
    }
    
    /**
     * Remove all registered, but inactivated user accounts, if they were created later
     * than 3 days ago.
     */
    public function deleteNonActivatedUsers()
    {
        $this->db->where('active', 0)
                 ->where('created_on <= ', strtotime('-3 days'))
                 ->where("(activation_code IS NOT NULL OR activation_code != '')");
        if( !$this->db->delete('users') ) {
            // Send notice to admins, maybe...
            log_message('error', 'WARNING: some trouble while removing non-activated user accounts');
        }
    }
}