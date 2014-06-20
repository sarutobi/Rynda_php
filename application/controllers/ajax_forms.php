<?php if( !defined('BASEPATH') ) exit('No direct script access allowed');
/**
 * Файл содержит определение класса Ajax_Forms (контроллер служебных скриптов)
 *
 * @copyright  Copyright (c) 2011 Звягинцев Л. aka Ahaenor
 * @link       /application/controllers/ajax_forms.php
 * @version    0.1
 * @since      Файл доступен начиная с версии проекта 0.1
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/
 */

/**
 * Контроллер скриптов для обработки данных форм.
 */
class Ajax_Forms extends Rynda_Controller
{
    /**
     * Конструктор контроллера дополнен проверкой на содержимое $_REQUEST.
     */
    public function __construct()
    {
        parent::__construct();
        $this->lang->load('rynda_forms');
        
        // Form processing methods is accessed only via POST. Give 403 otherwise:
        if($this->input->server('REQUEST_METHOD') != 'POST')
            show_error($this->lang->line('pages_message403'), 403);
    }

    /**
     * Обработка запроса на получение списка сообщений, соотв. указанным параметрам.
     */
    public function ajaxGetMessages()
    {
        $this->load->model('Messages_Model', 'messages', TRUE);

        $filter = array();
        // Параметры фильтрации, которые участвуют во всех запросах:
        if($this->input->post('persist')) {
            foreach((array)$this->input->post('persist') as $varName => $value) {
                $filter[$varName] = $value;
            }
        }

        $filter['subdomain'] = getSubdomain();

        // Параметры фильтрации, которые выбираются на форме фильтра:
        if((int)$this->input->post('userId') > 0)
            $filter['userId'] = (int)$this->input->post('userId');
        if((int)$this->input->post('dateAddedFrom') > 0)
            $filter['dateAddedFrom'] = (int)$this->input->post('dateAddedFrom');
        if((int)$this->input->post('dateModifiedFrom') > 0)
            $filter['dateModifiedFrom'] = (int)$this->input->post('dateModifiedFrom');
        if((int)$this->input->post('regionId') > 0)
            $filter['regionId'] = (int)$this->input->post('regionId');
        if($this->input->post('searchPlace'))
            $filter['address'] = trim($this->input->post('searchPlace'));
        if($this->input->post('typeId')) {
            $filter['typeId'] = array();
            foreach((array)$this->input->post('typeId') as $type) {
                if($type)
                    $filter['typeId'][] = (int)$type;
            }
        } else if($this->input->post('typeSlug')) {
            $filter['typeSlug'] = array();
            foreach((array)$this->input->post('typeSlug') as $type) {
                if($type)
                    $filter['typeSlug'][] = trim($type);
            }
        } else if(empty($filter['typeSlug'])) { // Типы сообщений по умолчанию
            $filter['typeSlug'] = array('request', 'offer', 'info');
        }

        if($this->input->post('category')) {
            $filter['category'] = array();
            foreach((array)$this->input->post('category') as $cat) {
                if( !empty($cat) )
                    $filter['category'][] = (int)$cat;
            }
        }
        if($this->input->post('untimed') !== ''
        && $this->input->post('untimed') !== FALSE)
            $filter['isUntimed'] = (bool)$this->input->post('untimed');
        if($this->input->post('searchString'))
            $filter['full'] = trim($this->input->post('searchString'));

        // Статусы сообщений. По умолчанию, сообщения со статусами "не промодерировано" и
        // "закрыто" исключаются из результата:
        if(empty($filter['statusId']))
            $filter['statusId'] = array(MESSAGE_STATUS_MODERATED, MESSAGE_STATUS_VERIFIED,
                                        MESSAGE_STATUS_REACTION, MESSAGE_STATUS_REACTED);
        if($this->input->post('searchNonModerated'))
            $filter['statusId'][] = MESSAGE_STATUS_RECEIVED;
        if($this->input->post('searchClosed'))
            $filter['statusId'][] = MESSAGE_STATUS_CLOSED;

        // Активность сообщений. По умолчанию, неактивные сообщения исключаются из результата:
        if( !$this->input->post('inactive')
          || $this->input->post('inactive') == 0)
            $filter['isActive'] = TRUE;

        $itemsPerPage = (int)$this->input->post('itemsPerPage');
        if($itemsPerPage) {
            $currentPage = (int)$this->input->post('currentPage') ?
                               (int)$this->input->post('currentPage') : 1;
            $filter['limit'] = array(($currentPage-1)*$itemsPerPage,
                                     $itemsPerPage);
        }

        // Параметры сортировки результатов:
        $order = array('dateModified' => 'desc'); // По умолчанию - по дате
        if($this->input->post('orderBy')) {
            $order = array();
            foreach((array)$this->input->post('orderBy') as $orderBy) {
                $order[$orderBy] = 'desc';
            }
        }

        $messages = $this->messages->getList($filter, $order);
        if($messages !== FALSE) {
            echo jsonResponse('success', '', array('data' => $messages,
                                                   'totalDataCount' => $this->messages->getCount($filter)));
        } else {
            echo jsonResponse('error', '', $this->messages->getErrorMessages() ?
                                               $this->messages->getErrorMessages() :
                                               $this->lang->line('forms_searchMessagesError'));
        }
    }

    /**
     * Обработка запроса на получение списка организаций, соотв. указанным параметрам.
     */
    public function ajaxGetOrganizations()
    {
        $filter = array('subdomain' => getSubdomain());
        
        // Параметры фильтрации, которые участвуют во всех запросах:
        if($this->input->post('persist')) {
            foreach((array)$this->input->post('persist') as $varName => $value) {
                $filter[$varName] = $value;
            }
        }

        if($this->input->post('category')) {
            $filter['category'] = array();
            foreach((array)$this->input->post('category') as $cat) {
                if($cat)
                    $filter['category'][] = (int)$cat;
            }
        }

        if((int)$this->input->post('regionId') > 0)
            $filter['regionId'] = (int)$this->input->post('regionId');

        if($this->input->post('typeId')) {
            $filter['typeId'] = array();
            foreach((array)$this->input->post('typeId') as $typeId) {
                if($typeId)
                    $filter['typeId'][] = (int)$typeId;
            }
        }
        
        if((int)$this->input->post('dateAddedFrom') > 0)
            $filter['dateAddedFrom'] = (int)$this->input->post('dateAddedFrom');
        
        $itemsPerPage = (int)$this->input->post('itemsPerPage');
        if($itemsPerPage) {
            $currentPage = (int)$this->input->post('currentPage') ?
                               (int)$this->input->post('currentPage') : 1;
            $filter['limit'] = array(($currentPage-1)*$itemsPerPage,
                                     $itemsPerPage);
        }

        $org = $this->organizations->getList($filter, array('title' => 'asc',));
        if($org !== FALSE) {
            echo jsonResponse('success', '',
                              array('data' => $org,
                                    'totalDataCount' => $this->organizations->getCount($filter)));
        } else {
            echo jsonResponse('error', $this->organizations->getErrorMessages() ?
                                           $this->organizations->getErrorMessages() :
                                           $this->lang->line('forms_searchOrganizationsError'));
        }
    }

    /**
     * Обработка запроса на получение списка пользователей, соотв. указанным параметрам.
     */
    public function ajaxGetUsers()
    {
        $this->load->model('Users_Model', 'users', TRUE);

        $filter = array('subdomain' => getSubdomain(), 'isPrivate' => 0);

        if($this->input->post('category')) {
            $filter['category'] = array();
            foreach((array)$this->input->post('category') as $cat) {
                if($cat)
                    $filter['category'][] = (int)$cat;
            }
        }

        if((int)$this->input->post('regionId') > 0)
            $filter['regionId'] = (int)$this->input->post('regionId');

        if($this->input->post('searchString'))
            $filter['searchString'] = $this->input->post('searchString');
        
        if((int)$this->input->post('dateAddedFrom') > 0)
            $filter['dateAddedFrom'] = (int)$this->input->post('dateAddedFrom');
        
        $itemsPerPage = (int)$this->input->post('itemsPerPage');
        if($itemsPerPage) {
            $currentPage = (int)$this->input->post('currentPage') ?
                               (int)$this->input->post('currentPage') : 1;
            $filter['limit'] = array(($currentPage-1)*$itemsPerPage,
                                     $itemsPerPage);
        }

        $order = array();
        $this->input->post('orderByDir') == 'asc' ? 'asc' : 'desc';
        if($this->input->post('filterOrderBy') == 'dateAdded')
            $order['dateAdded'] = $this->input->post('orderByDir') == 'asc' ? 'asc' : 'desc';
        else {
            $direction = $this->input->post('orderByDir') == 'asc' ? 'asc' : 'desc';
            $order = array('firstName' => $direction,
                           'lastName' => $direction,);
        }

        $users = $this->users->getList($filter, $order);
        if($users !== FALSE) {
            echo jsonResponse('success', '',
                              array('data' => $users,
                                    'totalDataCount' => $this->users->getCount($filter)));
        } else {
            echo jsonResponse('error', $this->users->getErrorMessages() ?
                                           $this->users->getErrorMessages() :
                                           $this->lang->line('forms_searchUsersError'));
        }
    }

    /**
     * Обработка файла фотографии/аватарки, загруженной при редактировании параметров
     * аккаунта пользователя.
     */
    public function editUserdataImageUpload()
    {
        if(empty($_FILES) || empty($this->_user))
            return;
        if( !$this->input->post('mediaFieldName') )
            return;
        $mediaType = $this->input->post('mediaType');
        $originalMaxSize = FALSE;
        $originalMaxWidth = FALSE;
        $originalMaxHeight = FALSE;
        $resultWidth = FALSE;
        $resultHeight = FALSE;
        $mediaPath = FALSE; // Физ.путь к файлу картинки (отн. корня проекта)
        $mediaUrlPath = rtrim($this->config->item('static_subdomain'), '/'); // URL картинки
        switch($mediaType) {
            case MEDIA_IS_TYPE_AVATAR:
                $originalMaxSize = $this->config->item('uploads_avatar_max_size');
                $originalMaxWidth = $this->config->item('uploads_avatar_max_width');
                $originalMaxHeight = $this->config->item('uploads_avatar_max_height');
                $resultWidth = $this->config->item('avatar_width');
                $resultHeight = $this->config->item('avatar_height');
                $mediaPath = $this->config->item('avatar_path');
                break;
//            case MEDIA_IS_TYPE_:
//                $mediaMaxSize = $this->config->item('uploads_photo_max_size');
//                $mediaMaxWidth = $this->config->item('uploads_photo_max_width');
//                $mediaMaxHeight = $this->config->item('uploads_photo_max_height');
//                $mediaPath = $this->config->item('photo_path');
//                break;
//            case '': // Другие типы медиа...
            default:
                die( jsonResponse('error', $this->lang->line('forms_unknownMediaType')) );
        }
        $mediaUrlPath .= '/'.trim($mediaPath, '/');

        // Обработка загрузки файла фотки:
        $this->load->library('upload',
                             array('upload_path' => $this->config->item('uploads_path'),
                                   'allowed_types' => 'jpg|jpeg|gif|png',
                                   'max_size' => $originalMaxSize,
                                   'max_width' => $originalMaxWidth,
                                   'max_height' => $originalMaxHeight,
                                   'overwrite' => FALSE,));
        if( !$this->upload->do_upload($this->input->post('mediaFieldName')) ) {
            // Логгинг ошибки загрузки автоматически выполняется библиотекой Upload:
            die( jsonResponse('error', $this->upload->display_errors('', '')) );
        }

        $picture = $this->upload->data();
        $pictureFinalRawName = sha1($picture['file_name'].getSessionCookie());
        $pictureHash = sha1_file($picture['file_path'].'/'.$picture['file_name']);
        $pictureUrl = $mediaUrlPath.'/'.$pictureFinalRawName.$picture['file_ext'];

        // Проверка, не загружалась ли уже такая картинка:
        $res = $this->db->query('SELECT COUNT(id) count
                                 FROM multimedia
                                 WHERE type=? AND message_id IS NULL AND uri=? AND checksum=?',
                                array($mediaType, $pictureUrl, $pictureHash));
        if($res && $res->row()->count > 0) { // Фотка уже есть
            unlink($picture['file_path'].'/'.$picture['file_name']);
            die( jsonResponse('error', $this->lang->line('forms_imageAlreadyUploaded')) );
        }
        
        $this->load->library('image_lib');

        // Ресайз картинки:
        $this->image_lib->initialize(array('image_library' => 'GD2',
                                           'source_image' => $picture['file_path']
                                                            .'/'.$picture['file_name'],
                                           'width' => $resultWidth,
                                           'height' => $resultHeight,));
        if ( !$this->image_lib->resize() ) {
            unlink($picture['file_path'].'/'.$picture['file_name']);
            log_message('error', 'Error while postprocessing avatar picture. Message: '
                                .$this->image_lib->display_errors('', ''));
            die( jsonResponse('error', $this->lang->line('forms_uploadImageError')) );
        }
        $this->image_lib->clear();

        // Создание thumbnail-а картинки:
        $thumbnailFileName = "{$pictureFinalRawName}_thumb{$picture['file_ext']}";
        $thumbnailUrl = $mediaUrlPath.'/'.$thumbnailFileName;
        $this->image_lib->initialize(array('image_library' => 'GD2',
                                           'source_image' => $picture['file_path']
                                                            .'/'.$picture['file_name'],
                                           'new_image' => $picture['file_path']
                                                         .'/'.$thumbnailFileName,
                                           'create_thumb' => TRUE,
                                           'thumb_marker' => '',
                                           'width' => 75,
                                           'height' => 50,
                                           'overwrite' => FALSE,));
        if( !$this->image_lib->resize() ) {
            unlink($picture['file_path'].'/'.$picture['file_name']);
            log_message('error', 'Error while creating picture thumnail. Message: '
                                .$this->image_lib->display_errors('', ''));
            die( jsonResponse('error', $this->lang->line('forms_uploadImageError')) );
        }

        // Сохранение данных картинки в базе:
        $this->db->trans_start();
        // Удаление предыдущего файла аватарки, если он был:
        if($mediaType == MEDIA_IS_TYPE_AVATAR && !empty($this->_user->my_photo)) {
            $this->load->model('Media_Model', 'media', TRUE);
            $this->media->removeById($this->_user->my_photo);
        }
        $this->db->insert('multimedia', array('type' => $mediaType,
                                              'uri' => $pictureUrl,
                                              'thumb_uri' => $thumbnailUrl,
                                              'checksum' => $pictureHash,));
        $pictureId = $this->db->insert_id();
        $this->ion_auth->update($this->_user->id, array('my_photo' => $pictureId));
        $this->db->trans_complete();
        
        if( !$this->db->trans_status() ) {
            log_message('error', 'Error while inserting picture in DB.');
            unlink($picture['file_path'].'/'.$picture['file_name']);
            die( jsonResponse('error',
                              str_replace('#SUPPORT_EMAIL#',
                                           $this->config->item('admin_email'),
                                           $this->lang->line('forms_uploadImageError'))) );
        }

        // Перенос файла картинки и её thumbnail-а из папки загрузок в папку статики:
        if( !rename($picture['file_path'].$picture['file_name'],
                    ABSOLUTE_PATH.$mediaPath.$pictureFinalRawName.$picture['file_ext'])
         || !rename($picture['file_path'].$thumbnailFileName,
                    ABSOLUTE_PATH.$mediaPath.$thumbnailFileName) ) {
            log_message('error', 'Error while moving picture to the persistent folder.');
            unlink($picture['file_path'].'/'.$picture['file_name']);
            unlink($picture['file_path'].$thumbnailFileName);
            $this->db->delete('multimedia', array('id' => $pictureId));
            die( jsonResponse('error', str_replace('#SUPPORT_EMAIL#',
                                                   $this->config->item('admin_email'),
                                                   $this->lang->line('forms_uploadImageError'))) );
        }

        // Finally, success >^^<
        echo jsonResponse( 'success',
                           $this->lang->line('forms_uploadImageSuccess'),
                           array('fileData' => array('id' => $pictureId,
                                                     'pictureUrl' => site_url($pictureUrl),
                                                     'thumbUrl' => site_url($thumbnailUrl))) );
        /**
         * ВАЖНО! НЕ ИСПОЛЬЗОВАТЬ СТРОКУ "FALSE" В КАЧЕСТВЕ ОТВЕТА!
         * В результате событие onSubmit jQuery-плагина Valums' Ajax Upload не отработает.
         */
    }

    /**
     * Обработка файла фотографии, загруженной на форме добавления сообщения.
     */
    public function addMessageImageUpload()
    {
        if( /* !$this->input->is_ajax_request() || */ empty($_FILES) )
            return;

        // Обработка загрузки файла фотки:
        $this->load->library('upload',
                             array('upload_path' => $this->config->item('uploads_path'),
                                   'allowed_types' => 'jpg|jpeg|gif|png',
                                   'max_size' => $this->config->item('uploads_photo_max_size'),
                                   'max_width' => $this->config->item('uploads_photo_max_width'),
                                   'max_height' => $this->config->item('uploads_photo_max_height'),
                                   'overwrite' => FALSE,));
        if( !$this->upload->do_upload('photo') ) {
            // Логгинг ошибки загрузки автоматически выполняется библиотекой Upload:
            die( jsonResponse('error', $this->upload->display_errors('', '')) );
        }

        $photo = $this->upload->data();
        $photoFinalRawName = sha1($photo['file_name'].getSessionCookie());
        $photoHash = sha1_file($photo['file_path'].'/'.$photo['file_name']);
        $photoBaseUrl = rtrim($this->config->item('static_subdomain'), '/');
        $photoUrl = $photoBaseUrl.'/'.trim($this->config->item('photo_path'), '/')
                   .'/'.$photoFinalRawName.$photo['file_ext'];

        // Проверка, не загружалась ли уже такая фотка:
        $res = $this->db->query('SELECT COUNT(id) count
                                 FROM multimedia
                                 WHERE type=? AND message_id IS NULL AND uri=? AND checksum=?',
                                array(MEDIA_IS_TYPE_MESSAGE_PHOTO, $photoUrl, $photoHash));
        if($res && $res->row()->count > 0) { // Фотка уже есть
            unlink($photo['file_path'].'/'.$photo['file_name']);
            die( jsonResponse('error', $this->lang->line('forms_imageAlreadyUploaded')) );
        }

        // Создание thumbnail-а фотки:
        $thumbnailFileName = "{$photoFinalRawName}_thumb{$photo['file_ext']}";
        $thumbnailUrl = $photoBaseUrl.'/'.trim($this->config->item('photo_path'), '/')
                       .'/'.$thumbnailFileName;
        $this->load->library('image_lib', array('image_library' => 'GD2',
                                                'source_image' => $photo['file_path'].'/'
                                                                 .$photo['file_name'],
                                                'new_image' => $photo['file_path'].'/'
                                                              .$thumbnailFileName,
                                                'create_thumb' => TRUE,
                                                'thumb_marker' => '',
                                                'width' => 75,
                                                'height' => 50,
                                                'overwrite' => FALSE,));
        if( !$this->image_lib->resize() ) {
            unlink($photo['file_path'].'/'.$photo['file_name']);
            log_message('error', 'Error while creating photo thumnail. Message: '
                                .$this->image_lib->display_errors('', ''));
            die( jsonResponse('error', $this->lang->line('forms_uploadImageError')) );
        }
        
        // Сохранение данных фотки в базе:
        if( !$this->db->insert('multimedia', array('type' => MEDIA_IS_TYPE_MESSAGE_PHOTO,
                                                   'uri' => $photoUrl,
                                                   'thumb_uri' => $thumbnailUrl,
                                                   'checksum' => $photoHash,)) ) {
            log_message('error', 'Error while inserting photo in DB.');
            unlink($photo['file_path'].'/'.$photo['file_name']);
            die( jsonResponse('error', str_replace('#SUPPORT_EMAIL#',
                                                   $this->config->item('admin_email'),
                                                   $this->lang->line('forms_uploadImageError'))) );
        }
        $photoId = $this->db->insert_id();

        // Перенос файла фотки и thumbnail-а из папки загрузок в папку статики:
        if( !rename($photo['file_path'].$photo['file_name'],
                    ABSOLUTE_PATH.$this->config->item('photo_path').$photoFinalRawName.$photo['file_ext'])
         || !rename($photo['file_path'].$thumbnailFileName,
                    ABSOLUTE_PATH.$this->config->item('photo_path').$thumbnailFileName) ) {
            log_message('error', 'Error while moving photo to the persistent folder.');
            unlink($photo['file_path'].'/'.$photo['file_name']);
            unlink($photo['file_path'].$thumbnailFileName);
            $this->db->delete('multimedia', array('id' => $photoId));
            die( jsonResponse('error', str_replace('#SUPPORT_EMAIL#',
                                                   $this->config->item('admin_email'),
                                                   $this->lang->line('forms_uploadImageError'))) );
        }

        // Finally, success >^^<
        echo jsonResponse('success',
                          $this->lang->line('forms_uploadImageSuccess'),
                          array('fileData' => array('id' => $photoId,
                                                    'url' => $photoUrl,
                                                    'thumbUrl' => $thumbnailUrl)));
        /**
         * ВАЖНО! НЕ ИСПОЛЬЗОВАТЬ СТРОКУ "FALSE" В КАЧЕСТВЕ ОТВЕТА!
         * В результате событие onSubmit jQuery-плагина Valums' Ajax Upload не отработает.
         */
    }

    /**
     * Удаление файла фотографии, загруженной на форме сообщения.
     * Используется при отмене юзером одной из загруженных им фотографий.
     */
    public function addMessageImageRemove()
    {
        if( !$this->input->post('id') )
            return;

        $this->load->model('Media_Model', 'media', TRUE);
        
        echo $this->media->removeById((int)$this->input->post('id')) ?
                 jsonResponse('success') : jsonResponse('error');
    }

    /**
     * Обработка формы добавления нового сообщения с просьбой помощи.
     */
    public function addRequestProcess()
    {
        $this->load->model('Messages_Model', 'messages', TRUE);
        $this->load->model('Locations_Model', 'locations', TRUE);
        
        $photos = array_map(function($val){ return (int)$val; },
                            (array)$this->input->post('photoAttached'));

        $phones = array_map(function($val) { return preg_replace(array('/[^0-9]/'), '', $val); },
                            (array)$this->input->post('phone'));

        $category = array();
        foreach((array)$this->input->post('category') as $cat) {
            if( !empty($cat) )
                $category[] = (int)$cat;
        }

        $regionId = $this->input->post('locationRegionId') ?
                        $this->input->post('locationRegionId') :
                        $this->locations->getRegionByCoords((float)$this->input->post('locationLat'),
                                                            (float)$this->input->post('locationLng'));
        $userEmail = trim($this->input->post('email'));
        $data = array('title' => $this->input->post('messageTitle'),
                      'text' => $_POST['messageText'], //$this->input->post('messageText'),
                      'type' => 'request',
                      'category' => $category,
                      'regionId' => is_array($regionId) ? reset($regionId) : (int)$regionId,
                      'address' => $this->input->post('locationAddress'),
                      'lat' => (float)$this->input->post('locationLat'),
                      'lng' => (float)$this->input->post('locationLng'),
                      'statusId' => MESSAGE_STATUS_RECEIVED,
                      'userId' => (int)$this->input->post('userId'),
                      'isPublic' => !(bool)$this->input->post('isPrivate'),
                      'isNotifySender' => (bool)$this->input->post('sendNotices'),
                      'sender' => array('firstName' => $this->input->post('firstName'),
                                        'patrName' => $this->input->post('patrName'),
                                        'lastName' => $this->input->post('lastName'),
                                        'phones' => $phones,
                                        'email' => $userEmail,),
                      'photo' => $photos,);

        $this->output->set_header('Content-type: application/json');
        $messageId = $this->messages->add($data);
        if($messageId) {
            // Create new user account, if needed:
            if($userEmail
            && !$this->ion_auth->logged_in()
            && !$this->ion_auth->email_check($userEmail) ) {
                // Check the domain of the user email for security:
                $loginEmailDomain = explode('@', $userEmail);
                $loginEmailDomain = $loginEmailDomain[1];

                if($loginEmailDomain == $this->config->item('system_email_domain')) {
                    /** * @todo Решить, что делать в этом случае. */
//                    die( jsonResponse('error', $this->lang->line('auth_inappropriateEmail')) );
                }

                $this->load->helper('string');
                $tmpPassword = random_string('alnum', 12);
                $user =
                    $this->ion_auth->register('', $tmpPassword, $userEmail,
                                              array('first_name' => $this->input->post('firstName'),
                                                    'last_name' => $this->input->post('lastName')));
                if($user) {
                    // Email to notice the user of his new account :)
                    $this->email->clear();
                    $this->email->initialize(array('mailtype' => 'html'));
                    $this->email->from($this->config->item('admin_email'),
                                       $this->config->item('project_basename'));
                    $this->email->to($userEmail);
                    $this->email->subject('Ваша новая учётная запись на '
                                         .$this->config->item('base_url'));
                    $this->email->message($this->load->view('auth/email/addMessageRegistration',
                                                            array('activation' => $user['activation'],
                                                                  'userId' => $user['id'],
                                                                  'userEmail' => $userEmail,
                                                                  'tmpPassword' => $tmpPassword,
                                                                  'userFirstName' =>
                                                                      $this->input->post('firstName'),
                                                                  'userLastName' =>
                                                                      $this->input->post('lastName'),),
                                                            TRUE));
                    $this->email->send();
                } else { // // Send error report
                    $errorMessages = "Some error occures in user registration while message creation. Message data: \n".print_r($data, TRUE);
                    $this->email->from($this->config->item('admin_email'), $this->config->item('project_basename'));
                    $this->email->to($this->config->item('admin_email'));
                    $this->email->subject($this->config->item('base_url').' - error in user registration while creating the message');
                    $this->email->message($errorMessages);
                    $this->email->send();

                    log_message('error', $errorMessages);
                }
            }

            $orgs = $this->organizations->getRelevantByMessage($messageId);
            $ncOrg = $gOrg = array();
            foreach((array)$orgs as $org) {
                switch($org['typeId']) {
                    case 1:
                        if(count($ncOrg) < 5)
                            $ncOrg[] = $org;
                        break;
                    case 2:
                        if(count($gOrg) < 5)
                            $gOrg[] = $org;
                        break;
                    default:
                }
            }

            $possibleData = array('volunteers' => $this->messages->getRelevantByMessage($messageId, array(), 5),
                                  'ncOrg' => $ncOrg,
                                  'gOrg' => $gOrg);
            echo jsonResponse('success', '', array('possibleHelp' => $possibleData));
        } else {
            $errorMessages = "Some error occures in message creation. Message data: \n".print_r($data, TRUE);
            $this->load->library('email');
            $this->email->from($this->config->item('admin_email'), $this->config->item('project_basename'));
            $this->email->to($this->config->item('admin_email'));
            $this->email->subject($this->config->item('base_url').' - error while creating message');
            $this->email->message($errorMessages);
            $this->email->send();

            log_message('error', $errorMessages);

            echo jsonResponse('error', $this->messages->getErrorMessages() ?
                                           $this->messages->getErrorMessages() :
                                           $this->lang->line('forms_addRequestError'));
        }
    }

    /**
     * Обработка формы добавления нового сообщения с предложением помощи.
     */
    public function addOfferProcess()
    {
        $this->load->model('Messages_Model', 'messages', TRUE);
        $this->load->model('Locations_Model', 'locations', TRUE);
       

        $this->load->library('email');

        $photos = array_map(function($val){ return (int)$val; },
                            (array)$this->input->post('photoAttached'));

        $phones = (array)$this->input->post('phone');
        foreach($phones as &$phone) {
            $phone = preg_replace(array(/*'/^(\+7|8)/',*/ '/[^0-9]/'), '', $phone);
        }

        $category = array();
        foreach((array)$this->input->post('category') as $cat) {
            if( !empty($cat) )
                $category[] = (int)$cat;
        }

        $regionId = $this->input->post('locationRegionId') ?
                        (int)$this->input->post('locationRegionId') :
                        $this->locations->getRegionByCoords((float)$this->input->post('locationLat'),
                                                            (float)$this->input->post('locationLng'));
        $userEmail = trim($this->input->post('email'));
        $data = array('title' => $this->input->post('messageTitle'),
                      'text' => $_POST['messageText'], //$this->input->post('messageText'),
                      'type' => 'offer',
                      'category' => $category,
                      'regionId' => $regionId,
                      'address' => $this->input->post('locationAddress'),
                      'lat' => (float)$this->input->post('locationLat'),
                      'lng' => (float)$this->input->post('locationLng'),
                      'statusId' => MESSAGE_STATUS_RECEIVED,
                      'userId' => (int)$this->input->post('userId'),
                      'isPublic' => !(bool)$this->input->post('isPrivate'),
                      'isNotifySender' => (bool)$this->input->post('sendNotices'),
                      'sender' => array('firstName' => $this->input->post('firstName'),
//                                        'patrName' => htmlentities(trim($this->input->post('patrName')),
//                                                                   ENT_QUOTES, 'UTF-8', false),
                                        'lastName' => $this->input->post('lastName'),
                                        'phones' => $phones,
                                        'email' => $userEmail,),
                      'period' => htmlentities(trim($this->input->post('aidingTimes')),
                                               ENT_QUOTES, 'UTF-8', FALSE),
                      'distance' => htmlentities(trim($this->input->post('aidingDistance')),
                                                 ENT_QUOTES, 'UTF-8', FALSE),
                      'distanceEmergency' =>
                          htmlentities(trim($this->input->post('aidingDistanceEmergency')),
                                       ENT_QUOTES, 'UTF-8', FALSE),
                      'photo' => $photos,);

        $this->output->set_header('Content-type: application/json');
        $messageId = $this->messages->add($data);
        if($messageId) {
            // Create new user account, if needed:
            if( !$this->ion_auth->logged_in() && !$this->ion_auth->email_check($userEmail) ) {
                // Check the domain of the user email for security:
                $loginEmailDomain = explode('@', $userEmail);
                $loginEmailDomain = $loginEmailDomain[1];

                if($loginEmailDomain == $this->config->item('system_email_domain')) {
                    /** * @todo Решить, что делать в этом случае. */
//                    die( jsonResponse('error', $this->lang->line('auth_inappropriateEmail')) );
                }

                $this->load->helper('string');
                $tmpPassword = random_string('alnum', 12);
                $user =
                    $this->ion_auth->register('', $tmpPassword, $userEmail,
                                              array('first_name' => $this->input->post('firstName'),
                                                    'last_name' => $this->input->post('lastName')));
                if($user) {
                    // Email to notice the user of his new account :)
                    $this->email->clear();
                    $this->email->initialize(array('mailtype' => 'html'));
                    $this->email->from($this->config->item('admin_email'),
                                       $this->config->item('project_basename'));
                    $this->email->to($userEmail);
                    $this->email->subject('Ваша новая учётная запись на '
                                         .$this->config->item('base_url'));
                    $this->email->message($this->load->view('auth/email/addMessageRegistration',
                                                            array('activation' => $user['activation'],
                                                                  'userId' => $user['id'],
                                                                  'userEmail' => $userEmail,
                                                                  'tmpPassword' => $tmpPassword,
                                                                  'userFirstName' =>
                                                                      $this->input->post('firstName'),
                                                                  'userLastName' =>
                                                                      $this->input->post('lastName'),),
                                                            TRUE));
                    $this->email->send();
                } else { // // Send error report
                    $errorMessages = "Some error occures in user registration while message creation. Message data: \n".print_r($data, TRUE);
                    $this->email->from($this->config->item('admin_email'), $this->config->item('project_basename'));
                    $this->email->to($this->config->item('admin_email'));
                    $this->email->subject($this->config->item('base_url').' - error in user registration while creating the message');
                    $this->email->message($errorMessages);
                    $this->email->send();

                    log_message('error', $errorMessages);
                }
            }

            $orgs = $this->organizations->getRelevantByMessage($messageId);
            $ncOrg = $gOrg = array();
            foreach($orgs as $org) {
                switch($org['typeId']) {
                    case 1:
                        if(count($ncOrg) < 5)
                            $ncOrg[] = $org;
                        break;
                    case 2:
                        if(count($gOrg) < 5)
                            $gOrg[] = $org;
                        break;
                    default:
                }
            }

            $possibleData = array('volunteers' =>
                                      $this->messages->getRelevantByMessage($messageId, array(), 5),
                                  'ncOrg' => $ncOrg,
                                  'gOrg' => $gOrg);
            echo jsonResponse('success', '', array('possibleHelp' => $possibleData));
        } else {
            $errorMessages = "Some error occures in message creation. Message data: \n".print_r($data, TRUE);
            // Send error report:
            $this->email->from($this->config->item('admin_email'), $this->config->item('project_basename'));
            $this->email->to($this->config->item('admin_email'));
            $this->email->subject($this->config->item('base_url').' - error while creating message');
            $this->email->message($errorMessages);
            $this->email->send();

            log_message('error', $errorMessages);

            echo jsonResponse('error', $this->messages->getErrorMessages() ?
                                           $this->messages->getErrorMessages() :
                                           $this->lang->line('forms_addOfferError'));
        }
    }

    /**
     * Обработка формы редактирования имени пользователя (страница редактирования профиля).
     */
    public function editUserPersonalData()
    {
        if(empty($this->_user)
        || !$this->input->post('userId')
        || $this->input->post('userId') != $this->_user->id)
            redirect('/auth/login');
        
        $this->load->model('Users_Model', 'user', TRUE);

        $updateFields = array();
        if($this->input->post('firstName'))
            $updateFields['first_name'] = $this->input->post('firstName');
        if($this->input->post('lastName'))
            $updateFields['last_name'] = $this->input->post('lastName');

        $flags = ($this->input->post('isPrivate') ? USER_DATA_IS_PRIVATE : 0x0);
        if($this->_user->flags != $flags)
            $updateFields['flags'] = $flags;

        if($this->input->post('about'))
            $updateFields['about_me'] = $this->input->post('about');
        if($this->input->post('gender') !== FALSE) {
            if($this->input->post('gender') == 0)
                $updateFields['gender'] = 0;
            else
                $updateFields['gender'] = $this->input->post('gender') == 1 ? 1 : 2;
        }

        if($updateFields)
            $res = $this->ion_auth->update($this->input->post('userId'), $updateFields);

        echo $res ? jsonResponse('success', $this->lang->line('forms_editUserDataSuccess')) :
                    jsonResponse('error', $this->lang->line('forms_ajaxProcessError'));
    }

    /**
     * Обработка формы редактирования контактных данных пользователя (страница
     * редактирования профиля).
     */
    public function editUserContacts()
    {
        if(empty($this->_user)
        || !$this->input->post('userId')
        || $this->input->post('userId') != $this->_user->id)
            redirect('/auth/login');

        // Профили юзера в соц.сетях:
        $this->load->model('Social_Net_Model', 'socNets', TRUE);
        $socNetProfiles = array();
        $errors = array();
        foreach($this->socNets->getList() as $socNet) {
            $profileUrl = $this->input->post('socNetProfile_'.$socNet['id']);
            if( !$profileUrl )
                continue;

            $socNetIdGiven = $this->socNets->getSocNetByProfile($profileUrl);
            if($socNetIdGiven && $socNetIdGiven == $socNet['id'])
                $socNetProfiles[] = array('id' => $socNet['id'], 'url' => $profileUrl,);
            else
                $errors[] = str_replace('#SOC_NET#',
                                        $socNet['title'],
                                        $this->lang->line('forms_socNetUrlInvalid'));
        }
        if($errors)
            die( jsonResponse('error', implode('<br />', $errors)) );
        else if($socNetProfiles) {
            $this->load->model('Users_Model', 'user', TRUE);
            if( !$this->user->updateUserSocProfiles($this->input->post('userId'),
                                                    $socNetProfiles) ) 
                jsonResponse('error', $this->lang->line('forms_ajaxProcessError'));
        }

        $updateFields = array();
        if($this->input->post('phone'))
            $updateFields['phones'] = '{'.implode(',', (array)$this->input->post('phone')).'}';
        if($this->input->post('isPublic'))
            $updateFields['isPublic'] = (boolean)$this->input->post('isPublic');

        if( !$updateFields
         || $this->ion_auth->update($this->input->post('userId'), $updateFields) )
            die( jsonResponse('success', $this->lang->line('forms_editUserDataSuccess')) );
        else
            jsonResponse('error', $this->lang->line('forms_ajaxProcessError'));
    }

    /**
     * Обработка формы добавления нового профиля волонтёрства для пользователя.
     */
    public function addVolunteerProfileProcess()
    {
        if(empty($this->_user) || !$this->ion_auth->logged_in() )
            redirect('/auth/login');

        $this->load->model('Volunteer_Profiles_Model', 'profiles', TRUE);
        $this->load->model('Locations_Model', 'locations', TRUE);

        if( $this->profiles->getCount(array('title' => $this->input->post('title'),
                                            'userId' => $this->_user->id)) ) {
            die( jsonResponse('error',
                              str_replace('#VP_TITLE#',
                                          $this->input->post('title'),
                                          $this->lang->line('forms_addVpTitleExists'))) );
        }

        $category = array();
        foreach((array)$this->input->post('category') as $cat) {
            if( !empty($cat) )
                $category[] = (int)$cat;
        }
        
        $regionId = 0;
        if($this->input->post('locationRegionId'))
            $regionId = (int)$this->input->post('locationRegionId');
        else {
            $tmp = $this->locations->getRegionByCoords((float)$this->input->post('locationLat'),
                                                       (float)$this->input->post('locationLng'));
            if($tmp)
                $regionId = reset($tmp);
        }

        $data = array('title' => $this->input->post('title'),
                      'category' => $category,
                      'regionId' => $regionId,
                      'address' => $this->input->post('locationAddress'),
                      'lat' => (float)$this->input->post('locationLat'),
                      'lng' => (float)$this->input->post('locationLng'),
                      'isActive' => TRUE,
                      'isAllCategories' => (bool)$this->input->post('allCategories'),
                      'userId' => $this->_user->id,
                      'days' => (array)$this->input->post('helpDays'),
                      'distance' => (int)$this->input->post('aidingDistance')*1000,
                      'mailoutEmail' => $this->input->post('mailoutEmail') ?
                                            trim($this->input->post('mailoutEmail')) : FALSE,);

        $this->output->set_header('Content-type: application/json');
        $profileId = $this->profiles->add($data);
        if($profileId)
            echo jsonResponse('success', '', $this->profiles->getById($profileId));
        else {
            $errorMessages = "Some error occures in profile creation. Profile data: \n".print_r($data, TRUE);
            $this->load->library('email');
            $this->email->from($this->config->item('admin_email'), $this->config->item('project_basename'));
            $this->email->to($this->config->item('admin_email'));
            $this->email->subject($this->config->item('base_url').' - error while creating profile');
            $this->email->message($errorMessages);
            $this->email->send();

            log_message('error', $errorMessages);

            echo jsonResponse('error', $this->profiles->getErrorMessages() ?
                                           $this->profiles->getErrorMessages() :
                                           $this->lang->line('forms_addVpError'));
        }
    }
    
    /**
     * Обработка формы редактирования профиля волонтёрства пользователя.
     */
    public function editVolunteerProfile()
    {
        $this->load->model('Volunteer_Profiles_Model', 'profiles', TRUE);

        if(empty($this->_user)
        || $this->profiles->getProfileUserId($this->input->post('vpId')) != $this->_user->id)
            redirect('/auth/login');

        $this->load->model('Locations_Model', 'locations', TRUE);

        $updateFields = array();
        if($this->input->post('aidingDistance') !== FALSE) {
            $updateFields['distance'] = (int)$this->input->post('aidingDistance')*1000;
            if( !$updateFields['distance'] )
                $updateFields['distance'] = 1000;
        }
        if($this->input->post('title') && $this->input->post('titleChanged')) {
            if( $this->profiles->getCount(array('title' => $this->input->post('title'),
                                                'userId' => $this->_user->id)) ) {
                die( jsonResponse('error',
                                  str_replace('#VP_TITLE#',
                                              $this->input->post('title'),
                                              $this->lang->line('forms_addVpTitleExists'))) );
            }
            $updateFields['title'] = $this->input->post('title');
        }
        if($this->input->post('helpDays'))
            $updateFields['days'] = (array)$this->input->post('helpDays');
        if($this->input->post('mailoutEmail'))
            $updateFields['mailoutEmail'] = $this->input->post('mailoutEmail') ?
                                                trim($this->input->post('mailoutEmail')) : FALSE;
        else
            $updateFields['mailoutEmail'] = '';
        
        if( !$updateFields )
            die(jsonResponse('success', ''));

        echo $this->profiles->update($this->input->post('vpId'), $updateFields) ?
                 jsonResponse( 'success', '', array('id' => $this->input->post('vpId'),
                                                    'newVpTitle' => $this->input->post('title')) ) :
                 jsonResponse('error', $this->lang->line('forms_ajaxProcessError'));
    }
    
    /**
     * Обработка формы удаления профиля волонтёрства.
     */
    public function deleteVolunteerProfileProcess()
    {
        if(empty($this->_user) || !$this->ion_auth->logged_in() )
            redirect('/auth/login');

        $this->load->model('Volunteer_Profiles_Model', 'profiles', TRUE);

        if($this->_user && $this->_user->password
        == $this->ion_auth_model->hash_password_db($this->_user->email, $this->input->post('deleteVpPass'))) {
            die($this->profiles->delete((int)$this->input->post('vpId')) ?
                    jsonResponse('success', $this->lang->line('forms_deleteVpSuccess')) :
                    jsonResponse('error', $this->profiles->getErrorMessages() ?
                                              $this->profiles->getErrorMessages() :
                                              $this->lang->line('forms_deleteVpError')));
        } else
            die(jsonResponse('error', $this->profiles->getErrorMessages() ?
                                          $this->profiles->getErrorMessages() :
                                          $this->lang->line('forms_deleteVpPasswordIncorrectError')));
    }
    
    public function addCommentProcess()
    {
        $this->load->model('Comments_Model', 'comments', TRUE);
        $this->load->model('Users_Model', 'users', TRUE);
        $this->load->model('Subscriber_Model', 'subscriber', TRUE);
        
        $arrayToSend = array(                              
            'messageId' => $this->input->post('messageId'), 
            'parentId' => $this->input->post('parentId'), 
            'text' => $_REQUEST["text"],//$this->input->post('text'), 
            'userId' => $this->input->post('userId')? $this->input->post('userId'): null,
        );
        
        if(!$this->input->post('userId')){
            $arrayToSend['userName'] = $this->input->post('userName');
            $arrayToSend['email'] = $this->input->post('email');
        } else {
          $user = $this->users->getMetaById($this->input->post('userId'));
          $arrayToSend['userName'] = $user['first_name'].' ';
          $arrayToSend['userName'] .= !$user['is_private']? $user['last_name']: strtoupper(mb_substr($user['last_name'], 0, 1));
        }

        echo $commentId = $this->comments->add($arrayToSend);
        
        //подписка на комменты
        if($this->input->post('subscribe') && $commentId){
            $email = $this->input->post('email')? $this->input->post('email'): '';
            $userId = $this->input->post('userId')? $this->input->post('userId'): null;
            $name = $this->input->post('userName')? $this->input->post('userName'): '';
            echo $subscribeId = $this->subscriber->add(
                      1,
                      $this->input->post('parentId'),
                      $email,
                      $name,
                      $userId
            );          
        }        
        
        //отправка уведомлений
        if($commentId){
          $list = $this->subscriber->getEmailList(
                  $this->input->post('messageId'), 
                  $this->input->post('parentId')
          );
          
          $this->mailoutNewCommentNotice($list, $commentId, $this->input->post('messageId'));
        }
      
    }
    
    public function setStatusCommentProcess()    
    {
        $this->load->model('Comments_Model', 'comments', TRUE);
        switch($this->input->post('action')) {
            case 'delete':
                $status = COMMENT_STATUS_DELETED;
                break;
            case 'spam':
                $status = COMMENT_STATUS_SPAM;
                break;
            case 'del_by_moder':
                $status = COMMENT_STATUS_DELETED_BY_MODER;
                break;
            default:
                $status = false;
        }
        if($this->_checkStatusChangeAccess($status, $this->input->post('commentId'))){
            $this->comments->setStatus((int)$this->input->post('commentId'), $status);
        }
    }  
    
    //можно ли данному юзеру изменить статус данного комментария. для защиты от удаления и прочих действий над 
    //чужими комментами каких-нибудь умельцев
    private function _checkStatusChangeAccess($statusId, $commentId)
    {
        if(!$statusId)
            return false;
        
        //проверка на модератора
        if(in_array($statusId, array(COMMENT_STATUS_SPAM, COMMENT_STATUS_DELETED_BY_MODER)) &&
           $this->ion_auth->profile()->group != 'moder'){
            return false;
        }
        
        //проверка на принадлежность юзеру коммента
        if($statusId != COMMENT_STATUS_DELETED){
            $this->load->model('Comments_Model', 'comments', TRUE);
            $comment = $this->comments->getbyId($commentId);
            if($comment['userId'] != $this->ion_auth->profile()->id){
                return false;
            }
        }
        
        return true;
    }

    public function countComments()
    {
        $this->load->model('Comments_Model', 'comments', TRUE);

        echo json_encode(array(
            'allComments' =>
                $this->comments->getCount(array('messageId' => $this->input->post('messageId'),
                                                'statusId' => array(COMMENT_STATUS_RECEIVED,
                                                                    COMMENT_STATUS_MODERATED,
                                                                    COMMENT_STATUS_DELETED,
                                                                    COMMENT_STATUS_DELETED_BY_MODER))), 
            'premoderating' =>
                $this->comments->getCount(array('messageId' => $this->input->post('messageId'),
                                                'statusId' => COMMENT_STATUS_RECEIVED))
        ));
    }
    
    public function getCommentsTree()
    {
        $this->load->model('Comments_Model', 'comments', TRUE);
        $this->load->model('Users_Model', 'users', TRUE);
        $this->load->model('Subscriber_Model', 'subscriber', TRUE);
        
        $list = $this->comments->getList(array('messageId' => $this->input->post('messageId'),
                                               'statusId' => array(COMMENT_STATUS_RECEIVED,
                                                                   COMMENT_STATUS_MODERATED,
                                                                   COMMENT_STATUS_DELETED,
                                                                   COMMENT_STATUS_SPAM,
                                                                   COMMENT_STATUS_DELETED_BY_MODER)),
                                         array('depth' => 'desc', 'dateAdded' => 'asc'));
        
        //список подписок на комменты
        $subscrIds = array();
        if(isset($this->_user->id)){
            $subscrList = $this->subscriber->getList(array('subscribe_type' => 1, 'user_id' => $this->_user->id));
            foreach($subscrList as $subscr){
                $subscrIds[] = $subscr->subscribe_to;
            }
        } 
        
        // Формирование дерева комментариев:
        $tree = $list;
        foreach ($list as $key => $comment) {
            //прицепление картинки аватара
            $comment['avatar_url'] = 0;
            if($comment['userId'] > 0 && $userMeta = $this->users->getMetaById($comment['userId'])){
                $tree[$key]['avatar_url'] = $userMeta['avatar_url'] ?
                                                $userMeta['avatar_url'] :
                                                '/css/i/anonymous_thumb.png';
            } else {
                $tree[$key]['avatar_url'] = '/css/i/anonymous_thumb.png';
            }
            //форматирует дату
            $tree[$key]['dateAdded'] = date( 
                    $this->config->item('comments_date_format'), 
                    $tree[$key]['dateAdded']
            );
            
            //помечаем комменты, на которые подписаны
            $tree[$key]['isSubscribed'] = in_array($comment['id'], $subscrIds)? true: false;

            if($comment['parentId'] !=0 && isset($tree[ $comment['parentId'] ])) {
                $tree[$comment['parentId']]['children'][] = $tree[$key];
                unset($tree[$key]);
            }
        }

        $clearTree = array();
        foreach($tree as $comment){
            $clearTree[] = $comment;
        }   

        echo json_encode($clearTree);
    }  
    
    public function mailoutNewCommentNotice($list, $commentId, $messageId, $print = false)
    {
        $this->load->model('Messages_Model', 'messages', TRUE);
        $this->load->model('Comments_Model', 'comments', TRUE);
        $this->load->model('Users_Model', 'users', TRUE);
        $this->load->library('email');
        
 
        
        $comment = $this->comments->getById($commentId);
        $message = $this->messages->getById($messageId);
        $parentComment = $comment['parentId']? $this->comments->getById($comment['parentId']): 0;
        //$commenter = $this->users->getMetaById($comment['userId']);
        
        foreach($list as $item){
            $emailTemplateName = $item->subscribe_type? 'commentNewAnswer': 'messageNewComment';

            if($print) {
                /**
                 * Режим для отладки шаблона письма (вывод шаблона в stdout).
                 */
                 $this->output->set_header('Content-type: text/html; charset=utf-8');
                 $mailText = $this->load->view('email/'.$emailTemplateName, 
                                             array('subscriber' => $item,
                                                   'message' => $message,
                                                   'comment' => $comment,
                                                   'parentComment' => $parentComment
                 ));                 
                 
            } else {
              
              $this->email->initialize(array('charset' => 'utf-8',
                                       'mailtype' => 'html',));              
              $this->email->to($item->email);
              $this->email->from($this->config->item('mailout_email_from'),
                                 $this->config->item('project_basename'));
              $this->email->subject('новый ответ');   
              //var_dump('email/'.$emailTemplateName);exit;
              $mailText = $this->load->view('email/'.$emailTemplateName, 
                                         array('subscriber' => $item,
                                               'message' => $message,
                                               'comment' => $comment,
                                               'parentComment' => $parentComment
                                             ), TRUE);
                $this->email->message($mailText);               
                $this->email->send();
                $this->email->clear();                
            }
        }
        
        //письмо модератору
        $this->email->initialize(array('charset' => 'utf-8',
                                 'mailtype' => 'html',));  
        $this->email->to($this->config->item('moderator_email'));
        $this->email->from($this->config->item('mailout_email_from'),
                           $this->config->item('project_basename'));
        $this->email->subject('новый ответ');              
        $mailText = $this->load->view('email/moderNewComment', 
                                   array('message' => $message,
                                         'comment' => $comment,
                                         'parentComment' => $parentComment
                                       ), TRUE);
          $this->email->message($mailText);               
          $this->email->send();
          $this->email->clear();        
    }
    
}
