<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * CodeIgniter Analytics Class
 *
 * Utility class for google analytics API
 * interaction. https://github.com/pierlo-upitup/google_analytics
 *
 * @package 	CodeIgniter
 * @subpackage 	Analytics
 * @category 	Library
 * @author 		Luis Felipe Pérez
 * @version 	0.2.1
 */

class Analytics {

	private $account_mail;
	private $key_location;
	private $profile_id;
	private $service;
	private $start_date;
	private $end_date;
	private $ci;
	private $error;
	private $abort;

	public function __construct($params = array())
	{
		$this->ci =& get_instance();
		$this->initialize($params);

		if (!$this->abort) {
			$this->set_credentials();
		}
	}

	public function initialize($params = array())
	{
		$this->ci->load->config('analytics', TRUE);
		$this->key_location = $this->ci->config->item('key_location', 'analytics');
		$this->account_mail = $this->ci->config->item('account_mail', 'analytics');
		$this->profile_id   = $this->ci->config->item('profile_id', 'analytics');

		foreach ($params as $key => $value) {
			if (isset($this->$key) && !empty($value) && $key != 'ci') {
				$this->$key = $value;
			}
		}

		if (empty($this->key_location) || empty($this->account_mail) || empty($this->profile_id)) {
			$this->error = 'Settings are not complete';
			$this->abort = TRUE;
		} else {
			$this->abort = FALSE;
		}

		if (!$this->abort && !file_exists($this->key_location)) {
			show_error('Invalid Google service key file location', 500, 'Google Analytics');
		}

		$this->start_date = date('Y-m-d', strtotime('-15 days'));
		$this->end_date   = date('Y-m-d');
	}

	private function set_credentials()
	{
		$client = new Google_Client();
		$client->setApplicationName("CodeigniterAnalytics");
		$service = new Google_Service_Analytics($client);

		try {
			if (isset($_SESSION['service_token'])) {
				$client->setAccessToken($_SESSION['service_token']);
			}

			$key = file_get_contents($this->key_location);
			$cred = new Google_Auth_AssertionCredentials(
			    $this->account_mail,
			    array(Google_Service_Analytics::ANALYTICS_READONLY),
			    $key
			);

			$client->setAssertionCredentials($cred);

			if($client->getAuth()->isAccessTokenExpired()) {
				$client->getAuth()->refreshTokenWithAssertion($cred);
			}

			$_SESSION['service_token'] = $client->getAccessToken();
			$this->service = new Google_Service_Analytics($client);
		} catch (Google_Service_Exception $e) {
			show_error("Error code: " . $e->getCode() . "<br>" . "Error message: " . $e->getMessage(), 500, 'Google Analytics');
		} catch (Google_Exception $e) {
			show_error("An error occurred: (" . $e->getCode() . ") " . $e->getMessage(), 500, 'Google Exception');
		}
	}

	public function fail()
	{
		return $this->abort;
	}

	public function set_date_range($start, $end)
	{
		$this->start_date = $start;
		$this->end_date   = $end;
	}

	public function get_data($metrics, $params)
	{
		try {
			$results = $this->service->data_ga->get('ga:'.$this->profile_id, $this->start_date, $this->end_date, $metrics, $params);
		} catch (Google_Service_Exception $e) {
			show_error("Error code: " . $e->getCode() . "<br>" . "Error message: " . $e->getMessage(), 500, 'Google Analytics');
		} catch (Google_Exception $e) {
			show_error("An error occurred: (" . $e->getCode() . ") " . $e->getMessage(), 500, 'Google Exception');
		}

		return $results->rows;
	}

	public function get_visits()
	{
		$metrics = "ga:users, ga:newUsers";
		$params  = array("dimensions" => "ga:date");

		return $this->get_data($metrics, $params);
	}

	public function get_page_views()
	{
		$metrics = "ga:pageviews";
		$params  = array("dimensions" => "ga:date");

		return $this->get_data($metrics, $params);
	}

	public function get_time()
	{
		$metrics = "ga:timeOnsite";
		$params  = array("dimensions" => "ga:date");

		return $this->get_data($metrics, $params);
	}

	public function get_browsers()
	{
		$metrics = "ga:users";
		$params  = array("dimensions" => "ga:browser,ga:browserVersion");

		return $this->get_data($metrics, $params);
	}

}
