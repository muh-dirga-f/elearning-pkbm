<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Siswa extends CI_Controller {

	public function index()
	{
		$this->load->helper('url');

		$this->load->view('app/siswa/index.html');
	}
	public function home()
	{
		$this->load->helper('url');

		$this->load->view('app/siswa/home.html');
	}
	public function kelas()
	{
		$this->load->helper('url');

		$this->load->view('app/siswa/kelas.html');
	}
	public function data_siswa()
	{
		$this->load->helper('url');

		$this->load->view('app/siswa/data_siswa.html');
	}
	public function tugas()
	{
		$this->load->helper('url');

		$this->load->view('app/siswa/tugas.html');
	}
	public function buka_soalPG()
	{
		$this->load->helper('url');

		$this->load->view('app/siswa/buka_soalPG.html');
	}
	public function buka_soalESSAY()
	{
		$this->load->helper('url');

		$this->load->view('app/siswa/buka_soalESSAY.html');
	}
	public function buka_soalFILE()
	{
		$this->load->helper('url');

		$this->load->view('app/siswa/buka_soalFILE.html');
	}
	public function materi()
	{
		$this->load->helper('url');

		$this->load->view('app/siswa/materi.html');
	}

}
