<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Log extends Backend_Controller {
	
	// http://stackoverflow.com/questions/19706046/how-to-read-an-external-local-json-file-in-javascript
	
	protected $user_detail;

	public function __construct(){
		parent::__construct();		
		$this->load->model(array('User_model', 'User_detail_model', 'Log_kursus_model', 'Log_latihan_model', 'Log_sertifikat_model', 'Sertifikat_model'));
	}

	public function index(){
		$this->site->view('log_kursus', $data);		
	}

	public function kursus($param=null){
		global $SConfig;
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
			if($param == 'ambil'){
				$post = $this->input->post();

				if(!empty($post['id'])){
					$record = $this->Log_kursus_model->get_log_kursus(array('dk_id'=>$post['id']));
					echo json_encode(array('status' => 'success', 'data' => $record));
				}
				else{
					if(get_user_info('group') == 'admin'){
						$record = $this->Log_kursus_model->get_log_kursus();
					}
					else{
						$record = $this->Log_kursus_model->get_log_kursus(array('organisasi_id'=> get_user_info('user_organisasi')));
					}
					echo json_encode(array(
						'data' => $record,
					) );					
				}			
			}

			else if($param == 'hapus'){
				$post = $this->input->post();
				if(!empty($post['dk_id'])){
					$this->Log_kursus_model->delete($post['dk_id']);
					$result = array('status' => 'success');
					echo json_encode($result);
				}
			}
		}
		else{
			$this->site->view('log_kursus', $data);
		}
				
	}

	public function test($param=null){
		global $SConfig;
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
			if($param == 'ambil'){
				$post = $this->input->post();

				if(!empty($post['id'])){
					$record = $this->Log_latihan_model->get_log_latihan(array('log_lat_id'=>$post['id']));
					echo json_encode(array('status' => 'success', 'data' => $record));
				}
				else{
					// if(get_user_info('group') == 'admin'){
					// 	$record = $this->User_model->get_user_instruktur(array('group' => 'instruktur'));	
					// }
					// else if(get_user_info('group') == 'instruktur'){
					// 	$record = $this->User_model->get_user_instruktur(array('ID'=> get_user_info('ID')));
					// }
					// else{
					// 	$record = $this->User_model->get_user_instruktur(array('group' => 'instruktur', 'user_organisasi' => get_user_info('user_organisasi')));

					// }

					if(get_user_info('group') == 'admin'){
						$record = $this->Log_latihan_model->get_log_latihan();
					}
					else{
						$record = $this->Log_latihan_model->get_log_latihan(array('organisasi_id'=> get_user_info('user_organisasi')));
					}
					
					echo json_encode(array(
						'data' => $record,
					) );					
				}			
			}

			else if($param == 'hapus'){
				$post = $this->input->post();
				if(!empty($post['log_lat_id'])){
					$this->Log_latihan_model->delete($post['log_lat_id']);
					$result = array('status' => 'success');
					echo json_encode($result);
				}
			}
		}
		else{
			$this->site->view('log_test', $data);
		}
				
	}
	public function sertifikat($param=null){
		global $SConfig;
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
			if($param == 'ambil'){
				$post = $this->input->post();

				if(!empty($post['id'])){
					$record = $this->Log_latihan_model->get_log_latihan(array('log_lat_id'=>$post['id']));
					echo json_encode(array('status' => 'success', 'data' => $record));
				}
				else{
					// if(get_user_info('group') == 'admin'){
					// 	$record = $this->User_model->get_user_instruktur(array('group' => 'instruktur'));	
					// }
					// else if(get_user_info('group') == 'instruktur'){
					// 	$record = $this->User_model->get_user_instruktur(array('ID'=> get_user_info('ID')));
					// }
					// else{
					// 	$record = $this->User_model->get_user_instruktur(array('group' => 'instruktur', 'user_organisasi' => get_user_info('user_organisasi')));

					// }


					if(get_user_info('group') == 'admin'){
						$record = $this->Log_sertifikat_model->get_log_sertifikat();
					}
					else{
						$record = $this->Log_sertifikat_model->get_log_sertifikat(array('organisasi_id'=> get_user_info('user_organisasi')));
					}
					
					echo json_encode(array(
						'data' => $record,
					) );					
				}			
			}

			else if($param == 'hapus'){
				$post = $this->input->post();
				if(!empty($post['log_lat_id'])){
					$this->Log_latihan_model->delete($post['log_lat_id']);
					$result = array('status' => 'success');
					echo json_encode($result);
				}
			}
		}
		else{
			$this->site->view('log_sertifikat', $data);
		}
				
	}

	public function ujian($param=null){
		global $SConfig;
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
			if($param == 'ambil'){
				$post = $this->input->post();

				if(!empty($post['id'])){
					$record = $this->Log_latihan_model->get_log_ujian(array('log_lat_id'=>$post['id']));
					echo json_encode(array('status' => 'success', 'data' => $record));
				}
				else{
					// if(get_user_info('group') == 'admin'){
					// 	$record = $this->User_model->get_user_instruktur(array('group' => 'instruktur'));	
					// }
					// else if(get_user_info('group') == 'instruktur'){
					// 	$record = $this->User_model->get_user_instruktur(array('ID'=> get_user_info('ID')));
					// }
					// else{
					// 	$record = $this->User_model->get_user_instruktur(array('group' => 'instruktur', 'user_organisasi' => get_user_info('user_organisasi')));

					// }

					if(get_user_info('group') == 'admin'){
						$record = $this->Log_latihan_model->get_log_ujian(array('log_lat_type'=>'ujian'));
					}
					else{
						$record = $this->Log_latihan_model->get_log_ujian(array('log_lat_type'=>'ujian','organisasi_id'=> get_user_info('user_organisasi')));
					}
					
					echo json_encode(array(
						'data' => $record,
					) );					
				}			
			}

			else if($param == 'hapus'){
				$post = $this->input->post();
				if(!empty($post['log_lat_id'])){
					$this->Log_latihan_model->delete($post['log_lat_id']);
					$result = array('status' => 'success');
					echo json_encode($result);
				}
			}
		}
		else{
			$this->site->view('log_ujian', $data);
		}
				
	}

	public function preview($id){


		$data = $this->Sertifikat_model->get_log_sertifikat(array('log_ser_id'=>$id));
		// print_r($data);


		$this->load->library('Pdf');

		$pdf = new Pdf('L', 'mm', 'A4', true, 'UTF-8', false);
		
		//= 1.	set document information
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor('Depkes');
		$pdf->SetTitle('Preview');
		$pdf->SetSubject('Preview');
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
		$img_file = base_url().'uploads/certificate/template-sertifikat.jpg';
		$logo = $data[0]->organisasi_photo;
		// $nomor = $data[0]->sertifikat_nomor_sk;
		$nomerfull = $data[0]->log_ser_nomer_full;
		$tanggal = tgl_ind($data[0]->log_ser_created);
		$nosk = $data[0]->kursus_nosk;
		$noskp = $data[0]->kursus_skp;
		$nama = $data[0]->nama;
		$namaketua = $data[0]->organisasi_pimpinan;
		$kursus = $data[0]->kursus_nama;
		$signature = $data[0]->organisasi_signature;
		$namaorganisasi = $data[0]->organisasi_nama;

		if($logo != ''){
			$logo = '<img style="height:100px" src="'.$logo.'">';
		}
		if($signature != "")
		{
			$signature		= '<img style="width:100px" src="'.$signature.'">';
		}	
			
		$pimpinan = $data[0]->sertifikat_pemimpin;

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
}