<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$this->template->render();
	}

	public function login()
	{
		if ($this->admin_auth->is_blocked()) {
			redirect('admin/blocked');
		}

		if ($this->admin_auth->is_logged()) {
			redirect('admin');
		}

		$this->load->model('administrator');
		$this->form_validation->set_rules('user', 'Usuario', 'required|trim');
		$this->form_validation->set_rules('pass', 'Password', 'required|trim');

		if ($this->form_validation->run()) {
			if ($this->administrator->login($this->input->post('user', TRUE), $this->input->post('pass', TRUE))) {
				redirect('admin');
			} else {
				$this->session->set_flashdata('error', TRUE);
			}
		}

		$this->template->set_master_template('layouts/admin_blank');
		$this->template->write('title', 'Login');
		$this->template->write_view('content', 'login');
		$this->template->write('body_class', 'login-page');
		$this->template->render();
	}

	public function logout()
	{
		$this->admin_auth->logout();
		redirect('admin/login');
	}

	public function blocked()
	{
		if (!$this->admin_auth->is_blocked()) {
			redirect('admin/login');
		}

		$ip   = $this->input->ip_address();
		$data = array('remaining' => $this->admin_auth->time_remaining($ip));

		$this->template->set_master_template('layouts/admin_blank');
		$this->template->write('title', 'Blocked');
		$this->template->write('body_class', 'login-page');
		$this->template->write_view('content', 'blocked', $data);
		$this->template->add_js('assets/vendor/countdown.js');
		$this->template->add_js('assets/js/blocked.js');
		$this->template->render();
	}

}

/* End of file Admin.php */
/* Location: ./application/controllers/Admin.php */
