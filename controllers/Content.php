<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Content extends Frontend_Controller {

	public function __construct(){
		parent::__construct();		
	}

	public function index(){
		$this->site->view('content');	
	}	


  public function organisasi(){
    if($this->Organisasi_model->count(array('organisasi_slug'=>$this->uri->segment(2)))){
      $data['organisasi'] = $this->Organisasi_model->get_by(array('organisasi_slug'=>$this->uri->segment(2)));
      $this->site->view('organisasi', $data);
    }
    else{

      $this->site->view('error404');
    }
  
  } 

  public function profesi(){
    if($this->Profesi_model->count(array('profesi_slug'=>$this->uri->segment(2))))  {
      $data['profesi'] = $this->Profesi_model->get_by(array('profesi_slug'=> $this->uri->segment(2)));
      $this->site->view('profesi', $data); 
    }
    else{

      $this->site->view('error404');
    }
  } 

  public function related(){
    $this->site->view('related'); 
  } 


	public function email(){
        $config = [
               'mailtype'  => 'html',
               'charset'   => 'utf-8',
               'protocol'  => 'smtp',
               'smtp_host' => 'ssl://smtp.gmail.com',
               'smtp_user' => 'randytrikarya@gmail.com',    // Ganti dengan email gmail kamu
               'smtp_pass' => 'trikaryacemerlang',      // Password gmail kamu
               'smtp_port' => 465,
               'crlf'      => "\r\n",
               'newline'   => "\r\n"
           ];

        // Load library email dan konfigurasinya
        $this->load->library('email', $config);

        // Email dan nama pengirim
        $this->email->from('no-reply@masrud.com', 'MasRud.com | M. Rudianto');

        // Email penerima
        $this->email->to('rankom202@gmail.com'); // Ganti dengan email tujuan kamu

        // Lampiran email, isi dengan url/path file
        $this->email->attach('https://masrud.com/content/images/20181215150137-codeigniter-smtp-gmail.png');

        // Subject email
        $this->email->subject('Kirim Email dengan SMTP Gmail | MasRud.com');

        // Isi email
        $this->email->message("Ini adalah contoh email CodeIgniter yang dikirim menggunakan SMTP email Google (Gmail).<br><br> Klik <strong><a href='https://masrud.com/post/kirim-email-dengan-smtp-gmail' target='_blank' rel='noopener'>disini</a></strong> untuk melihat tutorialnya.");

        // Tampilkan pesan sukses atau error
        if ($this->email->send()) {
            echo 'Sukses! email berhasil dikirim.';
        } else {
            echo 'Error! email tidak dapat dikirim.';
        }
  }
}
