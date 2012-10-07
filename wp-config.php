<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */



/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         ' !(Cr+d|ij=Wj0EwAGl5=S}v;WYF>4w}-,f~umojLHUdvq<~tK[V6x]do1Mmb2:$');
define('SECURE_AUTH_KEY',  'q: +vzGo$jd^s?UbJt5DXI4K2Ko]xS&b,[1}+-R<w;}WQ81;9yAzh#TC|c4qnoY1');
define('LOGGED_IN_KEY',    'L~.y:|)FQ/l P@in&xoRFVlfts-26`+j}Z(0.yQ,+9cG.Z9p:sd|u}-;/TWRE`h0');
define('NONCE_KEY',        '%U?p#%7iK3v8Kl1st>!Vj-MtQ$hwN+|cQORa#xbB56l-+{sp)G 4Xk`0uL=-`qo-');
define('AUTH_SALT',        'fv{`Q-R3L8/6Wz7RDY||5f4RIE `aOGn$Cy7[|;]p2L}9Cdn<>GutK^HfQ?7%o@Y');
define('SECURE_AUTH_SALT', '@@CN:Z@J/nPNm.WEE!K>|B#s|W5/_c*AOov)<Q1|%970q+e4bmo^VJC?{ZRB.Mxz');
define('LOGGED_IN_SALT',   'F7NX~a#,K?t4FpD,^lLQu6r-H!l#R/}^Hbq.ZCCcG2GDg,mTX*^Y6~tg_kAt*e>j');
define('NONCE_SALT',       '~?f|w$H3Y{  3gp28*t:J9T$y-P+~};VO5#Csyl #vVuRCdlGQ9l,)i$nm8CZapi');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', '');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

$config = ABSPATH .str_replace(".","_",$_SERVER['SERVER_NAME']).".php";
if (file_exists($config)) {
  /** Sets up WordPress vars and included files. */
  require_once($config);
}

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');


