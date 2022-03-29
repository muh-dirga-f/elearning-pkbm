<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Guru extends CI_Controller {

	public function index()
	{
		$this->load->helper('url');

		$this->load->view('app/guru/index.html');
	}
	public function home()
	{
		$this->load->helper('url');

		$this->load->view('app/guru/home.html');
	}
	public function kelas()
	{
		$this->load->helper('url');

		$this->load->view('app/guru/kelas.html');
	}
	public function materi()
	{
		$this->load->helper('url');

		$this->load->view('app/guru/materi.html');
	}
	public function siswa()
	{
		$this->load->helper('url');

		$this->load->view('app/guru/siswa.html');
	}
	public function tugas()
	{
		$this->load->helper('url');

		$this->load->view('app/guru/tugas.html');
	}
	public function buat_soal()
	{
		$this->load->helper('url');

		$this->load->view('app/guru/buat_soal.html');
	}
	public function tugas_terkumpul()
	{
		$this->load->helper('url');

		$this->load->view('app/guru/tugas_terkumpul.html');
	}
	public function report()
	{
		$this->load->helper('url');

		$this->load->view('app/guru/report.html');
	}



	// tipe soal
	public function pg()
	{
		$this->load->helper('url');

		$this->load->view('app/guru/pg.html');
	}
	public function essay()
	{
		$this->load->helper('url');

		$this->load->view('app/guru/essay.html');
	}
	public function file()
	{
		$this->load->helper('url');

		$this->load->view('app/guru/file.html');
	}

	// buka soal
	public function buka_soalPG()
	{
		$this->load->helper('url');

		$this->load->view('app/guru/buka_soalPG.html');
	}
	public function buka_soalESSAY()
	{
		$this->load->helper('url');

		$this->load->view('app/guru/buka_soalESSAY.html');
	}
	public function buka_soalFILE()
	{
		$this->load->helper('url');

		$this->load->view('app/guru/buka_soalFILE.html');
	}
}
