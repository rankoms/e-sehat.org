<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Transaksi {

	public function keranjang(){
		$_this =& get_instance();
		$post_detail = new stdClass();
		$post_detail->post_title = 'Keranjang Belanja';
		$post_detail->post_content = '<div class="row">
          <div class="col-md-12">
            <form method="POST">
	            <table class="table table-striped" id="list-transaction">
	                <thead>
	                    <tr>
	                        <th>Product</th>
	                        <th>Quantity</th>
	                        <th> </th>
	                        <th class="text-center">Price</th>
	                        <th class="text-center">Total</th>
	                    </tr>
	                </thead>
	                <tbody>';

    $post_detail->post_attribute = json_encode(array());
    $cart = $_this->cart->contents();

		/* mengambil semua id product dari cart */
		foreach ($cart as $items){			
			$produk_ID[] = $items['id'];
		}	

		if(isset($produk_ID)){
			$produk_detail = $_this->Produk_model->get_produk(NULL,NULL,NULL,NULL,NULL,$produk_ID);		
			foreach($produk_detail as $record){
				$item_detail[$record->post_ID] = $record;
			}				

			foreach ($cart as $items){

				$post_detail->post_content .= '<tr class="shop-item" id="rowid_'.$items['rowid'].'">
				    <td class="col-sm-8 col-xs-6" >
					    <div class="media">
					        <a class="thumbnail pull-left" style="margin:0 10px 0 0; padding:5px;" href="#">
					        	<img class="media-object" src="'.$_this->site->resize_img($item_detail[$items['id']]->post_image, 80, 72, 1).'" style="width: 80px; height: 72px">
					        </a>
					        <div class="media-body">
					            <h5 class="media-heading"><a href="'.permalink($item_detail[$items['id']]).'">'.$item_detail[$items['id']]->post_title.'</a></h5>
					            <p>'.post_content($item_detail[$items['id']],110).'</p>					            
					        </div>
					    </div>
				    </td>
				    <td class="col-sm-1 col-xs-1" style="text-align: center">
				    	<input type="input" class="cart-qty form-control" value="'.$items['qty'].'">
				    </td>
				    <td class="col-sm-1 col-xs-1">
					    <button type="button" class="btn btn-default cart-remove">
					        <span class="glyphicon glyphicon-remove"></span> 
					    </button>
				    </td>
				    <td class="col-sm-1 col-xs-1 text-center"><strong class="cart-price">'.rupiah($items['price']).'</strong></td>
				    <td class="col-sm-1 col-xs-1 text-center">
				    	<strong class="cart-subtotal">'.rupiah($items['subtotal']).
				    	'</strong>
				    </td></tr>';		
			}
		}

	    $post_detail->post_content .= '
	                    <tr>
	                        <td><h5>Total</h5></td>
	                        <td></td>
	                        <td></td>
	                        <td></td>
	                        <td class="text-right"><strong><h5 id="cart-total">'.rupiah($_this->cart->total()).'</h5></strong></td>
	                    </tr>
	                    <tr>
	                        <td></td>
	                        <td></td>
	                        <td></td>
	                        <td>
	                        <a href="'.base_url('produk').'" class="btn btn-default">
	                            <span class="glyphicon glyphicon-shopping-cart"></span> Lanjut Belanja!
	                        </a></td>
	                        <td>
	                        <a href="'.base_url('halaman/transaksi/pembayaran').'" class="btn btn-primary">
	                            Pembayaran <span class="glyphicon glyphicon-play"></span>
	                        </a></td>
	                    </tr>
	                </tbody>
	            </table>
          	</form>
          </div>
        </div>';

          /* sisipkan ke dalam post */
		$_this->post->post_detail = $post_detail;
	}

	public function pembayaran(){
		$_this =& get_instance();
		$post_detail = new stdClass();
		$post_detail->post_title = 'Form Pemesanan/Pengiriman';
		$post_detail->post_content = '<div class="row">
                      <div class="col-md-6">';

    if($_this->session->flashdata('message')){
      $post_detail->post_content .= '<p class="help-block">'.$_this->session->flashdata('message').'</p>';
    }

    $post_detail->post_content .= '<form class="form-horizontal" id="form-order" method="POST" action="'.base_url('halaman/transaksi/tagihan_pembelian').'">
                          <div class="form-group">
                            <label class="control-label col-xs-3">Atas Nama</label>
                            <div class="col-xs-9">
                                <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" placeholder="Isikan nama lengkapnya disini" required>                                
                            </div>
                          </div>

                          <div class="form-group">
                            <label class="control-label col-xs-3">Email</label>
                            <div class="col-xs-9">
                                <input type="email" class="form-control" id="email" name="email" placeholder=""  required>
                            </div>
                          </div>

                          <div class="form-group">
                            <label class="control-label col-xs-3">No. HP</label>
                            <div class="col-xs-9">
                                <input type="text" class="form-control" id="no_hp" name="no_hp" placeholder="08xxxx" required>
                                <p class="help-block">Diperlukan untuk konfirmasi dari pihak ekspedisi</p>
                            </div>
                          </div>

                          <div class="form-group">
                            <label class="control-label col-xs-3">No. Telephone</label>
                            <div class="col-xs-9">
                                <input type="text" class="form-control" id="no_telepon" name="no_telepon" placeholder="08xxxx" required>                                
                            </div>
                          </div>

                          <div class="form-group">
                            <label class="control-label col-xs-3">Provinsi</label>
                            <div class="col-xs-9">
                                '.form_dropdown_provinsi().'
                            </div>
                          </div>

                          <div class="form-group">
                            <label class="control-label col-xs-3">Kotamadya / Kabupaten</label>
                            <div class="col-xs-9">
                                <select name="kota" id="kota" class="form-control" required><option value="" selected="">Pilih Kota/Kabupaten</option></select>                                
                            </div>
                          </div>

                          <div class="form-group">
                            <label class="control-label col-xs-3">Kecamatan</label>
                            <div class="col-xs-9">
                                <select name="kecamatan" id="kecamatan" class="form-control" required><option value="" selected="">Pilih Kecamatan</option></select>
                            </div>
                          </div>

                          <div class="form-group">
                            <label class="control-label col-xs-3">Alamat Lengkap</label>
                            <div class="col-xs-9">
                                <textarea class="form-control" name="alamat_lengkap" rows="2" placeholder="isi alamat lengkap di sini..." required></textarea>
                            </div>
                          </div>

                          <div class="form-group">
                            <label class="control-label col-xs-3">Pilih Tarif JNE</label>
                            <div class="col-xs-9">
                                <select class="form-control" id="select_tarif_jne" name="select_tarif_jne" required>
                                  <option>Pilih Jenis Ongkir</option>
                                </select>
                            </div>
                          </div>              

                          <div class="form-group">
                            <label class="control-label col-xs-3">Membership</label>
                            <div class="col-xs-9">
                                <div class="checkbox">
                                    <label><input type="checkbox" name="member" id="member" value="yes"> Daftarkan sebagai member</label>
                                </div>
                                <p class="help-block">Jika ini diceklis maka Anda harus mengisi bagian bawah</p>
                            </div>
                          </div>
                          <div class="form-group">
                            <label class="control-label col-xs-3">Username</label>
                            <div class="col-xs-9">
                                <input type="text" class="form-control" id="username" name="username">                                
                            </div>
                          </div>
                          <div class="form-group">
                            <label class="control-label col-xs-3">Password</label>
                            <div class="col-xs-9">
                                <input type="password" class="form-control" id="password" name="password">                                
                            </div>
                          </div>                          

                          <div class="form-group">
                            <div class="col-xs-offset-3 col-xs-9">
                                <button type="submit" class="btn btn-primary input-block-level"><span class="glyphicon glyphicon-ok"></span> Pesan Sekarang!</button>
                            </div>
                          </div>

                        </form>

                      </div>
                    
                      <div class="col-md-6">

                        <h4 class="lead">Daftar Belanja</h4>

                        <table class="table table-striped" id="list-fix-order">
                            <thead>
                                <tr>
                                    <th>Produk</th>
                                    <th>Jumlah</th>
                                    <th class="text-center">Total</th>
                                </tr>
                            </thead>
                            <tbody>';

    $cart = $_this->cart->contents();

    /* mengambil semua id product dari cart */
    foreach ($cart as $items){      
      $produk_ID[] = $items['id'];
    }

    if(isset($produk_ID)){
      $produk_detail = $_this->Produk_model->get_produk(NULL,NULL,NULL,NULL,NULL,$produk_ID);   
      
      foreach($produk_detail as $record){
        $item_detail[$record->post_ID] = $record;
      }   

      foreach ($cart as $items){
        $post_attribute = json_decode($item_detail[$items['id']]->post_attribute);
        $post_detail->post_content .= '<tr class="shop-item">
                                        <td class="col-sm-6 col-xs-6">                                
                                          <h5 class="media-heading">'.$items['name'].'</h5> 
                                          <p class="help-block">@'.rupiah($items['price']).' ('.$post_attribute->post_weight.'gr)</p>
                                        </td>
                                        <td class="col-sm-1 col-xs-1" style="text-align: center"><p class="form-control-static">'.$items['qty'].'</p></td>
                                        <td class="col-sm-2 col-xs-1 text-right"><strong>'.rupiah($items['subtotal']).'</strong></td>
                                      </tr>';
      }

    }

    $post_detail->post_content .= '<tr>
                                    <td><h6>Subtotal</h6></td>
                                    <td></td>
                                    <td class="text-right"><strong><h6>'.rupiah($_this->cart->total()).'</h6></strong></td>
                                </tr>
                                <tr>
                                    <td><h6>Ongkos Kirim</h6></td>
                                    <td></td>
                                    <td class="text-right"><strong><h6 id="ongkos-kirim">-</h6></strong></td>
                                </tr>
                                <tr>
                                    <td><h5>Total</h5></td>
                                    <td></td>
                                    <td class="text-right"><strong><h5 id="total-bayar">-</h5></strong></td>
                                </tr>
                            </tbody>
                        </table>

                      </div></div>';

		$post_detail->post_attribute = json_encode(array());
    $_this->post->post_detail = $post_detail;
	}

	public function tagihan_pembelian(){
		$_this =& get_instance();
    $_this->load->library('form_validation');
    $_this->load->model(array('Transaksi_model','Pengiriman_model','Transaksi_detil_model'));    

    $rules_pemesanan = array(
        'nama_lengkap' => array(
          'field' => 'nama_lengkap', 
          'label' => 'Nama', 
          'rules' => 'trim|required'
        ),
        'email' => array(
          'field' => 'email', 
          'label' => 'Email', 
          'rules' => 'trim|required|valid_email'
        ),
        'no_hp' => array(
          'field' => 'no_hp', 
          'label' => 'No. Handphone', 
          'rules' => 'required'
        ), 
        'no_telepon' => array(
          'field' => 'no_telepon', 
          'label' => 'No. Telephone', 
          'rules' => 'required'
        ) ,
        'provinsi' => array(
          'field' => 'provinsi', 
          'label' => 'Provinsi', 
          'rules' => 'required'
        ),  
        'kota' => array(
          'field' => 'kota', 
          'label' => 'Kota', 
          'rules' => 'required'
        ), 
        'kecamatan' => array(
          'field' => 'kecamatan', 
          'label' => 'Kecamatan', 
          'rules' => 'required'
        ),     
        'alamat_lengkap' => array(
          'field' => 'alamat_lengkap', 
          'label' => 'Alamat Lengkap', 
          'rules' => 'required'
        ),   
        'select_tarif_jne' => array(
          'field' => 'select_tarif_jne', 
          'label' => 'Tarif JNE', 
          'rules' => 'required'
        )
    );

    $_this->form_validation->set_rules($rules_pemesanan);

    $post = $_this->input->post();
    if ($_this->form_validation->run() == TRUE) {

      $user_exist = $_this->User_model->count(array('email' => $post['email']));
      if($user_exist){
        $user_detail = $_this->User_model->get_by(array('email' => $post['email']), NULL,NULL,TRUE);
        $user_id = $user_detail->ID;
      }

      /* dicek terlebih dahulu apakah user mengajukan diri sebagai member */
      if(isset($post['member'])){
        /* jika email yang ada sudah digunakan */
        if($user_exist){          
          $user_id = $user_detail->ID;
        }
        else{
          /* buat user baru menggunakan alamat email yang telah dimasukkan */
          $data_user = array(
              'username' => $post['username'],
              'password' => bCrypt($post['password'],12),             
              'group' => 'user',
              'email' => $post['email'],              
              'active' => 1
            );

          $user_id = $_this->User_model->insert($data_user);
        }
      }
      else{
        if($user_exist){          
          $user_id = $user_detail->ID;
        }
        else{
          $user_id =  1; 
        }
        
      }

      /* Masukkan ke dalam keranjang belanja dalam database */
      $date = date('Y-m-d H:i:s');
      $session = $_this->session->userdata;

      $data_transaksi = array(
          'transaction_status' => 'pending', 
          'user_id' =>  $user_id,
          'transaction_time' => $date,
          'total' => $_this->cart->total(), 
          'random' => $session['digit_unique'], 
          'tax' => $session['ongkir'], 
          'total_tax' => $session['total_ongkir'], 
          'all_total' => $_this->cart->total() + $session['total_ongkir'] + $session['digit_unique'], 
          'tax_type' => $session['tipe_ongkir'], 
        );

      $id_transaksi = $_this->Transaksi_model->insert($data_transaksi);

      /* Masukkan detil transaksi ke dalam database */
      $cart = $_this->cart->contents();
      $data_detail_transaksi = array();


      /* transaction_id, product_id, name, option, quantity, price, sub_total */
      $x = 0;
      foreach($cart as $items){
        $data_detail_transaksi[$x] = array(
          'transaction_id' => $id_transaksi, 
          'product_id' =>  $items['id'],
          'name' => $items['name'],
          'option' => json_encode($items['options']),
          'quantity' => $items['qty'],
          'price' => $items['price'],
          'sub_total' => $items['subtotal']
          );

        $x++;
      }

      $_this->Transaksi_detil_model->insert($data_detail_transaksi,TRUE);

      /* Masukkan info alamat ke dalam shipping dalam database */
      $data_pengiriman = array(
          'user_id' => $user_id,
          'transaction_id' => $id_transaksi,
          'nama_lengkap' => $post['nama_lengkap'],
          'alamat' => $post['alamat_lengkap'],
          'provinsi' => $post['provinsi'],
          'kota' => $post['kota'],
          'kecamatan' => $post['kecamatan'],
          'no_handphone' => $post['no_hp'],
          'no_telepon' => $post['no_telepon'],
          'email' => $post['email']     
        );

      $_this->Pengiriman_model->insert($data_pengiriman);
      
      /* form tagihan */
      $post_detail = new stdClass();
      $post_detail->post_title = 'Invoice/Tagihan Pembelian';
      $post_detail->post_content = '<div class="row">
                      <div class="col-md-12"><p class="text-center">Terima kasih, Anda telah berhasil melakukan pemesanan, transaksi ini tercatat sebagai invoice nomor <strong>'.$id_transaksi.'</strong></p>

                    <p class="text-center"><a class="btn btn-primary" id="btn-fix-order">Lihat Detil Transaksi</a></p>';
                    

      $post_detail->post_content .= '<table class="table span6 table-hover" id="list-fix-order-invoice">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Quantity</th>
                                    <th class="text-center">Total</th>
                                </tr>
                            </thead>
                            <tbody>';

      $cart = $_this->cart->contents();
      $session = $_this->session->userdata;

      /* mengambil semua id product dari cart */
      foreach ($cart as $items){      
        $produk_ID[] = $items['id'];
      }

      if(isset($produk_ID)){
        $produk_detail = $_this->Produk_model->get_produk(NULL,NULL,NULL,NULL,NULL,$produk_ID);   
        
        foreach($produk_detail as $record){
          $item_detail[$record->post_ID] = $record;
        }   

        foreach ($cart as $items){
          $post_attribute = json_decode($item_detail[$items['id']]->post_attribute);
          $post_detail->post_content .= '<tr class="shop-item">
                                          <td class="col-sm-6 col-xs-6">                                
                                            <h5 class="media-heading">'.$items['name'].'</h5> 
                                            <p class="help-block">@'.rupiah($items['price']).' ('.$post_attribute->post_weight.'gr)</p>
                                          </td>
                                          <td class="col-sm-1 col-xs-1" style="text-align: center"><p class="form-control-static">'.$items['qty'].'</p></td>
                                          <td class="col-sm-2 col-xs-1 text-right"><strong>'.rupiah($items['subtotal']).'</strong></td>
                                        </tr>';
        }

      }

      $post_detail->post_content .= '<tr>
                                      <td><h6>Subtotal</h6></td>
                                      <td></td>
                                      <td class="text-right"><strong><h6>'.rupiah($_this->cart->total()).'</h6></strong></td>
                                  </tr>
                                  <tr>
                                      <td><h6>Ongkos Kirim</h6></td>
                                      <td></td>
                                      <td class="text-right"><strong><h6 id="ongkos-kirim">'.rupiah($session['total_ongkir']).'</h6></strong></td>
                                  </tr>
                                  <tr>
                                      <td><h6>Angka Unik</h6></td>
                                      <td></td>
                                      <td class="text-right"><strong><h6>'.rupiah($session['digit_unique']).'</h6></strong></td>
                                  </tr>
                                  <tr>
                                      <td><h5>Total</h5></td>
                                      <td></td>
                                      <td class="text-right"><strong><h5 id="total-bayar">'.rupiah($session['total_transfer'] + $session['digit_unique']).'</h5></strong></td>
                                  </tr>
                              </tbody>
                          </table>';                         

      $post_detail->post_content .= '<p>&nbsp;</p>

                    <p class="text-center">Segera lakukan pembayaran sebelum : </p>

                    <h4 class="text-center"><span class="label label-default">'.date('d-M-Y',strtotime("+3 days")).'</span></h4>
                    
                    <p class="text-center">Lakukan pembayaran sebesar : </p>
                    <h3 class="text-center">'.rupiah($session['total_transfer'] + $session['digit_unique']).'</h3>
                    <p class="text-center">Tepat hingga 2 digit terakhir, tidak kurang tidak lebih</p>
                    <p class="text-center"><i>Jika jumlah yang Anda transfer berbeda dengan diatas maka Akan menghambat proses verifikasi</i></p>
                    <p>&nbsp;</p>
                    <p class="text-center">Pembayaran bisa dilakukan ke salah satu rekening dibawah ini</p>

                    <div class="row">
                      
                        <div class="col-md-12 text-center">';

      /* list bank */

      $website_setting = $_this->site->website_setting;
      $nomor_rekening = $website_setting['nomor_rekening'];
      $jenis_rekening = $website_setting['jenis_rekening'];
      $atas_nama = $website_setting['atas_nama'];

      $post_detail->post_content .= '<ul class="list-bank">';

      for($x=0;$x<count($nomor_rekening);$x++){
        $post_detail->post_content .= '<li><h5>No.Rek. '.$jenis_rekening[$x].' :</h5><strong>'.$nomor_rekening[$x].' a/n '. $atas_nama[$x] .'</strong></li>';
      }

      $post_detail->post_content .= '</ul>';

      $post_detail->post_content .= '</div>

                    </div>

                    <p class="text-center">Transaksi dianggap batal bila sampai dengan <strong>'.date('d-M-Y',strtotime("+3 days")).'</strong> pembayaran belum dilunasi</p>

                    <p class="text-center">Kami telah mengirim Invoice pemesanan ke email Anda, silahkan lakukan pembayaran sebelum <strong>'.date('d-M-Y',strtotime("+3 days")).'</strong>. </p>


                    <p class="text-center">Silahkan lakukan konfirmasi pembayaran ke nomor <strong>'.$website_setting['handphone'].'</strong> (SMS/Whatsapp) </p>

                    <p class="text-center">Terima kasih telah berbelanja di <strong>'.$website_setting['domain'].'</strong></p>
                </div>
              </div>';

      $post_detail->post_attribute = json_encode(array());
      $_this->post->post_detail = $post_detail;    

      /* UNSET ALL ARRAY SESSION FOR TRANSACTION */
      $data_transaction = array(
        'cart_contents', 'tipe_ongkir', 'ongkir', 'total_ongkir', 'total_transfer', 'digit_unique' 
      );

      $_this->session->unset_userdata($data_transaction);      
    }   
    else{
      $_SESSION['message'] = 'Mohon diisi kembali dengan benar!';
      $_this->session->keep_flashdata('message');
      redirect($_SERVER['HTTP_REFERER']);
    }  
	} 

  public function daftar(){
    $_this =& get_instance();
    $_this->site->is_logged_in();
    $post_detail = new stdClass();
    $post_detail->post_title = 'Daftar Transaksi!';
    $post_detail->post_content = '<div class="row">
        <div class="col-md-10">
          <p>Berikut adalah transaksi yang pernah Anda lakukan </p>
          <table id="table-transaksi" class="table table-striped">
            <thead>
              <tr>
                <th width="15%" class="text-center">No. Invoice</th>
                <th width="55%">Detil Transaksi</th>
                <th width="15%" class="text-center">Total Transfer</th>
                <th width="15%"class="text-center">Status</th>
              </tr>
            </thead>
            <tbody>
              <!--<tr>
                <td class="text-center"><h6><strong>#1</strong></h6></td>
                <td>
                    <ol>
                    <li>Corsair KATAR Gaming Mouse <strong>x 1 = Rp 495.000</strong></li>
                    <li>Samsung DVDRW SE-208GB <strong>x 1 = Rp 295.000</strong></li>
                    </ol>                    
                    <a class="detail-popover btn btn-default" title="Detil Transaksi Invoce No. #1">Detil Transaksi</a>
                    <div class="div-hidden">
                        <ul class="ul-transaksi">
                          <li>Corsair KATAR Gaming Mouse <strong>x 1 = Rp 495.000</strong></li>
                          <li>Samsung DVDRW SE-208GB <strong>x 1 = Rp 295.000</strong></li>
                        </ul>
                        <br>Jumlah : <strong>790.000</strong>
                        <br>Ongkos Kirim : <strong>817.000</strong> (JNE REG)
                        <br>Angka Unik : <strong>13</strong>
                        <br>Total pembayaran : <strong>817.013</strong>
                        <br>Waktu Transaksi : <strong>21/07/2016</strong>
                        <br>Status : <strong>Pending</strong>
                    </div>
                </td>
                <td class="text-center"><h6>Rp 817.013</h6></td>
                <td class="text-center"><h6><span class="label label-danger">Pending Order</span></h6></td>
              </tr>-->
            </tbody>
          </table>

          <p>*<i>Silahkan lakukan konfirmasi jika Anda telah melakukan transfer</i></p>
        </div>
        <div class="col-md-2"></div>
      </div>';
    $post_detail->post_attribute = json_encode(array());
    $_this->post->post_detail = $post_detail;          
  }  
	
}