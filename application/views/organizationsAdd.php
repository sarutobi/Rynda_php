<?php if( !defined('BASEPATH') ) exit('No direct script access allowed');
/**
 * Файл содержит шаблон формы ввода информации об организации 
 *
 * @copyright  Copyright (c) 2011 Valery A. Ilychev aka Sarutobi 
 * @link       /application/views/organizationsAdd.php
 * @version    0.1
 * @since      Файл доступен начиная с версии проекта 0.1
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/
 */
?>
<div class="g960 orgaddpage rounded_all mb35" id="content">
    <div id="formTotal">
    	<div class="header_container rounded_top">
            <h3 class="regulartext_header">Добавить организацию</h3>
        </div>	
        
        <form id="mainForm" action="#">
        	<div>
            	<!-- Левая панель -->
            	<div class="column" id="ch1column">
                	<div class="column_container_offer width_fixer" id="ch_1_column_container">
            		<select name="organizationType" id="organizationType">
                <option value="">Выберите тип организации</option>
                <?php foreach($organizationTypes as $type) {?>
                    <option value="<?php echo $type['id'];?>"><?php echo $type['name'];?></option>
                <?php }?>
            </select>
            <div id="organizationTypeError" class="validationError" style="display:none;"></div>
            <br />
            
            <input type="text" name="name" id="name" placeholder="Наименование организации" tabindex="1" size="100">
            <div id="nameError" class="validationError" style="display:none;"></div>
            <br />
            <br />
            <textarea name="description" id="description" placeholder="Описание организации" rows="10" cols="100"></textarea>
            <br />
            <br />
            <select tabindex="0" name="organizationRegion" id="organizationRegion">
                <option value="">Выберите регион</option>
                <?php foreach($regions as $region) {?>
                <option value="<?php echo $region['id'];?>"><?php echo $region['name'];?></option>
                <?php }?>
            </select>
            <div id="organizationRegionError" class="validationError" style="display:none;"></div>
            <br />
            <input type="text" name="address" id="address" placeholder="Адрес организации (вкл. город)" size="100" />
            <div id="addressError" class="validationError" style="display:none;"></div>
            <br />
            <br />
            <input type="text" name="phones" id="phones" placeholder="Телефоны в виде: 8-495-123-45-67,4757634,..." size="100" />
            <div id="phonesError" class="validationError" style="display:none;"></div>
            <br />
            <br />
            <input type="text" name="contacts" id="contacts" placeholder="Ф.И.О. контактных лиц организации" size="100" />
            <br />
            <br />
            <input type="text" name="emails" id="emails" placeholder="Адреса e-mail в виде: ahaenor@gmail.com,hello@world.com,..." size="100" />
            <div id="emailsError" class="validationError" style="display:none;"></div>
            <br />
            <br />
            <input type="text" name="sites" id="sites" placeholder="Адреса сайтов в виде: somesite.ru,onemoresite.com,..." size="100" />
            <br />
            <br />
            
            		</div>
        		</div>
        		<!-- Правая верхняя панель -->
                <div class="column" id="ch2column">
                	<div class="column_container_offer" id="ch_2_column_container">
                    	<h3>Выберите категорию:</h3>
                        <?php $this->load->view('widgets/categorySelect',
                                                array('categories' => $categories,
                                                      'expandable' => FALSE,
                                                      ));?>
                        <div id="categoryError" class="validationError" style="display:none;"></div>
                        <div class="clearfix"></div>
                        <input type="submit" id="addSubmit" value="" />
                        <div class="clearfix"></div>
                        <div id="formResponseMessage"></div>
                        <img id="formResponseLoading" src="/images/loading.gif" style="display:none;" alt="" />
                    </div>
                </div>
        		<!-- Правая верхняя панель завершена -->
        	</div>
        </form>
    </div> 
</div>

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5/jquery.min.js" type="text/javascript"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.11/jquery-ui.min.js" type="text/javascript"></script>
<script src="http://maps.google.com/maps/api/js?sensor=false" type="text/javascript"></script>
<script src="/javascript/lib/jQueryTemplates.js?v=<?php echo sha1($this->config->item('js_version'));?>" type="text/javascript"></script>

<script src="/javascript/organizationsAdd.js?v=<?php echo sha1($this->config->item('js_version'));?>" type="text/javascript"></script>