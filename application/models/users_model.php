<?php if( !defined('BASEPATH') ) exit('No direct script access allowed');
/**
 * File contains the definition of Users_Model class (data model).
 *
 * @copyright  (c) 2011 Zvyagintsev L. aka Ahaenor
 * @link       /application/models/users_model.php
 * @version    0.1
 * @since      Rynda.org 0.6
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/
 */

/**
 * Model to work with user accounts.
 */
class Users_Model extends Rynda_Model
{
    const ERROR_SELECTING_USER_META = 1;

    public function __construct()
    {
        parent::__construct();
        self::$_errorTypes = array(self::ERROR_SELECTING_USER_META =>
                                       $this->lang->line('modelAuth_errorUserMetaSelectionFailed'),);
    }

    /**
     * Edit user socmedia links.
     *
     * @param $userId integer User ID.
     * @param $socNetProfiles array Socmedia links needed to change.
     * @return boolean TRUE if edit succeeded, FALSE otherwise. If false, see model's
     * errors array.
     */
    public function updateUserSocProfiles($userId, array $socNetProfiles)
    {
        $userId = (int)$userId;
        if( !$userId || !$socNetProfiles )
            return FALSE;

        $this->db->delete('user_social_profile', array('user_id' => $userId));
        if($socNetProfiles) {
            foreach($socNetProfiles as &$socNetProfile) {
                $socNetProfile = array('user_id' => $userId,
                                       'social_id' => $socNetProfile['id'],
                                       'profile_url' => $socNetProfile['url']);
            }
            $this->db->insert_batch('user_social_profile', $socNetProfiles);
        }

        $this->db->trans_complete();

        return $this->db->trans_status() !== FALSE;
    }

    /**
     * Get the data for main page userlist widget.
     * 
     * @param $limit integer The number of users selected.
     * @return array Array of lines with following fields:
     * * userId integer User ID.
     * * firstName string User's first name.
     * * lastName string User's last name.
     * * avatarUrl string URL to userpic (rel. to the project root).
     */
    public function getUserListWidgetData($limit = 50)
    {
        $limit = (int)$limit > 0 ? (int)$limit : 50;

        $res = $this->db->select('id "userId", last_name "lastName",
                                  first_name "firstName", avatar_url "avatarUrl"')
                        ->from('user_view')
                        ->where('avatar_url IS NOT NULL')
                        ->where('is_private', 0)
                        ->where('active', 1)
                        ->order_by('id', 'random')
//                        ->distinct()
                        ->limit($limit)->get();
        return $res ? $res->result_array() : array();
    }

    /**
     * Служебный метод для предобработки параметров запроса на получение списка сообщений.
     *
     * @param $filter array Массив параметров фильтрации. Возможные значения полей:
     * * id mixed ID сообщения.
     * * dateAddedFrom mixed Нижняя граница даты/времени последнего логина пользователя. Если передано
     * целое число, оно воспринимается как timestamp. Если передана строка, она воспринимается
     * как аргумент PHP-функции strtotime().
     * * dateAddedTo mixed Верхняя граница даты/времени последнего логина пользователя. Если передано
     * целое число, оно воспринимается как timestamp. Если передана строка, она воспринимается
     * как аргумент PHP-функции strtotime().
     * * dateLastLoginFrom mixed Нижняя граница даты/времени последнего логина пользователя. Если
     * передано целое число, оно воспринимается как timestamp. Если передана строка, она
     * воспринимается как аргумент PHP-функции strtotime().
     * * dateLastLoginTo mixed Верхняя граница даты/времени последнего логина пользователя. Если
     * передано целое число, оно воспринимается как timestamp. Если передана строка, она
     * воспринимается как аргумент PHP-функции strtotime().
     * * isActive boolean Активность аккаунта пользователя на данный момент.
     * * isPrivate boolean Анонимность пользователя (TRUE - данные отправителя скрыты,
     * FALSE - открыты).
     * * regionId mixed ID региона, интересного пользователю, или массив таких ID.
     * * category mixed ID категории, интересной пользователю, или массив таких ID.
     * * limit mixed Кол-во строк в ответе. Можно передать целое положительное число, либо
     * строку вида 'начальный_индекс,кол-во_эл-тов', либо массив вида
     * array(начальный_индекс, кол-во_эл-тов). По умолчанию не ограничено.
     * @param $order array Массив параметров сортировки результатов. Ключи массива -
     * названия полей сообщения, значения - порядок сортировки по соотв. полю (возможные
     * значения: asc - по возрастанию, desc - по убыванию). По умолчанию результаты
     * никак не сортируются.
     * @return object Объект Active Record, настроенный на выполнение запроса на получение
     * списка сообщений с переданными параметрами. Объект готов к выполнению запроса на
     * извлечение или подсчёт строк.
     */
    protected function _processListParams(array $filter = array(), array $order = array())
    {
        // Параметры фильтрации:
        $filter = $filter + array('id' => 0,
                                  'regionId' => array(),
                                  'category' => array(),);

        $query = $this->db->from('user_view');

        if((int)$filter['id'] > 0)
            $query->where('id', (int)$filter['id']);

        if( !empty($filter['dateAddedFrom']) ) {
            if((int)$filter['dateAddedFrom'] === $filter['dateAddedFrom']) // Передан timestamp
                $query->where('created_on >= ', $filter['dateAddedFrom']);
            else // Передана строка - аргумент для strtotime()
                $query->where('created_on >= ', strtotime($filter['dateAddedFrom']));
        }

        if( !empty($filter['dateAddedTo']) ) {
            if((int)$filter['dateAddedTo'] === $filter['dateAddedTo']) // Передан timestamp
                $query->where('created_on <= ', $filter['dateAddedTo']);
            else // Передана строка - аргумент для strtotime()
                $query->where('created_on <= ', strtotime($filter['dateAddedTo']));
        }

        if( !empty($filter['dateLastLoginFrom']) ) {
            if((int)$filter['dateLastLoginFrom'] === $filter['dateLastLoginFrom']) // Передан timestamp
                $query->where('last_login >= ', $filter['dateLastLoginFrom']);
            else // Передана строка - аргумент для strtotime()
                $query->where('last_login >= ', strtotime($filter['dateLastLoginFromFrom']));
        }

        if( !empty($filter['dateLastLoginTo']) ) {
            if((int)$filter['dateLastLoginTo'] === $filter['dateLastLoginTo']) // Передан timestamp
                $query->where('last_login <= ', $filter['dateLastLoginTo']);
            else // Передана строка - аргумент для strtotime()
                $query->where('last_login <= ', strtotime($filter['dateLastLoginTo']));
        }

        if(isset($filter['isPrivate'])) {
            $query->where('is_private', (int)$filter['isPrivate'] > 0 ? 1 : 0);
        }

        if(isset($filter['isActive']))
            $query->where('active', (int)$filter['isActive'] > 0 ? 1 : 0);

        if( !empty($filter['regionId']) && (int)$filter['regionId'] >= 0 ) {
            $query->where('id IN (SELECT user_id
                                  FROM public.volunteer_profile_view
                                  WHERE vp_active=1
                                  AND region_id='.$filter['regionId'].')');
        }

        if( !empty($filter['category']) ) {
            $filter['category'] = '{'.implode(',', (array)$filter['category']).'}';
            $query->where("id IN (SELECT user_id
                                  FROM public.volunteer_profile_view
                                  WHERE vp_active=1
                                  AND (categories::INT[] && '{$filter['category']}'::INT[] OR vp_allcats = 1))",
                          NULL, FALSE);
        }
        
        if( !empty($filter['searchString']) ) {
            $query->where("(first_name ILIKE '%".trim($filter['searchString'])."%' OR last_name ILIKE '%".trim($filter['searchString'])."%')");
        }

        if( !empty($filter['limit']) )
            $this->_addLimit($filter['limit']);

        // Параметры сортировки результата:
        foreach($order as $field => $orderBy) {
            $orderBy = trim($orderBy) == 'desc' ? 'desc' : 'asc';
            switch($field) {
                case 'id':
                    $query->order_by('id', $orderBy);
                    break;
                case 'firstName':
                    $query->order_by('first_name', $orderBy);
                    break;
                case 'lastName':
                    $query->order_by('last_name', $orderBy);
                    break;
                case 'isActive':
                    $query->order_by('active', $orderBy);
                    break;
                case 'isPrivate':
                    $query->order_by('is_private', $orderBy);
                    break;
                case 'regionId':
                    $query->order_by('region_id', $orderBy);
                    break;
                case 'dateAdded':
                    $query->order_by('created_on', $orderBy);
                    break;
                case 'dateLastLogin':
                    $query->order_by('last_login', $orderBy);
                    break;
                case 'category':
                case 'categories':
                    $query->order_by('vp_allcats', $orderBy);
                    $query->order_by('categories', $orderBy);
                    break;
                /**
                 * @todo Если потом понадобится, добавить возможность сортировки по тэгам.
                 */
//                case '':
//                    break;
                default:
                    // Неизвестное поле для сортировки
            }
        }

        return $query;
    }

    /**
     * Получение количества сообщений, соответствующих указанным параметрам фильтрации.
     *
     * @param $filter array Массив параметров фильтрации. Описание аргумента -
     * см. в описании аргумента $filter метода _processListParams() этого класса.
     * @return integer Количество сообщений.
     */
    public function getCount(array $filter = array())
    {
        unset($filter['limit']); // На всякий случай, если задан лимит выборки - отменить
        return $this->_processListParams($filter)->count_all_results();
    }

    /**
     * Получение списка пользователей, соотв. указанным параметрам фильтрации.
     *
     * @param $filter array Массив параметров фильтрации. Описание аргумента -
     * см. в описании аргумента $filter метода _processListParams() этого класса.
     * @param $order array Массив параметров сортировки результатов. Описание аргумента -
     * см. в описании аргумента $order метода _processListParams() этого класса.
     * @return array Массив пользователей, содержащий строки со следующими полями:
     * * id integer ID пользователя.
     */
    public function getList(array $filter = array(), array $order = array())
    {
        $users = $this->_processListParams($filter, $order)->get();
        if( !$users )
            return array();

        $res = array();
        foreach($users->result() as $user) {
            // Телефоны отправителя сообщения:
            $phones = array();
            foreach(explode(',', trim($user->phones, '{}')) as $phone) {
                if( !empty($phone) )
                    $phones[] = $phone;
            }

            // Профили волонтёрства пользователей:
            $userVp = array();
            $resVp = $this->db->from('volunteer_profile_view')
                            ->where(array('user_id' => $user->id,
                                          'vp_active' => 1,))->get();
            foreach($resVp->result() as $vp) {
                // Категории профиля:
                $cats = array();
                foreach(explode(',', trim($vp->categories, '{}')) as $category) {
                    if(empty($category))
                        continue;
                    $category = $this->db->select('category_id "id",
                                                   category_name "name",
                                                   category_slug "slug"')
                        ->from('category_view')
                        ->where('category_id', $category)->get();
                    if($category && $category->num_rows())
                        $cats[] = $category->row_array();
                }
                $userVp[] = array('id' => $vp->vp_id,
                                  'title' => $vp->title,
                                  'regionId' => $vp->region_id,
                                  'regionName' => $vp->region_name,
                                  'isAllCategories' => $vp->vp_allcats,
                                  'categories' => $cats,);
            }

            $res[] = array('id' => $user->id,
                           'firstName' => $user->first_name,
                           'lastName' => $user->last_name,
                           'fullName' => $user->first_name.' '.$user->last_name,
                           'phones' => $phones,
                           'email' => $user->email,
                           'isPrivate' => ($user->is_private == 1),
                           'isActive' => ($user->active == 1),
                           'dateAdded' => $user->created_on,
                           'dateLastLogin' => $user->last_login,
                           'avatarUrl' => $user->avatar_url,
                           'userVp' => $userVp,
            );
        }

        return $res;
    }
    
    /**
     * Получить все данные пользователя
     *
     * @param type $id integer User ID
     */
    public function getMetaById($id)
    {
        $this->_clearErrors();

        $id = (int)$id;
        if($id <= 0) {
            return FALSE;
        } else {      
            $res = $this->db->get_where('user_view', array('id' => $id),1);
            return $res ? $res->row_array() : FALSE;
        }
    }
    
}