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
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
if(strstr($_SERVER['SERVER_NAME'], 'localhost')){
	define( 'DB_NAME', 'inspiry' );
	define( 'DB_USER', 'root' );
	define( 'DB_PASSWORD', 'root' );
	define( 'DB_HOST', 'localhost' );
}
else{ 
	define( 'DB_NAME', 'taurang8_inspiry' );
	define( 'DB_USER', 'taurang8_inspiry' );
	define( 'DB_PASSWORD', 'Observer13' );
	define( 'DB_HOST', '127.0.0.1' );
}


/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         '%Q%^5R+{TI<;/.4(j|ssWcED|,giH51[uT~$:isQBHz~QD2Ey juBIgR)^MUoI1O' );
define( 'SECURE_AUTH_KEY',  'SQEWxww?zFlGe|@^pOBO3m%EEeiu6dGEgKArJ<{.3[wg0O-f2/Ansz%_@YZo,0ms' );
define( 'LOGGED_IN_KEY',    'k]ulwEK4{Ct0B|OS$fY.[PKIxv*,,RTHV~/#+YU&e1}r3miyj?41=^A7!reDbu|%' );
define( 'NONCE_KEY',        '#nT`*dy.Cb(p@EU4x{|dTDs?.qC1C#x8j7zd}kxgE^Q}<WRvk:,lBV3^@{azS;FX' );
define( 'AUTH_SALT',        '~nUG4YRBsM*?fQL[:.)~;agYLvpxB_AqX =[!:Ma2j2TOGbof_?0TZ#zmEo#,gkk' );
define( 'SECURE_AUTH_SALT', '|s,Fy5`,$|H( Ya7hA0LqUcncbEsa/*7/voeZV,)rS&*cSD|s$nN^3`)l_?]N;,~' );
define( 'LOGGED_IN_SALT',   'p{fA9R1q-EW4LXS51K6:]H(JHv/oG>4{v8F/*+2*`lxTc.s>g~Yn<rcY<(G;^z[J' );
define( 'NONCE_SALT',       'g;||mP%Twq[f;|b/4WoD-5n-=P*:)T$A@OgWdBbj)jN|DR`G}ry@27m}4tIP}0EF' );

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
