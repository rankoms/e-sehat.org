<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kursus extends Backend_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model(array('Artikel_model', 'Kategori_model', 'Sertifikat_model', 'Kursus_model', 'Materi_model', 'Soal_model'));
	}

	public function index(){
		$data = array();
		$this->site->view('kursus', $data);
	}

	public function action($param){
		global $SConfig;
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
			// 
			if($param == 'tambah' || $param == 'update'){
				$rules = $this->Kursus_model->rules;
				$this->form_validation->set_rules($rules);

				if($this->form_validation->run() == TRUE){
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
						// http://localhost/ci_cms/uploads/courses/kemses.png
			            $path_photo = base_url().'uploads/courses/kemses.png';
					}

					/* ATRIBUT ARTIKEL BERAKHIR DISINI */

					$data = array(
							'kursus_nama'        => $post['kursus_nama'],//get_user_info('ID'),
							'kursus_slug'        => url_title($post['kursus_nama'], '-', TRUE),
							'kursus_organisasi'  => $post['kursus_organisasi'],
							'kursus_photo'       => $path_photo,
							'kursus_link'        => $post['kursus_link'],
							'kursus_skp'         => $post['kursus_skp'],
							'kursus_type_video'  => $post['kursus_type_video'],
							'kursus_video'       => $post['kursus_video'], // date('Y-m-d H:i:s')
							'kursus_pemateri'    => json_encode($post['kursus_pemateri']),
							'kursus_deskripsi'   => $post['kursus_deskripsi'],
							'kursus_masabelajar' => $post['kursus_masabelajar'],
							'kursus_nosk' 		 => $post['kursus_nosk'],
							'kursus_created'     => date('Y-m-d H:i:s'),
							'kursus_iduser'      => get_user_info('ID')										
						);


					if(!empty($post['kursus_id'])){
						unset($data['kursus_created']);
						$this->Kursus_model->update($data, array('kursus_id' => $post['kursus_id']));
						$result = array('status' => 'success');
					}
					else{				
						$this->Kursus_model->insert($data);	
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
					$record = $this->Kursus_model->get($post['id']);
					echo json_encode(array('status' => 'success', 'data' => $record));
				}
				else{
					if(get_user_info('group') == 'admin'){
						$record = $this->Kursus_model->get_kursus();
					}
					else {

						$record = $this->Kursus_model->get_kursus(array('organisasi_id'=>get_user_info('user_organisasi')));
					}


					echo json_encode(
							array(
									'data' => $record
								)

						);					
				}
			}

			else if($param == 'hapus'){
				$post = $this->input->post(NULL,TRUE);
				if(!empty($post['kursus_id'])){
					$this->Kursus_model->delete($post['kursus_id']);
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
	public function detail($action ='',$param = '', $id =''){
		if($action == 'modul'){
			if($param == 'ambil'){
				$post = $this->input->post();
				if($id !=''){
					$record = $this->Modul_model->get_kursus_modul(array('modul_kursus'=> $id),NULL,NULL,FALSE, NULL);	
					echo json_encode(array(
						'data' => $record,
					) );	

				}
				else if(!empty($post['id'])){
					$record = $this->Modul_model->get($post['id']);
					echo json_encode(array('data'=> $record));
				}
				// else if(!empty($post['kursus'])){
				// 	$record = $this->Modul_model->get_by(array('modul_kursus'=> $post['kursus']),NULL,NULL,FALSE, NULL);	
				// 	echo json_encode(array(
				// 		'data' => $record,
				// 	) );	
				// }
				else{
					$record = $this->Modul_model->get_by(NULL,NULL,NULL,FALSE, NULL);	
					echo json_encode(array(
						'data' => $record,
					) );	
				}		
			}
			else if($param == 'tambah' || $param == 'update'){
				$post = $this->input->post();
				$rules = $this->Modul_model->rules;
				$this->form_validation->set_rules($rules);
				if($this->form_validation->run() == TRUE){
					$data = array(
						'modul_kursus' 	=> $post['modul_kursus'],
						'modul_name' 	=> $post['modul_name'],
						'modul_title'	=> $post['modul_title'],
						'modul_slug'	=> url_title($post['modul_title'], '-', TRUE),
						'modul_created' => date('Y-m-d H:i:s'),
						'modul_iduser'	=> get_user_info('ID')
					);

					if($post['modul_id']){
						unset($data['modul_created']);
						unset($data['modul_kursus']);
						$this->Modul_model->update($data, array('modul_id'=> $post['modul_id']));
						echo json_encode(array('status' => 'success'));

					}
					else{
						$this->Modul_model->insert($data);
						echo json_encode(array('status' => 'success'));
					}
				}	
				else{
					echo json_encode(array('status' => 'failed','errors' => $this->form_validation->error_array()));
				}
			}
			else if ($param == 'hapus'){
				$post = $this->input->post(NULL,TRUE);
				if(!empty($post['modul_id'])){
					$this->Modul_model->delete($post['modul_id']);
					$result = array('status' => 'success');
				}

				echo json_encode($result);
			}
		}
		else if($action == 'video'){
			if($param == 'ambil'){
				$post = $this->input->post();
				if($id !=''){
					$record = $this->Materi_model->get_materi_kursus(array('materi_kursus'=> $id, 'materi_type' => 'video'),NULL,NULL,FALSE, NULL);	
					echo json_encode(array(
						'data' => $record,
						'query' => $this->db->last_query()
					) );	
				}
				else if (!empty($post['id'])){
					$record = $this->Materi_model->get($post['id']);
					echo json_encode(array('data'=> $record));
				}
				else{
					$record = $this->Materi_model->get_materi_kursus(array('materi_kursus'=> $id, 'materi_type' => 'video'),NULL,NULL,FALSE, NULL);
					echo json_encode(array(
						'data' => $record,
						'query' => $this->db->last_query()
					) );	
				}

			}

			if($param == 'tambah' || $param == 'update'){
				$post = $this->input->post();
				$rules = $this->Materi_model->rules_video;
				$this->form_validation->set_rules($rules);
				if($this->form_validation->run() == TRUE){

					$data = array(
						'materi_projectid' => $post['materi_projectid'],
						'materi_videoid' => $post['materi_videoid'],
						'materi_duration' => $post['materi_duration'],
						'materi_nama' => $post['materi_nama'],
						'materi_slug' => url_title($post['materi_nama'], '-', TRUE),
						'materi_created' => date('Y-m-d H:i:s'),
						'materi_thumbnail' => $post['materi_thumbnail'],
						'materi_type'	=> 'video',
						'materi_iduser' => get_user_info('ID'),
						'materi_modul'      => $post['materi_modul_video'],
						'materi_kursus'     => $post['materi_kursus']
					);

					if($post['materi_id']){

						$data = array(
								'materi_projectid'  => $post['materi_projectid'],
								'materi_videoid'    => $post['materi_videoid'],
								'materi_duration'   => $post['materi_duration'],
								'materi_nama'       => $post['materi_nama'],
								'materi_thumbnail'  => $post['materi_thumbnail'],
								'materi_type'       => 'video',
								'materi_iduser'     => get_user_info('ID'),
								'materi_modul'      => $post['materi_modul_video'],
								'materi_kursus'     => $post['materi_kursus']
						);
						$this->Materi_model->update($data, array('materi_id' => $post['materi_id']));
						$result = array('status' => 'success', 'query' => $this->db->last_query());
					}
					else{
						$this->Materi_model->insert($data);
						$result = array('status' => 'success', 'query' => $this->db->last_query());
					}
					echo json_encode($result);
				}
				else{
					$result = array('status' => 'failed', 'errors' => $this->form_validation->error_array());
					echo json_encode($result);
				}
			}

			else if ($param == 'hapus'){
				$post = $this->input->post(NULL,TRUE);
				$data['materi'] = $this->Materi_model->get($post['materi_id']);
				if(!empty($post['materi_id'])){
					$this->Materi_model->delete($post['materi_id']);
					$result = array('status' => 'success', 'action'=>'hapus', 'data'=> $data);
				}

				echo json_encode($result);
			}
		}

		else if($action == 'file'){
			if($param == 'ambil'){
				$post = $this->input->post();
				if($id !=''){
					$record = $this->Materi_model->get_materi_kursus(array('materi_kursus'=> $id, 'materi_type' => 'file'),NULL,NULL,FALSE, NULL);	
					echo json_encode(array(
						'data' => $record,
						'query' => $this->db->last_query()
					) );	
				}
				else if(!empty($post['id'])){
					$record = $this->Materi_model->get($post['id']);
					echo json_encode(array('data'=> $record));
				}
				else{
					$record = $this->Materi_model->get_materi_kursus(array('materi_kursus'=> $id, 'materi_type' => 'file'),NULL,NULL,FALSE, NULL);
					echo json_encode(array(
						'data' => $record,
						'query' => $this->db->last_query()
					) );	
				}
			}
			if($param == 'cek'){
				if($id != ''){
					$record = $this->Materi_model->get($id);	
					echo json_encode(array(
						'data' => $record,
						'query' => $this->db->last_query()
					) );	

				}
			}

			if($param == 'tambah' || $param == 'update'){
				$post = $this->input->post();
				$rules = $this->Materi_model->rules_materi;
				$this->form_validation->set_rules($rules);

				if($this->form_validation->run() == TRUE){
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

					$data = array(
						'materi_kursus' => $post['materi_kursus'],
						'materi_modul' => $post['materi_modul_file'],
						'materi_nama' => $post['materi_nama_file'],
						'materi_created' => date('Y-m-d H:i:s'),
						'materi_thumbnail' => $path_photo,
						'materi_type'	=> 'file',
						'materi_iduser' => get_user_info('ID')
					);

					if($post['materi_id2']){
						unset($data['materi_created']);
						$this->Materi_model->update($data, array('materi_id' => $post['materi_id2']));
						$result = array('status' => 'success', 'query' => $this->db->last_query());
					}
					else{
						$this->Materi_model->insert($data);
						$result = array('status' => 'success', 'query' => $this->db->last_query());
					}
					echo json_encode($result);
				}
				else{
					$result = array('status' => 'failed', 'errors' => $this->form_validation->error_array());
					echo json_encode($result);
				}
			}

			else if ($param == 'hapus'){
				$post = $this->input->post(NULL,TRUE);
				if(!empty($post['materi_id2'])){
					$this->Materi_model->delete($post['materi_id2']);
					$result = array('status' => 'success');
				}

				echo json_encode($result);
			}
		}
		else if($action == 'kuis'){
			if($param == 'ambil'){
				$post = $this->input->post();
				if($id !=''){
					$record = $this->Soal_model->get_kuis_kursus(array('materi_kursus'=> $id, 'soal_type' => 'kuis'),NULL,NULL,FALSE, NULL);	
					echo json_encode(array(
						'data' => $record,
						'query' => $this->db->last_query()
					) );	
				}
				else if (!empty($post['id'])){
					$record = $this->Soal_model->get($post['id']);
					echo json_encode(array('data'=> $record));
				}
				else{
					$record = $this->Soal_model->get_kuis_kursus(array('materi_kursus'=> $id, 'soal_type' => 'kuis'),NULL,NULL,FALSE, NULL);
					echo json_encode(array(
						'data' => $record,
						'query' => $this->db->last_query()
					) );	
				}

			}

			if($param == 'tambah' || $param == 'update'){
				$post = $this->input->post();
				$rules = $this->Soal_model->rules_kuis;
				$this->form_validation->set_rules($rules);
				if($this->form_validation->run() == TRUE){

					/* ARTIBUT UNTUK FOTO */
					$this->site->create_dir();
					$this->load->library('upload', $this->site->media_upload_config());
					$date = date('Y-m-d H:i:s');
					$yeardir = date('Y');
					$monthdir = date('M');
					$datedir = date('d');

		 			$upload_data = $this->upload->data();
			        if($this->upload->do_upload("file2")){ //upload file
		 				$upload_data = $this->upload->data();
						$filefullpath = base_url().'uploads/'.$yeardir.'/'.$monthdir.'/'.$datedir.'/'.get_user_info('ID').'/'.$upload_data['file_name'];
			            $path_photo = $filefullpath;
					}
					/* SAAT EDIT PHOTO CONTENT KALAU ADA ISINYA*/
					elseif(!empty($post['path_photo2'])){
			            $path_photo = $post['path_photo2'];
					}
					else{
			            $path_photo = '';
					}
					$modul = $this->Materi_model->get($post['soal_materiid']);
					$data = array(
						'soal_materiid'   => $post['soal_materiid'],
						'soal_kursusid'   => $post['soal_kursusid'],
						'soal_modulid'    => $modul->materi_modul,
						'soal_soal'       => $post['soal_soal'],
						'soal_a'          => $post['soal_a'],
						'soal_b'          => $post['soal_b'],
						'soal_c'          => $post['soal_c'],
						'soal_d'          => $post['soal_d'],
						'soal_e'          => $post['soal_e'],
						'soal_time'       => get_time($post['soal_time']),
						'soal_duration'   => $post['soal_duration'],
						'soal_jawaban'    => $post['soal_jawaban'],
						'soal_type'       =>  'kuis',
						'soal_photo'      => $path_photo,
						'soal_lastupdate' => date('Y-m-d H:i:s'),
						'soal_iduser'     => get_user_info('ID')
					);

					if($post['soal_id']){
						if($post['soal_time'] == ''){
							unset($data['soal_time']);
						}
						$this->Soal_model->update($data, array('soal_id' => $post['soal_id']));
						$result = array('status' => 'success', 'query' => $this->db->last_query());
					}
					else{
						$this->Soal_model->insert($data);
						$result = array('status' => 'success', 'query' => $this->db->last_query());
					}
					echo json_encode($result);
				}
				else{
					$result = array('status' => 'failed', 'errors' => $this->form_validation->error_array());
					echo json_encode($result);
				}
			}
			
			
			else if ($param == 'hapus'){
				$post = $this->input->post(NULL,TRUE);
				if(!empty($post['soal_id'])){
					$this->Soal_model->delete($post['soal_id']);
					$result = array('status' => 'success');
				}

				echo json_encode($result);
			}
		}

		else if($action == 'latihan'){
			if($param == 'ambil'){
				$post = $this->input->post();
				if($id !=''){
					$record = $this->Soal_model->get_kuis_kursus(array('materi_kursus'=> $id, 'soal_type' =>'latihan'),NULL,NULL,FALSE, NULL);	
					echo json_encode(array(
						'data' => $record,
						'query' => $this->db->last_query()
					) );	
				}
				else if (!empty($post['id'])){
					$record = $this->Soal_model->get($post['id']);
					echo json_encode(array('data'=> $record));
				}
				else{
					$record = $this->Soal_model->get_kuis_kursus(array('materi_kursus'=> $id, 'soal_type' =>'latihan'),NULL,NULL,FALSE, NULL);
					echo json_encode(array(
						'data' => $record,
						'query' => $this->db->last_query()
					) );	
				}

			}

			if($param == 'tambah' || $param == 'update'){
				$post = $this->input->post();
				$rules = $this->Soal_model->rules_latihan;
				$this->form_validation->set_rules($rules);
				if($this->form_validation->run() == TRUE){

					/* ARTIBUT UNTUK FOTO */
					$this->site->create_dir();
					$this->load->library('upload', $this->site->media_upload_config());
					$date = date('Y-m-d H:i:s');
					$yeardir = date('Y');
					$monthdir = date('M');
					$datedir = date('d');

		 			$upload_data = $this->upload->data();
			        if($this->upload->do_upload("file3")){ //upload file
		 				$upload_data = $this->upload->data();
						$filefullpath = base_url().'uploads/'.$yeardir.'/'.$monthdir.'/'.$datedir.'/'.get_user_info('ID').'/'.$upload_data['file_name'];
			            $path_photo = $filefullpath;
					}
					/* SAAT EDIT PHOTO CONTENT KALAU ADA ISINYA*/
					elseif(!empty($post['path_photo3'])){
			            $path_photo = $post['path_photo3'];
					}
					else{
			            $path_photo = '';
					}

					$data = array(
						'soal_materiid'   => $post['soal_materiid_lat'],
						'soal_kursusid'   => $post['soal_kursusid_lat'],
						'soal_modulid'    => $post['soal_modulid_lat'],
						'soal_soal'       => $post['soal_soal_lat'],
						'soal_a'          => $post['soal_a_lat'],
						'soal_b'          => $post['soal_b_lat'],
						'soal_c'          => $post['soal_c_lat'],
						'soal_d'          => $post['soal_d_lat'],
						'soal_e'          => $post['soal_e_lat'],
						'soal_jawaban'    => $post['soal_jawaban_lat'],
						'soal_type'       =>  'latihan',
						'soal_photo'      => $path_photo,
						'soal_lastupdate' => date('Y-m-d H:i:s'),
						'soal_iduser'     => get_user_info('ID')
					);

					if($post['soal_id_lat']){
						$this->Soal_model->update($data, array('soal_id' => $post['soal_id_lat']));
						$result = array('status' => 'success', 'query' => $this->db->last_query());
					}
					else{
						$this->Soal_model->insert($data);
						$result = array('status' => 'success', 'query' => $this->db->last_query());
					}
					echo json_encode($result);
				}
				else{
					$result = array('status' => 'failed', 'errors' => $this->form_validation->error_array());
					echo json_encode($result);
				}
			}
			else if($param == 'hapus'){
				
				$post = $this->input->post(NULL,TRUE);
				if(!empty($post['soal_id_lat'])){
					$this->Soal_model->delete($post['soal_id_lat']);
					$result = array('status' => 'success');
				}

				echo json_encode($result);
			}
		}

		else if($action == 'ujian'){
			if($param == 'ambil'){
				$post = $this->input->post();
				if($id !=''){
					$record = $this->Soal_model->get_ujian_kursus(array('soal_kursusid'=> $id, 'soal_type' =>'ujian'),NULL,NULL,FALSE, NULL);	
					echo json_encode(array(
						'data' => $record,
						'query' => $this->db->last_query()
					) );	
				}

				else if (!empty($post['id'])){
					$record = $this->Soal_model->get($post['id']);
					echo json_encode(array('data'=> $record));
				}

			}

			if($param == 'tambah' || $param == 'update'){
				$post = $this->input->post();
				$rules = $this->Soal_model->rules_ujian;
				$this->form_validation->set_rules($rules);
				if($this->form_validation->run() == TRUE){

					/* ARTIBUT UNTUK FOTO */
					$this->site->create_dir();
					$this->load->library('upload', $this->site->media_upload_config());
					$date = date('Y-m-d H:i:s');
					$yeardir = date('Y');
					$monthdir = date('M');
					$datedir = date('d');

		 			$upload_data = $this->upload->data();
			        if($this->upload->do_upload("file4")){ //upload file
		 				$upload_data = $this->upload->data();
						$filefullpath = base_url().'uploads/'.$yeardir.'/'.$monthdir.'/'.$datedir.'/'.get_user_info('ID').'/'.$upload_data['file_name'];
			            $path_photo = $filefullpath;
					}
					/* SAAT EDIT PHOTO CONTENT KALAU ADA ISINYA*/
					elseif(!empty($post['path_photo4'])){
			            $path_photo = $post['path_photo4'];
					}
					else{
			            $path_photo = '';
					}

					$data = array(
						'soal_kursusid'   => $post['soal_kursusid_ujian'],
						'soal_modulid'    => $post['materi_modul_ujian'],
						'soal_soal'       => $post['soal_soal_ujian'],
						'soal_a'          => $post['soal_a_ujian'],
						'soal_b'          => $post['soal_b_ujian'],
						'soal_c'          => $post['soal_c_ujian'],
						'soal_d'          => $post['soal_d_ujian'],
						'soal_e'          => $post['soal_e_ujian'],
						'soal_jawaban'    => $post['soal_jawaban_ujian'],
						'soal_type'       =>  'ujian',
						'soal_photo'      => $path_photo,
						'soal_lastupdate' => date('Y-m-d H:i:s'),
						'soal_iduser'     => get_user_info('ID')
					);

					if($post['soal_id_ujian']){
						$this->Soal_model->update($data, array('soal_id' => $post['soal_id_ujian']));
						$result = array('status' => 'success', 'query' => $this->db->last_query());
					}
					else{
						$this->Soal_model->insert($data);
						$result = array('status' => 'success', 'query' => $this->db->last_query());
					}
					echo json_encode($result);
				}
				else{
					$result = array('status' => 'failed', 'errors' => $this->form_validation->error_array());
					echo json_encode($result);
				}
			}
			else if($param == 'hapus'){
				
				$post = $this->input->post(NULL,TRUE);
				if(!empty($post['soal_id_ujian'])){
					$this->Soal_model->delete($post['soal_id_ujian']);
					$result = array('status' => 'success');
				}

				echo json_encode($result);
			}
		}
		else{
			$data['data'] = $this->Kursus_model->get($_GET['id']);
			$this->site->view('detail_kursus', $data);
		}
	}
	public function sertifikat($param = ''){
		global $SConfig;
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
			if($param == 'tambah' || $param == 'update'){
				$rules = $this->Sertifikat_model->rules;
				$this->form_validation->set_rules($rules);

				if($this->form_validation->run() == TRUE){
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

			        if($this->upload->do_upload("file2")){ //upload file
		 				$upload_data = $this->upload->data();
						$filefullpath = base_url().'uploads/'.$yeardir.'/'.$monthdir.'/'.$datedir.'/'.get_user_info('ID').'/'.$upload_data['file_name'];
			            $path_photo2 = $filefullpath;
					}
					/* SAAT EDIT PHOTO CONTENT KALAU ADA ISINYA*/
					elseif(!empty($post['path_photo2'])){
			            $path_photo2 = $post['path_photo2'];
					}
					else{
			            $path_photo2 = '';
					}


					/* ATRIBUT ARTIKEL BERAKHIR DISINI */

					$data = array(
							'sertifikat_kursus'      => $post['sertifikat_kursus'],
							'sertifikat_pemimpin'    => $post['sertifikat_pemimpin'],
							'sertifikat_nomor_sk'    => $post['sertifikat_nomor_sk'],
							'sertifikat_nomor'       => $post['sertifikat_nomor'],
							'sertifikat_nomor_skp'   => $post['sertifikat_nomor_skp'],
							// 'sertifikat_instruktur'  => json_encode($post['sertifikat_instruktur']),
							'sertifikat_video'		 => $post['sertifikat_video'],
							'sertifikat_kuis'		 => $post['sertifikat_kuis'],
							'sertifikat_latihan'	 => $post['sertifikat_latihan'],
							'sertifikat_ujian'		 => $post['sertifikat_ujian'],
							// 'sertifikat_pemateri' => $post['sertifikat_pemateri'],
							'sertifikat_logo'        => $path_photo,
							'sertifikat_ttd'         => $path_photo2,
							'sertifikat_iduser'      => get_user_info('ID'),
							'sertifikat_created'  	 => date('Y-m-d H:i:s')
						);


					if(!empty($post['sertifikat_id'])){
						unset($data['sertifikat_created']);
						$this->Sertifikat_model->update($data, array('sertifikat_id' => $post['sertifikat_id']));
						$result = array('status' => 'success');
					}
					else{
						if($this->Sertifikat_model->count(array('sertifikat_kursus' => $post['sertifikat_kursus'])) >=1) 
						{
							$result = array('status' => 'failed', 'organisasi' => 'error');	
						}
						else
						{
							$this->Sertifikat_model->insert($data);	
							$result = array('status' => 'success');	

						}						
					}

				}
				else{
					$result = array('status' => 'failed', 'errors' => $this->form_validation->error_array());
				}

				echo json_encode($result);
			}
			else if($param == 'ambil'){

				$post = $this->input->post();

				if(!empty($post['id'])){
					$record = $this->Sertifikat_model->get($post['id']);
					echo json_encode(array('status' => 'success', 'data' => $record));
				}
				else{
					if(get_user_info('group') == 'adminop' || get_user_info('group') == 'instructor'){
						$record = $this->Sertifikat_model->get_sertifikat(array('organisasi_id'=> get_user_info('user_organisasi')));
					}
					else{

						$record = $this->Sertifikat_model->get_sertifikat();	
					}


					echo json_encode(array(
						'data' => $record,
					) );					
				}			
			}
			else if($param == 'hapus'){
				
				$post = $this->input->post(NULL,TRUE);
				if(!empty($post['sertifikat_id'])){
					$this->Sertifikat_model->delete($post['sertifikat_id']);
					$result = array('status' => 'success');
				}

				echo json_encode($result);
			}
		}
		else{
			$data = array();
			$this->site->view('sertifikat', $data);
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

	public function preview($id){

		$data = $this->Sertifikat_model->get_sertifikat(array('sertifikat_id'=>$id));
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
		$nomerfull = '0001/'.$data[0]->sertifikat_nomor.'/'.getRomawi(date('n')).'/'.date('Y');
		// $nosk = 'SK-'.substr($nomerfull, 5);
		$nosk = $data[0]->kursus_nosk;
		$noskp = $data[0]->kursus_skp;
		$nama = $_SESSION['nama'];
		$namaketua = $data[0]->organisasi_pimpinan;
		$kursus = $data[0]->kursus_nama;
		$signature = $data[0]->organisasi_signature;
		$namaorganisasi = $data[0]->organisasi_nama;
		$tanggal = tgl_ind(date('Y-m-d H:i:s'));

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