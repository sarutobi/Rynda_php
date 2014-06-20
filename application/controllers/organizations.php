<?php if( !defined('BASEPATH') ) exit('No direct script access allowed');
/**
 * Файл содержит определение класса Organization (контроллер страницы)
 *
 * @copyright  Copyright (c) 2011 Valery A. Ilychev aka Sarutobi 
 * @link       /application/controllers/organizations.php
 * @version    0.1
 * @since      Файл доступен начиная с версии проекта 0.1
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/
 */

/**
 * Контроллер страниц для работы с организациями.
 */
class Organizations extends Rynda_Controller
{
    /**
     * Конструктор - подключение языкового файла для локализации сообщений на страницах.
     */
    public function __construct()
    {
        parent::__construct();
        $this->lang->load('rynda_forms');
    }

    public function index()
    {
        $this->load->model('Categories_Model', 'categories', TRUE);

        $organizationTypes = $this->organizations->getTypesList();
        $this->load->view('commonHeader',
                          array('showAuth' => TRUE,
                                'user' => $this->_user,
                                'regions' => $this->regions->getList(),
                                'userRegion' => $this->_userRegion,
                                'subdomains' => $this->subdomains->getList(array('limit' => 15,
                                                                                 'status' => 1,)),
                                'title' => 'Организации',
                                'organizationTypes' => $organizationTypes,
                                'showRequestButton' => TRUE,
                                'showOfferButton' => TRUE,));
        $this->load->view('organizationsListSingle', array('regions' => $this->regions->getList(),
                                                           'categories' => $this->categories->getChilds(),
                                                           'organizationTypes' => $organizationTypes,
                                                           'listTitleShort' => 'организации',
                                                           'listTitle' => 'организации',
                                                           'filterShowFields' => array('region',
                                                                                       'category',
                                                                                       'organizationType',
                                                                                       /*'filterString'*/),
                                                           'filterPersistVars' => array(
                                                               'subdomain' => getSubdomain(),),
        ));

        $jsVars = array('LANG_ORGANIZATIONS_NOT_FOUND' => $this->lang->line('forms_searchOrganizationsNotFound'),
                        'CONST_COOKIE_DOMAIN' => $this->config->item('cookie_domain'),
                        'CONST_REGION_SERVICE_URL' => $this->config->item('region_geolocation_url'),);
        $this->load->view('jsVars', array('jsVars' => $jsVars));
        $this->load->view('commonFooter');
    }
    
    /**
     * Страница списка организаций определённого типа (гос./НКО/коммерч./...).
     *
     * @param $type mixed ID или короткое название типа организации.
     */
    public function type($type)
    {
        if(empty($type))
            show_error($this->lang->line('pages_orgTypeNotFound'), 404);

        $type = $this->organizations->getType($type);

        if( !$type ) // Тип организации не найден
            show_error($this->lang->line('pages_orgTypeNotFound'), 404);
        else if( !$this->organizations->isTypeInSubdomain($type['id'], getSubdomain()) ) {
            /**
             * Тип организации существует, но не привязан к текущему поддомену.
             * Показывать страницу ошибки неверно - редирект на корневую страницу организаций:
             */
            $urlComponents = getUrlComponents();
            redirect("{$urlComponents['scheme']}://{$urlComponents['host']}/org");
        }

        $this->load->model('Categories_Model', 'categories', TRUE);

        $this->load->view('commonHeader',
                          array('showAuth' => TRUE,
                                'user' => $this->_user,
                                'regions' => $this->regions->getList(),
                                'userRegion' => $this->_userRegion,
                                'subdomains' => $this->subdomains->getList(array('limit' => 15,
                                                                                 'status' => 1,)),
                                'title' => "Организации типа «{$type['name']}»",
                                'organizationTypes' => $this->organizations->getTypesList(),
                                'showRequestButton' => TRUE,
                                'showOfferButton' => TRUE,));
        $this->load->view('organizationsListSingle',
                          array('regions' => $this->regions->getList(),
                                'categories' => $this->categories->getChilds(),
                                'listTitleShort' => "Организации типа «{$type['name']}»",
                                'listTitle' => "Организации типа «{$type['name']}»",
                                'filterShowFields' => array('region',
                                                            'category',
                                                            /*'filterString'*/),
                                'filterPersistVars' => array('subdomain' => getSubdomain(),
                                                             'typeId' => $type['id'],),));

        $jsVars = array('LANG_ORGANIZATIONS_NOT_FOUND' => $this->lang->line('forms_searchOrganizationsNotFound'),
                        'CONST_COOKIE_DOMAIN' => $this->config->item('cookie_domain'),
                        'CONST_REGION_SERVICE_URL' => $this->config->item('region_geolocation_url'),);
        $this->load->view('jsVars', array('jsVars' => $jsVars));
        $this->load->view('commonFooter');
    }

    /**
     * Страница детальной информации об организации.
     *
     * @param id int ID организации
     */
    public function detail($id)
    {
        $organization = $this->organizations->getById($id);
        $this->load->view('commonHeader',
                          array('showAuth' => TRUE,
                                'user' => $this->_user,
                                'regions' => $this->regions->getList(),
                                'userRegion' => $this->_userRegion,
                                'subdomains' => $this->subdomains->getList(array('limit' => 15,
                                                                                 'status' => 1,)),
                                'title' => "Организация «{$organization['title']}»",
                                'organizationTypes' => $this->organizations->getTypesList(),
                                'showRequestButton' => TRUE,
                                'showOfferButton' => TRUE,));
        $this->load->view('organizationsDetail', array('org' => $organization,));

        $jsVars = array('CONST_COOKIE_DOMAIN' => $this->config->item('cookie_domain'),
                        'CONST_REGION_SERVICE_URL' => $this->config->item('region_geolocation_url'),);
        $this->load->view('jsVars', array('jsVars' => $jsVars));
        $this->load->view('commonFooter');
    }

    /**
     * Страница ввода новой организации.
     */
    public function add()
    {
        // Доступ на страницу добавления только для группы админов:
        if( !$this->ion_auth->logged_in() || !$this->ion_auth->in_group('admin') )
            redirect('/auth/login');

        $this->load->model('Categories_Model', 'categories', TRUE);

        $this->load->view('commonHeader',
                          array('showAuth' => TRUE,
                                'user' => $this->_user,
                                'regions' => $this->regions->getList(),
                                'userRegion' => $this->_userRegion,
                                'subdomains' => $this->subdomains->getList(array('limit' => 15,
                                                                                 'status' => 1,)),
                                'title' => 'Новая организация',
                                'organizationTypes' => $this->organizations->getTypesList(),
                                'showRequestButton' => FALSE, 
                                'showOfferButton' => FALSE,));
        $this->load->view('organizationsAdd', array('regions' => $this->regions->getList(),
                                                    'categories' => $this->categories->getChilds(),));
        $jsVars = array('LANG_ORG_NAME_REQUIRED' => $this->lang->line('forms_addOrgNameRequired'),
                        'LANG_ORG_TYPE_REQUIRED' => $this->lang->line('forms_addOrgTypeRequired'),
                        'LANG_ORG_CATEGORY_REQUIRED' => $this->lang->line('forms_addOrgCategoryRequired'),
                        'LANG_ORG_REGION_REQUIRED' => $this->lang->line('forms_addOrgRegionRequired'),
                        'LANG_ORG_ADDRESS_REQUIRED' => $this->lang->line('forms_addOrgAddressRequired'),
                        'LANG_ORG_PHONES_INVALID' => $this->lang->line('forms_addOrgPhonesInvalid'),
                        'LANG_ORG_EMAILS_INVALID' => $this->lang->line('forms_addOrgEmailsInvalid'),
                        'CONST_COOKIE_DOMAIN' => $this->config->item('cookie_domain'),
                        'CONST_REGION_SERVICE_URL' => $this->config->item('region_geolocation_url'),);
        $this->load->view('jsVars', array('jsVars' => $jsVars));
        $this->load->view('commonFooter');
    }
    
    /**
     * Страница списка организаций по категории.
     *
     * @param $category mixed Либо ID категории, либо её короткое название.
     */
    public function category($category)
    {
        if(empty($category))
            show_error($this->lang->line('pages_categoryNotFound'), 404);

        $this->load->model('Categories_Model', 'categories', TRUE);
//        $this->load->model('Organizations_Model', 'organizations', TRUE);
        //$this->load->model('Regions_Model', 'regions', TRUE);
        

        if((int)$category > 0) // Передан ID категории
            $category = $this->categories->getById((int)$category);
        else if($category) // Передано короткое название категории
            $category = $this->categories->getBySlug( urlencode(trim($category)) );

        if( !$category ) // Категория не найдена
            show_error($this->lang->line('pages_categoryNotFound'), 404);
        
        $this->load->view('commonHeader',
                          array('showAuth' => TRUE,
                                'user' => $this->_user,
                                'regions' => $this->regions->getList(),
                                'userRegion' => $this->_userRegion,
                                'subdomains' => $this->subdomains->getList(array('limit' => 15,
                                                                                 'status' => 1,)),
                                'title' => "Категория «{$category['name']}»",
                                'organizationTypes' => $this->organizations->getTypesList(),
                                'showRequestButton' => TRUE,
                                'showOfferButton' => TRUE,));
                                                
        $this->load->view( 'organizationsListSingle', array('regions' => $this->regions->getList(),
                                                            'filterShowFields' => array('region'),
                                                            'filterPersistVars' => array(
                                                                'subdomain' => getSubdomain(),
                                                                'category' => array($category['id']))) );

        $jsVars = array('LANG_ORGANIZATIONS_NOT_FOUND' => $this->lang->line('forms_searchOrganizationsNotFound'),
                        'CONST_COOKIE_DOMAIN' => $this->config->item('cookie_domain'),
                        'CONST_REGION_SERVICE_URL' => $this->config->item('region_geolocation_url'));
                        
        $this->load->view('jsVars', array('jsVars' => $jsVars));
        $this->load->view('commonFooter');
    }   
}
