<?php

/**
 * PROJECT URLs
 */
define("CONF_URL_BASE", getenv("APP_URL_BASE"));
define("CONF_URL_ADMIN", getenv("APP_URL_ADMIN"));



/**
 * DATES
 */
define("CONF_DATE_BR", "d/m/Y H:i:s");
define("CONF_DATE_APP", "Y-m-d H:i:s");

/**
 * PASSWORD
 */
define("CONF_PASSWD_MIN_LEN", 8);
define("CONF_PASSWD_MAX_LEN", 40);
define("CONF_PASSWD_ALGO", PASSWORD_DEFAULT);
define("CONF_PASSWD_OPTION", ["cost" => 10]);

/**
 * VIEW
 */
define("CONF_VIEW_PATH", __DIR__ . "/../../shared/views");
define("CONF_VIEW_EXT", "php");
define("CONF_VIEW_THEME", "cafeweb");
define("CONF_VIEW_APP", "cafeapp");

/**
 * UPLOAD
 */
define("CONF_UPLOAD_DIR", "storage");
define("CONF_UPLOAD_IMAGE_DIR", "images");
define("CONF_UPLOAD_FILE_DIR", "files");
define("CONF_UPLOAD_MEDIA_DIR", "medias");

/**
 * IMAGES
 */
define("CONF_IMAGE_CACHE", CONF_UPLOAD_DIR . "/" . CONF_UPLOAD_IMAGE_DIR . "/cache");
define("CONF_IMAGE_SIZE", 2000);
define("CONF_IMAGE_QUALITY", ["jpg" => 75, "png" => 5]);

/**
 * MAIL
 */

define('CONF_MAIL_HOST',  getenv("CONF_MAIL_HOST"));
define('CONF_MAIL_PORT',  getenv("CONF_MAIL_PORT"));
define('CONF_MAIL_USER',  getenv("CONF_MAIL_USER"));
define('CONF_MAIL_PASS',  getenv("CONF_MAIL_PASS"));
define('CONF_MAIL_SENDER', ['name' =>  getenv("CONF_MAIL_SENDER_NAME"), 'address' =>  getenv("CONF_MAIL_SENDER_ADDRESS")]);
define('CONF_MAIL_SUPPORT',  getenv("CONF_MAIL_SUPPORT"));
define('CONF_MAIL_OPTION_LANG',  getenv("CONF_MAIL_OPTION_LANG"));
define('CONF_MAIL_OPTION_HTML',  getenv("CONF_MAIL_OPTION_HTML"));
define('CONF_MAIL_OPTION_AUTH',  getenv("CONF_MAIL_OPTION_AUTH"));
define('CONF_MAIL_OPTION_SECURE',  getenv("CONF_MAIL_OPTION_SECURE"));
define('CONF_MAIL_OPTION_CHARSET',  getenv("CONF_MAIL_OPTION_CHARSET"));