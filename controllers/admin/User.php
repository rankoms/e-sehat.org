<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends Backend_Controller {
	
	// http://stackoverflow.com/questions/19706046/how-to-read-an-external-local-json-file-in-javascript
	
	protected $user_detail;

	public function __construct(){
		parent::__construct();		
		$this->load->model(array('User_model', 'User_detail_model'));
	}

	public function index(){
		if(get_user_info('group') == 'instruktur' || get_user_info('group') == 'adminop'){
			redirect('admin/user/'.get_user_info('group'));
		}
		$this->site->view('user', $data);		
	}

	public function login(){
		
		/* tahap 3 finishing */
		$post = $this->input->post(NULL, TRUE);
		
		if(isset($post['username']) ){ 
			$this->user_detail = $this->User_model->get_by(array('username' => $post['username'], 'group !=' => 'murid'), 1, NULL, TRUE);
		}

		$this->form_validation->set_message('required', '%s kosong, tolong diisi!');

		$rules = $this->User_model->rules;
		$this->form_validation->set_rules($rules);	

		if ($this->form_validation->run() == FALSE){	
			$this->site->view('login');
        }
        else{
			$login_data = array(
					'ID' => $this->user_detail->ID,
			        'username'  => $post['username'],			        
			        'nama'  => $this->user_detail->nama,			        
			        'logged_in' => TRUE,
			        'active' => $this->user_detail->active,
			        'last_login' => $this->user_detail->last_login,
			        'group' => $this->user_detail->group,
			        'email' => $this->user_detail->email,
			);						

			$this->session->set_userdata($login_data);

			if(isset($post['remember']) ){
				$expire = time() + (86400 * 7);
				set_cookie('username', $post['username'], $expire , "/");
				set_cookie('password', $post['password'], $expire , "/" );
			}
			
			redirect(set_url('dashboard'));
        }
    }

	public function logout(){
		$this->session->sess_destroy();
		// delete_cookie('username'); delete_cookie('password');
		redirect(set_url('/'));
	}

	public function password_check($str){
    	$user_detail =  $this->user_detail;  	
    	if (@$user_detail->password == crypt($str,@$user_detail->password)){
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

	public function action($param){
		global $SConfig;
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
			
			if($param == 'ambil'){
				$post = $this->input->post();

				if(!empty($post['id'])){
					$record = $this->User_model->get_user_detail($post['id']);
					echo json_encode(array('status' => 'success', 'data' => $record));
				}
				else{
					$offset = NULL;
					
					if(!empty($post['hal_aktif']) && $post['hal_aktif'] > 1){
						$offset = ($post['hal_aktif'] - 1) * NULL ;
					}

					if(!empty($post['cari']) && ($post['cari'] != 'null')){
						$cari = $post['cari'];
						$total_rows = $this->User_model->count(array("username LIKE" => "%$cari%"));
						@$record = $this->User_model->get_user(array("username LIKE" => "%$cari%"),NULL, $offset, FALSE, "count(post_ID) as jumlah_post, ID, username, group, email, active");
					}
					else{
						$record = $this->User_model->get_user(NULL,NULL,$offset,FALSE, "count(post_ID) as jumlah_post, ID, username, group, email, active, nama, path_photo");	
						$total_rows = $this->User_model->count();						
					}

					echo json_encode(array(
						'data' => $record,
						'total_rows' => $total_rows, 
						'perpage' => NULL,
					) );					
				}			
			}
			else if($param == 'tambah' || $param == 'update'){

				if($param == 'update'){
					$rules = $this->User_model->rules_update;					
				}
				else{
					$rules = $this->User_model->rules_register;
				}
				
				$this->form_validation->set_rules($rules);

				if ($this->form_validation->run() == TRUE) {
					$group = 'user';
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

					$data = array(
							'username' => $post['username'],
							'nama' => $post['nama'],							
							'password' => md5($post['password']),		
							// 'password' => bCrypt($post['password'],12),					
							'group' => (!empty($post['group'])) ? $group = $post['group'] : $group = '',
							'email' => $post['email'],		
							'created_on' => date('Y-m-d H:i:s'),					
							'active' => 1,
							'path_photo' => $path_photo
						);

					unset($data['active']);

					if($param == 'update'){
						// unset($data['username']);
						// unset($data['email']);
						unset($data['created_on']);
						
						if(!empty($post['password'])) { 
							$data['password'] = md5($post['password']);
							// $data['password'] = bCrypt($post['password'],12);
						}
						else{
							unset($data['password']);
						}

						$this->User_model->update($data, array('ID' => $post['user_id']));
						$getID = $post['user_id'];
					}
					else{
						$getID = $this->User_model->insert($data);
					}
					$result = array('status' => 'success', 'query' => $this->db->last_query());
				}
				else{
					$result = array('status' => 'failed', 'errors' => $this->form_validation->error_array());
				}

				echo json_encode($result);			
			}

			else if($param == 'hapus'){
				$post = $this->input->post();
				if(!empty($post['user_id'])){
					$this->User_model->delete($post['user_id']);
					$result = array('status' => 'success');
				}

				echo json_encode($result);
			}

			else if($param == 'mass'){
				$post = $this->input->post(NULL,TRUE);
				if($post['mass_action_type'] == 'hapus'){
					if(count($post['user_id']) > 0){										
						foreach($post['user_id'] as $id)
						$this->User_model->delete($id);
						$result = array('status' => 'success');
						echo json_encode($result);	
					}
				}
				else if($post['mass_action_type'] == 'non-aktifkan' || $post['mass_action_type'] == 'aktifkan'){
					if(count(@$post['user_id']) > 0){
						if($post['mass_action_type'] == 'aktifkan'){
							$active = 1;
						}
						else{
							$active = 0;
						}
						foreach($post['user_id'] as $id)
						$this->User_model->update(array('active' => $active),array('ID' => $id));
						$result = array('status' => 'success');
						echo json_encode($result);
					}
				}
			}			

		}
	}

	public function email_check($str){
		/* bisa digunakan untuk mengecek ke dalam database nantinya */
		if ($this->User_model->count(array('email' => $str)) > 0){
            $this->form_validation->set_message('email_check', 'Email sudah digunakan, mohon diganti yang lain...');
            return FALSE;
        }
        else{
            return TRUE;
        }
	}	

	public function username_check($str){
		/* bisa digunakan untuk mengecek ke dalam database nantinya */
		if ($this->User_model->count(array('username' => $str)) > 0){
            $this->form_validation->set_message('username_check', 'Username sudah digunakan, mohon diganti yang lain...');
            return FALSE;
        }
        else{
            return TRUE;
        }
	}	

	public function murid($param=''){
		global $SConfig;
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
			if($param == 'ambil'){
				$post = $this->input->post();

				if(!empty($post['id'])){
					$record = $this->User_model->get($post['id']);
					echo json_encode(array('status' => 'success', 'data' => $record));
				}
				else{
					$offset = NULL;
					
					if(!empty($post['hal_aktif']) && $post['hal_aktif'] > 1){
						$offset = ($post['hal_aktif'] - 1) * NULL ;
					}

					if(!empty($post['cari']) && ($post['cari'] != 'null')){
						$cari = $post['cari'];
						$total_rows = $this->User_model->count(array("username LIKE" => "%$cari%"));
						@$record = $this->User_model->get_user_murid(array("username LIKE" => "%$cari%"),NULL, $offset, FALSE, "count(post_ID) as jumlah_post, ID, username, group, email, active");
					}
					else{
						if(get_user_info('group') == 'admin'){

							$record = $this->User_model->get_user_murid(array('group'=>'murid'),NULL,$offset,FALSE, NULL);	
						}
						else{
							$record = $this->User_model->get_user_murid(array('user_organisasi'=> get_user_info('user_organisasi'), 'group'=> 'murid'),NULL,$offset,FALSE, NULL);	
						}					
					}

					echo json_encode(array(
						'data' => $record,
					) );					
				}			
			}
			else if($param == 'tambah' || $param == 'update'){
				$post = $this->input->post();
				if($param == 'update'){
					// $rules = $this->User_model->rules_update;
					if($post['user_type'] == 'SERTIFIKAT'){
						$rules = $this->User_model->rules_update_sertifikat;
					}
					else{
						$rules = $this->User_model->rules_update_belajar;
					}	

				}
				else{
					if($post['user_type'] == 'SERTIFIKAT'){
						$rules = $this->User_model->rules_register_sertifikat;
					}
					else{
						$rules = $this->User_model->rules_register_belajar;
					}
					
				}
				$this->form_validation->set_rules($rules);

				if ($this->form_validation->run() == TRUE) {
					$group = 'user';
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
			            $path_photo = base_url().'uploads/user/user.jpg';
					}

					$data = array(
							'username' => $post['username'],
							'nama' => $post['nama'],
							'password' => md5($post['password']),							
							// 'password' => bCrypt($post['password'],12),							
							'group' => 'murid',
							'email' => $post['email'],		
							'created_on' => date('Y-m-d H:i:s'),
							'user_organisasi' => $post['user_organisasi'],
							'user_str' => $post['user_str'],
							'user_nik' => $post['user_nik'],
							'user_type' => $post['user_type'],		
							'active' => 1,
							'path_photo' => $path_photo
						);

					unset($data['active']);

					if($param == 'update'){
						// unset($data['username']);
						// unset($data['email']);
						unset($data['created_on']);
						
						if(!empty($post['password'])) { 
							$data['password'] = md5($post['password']);
							// $data['password'] = bCrypt($post['password'],12);
						}
						else{
							unset($data['password']);
						}

						$this->User_model->update($data, array('ID' => $post['user_id']));
						$getID = $post['user_id'];
					}
					else{
						$getID = $this->User_model->insert($data);
					}
					$result = array('status' => 'success', 'query' => $this->db->last_query());
				}
				else{
					$result = array('status' => 'failed', 'errors' => $this->form_validation->error_array());
				}

				echo json_encode($result);			
			}

			else if($param == 'hapus'){
				$post = $this->input->post();
				if(!empty($post['user_id'])){
					$this->User_model->delete($post['user_id']);
					$result = array('status' => 'success');
				}

				echo json_encode($result);
			}
			else if($param == 'aktivasi'){
				$post = $this->input->post();
				if(!empty($post['user_id'])){
					$this->User_model->update(array('active'=>1), array('ID' => $post['user_id']));
					$result = array('status' => 'success');
				}


				/* KIRIM EMAIL */

           /*    $this->load->library('email');
                
				$this->email->initialize(array(
				  'protocol' => 'smtp',
				  'smtp_host' => 'smtp.sendgrid.net',
				  'smtp_user' => 'babastudio2019',
				  'smtp_pass' => '123qweasd',
				  'smtp_port' => 587,
				  'crlf' => "\r\n",
				  'newline' => "\r\n"
				));

                $this->email->set_mailtype("html");
                $this->email->from('no-reply@e-sehat.org', 'Sekertariat KTKI');
                $this->email->to($post['email_user']);
               
                $this->email->subject('Aktivasi Akun Admin Berhasil');
                $this->email->message('Selamat Akun anda berhasil Teraktivasi, Silahkan Link berikut untuk Login<a href="'.base_url().'login'.'">Klik disini</a> 
	                <br><br>-------------<br>
	        <b>Sekertariat Konsil Tenaga Kesehatan Indonesia <br>Gedung Badan PPSDM Kesehatan Lantai 8</b><br>
	        JL. HANG JEBAT III BLOK F3, <br> KEBAYORAN BARU JAKARTA SELATAN 12120');
                
                $this->email->send();
                */
				echo json_encode($result);
			}

			else if($param == 'mass'){
				$post = $this->input->post(NULL,TRUE);
				if($post['mass_action_type'] == 'hapus'){
					if(count($post['user_id']) > 0){										
						foreach($post['user_id'] as $id)
						$this->User_model->delete($id);
						$result = array('status' => 'success');
						echo json_encode($result);	
					}
				}
				else if($post['mass_action_type'] == 'non-aktifkan' || $post['mass_action_type'] == 'aktifkan'){
					if(count(@$post['user_id']) > 0){
						if($post['mass_action_type'] == 'aktifkan'){
							$active = 1;
						}
						else{
							$active = 0;
						}
						foreach($post['user_id'] as $id)
						$this->User_model->update(array('active' => $active),array('ID' => $id));
						$result = array('status' => 'success');
						echo json_encode($result);
					}
				}
			}	
		}
		else{
			$data = array();	
			$this->site->view('murid', $data);	
		}	
	}
	public function adminop($param=''){
		if(get_user_info('group') == 'instruktur'){
			redirect('admin/dashboard');
		}
		global $SConfig;
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
			if($param == 'ambil'){
				$post = $this->input->post();

				if(!empty($post['id'])){
					$record = $this->User_model->get_user_detail($post['id']);
					echo json_encode(array('status' => 'success', 'data' => $record));
				}
				else{
					if(get_user_info('group') == 'admin'){
						$record = $this->User_model->get_user_adminop(array('group' => 'adminop'));
					}
					else{
						$record = $this->User_model->get_user_adminop(array('group' => 'adminop', 'ID'=> get_user_info('ID'), 'user_organisasi'=> get_user_info('user_organisasi')));
					}	
					echo json_encode(array(
						'data' => $record,
					) );					
				}			
			}
			else if($param == 'tambah' || $param == 'update'){

				if($param == 'update'){
					$rules = $this->User_model->rules_update;					
				}
				else{
					$rules = $this->User_model->rules_register;
				}
				
				$this->form_validation->set_rules($rules);

				if ($this->form_validation->run() == TRUE) {
					$group = 'user';
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
			            $path_photo = base_url().'uploads/user/user.jpg';
						// echo json_encode(array('status' => 'failed', 'data' => $upload_data['file_type']));
					}

					$data = array(
							'username' => $post['username'],
							'nama' => $post['nama'],
							'password' => md5($post['password']),	
							// 'password' => bCrypt($post['password'],12),	
							'user_organisasi'=> $post['user_organisasi'],						
							'group' => 'adminop',
							'email' => $post['email'],							
							'created_on' => date('Y-m-d H:i:s'),							
							'active' => 1,
							'path_photo' => $path_photo
						);


					if($param == 'update'){
						// unset($data['username']);
						// unset($data['email']);
						unset($data['created_on']);
						
						if(!empty($post['password'])) { 
							$data['password'] = md5($post['password']);
							// $data['password'] = bCrypt($post['password'],12);
						}
						else{
							unset($data['password']);
						}

						$this->User_model->update($data, array('ID' => $post['user_id']));
						$getID = $post['user_id'];
					}
					else{
						$getID = $this->User_model->insert($data);
					}
					$result = array('status' => 'success', 'query' => $this->db->last_query());
				}
				else{
					$result = array('status' => 'failed', 'errors' => $this->form_validation->error_array());
				}

				echo json_encode($result);			
			}

			else if($param == 'hapus'){
				$post = $this->input->post();
				if(!empty($post['user_id'])){
					$this->User_model->delete($post['user_id']);
					$result = array('status' => 'success');
				}

				echo json_encode($result);
			}

			else if($param == 'mass'){
				$post = $this->input->post(NULL,TRUE);
				if($post['mass_action_type'] == 'hapus'){
					if(count($post['user_id']) > 0){										
						foreach($post['user_id'] as $id)
						$this->User_model->delete($id);
						$result = array('status' => 'success');
						echo json_encode($result);	
					}
				}
				else if($post['mass_action_type'] == 'non-aktifkan' || $post['mass_action_type'] == 'aktifkan'){
					if(count(@$post['user_id']) > 0){
						if($post['mass_action_type'] == 'aktifkan'){
							$active = 1;
						}
						else{
							$active = 0;
						}
						foreach($post['user_id'] as $id)
						$this->User_model->update(array('active' => $active),array('ID' => $id));
						$result = array('status' => 'success');
						echo json_encode($result);
					}
				}
			}	
		}
		else{
			$data = array();	
			$this->site->view('adminop', $data);	
		}	
	}
	public function instruktur($param=''){
		global $SConfig;
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
			if($param == 'ambil'){
				$post = $this->input->post();

				if(!empty($post['id'])){
					$record = $this->User_model->get_user_detail($post['id']);
					echo json_encode(array('status' => 'success', 'data' => $record));
				}
				else{
					if(get_user_info('group') == 'admin'){
						$record = $this->User_model->get_user_instruktur(array('group' => 'instruktur'));	
					}
					else if(get_user_info('group') == 'instruktur'){
						$record = $this->User_model->get_user_instruktur(array('ID'=> get_user_info('ID')));
					}
					else{
						$record = $this->User_model->get_user_instruktur(array('group' => 'instruktur', 'user_organisasi' => get_user_info('user_organisasi')));

					}
					echo json_encode(array(
						'data' => $record,
					) );					
				}			
			}
			else if($param == 'tambah' || $param == 'update'){

				if($param == 'update'){
					$rules = $this->User_model->rules_update;					
				}
				else{
					$rules = $this->User_model->rules_register;
				}
				
				$this->form_validation->set_rules($rules);

				if ($this->form_validation->run() == TRUE) {
					$group = 'user';
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
			            $path_photo = base_url().'uploads/user/user.jpg';
					}

					$data = array(
							'username' => $post['username'],
							'nama' => $post['nama'],
							'password' => md5($post['password']),							
							// 'password' => bCrypt($post['password'],12),							
							'group' => 'instruktur',
							'user_organisasi' => $post['user_organisasi'],
							'email' => $post['email'],							
							'created_on' => date('Y-m-d H:i:s'),							
							'active' => 1,
							'path_photo' => $path_photo
						);


					if($param == 'update'){
						// unset($data['username']);
						// unset($data['email']);
						
						if(!empty($post['password'])) { 
							$data['password'] = md5($post['password']);
							// $data['password'] = bCrypt($post['password'],12);
						}
						else{
							unset($data['password']);
						}

						$this->User_model->update($data, array('ID' => $post['user_id']));
						$getID = $post['user_id'];
					}
					else{
						$getID = $this->User_model->insert($data);
					}
					$result = array('status' => 'success', 'query' => $this->db->last_query());
				}
				else{
					$result = array('status' => 'failed', 'errors' => $this->form_validation->error_array());
				}

				echo json_encode($result);			
			}

			else if($param == 'hapus'){
				$post = $this->input->post();
				if(!empty($post['user_id'])){
					$this->User_model->delete($post['user_id']);
					$result = array('status' => 'success');
				}

				echo json_encode($result);
			}

			else if($param == 'mass'){
				$post = $this->input->post(NULL,TRUE);
				if($post['mass_action_type'] == 'hapus'){
					if(count($post['user_id']) > 0){										
						foreach($post['user_id'] as $id)
						$this->User_model->delete($id);
						$result = array('status' => 'success');
						echo json_encode($result);	
					}
				}
				else if($post['mass_action_type'] == 'non-aktifkan' || $post['mass_action_type'] == 'aktifkan'){
					if(count(@$post['user_id']) > 0){
						if($post['mass_action_type'] == 'aktifkan'){
							$active = 1;
						}
						else{
							$active = 0;
						}
						foreach($post['user_id'] as $id)
						$this->User_model->update(array('active' => $active),array('ID' => $id));
						$result = array('status' => 'success');
						echo json_encode($result);
					}
				}
			}	
		}
		else{
			$data = array();	
			$this->site->view('instruktur', $data);	
		}	
	}
}
