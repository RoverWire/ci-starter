<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Access extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$this->load->model('administrator');
		$this->form_validation->set_rules('user', 'Usuario', 'required|trim');
		$this->form_validation->set_rules('pass', 'Password', 'required|trim');

		if ($this->form_validation->run()) {
			if ($this->administrator->login($this->input->post('user', TRUE), $this->input->post('pass', TRUE))) {
				redirect('admin');
			} else {
				$this->session->set_flashdata('error', TRUE);
				redirect('access');
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
		redirect('access');
	}

	public function blocked()
	{
		if (!$this->admin_auth->is_blocked()) {
			redirect('access');
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

	public function lost_password()
	{
		$this->form_validation->set_rules('mail', 'Correo', 'required|valid_email|trim');
		$this->form_validation->set_error_delimiters('', '');

		if ($this->form_validation->run()) {
			if ($this->administrator->send_password($this->input->post('mail', TRUE))) {
				$this->session->set_flashdata('success', TRUE);
			} else {
				$this->session->set_flashdata('error', TRUE);
			}

			redirect('access/lost-password');
		}

		$this->template->set_master_template('layouts/admin_blank');
		$this->template->write('title', 'Lost Password');
		$this->template->write_view('content', 'lost_password');
		$this->template->write('body_class', 'login-page');
		$this->template->render();
	}

	public function reset_password($user = '', $token = '')
	{
		if (!$this->admin_auth->validate_mail_token($user, $token)) {
			$this->session->set_flashdata('error', TRUE);
			redirect('access');
		}

		$this->form_validation->set_rules('pass', 'nueva contraseña', 'required|min_length[8]|trim');
		$this->form_validation->set_rules('confirm', 'confirmar contraseña', 'required|matches[pass]|trim');
		$this->form_validation->set_error_delimiters('', '<br>');

		if ($this->form_validation->run()) {
			if ($this->administrator->change_password($user, $this->input->post('pass', TRUE))) {
				$this->session->set_flashdata('success', TRUE);
				redirect('access');
			}
		}

		$this->template->set_master_template('layouts/admin_blank');
		$this->template->write('title', 'Reset Password');
		$this->template->write_view('content', 'reset_password');
		$this->template->write('body_class', 'login-page');
		$this->template->render();
	}


}

/* End of file Access.php */
/* Location: ./application/controllers/Access.php */
