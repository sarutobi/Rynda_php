<?php if( !defined('BASEPATH') ) exit('No direct script access allowed');
/**
 * Файл содержит шаблон страницы списка актуальных рекомендаций.
 *
 * @copyright  Copyright (c) 2011 Valery A. Ilychev aka Sarutobi
 * @link       /application/views/recommendationsList.php
 * @version    0.1
 * @since      Файл доступен начиная с версии проекта 0.1
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/
 */
?>
<div class="g960" id="content">
	<div class="breadcrumbs mb10">
        <a title="Главная" href="/">Главная</a> &raquo;
        <a href="/rec" title="Рекомендации">Рекомендации</a>
    </div>
    <div class="inside g340">
	<?php
//        $this->load->view('widgets/messagesFilter',
//                          array('regions' => empty($regions) ? array() : $regions,
//                                'categories' => empty($categories) ? array() : $categories,
//                                'filterShowFields' => empty($filterShowFields) ? array() : $filterShowFields,
//                                'filterPersistVars' => empty($filterPersistVars) ? array() : $filterPersistVars,));
    ?>
	</div>
	<div class="column g560 ml50">
    	<h2 class="h24 allcaps mb10">
            Список рекомендаций
            <span id="messagesCount" style="display:none;"></span>
            <img id="filterResponseLoading" src="/images/blue_sm_loader.gif" alt="" style="display: none;" />
        </h2>
        
    </div>
</div>