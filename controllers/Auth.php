<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends Frontend_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model(array('Profesi_model', 'Organisasi_model', 'User_model'));

	}

	public function index(){
		if(isset($_SESSION['ID'])){
			redirect(set_url('dashboard'));
		}
		$this->site->view('login');	
	}

	public function login(){
		if(isset($_SESSION['ID'])){
			redirect(set_url('dashboard'));
		}
		
		/* tahap 3 finishing */
		$post = $this->input->post(NULL, TRUE);
		
		if(isset($post['username']) ){ 
			$this->user_detail = $this->User_model->get_user_login(NULL, 1, NULL, TRUE, NULL, $post['username'], $post['username']);
		}
		if($this->user_detail->active == 0){
			$this->session->set_flashdata('aktivasi', 'Silahkan Cek Email anda untuk aktivasi ');
			redirect($serv['HTTP_REFERER'].'/login');
			$this->site->view('login');
		}
		else{
			$this->form_validation->set_message('required', '%s kosong, tolong diisi!');

			$rules = $this->User_model->rules;
			$this->form_validation->set_rules($rules);	

			if ($this->form_validation->run() == FALSE){	

				$this->session->set_flashdata('error', 'Username atau Password anda Salah');
				redirect($serv['HTTP_REFERER'].'/login');
				$this->site->view('login');
				// echo $this->db->last_query();
	        }
	        else{
				$login_data = array(
						'ID' => $this->user_detail->ID,
				        'username'  => $post['username'],	
				        'nama'  => $this->user_detail->nama,			        		        
				        'logged_in' => TRUE,
				        'active' => $this->user_detail->active,
				        'created_on' => $this->user_detail->created_on,
				        'last_login' => $this->user_detail->last_login,
				        'group' => $this->user_detail->group,
				        'email' => $this->user_detail->email,
				        'user_type' => $this->user_detail->user_type,
				        'user_organisasi' => $this->user_detail->user_organisasi
				);						

				$this->session->set_userdata($login_data);

				if(isset($post['remember']) ){
					$expire = time() + (86400 * 7);
					set_cookie('username', $post['username'], $expire , "/");
					set_cookie('password', $post['password'], $expire , "/" );
				}
				if($this->user_detail->group == 'murid'){

					redirect(set_url('dashboard'));
				}
				else{
					redirect(set_url('admin/dashboard'));
				}
	        }
		}
		
    }

	public function password_check($str){
    	$user_detail =  $this->user_detail;  	
    	if (@$user_detail->password == md5($str)){
			return TRUE;
		}
		else if(@$user_detail->password){
			$this->form_validation->set_message('password_check', 'Passwordnya Anda salah...');
			return FALSE;
		}
		else{
			$this->form_validation->set_message('password_check', 'Anda tidak punya akses Admin...');
			return FALSE;	
		}		
	}	

	public function register($action = ''){
		if(isset($_SESSION['ID'])){
			redirect(set_url('dashboard'));
		}

		if($action == 'auth'){

			$post = $this->input->post();
			if($post['user_type'] == 'SERTIFIKAT'){
				$rules = $this->User_model->rules_register_sertifikat;
			}
			else{
				$rules = $this->User_model->rules_register_belajar;
			}
			
			$this->form_validation->set_rules($rules);

	   		$recaptcha = $this->input->post('g-recaptcha-response');
	    	$response = $this->recaptcha->verifyResponse($recaptcha);
			if ($this->form_validation->run() == TRUE AND isset($response['success']) AND $response['success'] == true) {
				$group = 'user';
	            $path_photo = base_url().'uploads/user/user.jpg';
				$data = array(
						'username' => $post['username'],
						'nama' 	=> $post['nama'],
						'password' => md5($post['password']),							
						// 'password' => bCrypt($post['password'],12),							
						'group' => 'murid',
						'email' => $post['email'],	
						'created_on' => date('Y-m-d h:i:s'),						
						'active' => 1,
						'path_photo' => $path_photo,
						'user_type' => $post['user_type'],
						'user_nik' => $post['user_nik'],
						'user_str' => $post['user_str'],
						'user_organisasi' => $post['user_organisasi']
					);


				if($post['user_type'] == 'SERTIFIKAT'){

					unset($data['active']);

					$getID = $this->User_model->insert($data);

					$this->session->set_flashdata('success', 'Mohon tunggu, Akun anda akan di verifikasi admin terlebih dahulu');
				}
				else{

					/* KIRIM EMAIL */
					$this->load->library('email');

					$this->email->initialize(array(
					  'protocol' => 'smtp',
					  'smtp_host' => 'smtp.sendgrid.net',
					  'smtp_user' => 'babastudio2019',
					  'smtp_pass' => '123qweasd',
					  'smtp_port' => 587,
					  'crlf' => "\r\n",
					  'newline' => "\r\n"
					));

					$this->email->from('no-reply@e-sehat.org', 'Sekretariat KTKI');
			        $this->email->set_mailtype("html");
					$this->email->to($post['email']);
					$this->email->subject('Aktivasi Akun');
					$this->email->message('Klik Link berikut untuk Aktivasi Akun anda <a href="'.base_url().'aktivasi/'.$post['username'].'">Klik disini</a>
				                <br><br>-------------<br>
				        <b>Sekertariat Konsil Tenaga Kesehatan Indonesia <br>Gedung Badan PPSDM Kesehatan Lantai 8</b><br>
				        JL. HANG JEBAT III BLOK F3, <br> KEBAYORAN BARU JAKARTA SELATAN 12120');
					$this->email->send();


					unset($data['active']);

					$getID = $this->User_model->insert($data);

					$this->session->set_flashdata('success', 'Registrasi berhasil, silahkan cek email anda untuk aktivasi');
				}
				redirect(set_url('login'));
			}
			else{
				$result = array('status' => 'failed', 'errors' => $this->form_validation->error_array());
			    $data = array(
			        'captcha' => $this->recaptcha->getWidget(), // menampilkan recaptcha
			        'script_captcha' => $this->recaptcha->getScriptTag(), // javascript recaptcha ditaruh di head
			    );
				$this->site->view('register', $data);
			}
	

		}
		else{
			$data['post'] = $_POST;
		    $data = array(
		        'captcha' => $this->recaptcha->getWidget(), // menampilkan recaptcha
		        'script_captcha' => $this->recaptcha->getScriptTag(), // javascript recaptcha ditaruh di head
		    );
			$this->site->view('register', $data);	
		}

	}	

	public function email_check($str){
		/* bisa digunakan untuk mengecek ke dalam database nantinya */
		if ($this->User_model->count(array('email' => $str)) > 0){
            $this->form_validation->set_message('email_check', 'Email sudah digunakan, mohon diganti yang lain...');
            return FALSE;
        }
        else{
            return TRUE;
        }
	}	


	public function username_check($str){
		/* bisa digunakan untuk mengecek ke dalam database nantinya */
		if ($this->User_model->count(array('username' => $str)) > 0){
            $this->form_validation->set_message('username_check', 'Username sudah digunakan, mohon diganti yang lain...');
            return FALSE;
        }
        else{
            return TRUE;
        }
	}	

	public function logout(){
		$this->session->sess_destroy();
		redirect(set_url('/'));
	}


	public function reset_password(){
		$jumlah = $this->Forget_model->count(array('forget_key' => $this->uri->segment('2'), 'forget_expire >'=> date('Y-m-d H:i:s'), 'forget_used' => 'belum'));

		if($jumlah >= 1){
			$data['data'] = $this->Forget_model->get_forget_password(array('forget_key'=>$this->uri->segment('2'), 'forget_expire >'=> date('Y-m-d H:i:s'), 'forget_used' => 'belum'));

			$this->site->view('reset_password', $data);
		}
		else{
			$this->site->view('reset_password');
		}
		// 
	}

	public function aktivasi(){
		$jumlah = $this->User_model->count(array('username' => $this->uri->segment('2')));

		if($jumlah >= 1){
			$data = array(
				'active' => 1);
			$this->User_model->update($data, array('username' => $this->uri->segment('2')));

			$this->session->set_flashdata('success', 'Selamat Akun anda sudah Aktive, Silahkan login untuk melanjutkan');
			redirect(set_url('/login'));
		}
		// 
	}

	public function edit_password(){
		$post = $this->input->post(NULL, TRUE);
		$rules = $this->User_model->reset_rules;
		$this->form_validation->set_rules($rules);

		if($this->form_validation->run() == TRUE){
			$data = array(
				'password' => md5($post['password'])
			);
			// $data = array(
			// 	'password' => bCrypt($post['password'],12)
			// );
			$this->User_model->update($data, array('ID'=> $post['ID']));

			/* RUBAH STATUS USED DI TABLE FORGET PASSWORD */
			$this->Forget_model->update(array('forget_used'=>'sudah'), array('forget_id' => $post['forget_id']));
			$this->session->set_flashdata('success', 'Password berhasil Terupdate');
			redirect(set_url('login'));
		}
		else{
			$this->session->set_flashdata('error', 'Password Gagal Terupdate');
			redirect(set_url('login'));
		}
		
	}

	public function cek_email(){
		global $SConfig;
		$post = $this->input->post(NULL, TRUE);
		if ($this->User_model->count(array('email' => $post['email'])) > 0){
        	echo json_encode(array('data'=>'success'));				
        }
        else{
        	echo json_encode(array('data'=>'failed'));
        }
	}

	public function kirim_email(){
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){

			global $SConfig;
			$post = $this->input->post(NULL, TRUE);

			/* input ke table forget_password */
			$data = array(
				'forget_key' 	=> md5($post['email']),
				'forget_email'	=> $post['email'],
				'forget_user'	=> 1,
				'forget_used'	=> 'belum',
				'forget_created'=> date('Y-m-d H:i:s'),
				'forget_expire'	=> date('Y-m-d H:i:s', strtotime('+1 days', strtotime(date('Y-m-d H:i:s'))))
			);

			$this->Forget_model->insert($data);


			$this->load->library('email');

			$this->email->initialize(array(
			  'protocol' => 'smtp',
			  'smtp_host' => 'smtp.sendgrid.net',
			  'smtp_user' => 'babastudio2019',
			  'smtp_pass' => '123qweasd',
			  'smtp_port' => 587,
			  'crlf' => "\r\n",
			  'newline' => "\r\n"
			));

			$this->email->from('no-reply@e-sehat.org', 'Sekretariat KTKI');
	        $this->email->set_mailtype("html");
			$this->email->to($post['email']);
	        $this->email->subject('Forget Password');
	        $this->email->message('Klik Link berikut untuk merubah passsword anda <a href="'.base_url().'reset-password/'.md5($post['email']).'">Klik disini</a>
	                <br><br>-------------<br>
	        <b>Sekertariat Konsil Tenaga Kesehatan Indonesia <br>Gedung Badan PPSDM Kesehatan Lantai 8</b><br>
	        JL. HANG JEBAT III BLOK F3, <br> KEBAYORAN BARU JAKARTA SELATAN 12120');
	        
	        $this->email->send();
	    }
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
