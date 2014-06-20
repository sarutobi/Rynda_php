<?php if( !defined('BASEPATH') ) exit('No direct script access allowed');
/**
 * Файл содержит определение класса Info_Pages_Model (модель данных)
 *
 * @copyright  Copyright (c) 2011 Звягинцев Л. aka Ahaenor
 * @link       /application/models/info_pages_model.php
 * @version    0.1
 * @since      Файл доступен начиная с версии проекта 0.1
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/
 */

/**
 * Модель для работы с информационными страницами сайта.
 */
class Info_Pages_Model extends Rynda_Model
{
    /**
     * Для удобства обращения с кодами ошибок модели можно определить их как
     * константы класса модели:
     */
//    const SOME_ERROR_CODE = 1;

    public function __construct()
    {
        parent::__construct();
//        self::$_errorTypes = array(self::SOME_ERROR_CODE =>
//                                       $this->lang->line('modelMessages_error'),);
    }

    /**
     * Метод для получения данных информационной страницы по её ID-у.
     *
     * @param $id integer ID страницы.
     * @param $returnPageText boolean Если передано true, среди прочих параметров функция
     * вернёт текст страницы. По умолчанию false.
     * @return mixed Если страница не найдена, возвращается FALSE. Иначе будет возвращён
     * массив полей страницы, содержащий следующие поля:
     * * id integer ID страницы.
     * * title string Заголовок страницы.
     * * slug string Короткое ЧПУ-дружественное название страницы.
     * * text string Текст страницы. Поле содержится в массиве только в случае, когда аргумент
     * $returnPageText имеет значение TRUE.
     * * isActive boolean Активность страницы.
     */
    public function getById($id, $returnPageText = FALSE)
    {
        $this->_clearErrors();

        $id = (int)$id;
        if($id <= 0)
            return FALSE;
        else {
            $res = $this->db->get_where('infopages_view', array('page_id' => $id), 1)->row();
            if(empty($res))
                return FALSE;

            $res = array('id' => $res->page_id,
                         'title' => $res->page_title,
                         'slug' => $res->page_slug,);
            if((bool)$returnPageText)
                $res['text'] = reset($this->db->query("SELECT text FROM public.show_info_page({$id})")->row());

            return $res;
        }
    }

    /**
     * Метод для получения данных информационной страницы по её короткому имени.
     *
     * @param $slug string Короткое название страницы.
     * @param $returnPageText boolean Если передано true, среди прочих параметров функция
     * вернёт текст страницы. По умолчанию false.
     * @return mixed Если страница не найдена, возвращается FALSE. Иначе будет возвращён
     * массив полей страницы, содержащий следующие поля:
     * * id integer ID страницы.
     * * title string Заголовок страницы.
     * * slug string Короткое ЧПУ-дружественное название страницы.
     * * text string Текст страницы. Поле содержится в массиве только в случае, когда аргумент
     * $returnPageText имеет значение TRUE.
     * * isActive boolean Активность страницы.
     */
    public function getBySlug($slug, $returnPageText = FALSE)
    {
        $this->_clearErrors();

        $slug = trim($slug);
        if( empty($slug) )
            return FALSE;
        else {
            $res = $this->db->get_where('infopages_view', array('page_slug' => $slug), 1)->row();
            if(empty($res))
                return FALSE;

            $res = array('id' => $res->page_id,
                         'title' => $res->page_title,
                         'slug' => $res->page_slug,);
            if((bool)$returnPageText)
                $res['text'] = reset($this->db->query("SELECT text FROM public.show_info_page({$res['id']})")->row());

            return $res;
        }
    }
}