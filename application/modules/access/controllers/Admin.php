<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('administrator');
	}

	public function index()
	{
		if ($this->input->post('del')) {
			$this->administrator->delete($this->input->post('del'));
			$this->session->set_flashdata('msg_success', 'Los usuarios han sido eliminados de manera permanente.');
			redirect('admin/access');
		}

		$this->load->library('pagination');
		$default     = array('search', 'offset');
		$param       = $this->uri->uri_to_assoc(3, $default);
		$num_results = 15;

		$param['search']     = ($this->input->post('search') != '') ? $this->input->post('search', TRUE):$param['search'];
		$data                = array();
		$data['msg_success'] = $this->session->flashdata('msg_success');
		$data['query']       = $this->administrator->search( $param['search'], $param['offset'], $num_results );
		$data['search']      = $param['search'];
		$data['form_action'] = '/admin/access';

		if (empty($param['search'])) {
			unset($param['search']);
			$config['uri_segment'] = 4;
		} else {
			$config['uri_segment'] = 6;
		}

		$param['offset']      = '';
		$config['total_rows'] = $this->administrator->found_rows();
		$config['base_url']   = '/admin/access/'.$this->uri->assoc_to_uri($param);
		$config['per_page']   = $num_results;

		$this->pagination->initialize($config);

		$data['pages']       = $this->pagination->create_links();
		$config['num_links']  = 1;

		$this->pagination->initialize($config);
		$data['pages_mobile']   = $this->pagination->create_links();


		$this->template->asset_js('crud.js');
		$this->template->write('title', 'Administradores');
		$this->template->write_view('content', 'list', $data);
		$this->template->render();
	}

	public function add()
	{
		$this->form_validation->set_rules('data[name]', 'nombre', 'required|trim');
		$this->form_validation->set_rules('data[mail]', 'correo', 'required|valid_email|trim');
		$this->form_validation->set_rules('data[user]', 'usuario', 'required|is_unique[administrators.user]|trim');
		$this->form_validation->set_rules('data[pass]', 'contraseña', 'required|min_length[8]|matches[repeat]|trim');
		$this->form_validation->set_rules('repeat', 'repetir', 'required|trim');

		if ($this->form_validation->run()) {
			$this->administrator->insert($this->input->post('data', TRUE));
			$this->session->set_flashdata('msg_success', 'El usuario administrador ha sido agregado.');
			redirect('admin/access');
		}

		$data = $this->administrator->prepare_data($this->input->post('data'));
		$data['form_title'] = 'Nuevo Administrador';
		$data['breadcrumb'] = 'Nuevo';

		if(!isset($_POST['data'])){
			$data['active'] = 1;
		}

		$this->template->write_view('content', 'form', $data);
		$this->template->add_css('assets/vendor/switchery/switchery.css');
		$this->template->add_js('assets/vendor/switchery/switchery.js');
		$this->template->render();
	}

	public function edit($id = '')
	{
		if (!$this->administrator->exists($id)) {
			redirect('admin/administrator');
		}

		$stored = $this->administrator->get($id)->row_array();
		$this->form_validation->set_rules('data[name]', 'nombre', 'required|trim');
		$this->form_validation->set_rules('data[mail]', 'correo', 'required|valid_email|trim');

		if (isset($_POST['data']) && strtolower($stored['user']) != strtolower($_POST['data']['user'])) {
			$this->form_validation->set_rules('data[user]', 'usuario', 'required|is_unique[administrators.user]|trim');
		}

		if (isset($_POST['data']['pass']) && !empty($_POST['data']['pass'])) {
			$this->form_validation->set_rules('data[pass]', 'contraseña', 'required|min_length[8]|matches[repeat]|trim');
			$this->form_validation->set_rules('repeat', 'repetir', 'required|trim');
		}

		if ($this->form_validation->run()) {
			$this->administrator->update($this->input->post('data', TRUE), $id);
			$this->session->set_flashdata('msg_success', 'Los datos del usuario han sido actualizados.');

			if ($id == $_SESSION['session_id']) {
				$this->administrator->build_session('id', $id);
			}

			redirect('admin/access');
		}

		$data = $this->administrator->prepare_data($this->input->post('data'), $stored);
		$data['form_title'] = 'Editar Administrador';
		$data['breadcrumb'] = 'Editar';

		$this->template->write_view('content', 'form', $data);
		$this->template->add_css('assets/vendor/switchery/switchery.css');
		$this->template->add_js('assets/vendor/switchery/switchery.js');
		$this->template->render();
	}

	public function delete($id = '')
	{
		if (!empty($id) && $id > 1) {
			$this->administrator->delete($id);
			$this->session->set_flashdata('msg_success', 'El usuario administrador ha sido eliminado.');
		}

		redirect('admin/access');
	}

	public function change_password()
	{
		$this->template->render();
	}

}

/* End of file Access.php */
/* Location: ./application/controllers/Access.php */
