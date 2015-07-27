<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$this->load->library('analytics');
		$data = array();
		$this->template->write('title', 'Inicio');
		$this->template->write_view('content', 'admin/index');

		if (!$this->analytics->fail()) {
			$data['report'] = $this->analytics->get_visits();
			$this->template->write_view('content', 'admin/analytics', $data);
			$this->template->asset_css('analytics.css');
		}

		$this->template->render();
	}

}

/* End of file Admin.php */
/* Location: ./application/controllers/Admin.php */
