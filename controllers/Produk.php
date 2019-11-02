<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Produk extends Frontend_Controller {

	public function __construct(){
		parent::__construct();
	}

	public function index(){
		$data = array();

		$pencarian = $this->input->get('pencarian', TRUE);
		
		if($pencarian){
			$this->site->_isSearch = TRUE;
			$this->post->post_search_keyword = $pencarian;
			$this->site->view('pencarian', $data);
		}
		else{
			$this->site->_isHome = TRUE;
			$this->site->view('index', $data);			
		}
	}	

	public function kategori(){
		$data = array();
		$this->site->_isCategory = TRUE;
		$this->site->view('kategori_produk', $data);
	}	

	public function detil(){
		$data = array();
		$this->site->_isDetail = TRUE;		
		$this->post->get_post_detail('produk');
		$this->site->view('produk', $data);
	}

	/* TRANSACTION */
	public function transaksi($action='tambah'){
		global $SConfig;
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
			$post = $this->input->post(NULL, TRUE);
			if($action == 'tambah'){
				if(!isset($post['qty'])) $post['qty'] = 1;

				/* ********************************************************** */
				/* DIBAGIAN INI DIGUNAKAN UNTUK MENAMBAHKAN KERANJANG BELANJA */
				/* ********************************************************** */
				$post_detail = $this->Produk_model->get_by(array('post_ID' => $post['post_ID']),NULL,NULL,TRUE);

				/* data untuk dimasukkan ke dalam keranjang belanja */
				$data = array(
				        'id'      => $post['post_ID'],
				        'qty'     => $post['qty'],
				        'price'   => $post_detail->post_discount,
				        'name'    => $post_detail->post_title,
				        'options' => array('Size' => @$post['post_size'], 'Color' => @$post['post_color'])
				);

				/* jika berhasil dimasukkan ke keranjang belanja */
				if($post_detail->post_stock != 0 ){
					if($this->cart->insert($data)){
						echo json_encode(
							array(
								'status' => 'success', 
								'post_detail' => $post_detail, 
								'qty' => $post['qty']
							)
						);					
					}	
					else{
						echo json_encode(
							array(
								'status' => 'failed'
							)
						);	
					}									
				}

				else{
					echo json_encode(
						array(
							'status' => 'empty'
						)
					);	
				}
			}

			else if($action == 'update'){

				$cart = $this->cart->contents();				
				@$id = $cart[$post['rowid']]['id'];
				$post_detail = $this->Produk_model->get_by(array('post_ID' => $id),NULL,NULL,TRUE);

				$data = array(
				        'rowid' => $post['rowid'],
				        'qty'   => $post['qty']
					);

				if($this->cart->update($data)){
					$data = array_merge( $data, 
						array(
							'status' => 'success',
							'subtotal' => rupiah($post_detail->post_discount * $post['qty']),
							'total' => rupiah($this->cart->total())
						)
					);

					echo json_encode($data);	
				}	
				else{
					echo json_encode(
						array(
							'status' => 'failed'
						)
					);						
				}				
			}

			else if($action == 'update_ongkir'){
				$post = $this->input->post(NULL, TRUE);
				$ongkir = explode('_', $post['ongkir']);
				
				/* ambil terlebih dahulu semua data item produk */
				$cart = $this->cart->contents();

			    /* mengambil semua id product dari cart */
			    foreach ($cart as $items){      
			      $produk_ID[] = $items['id'];
			    }

			    /* ambil ukuran heightnya ...  */
			    if(isset($produk_ID)){
					$produk_detail = $this->Produk_model->get_produk(NULL,NULL,NULL,NULL,NULL,$produk_ID);   					
					$total_berat = NULL;

					foreach($produk_detail as $record){
						$item_detail[$record->post_ID] = $record;
					}

					foreach ($cart as $items){
						$post_attribute = json_decode($item_detail[$items['id']]->post_attribute);
						$total_berat += $items['qty'] * $post_attribute->post_weight;														      	
					}

					$total_berat_up = (ceiling($total_berat,1000) / 1000) ;

					$data = array(
						'tipe_ongkir' => $ongkir[0],
						'ongkir' => $ongkir[1],
						'total_ongkir' => $total_berat_up * $ongkir[1],
						'total_transfer' => ($total_berat_up * $ongkir[1]) + $this->cart->total(),
						'digit_unique' => rand(1, 200)
					);

					$this->session->set_userdata($data);

					/* untuk ditampilkan */
					$data['total_ongkir'] = rupiah($total_berat_up * $ongkir[1]);
					$data['total_transfer'] = rupiah(($total_berat_up * $ongkir[1]) + $this->cart->total());
					echo json_encode($data);
					
			    }				
			}

			else if($action == 'tarif_jne'){
				$post = $this->input->post(NULL, TRUE);
				$kota = $post['kota'];
				$kecamatan = $post['kecamatan'];
				if(!empty($kota) && !empty($kecamatan)){
					$this->load->model('Tarif_jne_model');
					$record = $this->Tarif_jne_model->get_by(array("kota_kabupaten LIKE" => "%$kota%", "kecamatan LIKE" => "%$kecamatan%"), NULL,NULL,TRUE);

					if(!$record){
						$kota = str_replace("KOTA ", "", $kota);
						$record = $this->Tarif_jne_model->get_by(array("kota_kabupaten LIKE" => "%$kota%"), NULL,NULL,TRUE);
					}

					echo json_encode($record);
				}				
			}

			else if($action == 'keranjang_belanja'){
				/* ambil terlebih dahulu semua data item produk */
				$cart = $this->cart->contents();

			    /* mengambil semua id product dari cart */
			    foreach ($cart as $items){      
			      $produk_ID[] = $items['id'];
			    }

			    /* ambil ukuran heightnya ...  */
			    if(isset($produk_ID)){
					$produk_detail = $this->Produk_model->get_produk(NULL,NULL,NULL,NULL,NULL,$produk_ID);   					
					$total_berat = NULL;

					foreach($produk_detail as $record){
						$item_detail[$record->post_ID] = $record;
					}

					foreach ($cart as $items){
						// $post_attribute = json_decode($item_detail[$items['id']]->post_attribute);																			      	
					}
				}
			}

			else if($action == 'daftar'){
				$this->load->model(array('Transaksi_model','Transaksi_detil_model'));
				$transaksi = $this->Transaksi_model->get_by(array('user_id' => get_user_info('ID')));
				
				/* get transaksi id for all */
				foreach($transaksi as $item){
					$transaction_id[] = $item->transaction_id;
				}

				/* untuk detil transaksi */
				$transaksi_detil = $this->Transaksi_detil_model->get_by(NULL,NULL,NULL,NULL,NULL,$transaction_id);	
				
				echo json_encode(
					array(
						'status' => 'success',
						'data_transaksi' => $transaksi,
						'data_transaksi_detil' => $transaksi_detil
					)
				);
			}

			else if($action == 'kirim_konfirmasi'){
				$this->load->model('Konfirmasi_model');
				$post['confirm_status'] = 'pending';
				$post['confirm_session'] = $this->session->session_id;
				$post['confirm_time'] = date('Y-m-d H:i:s');

				$exist_confirmation = $this->Konfirmasi_model->count(array('confirm_session' => $this->session->session_id)); 
				if($exist_confirmation > 0){
					$this->Konfirmasi_model->update($post,array('confirm_session' => $this->session->session_id));
				}
				else{
					$this->Konfirmasi_model->insert($post);	
				}
				
				echo json_encode(array('status' => 'success'));
			}
		}
	}




}
