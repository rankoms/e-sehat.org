<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Post {
	public $post_record;
	public $post_detail;
	public $post_except = array();
	public $post_search_keyword ;

	public function get_post_detail($type=NULL){
		$_this =& get_instance();	

		if($_this->uri->total_segments() == 3 && $_this->uri->segment(2) != 'beli'){
			$post_name =   $_this->uri->segment(3);		
			$model = "Artikel_model";
			$function = "get_artikel";
			
			if($type == 'produk'){
				$model = "Produk_model";
				$function = "get_produk";
			}

			$this->post_detail = $_this->$model->$function(array('post_name' => $post_name), NULL, NULL, TRUE);							
			
			/* UNTUK COUNTER ARTIKEL */
			$counter = $this->post_detail->post_counter + 1 ;		
			$_this->$model->update(array('post_counter' => $counter) , array('post_ID' => $this->post_detail->post_ID ));										
		}
		else if($_this->uri->total_segments() == 2){
			$post_name = $_this->uri->segment(2);	
			$this->post_detail = $_this->Halaman_model->get_by(array('post_name' => $post_name), NULL, NULL, TRUE);							
		}
	}
}