<?php if( !defined('BASEPATH') ) exit('No direct script access allowed');
/**
 * Файл содержит определение класса Main (контроллер страницы)
 *
 * @copyright  Copyright (c) 2011 Звягинцев Л. aka Ahaenor
 * @link       /application/controllers/main.php
 * @version    0.1
 * @since      Файл доступен начиная с версии проекта 0.1
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/
 */

/**
 * Контроллер главной страницы всех сайтов системы:
 * как основного, так и сайтов-сателлитов (дочерних карт).
 */
class Main extends Rynda_Controller
{
    /**
     * Главная страница системы.
     */
    public function index()
    {
        $this->load->model('Categories_Model', 'categories', TRUE);
        $this->load->model('Subdomains_Model', 'subdomains', TRUE);
        $this->load->model('Messages_Model', 'messages', TRUE);
        $this->load->model('Users_Model', 'users', TRUE);
        $this->load->model('Feeds_Model', 'feeds', TRUE);

        $this->lang->load('rynda_views');

        $this->load->view('commonHeader', array('showAuth' => TRUE,
                                                'user' => $this->_user,
                                                'regions' => $this->regions->getList(),
                                                'userRegion' => $this->_userRegion,
                                                'subdomains' =>
                                                    $this->subdomains->getList(array('limit' => 15,
                                                                                     'status' => 1,)),
                                                'organizationTypes' =>
                                                    $this->organizations->getTypesList(),
                                                'showRequestButton' => TRUE,
                                                'showOfferButton' => TRUE,));
        $this->load->view('mainIndex',
                          array('disclaimer' => $this->subdomains->getDisclaimer(),
                                'categories' => $this->categories->getList(array('subdomain' => getSubdomain(),)),
                                'messageTypes' => $this->messages->getTypesList(array('comment', 'advise')),
                                'newsRecent' => $this->feeds->getItemsList(array('limit' => 6,
                                                                                 'feedId' => 3),
                                                                           array('dateAdded' => 'desc')),
                                'usersForList' => $this->users->getUserListWidgetData(10),
                                'messageTypeRequest' => 'request',
                                'messageTypeOffer' => 'offer',
                              ));

        $jsVars = array('LANG_CATEGORIES_ENUM_TITLE' => $this->lang->line('views_categoriesEnumTitle'),
                        'LANG_LINK_TO_MESSAGE_PAGE' => $this->lang->line('views_messagePageLinkText'),
                        'LANG_CONTROL_ZOOM_IN' => $this->lang->line('views_zoomInControl'),
                        'LANG_COUNT_CLUSTER_MESSASES' => $this->lang->line('views_countClusterMessages'),
                        'LANG_EXCERPT_TEXT' => $this->lang->line('views_excerptText'),
                        'CONST_MAP_DEFAULT_ZOOM' => $this->config->item('main_map_default_zoom'),
                        'CONST_MAP_DEFAULT_LAT' => $this->config->item('main_map_default_lat'),
                        'CONST_MAP_DEFAULT_LNG' => $this->config->item('main_map_default_lng'),
                        'CONST_COOKIE_DOMAIN' => $this->config->item('cookie_domain'),
                        'CONST_REGION_SERVICE_URL' => $this->config->item('region_geolocation_url'),
                        'CONST_MESSAGE_TYPE_REQUEST' => 'request',
                        'CONST_MESSAGE_TYPE_OFFER' => 'offer',
                        'CONST_MESSAGE_TYPE_INFO' => 'info',
                        'CONST_MESSAGE_STATUS_REACTED' => MESSAGE_STATUS_REACTED,
                        'CONST_MESSAGE_STATUS_CLOSED' => MESSAGE_STATUS_CLOSED,);
        $this->load->view('jsVars', array('jsVars' => $jsVars));
        $this->load->view('commonFooter');
        
//        $this->load->library('Rynda_Output_Ushahidi');
//        $this->rynda_output_ushahidi->pushMessage(138);
    }

    /**
     * Информационная страница системы.
     *
     * @param $page mixed Либо ID отображаемой страницы, либо её короткое название.
     */
    public function info($page)
    {
        if(empty($page))
            show_error($this->lang->line('pages_infoPageNotFound'), 404);
        
        $this->load->model('Info_Pages_Model', 'infoPages', TRUE);

        if((int)$page > 0) // Передан ID страницы
            $page = $this->infoPages->getById((int)$page, TRUE);
        else if($page) // Передано короткое название страницы
            $page = $this->infoPages->getBySlug(urlencode(trim($page)), TRUE);

        if( !$page ) // Страница не найдена
            show_error($this->lang->line('pages_infoPageNotFound'), 404);

        $this->load->view('commonHeader',
                          array('showAuth' => TRUE,
                                'user' => $this->_user,
                                'regions' => $this->regions->getList(),
                                'userRegion' => $this->_userRegion,
                                'subdomains' => $this->subdomains->getList(array('limit' => 15,
                                                                                 'status' => 1,)),
                                'title' => mb_strtoupper($page['title']),
                                'organizationTypes' => $this->organizations->getTypesList(),
                                'showRequestButton' => TRUE,
                                'showOfferButton' => TRUE,));
        $this->load->view('mainInfo', array('page' => $page,));

        $jsVars = array('CONST_COOKIE_DOMAIN' => $this->config->item('cookie_domain'),
                        'CONST_REGION_SERVICE_URL' => $this->config->item('region_geolocation_url'),);
        $this->load->view('jsVars', array('jsVars' => $jsVars));
        $this->load->view('commonFooter');
    }
}
