<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'musichub');

/** MySQL database username */
define('DB_USER', 'kazzy');

/** MySQL database password */
define('DB_PASSWORD', 'handsom@@');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'WF-`H|~xT>Z_b1@[z,.|}?Pk-hh=*a&<9kALN+3{H@YFRI]uhS$]C+8 QTbV4[dD');
define('SECURE_AUTH_KEY',  'tEefWQv.lm!B`O1n-ExdE:FI_%nAr03,JI?`PWPbFX=?N}B(1B=mf;S$Zsk?j}5:');
define('LOGGED_IN_KEY',    '-^W&}.Wx($7C|r#QC0RYH3r9}_6!ipKkgFCcCu?#FF.@}&aN HaE8D_4i>5TN^y,');
define('NONCE_KEY',        'ZMImVsnheViI|`s]/Or48uEn3Q[WpRS<!Z$)zz]0uI=^Qa/Cna9t7|1AdBzOTl+4');
define('AUTH_SALT',        'nB4p:k%!aw>Bx?OkJ?B3LQAB,Dh.y45teowQ@Fx]{9}5.uBstE73xAO*8XO{2ipy');
define('SECURE_AUTH_SALT', '>wo@xG/:s7Ln9#vzoZa~8vnTX=|@;gp1I[UWD6gU-IS~C-!~5l5AK12Y7E<%pBUo');
define('LOGGED_IN_SALT',   '%Mp#z;qfr~?|&D.LU*x@K$HXZ)=V|}4;u(hFMLiza%:]VSw%k=E&!WS~J0wSH5Kp');
define('NONCE_SALT',       '3O,}7%(#fQJb8qE^Eddg1BtLS33s+@l}]!5c92<c;@$fOqY`!Pm!C0Oj*w KZH/.');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
