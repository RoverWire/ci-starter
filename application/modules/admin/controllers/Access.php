<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Access extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('administrator');
	}

	public function index()
	{
		$this->template->render();
	}

}

/* End of file Access.php */
/* Location: ./application/controllers/Access.php */
