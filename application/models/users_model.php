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
     * Get list of user accounts according to filter params.
     *
     * @param $filter array Filter params. Possible array fields:
     * @return array .
     */
    public function getList($filter = array(), $select = array(), $order = array())
    {
        
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