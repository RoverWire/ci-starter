<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->library('auth');
		$this->load->model('auth_attempt');
	}

	public function index()
	{
		$this->template->render();
	}

	public function login()
	{
		$this->template->set_master_template('layouts/admin_blank');
		$this->template->write_view('content', 'login');
		$this->template->render();
	}

	public function logout()
	{
		redirect('admin/login');
	}

}

/* End of file Admin.php */
/* Location: ./application/controllers/Admin.php */
