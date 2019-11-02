<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Front extends Frontend_Controller {

	public function __construct(){
		parent::__construct();		
		$this->load->model(array('Pesan_model', 'Subscribe_email_model'));
		$this->load->library(array('form_validation','user_agent'));	
	}

	public function index(){
		$data = array();
		$this->site->_isHome = TRUE;
		$this->site->view('index', $data);
	}	

	public function about(){
		$this->site->view('about');	
	}	

	public function caraguna(){
		$this->site->view('caraguna');	
	}	
	public function courses(){
		$this->site->view('courses');	
	}	
	public function kontak($param =null){
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
			$serv = $_SERVER;
			if($param == 'kirim'){
				$rules = $this->Pesan_model->rules;
				$this->form_validation->set_rules($rules);
				if($this->form_validation->run() == TRUE){
					$post = $this->input->post();
					$data = array(
						'pesan_nama' => $post['pesan_nama'],
						'pesan_email' => $post['pesan_email'],
						'pesan_pesan' => $post['pesan_pesan'],
						'pesan_ip' 	=> $serv['REMOTE_ADDR'],
						'pesan_agent' => $this->agent->agent_string(),
						'pesan_created' => date('Y-m-d H:i:s')
					);

					$this->Pesan_model->insert($data);

					echo json_encode(array('status' => 'success'));

				}
				else{
					$result = array('status' => 'failed', 'errors' => $this->form_validation->error_array());
					echo json_encode($result);
				}

			}
			else if($param == 'subscribe'){

				$post = $this->input->post();
				$data = array(
					'subs_email_email'   => $post['subs_email_email'],
					'subs_email_ip'      => $serv['REMOTE_ADDR'],
					'subs_email_agent'   => $this->agent->agent_string(),
					'subs_email_created' => date('Y-m-d H:i:s')
				);

				$this->Subscribe_email_model->insert($data);

				echo json_encode(array('status' => 'success'));
			}
		}
		else{
			$this->site->view('kontak');	
		}

		
	}	

	public function register(){
		$this->site->view('register');	
	}	


	public function get_halaman(){
		$record = $this->Halaman_model->get_by(array('post_type' => 'halaman'));
		echo json_encode(array('record' => $record));
	}
	public function komentar(){
		$serv = $_SERVER;
		$status = 'publish';
		$note = 'sudah ditampilkan';		
		$website_setting = $this->site->website_setting;

		if(array_key_exists('setting_komentar', $website_setting)){
			$this->load->model(array('Komentar_model'));
			$this->load->library(array('form_validation','user_agent'));						
			$rules = $this->Komentar_model->rules;
			$this->form_validation->set_rules($rules);
			if ($this->form_validation->run() == TRUE) {
				$post = $this->input->post(NULL,TRUE);	

				/* moderasi komentar status */				
				if(array_key_exists('moderasi_komentar', $website_setting)){
					$status = 'pending';	
					$note = 'segera dimoderasi';							
				}	

				$data = array(
					'comment_post_ID' 		=> $post['post_ID'], 
					'comment_author_name' 	=> $post['comment_author_name'], 
					'comment_author_email' 	=> $post['comment_author_email'], 
					'comment_author_url' 	=> $post['comment_author_url'], 
					'comment_author_IP' 	=> $serv['REMOTE_ADDR'],
					'comment_date' 			=> date('Y-m-d H:i:s'), 
					'comment_content' 		=> $post['comment_content'], 
					'comment_approved' 		=> $status, 
					'comment_agent' 		=> $this->agent->agent_string(), 	
				);

				if(!empty($post['post_ID'])){
					$insert_id = $this->Komentar_model->insert($data);
					if($insert_id){
						/* kirim email komentar baru */
						$this->load->library('email');
						$email_config['mailtype'] = 'html';
						$this->email->initialize($config);
						$this->email->from($website_setting['email'].'', $website_setting['judul']);
						$this->email->to($website_setting['email']);
						$this->email->subject('Ada Komentar Baru (#'.$insert_id.') di '.$website_setting['domain']);
						$this->email->message('Ada komentar Baru dari ... <br />' .
								'Nama : '. $post['comment_author_name'] .'<br />'.
								'Email : ' . $post['comment_author_email'] . '<br />'.
								'IP ' . $serv['REMOTE_ADDR'] . '<br />'.
								'URL : ' . $post['comment_author_url'] . '<br />'.
								'Isi komentar : <br />' . $post['comment_content']
							);

						$this->email->send();
					}
				}

				$notif = array('status' => 'success', 'message' => 'Terima kasih! Komentar Anda '.$note);
				$this->session->set_flashdata($notif);

			}
			else{
				$notif = array_merge(array('status' => 'error'), $this->form_validation->error_array()) ;	
				$this->session->set_flashdata($notif);
				$statusnya = $this->session->flashdata();
				
			}
		}
		redirect($serv['HTTP_REFERER'].'#form-komentar');
	}


	public function email(){
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
		$this->email->to('rankom202@gmail.com');
		$this->email->subject('Aktivasi Akun');
		$this->email->message('Klik Link berikut untuk Aktivasi Akun anda <a href="'.base_url().'aktivasi/'.$post['username'].'">Klik disini</a>
	                <br><br>-------------<br>
	        <b>Sekertariat Konsil Tenaga Kesehatan Indonesia <br>Gedung Badan PPSDM Kesehatan Lantai 8</b><br>
	        JL. HANG JEBAT III BLOK F3, <br> KEBAYORAN BARU JAKARTA SELATAN 12120');
		$this->email->send();
    }

    public function email2(){

						$this->load->library('email');
						$email_config['mailtype'] = 'html';
						$this->email->initialize($config);
						$this->email->from('randytrikarya2@gmail.com');
						$this->email->to('rankom202@gmail.com');
						$this->email->subject('Ada Komentar Baru');
						$this->email->message('Ada komentar Baru dari ... <br />');

						$this->email->send();
    }

    
}
