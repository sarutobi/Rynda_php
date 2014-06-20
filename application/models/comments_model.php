<?php if( !defined('BASEPATH') ) exit('No direct script access allowed');
/**
 * Файл содержит определение класса Comments_Model (модель данных)
 *
 * @copyright  Copyright (c) 2011 Звягинцев Л. aka Ahaenor
 * @link       /application/models/comments_model.php
 * @version    0.1
 * @since      Файл доступен начиная с версии проекта 0.5
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/
 */

/**
 * Модель для работы с комментариями на сайте.
 */
class Comments_Model extends Rynda_Model
{
    /**
     * Для удобства обращения с кодами ошибок модели можно определить их как
     * константы класса модели:
     */
    const ERROR_INSERTING_COMMENT = 1;

    public function __construct()
    {
        parent::__construct();
        self::$_errorTypes = array(self::ERROR_INSERTING_COMMENT =>
                                       $this->lang->line('modelComments_errorCommentInsertFailed'),
//                                   self::ERROR_ =>
//                                       $this->lang->line(''),
                             );
    }
    
    /**
     * Служебный метод для предобработки параметров запроса на получение списка комментариев.
     * 
     * @param $filter array Массив параметров фильтрации. Возможные значения полей:
     * * id mixed ID комментария.
     * * messageId integer ID сообщения, к которому относится комментарий.
     * * parentId integer ID комментария, в ответ на который оставлен текущий.
     * * depth integer Уровень вложенности комментария (0 - корневой).
     * * text string Текст комментария.
     * * dateAddedFrom mixed Нижняя граница даты/времени добавления комментария. Если передано
     * целое число, оно воспринимается как timestamp. Если передана строка, она воспринимается
     * как аргумент PHP-функции strtotime().
     * * dateAddedTo mixed Верхняя граница даты/времени добавления комментария. Если передано
     * целое число, оно воспринимается как timestamp. Если передана строка, она воспринимается
     * как аргумент PHP-функции strtotime().
     * * subdomain mixed ID или название субдомена (дочерней карты), к которому относится
     * комментируемое сообщение. Если передано FALSE, 0, NULL или пустая строка, будут
     * извлечены комментарии на сообщения, привязанные к главной карте.
     * * statusId mixed Номер текущего статуса комментария или массив номеров статусов.
     * * userId integer ID пользователя, являющегося автором комментария.
     * * limit mixed Кол-во строк в ответе. Можно передать целое положительное число, либо
     * строку вида 'начальный_индекс,кол-во_эл-тов', либо массив вида
     * array(начальный_индекс, кол-во_эл-тов). По умолчанию не ограничено.
     * @param $order array Массив параметров сортировки результатов. Ключи массива -
     * названия полей комментария, значения - порядок сортировки по соотв. полю (возможные
     * значения: asc - по возрастанию, desc - по убыванию). По умолчанию результаты
     * никак не сортируются.
     * @return object Объект Active Record, настроенный на выполнение запроса на получение
     * списка комментариев с переданными параметрами. Объект готов к выполнению запроса на
     * извлечение или подсчёт строк.
     */
    protected function _processListParams(array $filter = array(), array $order = array())
    {
        // Параметры фильтрации:
        $filter = $filter + array('id' => 0,);

        $query = $this->db->from('comment_view');

        if((int)$filter['id'] > 0)
            $query->where('comment_id', (int)$filter['id']);

        if((int)$filter['messageId'] > 0)
            $query->where('message_id', (int)$filter['messageId']);

        if(!empty($filter['parentId']) && (int)$filter['parentId'] > 0)
            $query->where('in_reply_to', (int)$filter['parentId']);

        if(isset($filter['depth']))
            $query->where('level', (int)$filter['depth']);

        if( !empty($filter['text']) )
            $query->like('comment_txt', trim($filter['text']));

        if( !empty($filter['dateAddedFrom']) ) {
            if((int)$filter['dateAddedFrom'] === $filter['dateAddedFrom']) // Передан timestamp
                $query->where('date_add >= ', date('Y-m-d', $filter['dateAddedFrom']));
            else // Передана строка - аргумент для strtotime()
                $query->where('date_add >= ', date('Y-m-d', strtotime($filter['dateAddedFrom'])));
        }

        if( !empty($filter['dateAddedTo']) ) {
            if((int)$filter['dateAddedTo'] === $filter['dateAddedTo']) // Передан timestamp
                $query->where('date_add <= ', date('Y-m-d', $filter['dateAddedTo']));
            else // Передана строка - аргумент для strtotime()
                $query->where('date_add <= ', date('Y-m-d', strtotime($filter['dateAddedTo'])));
        }

//        if(isset($filter['subdomain'])) {
//            if((int)$filter['subdomain'] > 0) // Передан ID субдомена
//                $query->where('subdomain_id', (int)$filter['subdomain']);
//            else // Передано название субдомена
//                $query->where('subdomain', trim($filter['subdomain']));
//        }

        if( isset($filter['statusId']) )
            $query->where_in('status', array_map(function($value){ return (int)$value; },
                                                 (array)$filter['statusId']));

        if( !empty($filter['userId']) )
            $query->where('user_id', (int)$filter['userId']);

        if( !empty($filter['limit']) )
            $this->_addLimit($filter['limit']);

        // Параметры сортировки результата:
        foreach($order as $field => $orderBy) {
            $orderBy = trim($orderBy) == 'desc' ? 'desc' : 'asc';
            switch($field) {
                case 'id':
                    $query->order_by('comment_id', $orderBy);
                    break;
                case 'messageId':
                    $query->order_by('message_id', $orderBy);
                    break;
                case 'parentId':
                    $query->order_by('in_reply_to', $orderBy);
                    break;
                case 'depth':
                    $query->order_by('level', $orderBy);
                    break;
                case 'dateAdded':
                    $query->order_by('date_add', $orderBy);
                    break;
                case 'statusId':
                    $query->order_by('status', $orderBy);
                    break;
                case 'userId':
                    $query->order_by('user_id', $orderBy);
                    break;
//                case '':
//                    break;
                default:
                    // Неизвестное поле для сортировки
            }
        }

        return $query;
    }

    /**
     * Получение данных комментария по его ID.
     *
     * @param $id integer ID комментария.
     * @return mixed Если комментарий не найден, возвращается FALSE. Иначе будет возвращён
     * массив полей комментария, содержащий следующие поля:
     * * id integer ID комментария.
     * * messageId integer ID сообщения, к которому относится комментарий.
     * * parentId integer ID комментария, в ответ на который оставлен текущий.
     * * depth integer Уровень вложенности комментария (0 - корневой).
     * * text string Текст комментария.
     * * dateAdded string Timestamp даты/времени добавления сообщения.
     * * userId integer ID пользователя, создавшего комментарий. Если автор - гость,
     * поле имеет значение 0.
     * * userName string Имя или ник пользователя, создавшего комментарий.
     * * email string E-mail пользователя, создавшего комментарий.
     * * ip string IP пользователя, создавшего комментарий.
     * * statusId integer ID текущего статуса комментария.
     * * statusName string Название текущего статуса комментария.
     */
    public function getById($id)
    {
        $this->_clearErrors();

        $id = (int)$id;
        if($id <= 0)
            return FALSE;

        $res = $this->db->get_where('comment_view', array('comment_id' => $id), 1)->row();
        if(empty($res))
            return FALSE;
        return array('id' => $res->comment_id,
                     'messageId' => $res->message_id,
                     'parentId' => $res->reply_to,
                     'depth' => $res->level,
                     'text' => $res->comment_txt,
                     'dateAdded' => strtotime($res->date_add),
//                             'subdomainId' => $comment->,
//                             'subdomainName' => $comment->,
                     'statusId' => $res->status,
                     'statusName' => $this->getStatusName($res->status),
                     'userId' => (int)$res->user_id,
                     'userName' => $res->sender,
                     'email' => $res->email,
                     'ip' => $res->ip,);
    }

    /**
     * Получение количества комментариев, соответствующих указанным параметрам фильтрации.
     *
     * @param $filter array Массив параметров фильтрации. Описание аргумента - 
     * см. в описании аргумента $filter метода _processListParams() этого класса.
     * @return integer Количество комментариев.
     */
    public function getCount(array $filter = array())
    {
        unset($filter['limit']); // На всякий случай, если задан лимит выборки - отменить
        return $this->_processListParams($filter)->count_all_results();
    }

    /**
     * Получение списка комментариев, соотв. указанным параметрам фильтрации.
     *
     * @param $filter array Массив параметров фильтрации. Описание аргумента - 
     * см. в описании аргумента $filter метода _processListParams() этого класса.
     * @param $order array Массив параметров сортировки результатов. Описание аргумента - 
     * см. в описании аргумента $order метода _processListParams() этого класса.
     * @return array Массив комментариев, содержащий строки со следующими полями:
     * * id integer ID комментария.
     * * messageId integer ID сообщения, к которому относится комментарий.
     * * parentId integer ID комментария, в ответ на который оставлен текущий.
     * * depth integer Уровень вложенности комментария (0 - корневой).
     * * text string Текст комментария.
     * * dateAdded string Timestamp даты/времени добавления сообщения.
     * * userId integer ID пользователя, создавшего комментарий. Если автор - гость,
     * поле имеет значение 0.
     * * userName string Имя или ник пользователя, создавшего комментарий.
     * * email string E-mail пользователя, создавшего комментарий.
     * * ip string IP пользователя, создавшего комментарий.
     * * statusId integer ID текущего статуса комментария.
     * * statusName string Название текущего статуса комментария.
     */
    public function getList(array $filter = array(), array $order = array())
    {
        $comments = $this->_processListParams($filter, $order)->get();
        if( !$comments )
            return array();

        $res = array();
        foreach($comments->result() as $comment) {
          $res[$comment->comment_id] = array('id' => $comment->comment_id,
                           'messageId' => $comment->message_id,
                           'parentId' => $comment->reply_to,
                           'depth' => $comment->level,
                           'text' => $comment->comment_txt,
                           'dateAdded' => strtotime($comment->date_add),
//                           'subdomainId' => $comment->,
//                           'subdomainName' => $comment->,
                           'statusId' => $comment->status,
                           'statusName' => $this->getStatusName($comment->status),
                           'userId' => (int)$comment->user_id,
                           'userName' => $comment->sender,
                           'email' => $comment->email,
                           'ip' => $comment->ip,);
        }

        return $res;
    }

    /**
     * Вставка в БД нового комментария.
     *
     * @param $data array Массив данных нового комментария. Возможные значения полей:
     * * messageId integer ID сообщения, к которому относится комментарий.
     * * parentId integer ID комментария, в ответ на который оставлен данный. Если
     * комментарий имеет корневой уровень, поле имеет значение 0. По умолчанию 0.
     * * text string Текст сообщения. Обязательно должен быть передан.
     * * statusId integer Статус комментария. По умолчанию COMMENT_STATUS_RECEIVED.
     * * userId integer ID пользователя, который добавляет комментарий. Если пользователь
     * незарегистрирован, поле имеет значение 0. По умолчанию используется ID текущего
     * пользователя.
     * * userName string Имя или ник пользователя, создавшего комментарий. По умолчанию
     * поле имеет значение, соотв. гостю (текст значения хранится в параметре локализации
     * modelComments_anonymousName).
     * * email string E-mail пользователя, создавшего комментарий. Обязательно должен быть
     * передан.
     * @return mixed ID нового комментария при успешной вставке, иначе FALSE.
     */
    public function add(array $data)
    {
        $this->_clearErrors();
        
        $currUser = $this->ion_auth->user()->row();
        $data = $data + array('parentId' => 0,
                              'messageId' => 0,
                              'text' => '',
                              'statusId' => $this->config->item('comment_status_default'),
//                              'userId' => empty($this->_user->id) ? 0 : $this->_user->id,
                              'userId' => empty($currUser->id) ? 0 : $currUser->id,
                              'userName' => '',
                              'email' => '',);
        $data['email'] = trim($data['email']);
        

        if(empty($data['messageId'])) { // Связь коммента с сообщением обязательна
            $this->_addError(self::ERROR_INSERTING_COMMENT);
            return FALSE;
        }

        if( !trim($data['userName']) ) {
            $data['userName'] = $this->lang->line('modelComments_anonymousName');
        }

        if( !isset($data['email']) ) { // Email автора коммента обязателен
            $this->_addError(self::ERROR_INSERTING_COMMENT);
            return FALSE;
        }

        if( !isset($data['text']) ) { // Текст коммента обязателен
            $this->_addError(self::ERROR_INSERTING_COMMENT);
            return FALSE;
        }

        $this->db->trans_start();

        $this->db->insert('comment', array('message' => trim($data['text']),
                                           'status' => (int)$data['statusId'],
                                           'sender' => trim($data['userName']),
                                           'email' => trim($data['email']),
                                           'ip' => $this->input->ip_address(),
                                           'user_id' => $data['userId'],));
        if($this->db->affected_rows() <= 0) {
            $this->_addError(self::ERROR_INSERTING_COMMENT);
        }
        $commentId = $this->db->insert_id();

        $commentDepth = 0;
        if((int)$data['parentId'] > 0) {
            $data['parentId'] = (int)$data['parentId'];
            $parent = $this->getById($data['parentId']);
            if($parent)
                $commentDepth = $parent['depth']+1;
            else { // Передан некорректный ID родительского комментария, ошибка
                $this->_addError(self::ERROR_INSERTING_COMMENT);
                return FALSE;
            }
        }

        $this->db->insert('in_reply_to', array('message_id' => $data['messageId'],
                                               'comment_id' => $commentId,
                                               'reply_to' => (int)$data['parentId'],
                                               'level' => $commentDepth,));
        $this->db->trans_complete();

        return $this->db->trans_status() ? $commentId : FALSE;
    }
    
    /**
     * Получение текстового названия статуса комментария по его ID.
     *
     * @param $statusId integer ID статуса.
     * @return mixed Название статуса либо FALSE, если передан некорректный ID.
     */
    public function getStatusName($statusId)
    {
        $this->lang->load('rynda_models');
        switch((int)$statusId) {
            case COMMENT_STATUS_RECEIVED:
                return $this->lang->line('modelComments_statusReceivedName');
            case COMMENT_STATUS_MODERATED:
                return $this->lang->line('modelComments_statusModeratedName');
            case COMMENT_STATUS_DELETED:
                return $this->lang->line('modelComments_statusDeletedName');
            case COMMENT_STATUS_SPAM:
                return $this->lang->line('modelComments_statusSpamName');
            default:
                return FALSE;
        }
    }
}
