<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

	public function index()
	{
		$this->load->helper('url');

		$this->load->view('app/admin/index.html');
	}
	public function home()
	{
		$this->load->helper('url');

		$this->load->view('app/admin/home.html');
	}
	public function user()
	{
		$this->load->helper('url');

		$this->load->view('app/admin/user.html');
	}
	public function kelas()
	{
		$this->load->helper('url');

		$this->load->view('app/admin/kelas.html');
	}
	public function materi()
	{
		$this->load->helper('url');

		$this->load->view('app/admin/materi.html');
	}

}
