<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin_Controller extends CI_Controller {

	private   $public    = array('access/', 'access/blocked', 'access/lost-password', 'access/reset-password');
	private   $no_block  = array('access/blocked', 'access/lost-password', 'access/reset-password');
	protected $allowed   = array(0);

	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('url', 'crud'));
		$this->load->library(array('session', 'form_validation'));
		$this->load->model('access/administrator');
		# admin_auth object is initialized on administrator model

		$current         = $this->uri->segment(1, '') . '/' . $this->uri->segment(2, '');
		$is_public       = in_array($current, $this->public);
		$blocked_allowed = in_array($current, $this->no_block);

		if ( ! $is_public && !$this->admin_auth->is_logged()) {
			redirect('access');
		}

		if ( $is_public && $this->admin_auth->is_logged() ) {
			redirect('admin');
		}

		if ( $this->admin_auth->is_blocked() && !$blocked_allowed ) {
			redirect('access/blocked');
		}

		$this->template->set_template('admin');
		$this->form_validation->set_error_delimiters('<div class="help-block">', '</div>');
	}

}

/* End of file Admin_Controller.php */
/* Location: ./application/core/Admin_Controller.php */
