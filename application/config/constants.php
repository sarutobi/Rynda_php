<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

define('FOPEN_READ',                          'rb');
define('FOPEN_READ_WRITE',                    'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE',      'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE',                  'ab');
define('FOPEN_READ_WRITE_CREATE',             'a+b');
define('FOPEN_WRITE_CREATE_STRICT',           'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',      'x+b');

/**
 * ***************************
 * Константы проекта Rynda.org
 * ***************************
 */

/**
 * Абсолютный путь к корневой папке системы. Включает завершающий слэш.
 */
define('ABSOLUTE_PATH', str_replace('\\', '/', realpath('.')), '/\\');

/**
 * Признаки/атрибуты сообщения (значение по умолчанию 1):
 */
/** * 1 бит: Активность сообщения */
define('MESSAGE_IS_ACTIVE', 0x1);
/** * 2 бит: Срочность сообщения: не срочно (1) или срочно (0) */
define('MESSAGE_IS_UNTIMED', 0x1 << 1);
/** * 3 бит: Анонимность автора сообщения: публиковать его данные (1) или скрыть их (0) */
define('MESSAGE_IS_PUBLIC_SENDER', 0x1 << 2);
/** * 4 бит: Желает ли отправитель сообщения получать уведомления об ответах: 1 - требует, 0 - нет */
define('MESSAGE_IS_NOTIFY_SENDER', 0x1 << 3);

/**
 * Тип нового сообщения по умолчанию. Должна иметь значение короткого названия одной из
 * строк в таблице message_type. Значение 'request' - тип "просьба помощи".
 */
define('NEW_MESSAGE_DEFAULT_TYPE', 'request');

/**
 * Возможные статусы (состояния) сообщения:
 */
/**
 * Сообщение отправлено и получено
 */
define('MESSAGE_STATUS_RECEIVED', 1);
/**
 * Сообщение прошло модерацию
 */
define('MESSAGE_STATUS_MODERATED', 2);
/**
 * Сообщение верифицировано (для просьбы помощи - подтверждена истинность,
 * для предложения помощи - завершены переговоры об участии)
 */
define('MESSAGE_STATUS_VERIFIED', 3);
/**
 * Идёт реакция на сообщение (для просьбы помощи - помощь в пути и т.д.,
 * для предложения помощи - выехал на помощь и т.д.)
 */
define('MESSAGE_STATUS_REACTION', 4);
/**
 * Реакция завершена (для просьбы помощи - помощь была доставлена и т.д.,
 * для предложения помощи - участие в помощи завершено)
 */
define('MESSAGE_STATUS_REACTED', 5);
/**
 * Сообщение закрыто (автор сообщения подтвердил, что помощь им получена/доставлена)
 */
define('MESSAGE_STATUS_CLOSED', 6);

/**
 * Признаки/атрибуты профилей волонтёрства (значение по умолчанию 1):
 */
/** * 1 бит: Активность профиля */
define('PROFILE_IS_ACTIVE', 0x1);
/** * 2 бит: Охватывает ли профиль все категории */
define('PROFILE_IS_ALL_CATEGORIES', 0x1 << 1);

/**
 * Возможные статусы (состояния) комментариев на сообщения:
 */
/**
 * Комментарий отправлен и получен
 */
define('COMMENT_STATUS_RECEIVED', 0);
/**
 * Комментарий прошёл модерацию
 */
define('COMMENT_STATUS_MODERATED', 1);
/**
 * Комментарий был удалён
 */
define('COMMENT_STATUS_DELETED', 2);
/**
 * Комментарий - отвергнуто/удалено модером
 */
define('COMMENT_STATUS_DELETED_BY_MODER', 3);
/**
 * Комментарий - спам
 */
define('COMMENT_STATUS_SPAM', 4);


/**
 * Тип медиа - фотография
 */
define('MEDIA_IS_TYPE_MESSAGE_PHOTO', 1);
/**
 * Тип медиа - аватар пользователя
 */
define('MEDIA_IS_TYPE_AVATAR', 2);

/**
 * Тип организации - некоммерческая (НКО)
 */
define('ORGANIZATION_IS_TYPE_NC', 1);
/**
 * Тип организации - коммерческая (бизнес)
 */
define('ORGANIZATION_IS_TYPE_BUSINESS', 2);
/**
 * Тип организации - государственная
 */
define('ORGANIZATION_IS_TYPE_GOV', 3);

/**
 * Признаки/атрибуты профиля пользователя (значение по умолчанию 0):
 */
/** * 1 бит: Скрывать контакты пользователя на сайте (1) или публиковать их (0) */
define('USER_DATA_IS_PRIVATE', 0x1);
/** * 2 бит: Желает ли пользователь не получать уведомления об ответах на свои сообщения: 1 - не желает, 0 - желает */
define('USER_RESPONSES_NOTIFY_OFF', 0x1 << 1);

/**
 * *************************************
 * Константы проекта Rynda.org завершены
 * *************************************
 */


/* End of file constants.php */
/* Location: ./application/config/constants.php */
