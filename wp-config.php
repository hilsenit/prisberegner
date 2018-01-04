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
define('DB_NAME', 'a1kommunikation');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'root');

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
define('AUTH_KEY',         'j*kJ#iEx$3,#}_>M4I0$:yYqT{YG]E5t?P`Ais2J}Sqp%nNU{tF._~J8:dS>4H@/');
define('SECURE_AUTH_KEY',  'qFFB#d3+J0O~@-jc]gW%6ZI ;pAWKi(.$lY{a^#<K5X2 .xV8#f]g*#kVSc2C#9l');
define('LOGGED_IN_KEY',    'dUU,d__sLJ_&fi%Xyme[Rdgf V~:}l.T%O)g%J]g_<H)YnU1GieYuq+2):j~NSeY');
define('NONCE_KEY',        'A`jNcU7~@=U>UNB_;/$5SOV?;UiE7hF(X5~F>(F<|@ewl~iAl|_-G7+;@b4%;r6i');
define('AUTH_SALT',        'tu<a<kf][L&T#mZic2Qav^)Nw27 p5$u%D@?7-~MYZXqA7OdDbJhp?#9Oh!0q=3<');
define('SECURE_AUTH_SALT', 'n#A<jd]dkjbA/L@f,JY D@?,/6PcsQ]xe-R<M#CE?QKkxZaP`ZQ97ymjnr2&,B!,');
define('LOGGED_IN_SALT',   'qIqZ4nZ^ ioZ{50lgrTe}CuYKm[YdU_Yf=D8,5jOPMb_!X([CH)5VJesVIGIs![<');
define('NONCE_SALT',       'T(H?Mk]1wBfG!np]oyU04~7 fK>;T `K}#+,T&-Ydk-/&B!;%LUxQK=NbUtO|?ln');

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
