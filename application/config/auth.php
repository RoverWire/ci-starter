<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * CodeIgniter Auth Class
 *
 * Configuration for Auth library
 *
 * @package 	CodeIgniter
 * @subpackage 	Auth
 * @category 	Library
 * @author 		Luis Felipe Pérez
 * @version 	0.2.1
 */

/**
 * Salt length for password encryption
 */
$config['salt_length'] = 10;

/**
 * Max number for login attempts
 */
$config['max_attempts'] = 3;

/**
 * Blocked time after user reach max attempts number.
 */
$config['blocked_time'] = '00:30:00';
