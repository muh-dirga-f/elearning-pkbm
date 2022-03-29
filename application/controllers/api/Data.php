<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class Data extends REST_Controller {

    function __construct()
    {
        parent::__construct();

        $this->output->set_header( "Access-Control-Allow-Origin: *" );
        $this->output->set_header( "Access-Control-Allow-Credentials: true" );
        $this->output->set_header( "Access-Control-Allow-Methods: POST, GET, PUT, DELETE, OPTIONS" );
        $this->output->set_header( "Access-Control-Max-Age: 604800" );
        $this->output->set_header( "Access-Control-Request-Headers: x-requested-with" );
        $this->output->set_header( "Access-Control-Allow-Headers: x-requested-with, x-requested-by" );
    }

    ///////////////////////////////// data users //////////////////////////////
    ///////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////


    public function edit_tahun_ajaran_post() //edit data
    {
        $nis = $this->post('nis');
        
        $cek = $this->db->get_where('tahun_ajaran', ['nis' => $nis])->num_rows();
        if($cek > 0){
            $data = [
                'nis' => $this->post('nis'),
                'tahun_ajaran' => $this->post('tahun_ajaran'),
                'semester_aktif' => $this->post('semester')
            ];
            $data = $this->db->update('tahun_ajaran', $data, ['nis' => $nis]);
        }else{
            $data = [
                'id' => NULL,
                'nis' => $this->post('nis'),
                'tahun_ajaran' => $this->post('tahun_ajaran'),
                'semester_aktif' => $this->post('semester')
            ];
            $data = $this->db->insert('tahun_ajaran', $data);
        }
        
        if ($this->db->affected_rows() > 0)
        {
            $this->set_response([
                'status' => TRUE,
                'data' => $data,
                'message' => 'data user berhasil diubah'], REST_Controller::HTTP_OK);
        } else {
            $this->set_response([
                'status' => FALSE,
                'message' => 'data user gagal diubah'], REST_Controller::HTTP_OK);
        }
    }


    ///////////////////////////////// data users //////////////////////////////
    ///////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////
    public function users_get() //view data
    {

        $id = $this->get('id');

        if ($id === NULL)
        {
            $users = $this->db->get('user')->result_array();
            if ($users)
            {
                $this->response([
                'status' => TRUE,
                'data' => $users], REST_Controller::HTTP_OK);
            } else {
                $this->response([
                'status' => FALSE,
                'data' => $users,
                'message' => 'user tidak ditemukan'], REST_Controller::HTTP_OK);
            }
        }
        else
        {
            $users = $this->db->get_where('user', ['id' => $id])->result_array();
            if ($users)
            {
                $this->response([
                'status' => TRUE,
                'data' => $users], REST_Controller::HTTP_OK);
            } else {
                $this->response([
                'status' => FALSE,
                'data' => $users,
                'message' => 'user tidak ditemukan'], REST_Controller::HTTP_OK); 
            }
        }
    }

    public function users_post() //add data
    {
        $no = $this->post('no');
        $user = $this->post('user');

        $cek = $this->db->query('SELECT * FROM user WHERE user="'.$user.'" OR no="'.$no.'"')->num_rows();
        if ($cek > 0) 
        {
            $this->set_response([
                'status' => FALSE,
                'message' => 'nis/nip atau user sudah ada'], REST_Controller::HTTP_OK);
        } else {
            $data = [
            'id' => NULL,
            'no' => $this->post('no'),
            'user' => $this->post('user'),
            'fullname' => $this->post('fullname'),
            'pass' => $this->post('pass'),
            'email' => $this->post('email'),
            'level' => $this->post('level')
            ];
            if(!empty($_FILES['photo']['name']))
            {
                $path = 'upload/';
                $type = 'jpeg|jpg|png';
                $name = round(microtime(true) * 1000);
                $field = 'photo';
                $upload = $this->_do_upload($path,$type,$name,$field);
                $data['photo'] = $upload;
            }else{
                $data['photo'] = "";
            }
             
            $this->db->insert('user', $data);
            
            if ($this->db->affected_rows() > 0)
            {
                $this->set_response([
                    'status' => TRUE,
                    'message' => 'user berhasil ditambahkan'], REST_Controller::HTTP_OK);
            } else {
                $this->set_response([
                    'status' => FALSE,
                    'message' => 'user gagal ditambahkan'], REST_Controller::HTTP_OK);
            }
        }
    }

    public function edit_users_post() //edit data
    {
        $id = $this->post('id');
        $no = $this->post('no');
        $user = $this->post('user');
        
        $cek = $this->db->query('SELECT * FROM user WHERE id<>"'.$id.'" AND id IN (SELECT id FROM user WHERE user="'.$user.'" OR no="'.$no.'")')->num_rows();
        if ($cek > 0) 
        {
            $this->set_response([
                'status' => FALSE,
                'message' => 'nis/nip atau user sudah ada'], REST_Controller::HTTP_OK);
        } else {
            $data = [
                'no' => $this->post('no'),
                'user' => $this->post('user'),
                'fullname' => $this->post('fullname'),
                'pass' => $this->post('pass'),
                'email' => $this->post('email'),
                'level' => $this->post('level')
            ];
            if(!empty($_FILES['photo']['name']))
            {
                $path = 'upload/';
                $type = 'jpeg|jpg|png';
                $name = round(microtime(true) * 1000);
                $field = 'photo';
                $upload = $this->_do_upload($path,$type,$name,$field);
                $data['photo'] = $upload;
            }
            
            $this->db->update('user', $data, ['id' => $id]);
            
            if ($this->db->affected_rows() > 0)
            {
                $this->set_response([
                    'status' => TRUE,
                    'id' => $id,
                    'message' => 'data user berhasil diubah'], REST_Controller::HTTP_OK);
            } else {
                $this->set_response([
                    'status' => FALSE,
                    'message' => 'data user gagal diubah'], REST_Controller::HTTP_OK);
            }
        }
    }

    public function del_users_post() //delet data
    {
        $id = $this->post('id');
        if ($id === NULL)
        {
            $this->response([
                'status' => FALSE,
                'message' => 'masukkan id'], REST_Controller::HTTP_OK);
        } else {
            $this->db->delete('user', ['id' => $id]);

            if ($this->db->affected_rows() > 0)
            {
                $this->set_response([
                    'status' => TRUE,
                    'id' => $id,
                    'message' => 'data berhasil terhapus'], REST_Controller::HTTP_OK);
            } else {
                $this->set_response([
                    'status' => FALSE,
                    'id' => $id,
                    'message' => 'id tidak ada'], REST_Controller::HTTP_OK);
            }
        }
    }
    ///////////////////////////////// data materi //////////////////////////////
    ///////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////
    public function materi_get() //view data
    {

        $id = $this->get('id');
        $no = $this->get('no');

        if ($id === NULL)
        {
            if ($no === NULL){
                $this->db->select('materi.id,materi.no,materi.judul,materi.kelas,materi.link,kelas.nama_kelas');    
                $this->db->from('materi');
                $this->db->join('kelas', 'kelas.kode = materi.kelas');
                $materi = $this->db->get()->result_array();
                if ($materi)
                {
                    $this->response([
                    'status' => TRUE,
                    'data' => $materi], REST_Controller::HTTP_OK);
                } else {
                    $this->response([
                    'status' => FALSE,
                    'data' => $materi,
                    'message' => 'materi tidak ditemukan'], REST_Controller::HTTP_OK);
                }
            }else{
                $materi = $this->db->query("SELECT materi.id,materi.no,materi.judul,materi.kelas,materi.link,kelas.nama_kelas FROM materi INNER JOIN kelas ON materi.kelas = kelas.kode WHERE materi.no = '".$no."'")->result_array();
                if ($materi)
                {
                    $this->response([
                    'status' => TRUE,
                    'data' => $materi], REST_Controller::HTTP_OK);
                } else {
                    $this->response([
                    'status' => FALSE,
                    'data' => $materi,
                    'message' => 'materi tidak ditemukan'], REST_Controller::HTTP_OK);
                }
            }
        }
        else
        {
            $materi = $this->db->get_where('materi', ['id' => $id])->result_array();
            if ($materi)
            {
                $this->response([
                'status' => TRUE,
                'data' => $materi], REST_Controller::HTTP_OK);
            } else {
                $this->response([
                'status' => FALSE,
                'data' => $materi,
                'message' => 'materi tidak ditemukan'], REST_Controller::HTTP_OK); 
            }
        }
    }

    public function materi_post() //add data
    {
            $data = [
            'id' => NULL,
            'no' => $this->post('no'),
            'judul' => $this->post('judul'),
            'kelas' => $this->post('kelas'),
            ];
            if(!empty($_FILES['link']['name']))
            {
                $path = 'upload/materi/';
                $type = 'docx|doc|pdf';
                $name = round(microtime(true) * 1000).'_'.$_FILES['link']['name'];
                $field = 'link';
                $upload = $this->_do_upload($path,$type,$name,$field);
                $data['link'] = $upload;
            }else{
                $data['link'] = "";
            }
             
            $this->db->insert('materi', $data);
            
            if ($this->db->affected_rows() > 0)
            {
                $this->set_response([
                    'status' => TRUE,
                    'message' => 'materi berhasil ditambahkan'], REST_Controller::HTTP_OK);
            } else {
                $this->set_response([
                    'status' => FALSE,
                    'message' => 'materi gagal ditambahkan'], REST_Controller::HTTP_OK);
            }
    }

    public function edit_materi_post() //edit data
    {
        $id = $this->post('id');
            $data = [
                'no' => $this->post('no'),
                'judul' => $this->post('judul'),
                'kelas' => $this->post('kelas'),
            ];
            if(!empty($_FILES['link']['name']))
            {
                $path = 'upload/materi/';
                $type = 'docx|doc|pdf';
                $name = round(microtime(true) * 1000);
                $field = 'link';
                $upload = $this->_do_upload($path,$type,$name,$field);
                $data['link'] = $upload;
            }
            
            $this->db->update('materi', $data, ['id' => $id]);
            
            if ($this->db->affected_rows() > 0)
            {
                $this->set_response([
                    'status' => TRUE,
                    'id' => $id,
                    'message' => 'data materi berhasil diubah'], REST_Controller::HTTP_OK);
            } else {
                $this->set_response([
                    'status' => FALSE,
                    'message' => 'data materi gagal diubah'], REST_Controller::HTTP_OK);
            }
        
    }

    public function del_materi_post() //delet data
    {
        $id = $this->post('id');
        if ($id === NULL)
        {
            $this->response([
                'status' => FALSE,
                'message' => 'masukkan id'], REST_Controller::HTTP_OK);
        } else {
            $this->db->delete('materi', ['id' => $id]);

            if ($this->db->affected_rows() > 0)
            {
                $this->set_response([
                    'status' => TRUE,
                    'id' => $id,
                    'message' => 'data berhasil terhapus'], REST_Controller::HTTP_OK);
            } else {
                $this->set_response([
                    'status' => FALSE,
                    'id' => $id,
                    'message' => 'id tidak ada'], REST_Controller::HTTP_OK);
            }
        }
    }

    /////////////////////// data kelas //////////////////////////
    /////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////
    public function kelas_get() //view data
    {

        $id = $this->get('id');
        $no = $this->get('no');
        $kode = $this->get('kode');

        if ($id === NULL)
        {
            if ($no === NULL)
            {
                if ($kode === NULL)
                {
                    $kelas = $this->db->get('kelas')->result_array();
                    if ($kelas)
                    {
                        $this->response([
                        'status' => TRUE,
                        'data' => $kelas], REST_Controller::HTTP_OK);
                    } else {
                        $this->response([
                        'status' => FALSE,
                        'data' => [],
                        'message' => 'user tidak ditemukan'], REST_Controller::HTTP_OK);
                    }
                }
                else {
                    $kelas = $this->db->query('SELECT * FROM (SELECT kelas_siswa.kd_kelas,kelas_siswa.nis FROM kelas_siswa UNION SELECT kelas.kode,kelas.no FROM kelas) AS K INNER JOIN user ON K.nis = user.no WHERE K.kd_kelas = "'.$kode.'"')->result_array();
                    if ($kelas)
                    {
                        $this->response([
                        'status' => TRUE,
                        'data' => $kelas], REST_Controller::HTTP_OK);
                    } else {
                        $this->response([
                        'status' => FALSE,
                        'data' => $kelas,
                        'message' => 'user tidak ditemukan'], REST_Controller::HTTP_OK); 
                    }
                }
            }else{
                $kelas = $this->db->get_where('kelas', ['no' => $no])->result_array();
                if ($kelas)
                {
                    $this->response([
                    'status' => TRUE,
                    'data' => $kelas], REST_Controller::HTTP_OK);
                } else {
                    $this->response([
                    'status' => FALSE,
                    'data' => $kelas,
                    'message' => 'user tidak ditemukan'], REST_Controller::HTTP_OK); 
                }
            }
        }
        else
        {
            $kelas = $this->db->get_where('kelas', ['id' => $id])->result_array();
            if ($kelas)
            {
                $this->response([
                'status' => TRUE,
                'data' => $kelas], REST_Controller::HTTP_OK);
            } else {
                $this->response([
                'status' => FALSE,
                'message' => 'user tidak ditemukan'], REST_Controller::HTTP_OK); 
            }
        }
    }

    public function kelas_post() //add data
    {
        $kode = $this->post('kode');

        $cek = $this->db->query('SELECT * FROM kelas WHERE kode="'.$kode.'"')->num_rows();
        if ($cek > 0) 
        {
            $this->set_response([
                'status' => FALSE,
                'message' => 'kelas sudah ada'], REST_Controller::HTTP_OK);
        } else {
            $data = [
            'id' => NULL,
            'kode' => $this->post('kode'),
            'nama_kelas' => $this->post('nama_kelas'),
            'no' => $this->post('no'),
            'status' => $this->post('status'),
            ];
            $this->db->insert('kelas', $data);
            
            if ($this->db->affected_rows() > 0)
            {
                $this->set_response([
                    'status' => TRUE,
                    'message' => 'kelas berhasil ditambahkan'], REST_Controller::HTTP_OK);
            } else {
                $this->set_response([
                    'status' => FALSE,
                    'message' => 'kelas gagal ditambahkan'], REST_Controller::HTTP_OK);
            }
        }
    }

    public function edit_kelas_post() //edit data
    {
        $id = $this->post('id');
        $kode = $this->post('kode');
        
        $cek = $this->db->query('SELECT * FROM kelas WHERE id<>"'.$id.'" AND id IN (SELECT id FROM kelas WHERE kode="'.$kode.'")')->num_rows();
        if ($cek > 0) 
        {
            $this->set_response([
                'status' => FALSE,
                'message' => 'kode sudah ada'], REST_Controller::HTTP_OK);
        } else {
            $data = [
                'kode' => $this->post('kode'),
                'nama_kelas' => $this->post('nama_kelas'),
                'no' => $this->post('no'),
                'status' => $this->post('status'),
            ];
            $this->db->update('kelas', $data, ['id' => $id]);
            
            if ($this->db->affected_rows() > 0)
            {
                $this->set_response([
                    'status' => TRUE,
                    'id' => $id,
                    'message' => 'data kelas berhasil diubah'], REST_Controller::HTTP_OK);
            } else {
                $this->set_response([
                    'status' => FALSE,
                    'message' => 'data kelas gagal diubah'], REST_Controller::HTTP_OK);
            }
        }
    }

    public function del_kelas_post() //delet data
    {
        $id = $this->post('id');
        if ($id === NULL)
        {
            $this->response([
                'status' => FALSE,
                'message' => 'masukkan id'], REST_Controller::HTTP_OK);
        } else {
            $this->db->delete('kelas', ['id' => $id]);

            if ($this->db->affected_rows() > 0)
            {
                $this->set_response([
                    'status' => TRUE,
                    'id' => $id,
                    'message' => 'data berhasil terhapus'], REST_Controller::HTTP_OK);
            } else {
                $this->set_response([
                    'status' => FALSE,
                    'id' => $id,
                    'message' => 'id tidak ada'], REST_Controller::HTTP_OK);
            }
        }
    }

    /////////////////////////////// fungsi data tugas ///////////////////////////////
    ////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////
    public function tugas_guru_get() // tugas.html(guru)
    {
        $kode = $this->get('kode');

        if ($kode != null)
        {
            // $tugas = $this->db->query('SELECT * FROM `tugas` WHERE kd_kelas="'.$kode.'"')->result_array();

            $this->db->select('tugas.id,tugas.kd_kelas,tugas.judul,tugas.batas_waktu,tugas.tipe_soal,tugas.soal,nilai.id_tugas,nilai.jawaban,nilai.tanggal,nilai.nis,nilai.nilai');
            $this->db->from('tugas');
            $this->db->join('nilai','nilai.id_tugas=tugas.id','LEFT');
            $this->db->where('tugas.kd_kelas', $kode);
            $this->db->group_by('tugas.id');
            $query=$this->db->get();
            $tugas= $query->result_array();
            if ($tugas)
            {
                $this->response([
                'status' => TRUE,
                'data' => $tugas], REST_Controller::HTTP_OK);
            } else {
                $this->response([
                'status' => FALSE,
                'data' => $tugas,
                'message' => 'tugas tidak ditemukan'], REST_Controller::HTTP_OK); 
            }
        }
        else 
        {
            $tugas = $this->db->get('tugas')->result_array();
            if ($tugas)
            {
                $this->response([
                'status' => TRUE,
                'data' => $tugas], REST_Controller::HTTP_OK);
            } else {
                $this->response([
                'status' => FALSE,
                'message' => 'tugas tidak ditemukan'], REST_Controller::HTTP_OK);
            }
        }
    }
    public function tugas_get() // tugas.html(siswa)
    {
        $kode = $this->get('kode');
        $nis = $this->get('nis');

        if ($kode != NULL)
        {
            // $tugas = $this->db->query('SELECT * FROM `tugas` WHERE kd_kelas="'.$kode.'"')->result_array();

            $this->db->select('tugas.id,tugas.kd_kelas,tugas.judul,tugas.batas_waktu,tugas.tipe_soal,tugas.soal,nilai.id_tugas,nilai.jawaban,nilai.tanggal,nilai.nis,nilai.nilai');
            $this->db->from('tugas');
            $this->db->join('nilai','nilai.id_tugas=tugas.id','LEFT');
            $this->db->where('tugas.kd_kelas', $kode);
            $this->db->where('nilai.nis', $nis);
            $this->db->group_by('nilai.id_tugas');
            $query=$this->db->get();
            $tugas= $query->result_array();
            if ($tugas)
            {
                $this->response([
                'status' => TRUE,
                'data' => $tugas], REST_Controller::HTTP_OK);
            } else {
                $this->response([
                'status' => FALSE,
                'data' => $tugas,
                'message' => 'tugas tidak ditemukan'], REST_Controller::HTTP_OK); 
            }
        }
        else 
        {
            $tugas = $this->db->get('tugas')->result_array();
            if ($tugas)
            {
                $this->response([
                'status' => TRUE,
                'data' => $tugas], REST_Controller::HTTP_OK);
            } else {
                $this->response([
                'status' => FALSE,
                'message' => 'tugas tidak ditemukan'], REST_Controller::HTTP_OK);
            }
        }
    }

    /////////////////////////////// fungsi save soal ///////////////////////////////
    ////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////
    public function pg_post() //fungsi save soal
    {
        $kelas = $this->post('kelas');
        $judul = $this->post('judul');
        $batas = $this->post('batas');
        $soal = $this->post('soal');

            $data = [
            'id' => NULL,
            'kd_kelas' => $kelas,
            'judul' => $judul,
            'batas_waktu' => $batas,
            'tipe_soal' => 'PG',
            'soal' => $soal
            ];
            $tugas  = $this->db->insert('tugas', $data);
            $insert_id = $this->db->insert_id();
            
            if($tugas){
                $nis = '';
                $getKelas = $this->db->get_where('kelas_siswa', ['kd_kelas'=>$kelas])->result_array();
                foreach ($getKelas as $key => $value) {
                    $nis = $getKelas[$key]['nis'];
                        $dataNilai = [
                        'id' => null,
                        'id_tugas' => $insert_id,
                        'jawaban' => '',
                        'tanggal' => '',
                        'nis' => $nis,
                        'nilai' => ''
                        ];
                        $this->db->insert('nilai', $dataNilai);
                }
            }
            if ($this->db->affected_rows() > 0)
            {
                $this->set_response([
                    'status' => TRUE,
                    'message' => 'tugas berhasil ditambahkan'], REST_Controller::HTTP_OK);
            } else {
                $this->set_response([
                    'status' => FALSE,
                    'message' => 'tugas gagal ditambahkan'], REST_Controller::HTTP_OK);
            }
    }


    public function file_post() //fungsi save soal
    {
        $kelas = $this->post('kelas');
        $judul = $this->post('judul');
        $batas = $this->post('batas');
        $soal = $this->post('soal');
            $data = [
            'id' => NULL,
            'kd_kelas' => $kelas,
            'judul' => $judul,
            'batas_waktu' => $batas,
            'tipe_soal' => 'FILE',
            'soal' => $soal
            ];
            $tugas  = $this->db->insert('tugas', $data);
            $insert_id = $this->db->insert_id();

            if($tugas){
                $nis = '';
                $getKelas = $this->db->get_where('kelas_siswa', ['kd_kelas'=>$kelas])->result_array();
                foreach ($getKelas as $key => $value) {
                    $nis = $getKelas[$key]['nis'];
                        $dataNilai = [
                        'id' => null,
                        'id_tugas' => $insert_id,
                        'jawaban' => '',
                        'tanggal' => '',
                        'nis' => $nis,
                        'nilai' => ''
                        ];
                        $this->db->insert('nilai', $dataNilai);
                }
            }
            if ($this->db->affected_rows() > 0)
            {
                $this->set_response([
                    'status' => TRUE,
                    'message' => 'tugas berhasil ditambahkan'], REST_Controller::HTTP_OK);
            } else {
                $this->set_response([
                    'status' => FALSE,
                    'message' => 'tugas gagal ditambahkan'], REST_Controller::HTTP_OK);
            }
    }


    public function essay_post() //fungsi save soal
    {
        $kelas = $this->post('kelas');
        $judul = $this->post('judul');
        $batas = $this->post('batas');
        $soal = $this->post('soal');
            $data = [
            'id' => NULL,
            'kd_kelas' => $kelas,
            'judul' => $judul,
            'batas_waktu' => $batas,
            'tipe_soal' => 'ESSAY',
            'soal' => $soal
            ];
            $tugas  = $this->db->insert('tugas', $data);
            $insert_id = $this->db->insert_id();

            if($tugas){
                $nis = '';
                $getKelas = $this->db->get_where('kelas_siswa', ['kd_kelas'=>$kelas])->result_array();
                foreach ($getKelas as $key => $value) {
                    $nis = $getKelas[$key]['nis'];
                        $dataNilai = [
                        'id' => null,
                        'id_tugas' => $insert_id,
                        'jawaban' => '',
                        'tanggal' => '',
                        'nis' => $nis,
                        'nilai' => ''
                        ];
                        $this->db->insert('nilai', $dataNilai);
                }
            }
            if ($this->db->affected_rows() > 0)
            {
                $this->set_response([
                    'status' => TRUE,
                    'message' => 'tugas berhasil ditambahkan'], REST_Controller::HTTP_OK);
            } else {
                $this->set_response([
                    'status' => FALSE,
                    'message' => 'tugas gagal ditambahkan'], REST_Controller::HTTP_OK);
            }
    }
    

    public function soal_get() //fungsi save soal
    {
        // $id = 42;
        $id = $this->get('id');
        $data = $this->db->get_where('tugas', ['id' => $id])->result_array();

        /////// khusus form siswa //////////////
        $data_soal = preg_replace('/[[:cntrl:]]/', '', $data[0]["soal"]);
        $data_soal = json_decode($data_soal, true);
        // print_r($data_soal);
        // $data_soal = json_decode($data[0]["soal"], true);
        if($data[0]["tipe_soal"]=="PG"){
            foreach ($data_soal as $key => $value) {
                unset($data_soal[$key]["correctAnswer"]);//menghapus kunci jawaban
            }
            $soal_fixed = json_encode($data_soal);
            $data[0]["soal"] = $soal_fixed;
        }
        if($data[0]["tipe_soal"]=="ESSAY"){
            foreach ($data_soal["data"] as $key => $value) {
                unset($data_soal["data"][$key][1]);//menghapus kunci jawaban
                unset($data_soal["data"][$key][2]);//menghapus bobot
            }
            $soal_fixed = json_encode($data_soal);
            $data[0]["soal"] = $soal_fixed;//mengubah isi dari array soal
        }
        // print_r($data);
        /////// khusus form siswa //////////////

            if ($this->db->affected_rows() > 0)
            {
                $this->set_response([
                    'status' => TRUE,
                    'data' => $data], REST_Controller::HTTP_OK);
            } else {
                $this->set_response([
                    'status' => FALSE,
                    'data' => $data], REST_Controller::HTTP_OK);
            }
    }

    public function soal_guru_get() //fungsi save soal
    {
        // $id = 32;
        $id = $this->get('id');
        $data = $this->db->get_where('tugas', ['id' => $id])->result_array();
        $data_soal = preg_replace('/[[:cntrl:]]/', '', $data[0]["soal"]);
        $data_soal = json_decode($data_soal, true);
        $soal_fixed = json_encode($data_soal);
        $data[0]["soal"] = $soal_fixed;//mengubah isi dari array soal

        // print_r($data);

            if ($this->db->affected_rows() > 0)
            {
                $this->set_response([
                    'status' => TRUE,
                    'data' => $data], REST_Controller::HTTP_OK);
            } else {
                $this->set_response([
                    'status' => FALSE,
                    'data' => $data], REST_Controller::HTTP_OK);
            }
    }
    
    public function jawaban_tersimpan_get() //fungsi save soal
    {
        $id_tugas = $this->get('id_tugas');
        $nis = $this->get('nis');

        $data = $this->db->get_where('nilai', ['id_tugas' => $id_tugas,'nis' => $nis]);
        // print_r($data);

            if ($data->num_rows() > 0)
            {
                $this->set_response([
                    'status' => TRUE,
                    'data' => $data->result_array(),
                    'message' => 'sukses'], REST_Controller::HTTP_OK);
            } else {
                $this->set_response([
                    'status' => FALSE,
                    'data' => $data->result_array(),
                    'message' => 'gagal'], REST_Controller::HTTP_OK);
            }
    }
    
    ////////////////////////////// fungsi daftar kelas siswa /////////////////////
    ////////////////////////////// fungsi daftar kelas siswa /////////////////////
    ////////////////////////////// fungsi daftar kelas siswa /////////////////////
    ////////////////////////////// fungsi daftar kelas siswa /////////////////////
    ////////////////////////////// fungsi daftar kelas siswa /////////////////////
    public function kelas2_get() //fungsi tampilkan daftar kelas siswa
    {
        $nis = $this->get('nis');

        $this->db->select('*');
        $this->db->from('kelas');
        $this->db->join('kelas_siswa','kelas_siswa.kd_kelas=kelas.kode','inner');
        $this->db->where('kelas_siswa.nis', $nis);
        $query=$this->db->get();
        $data= $query->result_array();
            
            if ($this->db->affected_rows() > 0)
            {
                $this->set_response([
                    'status' => TRUE,
                    'data' => $data,
                    'message' => 'daftar kelas'], REST_Controller::HTTP_OK);
            } else {
                $this->set_response([
                    'status' => FALSE,
                    'data' => $data,
                    'message' => 'kelas belum ada'], REST_Controller::HTTP_OK);
            }
    }
    ////////////////////////////// fungsi daftar nilai siswa /////////////////////
    ////////////////////////////// fungsi daftar nilai siswa /////////////////////
    ////////////////////////////// fungsi daftar nilai siswa /////////////////////
    ////////////////////////////// fungsi daftar nilai siswa /////////////////////
    ////////////////////////////// fungsi daftar nilai siswa /////////////////////
    public function daftar_nilai_get() //fungsi tampilkan daftar nilai siswa
    {
        $kode_kelas = $this->get('kode');
        $id = $this->get('id');

        $this->db->select('*');
        $this->db->from('kelas_siswa');
        $this->db->join('nilai','kelas_siswa.nis=nilai.nis','LEFT');
        $this->db->join('user','kelas_siswa.nis=user.no','LEFT');
        $this->db->join('tugas','tugas.id=nilai.id_tugas','LEFT');
        $this->db->where('kelas_siswa.kd_kelas', $kode_kelas);
        $this->db->where('nilai.id_tugas', $id);
        $query=$this->db->get();
        $data= $query->result_array();
            
            if ($this->db->affected_rows() > 0)
            {
                $this->set_response([
                    'status' => TRUE,
                    'data' => $data,
                    'message' => 'daftar kelas'], REST_Controller::HTTP_OK);
            } else {
                $this->set_response([
                    'status' => FALSE,
                    'data' => $data,
                    'message' => 'kelas belum ada'], REST_Controller::HTTP_OK);
            }
    }

    /////////////////////////////// fungsi login ///////////////////////////////
    ////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////
    public function login_post() //fungsi login
    {

        $user = $this->post('user');
        $pass = $this->post('pass');

        if ($user && $pass != NULL)
        {
            $users = $this->db->get_where('user', ['user' => $user, 'pass' => $pass])->result_array();
            
            
            
            // if ($this->db->num_rows() <= 0){
            // }

            if ($this->db->affected_rows() > 0)
            {
                $this->db->select('no,user,fullname,pass,email,level,photo,nis,tahun_ajaran,semester_aktif');
                $this->db->from('tahun_ajaran');
                $this->db->join('user','user.no=tahun_ajaran.nis','inner');
                $this->db->where('user',$user);
                $this->db->where('pass',$pass);
                $tahun_ajaran = $this->db->get()->result_array();

                if ($this->db->affected_rows() > 0)
                {
                    $this->response([
                    'status' => TRUE,
                    'tahun_ajaran' => TRUE,
                    'data' => $users,
                    'data_tahun_ajaran' => $tahun_ajaran], REST_Controller::HTTP_OK);
                }else{
                    $this->response([
                    'status' => TRUE,
                    'tahun_ajaran' => FALSE,
                    'data' => $users,
                    'data_tahun_ajaran' => $tahun_ajaran], REST_Controller::HTTP_OK);
                }
            } else {
                $this->response([
                'status' => FALSE,
                'message' => 'username atau password salah'], REST_Controller::HTTP_OK);
            }
        } else {
            $this->response([
                'status' => FALSE,
                'message' => 'username atau password tidak boleh kosong'], REST_Controller::HTTP_OK);
        }
    }

    /////////////////////////// upload foto /////////////////////////////
    /////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////
    private function _do_upload($path,$type,$name,$field) //upload foto
    {
        $config['upload_path']          = $path;
        // $config['upload_path']          = 'upload/';
        $config['allowed_types']        = $type;
        // $config['allowed_types']        = 'gif|jpg|png';
        $config['max_size']             = 2048; //set max size allowed in Kilobyte
        // $config['max_width']            = 1000; // set max width image allowed
        // $config['max_height']           = 1000; // set max height allowed
        $config['file_name']            = $name; //just milisecond timestamp fot unique name
        // $config['file_name']            = round(microtime(true) * 1000); //just milisecond timestamp fot unique name

        $this->load->library('upload', $config);

        if(!$this->upload->do_upload($field)) //upload and validate
        {
            $data['inputerror'][] = $field;
            $data['error_string'][] = 'Upload error: '.$this->upload->display_errors('',''); //show ajax error
            $data['status'] = FALSE;
            echo json_encode($data);
            exit();
        }
        return $this->upload->data('file_name');
    }




///// materi siswa ///////////
    public function materi_siswa_get()
    {
        // $nis = "11112";
        $nis = $this->get("nis");

        $this->db->select('materi.id,materi.judul,kelas.nama_kelas,materi.link');
        $this->db->from('kelas');
        $this->db->join('kelas_siswa','kelas_siswa.kd_kelas=kelas.kode','inner');
        $this->db->join('materi','kelas_siswa.kd_kelas=materi.kelas','inner');
        $this->db->where('kelas_siswa.nis', $nis);
        $query=$this->db->get();
        $data = $query->result_array();
        // print_r($query->result_array());

        if ($this->db->affected_rows() > 0)
            {
                $this->set_response([
                    'status' => TRUE,
                    'data' => $data], REST_Controller::HTTP_OK);
            } else {
                $this->set_response([
                    'status' => FALSE,
                    'data' => $data,
                    'message' => 'kelas belum ada'], REST_Controller::HTTP_OK);
            }

    }

    public function bulk_nilai_get()
    {
        $bulk = $this->get('bulk');
        $affected_rows = 0;
        // $bulk = '{"bulk_nilai":[["35","11112","100"],["35","11111",""]]}';
        $bulkArr = json_decode($bulk, true);
        // print_r($bulkArr["bulk_nilai"]);
        foreach ($bulkArr["bulk_nilai"] as $key => $value) {
            // print_r($bulkArr["bulk_nilai"][$key]);
            $id_tugas = $bulkArr["bulk_nilai"][$key][0];
            $nim = $bulkArr["bulk_nilai"][$key][1];
            $nilai = $bulkArr["bulk_nilai"][$key][2];
            // echo "id = ".$id_tugas."; nim = ".$nim."; nilai = ".$nilai."<br>";

            $data = [
            'nilai' => $nilai
            ];
            $this->db->where(['id_tugas'=>$id_tugas,'nim'=>$nim])->update('nilai', $data);
            $affected_rows = ($affected_rows+$this->db->affected_rows());
        }
        if ($affected_rows > 0)
        {
            $this->set_response([
                'status' => TRUE,
                'message' => 'tersimpan!.'], REST_Controller::HTTP_OK);
        } else {
            $this->set_response([
                'status' => FALSE,
                'message' => 'gagal disimpan!.'], REST_Controller::HTTP_OK);
        }
    }


    ///// cari kelas //////////
    public function cari_kelas_get()
    {
        $kode = $this->get("kode");
        $nis = $this->get("nis");

        $cek = $this->db->get_where('kelas_siswa', ['kd_kelas'=>$kode,'nis'=>$nis]);

        if($cek->num_rows() > 0){
            $this->set_response([
                    'status' => FALSE,
                    'message' => 'kelas sudah ada'], REST_Controller::HTTP_OK);
        }else{
            $query = $this->db->get_where('kelas', ['kode' => $kode]);

            if($query->num_rows() > 0){
                $data = [
                'id' => null,
                'kd_kelas' => $kode,
                'nis' => $nis
                ];
                $this->db->insert('kelas_siswa', $data);

                $this->set_response([
                        'status' => TRUE,
                        'message' => 'kelas berhasil ditambahkan'], REST_Controller::HTTP_OK);
            }else{
                $this->set_response([
                        'status' => FALSE,
                        'message' => 'kelas tidak ditemukan'], REST_Controller::HTTP_OK);
            }
        }
    }


















    public function jawabFILE_post() //fungsi save soal2 yaitu menyimpan jawaban siswa
    {
        $id_tugas = $this->post('id_tugas');
        $tanggal = $this->post('tanggal');
        $nis = $this->post('nis');
        $data = [];
        $cek = $this->db->get_where('nilai', ['id_tugas' => $id_tugas,'nis' => $nis])->num_rows();
            if($cek > 0){
                $data = [
                'jawaban' => '',
                'tanggal' => $tanggal,
                'nilai' => ''
                ];
                if(!empty($_FILES['file']['name']))
                {
                    $path = 'upload/tugas/';
                    $type = 'jpeg|jpg|png|doc|docx|pdf|ppt|pptx|xlx|xlxx';
                    $name = round(microtime(true) * 1000).'_'.$_FILES['file']['name'];
                    $field = 'file';
                    $upload = $this->_do_upload($path,$type,$name,$field);
                    $data['jawaban'] = $upload;
                }else{
                    $data['jawaban'] = "";
                }
                $this->db->where(['id_tugas'=>$id_tugas,'nis'=>$nis])->update('nilai', $data);
                // $error = $this->db->error();
                if ($this->db->affected_rows() > 0)
                {
                    $this->set_response([
                        'status' => TRUE,
                        'message' => 'tersimpan!.'], REST_Controller::HTTP_OK);
                } else {
                    $this->set_response([
                        'status' => FALSE,
                        'message' => 'gagal disimpan!.'
                        // ,
                        // 'error' => $error,
                        // 'id' => $id_tugas,
                        // 'nis' => $nis
                        ], REST_Controller::HTTP_OK);
                }
            }
    }
    public function jawabPG_get() //fungsi save soal2 yaitu menyimpan jawaban siswa
    {
        $id_tugas = $this->get('id_tugas');
        $jawaban = $this->get('jawaban');
        $tanggal = $this->get('tanggal');
        $nis = $this->get('nis');

            $cek = $this->db->get_where('nilai', ['id_tugas' => $id_tugas,'nis' => $nis])->num_rows();
            if($cek > 0){
                $hasil_nilai = $this->penilaianPG($jawaban,$id_tugas);
                $data = [
                'jawaban' => $jawaban,
                'tanggal' => $tanggal,
                'nilai' => $hasil_nilai
                ];
                $this->db->where(['id_tugas'=>$id_tugas,'nis'=>$nis])->update('nilai', $data);
                // $this->set_response([
                //         'status' => TRUE,
                //         'message' => 'tersimpan!.',
                //         'data' => $hasil_nilai], REST_Controller::HTTP_OK);
                if ($this->db->affected_rows() > 0)
                {
                    $this->set_response([
                        'status' => TRUE,
                        'message' => 'tersimpan!.'], REST_Controller::HTTP_OK);
                } else {
                    $this->set_response([
                        'status' => FALSE,
                        'message' => 'gagal disimpan!.'], REST_Controller::HTTP_OK);
                }
            }
    }

    public function jawabEssay_get(){
        // $jsonJawaban = '{"data": [["ir.sukarno"],["garuda"]]}';
        // $id_tugas = 34;
        // $tanggal = 1576950128;
        // $nis = 11111;
        $jsonJawaban = $this->get('jawabanEssay');
        $id_tugas = $this->get('id_tugas');
        $tanggal = $this->get('tanggal');
        $nis = $this->get('nis');

        $soal = $this->db->select('soal')->get_where('tugas', ['id'=>$id_tugas])->result_array();
        $jawabanDB = preg_replace('/[[:cntrl:]]/', '', $soal[0]["soal"]);
        $jawabanDB = json_decode($jawabanDB, true);
        // $jawabanDB = json_decode($soal[0]["soal"], true);


        // $jsonJawaban = '{"data": [["nama saya dirga","dirga",10],["halo apa kabar","apa kabar",10]]}';
        $jsonDecode = preg_replace('/[[:cntrl:]]/', '', $jsonJawaban);
        $jsonDecode = json_decode($jsonDecode, true);
        // $jsonDecode = json_decode($jsonJawaban, true);
        $jawaban = $jsonDecode["data"];
        // print_r($jawaban);
        $t_array = count($jawaban);
        $i=0;
        $total_nilai = 0;
        $total_bobot = 0;
        $fixJaw = '{"jawaban": [';

        if(is_array($jsonDecode)){
            foreach ($jawaban as $key => $value) {
                // print_r($key);
                $words = $jawabanDB["data"][$key][1];//kunci jawaban
                $wer = $jawaban[$key][0];//jawaban siswa
                $max = $jawabanDB["data"][$key][2];//bobot
                $i++;
                // echo "stat=".$i.";txt1=".$words.";txt2=".$wer.";max=".$max."; ";
                $nilai = $this->penilaianEssay($words,$wer,$max);
                // echo "nil$i = ".$nilai."<br>";
                $total_nilai = $total_nilai + $nilai;
                $total_bobot = $total_bobot + $max;
                $fixJaw .= '["'.$wer.'","'.$nilai.'"]';
                if(($t_array-1)!=$key)
                {
                    $fixJaw .= ',';
                }
            }
            $fixJaw .= ']}';
            $hasil_nilai = round(($total_nilai/$total_bobot)*100);
        }


        $fixJawaban = $fixJaw;
        $fixNilai = $hasil_nilai;
        // print_r($jsonDecode);

            $cek = $this->db->get_where('nilai', ['id_tugas' => $id_tugas,'nis' => $nis])->num_rows();
            if($cek > 0){
                $data = [
                'jawaban' => $fixJawaban,
                'tanggal' => $tanggal,
                'nilai' => $fixNilai
                ];
                $this->db->where(['id_tugas'=>$id_tugas,'nis'=>$nis])->update('nilai', $data);
                
                if ($this->db->affected_rows() > 0)
                {
                    $this->set_response([
                        'status' => TRUE,
                        'message' => 'tersimpan!.'], REST_Controller::HTTP_OK);
                } else {
                    $this->set_response([
                        'status' => FALSE,
                        'message' => 'gagal disimpan!.'], REST_Controller::HTTP_OK);
                }
            }
    }

    // public function penilaianPG($jawaban,$id_tugas){
    private function penilaianPG($jUsr,$id_tugas){
        $soal = $this->db->select('soal')->get_where('tugas', ['id'=>$id_tugas])->result_array();
        $jawabanDB = json_decode($soal[0]["soal"], true);
        $jawabanUser = json_decode($jUsr, true);
        $nilai=0;
        $index=1;
        $jumlahSoal = count($jawabanDB);
        foreach ($jawabanDB as $kunciJwb) {
            // echo ($jawabanUser['a'.$index]."<br>");
            // echo ($kunciJwb["correctAnswer"]."</br>");
            if ($jawabanUser['a'.$index] == $kunciJwb["correctAnswer"]){
                $nilai++;
            }
            $index++;
        }
        // echo $nilai;
        $hasil = (($nilai/$jumlahSoal)*100);
        return (int)$hasil;
    }
    private function penilaianEssay($words,$wer,$max){ 
    //$words -> untuk keyword jawaban dari guru; $wer -> jawaban dari siswa; $max -> bobot soal

        $j = 0;
        $key = explode(" ",$words);
        $ans = explode(" ",$wer);

        foreach($ans as $jaw){
            $n = strlen($jaw);
            $sub = substr($jaw,$n-1,$n);
            if(($sub ==  ',') or ($sub ==  '.') or ($sub ==  '!') or ($sub ==  '?')){
                $kata['ans'][] = substr($jaw,0,$n-1);
            }else{
                $kata['ans'][] = $jaw;
            }
        }

        foreach($key as $kunci){
            $n = strlen($kunci);
            $sub = substr($jaw,$n-1,$n);
            if(($sub ==  ',') or ($sub ==  '.') or ($sub ==  '!') or ($sub ==  '?')){
                $kata['key'][] = substr($kunci,0,$n-1);
            }else{
                $kata['key'][] = $kunci;
            }
        }
        foreach($kata['ans'] as $tempo){
            foreach($kata['key'] as $rary){
                if(strtolower($tempo) == strtolower($rary)){
                    $j++; //hitung kecocokkan kata kunci dan jawaban siswa
                }
            }
        }

        if($j >= count($kata['key'])){
            $j = count($kata['key']);
            $nilai = $max;
        }else{
            $nilai = $max * ($j / count($kata['key'])); //menentukan nilai akhir
        }
        return (int)$nilai;
    }

    public function report_nilai_get()
    {
        // $kd_kelas = 6523765;
        $kd_kelas = $this->get('kd_kelas');

        $getSiswa = $this->db->select('*')
                                ->from('kelas_siswa')
                                ->join('user', 'kelas_siswa.nis=user.no')
                                ->where('kd_kelas', $kd_kelas)
                                ->group_by('nis')
                                ->get()
                                ->result_array();
        // print_r($getSiswa);
        $getTugas = $this->db->select('*')->from('tugas')->where('kd_kelas', $kd_kelas)->get()->result_array();
        // print_r($getTugas);

        //judul soal
        $jsonJudul = '[';
        $jsonJudul .= '{ "data" : "NIM" },';
        $jsonJudul .= '{ "data" : "NAMA" },';
        // $arrayJudul = [];
        $countTugas = count($getTugas);
        foreach ($getTugas as $key => $value) {
            $judul = $getTugas[$key]["judul"];
            $jsonJudul .= '{ "data" : "'.$judul.'" }';
            if ($key != ($countTugas-1)){
                $jsonJudul .= ',';
            }
            // $arrayJudul[] = $judul;
        }
        $jsonJudul .= ']';
        $arrayJudul = json_decode($jsonJudul, true);
        // print_r($arrayJudul);

        //foreach siswa
        $hasil = '[';
        $countSiswa = count($getSiswa);
        foreach ($getSiswa as $keys => $value) {
            // echo " <br><br> nis = ";
            $nis = $getSiswa[$keys]["nis"];
            $nama = $getSiswa[$keys]["fullname"];
            $hasil .= '{"NIS": "'.$nis.'",';
            $hasil .= '"NAMA": "'.$nama.'",';
            //foreach tugas
            foreach ($getTugas as $key => $value) {
                // echo " <br> id = ";
                $id_tugas = $getTugas[$key]["id"];
                // echo " <br> judul = ";
                $judul = $getTugas[$key]["judul"];
                    $getNilai = $this->db->get_where('nilai', ['id_tugas'=>$id_tugas,'nis'=>$nis])->result_array();
                    if(!empty($getNilai)){
                        if($getNilai[0]["nilai"] == ''){
                            $nilai = "0";
                            // echo "<br> nilai = ";
                            // echo "0";
                        }else{
                            $nilai = $getNilai[0]["nilai"];
                            // echo "<br> nilai = ";
                            // echo $getNilai[0]["nilai"];
                        }
                    }else{
                        $nilai = "-";
                        // echo "<br> nilai = ";
                        // echo "-";
                    }
                    // echo "<br> nilai = ".$nilai;
                $hasil .= '"'.$judul.'": "'.$nilai.'"';
                if ($key != ($countTugas-1)){
                    $hasil .= ',';
                }
            }
            $hasil .= '}';
            if ($keys != ($countSiswa-1)){
                $hasil .= ',';
            }
        }
        $hasil .= ']';
        $arrHasil = json_decode($hasil, true);
        $this->set_response([
                        'status' => TRUE,
                        'data' => $arrHasil,
                        'columns' => $arrayJudul], REST_Controller::HTTP_OK);
    }

}
