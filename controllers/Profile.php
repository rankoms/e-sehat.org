<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profile extends Frontend_Controller {
	protected $user_detail;
	public function __construct(){
		parent::__construct();		
		$this->load->model(array('Log_sertifikat_model', 'Sertifikat_model'));
		$this->load->library(array('Pdf'));
		if(!get_user_info('ID')){
			redirect('/');
		}

	}

	public function index(){
		$data['profile'] = get_profile($_SESSION['ID']);
		$this->site->view('dashboard', $data);
	}	

	public function profile($action=NULL){

		$post = $this->input->post();
		if($action == 'informasidasar'){
			$rules = $this->User_model->informasi_dasar;
			$this->form_validation->set_rules($rules);
			if($this->form_validation->run() == TRUE){
				$data = array(
					'nama' => $post['nama'],
					'email' => $post['email']
				);

				$this->User_model->update($data, array('username' => $post['username']));

				$status = array('status' => 'success');
			}
			else{
				$status = array('status'=> 'failed', 'errors' => $this->form_validation->error_array());
			}
			echo json_encode($status);

		}
		else if($action == 'photo'){

			/* ARTIBUT UNTUK FOTO */
			$this->site->create_dir();
			$this->load->library('upload', $this->site->media_upload_config());
			$date = date('Y-m-d H:i:s');
			$yeardir = date('Y');
			$monthdir = date('M');
			$datedir = date('d');

 			$upload_data = $this->upload->data();
	        if($this->upload->do_upload("file")){ //upload file
 				$upload_data = $this->upload->data();
				$filefullpath = base_url().'uploads/'.$yeardir.'/'.$monthdir.'/'.$datedir.'/'.get_user_info('ID').'/'.$upload_data['file_name'];
	            $path_photo = $filefullpath;
	            $photo 		= $upload_data['file_name'];
			}
			else{
	            $path_photo = '';
	            $photo 		= 'img-content';
			}

			$data = array('path_photo' => $path_photo);
			$hasilnya = $this->User_model->update($data, array('username'=>$post['username']));
			if($hasilnya){
				$status = array('status' => 'success', 'hasil' => $hasilnya);
			}
			else{
				$status = array('status' => 'success', 'hasil' => $hasilnya);
			}
			echo json_encode($status);
		}
		else if($action == 'ubah-password'){
			$this->user_detail = $this->User_model->get_by(array('username' => $post['username']), 1, NULL, TRUE);
			$rules = $this->User_model->ubah_password;
			$this->form_validation->set_rules($rules);
			if($this->form_validation->run() == TRUE){
				// $data = array('password' => bCrypt($post['newpassword'],12));
				$data = array('password' => md5($post['newpassword']));
				$this->User_model->update($data, array('username' => $post['username']));
				$status = array('status' => 'success');
			}
			else{
				$status = array('status'=> 'failed', 'errors' => $this->form_validation->error_array());
			}
			echo json_encode($status);
		}
		else{

			$data['profile'] = get_profile($_SESSION['ID']);
			$this->site->view('profile', $data);	
		}

	}	
	public function kursus_saya(){
		$this->site->view('kursus-saya');	
	}	
	public function register(){
		$this->site->view('register');	
	}	
	public function sertifikat(){
		if(get_user_info('user_type') == 'BELAJAR'){
			redirect('/dashboard');
		}
		// $this->Sertifikat_model->get_by(array('sert'))
		$this->site->view('sertifikat');	
	}	


	public function get_sertifikat(){
		/* LOGIC PEMBUATAN NOMER SERTIFIKAT */
		/*
			1. CEK DAHULU SUDAH ADA BELUM SERTIFIKAT ATAS USERID, KURSUS
			2. KALAU BELUM ADA INSERT KE TABEL LOG_SERTIFIKAT
			3. KALAU SUDAH ADA MAKA TINGGAL AMBIL NOMER NYA
		*/

		$data = $this->Kursus_model->get_kursus(array('kursus_id'=> $this->uri->segment(3),'kursus_slug' =>$this->uri->segment(4) ));
		// var_dump($data);
		// print_r($data);
		// echo $data[0]->kursus_photo;


		$log_sertifikat = $this->Log_sertifikat_model->count(array('log_ser_userid'=>get_user_info('ID'), 'log_ser_kursusid'=>$this->uri->segment(3)));
		// echo $log_sertifikat;
		// echo $this->db->last_query();
		// echo $log_sertifikat;
		if($log_sertifikat == 0){
			$nomerlog = $this->Log_sertifikat_model->count(array('log_ser_kursusid' => $this->uri->segment(3), 'log_ser_created like'=> '%'.date('Y-m').'%')) +1;
			// echo $this->db->last_query();
			if(strlen($nomerlog) == 1){
				$nomerfull = '000'.$nomerlog.'/'.$data[0]->sertifikat_nomor.'/'.getRomawi(date('n')).'/'.date('Y');
			}
			else if(strlen($nomerlog) == 2){
				$nomerfull = '00'.$nomerlog.'/'.$data[0]->sertifikat_nomor.'/'.getRomawi(date('n')).'/'.date('Y');
			}
			else if(strlen($nomerlog) == 3){
				$nomerfull = '0'.$nomerlog.'/'.$data[0]->sertifikat_nomor.'/'.getRomawi(date('n')).'/'.date('Y');
			}
			else if(strlen($nomerlog) == 4){
				$nomerfull = $nomerlog.'/'.$data[0]->sertifikat_nomor.'/'.getRomawi(date('n')).'/'.date('Y');
			}
			$datalog = array(
				'log_ser_nomer' => $nomerlog,
				'log_ser_nomer_full' => $nomerfull,
				'log_ser_kursusid' => $this->uri->segment(3),
				'log_ser_sertifikatid' => $data[0]->sertifikat_id,
				'log_ser_userid' => get_user_info('ID'),
				'log_ser_created' => date('Y-m-d H:i:s')
			);
			$tanggal = tgl_ind(date('Y-m-d H:i:s'));
			$this->Log_sertifikat_model->insert($datalog);


			$nosk = $data[0]->kursus_nosk;
		}
		else{
			$datasertifikat = $this->Log_sertifikat_model->get_by(array('log_ser_userid'=>get_user_info('ID'), 'log_ser_kursusid'=>$this->uri->segment(3)));
			// var_dump($datasertifikat);
			$nomerfull = $datasertifikat[0]->log_ser_nomer_full;
			$tanggal = tgl_ind($datasertifikat[0]->log_ser_created);
			$nosk = $data[0]->kursus_nosk;
		}

		$img_file = base_url().'uploads/certificate/template-sertifikat.jpg';
		$nama = $_SESSION['nama'];
		$logo = $data[0]->organisasi_photo;
		$namaorganisasi = $data[0]->organisasi_nama;
		$noskp = $data[0]->kursus_skp;
		$nokursus = $data[0]->kursus_skp;
		$kursus = $data[0]->kursus_nama;
		$signature = $data[0]->organisasi_signature;
		$pimpinan = $data[0]->organisasi_pimpinan;

		/*ERROR*/

		$nama = $_SESSION['nama'];
		// $logo = '';
		// $namaorganisasi = '';
		// $noskp = '';
		// $nokursus = '';
		// $kursus = '';
		// $signature = '';
		// $pimpinan = '';

		if($logo != ''){
			$logo = '<img style="height:100px" src="'.$logo.'">';
		}
		if($signature != "")
		{
			$signature		= '<img style="width:100px" src="'.$signature.'">';
		}	
			
		$this->load->library('Pdf');

		$pdf = new Pdf('L', 'mm', 'A4', true, 'UTF-8', false);
		
		//= 1.	set document information
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor('Depkes');
		$pdf->SetTitle('Laporan');
		$pdf->SetSubject('Laporan');
		$pdf->SetKeywords('TCPDF, PDF');

		//=	2.	remove default header/footer
		$pdf->setPrintHeader(false);
		$pdf->setPrintFooter(false);

		//=	3.	set default monospaced font
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

		//=	4.	set margins
		$pdf->SetMargins(0, 19, 5);

		//=	5.	set auto page breaks
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
		
		//=	6.	set image scale factor
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

		//=	7.	set some language-dependent strings (optional)
		if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
			require_once(dirname(__FILE__).'/lang/eng.php');
			$pdf->setLanguageArray($l);
		}

		//=	8.	add a page
		$pdf->AddPage('L','A4');
		
		$pdf->SetAutoPageBreak(false, 0);
		
		/*==========================================
			9.	CREATE IMAGE
		==========================================*/
 		// $img_file = base_url().'uploads/certificate/draft-sertifcate-min.png';

		// $img_file = base_url().'uploads/certificate/sertifikat-elearning.jpg';
			//$pdf->Image($img_file, 0, 0, 300, 400, '', '', '', false, 300, '', false, false, 0); // 
		// $pdf->Image($img_file, 0, 0, 210, 297, '', '', '', false, 300, '', false, false, 0); // A4 potrait
		$pdf->Image($img_file, 0, 0, 297, 210, '', '', '', false, 300, '', false, false, 0); // A4 lanscape
		
		
		/*==========================================
			10.	CREATE HTML
		==========================================*/
		$html = '
		<font face="times">
		';

		$html .= '
			<table width="1024px" cellspacing="0" cellpadding="1" border="0px">
                <tr>
                    <td width="20%"></td>
                    <td width="60%"></td>
					<td width="20%" height="20px" style="text-align:center;">'.$logo.'</td>   
                </tr>
                <tr>
                    <td colspan="3" height="75px" style="text-align:center;" > <h2> <strong>'.$nomerfull.'</strong> </h2> </td>    
                </tr>
                <tr>
                    <td colspan="3" height="75px" style="text-align:center;" >  </td>    
                </tr>
                <tr>
                    <td colspan="3" height="75px" style="text-align:center;" ><h1 style="font-size:40px">'.strtoupper($nama).'</h1></td>    
                </tr>
                <tr>
                    <td colspan="3" height="70px" style="text-align:center;" >  </td>    
                </tr>
                <tr>
                    <td colspan="3" height="30px" style="text-align:center;" ><h1 style="font-size:20px">'.strtoupper($kursus).'</h1></td>    
                </tr>
                <tr>
                    <td colspan="3" height="30px" style="text-align:center;" > SK:'. $nosk.' </td>    
                </tr> 
                <tr>
                    <td colspan="3" height="30px" style="text-align:center;" > '.$noskp .' SKP </td>    
                </tr> 
                <tr>
                    <td colspan="3" height="30px" style="text-align:center;" > '.$tanggal .'</td>    
                </tr> 
                <tr>
                    <td colspan="3" height="30px" style="text-align:center;" > KETUA UMUM '.strtoupper($namaorganisasi).' </td>    
                </tr>
                <tr>
                    <td colspan="3" height="80px" style="text-align:center;" > '.$signature.' </td>    
                </tr>
                <tr>
                    <td colspan="3" height="50px" style="text-align:center;" > <b> '.$pimpinan.' </b> </td>    
                </tr>

            </table>
		'; 
		//echo $html;
		//die();
		
		// output the HTML content
		$pdf->writeHTML($html, true, false, true, false, '');

		//=	11.	Close and output PDF document
		$pdf->Output('laporan.pdf', 'I');
	}

	public function report_progress(){	
		$data['kursus'] = $this->Kursus_model->get_kursus(array('kursus_id'=> $this->uri->segment(2), 'kursus_slug'=> $this->uri->segment(3)));
		if(count($data['kursus'])){
			$data['duration'] = $this->Log_materi_model->get_log_materi(array('ID'=> get_user_info('ID'), 'kursus_id'=> $this->uri->segment(2)), NULL, NULL, NULL, 'sum(materi_duration) duration');
			// echo $this->db->last_query();
			$this->site->view('report_kursus', $data);
		}
		else{
			$this->site->view('error404');
		}
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

		//  $statusnya = $this->session->flashdata();
		//	print_r($statusnya);
		//  $this->session->set_flashdata('tes','value');
		redirect($serv['HTTP_REFERER'].'#form-komentar');
	}

	public function email(){
        $config = [
               'mailtype'  => 'html',
               'charset'   => 'utf-8',
               'protocol'  => 'smtp',
               'smtp_host' => 'ssl://smtp.gmail.com',
               'smtp_user' => 'esehatorg@gmail.com',    // Ganti dengan email gmail kamu
               'smtp_pass' => 'Es3h4t0rg@&!#',      // Password gmail kamu
               'smtp_port' => 465,
               'crlf'      => "\r\n",
               'newline'   => "\r\n"
           ];

        // Load library email dan konfigurasinya
        $this->load->library('email', $config);

        // Email dan nama pengirim
        $this->email->from('no-reply@esehat.org', 'e-sehat.org | Kementerian Kesehatan');

        // Email penerima
        $this->email->to('rankom202@gmail.com'); // Ganti dengan email tujuan kamu

        // Lampiran email, isi dengan url/path file
        // $this->email->attach('https://masrud.com/content/images/20181215150137-codeigniter-smtp-gmail.png');

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

}
