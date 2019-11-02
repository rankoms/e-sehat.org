<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Komentar extends Backend_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model(array('Komentar_model'));		
	}

	public function index(){
		$data = array();	
		$this->site->view('komentar', $data);
	}	

	public function action($param){
		global $SConfig;
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
			if($param == 'update'){
				$post = $this->input->post(NULL,TRUE);
				$rules = $this->Komentar_model->rules;
				$this->form_validation->set_rules($rules);

				if ($this->form_validation->run() == TRUE) {
					$post = $this->input->post();
					$data = array(
						'comment_reply' 		=> $post['comment_reply'], 
						'comment_author_admin'  => get_user_info('username'),
						'comment_content' 		=> $post['comment_content'], 
						'comment_approved' 		=> $post['comment_approved'], 
					);


					if(!empty($post['comment_ID'])){
						$this->Komentar_model->update($data, array('comment_ID' => $post['comment_ID']));
						$result = array('status' => 'success');
					}

				}
				else{
					$result = array('status' => 'failed', 'errors' => $this->form_validation->error_array());
				}

				echo json_encode($result);
			}

			else if($param == 'ambil'){
				$post = $this->input->post(NULL,TRUE);


				if(!empty($post['id'])){
					$record = $this->Komentar_model->get($post['id']);
					echo json_encode(array('status' => 'success', 'data' => $record));
				}
				else{					
					if(get_user_info('group') == 'adminop' || get_user_info('group') == 'instructor'){
						$record = $this->Komentar_model->get_komentar(array('kursus_organisasi' => get_user_info('user_organisasi')));
					}
					else{
						$record = $this->Komentar_model->get_komentar();

					}

					echo json_encode(
							array(
									'data' => $record,								
								)
						);	
				}			
			}
				
			else if($param == 'edit'){
			}	

			else if($param == 'hapus'){
				$post = $this->input->post();
				if(!empty($post['comment_ID'])){
					$this->Komentar_model->delete($post['comment_ID']);
					$result = array('status' => 'success');
				}

				echo json_encode($result);				
			}	

			else if($param == 'mass'){
				$post = $this->input->post(NULL,TRUE);
				if($post['mass_action_type'] == 'hapus'){
					if(count($post['comment_ID']) > 0){										
						foreach($post['comment_ID'] as $id)
						$this->Komentar_model->delete($id);
						$result = array('status' => 'success');
						echo json_encode($result);	
					}
				}

				else if($post['mass_action_type'] == 'pending' || $post['mass_action_type'] == 'publish'){
					if(count(@$post['comment_ID']) > 0){
						foreach($post['comment_ID'] as $id)
						$this->Komentar_model->update(array('comment_approved' => $post['mass_action_type']),array('comment_ID' => $id));
						$result = array('status' => 'success');
						echo json_encode($result);
					}
				}				
			}
		}	
	}


}
