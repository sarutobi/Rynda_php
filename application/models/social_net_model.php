<?php if( !defined('BASEPATH') ) exit('No direct script access allowed');
/**
 * Файл содержит определение класса Social_Net_Model (модель данных)
 *
 * @copyright  Copyright (c) 2011 Звягинцев Л. aka Ahaenor
 * @link       /application/models/social_net_model.php
 * @version    0.1
 * @since      Файл доступен начиная с версии проекта 0.8
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/
 */

/**
 * Модель для работы с соцсетями.
 */
class Social_Net_Model extends Rynda_Model
{
    /**
     * Для удобства обращения с кодами ошибок модели можно определить их как
     * константы класса модели:
     */
//    const ERROR_INSERTING_LOCATION = 1;
//    const ERROR_INSERTING_PROFILE_NO_USER = 2;
//    const ERROR_INSERTING_PROFILE_NO_LOCATION = 3;
//    const ERROR_INSERTING_PROFILE_UNKNOWN_REGION = 4;
//    const ERROR_INSERTING_PROFILE = 5;

    public function __construct()
    {
        parent::__construct();
//        self::$_errorTypes = array(self::ERROR_INSERTING_LOCATION =>
//                                       $this->lang->line('modelVp_errorLocationInsertFailed'),
//                                   self::ERROR_INSERTING_PROFILE_NO_USER =>
//                                       $this->lang->line('modelVp_errorUserUndefined'),
//                                   self::ERROR_INSERTING_PROFILE_NO_LOCATION =>
//                                       $this->lang->line('modelVp_errorLocationUndefined'),
//                                   self::ERROR_INSERTING_PROFILE_UNKNOWN_REGION =>
//                                       $this->lang->line('modelVp_errorRegionUndefined'),
//                                   self::ERROR_INSERTING_PROFILE =>
//                                       $this->lang->line('modelVp_errorVpInsertFailed'),);
    }

    /**
     * Получение списка соцсетей.
     *
     * @return array Массив полей соцсети, содержащий следующие поля:
     * * id integer ID соцсети.
     * * title string Название соцсети.
     * * icon string URL иконки соцсети.
     * * url string URL соцсети.
     */
    public function getList()
    {
        $socNets = $this->db->get('social_net');
        if( !$socNets->num_rows() )
            return array();

        $res = array();
        foreach($socNets->result() as $socNet) {
            $res[] = array('id' => $socNet->id,
                           'title' => $socNet->name,
                           'icon' => $socNet->icon,
                           'url' => $socNet->url,);
        }

        return $res;
    }
    
    /**
     * Проверка, зарегистрирована ли указанная соц.сеть в БД системы.
     * 
     * @param $url string URL соц.сети или профиля в ней.
     * @return mixed Если соц.сеть найдена в БД, возвращается её ID. Иначе возвращается
     * FALSE.
     */
    public function getSocNetByProfile($url)
    {
        $socNetsAvailable = $this->getList();
        $socNetId = 0;
        foreach($socNetsAvailable as $socNet) {
            if(strpos($url, $socNet['url']) !== FALSE) {
                $socNetId = $socNet['id'];
                break;
            }
        }

        return $socNetId ? $socNetId : FALSE;
    }

    /**
     * Получение списка ссылок на профили пользователя в соц.сетях.
     * 
     * @param $userId integer ID пользователя, ссылки на соц.профили которого необходимо
     * получить.
     * @return array Массив полей соц.профиля (включая поля соц.сети). Содержит следующие
     * поля:
     * * id integer ID ссылки на соц.профиль пользователя.
     * * userId integer ID пользователя.
     * * socNetId integer ID соцсети.
     * * socNetTitle string Название соцсети.
     * * socNetIcon string URL иконки соцсети.
     * * socNetUrl string URL соцсети.
     */
    public function getUserSocNetProfiles($userId)
    {
        $userId = (int)$userId;
        if($userId <= 0)
            return FALSE;

        $socNetProfiles = $this->db->select('*')
                            ->from('social_net')
                            ->join('user_social_profile',
                                   'social_net.id = user_social_profile.social_id')
                            ->where('user_social_profile.user_id', $userId)
                            ->get();
        if( !$socNetProfiles->num_rows() )
            return array();

        $res = array();
        foreach($socNetProfiles->result() as $profile) {
            $res[] = array('id' => $profile->id,
                           'profileUrl' => $profile->profile_url,
                           'userId' => $userId,
                           'socNetId' => $profile->social_id,
                           'socNetTitle' => $profile->name,
                           'socNetIcon' => $profile->icon,
                           'socNetUrl' => $profile->url,);
        }

        return $res;
    }
}