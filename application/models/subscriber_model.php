<?php if( !defined('BASEPATH') ) exit('No direct script access allowed');
/**
 * Файл содержит определение класса Subscriber_Model (модель данных)
 *
 * @link       /application/models/subscriber_model.php
 * @version    0.1
 * @since      Файл доступен начиная с версии проекта 0.5
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/
 */

/**
 * Модель для работы с комментариями на сайте.
 */
class Subscriber_Model extends Rynda_Model
{
    /**
     * Для удобства обращения с кодами ошибок модели можно определить их как
     * константы класса модели:
     */
    const ERROR_INSERTING_COMMENT = 1;
    const ERROR_NEED_EMAIL_OR_USER_ID = 2;

    public function __construct()
    {
        parent::__construct();
        self::$_errorTypes = array(self::ERROR_INSERTING_COMMENT =>
                                       $this->lang->line('modelComments_errorCommentInsertFailed'),
                                   self::ERROR_NEED_EMAIL_OR_USER_ID =>
                                       $this->lang->line('modelSubscriber_errorNeedEmail_orUser_id'),
                             );
    }
    
    /**
     * Метод для создания подписки на комментари к сообщениям
     * @param integer $subscribeType тип подписки - 0 на сообщение или 1 - на комментарий
     * @param integer $subscribeTo id комментария или сообщения, в зависимости от поля subscribe_type
     * @param string $email адрес подписки
     * @param integer $user_id пользователь. незарегистрированный пользователь - null
     */
    public function add($subscribeType, $subscribeTo, $email = '', $name = '', $userId = null)
    {
        $this->_clearErrors();
        
        if($email == '' && is_null($userId)) {
            $this->_addError(self::ERROR_NEED_EMAIL_OR_USER_ID,
                             "Subscriber_Model get no email and no user_id");
            return FALSE;          
        } elseif($email == '' && !is_null($userId) && $currUser = $this->ion_auth->get_user()) {
          $email = $currUser->email;
          $name = $currUser->first_name. ' '. $currUser->last_name;
        }
        
        $token = md5($subscribeType.$subscribeTo.$email.$name.rand(0, 5000));

        $this->db->trans_start();

        $this->db->insert('subscriber', array('subscribe_type' => $subscribeType,
                                               'email' => $email,
                                               'user_id' => $userId,
                                               'subscribe_to' => $subscribeTo,
                                               'name' => $name,
                                               'token' => $token));
        if($this->db->affected_rows() <= 0) {
            $this->_addError(self::ERROR_INSERTING_COMMENT);
        }
        $subscriberId = $this->db->insert_id();

        $this->db->trans_complete();
        
        return $this->db->trans_status() ? $subscriberId : FALSE;
    }
    
    public function getList($filter)
    {
      return $this->db->get_where('subscriber', $filter)->result();
      
    }
    
    /**
     * получить все аккаунты подписки для заданных условий(для емэйл рассылки)
     * @param type $messageId - сообщение
     * @param type $commentId - комментарий, на котороый поступил ответ
     * 
     * возвращает массив подписчиков
     */
    public function getEmailList($messageId, $commentId)
    {
        // сначала берём всех подписанных на все комменты к сообщению
        $sbscrs = $this->db->get_where('subscriber', 
                array('subscribe_type' => 0, 'subscribe_to' => $messageId))->result();
        
        $emails = array();
        foreach($sbscrs as $sbscr){
            $emails[] = (string)$sbscr->email;
        }
        //массив должен быть не путым, чтоб не вызвать ошибки БД
        if(empty($emails)){
            $emails[] = 'qwer';
        }
        
        $this->db->where(array('subscribe_type' => 1, 'subscribe_to' => $commentId));
        $this->db->where_not_in('email',$emails);
        $sbscrs2 = $this->db->get('subscriber');
        $sbscrs2 = $sbscrs2->result();
        
        return array_merge($sbscrs, $sbscrs2);
    }
    
    /**
     * Отписка от комментариев к сообщениям. Только для авторизированных пользователей.
     * @param type $userId - 
     */
    public function unsubscribe($userId = null, 
            $subscribeTo = null, $subscribeType = 0, $token = null, $subscriberId = null)
    {
        if($token && $subscriberId){
            $data = array(
                'token' => $token,
                'id' => $subscriberId
            );
        } elseif($userId && $subscribeTo) {
            $data = array(
                'user_id' => $userId, 
                'subscribe_to' => $subscribeTo, 
                'subscribe_type' => $subscribeType);            
        } else {
          $data = array();
        }
        return $this->db->delete('subscriber', $data); 
    }
    
    public function isSubscribed($userId, $subscribeTo, $subscribeType = 0)
    {
        if(!$userId){
            return false;
        }
        $res = $this->db->get_where('subscriber', array(
               'user_id' => $userId, 
               'subscribe_to' => $subscribeTo, 
               'subscribe_type' => $subscribeType), 
                1)->result();
        return $res? true: false;
      
    }
    
}
