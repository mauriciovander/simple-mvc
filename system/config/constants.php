<?

// place your personal config settings in application scope
// SUGGESTION: use /usr/share/php5/global_config.php and set 
// your valid paths in php.ini
include 'global_config.php';

define('BACKEND',php_uname('n'));
define('ARCHITECTURE',php_uname('m'));
define('OS',php_uname('s'));

@define('EMAIL_USER','user@gmail.com');
@define('EMAIL_PASSWORD','**********');
@define('ADDRESS','BITCOIN-ADDRESS');
@define('SECRET','super-secure-secret-key');
@define('BLOCKCHAIN_API_KEY','BLOCKCHAIN-API-KEY');

