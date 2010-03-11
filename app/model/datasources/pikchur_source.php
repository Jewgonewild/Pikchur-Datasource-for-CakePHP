<?PHP
// Copyright 2009 Pikchur
// Simple PHP Datasource that makes use of the Pikchur API. Makes use of the standard API documentation.
// Please note that all returns are converted from JSON to array notation.
// Written by: Emmanuel Pozo
// Last modified: 09.09.2009
// Documentation is available here: http://groups.google.com/group/pikchur-api/web/api-documentation

//This class requires the Curl PHP module, available in PHP 4 and 5
assert(function_exists("curl_init"));

class PikchurSource extends DataSource
{
	/* Initialization Vars */
	var $api_key = '';
	var $username = '';
	var $password = '';
	
	/*API URL's */
	var $auth_url = 'http://api.pikchur.com/auth/json';
	var $auth_key_url = 'https://api.pikchur.com/auth_keys/json';
	var $post_link_url = 'http://api.pikchur.com/post_url/json';
	var $get_url = 'https://api.pikchur.com/get/json';
	var $post_url = 'http://api.pikchur.com/post/json';
	var $feeds_url = 'http://api.pikchur.com/feeds/json';
	
	function __construct($config)
	{
		parent::__construct($config);
		$this->api_key = $this->config['api_key'];
		$this->username = $this->config['username'];
		$this->password = $this->config['password'];
	}
	/**
	* Used to authenticate a user. Return value is an auth key that can be used for future requests.
	* HTTP Method(s): POST
	* Requires Authentication: Yes
	*/
	function authenticate($params = array())
	{
		return $this->Process($this->auth_url, true, true, $params);
	}
	/**
	* Used to verify that the auth key is still valid.
	* HTTP Method(s): POST
	* Requires Authentication: Yes
	*/
	function verify_auth_key($params = array())
	{
		return $this->Process($this->auth_key_url, true, true, $params);
	}
	/**
	* Used to get user's information. If private data is required, simply provide your auth key in the request.
	* HTTP Method(s): POST
	* Requires Authentication: Yes
	*/
	function get_info($params = array())
	{
		return $this->Process($this->get_url, true, true, $params);
	}
	/**
	* Used to create a data feed
	* HTTP Method(s): POST
	* Requires Authentication: Yes
	* 
	*/
	function create_feed($params = array())
	{
		return $this->Process($this->feeds_url, true, true, $params);
	}
	/**
	* Used to post a URL to an image. Function will post an image to a given user's account. Default settings will take over if no service
	* overrides are provided.
	* HTTP Method(s): POST
	* Requires Authentication: Yes
	* 
	*/
	function post_url($params = array())
	{
		return $this->Process($this->post_link_url, true, true, $params);
	}
	/**
	* Used to post image data. Function will post an image to a given user's account. Default settings will take over if no service
	* overrides are provided
	* HTTP Method(s): POST
	* Requires Authentication: Yes
	* 
	*/
	function post_image($params = array())
	{
		return $this->Process($this->post_url, true, true, $params);
	}
	
	/**
	* Wrapper function used to make API calls via CURL
	*/
	private function Process($api_url, $require_credentials = true, $http_post = false, $parameters = null)
	{
		$curl_handle = curl_init();
		curl_setopt($curl_handle, CURLOPT_URL, $api_url);
		if ($require_credentials) 
		{
			$parameters['data[api][key]'] = $this->api_key;
			$parameters['data[api][username]'] = $this->username;
			$parameters['data[api][password]'] = $this->password;
		}
		if ($http_post)
		{
			curl_setopt($curl_handle, CURLOPT_POST, true);
		}
		if ($parameters)
		{
			curl_setopt($curl_handle, CURLOPT_POSTFIELDS, $parameters);
		}	

		curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, TRUE);
		$data = curl_exec($curl_handle);
		curl_close($curl_handle);
		return json_decode($data);
	}
}
?>