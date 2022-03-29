<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

	public function index()
	{
		$this->load->helper('url');

		$this->load->view('app/index.html');
	}

	public function register()
	{
		$this->load->helper('url');

		$this->load->view('app/register.html');
	}
}
