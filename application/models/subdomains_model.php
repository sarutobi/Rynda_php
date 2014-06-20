<?php if( !defined('BASEPATH') ) exit('No direct script access allowed');
/**
 * Файл содержит определение класса Subdomains_Model (модель данных)
 *
 * @copyright  Copyright (c) 2011 Звягинцев Л. aka Ahaenor
 * @link       /application/models/subdomains_model.php
 * @version    0.1
 * @since      Файл доступен начиная с версии проекта 0.1
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/
 */

/**
 * Модель для работы с дочерними картами (сателлитами) проекта.
 */
class Subdomains_Model extends Rynda_Model
{
    /**
     * Для удобства обращения с кодами ошибок модели можно определить их как
     * константы класса модели:
     */
//    const ERROR_OF_SOME_KIND = 1;

//    public function __construct()
//    {
//        parent::__construct();
//        self::$_errorTypes = array(self::ERROR_OF_SOME_KIND =>
//                                       $this->lang->line(''),);
//    }

    /**
     * Получение списка дочерних карт, соотв. указанным параметрам фильтрации.
     *
     * @param array $filter Массив параметров фильтрации. Возможные значения полей:
     * * id mixed Id сателлита или массив Id.
     * * url string Имя субдомена, на котором размещён сателлит.
     * * isCrysis boolean Считается ли сателлит "кризисом". True для выборки кризисных
     * сателлитов, false для не-кризисных. По умолчанию не передано, и параметр в
     * фильтрации не участвует.
     * * status integer Статус сателлита.
     * * limit mixed Кол-во строк в ответе. Можно передать целое положительное число, либо
     * строку вида 'начальный_индекс,кол-во_эл-тов', либо массив вида
     * array(начальный_индекс, кол-во_эл-тов). По умолчанию не ограничено.
     * @param array $order Массив параметров сортировки результатов. Ключи массива -
     * названия полей сателлита, значения - порядок сортировки по соотв. полю (возможные
     * значения: asc - по возрастанию, desc - по убыванию). По умолчанию результаты
     * сортируются по полю isCrysis в убыв. порядке, затем по полю order в возр. порядке.
     * @return array Массив сателлитов, содержащий строки со следующими полями:
     * * id integer Id сателлита.
     * * url string Имя субдомена, на котором размещён сателлит.
     * * title string Название сателлита.
     * * isCrysis boolean Считается ли сателлит "кризисом".
     * * status integer Текущий статус сателлита.
     * * order integer Индекс сортировки.
     */
    public function getList(array $filter = array(), array $order = array())
    {
        if(empty($order)) // Параметры сортировки по умолчанию
            $order = array('order' => 'asc',);

        // Параметры фильтрации:
        $this->db->from('subdomain_view');

        if( !empty($filter['id']) )
            $this->db->where('id', (int)$filter['id']);

        if( !empty($filter['url']) )
            $this->db->where('url', urlencode(trim($filter['url'])));

        if( !empty($filter['title']) )
            $this->db->like('title', trim($filter['url']));

        if( isset($filter['isCrysis']) )
            $this->db->where('url', (boolean)$filter['isCrysis']);

        if( !empty($filter['status']) )
            $this->db->where('status', (int)$filter['status']);

        if( !empty($filter['limit']) )
            $this->_addLimit($filter['limit']);

        // Параметры сортировки результата:
        foreach($order as $field => $orderBy) {
            $orderBy = trim($orderBy) == 'desc' ? 'desc' : 'asc';
            switch($field) {
                case 'id':
                case 'url':
                case 'title':
                case 'status':
                    $this->db->order_by($field, $orderBy);
                    break;
                case 'isCrysis':
                    $this->db->order_by('is_current', $orderBy);
                    break;
                case 'order': // По умолчанию, сортировка по индексу сортировки
                default:
                    $this->db->order_by('order', $orderBy);
            }
        }

        $subdomains = $this->db->get();

        if( !$subdomains )
            return array();

        $res = array();
        foreach($subdomains->result() as $subdomain) {
            $res[] = array('id' => $subdomain->id,
                           'url' => $subdomain->url,
                           'title' => $subdomain->title,
                           'isCrysis' => $subdomain->is_current == 't',
                           'status' => (int)$subdomain->status,
                           'disclaimer' => $subdomain->sub_disclaimer,
                           'order' => (int)$subdomain->order,);
        }

        return $res;
    }
    
    /**
     * Проверка существования субдомена системы.
     *
     * @param string $subdomain Субдомен, который требуется проверить. Если не указан,
     * используется субдомен текущего URL-а.
     * @return boolean Если субдомен не передан и отсутствует в текущем URL-е,
     * возвращается TRUE. Иначе, возвращается TRUE либо FALSE в зависимости от
     * существования субдомена системы.
     */
    public function subdomainExists($subdomain = NULL)
    {
        if( !$subdomain )
            $subdomain = getSubdomain();
        
        return $subdomain ? $this->db->select('COUNT(id) AS count')
                                     ->from('subdomain_view')
                                     ->where('url', $subdomain)->get()->row()->count > 0
                          : TRUE;
    }
    
    /**
     * Получение текста дисклеймера для одного из дочерних сайтов системы.
     *
     * @param $subdomain mixed ID дочернего сайта системы или домен 3-го уровня,
     * соответствующий дочернему сайту.
     * Например, для получения дисклеймера под-сайта пожаров (полное доменное имя
     * fires.basedomain.com) необходимо передать строку "fires".
     * По умолчанию используется url текущего под-сайта.
     * @return mixed HTML-код дисклеймера или FALSE, если дочерний сайт не найден.
     */
    public function getDisclaimer($subdomain = -1)
    {
        $subdomain = (int)$subdomain == -1 ? getSubdomain() : $subdomain;

        if((int)$subdomain > 0 || $subdomain === '') // Передан ID поддомена
            $res = $this->db->select('sub_disclaimer')
                            ->from('subdomain_view')
                            ->where('id', (int)$subdomain)
                            ->get();
        else
            $res = $this->db->select('sub_disclaimer')
                            ->from('subdomain_view')
                            ->where('url', trim($subdomain))
                            ->get();

        return $res && $res->num_rows() ? $res->row()->sub_disclaimer : FALSE;
    }
}