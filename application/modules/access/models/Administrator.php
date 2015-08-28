<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Administrator extends MY_Model {

	protected $_id         = 'id';
	protected $_table      = 'administrators';
	protected $field_names = array('name', 'user', 'pass', 'mail', 'active', 'created');
	protected $pre_insert  = array('hash_password', 'datetime_created');
	protected $pre_update  = array('hash_password', 'datetime_updated');
	protected $grid_fields = 'id, name, user, mail, active';

	public function __construct()
	{
		parent::__construct();

		/**
		 * We can initialize an instance
		 * of the Auth library with it own
		 * settings for this model.
		 */
		$config = array(
			'table_name' => $this->_table
		);

		$this->load->library('auth', $config, 'admin_auth');
	}

	protected function update_access($id)
	{
		$this->db->set('access', date('Y-m-d H:i:s'))->where($this->_id, $id)->update($this->_table);
	}

	protected function datetime_created($data)
	{
		$data['created'] = date('Y-m-d H:i:s');
		return $data;
	}

	protected function datetime_updated($data)
	{
		$data['updated'] = date('Y-m-d H:i:s');
		return $data;
	}

	protected function hash_password($data)
	{
		if (isset($data['pass']) && !empty($data['pass'])) {
			$data['pass'] = $this->admin_auth->hash($data['pass']);
		} else {
			unset($data['pass']);
		}

		return $data;
	}

	protected function search_procedure($search = '')
	{
		$this->db->like('name', $search, 'both');
		$this->db->or_like('mail', $search, 'both');
		$this->db->or_like('user', $search, 'both');
	}

	public function login($user, $pass)
	{
		if ($this->admin_auth->login($user, $pass)) {
			$this->build_session('user', $user);
			return TRUE;
		} else {
			return FALSE;
		}
	}

	public function build_session($field, $key)
	{
		$data = $this->db->where($field, $key)->get($this->_table, 1)->row();
		$this->load->helper('gravatar');
		$this->session->set_userdata('session_gravatar', get_gravatar($data->mail, 128));
		$this->session->set_userdata('session_username', $data->user);
		$this->session->set_userdata('session_mail', $data->mail);
		$this->session->set_userdata('session_name', $data->name);
		$this->session->set_userdata('session_id', $data->id);
	}

	public function send_password($mail)
	{
		$query = $this->db->select('id, name, user, mail')->where('mail', $mail)->get($this->_table, 1);

		if ($query->num_rows() > 0) {
			$data          = $query->row_array();
			$token         = $this->admin_auth->generate_mail_token($data['user']);
			$data['token'] = $token;
			$data['site']  = $this->config->item('base_url');
			$message       = $this->load->view('mail_password', $data, TRUE);

			$this->load->library('email');
			$this->email->to($data['mail']);
			$this->email->subject('Password Reset');
			$this->email->message($message);
			$this->email->from('system@example.com', 'Auth System');
			$this->email->send();

			return TRUE;
		} else {
			return FALSE;
		}
	}

	public function change_password($user, $pass)
	{
		if (!empty($user) && !empty($pass)) {
			$data   = array('pass' => $pass);
			$update = $this->where('user', $user)->update($data);

			if ($update) {
				$this->db->set('mail_token', NULL)->set('mail_expires', NULL)->where('user', $user)->update($this->_table);
			}

			return $update;
		} else {
			return FALSE;
		}
	}

}

/* End of file Administrator.php */
/* Location: ./application/models/Administrator.php */
