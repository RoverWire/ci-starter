<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Setup extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$this->load->library('migration');

		if (! $this->migration->current()) {
			show_error($this->migration->error_string());
		} else {
			echo "Done.";
		}
	}

}

/* End of file Setup.php */
/* Location: ./application/controllers/Setup.php */
