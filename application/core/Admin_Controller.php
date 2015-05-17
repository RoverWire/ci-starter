<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin_Controller extends CI_Controller {

	private   $public  = array('admin/login', 'admin/logout');
	protected $allowed = array(0);

	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->library('session');
		$this->load->database();

		$current   = $this->uri->segment(1, '') . '/' . $this->uri->segment(2, '');
		$is_public = in_array($current, $this->public);

		if( ! $is_public && !$this->session->userdata('auth')) {
			redirect('admin/login');
		}

		$this->load->library('Template');
		$this->load->library('form_validation');
		$this->template->set_template('admin');
		$this->form_validation->set_error_delimiters('<div class="help-block">', '</div>');
	}

}

/* End of file Admin_Controller.php */
/* Location: ./application/core/Admin_Controller.php */
