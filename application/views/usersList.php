<?php if( !defined('BASEPATH') ) exit('No direct script access allowed');
/**
 * Файл содержит шаблон страницы списка пользователей сайта
 *
 * @copyright  Copyright (c) 2011 Звягинцев Л. aka Ahaenor
 * @link       /application/views/usersList.php
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/
 */
?>
<div class="g960" id="content">
    <div class="breadcrumbs mb10">
        <a title="Главная" href="/">Главная</a> &raquo;
        <span title="<?php echo $listTitle;?>"><?php echo $listTitleShort;?></span>
    </div>
    <div class="inside g340">
        <?php $this->load->view('widgets/usersFilter',
        array('regions' => empty($regions) ? array() : $regions,
              'categories' => empty($categories) ? array() : $categories,
              'filterShowFields' => empty($filterShowFields) ? array() : $filterShowFields,));
        ?>
    </div>
    <div class="column g560 ml50">
        <div class="header_container rounded_top">
            <div class="alignright mc_container">
<!--                <div id="export_csv" class="csv pointer no_text" title="Экспорт списка в CSV">Экспорт списка</div>-->
                Всего: <span id="itemsCount" style="display:none;"></span>

            </div>
            <h3 class="mb0 rounded_top regulartext_header"><?php echo $listTitleShort;?></h3>
        </div>
        <div class="message_list" id="mp1">
            <div class="date_select mb10">
                <a href="#" class="dateAddedFrom" id="<?php echo mktime(0, 0, 0);?>" title="Только пользователи, зарегистрированные сегодня">За сегодня</a> |
                <a href="#" class="dateAddedFrom" id="<?php echo strtotime('-3 day', mktime(0, 0, 0));?>" title="Только пользователи, зарегистрированные за последние 3 дня">За 3 дня</a> |
                <a href="#" class="dateAddedFrom" id="<?php echo strtotime('-1 week', mktime(0, 0, 0));?>" title="Только пользователи, зарегистрированные за последнюю неделю">За неделю</a> |
                <a href="#" class="dateAddedFrom" id="<?php echo strtotime('-1 month', mktime(0, 0, 0));?>" title="Только пользователи, зарегистрированные за последний месяц">За месяц</a> |
                <a href="#" class="dateAddedFrom" id="0" title="Пользователи, зарегистрированные за все время">За все время</a><img id="filterResponseLoading" src="/images/white_sm_loader.gif" alt="" style="display: none;" />
            </div>

            <div id="filterResponseMessage"></div>
            <ul id="itemsList" class="no_list datelist grayDivider"></ul>
            <div class="clearfix"></div>
            <div id="paginationBlock" class="paginaton mb10">

                <div id="paginationPages" data-current-page="1" class="alignleft list_pagination_div"></div>
                <div class="alignright"><span class="darkGrey">Пользователей на странице:</span>
                    <select id="paginationItemsPerPage" name="paginationItemsPerPage">
                        <option value="10" selected="selected">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                        <option value="0">Все</option>
                    </select>
                </div>
                <div class="clearfix "></div>
            </div>
        </div>
    </div>
    <div class="clearfix "></div>
</div>
<script id="listItemTmpl" type="text/x-jquery-tmpl">
    <li>
        <a href="/info/u/${id}" title="#${id}: ${fullName}" class="darkGrey list_number">#${id}</a>
        <div class="list_content">
            {{if avatarUrl}}
            <span><img src="${avatarUrl}" rel="" /></span>
            {{else}}
            <span><img src="/css/i/anonymous_thumb.png" rel="" /></span>
            {{/if}}
            <a href="/info/u/${id}" class="darkRed" title="№${id}: ${fullName}">
                <strong>#${id}: ${fullName}</strong>
            </a>
            <br />
            <ul>
            {{each(vpIndex, vp) userVp}}
                <li>
                    <b>${vp.title}</b>
                    <br />
                    <span class="darkGrey fs10">
                        <a href="/info/r/${vp.regionId}" title="Все сообщения по региону «${vp.regionName}»">${vp.regionName}</a> | {{if isAllCategories == 1}}
                        любые категории помощи
                    {{else}}
                        {{each(i, cat) vp.categories}}
                        <a href="/info/c/${cat.id}" title="Все сообщения в категории «${cat.name}»" >${cat.name}</a>{{if i < vp.categories.length - 1}}, {{/if}}
                        {{/each}}
                    {{/if}}
                    </span>
                </li>
            {{/each}}
            </ul>
            <br />
            <div class="clearfix mt4"></div>
            <a href="/info/u/${id}" class="no_underline" title="Страница пользователя">Подробнее</a>
            <br />
            <br />
            <span class="darkGrey fs10">
                Зарегистрировался: ${$item.formatDate(dateAdded)} | Последний раз был на сайте: ${$item.formatDate(dateLastLogin)}
            </span>
        </div>
        <div class="clearfix"></div>
    </li>
</script>
<script id="paginationPagesTmpl" type="text/x-jquery-tmpl">
    {{if showLinkFirst}}
    <a href="#" class="paginationLink" data-page-num="1" data-begin-at="0"><< К началу</a> |
    {{/if}}
    {{if showLinkPrev}}
    <a href="#" class="paginationLink" data-page-num="${currentPageNum-1}" data-begin-at="${itemsPerPage*(currentPageNum-1)}">&laquo; Назад</a> |
    {{/if}}

    {{if showLinkFirst}} ... {{/if}}
    <span class="darkGrey">Страницы: </span>
    {{each pages}}
    {{if $value.pageNum == currentPageNum}}
    <b>${$value.pageNum}</b>
    {{else}}
    <a href="#" class="paginationLink" data-page-num="${$value.pageNum}" data-begin-at="${$value.beginAt}">${$value.pageNum}</a>
    {{/if}}
    {{/each}}
    {{if showLinkLast}} ... {{/if}}

    {{if showLinkNext}}
    | <a href="#" class="paginationLink" data-page-num="${currentPageNum+1}" data-begin-at="${itemsPerPage*currentPageNum}">Вперёд &raquo;</a>
    {{/if}}
    {{if showLinkLast}}
    | <a href="#" class="paginationLink" data-page-num="${totalPages}" data-begin-at="${itemsPerPage*(totalPages-1)}">К концу &raquo;</a>
    {{/if}}
</script>

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5/jquery.min.js" type="text/javascript"></script>
<script src="/javascript/lib/jQueryTemplates.js" type="text/javascript"></script>

<script src="/javascript/usersList.js?v=<?php echo sha1($this->config->item('js_version'));?>" type="text/javascript"></script>