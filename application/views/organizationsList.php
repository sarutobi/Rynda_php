<?php if( !defined('BASEPATH') ) exit('No direct script access allowed');
/**
 * Файл содержит шаблон страницы списка организаций.
 *
 * @copyright  Copyright (c) 2011 Valery A. Ilychev aka Sarutobi
 * @link       /application/views/organizationsList.php
 * @version    0.1
 * @since      Файл доступен начиная с версии проекта 0.1
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/
 */
?>
<div class="g960" id="content">
	<div class="breadcrumbs mb10">
        <a title="Главная" href="/">Главная</a> &raquo;
        <a href="/org" title="Организации">Организации</a>
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
            Список организаций <span>(<?php echo (int)$this->pagination->total_rows;?>)</span>
            <span id="messagesCount" style="display:none;"></span>
            <img id="filterResponseLoading" src="/images/blue_sm_loader.gif" alt="" style="display: none;" />
        </h2>
        
        <?php echo $this->pagination->create_links();?>
		<div class="" id="mp1">
            <ul class="no_list datelist grayDivider aid_list">
            <?php foreach($organizations as $org) {?>
                <li><img src="/images/ngo.png" alt="Инкогнито" />
                    <a href="/org/d/<?php echo $org['id'];?>" title="На страницу подробной информации об организации">
                        <?php echo $org['name'];?>
                    </a>
                    <span class="grey">(<?php echo $org['region_name'];?>)</span>
                    <br />
                <?php echo $org['description'];?>
                <div class="clearfix"></div>
                </li>
            <?php }?>  
            </ul>
    	</div>
    </div>
</div>

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5/jquery.min.js" type="text/javascript"></script>

<script src="/javascript/organizationsList.js" type="text/javascript"></script>