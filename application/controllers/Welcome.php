<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Nylas\Nylas;

class welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		$this->load->helper('url');
		$this->load->view('welcome_message');
	}

	public function nylas_mail()
	{
		$this->load->helper('url');

		$client = new Nylas("29ls5zw0xjckd97d3umfnrt43", "93dx1sybvjt9ott2pcjnzpz9l");
		$redirect_url = 'http://localhost/index.php/welcome/nylas_callback';
		$get_auth_url = $client->createAuthURL($redirect_url);

		// redirect to Nylas auth server
		redirect($get_auth_url);
		

		$this->load->view('welcome_message');
	}

	public function nylas_callback()
	{
		$access_code = $_GET['code'];
		echo $access_code;
		echo '////////////////////';
		$client = new Nylas("29ls5zw0xjckd97d3umfnrt43", "93dx1sybvjt9ott2pcjnzpz9l");
		$get_token = $client->getAuthToken($access_code);

		// save token in session
		echo $get_token;

		$client = new Nylas("29ls5zw0xjckd97d3umfnrt43", "93dx1sybvjt9ott2pcjnzpz9l", $get_token);
		$account = $client->account();

		echo $account->email_address;
		echo $account->provider;
		echo '<br/>';


		$messages = $client->messages();
		$first_msg = $messages->first()->json();

		var_dump($first_msg["body"]);

	}


}
