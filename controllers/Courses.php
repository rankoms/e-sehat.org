<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Courses extends Frontend_Controller {

	public function __construct(){
		parent::__construct();	
	}

	public function index(){
		$data = array();
		$this->site->view('courses', $data);
	}	

	public function video_kursus(){
		if(get_user_info('ID') == ''){
			redirect('dashboard');
		}	
		if($this->Modul_model->count(array('modul_id'=>$this->uri->segment(3), 'modul_slug'=>$this->uri->segment(4)))){
			$data['record'] = $this->Materi_model->get_by(array('materi_modul'=>$this->uri->segment(3), 'materi_type' => 'video'));
			$data['jumlah_record'] = $this->Materi_model->count(array('materi_modul'=>$this->uri->segment(3), 'materi_type' => 'video'));
			$data['jumlah_log'] = $this->Log_materi_model->count(array('log_materi_modul' => $this->uri->segment(3), 'log_materi_iduser'=> get_user_info('ID') ));
			$data['log_modul'] = $this->Modul_model->get($this->uri->segment(3));

			// echo $data['jumlah_log'];
			// MENCARI LOG UJIAN 
			/*
			 LOGIC
			 1. MENCARI JUMLAH MATERI 1 VIDEO
			 2. MENCARI JUMLAH LATIHAN 
			 3. MENGHITUNG JUMLAH LOG MATERI
			 4. MENGHITUNG LOG LATIHAN
			 5. HITUNG LOG LATIHAN == JUMLAH MATERI && MENGHITUNG LOG LATIHAN == JUMLAH LATIHAN
			*/
			$data['jumlah_log_latihan'] = $this->Log_latihan_model->count(array('log_lat_modulid'=> $this->uri->segment(3), 'log_lat_iduser' => get_user_info('ID'), 'log_lat_type' => 'latihan'));
			$data['id_latihan'] = $this->Soal_model->get_latihan(array('soal_modulid' => $this->uri->segment(3), 'soal_type' => 'latihan'));
			$data['id_ujian'] = $this->Soal_model->get_latihan(array('soal_modulid'=> $this->uri->segment(3), 'soal_type' => 'ujian'));

			//  echo $data['jumlah_record'].'<br>'; //jumlah materi
			//  echo count($data['id_latihan']).'jumlah latihan <br>';
			//  echo $data['jumlah_log'].'jumlah log materi <br>';
			// echo $data['jumlah_log_latihan']; //jumlah log latihan
			// echo $this->db->last_query();

			// print_r($data['id_latihan']);
			$this->site->view('video_kursus', $data);	
		}
		else{
			$this->Modul_model->count(array('modul_id'=>$this->uri->segment(3), 'modul_slug'=>$this->uri->segment(4)));
			// echo $this->db->last_query();
			$this->site->view('error404');
		}
	}	

	public function latihan($param=NULL){
		if(get_user_info('ID') == ''){
			redirect('dashboard');
		}	

		if($param == 'cekhasil'){

			$pilihan = $_POST["pilihan"];
			$id_soal = $_POST["id"];
			$jumlah = $_POST["jumlah"];
	 
			$score = 0;
			$benar = 0;
			$salah = 0;
			$kosong = 0;
			for($i=0;$i<$jumlah;$i++){
				$nomor = $id_soal[$i]; // id nomor soal
	 
				if(empty($pilihan[$nomor])){
					$kosong++;
				} else {
					$jawaban = $pilihan[$nomor];
	 				
	 				$cek = $this->Soal_model->count(array('soal_id'=> $nomor, 'soal_jawaban'=> $jawaban));

					if($cek){
						$benar++;
					} else {
						$salah++;
					}
				}
			}

			$data = array(
				'log_lat_benar' => $benar,
				'log_lat_jml_soal' => $jumlah,
				'log_lat_modulid' => $_POST['modul_id'],
				'log_lat_materiid' => $_POST['materi_id'],
				'log_lat_kursusid' => $_POST['kursus_id'],
				'log_lat_iduser' => get_user_info('ID'),
				'log_lat_type'=> 'latihan',
				'log_lat_created' => date('Y-m-d H:i:s')
			);
			$this->Log_latihan_model->insert($data);
			echo json_encode(array('benar'=> $benar, 'salah'=> $salah));
		}
		else{
			$data['record'] = $this->Materi_model->get_by(array('materi_modul'=>$this->uri->segment(3), 'materi_type' => 'video'));
			$data['jumlah_record'] = $this->Materi_model->count(array('materi_modul'=>$this->uri->segment(3), 'materi_type' => 'video'));
			$data['jumlah_log'] = $this->Log_materi_model->count(array('log_materi_modul' => $this->uri->segment(3), 'log_materi_iduser'=> get_user_info('ID') ));
			$data['log_modul'] = $this->Modul_model->get($this->uri->segment(3));


			$data['jumlah_log_latihan'] = $this->Log_latihan_model->count(array('log_lat_modulid'=> $this->uri->segment(3), 'log_lat_iduser' => get_user_info('ID'), 'log_lat_type' => 'latihan'));
			$data['id_latihan'] = $this->Soal_model->get_latihan(array('soal_modulid' => $this->uri->segment(3), 'soal_type' => 'latihan'));
			$data['id_ujian'] = $this->Soal_model->get_latihan(array('soal_modulid'=> $this->uri->segment(3), 'soal_type' => 'ujian'));
			$data['record_soal'] = $this->Soal_model->get_by(array('soal_materiid' => $this->uri->segment(5), 'soal_type' => 'latihan'));
			$data['jumlah_soal'] = count($this->Soal_model->get_by(array('soal_materiid' => $this->uri->segment(5), 'soal_type' => 'latihan')));
			$data['log_latihan'] = $this->Log_latihan_model->get_by(array('log_lat_materiid' => $this->uri->segment(5), 'log_lat_iduser' => get_user_info('ID'), 'log_lat_type' => 'latihan'));
			$data['count_log_latihan'] = count($data['log_latihan']);

			$this->site->view('latihan_kursus', $data);	
		}
	}

	public function ujian($param=NULL){
		if(get_user_info('ID') == ''){
			redirect('dashboard');
		}	
		if(get_user_info('group') == 'murid' && get_user_info('user_type') == 'BELAJAR'){
			redirect('dashboard');
		}

		if($param == 'cekhasil'){

			$pilihan = $_POST["pilihan"];
			$id_soal = $_POST["id"];
			$jumlah = $_POST["jumlah"];
	 
			$score = 0;
			$benar = 0;
			$salah = 0;
			$kosong = 0;
			for($i=0;$i<$jumlah;$i++){
				$nomor = $id_soal[$i]; // id nomor soal
	 
				if(empty($pilihan[$nomor])){
					$kosong++;
				} else {
					$jawaban = $pilihan[$nomor];
	 				
	 				$cek = $this->Soal_model->count(array('soal_id'=> $nomor, 'soal_jawaban'=> $jawaban));

					if($cek){
						$benar++;
					} else {
						$salah++;
					}
				}
			}

			$nilai = ceil($benar*100/$jumlah);

			$data = array(
				'log_lat_modulid' => $_POST['modul_id'],
				'log_lat_materiid' => $_POST['materi_id'],
				'log_lat_kursusid' => $_POST['kursus_id'],
				'log_lat_ujianke' => $_POST['log_lat_ujianke'] +1,
				'log_lat_benar' => $benar,
				'log_lat_jml_soal' => $jumlah,
				'log_lat_nilai' => $nilai,
				'log_lat_iduser' => get_user_info('ID'),
				'log_lat_type'=> 'ujian',
				'log_lat_created' => date('Y-m-d H:i:s')
			);
			$this->Log_latihan_model->insert($data);
			echo json_encode(array('benar'=> $benar, 'salah'=> $salah));
		}
		else{
			// $data['record'] = $this->Materi_model->get_by(array('materi_modul'=>$this->uri->segment(3), 'materi_type' => 'video'));
			// $data['jumlah_record'] = $this->Materi_model->count(array('materi_modul'=>$this->uri->segment(3), 'materi_type' => 'video'));
			// $data['jumlah_log'] = $this->Log_materi_model->count(array('log_materi_modul' => $this->uri->segment(3), 'log_materi_iduser'=> get_user_info('ID') ));


			// $data['id_latihan'] = $this->Soal_model->get_latihan(array('soal_modulid' => $this->uri->segment(3), 'soal_type' => 'latihan'));
			// $data['id_ujian'] = $this->Soal_model->get_latihan(array('soal_modulid'=> $this->uri->segment(3), 'soal_type' => 'ujian'));
			// $data['record_soal'] = $this->Soal_model->get_by(array('soal_materiid' => $this->uri->segment(5), 'soal_type' => 'latihan'));
			// $data['record_ujian'] = $this->Soal_model->get_by(array('soal_modulid' => $this->uri->segment(3), 'soal_type' => 'ujian'));
			// $data['jumlah_soal_ujian'] = count($data['record_ujian']);
			// $data['jumlah_soal'] = count($this->Soal_model->get_by(array('soal_materiid' => $this->uri->segment(5), 'soal_type' => 'latihan')));
			// $data['log_latihan'] = $this->Log_latihan_model->get_by(array('log_lat_materiid' => $this->uri->segment(5), 'log_lat_iduser' => get_user_info('ID'), 'log_lat_type' => 'latihan'));
			// $data['count_log_latihan'] = count($data['log_latihan']);

			$data['record_ujian'] = $this->Soal_model->get_by(array('soal_kursusid' => $this->uri->segment(3), 'soal_type' => 'ujian'));
			// var_dump($data['record_ujian']);
			$data['jumlah_soal_ujian'] = count($data['record_ujian']);
			$data['log_ujian'] = $this->Log_latihan_model->get_by(array('log_lat_materiid' => $this->uri->segment(3), 'log_lat_iduser' => get_user_info('ID'), 'log_lat_type' => 'ujian'));
			$data['count_log_ujian'] = count($data['log_ujian']);
			$data['log_modul'] = $this->Modul_model->get($this->uri->segment(3));

			$this->site->view('ujian_kursus', $data);	
		}
	}
	public function learn(){
		if(get_user_info('ID') == ''){
			redirect('dashboard');
		}	
		$data = array();
		/*
			LOGIK MEMBUKA MODUL DIBAWAH NYA
			 1. MENCARI JUMLAH MATERI DALAM 1 MODUL
			 2. MENGHITUNG LOG MATERI DALAM 1 MODUL
			 3. MENGHITUNG JUMLAH LATIHAN DALAM 1 MODUL
			 4. MENGHITUNG JUMLAH LOG LATIHAN DALAM 1 MODUL
			 5. MENGHITUNG JUMLAH UJIAN DALAM 1 MODUL
			 6. MENGHITUNG JUMLAH LOG LATIHAN

			 7. JUMLAH MATERI == JUMLAH LOG MATERI && JUMLAH LATIHAN == JUMLAH LOG LATIHAN && JUMLAH UJIAN == JUMLAH LOG UJIAN
		*/
		$data['count_ujian'] = $this->Soal_model->count(array('soal_kursusid' => $this->uri->segment(3), 'soal_type' => 'ujian'));

		/* UNTUK MEMBUKA LOG UJIAN LOGIC
		1. HITUNG SEMUA VIDEO DARI KURSUS TSB
		2. HITUNG LOG VIDEO KURSUS TSB
		3. KALAU JUMLAH VIDEO DAN JUMLAH LOG VIDEO SUDAH SAMA BARU BISA KERJAIN UJIAN
		
		*/
		$data['count_video'] = $this->Materi_model->count(array('materi_kursus' => $this->uri->segment(3), 'materi_type' => 'video'));
		$data['log_video'] = $this->Log_materi_model->count(array('log_materi_kursus'=>$this->uri->segment(3),'log_materi_iduser' => get_user_info('ID')));
		$this->site->view('video_learn', $data);	

		// print_r(get_modul($this->uri->segment(3)));
	}	

	public function detail(){
	    if($this->Kursus_model->count(array('kursus_id'=>$this->uri->segment(3), 'kursus_slug'=>$this->uri->segment(4)))){
	    	// $record = 
			$data['record'] = $this->Kursus_model->get_by(array('kursus_id'=>$this->uri->segment(3), 'kursus_slug'=>$this->uri->segment(4)));
	      	$this->site->view('detail_video', $data);
	    }
	    else{
	    	// echo $this->db->last_query();
	      	$this->site->view('error404');
	    }
	}	
	public function ambil_kursus(){
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
			$post = $this->input->post();
			if(get_user_info('ID')){

				if($this->Log_kursus_model->count(array('dk_kursusid'=>$post['id'], 'dk_userid'=> get_user_info('ID')))){
					// $this->load->l
					echo json_encode(array('status'=>'exist'));
				}
				else{
					$this->load->library('user_agent');
					$agent = $this->agent->agent_string();
					$ip = $_SERVER['REMOTE_ADDR'];
					$data = array(
						'dk_userid' => get_user_info('ID'),
						'dk_kursusid' => $post['id'],
						'dk_created' => date('Y-m-d H:i:s'),
						'dk_browser' => $this->agent->browser().' '.$this->agent->version(),
						'dk_agent' => $agent,
						'dk_ip' => $ip
					);
					$this->Log_kursus_model->insert($data);
					echo json_encode(array('status' => 'success', 'action' => 'tambah'));
				}
			}
			else{
				// redirect('/login', 'refresh');
				echo json_encode(array('status'=>'failed'));
			}

		}
	}

	public function get_kuis($param, $materiid){
		global $SConfig;
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
			if($param == 'ambil'){
				$data  = $this->Soal_model->get_kuis_not_done(array('soal_materiid'=> $materiid, 'soal_type' => 'kuis', 'log_soal_iduser' => get_user_info('ID')));
				if(count($data) == ''){
					$data = $this->Soal_model->get_kuis_not_done(array('soal_materiid'=> $materiid, 'soal_type' => 'kuis'));
				}
				else{
					$data = '';
				}
				echo json_encode(array('data'=>$data));
			}
			else if($param == 'tambah'){
				$post = $this->input->post(NULL,TRUE);
				$ip = $_SERVER['REMOTE_ADDR'];
				// $ip = '112.215.36.142';
				//$ip = '127.0.0.1';
				$date = date('Y-m-d H:i:s');
				$agent = $this->agent->agent_string();
				(!empty($_SERVER['HTTP_REFERER'])) ? $reff = $_SERVER['HTTP_REFERER'] : $reff = '';
				
				@$var = file_get_contents("http://ip-api.com/json/$ip");
				$var = json_decode($var);
				$data = array(
					'log_soal_soalid' => $post['log_soal_soalid'],
					'log_soal_iduser' => get_user_info('ID'),
					'log_soal_status' => $post['log_soal_status'],
					'log_soal_kursus' => $post['log_soal_kursus'],
					'log_soal_created' => date('Y-m-d H:i:s'),
					'log_soal_browser' => $this->agent->browser().' '.$this->agent->version(),
					'log_soal_agent' => $agent,
					'log_soal_ip' => $ip
				);
				$this->Log_soal_model->insert($data);
				echo json_encode(array('query' => $this->db->last_query()));
			}

		}
	}

	public function log_materi($param){
		global $SConfig;
        if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
            if($param == 'tambah'){
                $post = $this->input->post(NULL,TRUE);
                $jumlah = $this->Log_materi_model->count(array('log_materi_materiid' => $post['materi_id'], 'log_materi_iduser'=> get_user_info('ID')));
                /* UNTUK INPUT KE TABLE LOG MODUL 
					KALAU VIDEO DALAM 1 MODUL SUDAH DI TONTON SEMUA, MAKA TABLE LOG MODUL AKAN BERTAMBAH
                */
				$jumlah_video_selesai = $this->Log_materi_model->count(array('log_materi_iduser'=> get_user_info('ID'), 'log_materi_modul'=>$post['modul_id']));
				$jumlah_video_modul = $this->Materi_model->count(array('materi_modul'=>$post['modul_id'],'materi_type' => 'video'));
                if($jumlah == 0){
	            	$data = array(
	            		'log_materi_materiid' => $post['materi_id'],
	            		'log_materi_modul' => $post['modul_id'],
	            		'log_materi_kursus' => $post['materi_kursus'],
	            		'log_materi_iduser' => get_user_info('ID'),
	            		'log_materi_created' => date('Y-m-d H:i:s')
	            	);
	            	$this->Log_materi_model->insert($data);
                }
                if($jumlah_video_modul == $jumlah_video_modul){
                	$jumlah_log_modul = $this->Log_modul_model->count(array('log_modul_modulid'=>$post['modul_id'], 'log_modul_iduser'=>get_user_info('ID')));
                	if($jumlah_log_modul == 0){
	                	$data = array(
	                		'log_modul_modulid' => $post['modul_id'],
	                		'log_modul_iduser' => get_user_info('ID'),
	                		'log_modul_created' => date('Y-m-d H:i:s')
	                	);
	                	$this->Log_modul_model->insert($data);
                	}
                }
            }
        }
	}
	public function komentar(){
		$serv = $_SERVER;
		$status = 'publish';
		$note = 'sudah ditampilkan';

		$this->load->model(array('Komentar_model'));
		$this->load->library(array('form_validation','user_agent'));						
		
		$post = $this->input->post(NULL,TRUE);	
		$status = 'publish';
		$note = 'segera dimoderasi';

		$data = array(
			'comment_post_ID'		=> $post['materi_id'],
			'comment_author_IP' 	=> $serv['REMOTE_ADDR'],
			'comment_date' 			=> date('Y-m-d H:i:s'), 
			'comment_content' 		=> $post['comment_content'], 
			'comment_author' 		=> get_user_info('ID'), 
			'comment_author_name' 		=> get_user_info('username'), 
			'comment_author_email' 		=> get_user_info('email'), 
			'comment_approved' 		=> $status, 
			'comment_header' 		=> $post['comment_header'],
			'comment_agent' 		=> $this->agent->agent_string(), 	
		);
		$insert_id = $this->Komentar_model->insert($data);
		// if($insert_id){
			
	 //        $config = [
	 //               'mailtype'  => 'html',
	 //               'charset'   => 'utf-8',
	 //               'protocol'  => 'smtp',
	 //               'smtp_host' => 'ssl://smtp.gmail.com',
	 //               'smtp_user' => 'randytrikarya@gmail.com',    // Ganti dengan email gmail kamu
	 //               'smtp_pass' => 'trikaryacemerlang',      // Password gmail kamu
	 //               'smtp_port' => 465,
	 //               'crlf'      => "\r\n",
	 //               'newline'   => "\r\n"
	 //           ];

	 //        // Load library email dan konfigurasinya
	 //        $this->load->library('email', $config);

	 //        // Email dan nama pengirim
	 //        $this->email->from('no-reply@masrud.com', 'MasRud.com | M. Rudianto');

	 //        // Email penerima
	 //        $this->email->to('rankom202@gmail.com'); // Ganti dengan email tujuan kamu

	 //        // Lampiran email, isi dengan url/path file
	 //        $this->email->attach('https://masrud.com/content/images/20181215150137-codeigniter-smtp-gmail.png');

	 //        // Subject email
	 //        $this->email->subject('Kirim Email dengan SMTP Gmail | MasRud.com');

	 //        // Isi email
	 //        $this->email->message("Ini adalah contoh email CodeIgniter yang dikirim menggunakan SMTP email Google (Gmail).<br><br> Klik <strong><a href='https://masrud.com/post/kirim-email-dengan-smtp-gmail' target='_blank' rel='noopener'>disini</a></strong> untuk melihat tutorialnya.");

	 //        // Tampilkan pesan sukses atau error
	 //        if ($this->email->send()) {
	 //            echo 'Sukses! email berhasil dikirim.';
	 //        } else {
	 //            echo 'Error! email tidak dapat dikirim.';
	 //        }
		// }
		$this->session->set_flashdata('success', 'Komentar berhasil! ');
		redirect($serv['HTTP_REFERER'].'#form-komentar');
	}
}
