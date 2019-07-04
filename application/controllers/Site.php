<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Site extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$this->template->render();
	}

	public function info()
	{
		phpinfo();
	}

}

/* End of file Site.php */
/* Location: ./application/controllers/Site.php */
