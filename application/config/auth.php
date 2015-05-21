<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * CodeIgniter Auth
 *
 * Configuration for Auth library
 *
 * @package 	CodeIgniter
 * @subpackage 	Auth
 * @category 	Library
 * @author 		Luis Felipe Pérez
 * @version 	0.2.1
 */

/*
|--------------------------------------------------------------------------
| Authentication Settings
|--------------------------------------------------------------------------
|
| ['auth_method'] selects the method of hash for the password. You can
| choose between 'default' that uses a my own simple but secure method
| or if you prefer, you can use 'phpass' library for the most secure hash.
|
| ['auth_name'] is the session variable name to validate when a user is
| logged in or not.
|
| ['salt_length'] refers to the hash lenght if you select defaul method
| or the logaritmic count iteration if you use Phpass. This value can be
| an integer between 8 and 31. By Default is 10.
|
*/
$config['auth_method']        = 'default';
$config['auth_name']          = 'ci_auth';
$config['salt_length']        = 10;
$config['table_name']         = '';
$config['field_user']         = 'user';
$config['field_pass']         = 'pass';
$config['field_active']       = 'active';
$config['field_mail_token']   = 'mail_token';
$config['field_mail_expires'] = 'mail_expires';
$config['days_mail_expires']  = 2;


/*
|--------------------------------------------------------------------------
| Login Attempts Settings
|--------------------------------------------------------------------------
|
| The package can manage login attempts to avoid brute force.
| To activate it, set ['login_attempts'] variable to TRUE and set
| the number of attempts allowed and the time to block the user ip
| on ['blocked_time'] when ['max_attempts'] is reached.
|
| Note that ['blocked_time'] should be on 'hh:mm:ss' format or will be
| ignored and use default value of 30 minutes.
|
*/
$config['restrict_attempts'] = TRUE;
$config['max_attempts']      = 3;
$config['blocked_time']      = '00:30:00';
