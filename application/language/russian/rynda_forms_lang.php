<?php if( !defined('BASEPATH') ) exit('No direct script access allowed');
/**
 * Переменные для локализации системы rynda.org. Раздел: формы и сообщения на них
 *
 * @copyright  Copyright (c) 2011 Звягинцев Л. aka Ahaenor
 * @link       /application/language/russian/ryndaForms_lang.php
 * @version    0.1
 * @since      Файл доступен начиная с версии проекта 0.1
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/
 */

$lang['forms_firstNameRequired'] = 'Пожалуйста, укажите ваше имя';
$lang['forms_firstNameInvalid'] = 'Странное у вас имя %)';
$lang['forms_lastNameRequired'] = 'Пожалуйста, укажите вашу фамилию';
$lang['forms_lastNameInvalid'] = 'Странная у вас фамилия %)';
$lang['forms_phoneInvalid'] = 'Недопустимые символы в телефонном номере';
$lang['forms_emailRequired'] = 'Пожалуйста, укажите ваш e-mail';
$lang['forms_emailInvalid'] = 'Пожалуйста, введите нормальный e-mail';
$lang['forms_someContactsRequired'] = '<b>Пожалуйста, укажите ваш телефон и/или адрес e-mail!</b> Иначе мы не сможем связаться с Вами :(';
$lang['forms_locationRequired'] = 'Пожалуйста, укажите ваше местонахождение на карте';
$lang['forms_categoryRequired'] = 'Пожалуйста, укажите хотя бы одну категорию';
$lang['forms_requestTextRequired'] = 'Пожалуйста, напишите о вашей проблеме! Хоть пару слов';
$lang['forms_volunteerAboutRequired'] = 'Пожалуйста, напишите о том, чем вы можете помогать! Хоть пару слов';
$lang['forms_dataProcessAgreeRequired'] = 'Мы не можем принять ваши данные в обработку, пока вы не нам не разрешите :(';
$lang['forms_searchMessagesError'] = 'Ошибка при поиске сообщений :( Пожалуйста, напишите об этом нам на почту';
$lang['forms_searchOrganizationsError'] = 'Ошибка при поиске организаций :( Пожалуйста, напишите об этом нам на почту';
$lang['forms_searchMessagesNotFound'] = 'Не найдено сообщений по вашему запросу';
$lang['forms_searchOrganizationsNotFound'] = 'Не найдено организаций по вашему запросу';
$lang['forms_addrGeolocateSuccess'] = 'Найден адрес';
$lang['forms_addrGeolocateFailure'] = 'Адрес не найден :( Укажите его на форме вручную, пожалуйста';
$lang['forms_aidingTimeSometimes'] = 'Когда как';
$lang['forms_aidingTimeMonth'] = '1 раз в месяц';
$lang['forms_aidingTimeMonthMore'] = 'Более 1 раза в месяц';
$lang['forms_aidingTimeWeek'] = '1 раз в неделю';
$lang['forms_aidingTimeWeekMore'] = 'Более 1 раза в неделю';
$lang['forms_aidingTimeWorkDays'] = 'По будним дням';
$lang['forms_aidingTimeHolidays'] = 'По выходным';
$lang['forms_aidingTimeEveryday'] = 'Каждый день';
$lang['forms_aidingTimeAllTime'] = 'Круглосуточно!';
$lang['forms_aidingDistLabel'] = 'до #DISTANCE# км.';
$lang['forms_aidingDistMaxLabel'] = 'Более 100 км.';
$lang['forms_aidingDistMinLabel'] = '1 км.';
$lang['forms_aidingDistRequired'] = 'Пожалуйста, укажите расстояние, на котором вы можете помогать';
$lang['forms_socNetUrlInvalid'] = 'Неверная ссылка на профиль #SOC_NET#';
$lang['forms_vpTitleRequired'] = 'Пожалуйста, назовите как-нибудь ваш профиль волонтёрства';
$lang['forms_helpDaysRequired'] = 'Пожалуйста, укажите хотя бы 1 день, когда вы можете помогать';
$lang['forms_addOrgNameRequired'] = 'Название организации обязательно должно быть указано';
$lang['forms_addOrgTypeRequired'] = 'Тип организации обязательно должен быть указан';
$lang['forms_addOrgCategoryRequired'] = 'Должна быть выбрана хотя бы 1 категория организации';
$lang['forms_addOrgRegionRequired'] = 'Регион обязательно должен быть указан';
$lang['forms_addOrgAddressRequired'] = 'Адрес организации обязательно должен быть указан';
$lang['forms_addOrgPhonesInvalid'] = 'Пожалуйста, вводите корректные номера телефонов!';
$lang['forms_addOrgEmailsInvalid'] = 'Пожалуйста, вводите корректные адреса e-mail!';
$lang['forms_addOrgSuccess'] = 'Организация добавлена успешно';
$lang['forms_addOrgError'] = 'Ошибка при добавлении организации';

$lang['forms_addRequestSuccess'] = '<b>Ваша просьба о помощи принята!</b> Мы рассмотрим и подтвердим её в ближайшее время. После подтверждения сообщение попадёт на карту';
$lang['forms_addRequestError'] = '<b>Ошибка:(</b> Мы не смогли сохранить вашу просьбу о помощи. Пожалуйста, попробуйте ещё раз чуть позже';
$lang['forms_uploadImageSuccess'] = 'Фотография успешно загружена';
$lang['forms_uploadImageError'] = 'К сожалению, ваша фотография не может быть сохранена :(<br />
                                   Скорее всего, мы уже знаем об этом и работаем над решением проблемы.<br />
                                   Но всё равно, мы были бы благодарны, если бы вы написали
                                   об этом случае нам, <a href="mailto:#SUPPORT_EMAIL#">
                                   <strong>в техническую поддержку</strong></a>проекта. Спасибо!';
$lang['forms_imageAlreadyUploaded'] = '<b>Ошибка!</b> Вы уже загружали такую фотографию';
$lang['forms_unknownMediaType'] = '<b>Ошибка :(</b> Загрузка изображения неизвестного типа.<br />
                                   Скорее всего, мы уже знаем об этом и работаем над решением проблемы.<br />
                                   Но всё равно, мы были бы благодарны, если бы вы написали
                                   об этом случае нам, <a href="mailto:#SUPPORT_EMAIL#">
                                   <strong>в техническую поддержку</strong></a>проекта. Спасибо!';
$lang['forms_addOfferSuccess'] = '<b>Ваше предложение помощи принято!</b> Мы рассмотрим и подтвердим его в ближайшее время. После подтверждения сообщение попадёт на карту';
$lang['forms_addOfferError'] = '<b>Ошибка :(</b> Мы не смогли сохранить ваше предложение помощи. Пожалуйста, попробуйте ещё раз чуть позже';
$lang['forms_ajaxProcessError'] = 'При сохранении информации произошла ошибка :( Пожалуйста, попробуйте ещё раз позже';
$lang['forms_editUserDataSuccess'] = 'Изменения сохранены!';
$lang['forms_addVpSuccess'] = 'Профиль волонтёрства успешно добавлен!';
$lang['forms_addVpError'] = '<b>Ошибка :(</b> Мы не смогли сохранить ваш профиль волонтёрства. Пожалуйста, попробуйте создать его ещё раз попозже';
$lang['forms_addVpTitleExists'] = '<b>Ошибка :(</b> У вас уже есть профиль волонтёрства с названием «#VP_TITLE#»';
$lang['forms_editVpSuccess'] = 'Изменения сохранены!';
$lang['forms_deleteVpSuccess'] = 'Профиль волонтёрства успешно удалён';
$lang['forms_deleteVpPasswordIncorrectError'] = 'Невозможно удалить профиль волонтёрства: вы указали неверный пароль';
$lang['forms_deleteVpError'] = 'Ошибка при удалении профиля волонтёрства :( Пожалуйста, напишите нам об этом';
//$lang['forms_'] = '';

$lang['forms_CommentTextRequired'] = 'Текст сообщения обязателен'; 
$lang['forms_CommentNameRequired'] = 'Введите своё имя'; 
$lang['forms_CommentEmailRequired'] = 'Укажите email';
$lang['forms_CommentEmailInvalid'] = 'Проверьте правильность написания email адреса';
$lang['forms_CommentPremoderating'] = 'Комментарий отправлен на премодерацию';