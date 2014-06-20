<?php if( !defined('BASEPATH') ) exit('No direct script access allowed');
/**
 * Файл содержит определение класса Media_Model (модель данных)
 *
 * @copyright  Copyright (c) 2011 Звягинцев Л. aka Ahaenor
 * @link       /application/models/media_model.php
 * @version    0.1
 * @since      Файл доступен начиная с версии проекта 0.5
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/
 */

/**
 * Модель для работы с медиа, т.е. фотками, видео и пр.
 */
class Media_Model extends Rynda_Model
{
    /**
     * Для удобства обращения с кодами ошибок модели можно определить их как
     * константы класса модели:
     */
    const ERROR_DELETING_MEDIA_NONEXISTENT = 1;
    const ERROR_DELETING_MEDIA_DB = 2;
    const ERROR_DELETING_MEDIA_FILE = 3;
    const ERROR_DELETING_MEDIA_THUMB = 4;
    const ERROR_MEDIA_FILE_NOT_FOUND = 5;
    const ERROR_MEDIA_THUMB_NOT_FOUND = 6;
//    const SOME_ERROR_CODE = 1;

    public function __construct()
    {
        parent::__construct();
        self::$_errorTypes = array(self::ERROR_DELETING_MEDIA_NONEXISTENT =>
                                       $this->lang->line('modelMedia_errorDeletingMediaNone'),
                                   self::ERROR_DELETING_MEDIA_DB =>
                                       $this->lang->line('modelMedia_errorDeletingMediaDb'),
                                   self::ERROR_MEDIA_FILE_NOT_FOUND =>
                                       $this->lang->line('modelMedia_errorMediaFileNotFound'),
                                   self::ERROR_MEDIA_THUMB_NOT_FOUND =>
                                       $this->lang->line('modelMedia_errorMediaThumbNotFound'),
                                   self::ERROR_DELETING_MEDIA_FILE =>
                                       $this->lang->line('modelMedia_errorDeletingMediaFile'),
                                   self::ERROR_DELETING_MEDIA_THUMB =>
                                       $this->lang->line('modelMedia_errorDeletingMediaThumb'),
//                                   self::SOME_ERROR_CODE =>
//                                       $this->lang->line('modelMessages_error'),
                                  );
    }

    /**
     * Метод для получения данных об элементе медиа по его ID-у.
     *
     * @param $id integer ID элемента медиа.
     * @return mixed Если элемент медиа не найден, возвращается FALSE. Иначе будет возвращён
     * массив полей элемента медиа, содержащий следующие поля:
     * * id integer ID элемента медиа.
     * * type integer Тип элемента медиа (см. константы MEDIA_IS_TYPE_*).
     * * uri string URL к файлу медиа (отн. корня сайта или корня статического поддомена).
     * Если элемент медиа не представлен файлом, поле не заполняется.
     * * thumb_uri string URL к файлу thumbnail-а медиа (отн. корня сайта или корня
     * статического поддомена). Если элемент медиа не представлен файлом, поле не заполняется.
     * * message_id integer ID сообщения, к которому привязан элемент медиа. Если элемент
     * медиа не привязан к сообщению, поле не заполняется.
     * * checksum string Хэш URL-а элемента медиа.
     */
    public function getById($id)
    {
        $this->_clearErrors();

        $id = (int)$id;
        if($id <= 0)
            return FALSE;
        else {
            $res = $this->db->get_where('multimedia', array('id' => $id), 1);
            return $res ? $res->row_array() : FALSE;
        }
    }
    
    /**
     * Удаление элемента медиа. Если элемент представлен файлом или несколькими файлами,
     * они также удаляются.
     *
     * @param $id integer ID элемента медиа.
     * @return boolean TRUE в случае успешного удаления, иначе FALSE. В последнем случае
     * ошибки удаления доступны с помощью методов класса Rynda_Model.
     */
    public function removeById($id)
    {
        $this->_clearErrors();

        $id = (int)$id;
        if($id <= 0)
            return FALSE;
        else {
            $media = $this->getById($id);
            if( !$media ) {
                $this->_addError(self::ERROR_DELETING_MEDIA_NONEXISTENT);
                return FALSE;
            }
            if( !$this->db->query('DELETE FROM multimedia WHERE message_id IS NULL AND id=?',
                                  array($id)) ) {
                /** * @todo Логгить ошибку удаления фотки из базы */
                $this->_addError(self::ERROR_DELETING_MEDIA_DB);
            }
            if( !file_exists(ABSOLUTE_PATH.$media['uri']) ) {
                /** * @todo Логгить ошибку "файл фотки не существует" */
                $this->_addError(self::ERROR_MEDIA_FILE_NOT_FOUND);
            } else if( !@unlink(ABSOLUTE_PATH.$media['uri']) ) {
                /** * @todo Логгить ошибку удаления файла фотки */
                $this->_addError(self::ERROR_DELETING_MEDIA_FILE);
            }
            if( !file_exists(ABSOLUTE_PATH.$media['thumb_uri']) ) {
                /** * @todo Логгить ошибку "файл thumbnail-а фотки фотки не существует" */
                $this->_addError(self::ERROR_MEDIA_THUMB_NOT_FOUND);
            } else if( !@unlink(ABSOLUTE_PATH.$media['thumb_uri']) ) {
                /** * @todo Логгить ошибку удаления файла thumbnail-а фотки */
                $this->_addError(self::ERROR_DELETING_MEDIA_THUMB);
            }
            
            return $this->_lastErrors ? FALSE : TRUE;
        }
    }
}