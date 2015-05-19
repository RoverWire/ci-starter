<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * CodeIgniter MY_Model Class
 *
 * Base model with CRUD functions for CodeIgniter Framework.
 * Bassed on Jamie Rumbelow model base class.
 *
 * @package 	CodeIgniter
 * @subpackage 	Libraries
 * @category 	Library
 * @author 		Luis Felipe Pérez
 * @version 	0.4.1
 */

class MY_Model extends CI_Model {

	protected $_id;
	protected $_table;
	protected $total_results;
	protected $grid_fields;
	protected $file_fields   = array();
	protected $field_names   = array();

	protected $pre_insert = array();
	protected $pos_insert = array();
	protected $pre_update = array();
	protected $pos_update = array();
	protected $pre_delete = array();
	protected $pos_delete = array();
	protected $pre_get	  = array();
	protected $pos_get	  = array();

	protected $callback_params = array();

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->helper('inflector');
		$this->set_table();
	}

	/* --------------------------------------------------------------
	 * INTERNAL METHODS
	 * ------------------------------------------------------------ */

	/**
	 * Sets the table to use with the model. If not provided with $this->_table
	 * var, the function try to guess the name using the plural name of the
	 * model.
	 */

	protected function set_table()
	{
		if (empty($this->_table) || $this->_table == NULL) {
			$this->_table = plural(preg_replace('/(_m|_model)?$/', '', strtolower(get_class($this))));
		}
	}

	/**
	 * Trigger a method before or after a CRUD function
	 *
	 * @param  string 	$event event to trigger
	 * @param  mixed 	$data  could be an array of data to process
	 * @return mixed 	$data  array processed or FALSE if it is not provided
	 */

	protected function trigger($event, $data = FALSE)
	{
		if (isset($this->$event) && is_array($this->$event)) {
			foreach ($this->$event as $method) {
				if (strpos($method, '(')) {
					preg_match('/([a-zA-Z0-9\_\-]+)(\(([a-zA-Z0-9\_\-\., ]+)\))?/', $method, $matches);
					$method = $matches[1];
					$this->callback_params = explode(',', $matches[3]);
				}

				$data = call_user_func_array(array($this, $method), array($data));
			}
		}

		return $data;
	}

	/**
	 * Serialises data for you automatically, allowing you to pass
	 * through objects and let it handle the serialisation in the background
	 *
	 * @param  array $data data for process before or after an operation
	 * @return array $data serialized data
	 */

	public function serialize($data)
	{
		foreach ($this->callback_params as $field) {
			$data[$field] = serialize($data[$field]);
		}

		return $data;
	}

	/**
	 * Unserialises data for you automatically, allowing you to pass
	 * through objects and let it handle the unserialisation in the background
	 *
	 * @param  array $data data for process
	 * @return array $data unserialized data
	 */

	public function unserialize($data)
	{
		foreach ($this->callback_params as $field) {
			if (is_array($data)) {
				$data[$field] = unserialize($data[$field]);
			} else {
				$data->$field = unserialize($data->$field);
			}
		}

		return $data;
	}

	/* --------------------------------------------------------------
	 * CRUD METHODS
	 * ------------------------------------------------------------ */

	/**
	 * Inserts a new row on the table.
	 *
	 * @param  array $data info to insert
	 * @return mixed       returns the inserted id on sucess or FALSE
	 */

	public function insert($data)
	{
		if (is_array($data) && count($data) > 0) {
			$data = $this->trigger('pre_insert', $data);
			$this->db->insert($this->_table, $data);
			$id = $this->db->insert_id();
			$this->trigger('pos_insert', $id);
			return $id;
		} else {
			return FALSE;
		}
	}

	/**
	 * Update table data. It can be chainable with 'where' query helper.
	 *
	 * @param  array $data 	info to be updated
	 * @param  int 	 $id 	primary key of the row to be updated (optional)
	 * @return mixed 		returns an integer on sucess or FALSE
	 */

	public function update($data, $id = '')
	{
		if (is_array($data) && count($data) > 0) {
			$data = $this->trigger('pre_update', $data);

			if (!empty($id)) {
				$this->db->where($this->_id, $id);
			}

			$this->db->update($this->_table, $data);
			$rows = $this->db->affected_rows();
			$this->trigger('pos_update', array($data, $rows));
			return $rows;
		} else {
			return FALSE;
		}
	}

	/**
	 * Delete rows from table using primary key as reference
	 *
	 * @param  mixed $id 	a single value or array of primary keys (optional)
	 * @return mixed      	returns a integer number of affected rows.
	 */

	public function delete($id = '')
	{
		$id = $this->trigger('pre_delete', $id);

		if (!empty($id)) {
			if (!is_array($id)) {
				$id = array($id);
			}

			if (count($this->file_fields) > 0) {
				$query = $this->db->where_in($this->_id, $id)->get($this->_table);

				foreach ($query->result() as $row) {
					foreach ($this->file_fields as $file) {
						if(isset($row->$file) && !empty($row->$file) && file_exists('.'.$row->$file)) {
							unlink('.'.$row->$file);
						}
					}
				}

				clearstatcache();
			}

			$this->db->where_in($this->_id, $id);
		}

		$this->db->delete($this->_table);
		$rows = $this->db->affected_rows();
		$this->trigger('pos_delete', array($id, $rows));

		return $rows;
	}

	/**
	 * Get result set from table. If $id value are provided, it find only
	 * rows with specified primary key values. This function also can be
	 * chainable with query hepelrs like 'where', 'where in', etc.
	 *
	 * @param  mixed  $id 		can be a single value or array (optional)
	 * @return object $result   data result from table
	 */

	public function get($id = '')
	{
		$id = $this->trigger('pre_get', $id);
		if (!empty($id)) {
			if (is_array($id)) {
				$this->db->where_in($this->_id, $id);
			} else {
				$this->db->where($this->_id, $id);
				$this->db->limit(1);
			}
		}

		$result = $this->db->get($this->_table);
		$result = $this->trigger('pos_get', $result);

		return $result;
	}

	/* --------------------------------------------------------------
	 * QUERY HELPERS || ::EXPERIMENTAL::
	 * ------------------------------------------------------------ */

	/**
	 * Magic Method __call allows to execute DB methods like a wrapper. Also it allows
	 * chaining method for some active record query helpers.
	 *
	 * @param  string $method 	method name
	 * @param  array  $params 	parameters to pass at the method
	 * @return mixed  			returns $this object or FALSE if method doesn't exists
	 */

	public function __call($method, $params)
	{
		if (method_exists($this->db, $method)) {
			if (in_array($method, array('count_all', 'empty_table', 'truncate'))) {
				return call_user_func_array(array($this->db, $method), array($this->_table));
			} else if (in_array($method, array('query', 'simple_query', 'insert_id', 'plattform', 'version', 'last_query'))) {
				return call_user_func_array(array($this->db, $method), $params);
			} else {
				call_user_func_array(array($this->db, $method), $params);
				return $this;
			}
		} else {
			return FALSE;
		}
	}

	/**
	 * Count all rows of a result that a query produces
	 *
	 * @return int rows
	 */

	public function count_all_results()
	{
		$this->total_results = $this->db->count_all_results($this->_table);
		return $this->total_results;
	}

	/**
	 * Count all rows of a query using MySQL SQL_CALC_FOUND_ROWS
	 *
	 * @return int rows
	 */

	public function found_rows()
	{
		$query = $this->db->query('SELECT FOUND_ROWS() AS total');
		$data  = $query->row();
		return $data->total;
	}

	/**
	 * Executes a simple search. Useful for a single table model
	 * @param  string  $search string to search (optional)
	 * @param  integer $offset data offset (optional)
	 * @param  integer $limit  size of data set, if the value is 0, will return all results.
	 * @return object          data result.
	 */

	public function search($search = '', $offset = 0, $limit = 15)
	{
		$fields = $this->grid_fields;

		if (empty($fields)) {
			$fields = '*';
		}

		$this->db->select("SQL_CALC_FOUND_ROWS $fields", FALSE);
		$this->search_procedure($search);
		$limit  = (is_numeric($limit)) ? $limit:15;

		if ($limit != 0) {
			$offset = (is_numeric($offset)) ? $offset:0;
			$this->db->limit($limit, $offset);
		}

		return $this->db->get($this->_table);
	}

	protected function search_procedure($search = '')
	{
		/*
		We can redeclare the function with query
		filter statements like 'join', 'where'
		and 'order by' on our extended classes
		 */
	}

	/* --------------------------------------------------------------
	 * UTILITIES
	 * ------------------------------------------------------------ */

	/**
	 * Static method that generate a stamp string. Is useful to rename
	 * files uploaded and avoid overwriting.
	 *
	 * @return string stamp
	 */

	public static function calculate_id()
	{
		return date("Ymds") . sprintf("%02d", rand(0,99));
	}

	/**
	 * Get the table name from the model
	 *
	 * @return string table name
	 */

	public function table()
	{
		return $this->_table;
	}

	/**
	 * Returns an array containing the column names of the table
	 *
	 * @return array
	 */

	public function table_columns()
	{
		if (count($this->field_names) > 0) {
			return $this->field_names;
		} else {
			$query = $this->db->query("SHOW COLUMNS FROM ".$this->_table);
			foreach ($query->result() as $row) {
				$this->field_names[] = $row->Field;
			}
			return $this->field_names;
		}
	}

	/**
	 * Generates a initial set array of values to be passed a form view. If a
	 * $data array is provided (could be post values), this data will be merged
	 *
	 * @param  array  $data array data set
	 * @return array        data initialized
	 */

	public function prepare_data($data = '', $return = '')
	{
		if (! is_array($return)) {
			$return = array();

			if (count($this->field_names) == 0) {
				$this->table_columns();
			}

			foreach ($this->field_names as $field) {
				$return[$field] = '';
			}
		}

		if(is_array($data)){
			$return = array_merge($return, $data);
		}

		return $return;
	}

	/**
	 * Search a row with a field that contains a specified value. If
	 * a row is finded, then returns TRUE.
	 *
	 * @param  string $value value to search in the column
	 * @param  string $field column name to search
	 * @return bool
	 */

	public function exists($value, $field = '')
	{
		if(empty($field)){
			$field = $this->_id;
		}

		$num = $this->db->where($field, $value)->count_all_results($this->_table);
		return ($num > 0) ? TRUE : FALSE;
	}

	/**
	 * Produces a array that can be used with form helper. This also can be chained
	 * with other query helpers like:
	 * $arr = $this->model->where('field', $search)->order_by($label, 'ASC')->dropdown($value, $label);
	 *
	 * @param  string $value field that will be used as value
	 * @param  string $label field that will be used as label. If not provided, value field is used.
	 * @return array         option array
	 */

	public function dropdown($value, $label = '')
	{
		if (!empty($value)) {
			$query = $this->db->get($this->_table);
			$opt   = array();
			foreach ($query->result() as $row) {
				$opt[$row->$value] = (!empty($label)) ? $row->$label : $row->$value;
			}

			return $opt;
		} else {
			return array();
		}
	}

	/**
	 * Produces option tags for dropdowns. It uses dropdown function and is chainable
	 * with query helpers.
	 *
	 * @param  string $value    field for values
	 * @param  string $label    field for labels, if not provided, value field is used (optional)
	 * @param  string $selected preselected value (optional)
	 * @return string           string option tags
	 */

	public function dropdown_opt($value, $label = '', $selected = '')
	{
		$opt = $this->dropdown($value, $label);
		$str = '';

		foreach ($opt as $key => $val) {
			$attr = ($key == $selected) ? ' selected' : '';
			$str .= '<option value="'. $key . '"' . $attr . '>' . $val . "</option>\n";
		}

		return $str;
	}

}

/* End of file MY_Model.php */
/* Location: ./application/core/MY_Model.php */
