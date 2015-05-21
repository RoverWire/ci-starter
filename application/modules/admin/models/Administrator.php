<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Administrator extends MY_Model {

	protected $_id         = 'id';
	protected $_table      = 'administrators';
	protected $field_names = array('name', 'user', 'pass', 'mail', 'status', 'created', 'updated', 'access');
	protected $pre_insert  = array('hash_password', 'datetime_created');
	protected $pre_update  = array('hash_password', 'datetime_updated');
	protected $grid_fields = 'id, name, user, mail, status';

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

	private function update_access($id)
	{
		$this->db->set('access', date('Y-m-d H:i:s'))->where($this->_id, $id)->update($this->_table);
	}

	private function datetime_created($data)
	{
		$data['created'] = date('Y-m-d H:i:s');
		return $data;
	}

	private function datetime_updated($data)
	{
		$data['updated'] = date('Y-m-d H:i:s');
		return $data;
	}

	private function hash_password($data)
	{
		if (isset($data['pass']) && !empty($data['pass'])) {
			$data['pass'] = $this->admin_auth->hash_password($data['pass']);
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
		return $this->admin_auth->login($user, $pass);
	}

}

/* End of file Administrator.php */
/* Location: ./application/models/Administrator.php */
