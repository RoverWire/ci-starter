<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * CodeIgniter Auth Class
 *
 * Model to manage authentication attempts
 *
 * @package 	CodeIgniter
 * @subpackage 	Auth
 * @category 	Library
 * @author 		Luis Felipe Pérez
 * @version 	0.2.1
 */
class Auth_Attempt extends MY_Model {

	protected $_table     = 'auth_attempts';
	protected $_id        = 'ip';
	private $max_attempts = 3;
	private $blocked_time = '00:15:00';

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Set max number of attempts
	 * @param int $int number of allowed attempts
	 */
	public function set_max_attempts($int)
	{
		$this->max_attempts = $int;
	}

	/**
	 * Set blocked time when max number of attempts is reached
	 * @param time $time hh:mm:ss time format
	 */
	public function set_blocked_time($time)
	{
		if (preg_match('/^(?:(?:([01]?\d|2[0-3]):)?([0-5]?\d):)?([0-5]?\d)$/', $time)) {
			$this->blocked_time = $time;
		}
	}

	/**
	 * Counts and register each attempt based on user ip address
	 * @param  string $ip user ip address
	 * @return int        attempt counter
	 */
	public function attempt($ip = '')
	{
		if (empty($ip)) {
			$ip = $this->input->ip_address();
		}

		$data = array();
		$data['blocked'] = date("Y-m-d H:i:s");
		$query = $this->db->where('ip', $ip)->limit(1)->get($this->_table);

		if ($query->num_rows() == 1) {
			$row = $query->row();
			$data['attempts'] = $row->attempts + 1;
			$this->update($data, $ip);

			return $data['attempts'];
		} else {
			$data['attempts'] = 1;
			$data['ip'] = $ip;
			$this->insert($data);

			return 1;
		}
	}

	/**
	 * Cleans old and obsolete attempts from table
	 * @return void
	 */
	public function clean()
	{
		$this->db->where("TIMEDIFF(CURRENT_TIMESTAMP, blocked) > '{$this->blocked_time}'", NULL, FALSE);
		$this->db->delete($this->_table);
	}

	/**
	 * Validates if an ip address is blocked
	 * @param  string $ip user ip address
	 * @return bool       TRUE if not blocked, else FALSE
	 */
	public function validate($ip = '')
	{
		if (empty($ip)) {
			$ip = $this->input->ip_address();
		}

		$this->clean();
		$blocked = $this->db->where('ip', $ip)->where('attempts>=', $this->max_attempts)->count_all_results($this->_table);

		if ($blocked == 0) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	/**
	 * Get time remaining for a blocked ip
	 * @param  string $ip user ip
	 * @return mixed      time remaining
	 */
	public function remaining($ip = '')
	{
		if (empty($ip)) {
			$ip = $this->input->ip_address();
		}

		$query = $this->db->select("TIMEDIFF('{$this->blocked_time}', TIMEDIFF(CURRENT_TIMESTAMP, blocked)) AS remaining", FALSE)->where('ip', $ip)->get($this->_table, 1);

		if ($query->num_rows() == 1) {
			$row = $query->row();
			return $row->remaining;
		} else {
			return 0;
		}
	}

}

/* End of file Ip_Address.php */
/* Location: ./application/models/Ip_Address.php */
