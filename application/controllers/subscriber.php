<?php if( !defined('BASEPATH') ) exit('No direct script access allowed');

/**
 * Контроллер для работы с подпиской на комментарии
 */
class Subscriber extends Rynda_Controller
{
    /**
     * Конструктор - подключение языкового файла для локализации сообщений на страницах.
     */
    public function __construct()
    {
        parent::__construct();
        $this->lang->load('rynda_forms');
    }
    
    public function addSubscriberProcess($subscribeType = 0)
    {
        if(!$subscribeTo = $this->input->post('subscribeTo')){
           return false;
        }
        $this->load->model('Subscriber_Model', 'subscriber', TRUE);
        
        $email = $this->input->post('email')? $this->input->post('email'): '';
        $name = $this->input->post('name')? $this->input->post('name'): '';
        $userId = $this->input->post('userId')? $this->input->post('userId'): null;
        echo $subscribeId = $this->subscriber->add(
                  $subscribeType,
                  $subscribeTo,
                  $name,
                  $email,
                  $userId
        );
    }
    
    public function unsubscribeProcess()
    {
        $this->load->model('Subscriber_Model', 'subscriber', TRUE);
        if($token = $this->input->get('token')?$this->input->get('token'):false){
            $this->subscriber->unsubscribe($token, $this->input->get('subscriberId'));
        } else {
            $this->subscriber->unsubscribe($this->input->post('userId'), $this->input->post('messageId'));
        }
    }    
    
}
