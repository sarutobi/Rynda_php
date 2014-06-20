<?php
/**
 * Файл содержит определение класса Rynda_Output_Ushahidi (класс библиотеки менеджеров
 * вывода, предназначенных для интеграции платформы Rynda.org с другими системами).
 *
 * @copyright  Copyright (c) 2011 Звягинцев Л. aka Ahaenor
 * @link       /application/libraries/Rynda_output_ushahidi.php
 * @version    0.1
 * @since      Файл доступен начиная с версии проекта 0.5
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/
 */

require_once APPPATH.'libraries/Rynda_output.php';

/**
 * Класс библиотеки для интеграции платформы Rynda.org с сайтом на базе платформы Ushahidi.
 */
class Rynda_Output_Ushahidi extends Rynda_Output
{
    public function __construct()
    {
        parent::__construct();
        $this->_manager = reset( $this->_ci->partners->getManagersList(array('name' => 'Ushahidi',
                                                                             'type' => 0,)) ); // Тип менеджера - вывод
        if(empty($this->_manager))
            return FALSE;
    }

    public function getParams()
    {
        // ..
        return array();
    }

    public function pushMessage($messageId /*, $partnerId = FALSE*/)
    {
        $messageId = (int)$messageId;
        if( !$messageId )
            return FALSE;

        $this->_ci->load->model('Messages_Model', 'messages', TRUE);
        $message = $this->_ci->messages->getById($messageId);
        if( !$message )
            return FALSE;

//        if($partnerId) ...

        foreach((array)$this->_ci->partners->getList() as $partner) {
            // Параметры работы менеджера применимо к данной партнёрской системе:
            if( empty($this->_params[$partner['id']]) )
                $this->_params[$partner['id']] = $this->_ci->partners->getParamsValues($partner['id'],
                                                                                       $this->_manager['id']);
            $ushahidiReport = array('task' => 'report',
                                    'incident_title' => $message['title'],
                                    'incident_description' => $message['text'],
                                    'incident_date' => date('m', $message['dateAdded']).'/'.
                                                       date('d', $message['dateAdded']).'/'.
                                                       date('Y', $message['dateAdded']),
                                    'incident_hour' => date('g', $message['dateAdded']),
                                    'incident_minute' => date('i', $message['dateAdded']),
                                    'incident_ampm' => date('a', $message['dateAdded']),
                                    'latitude' => $message['lat'],
                                    'longitude' => $message['lng'],
                                    'location_name' => $message['address'],
                                    'incident_category' => 0,
                                    'person_first' => $message['isPublic'] ? $message['firstName'] : '',
                                    'person_last' => $message['isPublic'] ? $message['lastName'] : '',
                                    'person_email' => $message['isPublic'] ? $message['email'] : '',
                                    'resp' => 'json');
            /**
             * @todo Возможно, плейсхолдеры ФИ и мэйла для скрытых юзеров стоит сделать
             * параметрами менеджера.
             */
            $ch = curl_init();
            curl_setopt_array($ch,
                              array(CURLOPT_POST => 1,
                                    CURLOPT_HEADER => 0,
                                    CURLOPT_URL => $this->_params[$partner['id']]['apiUrl'],
                                    CURLOPT_FRESH_CONNECT => 1,
                                    CURLOPT_RETURNTRANSFER => 1,
                                    CURLOPT_FORBID_REUSE => 1,
                                    CURLOPT_TIMEOUT => 4,
                                    CURLOPT_POSTFIELDS => $ushahidiReport,));
            if( !$result = curl_exec($ch) )
                trigger_error(curl_error($ch));
            else {
                $result = json_decode($result);
                if($result->error->code) {
//                    echo '<pre>Failed: ' . print_r($result, TRUE) . '</pre>';
                } else {
//                    echo '<pre>Success: ' . print_r($result, TRUE) . '</pre>';
                }
            }
            curl_close($ch);
        }
    }
}
