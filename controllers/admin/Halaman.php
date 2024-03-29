<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Halaman extends Backend_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('Halaman_model');
	}

	public function index(){
		$data = array();	
		$this->site->view('halaman', $data);
	}	

	public function upload($type=NULL){
		global $SConfig;
		/* jika aksinya adalah tambah ... */
		$this->site->create_dir();
		$this->load->library('upload', $this->site->media_upload_config());

		$date = date('Y-m-d H:i:s');
		$yeardir = date('Y');
		$monthdir = date('M');
		$datedir = date('d');

		if ($this->upload->do_upload('userfile')){
			$upload_data = $this->upload->data();
			
			$filefullpath = base_url().'uploads/'.$yeardir.'/'.$monthdir.'/'.$datedir.'/'.get_user_info('ID').'/'.$upload_data['file_name'];													
			$status = array(
					'success' => 'TRUE',
					'img_original' => $filefullpath,
					'img' => $this->site->resize_img($filefullpath,200,125,1)
				);	
			echo json_encode($status);					

		}
		else{					
			echo json_encode(array('success' => 'FALSE'));
		}
		
	}
	public function action($param){
		global $SConfig;
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
			if($param == 'tambah' || $param == 'update'){
				$rules = $this->Halaman_model->rules;
				$this->form_validation->set_rules($rules);	
				if ($this->form_validation->run() == TRUE) {
					$post = $this->input->post();	
					
					$comment_status = ""; 
					$comment_notification = "";	

					/*atribut artikel */
					$post_date = date('Y-m-d H:i:s');
					if(!empty($post['comment_status'])) $comment_status = $post['comment_status'] ; 
					if(!empty($post['comment_notification'])) $comment_notification = $post['comment_notification'];

					$post_attribute = array(
							'post_image' => @$post['post_image']
						);

					$data = array(
							'post_author' => get_user_info('ID'),
							'post_title' => $post['post_title'],
							'post_name' => $post['post_name'],
							'post_content' => $post['post_content'],
							'post_parent' => $post['post_parent'],
							'post_date' => $post_date, 
							'post_type' => 'halaman',
							'comment_status' => $comment_status,
							'post_attribute' => json_encode($post_attribute)
						);

					if(!empty($post['post_id_hidden'])){
						unset($data['post_date']);
						$this->Halaman_model->update($data, array('post_ID' => $post['post_id_hidden']));
					}
					else{
						/* jika ada judul artikel yang sama, maka berikan imbuhan 2 di belakangnya */
						$is_exist = $this->Halaman_model->count(array('post_title' => $data['post_title']));
						if($is_exist > 0){
							$data['post_title'] = $data['post_title'].' 2';
							$data['post_name'] = url_title($data['post_title'], '-', TRUE);
						}			

						$this->Halaman_model->insert($data);
					}
					
					$result = array('status' => 'success');

				}
				else{
					$result = array('status' => 'failed', 'errors' => $this->form_validation->error_array());
				}

				echo json_encode($result);
			}

			else if($param == 'ambil'){
				$post = $this->input->post(NULL,TRUE);

				if(!empty($post['id'])){
					$record = $this->Halaman_model->get($post['id']) ;
					
					$record->post_attribute = json_decode($record->post_attribute);

					foreach($record->post_attribute as $key => $val){
						if($key == 'post_image' && count($val) > 0){
							for ($x=0;$x<count($val);$x++){
								$array_post_image[$x]['tmb'] = $this->site->resize_img($val[$x],100,100,1);	
								$array_post_image[$x]['ori'] = $val[$x];	
							}

							$record->post_attribute->$key = $array_post_image;
						}
						else{
							$record->post_attribute->$key = $val;
						}
					}

					echo json_encode(array('data' => $record));
				}
				else{
					$record = $this->Halaman_model->get_by(array('post_type' => 'halaman'),NULL,NULL,FALSE,'post_ID, post_title, post_parent');				
					echo json_encode(array('record' => $record));	
				}				
			}

			else if($param == 'hapus'){
				$post = $this->input->post();
				if(!empty($post['post_id_hidden'])){
					$this->Halaman_model->delete($post['post_id_hidden']);
					$this->Halaman_model->delete_by(array('post_parent' => $post['post_id_hidden']) );
					$result = array('status' => 'success');
				}

				echo json_encode($result);
			}

			else if($param == 'sortir'){
				$post = $this->input->post(NULL, TRUE);
				foreach($post['ID'] as $sort => $id)
				$this->Halaman_model->update(array('menu_sort' => $sort+1),array('post_ID' => $id));								
			}

		}
	}
}