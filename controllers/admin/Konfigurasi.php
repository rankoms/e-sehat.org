<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Konfigurasi extends Backend_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model(array('Konfigurasi_model', 'Halaman_model'));		
	}

	public function index(){
		$data = array();
		$this->site->view('konfigurasi', $data);
	}

	public function action($param){
		global $SConfig;
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
			$array_website_setting = $this->input->post(NULL,TRUE);

			/* jika aksinya adalah tambah ... */
			if($param == 'update'){
				$website_setting = $this->Konfigurasi_model->get_by(array('option_name' => 'website_setting'));				

				if(empty($array_website_setting['halaman_konfirmasi'])){

					$post_date = date('Y-m-d H:i:s');
					$data = array(
							'post_author' => get_user_info('ID'),
							'post_title' => $array_website_setting['halaman_konfirmasi_lain'],
							'post_name' => url_title($array_website_setting['halaman_konfirmasi_lain'], '-', TRUE),
							'post_content' => nl2br($array_website_setting['isi_halaman_konfirmasi']),
							'post_date' => $post_date, 
							'post_type' => 'halaman',
							'comment_status' => 'open',
							'post_attribute' => json_encode(array())
						);					

					if(!empty($data['post_title'])){
						$is_exist = $this->Halaman_model->count(array('post_title' => $data['post_title']));
						if($is_exist > 0){
							$data['post_title'] = $array_website_setting['halaman_konfirmasi_lain'];
							$data['post_name'] = url_title($data['post_title'], '-', TRUE);
							$this->Halaman_model->update($data, array('post_title' => $data['post_title']));
						}							
						else{
							$data['post_title'] = $array_website_setting['halaman_konfirmasi_lain'];
							$data['post_name'] = url_title($data['post_title'], '-', TRUE);
							$this->Halaman_model->insert($data);	
						}
					}
					else{
						$is_exist = $this->Halaman_model->count(array('post_title' => 'Konfirmasi'));
						$data['post_title'] = 'Konfirmasi';
						$data['post_name'] = 'konfirmasi';

						if($is_exist > 0){
							$this->Halaman_model->update($data, array('post_title' => 'Konfirmasi'));
						}
						else{
							$this->Halaman_model->insert($data);
						}
					}

					$array_website_setting['halaman_konfirmasi'] = $array_website_setting['halaman_konfirmasi_lain'];
					$array_website_setting['halaman_konfirmasi_lain'] = '';					
				}
				else{
					$post_date = date('Y-m-d H:i:s');
					$data = array(
							'post_author' => get_user_info('ID'),
							'post_title' => $array_website_setting['halaman_konfirmasi'],
							'post_name' => url_title($array_website_setting['halaman_konfirmasi'], '-', TRUE),
							'post_content' => nl2br($array_website_setting['isi_halaman_konfirmasi']),
							'post_date' => $post_date, 
							'post_type' => 'halaman',
							'comment_status' => 'open',
							'post_attribute' => json_encode(array())
						);	
					if(!empty($data['post_title'])){
						$is_exist = $this->Halaman_model->count(array('post_title' => $data['post_title']));
						if($is_exist > 0){
							$data['post_title'] = $array_website_setting['halaman_konfirmasi'];
							$data['post_name'] = url_title($data['post_title'], '-', TRUE);
							$this->Halaman_model->update($data, array('post_title' => $data['post_title']));
						}							
						else{
							$data['post_title'] = $array_website_setting['halaman_konfirmasi'];
							$data['post_name'] = url_title($data['post_title'], '-', TRUE);
							$this->Halaman_model->insert($data);	
						}
					}
					else{
						$is_exist = $this->Halaman_model->count(array('post_title' => 'Konfirmasi'));
						$data['post_title'] = 'Konfirmasi';
						$data['post_name'] = 'konfirmasi';

						if($is_exist > 0){
							$this->Halaman_model->update($data, array('post_title' => 'Konfirmasi'));
						}
						else{
							$this->Halaman_model->insert($data);
						}
					}											
				}

				if(count($website_setting) > 0){
					$this->Konfigurasi_model->update(
						array('option_value' => serialize($array_website_setting)),
						array('option_name' => 'website_setting')
						);	
				}
				else{
					$this->Konfigurasi_model->insert(array(
							'option_name' => 'website_setting',
							'option_value' => serialize($array_website_setting)
						));	
				}

				echo json_encode(array('status' => 'success'));
			}
			else if($param == 'ambil'){
				$setting = $this->Konfigurasi_model->get_by(array('option_name' => 'website_setting'),NULL,NULL,TRUE);
				if($setting){
					$website_setting = unserialize($setting->option_value);					

					echo json_encode(array(
							'website_setting' => $website_setting 
						));	
				}
			}
		}
	}
}