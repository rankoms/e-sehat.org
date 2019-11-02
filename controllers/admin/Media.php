<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Media extends Backend_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model(array('Media_model'));		
	}


	public function action($param,$type=NULL){
		global $SConfig;
		/* jika aksinya adalah tambah ... */
		if($param == 'tambah'){
			
			$this->site->create_dir();
			$this->load->library('upload', $this->site->media_upload_config());

			$date = date('Y-m-d H:i:s');
			$yeardir = date('Y');
			$monthdir = date('M');
			$datedir = date('d');

			if ($this->upload->do_upload('userfile')){
				$upload_data = $this->upload->data();
				
				$filefullpath = base_url().'uploads/'.$yeardir.'/'.$monthdir.'/'.$datedir.'/'.get_user_info('ID').'/'.$upload_data['file_name'];
				
				$data = array(	
					'post_author' => get_user_info('ID'), 		
					'post_date' => $date,	
					'post_title' => $upload_data['file_name'], 	
					'post_status' => 'inherit',	
					'post_name' => $upload_data['file_name'],	
					'guid' => $filefullpath,		
					'post_type' => 'attachment',				
					'post_attribute' => serialize($upload_data),	
					'post_mime_type' => $upload_data['file_type']
				);					
				
			
				if($this->Media_model->insert($data)){													
					$status = array(
							'success' => 'TRUE',
							'img_original' => $filefullpath,
							'img' => $this->site->resize_img($filefullpath,200,125,1)
						);	

					if($type=='produk'){
						$status['img'] = $this->site->resize_img($filefullpath,100,100,1);
					}

				}

				else{
					$status['success'] = 'FALSE';
				}
				
				echo json_encode($status);					

			}
			else{					
				echo json_encode(array('success' => 'FALSE'));
			}

		}
		
	}

}
