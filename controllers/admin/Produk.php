<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Produk extends Backend_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model(
			array(
				'Produk_model',
				'Kategori_model',
				'Transaksi_model',
				'Transaksi_detil_model',
				'Pengiriman_model',
				'Konfirmasi_model')
			);				
	}

	public function index(){
		$data = array();	
		$this->site->view('produk', $data);
	}	

	public function action($param){
		global $SConfig;
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
			
			/* jika aksinya adalah tambah ... */
			if($param == 'tambah' || $param == 'update'){
				$rules = $this->Produk_model->rules;
				$this->form_validation->set_rules($rules);	
				if ($this->form_validation->run() == TRUE) {
					$post = $this->input->post();					

					$category = 'tanpa-kategori';
					$comment_status = ""; 
					$comment_notification = "";					

					if(!empty($post['category_slug'])) $category = implode(",", $post['category_slug']) ;					
					
					/*atribut produk */
					$post_date = $post['year'].'/'.$post['month'].'/'.$post['date'].' '.$post['hour'].':'.$post['minute'].':00';

					if(!empty($post['comment_status'])) $comment_status = $post['comment_status'] ; 
					if(!empty($post['comment_notification'])) $comment_notification = $post['comment_notification'] ;

					$post_attribute = array(
							'comment_notification' => $comment_notification,
							'meta_title' => $post['meta_title'],
							'meta_keyword' => $post['meta_keyword'],
							'meta_description' => $post['meta_description'],
							'post_weight' => $post['post_weight'],
							'weight_type' => $post['weight_type'],
							'post_height' => $post['post_height'],
							'height_type' => $post['height_type'],
							'post_length' => $post['post_length'],
							'length_type' => $post['length_type'],
							'post_width' => $post['post_width'],
							'width_type' => $post['width_type'],
							'post_color' => $post['post_color'],
							'post_size' => $post['post_size'],
							'post_image' => @$post['post_image'],							
						);

					$data = array(
							'post_author' => get_user_info('ID'),
							'post_title' => $post['post_title'],
							'post_name' => url_title($post['post_title'], '-', TRUE),
							'post_content' => $post['post_content'],
							'post_date' => $post_date, // date('Y-m-d H:i:s')
							'post_type' => 'produk',
							'post_category' => $category,
							'comment_status' => $comment_status,
							'post_attribute' => json_encode($post_attribute),
							'post_code' => $post['post_code'],
							'post_price' => $post['post_price'],
							'post_discount' => $post['post_price_discount'],
							'post_stock' => $post['post_stock'],
							'post_image' => @$post['post_image'][0]
						);

					if(!empty($post['post_id_hidden'])){
						unset($data['post_date']);
						$this->Produk_model->update($data, array('post_ID' => $post['post_id_hidden']));
					}
					else{
						/* jika ada judul produk yang sama, maka berikan imbuhan 2 di belakangnya */
						$is_exist = $this->Produk_model->count(array('post_title' => $data['post_title']));
						if($is_exist > 0){
							$data['post_title'] = $data['post_title'].' 2';
							$data['post_name'] = url_title($data['post_title'], '-', TRUE);
						}			

						$this->Produk_model->insert($data);
					}
					
					$result = array('status' => 'success');
				}
				else{
					$result = array('status' => 'failed', 'errors' => $this->form_validation->error_array());
				}

				echo json_encode($result);				
			}

			/* jika aksinya adalah ambil */
			else if($param == 'ambil'){
				$post = $this->input->post(NULL,TRUE);
				// print_r($post);

				if(!empty($post['id'])){
					$record = $this->Produk_model->get($post['id']);
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

					echo json_encode(array('status' => 'success', 'data' => $record));
				}
				else{
					
					$offset = NULL;
					$kategori = NULL;
					
					if(!empty($post['hal_aktif']) && $post['hal_aktif'] > 1)
					$offset = ($post['hal_aktif'] - 1) * $SConfig->_backend_perpage ;

					if(!empty($post['kategori']) && ($post['kategori'] != 'null')){
						$kategori = $post['kategori'];
						$total_rows = $this->Produk_model->count(array("post_category LIKE" => "%$kategori%"));
						@$record = $this->Produk_model->get_by(array('post_type' => 'produk', "post_category LIKE" => "%$kategori%"),$SConfig->_backend_perpage, $offset);
					}
					else if(!empty($post['cari']) && ($post['cari'] != 'null')){
						$cari = $post['cari'];
						$total_rows = $this->Produk_model->count(array("post_title LIKE" => "%$cari%"));
						@$record = $this->Produk_model->get_by(array('post_type' => 'produk', "post_title LIKE" => "%$cari%"),$SConfig->_backend_perpage, $offset);
						
					}
					else{
						$total_rows = $this->Produk_model->count();
						@$record = $this->Produk_model->get_by(array('post_type' => 'produk'),$SConfig->_backend_perpage, $offset);						
					}	

					/* resize image ... */
					$allrecord = new stdClass();
					foreach($record as $key => $val){
						foreach($val as $k => $v){
							if($k == 'post_image'){
								$val->$k = $this->site->resize_img($v,100,100,1);	
							}
							else{
								$val->$k = $v;
							}
						}

						$allrecord->$key = $val;
					}			

					echo json_encode(
						array(
								'total_rows' => $total_rows, 
								'perpage' => $SConfig->_backend_perpage,
								'record' => $record,
								'all_category' => $this->Kategori_model->get_by(array('category_type' => 'produk'),NULL,NULL,FALSE,'category_slug,category_name')														
							)
						);				
				}					
			}

			else if($param == 'hapus'){
				$post = $this->input->post();
				if(!empty($post['post_id_hidden'])){
					$this->Produk_model->delete($post['post_id_hidden']);
					$result = array('status' => 'success');
				}

				echo json_encode($result);
			}

			else if($param == 'mass'){
				$post = $this->input->post(NULL,TRUE);
				if($post['mass_action_type'] == 'hapus'){
					if(count($post['post_id']) > 0){										
						foreach($post['post_id'] as $id)
						$this->Produk_model->delete($id);
						$result = array('status' => 'success');
						echo json_encode($result);	
					}
				}
				else if($post['mass_action_type'] == 'pending' || $post['mass_action_type'] == 'publish'){
					if(count(@$post['post_id']) > 0){
						foreach($post['post_id'] as $id)
						$this->Produk_model->update(array('post_status' => $post['mass_action_type']),array('post_ID' => $id));
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
							'category_type' => 'produk'
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
						$this->Kategori_model->insert($data);						
					}
					
					$result = array('status' => 'success');

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
					$record = $this->Kategori_model->get_by(array('category_type' => 'produk'));	
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
			$this->site->view('kategori_produk', $data);	
		}		
	}

	public function pesanan($action=NULL){
		global $SConfig;
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){		

			if($action == 'update'){
				$post = $this->input->post(NULL,TRUE);
				// print_r($post);

				$update_transaction = array(
						'transaction_status' => $post['transaction_status'],
						'transfer_destination' => $post['tujuan'],
						'tracking_number' =>  $post['tracking_number']
					);

				$update_shipping = array(
						'nama_lengkap' => $post['nama_lengkap'],
						'alamat' => $post['alamat'],
						'provinsi' => $post['provinsi'],
						'kota' => $post['kota'],
						'kecamatan' => $post['kecamatan'],
						'no_telepon' => $post['no_telepon'],
						'no_handphone' => $post['no_handphone'],
						'email' => $post['email'],

					);

				$this->Transaksi_model->update($update_transaction, array('transaction_id' => $post['transaction_id_hidden'])) ; 
				$this->Pengiriman_model->update($update_shipping, array('transaction_id' => $post['transaction_id_hidden'])) ;
				echo json_encode(array('status' => 'success'));					
				
			}
			else if($action == 'ambil'){
				$post = $this->input->post();	
				if(!empty($post['transaction_id'])){
					$record = $this->Transaksi_model->get_transaksi(array('{PRE}transaction.transaction_id' => $post['transaction_id']),NULL,NULL,TRUE);
					$detil = $this->Transaksi_detil_model->get_by(array('transaction_id' => $post['transaction_id']));
					
					$x = 0;
					foreach($detil as $row){
						if( $row->option){
							$detil[$x]->option = json_decode($row->option);
						}
						else{
							$detil[$x] = $row;
						}
						
						$x++;
					}

					echo json_encode(array('status' => 'success', 'data' => $record, 'detil' => $detil));
				}
				
				else{
					$offset = NULL;
					$detil = NULL;

					if(!empty($post['hal_aktif']) && $post['hal_aktif'] > 1){
						$offset = ($post['hal_aktif'] - 1) * $SConfig->_backend_perpage ;	
					}

					if(!empty($post['status']) && ($post['status'] != 'null')){

						$status = $post['status'];
						$total_rows = $this->Transaksi_model->count(array("{PRE}transaction.transaction_status" => $status));												
						@$record = $this->Transaksi_model->get_transaksi(array("{PRE}transaction.transaction_status" => $status),$SConfig->_backend_perpage, $offset);
					}
					else if(!empty($post['cari']) && ($post['cari'] != 'null')){
						$cari = $post['cari'];
						$total_rows = $this->Transaksi_model->count("{PRE}transaction.transaction_id LIKE \"%$cari%\" OR {PRE}shipping.nama_lengkap LIKE \"%$cari%\"");												
						@$record = $this->Transaksi_model->get_transaksi("{PRE}transaction.transaction_id LIKE \"%$cari%\" OR {PRE}shipping.nama_lengkap LIKE \"%$cari%\"",$SConfig->_backend_perpage, $offset);												
					}
					else{
						$total_rows = $this->Transaksi_model->count();												
						@$record = $this->Transaksi_model->get_transaksi(NULL,$SConfig->_backend_perpage, $offset);											
					}

					/* ambil transaction_id untuk detilnya nanti */
					if($record){
						foreach($record as $item){
							$transaction_id[] = $item->transaction_id;
						}
						/* ambil detil dari transaksi */
						$detil = $this->Transaksi_detil_model->get_by(NULL,NULL,NULL,NULL,NULL,$transaction_id);						
					}

					/* mengganti angka menjadi text untuk provinsi dan kota */
					$provinsi = $this->site->provinsi;

					$kota = json_decode(file_get_contents(base_url('assets/js/kota.json')));					

					for($x=0;$x<count($record);$x++){
						foreach($kota as $key){
							if($record[$x]->kota == $key->id){
								$record[$x]->kota = $key->name;
							} 
						}
						$record[$x]->provinsi = @$provinsi[$record[$x]->provinsi];
					}					


					echo json_encode(
						array(
								'total_rows' => $total_rows, 
								'perpage' => $SConfig->_backend_perpage,
								'record' => $record,
								'detil' => $detil
							)
						);	
				}
			}

			else if($action == 'hapus'){
				$post = $this->input->post();				
				if(!empty($post['transaction_id_hidden'])){
					$this->Transaksi_model->delete($post['transaction_id_hidden']);
					$this->Pengiriman_model->delete_by(array('transaction_id' => $post['transaction_id_hidden']));
					$this->Transaksi_detil_model->delete_by(array('transaction_id' => $post['transaction_id_hidden']));
					$result = array('status' => 'success');
				}

				echo json_encode($result);				
			}

			else if($action == 'mass'){
				$post = $this->input->post(NULL,TRUE);
				if($post['mass_action_type'] == 'pending' || $post['mass_action_type'] == 'sudah_transfer' || $post['mass_action_type'] == 'sudah_dikirim'){
					if(count(@$post['transaction_id']) > 0){
						foreach($post['transaction_id'] as $id){							
							$this->Transaksi_model->update(array('transaction_status' => $post['mass_action_type']),array('transaction_id' => $id));
						}
						
						$result = array('status' => 'success');
						echo json_encode($result);
					}
				}	

				else if($post['mass_action_type'] == 'hapus'){
					if(count($post['transaction_id']) > 0){										
						foreach($post['transaction_id'] as $id){
							$this->Transaksi_model->delete($id);
							$this->Pengiriman_model->delete_by(array('transaction_id' => $id));
							$this->Transaksi_detil_model->delete_by(array('transaction_id' => $id));							
						}
						
						$result = array('status' => 'success');
						echo json_encode($result);	
					}
				}

				else if($post['mass_action_type'] == 'cetak_alamat'){

					$transaksi = $this->Transaksi_model->get_transaksi(NULL,NULL,NULL,NULL,NULL,$post['transaction_id']);
					$detil_transaksi = $this->Transaksi_detil_model->get_by(NULL,NULL,NULL,NULL,NULL,$post['transaction_id']);

					if(print_addr($transaksi,$detil_transaksi)){
						$result = array('status' => 'success', 'file' => base_url('downloads/'.'pengiriman-paket-'.date('d-m-Y').'.doc'));
						echo json_encode($result);						
					}
				}

			}

		}
		else{
			$data = array();	
			$this->site->view('pesanan', $data);			
		}
	}

	public function konfirmasi($action=NULL){
		global $SConfig;
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){		

			if($action == 'ambil'){
				$post = $this->input->post(NULL, TRUE);

				if(!empty($post['confirmation_id'])){
					$record = $this->Konfirmasi_model->get_by(array('confirm_id' => $post['confirmation_id']),NULL,NULL,TRUE);
					echo json_encode(array('status' => 'success', 'data' => $record));
				}
				else{
					$offset = NULL;
					$detil = NULL;	

					if(!empty($post['hal_aktif']) && $post['hal_aktif'] > 1){
						$offset = ($post['hal_aktif'] - 1) * $SConfig->_backend_perpage ;	
					}

					if(!empty($post['cari']) && ($post['cari'] != 'null')){
						$cari = $post['cari'];
						$total_rows = $this->Konfirmasi_model->count(array("nama_lengkap LIKE" => "%$cari%"));												
						@$record = $this->Konfirmasi_model->get_by(array("nama_lengkap LIKE" => "%$cari%"),$SConfig->_backend_perpage, $offset);											
					}
					else{
						$total_rows = $this->Konfirmasi_model->count();												
						@$record = $this->Konfirmasi_model->get_by(NULL,$SConfig->_backend_perpage, $offset);					
					}

					echo json_encode(
						array(
								'total_rows' => $total_rows, 
								'perpage' => $SConfig->_backend_perpage,
								'record' => $record
							)
						);						
				}
																							
			}
			else if($action == 'update'){
				$post = $this->input->post(NULL, TRUE);
				$confirm_id = $post['confirmation_id_hidden']; 				
				unset($post['confirmation_id_hidden']);
				unset($post['transaction_id']);
				unset($post['mass_action_type']);
				$this->Konfirmasi_model->update($post,array('confirm_id' => $confirm_id));

				echo json_encode(array('status' => 'success'));
			}
			else if($action == 'hapus'){
				$post = $this->input->post();				
				if(!empty($post['confirmation_id_hidden'])){
					$this->Konfirmasi_model->delete($post['confirmation_id_hidden']);					
					$result = array('status' => 'success');
				}
				echo json_encode($result);

			}
			else if($action == 'mass'){
				$post = $this->input->post(NULL,TRUE);
				if($post['mass_action_type'] == 'pending' || $post['mass_action_type'] == 'sudah_transfer'){
					
					if(count(@$post['confirmation_id']) > 0){
						foreach($post['confirmation_id'] as $id){
							$this->Konfirmasi_model->update(array('confirm_status' => $post['mass_action_type']), array('confirm_id' => $id));						
						}


					}
				}
				else if($post['mass_action_type'] == 'hapus' ){
					if(count(@$post['confirmation_id']) > 0){
						foreach($post['confirmation_id'] as $id){
							$this->Konfirmasi_model->delete($id);						
						}
					}
				}

				echo json_encode(array('status' => 'success'));
			}


		}
		else{
			$data = array();	
			$this->site->view('konfirmasi', $data);			
		}

	}
}
