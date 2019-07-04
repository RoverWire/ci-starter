<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * CodeIgniter Auth Class
 *
 * Base class for simple authentication.
 *
 * @package 	CodeIgniter
 * @subpackage 	Auth
 * @category 	Library
 * @author 		Luis Felipe Pérez
 * @version 	0.2.1
 */

class Auth	{

	private $table_name         = 'users';
	private $field_user         = 'user';
	private $field_pass         = 'pass';
	private $field_active       = 'active';
	private $field_mail_token   = 'mail_token';
	private $field_mail_expires = 'mail_expires';
	private $auth_method        = 'default';
	private $auth_name          = 'ci_auth';
	private $salt_length        = 10;
	private $restrict_attempts  = TRUE;
	private $max_attempts       = 3;
	private $blocked_time       = '00:15:00';
	private $days_mail_expires  = 2;
	private $ci;

	public function __construct($config = array())
	{
		$this->ci =& get_instance();
		$this->initialize($config);
	}

	/**
	 * Loads configuration and initialize variables
	 * @param  array  $param optional array configutation
	 * @return void
	 */
	public function initialize($param = array())
	{
		$auth = $this->ci->load->config('auth', TRUE, TRUE);

		if (is_array($auth) && is_array($param)) {
			$param = array_merge($auth, $param);
		}

		foreach ($param as $key => $value) {
			if (isset($this->$key) && !empty($value) && $key != 'ci') {
				$this->$key = $value;
			}
		}

		if ($this->auth_method == 'phpass') {
			$phpass_conf = array('iteration_count_log2' => $this->salt_length);
			$this->ci->load->library('phpass', $phpass_conf);
		}

		if ($this->restrict_attempts == TRUE) {
			$this->ci->load->model('auth_attempt');
			$this->ci->auth_attempt->set_blocked_time($this->blocked_time);
			$this->ci->auth_attempt->set_max_attempts($this->max_attempts);
		}
	}

	/**
	 * Generates a salt for password encryption
	 * @return string salt
	 */
	private function salt()
	{
		return substr(md5(uniqid(rand(), TRUE)), 0, $this->salt_length);
	}

	/**
	 * Hash a string based on a previous hashed one
	 * @param  string $password password to hash
	 * @param  string $stored   stored hashed password
	 * @return mixed            returns hashed string or false
	 */
	private function to_hash($password, $stored)
	{
		if(!empty($password) AND !empty($stored)){
			$salt = substr($stored, 0, $this->salt_length);
			return $salt . substr(sha1($salt . $password), 0, -$this->salt_length);
		} else {
			return FALSE;
		}
	}

	/**
	 * Hash a string to be used as password
	 * @param  string $password string to hash
	 * @return string           hashed string
	 */
	public function hash($password)
	{
		if (empty($password)){
			return '';
		}

		if ($this->auth_method == 'phpass') {
			return $this->ci->phpass->HashPassword($password);
		} else {
			$salt = $this->salt();
			return $salt . substr(sha1($salt . $password), 0, -$this->salt_length);
		}
	}

	/**
	 * Compare given password against stored hashed one
	 * @param  string $password input password
	 * @param  string $stored   stored and hashed password
	 * @return bool             returns true if strings are equal, else return false.
	 */
	public function check($password, $stored)
	{
		if ($this->auth_method == 'phpass') {
			$auth = $this->ci->phpass->CheckPassword($password, $stored);
		} else {
			$hash = $this->to_hash($password, $stored);
			$auth = ($hash === $stored);
		}

		return $auth;
	}

	/**
	 * Validates if the user ip is already blocked or not.
	 * @return boolean blocked status
	 */
	public function is_blocked()
	{
		if ($this->restrict_attempts) {
			return !$this->ci->auth_attempt->validate();
		} else {
			return FALSE;
		}
	}

	/**
	 * Returns the time remaining for a blocked ip
	 * @param  string $ip user ip
	 * @return mixed      time remaining
	 */
	public function time_remaining($ip)
	{
		if ($this->restrict_attempts) {
			return $this->ci->auth_attempt->remaining($ip);
		} else {
			return 0;
		}
	}

	/**
	 * Login procedure
	 * @return boolean TRUE on success or else FALSE
	 */
	public function login($user, $pass)
	{
		$this->ci->load->database();
		$query = $this->ci->db->where($this->field_user, $user)->where($this->field_active, 1)->get($this->table_name, 1);
		$ip = $this->ci->input->ip_address();

		if ($query->num_rows() == 1) {
			$data = $query->row_array();

			if ($this->check($pass, $data[$this->field_pass])) {

				if ($this->restrict_attempts) {
					$this->ci->auth_attempt->delete($ip);
				}

				$this->ci->load->library('session');
				$this->ci->session->set_userdata($this->auth_name, TRUE);

				return TRUE;
			}
		}

		if ($this->restrict_attempts) {
			$this->ci->auth_attempt->attempt($ip);
		}

		return FALSE;
	}

	/**
	 * Verify if the user is logged in
	 * @return boolean TRUE if is logged else FALSE
	 */
	public function is_logged()
	{
		$this->ci->load->library('session');
		return $this->ci->session->has_userdata($this->auth_name);
	}

	/**
	 * Unset the session variable to avoid access
	 * @return void
	 */
	public function logout()
	{
		$this->ci->load->library('session');
		$this->ci->session->unset_userdata($this->auth_name);
	}

	/**
	 * Generates a strong password of N length without ambiguos
	 * characters like 0, o, 1 and l. Also you can choose between four
	 * available characters sets: lowercase, uppercase, digits and symbols
	 * @param  integer $length         password length
	 * @param  boolean $add_dashes     add dashes to password
	 * @param  string  $available_sets available sets of characters
	 * @return string                  password generated
	 */
	function generate_password($length = 8, $add_dashes = FALSE, $available_sets = 'luds')
	{
		$sets     = array();
		$all      = '';
		$password = '';

		if (strpos($available_sets, 'l') !== FALSE) {
			$sets[] = 'abcdefghjkmnpqrstuvwxyz';
		}

		if (strpos($available_sets, 'u') !== FALSE) {
			$sets[] = 'ABCDEFGHJKMNPQRSTUVWXYZ';
		}

		if (strpos($available_sets, 'd') !== FALSE) {
			$sets[] = '23456789';
		}

		if (strpos($available_sets, 's') !== FALSE) {
			$sets[] = '!@#$%&*?';
		}

		foreach($sets as $set) {
			$password .= $set[array_rand(str_split($set))];
			$all      .= $set;
		}

		$all = str_split($all);

		for($i = 0; $i < $length - count($sets); $i++) {
			$password .= $all[array_rand($all)];
		}

		$password = str_shuffle($password);

		if(!$add_dashes) {
			return $password;
		} else {
			$dash_len = floor(sqrt($length));
			$dash_str = '';

			while(strlen($password) > $dash_len) {
				$dash_str .= substr($password, 0, $dash_len) . '-';
				$password  = substr($password, $dash_len);
			}

			$dash_str .= $password;
			return $dash_str;
		}
	}

	/**
	 * Generates a token for mail activation or password recover.
	 * The token is saved on database and set it lifetime.
	 *
	 * @param  string $user username
	 * @return string       generated token
	 */
	public function generate_mail_token($user)
	{
		$active = $this->ci->db->where($this->field_mail_expires . "> ADDTIME(NOW(), '02:00:00')")
							   ->where($this->field_user, $user)
							   ->count_all_results($this->table_name);

		if ($active) {
			$query = $this->ci->db->select($this->field_mail_token.' AS token')
								  ->where($this->field_user, $user)
								  ->get($this->table_name, 1)->row();
			$token = $query->token;
		} else {
			$token    = $this->hash($this->generate_password(8));
			$this->ci->db->set($this->field_mail_token, $token)
			             ->set($this->field_mail_expires, "DATE_ADD(NOW(), INTERVAL {$this->days_mail_expires} DAY)", FALSE)
			             ->where($this->field_user, $user)
			             ->update($this->table_name);
		}

		return $token;
	}

	/**
	 * Validates if token exists and is valid
	 * @param  string $user  username
	 * @param  string $token token
	 * @return bool          is valid
	 */
	public function validate_mail_token($user, $token)
	{
		$this->clean_expired_tokens();

		$user  = $this->ci->security->xss_clean($user);
		$token = $this->ci->security->xss_clean($token);
		$valid = $this->ci->db->where($this->field_mail_expires. '> NOW()')
							  ->where($this->field_user, $user)
							  ->where($this->field_mail_token, $token)
							  ->count_all_results($this->table_name);

		return ($valid == 1) ? TRUE:FALSE;
	}

	/**
	 * Clean expired tokens from the table
	 * @return void
	 */
	public function clean_expired_tokens()
	{
		$this->ci->db->set($this->field_mail_token, NULL)
		             ->set($this->field_mail_expires, NULL)
		             ->where($this->field_mail_expires .' < NOW()')
		             ->update($this->table_name);
	}

}
