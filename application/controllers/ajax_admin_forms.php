<?php if( !defined('BASEPATH') ) exit('No direct script access allowed');
/**
 * Файл содержит определение класса Ajax_Admin_Forms (контроллер служебных скриптов)
 *
 * @copyright  Copyright (c) 2011 Звягинцев Л. aka Ahaenor
 * @link       /application/controllers/ajax_admin_forms.php
 * @version    0.1
 * @since      Файл доступен начиная с версии проекта 0.1
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/
 */

/**
 * Контроллер скриптов для обработки данных форм административного раздела.
 */
class Ajax_Admin_Forms extends Rynda_Controller
{
    /**
     * Конструктор контроллера дополнен проверкой на содержимое $_REQUEST.
     *
     * @return NULL
     */
    public function __construct()
    {
        parent::__construct();
        $this->lang->load('rynda_forms');
        
        // Обработчики форм вызываются только POST-ом. Иначе - отбой и 403-й статус.
        if($this->input->server('REQUEST_METHOD') != 'POST')
            show_error($this->lang->line('pages_message403'), 403);
    }

    /**
     * Обработка формы добавления новой организации.
     */
    public function addOrganizationProcess()
    {
        $this->load->model('Organizations_Model', 'organizations', TRUE);

        $phones = explode(',', trim($this->input->post('phones')));
        foreach($phones as &$phone) {
            if( !empty($phone) )
                $phone = preg_replace(array(/*'/^(\+7|8)/',*/ '/[^0-9]/'), '', $phone);
        }

        $category = array();
        foreach((array)$this->input->post('category') as $cat) {
            if( !empty($cat) )
                $category[] = (int)$cat;
        }

        $sites = explode(',', trim($this->input->post('sites')));
        foreach($sites as &$site) {
            if( !empty($site) ) // Удаляем http:// из URL-ов сайтов:
                $site = str_replace('http://', '', trim($site));
        }

        $emails = explode(',', trim($this->input->post('emails')));
        foreach($emails as &$email) {
            if( !empty($email) )
                $email = trim($email);
        }

        $data = array('name' => trim($this->input->post('name')),
                      'description' => trim($this->input->post('description')),
                      'type' => (int)$this->input->post('organizationType'),
                      'category' => $category,
                      'regionId' => (int)$this->input->post('organizationRegion'),
                      'address' => trim($this->input->post('address')),
                      'phones' => $phones,
                      'sites' => $sites,
                      'emails' => $emails,
                      'contacts' => trim($this->input->post('contacts')),);
//        echo '<pre>' . print_r($data, TRUE) . '</pre>';

        $this->output->set_header('Content-type: application/json');
        $res = $this->organizations->add($data);
        if($res)
            echo jsonResponse('success',
                              $this->lang->line('forms_addOrgSuccess'),
                              array('data' => array('id' => $res)));
        else {
            $errorMessages = "Some error occures in organization creation. Organization data: \n"
                            .print_r($data, TRUE);
            $this->load->library('email');
            $this->email->from($this->config->item('admin_email'), $this->config->item('project_basename'));
            $this->email->to($this->config->item('admin_email'));
            $this->email->subject('Error while creating message');
            $this->email->message($errorMessages);
            $this->email->send();

            log_message('error', $errorMessages);

            echo jsonResponse('error', $this->organizations->getErrorMessages() ?
                                           $this->organizations->getErrorMessages() :
                                           $this->lang->line('forms_addOrgError'));
        }
    }
}