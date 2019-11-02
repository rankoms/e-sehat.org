<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Master extends Backend_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model(array('Artikel_model', 'Kategori_model', 'Bank_model', 'Coupon_model', 'Profesi_model', 'Organisasi_model', 'Slider_model', 'Media_model', 'Video_model', 'Kursus_model'));
	}

	public function index(){
		$data = array();
		$this->site->view('master/bank', $data);
	}
	public function bank($action=''){
		global $SConfig;
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
			if($action == 'tambah' || $action == 'update'){
				$rules = $this->Bank_model->rules;
				$this->form_validation->set_rules($rules);

				if ($this->form_validation->run() == TRUE) {
					$post = $this->input->post();

					$data = array (
							'bank_nama' 	=> $post['bank_nama'],
							'bank_norek'	=> $post['bank_norek'],
							'bank_photo'	=> '',
							'bank_created'	=> date('Y-m-d h:i:s'),
							'bank_updated' 	=> date('Y-m-d h:i:s')
					);
					
					if(!empty($post['bank_id'])){
						$data = array (
								'bank_nama' 	=> $post['bank_nama'],
								'bank_norek'	=> $post['bank_norek'],
								'bank_photo'	=> '',
								// 'bank_created'	=> date('Y-m-d h:i:s'),
								'bank_updated' 	=> date('Y-m-d h:i:s')
						);
						$this->Bank_model->update($data, array('bank_id' => $post['bank_id']));
						$result = array('status' => 'success');	
					}
					else{
						if($this->Bank_model->insert($data)){
							$result = array('status' => 'success');	
						}		
						else{
							$result = array('status' => 'failed');	
						}			
					}

					echo json_encode($result);
				}
				else{
					echo json_encode(array('status' => 'failed','errors' => $this->form_validation->error_array()));
				}
			}

			else if($action == 'ambil'){
				$post = $this->input->post(NULL,TRUE);

				if(!empty($post['id'])){
					$record = $this->Bank_model->get($post['id']);
					// $record->post_attribute = json_decode($record->post_attribute);
					echo json_encode(array('status' => 'success', 'data' => $record));
				}
				else{
					$total_rows = $this->Bank_model->count();
					$offset = NULL;

					if(!empty($post['hal_aktif']) && $post['hal_aktif'] > 1 ){
						$offset = ($post['hal_aktif'] - 1) * NULL ;
					}
					else if(!empty($post['cari']) &&($post['cari'] != 'null')){
						$cari = $post['cari'];
						$total_rows = $this->Bank_model->count(array("bank_nama LIKE" => "%$cari%"));
						@$record = $this->Bank_model->get_by(array("bank_nama LIKE" => "%$cari%"),NULL, $offset);
					}

					else{
						$record = $this->Bank_model->get_by(array(),NULL, $offset);
					}
					

					echo json_encode(
							array(
									'total_rows' => $total_rows,
									'perpage' => NULL,
									'data' => $record
								)

						);					
				}	
			}

			else if($action == 'hapus'){
				$post = $this->input->post();
				if(!empty($post['bank_id'])){
					$this->Bank_model->delete($post['bank_id']);
					$result = array('status' => 'success');
				}

				echo json_encode($result);								
			}

			else if($action == 'sortir'){
				$post = $this->input->post(NULL, TRUE);
				foreach($post['ID'] as $sort => $id)
				$this->Kategori_model->update(array('category_sort' => $sort+1),array('category_ID' => $id));								
			}
		}
		else{
			$data = array();	
			$this->site->view('master/bank', $data);	
		}	
	}

	public function coupon($action=''){
		global $SConfig;
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
			if($action == 'tambah' || $action == 'update'){
				$rules = $this->Coupon_model->rules;
				$this->form_validation->set_rules($rules);

				if ($this->form_validation->run() == TRUE) {
					$post = $this->input->post();

					$data = array (
							'coupon_nama' 	=> $post['coupon_nama'],
							'coupon_created'	=> date('Y-m-d h:i:s'),
							'coupon_updated'	=> date('Y-m-d h:i:s'),
							'coupon_userid' 	=> get_user_info('ID')
					);
					
					if(!empty($post['coupon_id'])){
						$data = array (
							'coupon_nama' 	=> $post['coupon_nama'],
							'coupon_updated'	=> date('Y-m-d h:i:s'),
							'coupon_userid' 	=> get_user_info('ID')
						);
						$this->Coupon_model->update($data, array('coupon_id' => $post['coupon_id']));
						$result = array('status' => 'success');	
					}
					else{
						if($this->Coupon_model->insert($data)){
							$result = array('status' => 'success');	
						}		
						else{
							$result = array('status' => 'failed');	
						}			
					}

					echo json_encode($result);
				}
				else{
					echo json_encode(array('status' => 'failed','errors' => $this->form_validation->error_array()));
				}
			}

			else if($action == 'ambil'){
				$post = $this->input->post(NULL,TRUE);

				if(!empty($post['id'])){
					$record = $this->Coupon_model->get($post['id']);
					// $record->post_attribute = json_decode($record->post_attribute);
					echo json_encode(array('status' => 'success', 'data' => $record));
				}
				else{
					$total_rows = $this->Coupon_model->count();
					$offset = NULL;

					if(!empty($post['hal_aktif']) && $post['hal_aktif'] > 1 ){
						$offset = ($post['hal_aktif'] - 1) * NULL ;
					}
					else if(!empty($post['cari']) &&($post['cari'] != 'null')){
						$cari = $post['cari'];
						$total_rows = $this->Coupon_model->count(array("coupon_nama LIKE" => "%$cari%"));
						@$record = $this->Coupon_model->get_by(array("coupon_nama LIKE" => "%$cari%"),NULL, $offset);
					}

					else{
						$record = $this->Coupon_model->get_by(array(),NULL, $offset);
					}
					

					echo json_encode(
							array(
									'total_rows' => $total_rows,
									'perpage' => NULL,
									'data' => $record
								)

						);					
				}	
			}

			else if($action == 'hapus'){
				$post = $this->input->post();
				if(!empty($post['category_id'])){
					$this->Kategori_model->delete($post['category_id']);
					$this->Kategori_model->delete_by(array('category_parent' => $post['category_id']));
					$result = array('status' => 'success');
				}

				echo json_encode($result);								
			}

			else if($action == 'sortir'){
				$post = $this->input->post(NULL, TRUE);
				foreach($post['ID'] as $sort => $id)
				$this->Kategori_model->update(array('category_sort' => $sort+1),array('category_ID' => $id));								
			}
		}
		else{
			$data = array();	
			$this->site->view('master/coupon', $data);	
		}	
	}


	public function profesi($action=''){
		global $SConfig;
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
			if($action == 'tambah' || $action == 'update'){
				$rules = $this->Profesi_model->rules;
				$this->form_validation->set_rules($rules);

				if ($this->form_validation->run() == TRUE) {
					$post = $this->input->post();

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
					}
					/* SAAT EDIT PHOTO CONTENT KALAU ADA ISINYA*/
					elseif(!empty($post['path_photo'])){
			            $path_photo = $post['path_photo'];
					}
					else{
			            $path_photo = '';
					}


					$data = array (
							'profesi_nama' 	=> $post['profesi_nama'],
							'profesi_title' 	=> $post['profesi_title'],
							'profesi_slug' 	=> url_title($post['profesi_title'], '-', TRUE),
							'profesi_description' 	=> $post['profesi_description'],
							'profesi_photo' 	=> $path_photo,
							'profesi_created'	=> date('Y-m-d h:i:s'),
							'profesi_updated'	=> date('Y-m-d h:i:s'),
							'profesi_userid' 	=> get_user_info('ID')
					);
					
					if(!empty($post['profesi_id'])){
						$data = array (
								'profesi_nama' 	=> $post['profesi_nama'],
								'profesi_title' 	=> $post['profesi_title'],
								'profesi_slug' 	=> url_title($post['profesi_title'], '-', TRUE),
								'profesi_description' 	=> $post['profesi_description'],
								'profesi_photo' 	=> $path_photo,
								// 'profesi_created'	=> date('Y-m-d h:i:s'),
								'profesi_updated'	=> date('Y-m-d h:i:s'),
								'profesi_userid' 	=> get_user_info('ID')
						);
					
						$this->Profesi_model->update($data, array('profesi_id' => $post['profesi_id']));
						$result = array('status' => 'success');	
					}
					else{
						if($this->Profesi_model->insert($data)){
							$result = array('status' => 'success');	
						}		
						else{
							$result = array('status' => 'failed');	
						}			
					}

					echo json_encode($result);
				}
				else{
					echo json_encode(array('status' => 'failed','errors' => $this->form_validation->error_array()));
				}
			}

			else if($action == 'ambil'){
				$post = $this->input->post();

				if(!empty($post['id'])){
					$record = $this->Profesi_model->get($post['id']);
					echo json_encode(array('status' => 'success', 'data' => $record));
				}
				else{
					$offset = NULL;
					
					if(!empty($post['hal_aktif']) && $post['hal_aktif'] > 1){
						$offset = ($post['hal_aktif'] - 1) * NULL ;
					}

					if(!empty($post['cari']) && ($post['cari'] != 'null')){
						$cari = $post['cari'];
						$total_rows = $this->Profesi_model->count(array("profesi_nama LIKE" => "%$cari%"));
						@$record = $this->Profesi_model->get_by(array("profesi_nama LIKE" => "%$cari%"),NULL, $offset, FALSE, NULL);
					}
					else{
						$record = $this->Profesi_model->get_by(NULL,NULL,$offset,FALSE, NULL);	
						$total_rows = $this->Profesi_model->count();						
					}

					echo json_encode(array(
						'data' => $record,
						'total_rows' => $total_rows, 
						'perpage' => NULL,
					) );					
				}	
			}

			else if($action == 'hapus'){
				$post = $this->input->post();
				if(!empty($post['profesi_id'])){
					$this->Profesi_model->delete($post['profesi_id']);
					// $this->Kategori_model->delete_by(array('category_parent' => $post['category_id']));
					$result = array('status' => 'success');
				}

				echo json_encode($result);								
			}

			else if($action == 'sortir'){
				$post = $this->input->post(NULL, TRUE);
				foreach($post['ID'] as $sort => $id)
				$this->Kategori_model->update(array('category_sort' => $sort+1),array('category_ID' => $id));								
			}
		}
		else{
			$data = array();	
			$this->site->view('master/profesi', $data);	
		}	
	}


	public function organisasi($action='',$type=NULL){
		global $SConfig;
		if($action == 'tambah' || $action == 'update'){
			$rules = $this->Organisasi_model->rules;
			$this->form_validation->set_rules($rules);

			if ($this->form_validation->run() == TRUE) {
				$post = $this->input->post();

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
		            $path_photo1 = $filefullpath;
		            $photo 		= $upload_data['file_name'];
				}
				/* SAAT EDIT PHOTO CONTENT KALAU ADA ISINYA*/
				elseif(!empty($post['path_photo'])){
		            $path_photo1 = $post['path_photo'];
				}
				else{
		            $path_photo1 = '';
				}
		        if($this->upload->do_upload("file2")){ //upload file
	 				$upload_data = $this->upload->data();
					$filefullpath = base_url().'uploads/'.$yeardir.'/'.$monthdir.'/'.$datedir.'/'.get_user_info('ID').'/'.$upload_data['file_name'];
		            $path_photo2 = $filefullpath;
		            $photo2 		= $upload_data['file_name'];
				}
				/* SAAT EDIT PHOTO CONTENT KALAU ADA ISINYA*/
				elseif(!empty($post['path_photo2'])){
		            $path_photo2 = $post['path_photo2'];
				}
				else{
		            $path_photo2 = '';
				}

				$slider = array(
					'post_image' => @$post['post_image']
				);
				$data = array (
						'organisasi_profesi'     => $post['organisasi_profesi'],
						'organisasi_nama'        => $post['organisasi_nama'],
						'organisasi_kode'        => $post['organisasi_kode'],
						'organisasi_title'       => $post['organisasi_nama'],
						'organisasi_subtitle'    => $post['organisasi_nama'],
						'organisasi_slug'        => url_title($post['organisasi_nama'], '-', TRUE),
						'organisasi_description' => $post['organisasi_description'],
						'organisasi_pimpinan'    => $post['organisasi_pimpinan'],
						'organisasi_photo'		 => $path_photo1,
						'organisasi_signature'   => $path_photo2,
						'organisasi_url'         => $post['organisasi_url'],
						'organisasi_created'     => date('Y-m-d h:i:s'),
						'organisasi_slider'		 => json_encode($slider)
				);
				
				if(!empty($post['organisasi_id'])){
				
					$this->Organisasi_model->update($data, array('organisasi_id' => $post['organisasi_id']));
					$result = array('status' => 'success');	
				}
				else{
					if($this->Organisasi_model->insert($data)){
						$result = array('status' => 'success');	
					}		
					else{
						$result = array('status' => 'failed');	
					}			
				}

				echo json_encode($result);
			}
			else{
				echo json_encode(array('status' => 'failed','errors' => $this->form_validation->error_array()));
			}
		}

		else if($action == 'ambil'){
			$post = $this->input->post();

			if(!empty($post['id'])){
				$record = $this->Organisasi_model->get($post['id']);
				$record->organisasi_slider = json_decode($record->organisasi_slider);

				foreach($record->organisasi_slider as $key => $val){
					if($key == 'post_image' && count($val) > 0){
						for ($x=0;$x<count($val);$x++){
							$array_post_image[$x]['tmb'] = $this->site->resize_img($val[$x],100,100,1);	
							$array_post_image[$x]['ori'] = $val[$x];	
						}

						$record->organisasi_slider->$key = $array_post_image;
					}
					else{
						$record->organisasi_slider->$key = $val;
					}
				}


				echo json_encode(array('status' => 'success', 'data' => $record));
			}
			else{
				$offset = NULL;
				
				if(!empty($post['hal_aktif']) && $post['hal_aktif'] > 1){
					$offset = ($post['hal_aktif'] - 1) * NULL ;
				}

				if(!empty($post['cari']) && ($post['cari'] != 'null')){
					$cari = $post['cari'];
					$total_rows = $this->Organisasi_model->count(array("organisasi_nama LIKE" => "%$cari%"));
					@$record = $this->Organisasi_model->get_by(array("organisasi_nama LIKE" => "%$cari%"),NULL, $offset, FALSE, NULL);
				}
				else{
					$record = $this->Organisasi_model->get_by(NULL,NULL,$offset,FALSE, NULL);	
					$total_rows = $this->Organisasi_model->count();						
				}

				echo json_encode(array(
					'data' => $record,
					'total_rows' => $total_rows, 
					'perpage' => NULL,
				) );					
			}	
		}

		else if($action == 'dashboard'){
			if(get_user_info('group') == 'admin'){

			$record = $this->db->query("select a.organisasi_nama, a.organisasi_id,count(b.kursus_id) jumlah_kursus, 
(select count(*) from tbl_log_kursus x where x.dk_kursusid = b.kursus_id) as jumlah_peserta,
(select sum(case when materi_type = 'video' then 1 else 0 end) video from tbl_materi yx where b.kursus_id = yx.materi_kursus) as jumlah_video,
(select sum(case when materi_type = 'file' then 1 else 0 end) file from tbl_materi yz where b.kursus_id = yz.materi_kursus) as jumlah_file,
(select sum(case when soal_type = 'ujian' then 1 else 0 end) ujian from tbl_soal zx where b.kursus_id = zx.soal_kursusid) as jumlah_ujian,
(select sum(case when soal_type = 'latihan' then 1 else 0 end) latihan from tbl_soal zy where b.kursus_id = zy.soal_kursusid) as jumlah_latihan,
(select sum(case when `group` = 'murid' then 1 else 0 end) user from tbl_user z where z.user_organisasi = a.organisasi_id) as jumlah_user
from tbl_organisasi a
left join
tbl_kursus b on a.organisasi_id = b.kursus_organisasi
group by a.organisasi_id, a.organisasi_nama")->result();
				// $record = $this->Organisasi_model->get_dashboard_op(NULL,NULL,NULL,FALSE, "count(kursus_id) as jumlah, organisasi_nama");
			}
			else{

			$record = $this->db->query("select a.organisasi_nama, a.organisasi_id,count(b.kursus_id) jumlah_kursus, 
(select count(*) from tbl_log_kursus x where x.dk_kursusid = b.kursus_id) as jumlah_peserta,
(select sum(case when materi_type = 'video' then 1 else 0 end) video from tbl_materi yx where b.kursus_id = yx.materi_kursus) as jumlah_video,
(select sum(case when materi_type = 'file' then 1 else 0 end) file from tbl_materi yz where b.kursus_id = yz.materi_kursus) as jumlah_file,
(select sum(case when soal_type = 'ujian' then 1 else 0 end) ujian from tbl_soal zx where b.kursus_id = zx.soal_kursusid) as jumlah_ujian,
(select sum(case when soal_type = 'latihan' then 1 else 0 end) latihan from tbl_soal zy where b.kursus_id = zy.soal_kursusid) as jumlah_latihan,
(select sum(case when `group` = 'murid' then 1 else 0 end) user from tbl_user z where z.user_organisasi = a.organisasi_id) as jumlah_user
from tbl_organisasi a
left join
tbl_kursus b on a.organisasi_id = b.kursus_organisasi
where organisasi_id = ".get_user_info('user_organisasi')."
group by a.organisasi_id, a.organisasi_nama")->result();
				// $record = $this->Organisasi_model->get_dashboard_op(array('organisasi_id'=>get_user_info('user_organisasi')),NULL,NULL,FALSE, "count(kursus_id) as jumlah, organisasi_nama");
			}
			echo json_encode(array(
				'data' => $record));
		}
		else if($action == 'dashboard-kursus'){
			if(get_user_info('group') == 'admin'){
				$record = $this->Kursus_model->get_dashboard_kursus(NULL,NULL,NULL,FALSE, "count(organisasi_id) jumlah_organisasi, count(dk_id) as jumlah_peserta, organisasi_nama, kursus_nama");
			}
			else{
				$record = $this->Kursus_model->get_dashboard_kursus(array('organisasi_id'=>get_user_info('user_organisasi')),NULL,NULL,FALSE, "count(organisasi_id) jumlah_organisasi, count(dk_id) as jumlah_peserta, organisasi_nama, kursus_nama");

			}
			echo json_encode(array(
				'data' => $record));

		}
		else if($action == 'profesi'){
			$data = $this->Profesi_model->get();
			$result = array('data' => $data);
			echo json_encode($result);
		}

		else if($action == 'hapus'){
			$post = $this->input->post();
			if(!empty($post['organisasi_id'])){
				$this->Organisasi_model->delete($post['organisasi_id']);
				// $this->Organisasi_model->delete_by(array('category_parent' => $post['category_id']));
				$result = array('status' => 'success');
			}

			echo json_encode($result);								
		}

		else if($action == 'sortir'){
			$post = $this->input->post(NULL, TRUE);
			foreach($post['ID'] as $sort => $id)
			$this->Kategori_model->update(array('category_sort' => $sort+1),array('category_ID' => $id));								
		}

		else if($action == 'upload'){

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
		else{

			$data = array();	
			$this->site->view('master/organisasi', $data);
		}
	}

	public function media($param='',$type=NULL){
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

	// public function 
	public function slider($action=''){
		global $SConfig;
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
			if($action == 'tambah' || $action == 'update'){
				$rules = $this->Slider_model->rules;
				$this->form_validation->set_rules($rules);

				if ($this->form_validation->run() == TRUE) {
					$post = $this->input->post();

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

			            $path_photo = $this->site->resize_img($filefullpath,1600,800,1);
			            $photo 		= $upload_data['file_name'];
					}
					/* SAAT EDIT PHOTO CONTENT KALAU ADA ISINYA*/
					elseif(!empty($post['path_photo'])){
			            $path_photo = $this->site->resize_img($post['path_photo'],1600,800,1);
					}
					else{
			            $path_photo = '';
					}


					$data = array (
							'slider_title'       => $post['slider_title'],
							'slider_description' => $post['slider_description'],
							'slider_type' 		 => 'slider',
							'slider_photo'       => $path_photo,
							'slider_created'     => date('Y-m-d h:i:s'),
							'slider_status'      => $post['slider_status'],
							'slider_userid'      => get_user_info('ID')
					);
					
					if(!empty($post['slider_id'])){
					
						$this->Slider_model->update($data, array('slider_id' => $post['slider_id']));
						$result = array('status' => 'success');	
					}
					else{
						if($this->Slider_model->insert($data)){
							$result = array('status' => 'success');	
						}		
						else{
							$result = array('status' => 'failed');	
						}			
					}

					echo json_encode($result);
				}
				else{
					echo json_encode(array('status' => 'failed','errors' => $this->form_validation->error_array()));
				}
			}

			else if($action == 'ambil'){
				$post = $this->input->post();

				if(!empty($post['id'])){
					$record = $this->Slider_model->get($post['id']);
					echo json_encode(array('status' => 'success', 'data' => $record));
				}
				else{
					$offset = NULL;
					
					if(!empty($post['hal_aktif']) && $post['hal_aktif'] > 1){
						$offset = ($post['hal_aktif'] - 1) * NULL ;
					}

					if(!empty($post['cari']) && ($post['cari'] != 'null')){
						$cari = $post['cari'];
						$total_rows = $this->Slider_model->count(array("slider_title LIKE" => "%$cari%", 'slider_type' => 'slider'));
						@$record = $this->Slider_model->get_by(array("slider_title LIKE" => "%$cari%", 'slider_type' => 'slider'),NULL, $offset, FALSE, NULL);
					}
					else{
						$record = $this->Slider_model->get_by(array('slider_type'=>'slider'),NULL,$offset,FALSE, NULL);	
						$total_rows = $this->Slider_model->count(array('slider_type' => 'slider'));						
					}

					echo json_encode(array(
						'data' => $record,
						'total_rows' => $total_rows, 
						'perpage' => NULL,
					) );					
				}	
			}

			else if($action == 'profesi'){
				$data = $this->Profesi_model->get();
				$result = array('data' => $data);
				echo json_encode($result);
			}

			else if($action == 'hapus'){
				$post = $this->input->post();
				if(!empty($post['slider_id'])){
					$this->Slider_model->delete($post['slider_id']);
					// $this->Slider_model->delete_by(array('category_parent' => $post['category_id']));
					$result = array('status' => 'success');
				}

				echo json_encode($result);								
			}

			else if($action == 'sortir'){
				$post = $this->input->post(NULL, TRUE);
				foreach($post['ID'] as $sort => $id)
				$this->Kategori_model->update(array('category_sort' => $sort+1),array('category_ID' => $id));								
			}
		}
		else{
			$data = array();	
			$this->site->view('master/slider', $data);	
		}	
	}

	public function related($action=''){
		global $SConfig;
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
			if($action == 'tambah' || $action == 'update'){
				$rules = $this->Slider_model->rules_related;
				$this->form_validation->set_rules($rules);

				if ($this->form_validation->run() == TRUE) {
					$post = $this->input->post();

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
					/* SAAT EDIT PHOTO CONTENT KALAU ADA ISINYA*/
					elseif(!empty($post['path_photo'])){
			            $path_photo = $post['path_photo'];
					}
					else{
			            $path_photo = '';
					}


					$data = array (
							'slider_organisasi'  => $post['related_organisasi'],
							'slider_type' 		 => 'related',
							'slider_photo'       => $path_photo,
							'slider_created'     => date('Y-m-d h:i:s'),
							'slider_status'      => $post['related_status'],
							'slider_userid'      => get_user_info('ID')
					);
					

					if(!empty($post['related_id'])){
					
						$this->Slider_model->update($data, array('slider_id' => $post['related_id']));
						$result = array('status' => 'success');	
					}
					else{
						$is_exist = $this->Slider_model->count(array('slider_organisasi' => $data['slider_organisasi']));
						if($is_exist > 0){
							$data['slider_organisasi'] = $data['slider_organisasi']. ' 2';
						}
						if($this->Slider_model->insert($data)){
							$result = array('status' => 'success');	
						}		
						else{
							$result = array('status' => 'failed');	
						}			
					}
					echo json_encode($result);
				}
				else{
					echo json_encode(array('status' => 'failed','errors' => $this->form_validation->error_array()));
				}
			}

			else if($action == 'ambil'){
				$post = $this->input->post();

				if(!empty($post['id'])){
					$record = $this->Slider_model->get($post['id']);
					echo json_encode(array('status' => 'success', 'data' => $record));
				}
				else{
					$offset = NULL;
					
					$record = $this->Slider_model->get_related(NULL,NULL,$offset,FALSE, NULL);	
					$total_rows = $this->Slider_model->count(array('slider_type' => 'related'));						

					echo json_encode(array(
						'data' => $record,
						'total_rows' => $total_rows, 
						'perpage' => NULL,
					) );					
				}	
			}

			else if($action == 'profesi'){
				$data = $this->Profesi_model->get();
				$result = array('data' => $data);
				echo json_encode($result);
			}

			else if($action == 'hapus'){
				$post = $this->input->post();
				if(!empty($post['related_id'])){
					$this->Slider_model->delete($post['related_id']);
					// $this->Slider_model->delete_by(array('category_parent' => $post['category_id']));
					$result = array('status' => 'success');
				}

				echo json_encode($result);								
			}

			else if($action == 'sortir'){
				$post = $this->input->post(NULL, TRUE);
				foreach($post['ID'] as $sort => $id)
				$this->Kategori_model->update(array('category_sort' => $sort+1),array('category_ID' => $id));								
			}
		}
		else{
			$data = array();	
			$this->site->view('master/related', $data);	
		}	
	}


	// public function 
	public function video($action=''){
		global $SConfig;

		if($action == 'tambah' || $action == 'update'){
			$post = $this->input->post();

			$data = array(
				'video_projectid' => $post['video_projectid'],
				'video_description' => $post['video_description'],
				'video_duration' => $post['video_duration'],
				'video_name' => $post['video_name'],
				'video_created' => date('Y-m-d H:i:s'),
				'video_thumbnail' => $post['video_thumbnail']
			);
			$this->Video_model->insert($data);
		}
		else if($action == 'ambil'){
				$post = $this->input->post();

				if(!empty($post['id'])){
					$record = $this->Video_model->get($post['id']);
					echo json_encode(array('status' => 'success', 'data' => $record));
				}
				else{
					$offset = NULL;
					
					if(!empty($post['hal_aktif']) && $post['hal_aktif'] > 1){
						$offset = ($post['hal_aktif'] - 1) * NULL ;
					}

					if(!empty($post['cari']) && ($post['cari'] != 'null')){
						$cari = $post['cari'];
						$total_rows = $this->Video_model->count(array("video_name LIKE" => "%$cari%"));
						@$record = $this->Video_model->get_by(array("video_name LIKE" => "%$cari%"),NULL, $offset, FALSE, NULL);
					}
					else{
						$record = $this->Video_model->get_by(NULL,NULL,$offset,FALSE, NULL);	
						$total_rows = $this->Video_model->count();						
					}

					echo json_encode(array(
						'data' => $record,
						'total_rows' => $total_rows, 
						'perpage' => NULL,
					) );					
				}	
			}
		else{
			
			$data = array();	
			$this->site->view('master/video', $data);	
		}
	}

	public function handleVideo($action = ''){
		if($action == 'tambah' || $action == 'update'){
			$post = $this->input->post();

			$data = array(
				'video_projectid' => $post['video_projectid'],
				'video_description' => $post['video_description'],
				'video_duration' => $post['video_duration'],
				'video_name' => $post['video_name'],
				'video_created' => date('Y-m-d H:i:s'),
				'video_thumbnail' => $post['video_thumbnail']
			);
			$this->Video_model->insert($data);
		}
	}




	public function action($param){
		global $SConfig;
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
			// 
			if($param == 'tambah' || $param == 'update'){
				$rules = $this->Artikel_model->rules;
				$this->form_validation->set_rules($rules);

				if($this->form_validation->run() == TRUE){
					$post = $this->input->post();

					/* ATRIBUT ARTIKEL DIMULAI DISINI */
					
					/* KATEGORI ARTIKEL */
					$category = 'tanpa-kategori';
					if(!empty($post['category_slug'])) $category = implode(",", $post['category_slug']) ;	

					/* ATRIBUT WAKTU */
					$post_date = $post['year'].'/'.$post['month'].'/'.$post['date'].' '.$post['hour'].':'.$post['minute'].':00';

					/* ATRIBUT KOMENTAR */
					$comment_status = ""; 
					$comment_notification = "";							
					if(!empty($post['comment_status'])) $comment_status = $post['comment_status'] ; 
					if(!empty($post['comment_notification'])) $comment_notification = $post['comment_notification'] ;

					/* ATRIBUT FEATURED IMAGES */
					if(!array_key_exists('featured_image', $post)){
						$featured_image = '';
						$featured_image_thumbnail = '';
					}
					else{
						$featured_image = $post['featured_image'];
						$featured_image_thumbnail = $post['featured_image_thumbnail'];
					}
					
					/* ATRIBUT SEO */
					$post_attribute = array(
							'comment_notification' => $comment_notification,
							'meta_title' => $post['meta_title'],
							'meta_keyword' => $post['meta_keyword'],
							'meta_description' => $post['meta_description'],
							'featured_image' => $featured_image,
							'featured_image_thumbnail' => $featured_image_thumbnail,							
						);

					/* ATRIBUT ARTIKEL BERAKHIR DISINI */

					$data = array(
							'post_author' => $post['post_author'],//get_user_info('ID'),
							'post_title' => $post['post_title'],
							'post_name' => url_title($post['post_title'], '-', TRUE),
							'post_content' => $post['post_content'],
							'post_date' => $post_date, // date('Y-m-d H:i:s')
							'post_type' => 'artikel',
							'post_category' => $category,
							'comment_status' => $comment_status,
							'post_attribute' => json_encode($post_attribute),
							'post_image' => @$post['featured_image']												
						);


					if(!empty($post['post_id'])){
						$this->Artikel_model->update($data, array('post_ID' => $post['post_id']));
						$result = array('status' => 'success');
					}
					else{
						/* jika ada judul artikel yang sama, maka berikan imbuhan 2 di belakangnya */
						$is_exist = $this->Artikel_model->count(array('post_title' => $data['post_title']));
						if($is_exist > 0){
							$data['post_title'] = $data['post_title'].' 2';
							$data['post_name'] = url_title($data['post_title'], '-', TRUE);
							unset($data['post_date']);
						}						
						$this->Artikel_model->insert($data);	
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
					$record = $this->Artikel_model->get($post['id']);
					$record->post_attribute = json_decode($record->post_attribute);
					echo json_encode(array('status' => 'success', 'data' => $record));
				}
				else{
					$total_rows = $this->Artikel_model->count();
					$offset = NULL;

					if(!empty($post['hal_aktif']) && $post['hal_aktif'] > 1 ){
						$offset = ($post['hal_aktif'] - 1) * NULL ;
					}
					else if(!empty($post['cari']) &&($post['cari'] != 'null')){
						$cari = $post['cari'];
						$total_rows = $this->Artikel_model->count(array("post_title LIKE" => "%$cari%"));
						@$record = $this->Artikel_model->get_by(array('post_type' => 'artikel', "post_title LIKE" => "%$cari%", "post_type" => 'artikel'),NULL, $offset);
					}

					else{
						$record = $this->Artikel_model->get_by(array('post_type' => 'artikel'),NULL, $offset);
					}
					

					echo json_encode(
							array(
									'total_rows' => $total_rows,
									'perpage' => NULL,
									'record' => $record,
									'all_category' => $this->Kategori_model->get_by(
										array('category_type' => 'artikel'), 
										NULL,NULL,FALSE, 'category_slug,category_name'
										)
								)

						);					
				}
			}

			else if($param == 'hapus'){
				$post = $this->input->post(NULL,TRUE);
				if(!empty($post['post_id'])){
					$this->Artikel_model->delete($post['post_id']);
					$result = array('status' => 'success');
				}

				echo json_encode($result);
			}

			else if($param == 'mass'){
				$post = $this->input->post(NULL,TRUE);
				if($post['mass_action_type'] == 'hapus'){
					if(count($post['post_id']) > 0){										
						foreach($post['post_id'] as $id)
						$this->Artikel_model->delete($id);
						$result = array('status' => 'success');
						echo json_encode($result);	
					}
				}
				else if($post['mass_action_type'] == 'pending' || $post['mass_action_type'] == 'publish'){
					if(count(@$post['post_id']) > 0){
						foreach($post['post_id'] as $id)
						$this->Artikel_model->update(array('post_status' => $post['mass_action_type']),array('post_ID' => $id));
						$result = array('status' => 'success');
						echo json_encode($result);
					}
				}				
			}
		}
	}

	public function kategori($action=''){
		global $SConfig;
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
			if($action == 'tambah' || $action == 'update'){
				$rules = $this->Kategori_model->rules;
				$this->form_validation->set_rules($rules);

				if ($this->form_validation->run() == TRUE) {
					$post = $this->input->post();

					$data = array(
							'category_name' => xss_clean($post['category_name']),
							'category_slug' => url_title($post['category_name'], '-', TRUE),
							'category_description' => $post['category_description'],
							'category_parent' => $post['category_parent'],
							'category_type' => 'artikel'
						);
					
					if(!empty($post['category_id'])){
						$this->Kategori_model->update($data, array('category_ID' => $post['category_id']));
					}
					else{
						/* jika ada kategori yang sama maka berikan imbuhan 2 dibelakangnya */
						$is_exist = $this->Kategori_model->count(array('category_name' => $data['category_name']));
						if($is_exist > 0){
							$data['category_name'] = $data['category_name'].' 2';
							$data['category_slug'] = url_title($data['category_name'], '-', TRUE);
						}						
						if($this->Kategori_model->insert($data)){
							$result = array('status' => 'success');	
						}		
						else{
							$result = array('status' => 'failed');	
						}			
					}

					echo json_encode($result);
				}
				else{
					echo json_encode(array('status' => 'failed'));

				}
			}

			else if($action == 'ambil'){
				$post = $this->input->post(NULL,TRUE);

				if(!empty($post['id'])){
					echo json_encode(array('data' => $this->Kategori_model->get($post['id'])));
				}
				else{
					$record = $this->Kategori_model->get_by(array('category_type' => 'artikel'));
					echo json_encode(array('record' => $record));	
				}													
			}

			else if($action == 'hapus'){
				$post = $this->input->post();
				if(!empty($post['category_id'])){
					$this->Kategori_model->delete($post['category_id']);
					$this->Kategori_model->delete_by(array('category_parent' => $post['category_id']));
					$result = array('status' => 'success');
				}

				echo json_encode($result);								
			}

			else if($action == 'sortir'){
				$post = $this->input->post(NULL, TRUE);
				foreach($post['ID'] as $sort => $id)
				$this->Kategori_model->update(array('category_sort' => $sort+1),array('category_ID' => $id));								
			}
		}
		else{
			$data = array();	
			$this->site->view('kategori_artikel', $data);	
		}	
	}	

}