<?php
	
	function get_template_directory($path,$dir_file){
		global $SConfig;

		$replace_path = str_replace('\\', '/', $path);
		$get_digit_doc_root = strlen($SConfig->_document_root);
		$full_path = substr($replace_path,$get_digit_doc_root);
		return $SConfig->_site_url.$full_path.'/'.$dir_file;
	}

	function get_template($view){
		$_this =& get_instance();
		return $_this->site->view($view);
	}

	function set_url($sub){
		$_this =& get_instance();
		if($_this->site->side == 'backend'){
			return site_url('admin/'.$sub);
		}
		else{
			return site_url($sub);
		}
	}

	function is_active_page_print($page,$class){
		$_this =& get_instance();
		if($_this->site->side == 'backend' && $page == $_this->uri->segment(2)){
			return $class;
		}
	}

	function title(){
		$_this =& get_instance();
		global $SConfig;
		$array_backend_page = array(
							'dashboard' => 'Dashboard',
							'artikel' => 'Daftar Artikel',
							'kategori_artikel' => 'Daftar Kategori Artikel',
							'halaman' => 'Daftar Halaman',
							'produk'  => 'Daftar Produk',
							'kategori_produk' => 'Daftar Kategori Produk',
							'komentar' => 'Daftar Komentar',
							'statistik' => 'Statistik',
							'tampilan' => 'Tampilan',
							'konfigurasi' => 'Konfigurasi',
							'master' => 'Master',
							'kursus' => 'Kursus',
							'log' => 'Log',
							'user' => 'Daftar User');
		$title = NULL;

		if($_this->site->side == 'backend' && (array_key_exists($_this->uri->segment(2), $array_backend_page) ) ){
			return $array_backend_page[$_this->uri->segment(2)] . ' | ' . $SConfig->_cms_name;
		}
		else if($_this->site->side == 'frontend'){

			if($_this->uri->segment(1) == ''){
				$title = 'Beranda'. ' | ' . $SConfig->_cms_name;
			}

			else if($_this->uri->segment(1) == 'tentang-kami'){
				$title = 'Tentang Kami '. ' | ' . $SConfig->_cms_name;
			}
			else if($_this->uri->segment(1) == 'cara-penggunaan'){
				$title = 'Cara Penggunaan '. ' | ' . $SConfig->_cms_name;
			}
			else if($_this->uri->segment(1) == 'courses'){
				$title = 'Daftar Kursus '. ' | ' . $SConfig->_cms_name;
			}
			else if($_this->uri->segment(1) == 'kontak-kami'){
				$title = 'Kontak Kami '. ' | ' . $SConfig->_cms_name;
			}
			else if($_this->uri->segment(1) == 'login'){
				$title = 'Login '. ' | ' . $SConfig->_cms_name;
			}

			else if($_this->uri->segment(1) == 'register'){
				$title = 'Register '. ' | ' . $SConfig->_cms_name;
			}
			else if($_this->uri->segment(1) == 'dashboard'){
				$title = 'Dashboard '. ' | ' . $SConfig->_cms_name;
			}
			else if($_this->uri->segment(1) == 'profile'){
				$title = 'Profile '. ' | ' . $SConfig->_cms_name;
			}
			else if($_this->uri->segment(1) == 'kursus-saya'){
				$title = 'Kursus Saya '. ' | ' . $SConfig->_cms_name;
			}
			else if($_this->uri->segment(1) == 'sertifikat'){
				$title = 'Sertifikat '. ' | ' . $SConfig->_cms_name;
			}

			else if($_this->uri->segment(1) == 'organisasi'){
				$data = $_this->Organisasi_model->get_by(array('organisasi_slug'=> $_this->uri->segment(2)));
				$title = $data[0]->organisasi_nama. ' | ' . $SConfig->_cms_name;
			}
			return $title;
		}
	}

	function form_dropdown_provinsi(){
		$options = array(
			0	=> 'Pilih Provinsi',
	        11	=> 'Aceh',
	        12	=> 'Sumatera Utara',
	        13	=> 'Sumatera Barat',
	        14	=> 'Riau',
	        15	=> 'Jambi',
	        16 	=> 'Sumatera Selatan',
	        17 	=> 'Bengkulu',
	        18 	=> 'Lampung',
	        19 	=> 'Kepulauan Bangka Belitung',
	        21 	=> 'Kepulauan Riau',
	        31 	=> 'DKI Jakarta',
	        32 	=> 'Jawa Barat',
	        33 	=> 'Jawa Tengah',
	        34 	=> 'DI Yogyakarta',
	        35 	=> 'Jawa Timur',
	        36	=> 'Banten',
	        51	=> 'Bali',
	        52	=> 'Nusa Tenggara Barat',
	        53	=> 'Nusa Tenggara Timur',
	        61	=> 'Kalimantan Barat',
	        62	=> 'Kalimantan Timur',
	        63	=> 'Kalimantan Selatan',
	        64	=> 'Kalimantan Timur',
	        65	=> 'Kalimantan Utara',
	        71	=> 'Sulawesi Utara',
	        72	=> 'Sulawesi Tengah',
	        73	=> 'Sulawesi Selatan',
	        74	=> 'Sulawesi Tenggara',
	        75	=> 'Gorontalo',
	        76	=> 'Sulawesi Barat',
	        81	=> 'Maluku',
	        82	=> 'Maluku Utara',
	        91	=> 'Papua Barat',
	        94	=> 'Papua',
		);
		
		return form_dropdown('provinsi', $options, 0, 'id="provinsi" class="form-control" required');
	}
	
	function form_dropdown_halaman_konfirmasi(){
		global $SConfig;
		$_this =& get_instance();	
		$record = $_this->Halaman_model->get_by(array('post_type' => 'halaman'),NULL,NULL,FALSE,'post_title, post_name');						
		
		foreach($record as $key => $val){
			$options[$val->post_name] = $val->post_title;
		}

		$options = array_merge(array(0 => 'Pilih Halaman Konfirmasi'), $options);

		return form_dropdown('halaman_konfirmasi', $options, 0, 'id="halaman_konfirmasi"');	
	}

	function form_dropdown_group(){
		$options = array(
			''	=> 'Pilih Group',
	        'murid'	=> 'Murid',
	        'instruktur'	=> 'Instruktur',
	        'adminop'	=> 'Admin OP',
	        'admin'	=> 'Administrator',
	        // 'user'	=> 'Instruktur',

		);
		
		return form_dropdown('group', $options, 0, 'id="group"');	
	}


	/* ************************************************ */
	/* **************** FRONT END ********************* */
	/* ************************************************ */

	function have_post($tipe=NULL, $category=NULL, $limit=NULL){
		global $SConfig;
		$_this =& get_instance();	

		$website_setting = $_this->site->website_setting;
							
		$have_post = FALSE;
		$result = '';		
		$offset = 0 ;
		$post_except = NULL;

		if(empty($limit)) $limit = $website_setting['artikel_index'];

		if($_this->uri->segment(2) == 'hal'){
			if(!empty($category)){
				$offset = 0;
				$array_where = array('post_status' => 'publish', "post_category LIKE" => "%$category%");	
			}
			else{
				$getoffset = $_this->uri->segment(3);
				(!empty($getoffset)) ? $offset = ($getoffset - 1) * $limit : $offset = 0;

				/* untuk hasil dari pencarian */
				if(post_search_keyword()){
					$post_search_keyword = post_search_keyword();					
					$array_where = array('post_status' => 'publish', "post_title LIKE" => "%$post_search_keyword%");	
					$limit = $website_setting['pencarian'];
				}
				else{
					$array_where = array('post_status' => 'publish');	
				}
				
			}			
		}
		else{

			$getoffset = $_this->uri->segment(4);
			if(empty($category)) {
				$category = $_this->uri->segment(2);		
				$limit = $website_setting['artikel_index'];		
			}	
			
			(!empty($getoffset)) ? $offset = ($getoffset - 1) * $limit : $offset = 0;
			$array_where = array('post_status' => 'publish', "post_category LIKE" => "%$category%");
			$post_except = post_except();
		}
		
		/* yang ini digunakan ketika tipe dan kategori telah disetting ditemplate */
		$model = "Artikel_model";
		$function = "get_artikel";
					
		if($tipe == 'produk'){
			$model = "Produk_model";
			$function = "get_produk";
		}

		$result = $_this->$model->$function($array_where,$limit,$offset,NULL,NULL,NULL,$post_except); 						

		/* jika resultnya lebih dari 0 isi arraynya */
		if(count($result) > 0) {
			$_this->post->post_record = $result;
			$have_post = TRUE;
		}


		return $have_post;
	}

	function post(){
		$_this =& get_instance();
		return $_this->post->post_record;		
	}

	function permalink($post=NULL,$category=NULL){
		$url = NULL;
		
		if(!empty($category)){
			$url = set_url($post->post_type). '/'. $category;
		}

		else{
			if(!empty($post->post_category)){
				$split_category = explode(',', $post->post_category);
				(count($split_category) > 0) ? $category = $split_category[0] : $category = $post->post_category;
				$url = set_url($post->post_type) . '/'.$category . '/' . $post->post_name;
			}			
		}

		return $url;
	}

	function post_meta($type=NULL,$post=NULL){
		$_this =& get_instance();
		$post_detail = $_this->post->post_detail;
		$display = NULL;		

		if(empty($post)) $post = $post_detail;

		$atribut = json_decode($post->post_attribute);
		
		switch($type){
			case 'time': $display = mdate('%d/%m/%Y',strtotime($post->post_date)); break;
			case 'author' : $display = $post->username; break;
			case 'image' : $display = @$post->post_image; break; 
			case 'price' : $display = rupiah(@$post->post_discount); break; 
			case 'ID' : $display = $post->post_ID; break;

			default: $post->$type; break;
		}

		return $display;
	}

	function post_pagination($type=NULL){
		global $SConfig;
		$_this =& get_instance();
		$_this->load->library('pagination');
		$load_model = 'Kursus_model';
		$perpage = $SConfig->_frontend_perpage;	
		$website_setting = $_this->site->website_setting;

		/* pagination config for post category */
		if($_this->uri->segment(2) == 'hal'){
			$base_url = set_url($type).'/hal';

			if(post_search_keyword()){
				$post_search_keyword = post_search_keyword();					
				$total_rows = $_this->$load_model->count();
				$perpage = $limit = $website_setting['pencarian'];
			}
			if($type == 'kursus-saya'){
				$total_rows = $_this->$load_model->get_row_count_kursus(array('dk_userid' => $_SESSION['ID']));
			}
			else{
				$total_rows = $_this->$load_model->count();	
			}

			
		}
		else{

			if($type == 'kursus-saya'){
				$total_rows = $_this->$load_model->get_row_count_kursus(array('dk_userid' => $_SESSION['ID']));
			}
			else{
				$kategori = $_this->uri->segment(2);
				$total_rows = $_this->$load_model->count();
			}
			$base_url = set_url($type).'/'.'hal';
		}

		$config['base_url'] = $base_url;
        $config['total_rows'] = $total_rows;
        $config['per_page'] = $perpage;
        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';            
        $config['prev_link'] = '&laquo;';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['next_link'] = '&raquo;';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';	
        $config['first_link'] = '&laquo; First';
		$config['first_tag_open'] = '<li class="prev page">';
		$config['first_tag_close'] = '</li>';
        $config['last_link'] = 'Last &raquo;';
		$config['last_tag_open'] = '<li class="next page">';
		$config['last_tag_close'] = '</li>';
		$config['use_page_numbers'] = TRUE ;
		$config['reuse_query_string'] = TRUE;


		$_this->pagination->initialize($config);

		return $_this->pagination->create_links();		
	}

	function post_title($post=NULL){
		$_this =& get_instance();
		
		if(!empty($post)){
			$post_title = $post->post_title;
		}
		else{			
			$post_title = $_this->post->post_detail->post_title;
		}
		
		return $post_title;
	}

	function post_content($post=NULl,$introsize=NULL){
		
		$_this =& get_instance();

		if(!empty($post)){
			$post_content = $post->post_content;
		}
		else{
			$post_content = $_this->post->post_detail->post_content;
		}

		if(!empty($introsize)){
			$post_content = character_limiter( strip_tags($post_content),$introsize);
		}

		return post_shortcode($post_content);
	}

	function post_category($post=NULL,$permalink=TRUE){
		$_this =& get_instance();

		if(empty($post)){
			$post = $_this->post->post_detail;
		}

		if(!empty($post->post_category)){
			$categories = explode(',',$post->post_category);
			if(count($categories) > 1){
				$x = 0;
				foreach($categories as $category ){
					if($x > 0){
						if($permalink == TRUE){										
							return '<a href="'.permalink($post,$category).'" class="cat">'.humanize($category).'</a>';
						}
						else{
							return $category;
						}						
					}


					$x++;
				}
			}
			else{
				if($permalink == TRUE){	
					return '<a href="'.permalink($post,$post->post_category).'" class="cat">'.humanize($post->post_category).'</a>';
				}
				else{
					return $post->post_category;
				}
			}			
		}
	}

	function post_except($postid=NULL){
		$_this =& get_instance();
		if(!empty($postid)){
			$_this->post->post_except = array_merge(array($postid), $_this->post->post_except) ; 	
		}
		else{
			return $_this->post->post_except;
		}		
	}

	function post_detail($post=NULL,$param=NULL){
		$_this =& get_instance();

		if(empty($post)){
			$post = $_this->post->post_detail;
		}

		return $post->$param;		
	}	

	function comment_message($tag_open=NULL,$field=NULL,$tag_close=NULL){
		$_this =& get_instance();		
		$message = $_this->session->flashdata();
		$display = NULL;
		if(array_key_exists($field, $message)){
			$display = $tag_open.@$message[$field].$tag_close;
		}
		
		return $display;
	}

	function comment_list($limit=NULL){
		$_this =& get_instance();

		if(!empty($limit) && $limit > 0){
			$array_where = array(
					'comment_approved' => 'publish'
				);
		}
		else{
			$array_where = array(
					'comment_post_ID' => post_detail(NULL,'post_ID'),
					'comment_approved' => 'publish'
				);	
			$limit = 0;		
		}


		$display = $_this->Komentar_model->get_komentar($array_where,$limit);

		return $display;
	}
	
	function e_($param_name){
		$_this =& get_instance();
		$display = NULL;
		$template_setting = $_this->site->template_setting;
		
		if($param_name){
			$display = $template_setting[$param_name]['value'];
		}

		return $display;
	}

	function comment_feature(){
		$_this =& get_instance();	 
		$website_setting = $_this->site->website_setting;
		if(array_key_exists('setting_komentar', $website_setting)){
			return TRUE;
		}	
	}

	function head(){
		global $SConfig;
		$_this =& get_instance();
		$website_setting = $_this->site->website_setting;
		$display = NULL;

		if($_this->site->_isHome == TRUE){
			if(array_key_exists('home_meta_description', $website_setting)){
				$display .= '<meta name="description" content="'.$website_setting['home_meta_description'].'" />'."\n";
			}

			if(array_key_exists('home_meta_keyword', $website_setting)){		
				$display .= '<meta name="keyword" content="'.$website_setting['home_meta_keyword'].'" />'."\n";		
			}				
		}
			
		else if($_this->site->_isDetail == TRUE){
			$post_detail = $_this->post->post_detail; 
			$atribut = json_decode($post_detail->post_attribute);
			if(array_key_exists('auto_meta', $website_setting)){
				if(array_key_exists('meta_keyword', $atribut) && !empty($atribut->meta_keyword)){
					$display .= '<meta name="keyword" content="'.$atribut->meta_keyword.'" />'."\n";		
				}
				else{
					$display .= '<meta name="keyword" content="'.strip_tags(post_content($post_detail, 100)).'" />'."\n";
				}

				if(array_key_exists('meta_description', $atribut) && !empty($atribut->meta_description)){
					$display .= '<meta name="description" content="'.$atribut->meta_description.'" />'."\n";
				}
				else{
					$display .= '<meta name="keyword" content="'.strip_tags(post_content($post_detail, 100)).'" />'."\n";
				}

			}
		}

		foreach($website_setting as $key => $val){
			switch($key){
				case "url_canonical" : 
					$display .= '<link rel="canonical" href="'.current_url().'" />'."\n"; 
					break;

				case "google_webmaster" : 
					$display .= $website_setting['google_webmaster']."\n";
					break;

				case "google_analytic" : 
					$display .= "<script>
					  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
					  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
					  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
					  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

					  ga('create', '".$website_setting['google_analytic']."', '".$SConfig->_site_name."');
					  ga('send', 'pageview');

					</script>"."\n";
					break;

				case "alexa_verification" : 
					$display .= $website_setting['alexa_verification']."\n";
					break;
			}
		}

		return $display;
	}

	function _isHomePaging(){
		global $SConfig;
		$_this =& get_instance();
		if($_this->uri->segment(2) == 'hal'){
			return TRUE;
		}
	}

	function post_reset(){
		$_this =& get_instance();
		$_this->post->post_except = array();	
		return TRUE;
	}
	
	function post_menu($type=NULL,$navbar=FALSE){
		global $SConfig;
		$_this =& get_instance();
		$menu = '';
		if($type == 'artikel'){
			$record = $_this->Kategori_model->get_by(array('category_type' => 'artikel'));	
			$recordtree = buildTree($record,0,'artikel');
			if($navbar == TRUE){
				$menu = menu($recordtree,0,FALSE,TRUE,'artikel');
			}
			else{

			}
		}
		else if($type == 'produk'){
			$record = $_this->Kategori_model->get_by(array('category_type' => 'produk'));	
			$recordtree = buildTree($record,0,'artikel');
			if($navbar == TRUE){
				$menu = menu($recordtree,0,FALSE,TRUE,'artikel');
			}
			else{

			}			

		}
		else if($type == 'halaman'){
			$record = $_this->Halaman_model->get_by(array('post_type' => 'halaman'),NULL,NULL,FALSE,'post_ID, post_title, post_parent, post_name');		
			
			$recordtree = buildTree($record,0,'halaman');	

			if($navbar == TRUE){
				$menu = menu($recordtree,0,FALSE,TRUE,'halaman');
			}
			else{
				$menu = menu($recordtree,0,FALSE,FALSE,'halaman');
			}
		}

		return $menu ;
	}

	function buildTree(array $elements, $parentId = 0 ,$type=NULL) {
	    $branch = array();

	    foreach ($elements as $element) {
		    if($type == 'artikel' ){
		    	$parent = $element->category_parent ; 
		    	$id =  $element->category_ID ; 
		    }
		    else{
		    	$parent = $element->post_parent ;
		    	$id = $element->post_ID ;
		    }

	        if ($parent == $parentId) {
	            $children = buildTree($elements, $id,$type);
	            if ($children) {
	                $element->children = $children;
	            }
	            $branch[] = $element;
	        }
	    }

	    return $branch;
	}

	function menu($arr,$parent,$haschild=FALSE,$navbar=FALSE,$type=NULL) {
        
        if($haschild == TRUE){
        	if($navbar == TRUE){
        		echo '<ul class="dropdown-menu" role="menu">'."\n";
        	}
        	else{
        		echo '<ul class="menu-list">'."\n";
        	}
        }


        foreach ($arr as $val) {
		    if($type == 'artikel' ){
		    	$link = set_url($val->category_type). '/' . $val->category_slug ;
		    	$title = $val->category_name;
		    	$parent = $val->category_parent;
		    }
		    else{
		    	$link = set_url($type). '/' . $val->post_name ;
		    	$title = $val->post_title;
		    	$parent = $val->post_parent;
		    }


            if (!empty($val->children)) {
            	if($navbar == TRUE){
	                echo '<li class="dropdown">';
	                echo '<a href="'. $link . '" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">' . $title .'<span class="caret"></span></a>' ;
	                menu($val->children,$parent,TRUE,$navbar,$type);
	                echo '</li>'."\n";
            	}
            	else{
	                echo '<li >';
	                echo '<a href="' .$link. '">' . $title  ;
	                menu($val->children,$parent,TRUE,$navbar,$type);
	                echo '</li>'."\n";            		
            	}
            } 

            else {
                echo '<li><a href="'. $link . '">' . $title . '</a></li>'."\n";

            }
            
        }

        if($haschild == TRUE){
        	echo "</ul>"."\n";
    	}
    }

    function breadcrumb($post=NULL){
		$_this =& get_instance();
		$totalsegment = $_this->uri->total_segments();

		if($totalsegment == 3 && ($_this->uri->segment(2) != 'hal' || $_this->uri->segment(3) != 'hal' ) ){
			if(empty($post)){
				$post = $_this->post->post_detail;
			}

			if(!empty($post->post_category)){
				$split_category = explode(',', $post->post_category);
				(count($split_category) > 0) ? $category = $split_category[0] : $category = $post->post_category;				
			}	

			$display = '<ol class="breadcrumb">
                <li><a href="'.base_url().'">Home</a></li>
                <li><a href="'.set_url($post->post_type).'">'.humanize($post->post_type).'</a></li>
                <li class="active">'.humanize($category).'</li>
            </ol>';
		}
		/*
			<ol class="breadcrumb">
                <li><a href="#">Home</a></li>
                <li><a href="#">Library</a></li>
                <li class="active">Data</li>
            </ol>
		*/
		

		
		return $display;    	
    }

    function post_popular($where = NULL, $limit = NULL){
		global $SConfig;
		$_this =& get_instance();	

		if($where['post_type'] == 'artikel'){
			$result = $_this->Artikel_model->get_popular($where, $limit );
		}			
		/* jika resultnya lebih dari 0 isi arraynya */
		if(count($result) > 0) {
			$_this->post->post_record = $result;
			$custom_query = TRUE;
		}

		return $custom_query;		
    }

    function _isCategory(){
		global $SConfig;
		$_this =& get_instance();
		return humanize($_this->uri->segment(2));  	
    }

    function post_search_keyword(){
    	$_this =& get_instance();
    	return strip_tags($_this->post->post_search_keyword);
    }

    function resize_img($image=NULL, $width=NULL, $height=NULL, $type=NULL){
    	$_this =& get_instance();

    	if(empty($image)){
    		$image = set_url('assets/images').'/no_image.jpg';
    	}

    	return $_this->site->resize_img($image, $width, $height, $type);
    }	

    function form_dropdown_product_size(){
		global $SConfig;
		$_this =& get_instance();	
		$options = array();
		
		if(empty($post)){
			$post = json_decode($_this->post->post_detail->post_attribute);
		}

		$post_attribute_size = explode(',', $post->post_size);
		if(count($post_attribute_size) > 1){
			foreach ($post_attribute_size as $k => $v) {
				$options[$v] = $v;
			}	

			$options = array_merge(array('0' => 'Pilih Ukuran/Size Produk'), $options);		
			return form_dropdown('post_size', $options, '0', 'id="post_size" class="form-control"');	
		}
		else{
			return '<p><span class="value-statistic">-</span></p>';
		}
    }

    function form_dropdown_product_color(){
		global $SConfig;
		$_this =& get_instance();	
		$options = array();
		
		if(empty($post)){
			$post = json_decode($_this->post->post_detail->post_attribute);
		}

		$post_attribute_color = explode(',', $post->post_color);
		if(count($post_attribute_color) > 1){
			foreach ($post_attribute_color as $k => $v) {
				$options[$v] = $v;
			}	
			$options = array_merge(array('0' => 'Pilih Warna/Color Produk'), $options);
			return form_dropdown('post_color', $options, '0', 'id="post_color" class="form-control"');    				
		}

		else{
			return '<p><span class="value-statistic">-</span></p>';
		}		
    }

    function rupiah($price){
    	return "Rp ".number_format(@$price,0,".", ".");
    }

	function random_string($len = 10) {
	    $word = array_merge(range('a', 'z'), range('A', 'Z'), range(0, 9));
	    shuffle($word);
	    return substr(implode($word), 0, $len);
	}    

    function ceiling($number, $significance = 1){
        return ( is_numeric($number) && is_numeric($significance) ) ? (ceil($number/$significance)*$significance) : false;
    }

    function user_menu($navbar=FALSE){
    	$_this =& get_instance();
    	if($navbar == TRUE){
    		if(get_user_info('logged_in') == TRUE){
		    	$display = '<li class="dropdown">
	              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-user"></i> Menu User <span class="caret"></span></a>
	              <ul class="dropdown-menu" role="menu">
	                <li><a href="'.set_url('user/setting').'">Setting Akun</a></li>
	                <li><a href="'.set_url('halaman/transaksi/daftar').'">Daftar Transaksi</a></li>
	                <li><a href="'.set_url('user/logout').'">Logout!</a></li>
	              </ul>
	            </li>';
            }
            else{
            	$display = '<li><a href="'.set_url('user/login').'"><i class="fa fa-lock"></i> Login User!</a></li>';
            }
            return $display;
    	}
    } 

    function post_shortcode($post_content){
    	$shortcode_search = array(
    			'%%form-konfirmasi%%'
    		);

    	$shortcode_replace = array(
    			'
    			<div class="rows"><div class="col-md-8">
    			<form class="form-horizontal" id="form-konfirmasi" method="POST" action="'.set_url('produk/transaksi/kirim_konfirmasi').'">
					<div class="form-group">
						<label class="control-label col-xs-3">Atas Nama</label>
						<div class="col-xs-9">
						    <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" required>                                
						</div>
					</div>                         
					<div class="form-group">
						<label class="control-label col-xs-3">Email</label>
						<div class="col-xs-9">
							<input type="email" class="form-control" id="email" name="email"   required>
						</div>
					</div>        
					<div class="form-group">
						<label class="control-label col-xs-3">No. HP</label>
						<div class="col-xs-9">
						    <input type="text" class="form-control" id="no_hp" name="no_hp"  required>						   
						</div>
					</div>	

					<div class="form-group">
						<label class="control-label col-xs-3">No. Invoice</label>
						<div class="col-xs-9">
						    <input type="text" class="form-control" id="transaction_id" name="transaction_id" required>						   
							<p class="help-block">Kosongkan saja jika Anda tidak mengetahuinya, atau silahkan tanya Admin</p>
						</div>
					</div>	
					<div class="form-group">
						<label class="control-label col-xs-3">Jumlah Transfer</label>
						<div class="col-xs-9">
						    <input type="text" class="form-control" id="total" name="total" required>						   
						</div>
					</div>	

					<div class="form-group">
						<label class="control-label col-xs-3">Pembayaran Ke</label>
						<div class="col-xs-9">
						    '.form_dropdown_rekening_bank().'					   
						</div>
					</div>	

					<div class="form-group">
						<label class="control-label col-xs-3">Bank Pengirim</label>
						<div class="col-xs-9">
						    <input type="text" class="form-control" id="dari_rek_bank" name="dari_rek_bank" required>						   
						</div>
					</div>	

					<div class="form-group">
						<label class="control-label col-xs-3">No.Rek Pengirim</label>
						<div class="col-xs-9">
						    <input type="text" class="form-control" id="dari_rek_no" name="dari_rek_no" required>						   
						</div>
					</div>	

					<div class="form-group">
						<label class="control-label col-xs-3">Atas Nama Rek Pengirim</label>
						<div class="col-xs-9">
						    <input type="text" class="form-control" id="dari_rek_atas_nama" name="dari_rek_atas_nama" required>						   
						</div>
					</div>	

					<div class="form-group">
						<div class="col-xs-offset-3 col-xs-9">
							<button type="submit" class="btn btn-primary input-block-level"><span class="glyphicon glyphicon-ok"></span> Konfirmasikan!</button>
						</div>
					</div>
   			
    			</form>
    			</div>
    			<div class="col-md-4"></div>
    			</div>'
    		);

    	return str_replace($shortcode_search, $shortcode_replace, $post_content);
    }   

    function form_dropdown_rekening_bank(){
    	$_this =& get_instance();
    	$_this->load->model('Konfigurasi_model');
		$setting = $_this->Konfigurasi_model->get_by(array('option_name' => 'website_setting'),NULL,NULL,TRUE);
		if($setting){
			$website_setting = unserialize($setting->option_value);	
		}
    	$options = array();
    	if(@$website_setting['jenis_rekening']){
	     	for($x=0;$x<count($website_setting['jenis_rekening']);$x++){
	    		$options[$website_setting['jenis_rekening'][$x].'_'.$website_setting['nomor_rekening'][$x].'_'.$website_setting['atas_nama'][$x]] = $website_setting['jenis_rekening'][$x].' - '.$website_setting['nomor_rekening'][$x].' a/n '.$website_setting['atas_nama'][$x];
	    	}   		
    	}

    	$options = array_merge(array(''=>'Pilih Rekeningnya'), $options);
    	
    	return form_dropdown('tujuan', $options, '0', 'id="tujuan" class="form-control"');
    }

    function form_transaction_status(){
    	$_this =& get_instance();
    	$options = array(
    			'Pilih Statusnya',
    			'pending' => 'Pending',
    			'sudah_transfer' => 'Sudah Transfer',
    			'sudah_dikirim' => 'Sudah Dikirim'
    		);

    	return form_dropdown('transaction_status', $options, NULL, 'id="transaction_status" class="form-control"');
    }

    function print_addr($transaksi,$detil_transaksi){
    	global $SConfig;
    	$_this =& get_instance();

		$_this->load->model('Konfigurasi_model');
		$setting = $_this->Konfigurasi_model->get_by(array('option_name' => 'website_setting'),NULL,NULL,TRUE);
		if($setting){
			$website_setting = unserialize($setting->option_value);	
		}
		
		require_once APPPATH.'third_party/class.clsMsDocGenerator.php';	
		
		$doc = new clsMsDocGenerator('PORTRAIT', 'A4');
		$doc->setFontFamily('Times New Roman');
		$doc->setFontSize('14');
		$doc->addParagraph('Data pengiriman paket Tanggal '. date('d-m-Y'),array('text-align' => 'center', 'font-size' => '16pt', 'font-weight' => 'bold'));
		$doc->addParagraph('(Dokumen ini dapat langsung di print/cetak)',array('text-align' => 'center', 'font-size' => '11pt'));
		$doc->addParagraph('');
		
		$doc->startTable(NULL, 'normalTable');
		$header = array('header 1', 'header 2', 'header 3', 'header 4');
		$aligns = array('center', 'center', 'center', 'right');
		$valigns = array('middle', 'middle', 'middle', 'bottom');
		$column = 2;
		$totaldata = count($transaksi);
		$rowdata = ceil($totaldata / $column);
		

		$x = 0;
		for($row = 1; $row <= $rowdata; $row++){
			$cols = array();
			

			for($col = 1; $col <= $column; $col++){

				$provinsi = $_this->site->provinsi;

				$kota = json_decode(file_get_contents(base_url('assets/js/kota.json')));					
				
				foreach($kota as $key){
					if(@$transaksi[$x]->kota == $key->id){
						@$transaksi[$x]->kota = $key->name;
					} 
				}
				
				@$transaksi[$x]->provinsi = @$provinsi[$transaksi[$x]->provinsi];

				if(@$transaksi[$x]->nama_lengkap){
					$display = 'Kepada:<br />
					<font size=5><b>'.$transaksi[$x]->nama_lengkap.'</b></font><br /><br />
					Alamat: <br />
					'.$transaksi[$x]->alamat.'<br />Kota/Kab.
					'.$transaksi[$x]->kota.', Prov. '.$transaksi[$x]->provinsi.'<br />
					No. Telp: '.$transaksi[$x]->no_telepon.'<br />
					No. HP: '.$transaksi[$x]->no_handphone.' <br />
					Email: '.$transaksi[$x]->email.'<br />
					Jasa Pengiriman: JNE '.strtoupper($transaksi[$x]->tax_type).'<br />
					Pemesanan: <font size="2">';

					$display .= detail_transaction($detil_transaksi,$transaksi[$x]->transaction_id);

					$display .= '</font>
					<br /><br />
					<b>Pengirim: </b><br />
					'.$website_setting['judul'].' ('.$website_setting['domain'].')<br />
					'.$website_setting['alamat'].', 
					No. HP: '.$website_setting['handphone'].', No. Telp: '.$website_setting['telephone'].'
					';

					$cols[] = $display;
				}
				else{
					$cols[] = '';
				}
				
				$x++;
			}
			$doc->addTableRow($cols, NULL, NULL, array('width' => '30%', 'padding' => '15px', 'font-size' => '12pt', 'height' => 'auto', 'text-align' => 'left'));
			unset($cols);
		}

		$doc->endTable();

		$doc->output('pengiriman-paket-'.date('d-m-Y').'.doc', $SConfig->_document_root.'/downloads');    	
    	
    	return TRUE;
    }

	function detail_transaction($detil, $transaction_id, $detail=FALSE){
		$display = NULL;
		if(!empty($detil)){
			$display .= '<ul class="transdetail">';
			foreach($detil as $trx){
				
				if($trx->transaction_id == $transaction_id){
					$option_show = NULL;
					$option = json_decode($trx->option);
					if(!empty($option->Color) || !empty($option->Size)){
						$option_show = '(Warna: '.$option->Color.'<br />Ukuran: '.$option->Size.')';		
					}
				
					$display .= '<li>';
					$display .= $trx->name.'<strong> x '.$trx->quantity.'</strong> '.$option_show;										
					$display .= '</li>';
				}
				
			}			
			$display .= '</ul>';
			return $display;
		}
		
		return $display;
	}
   
    function form_confirm_status(){
    	$_this =& get_instance();
    	$options = array(
    			'Pilih Statusnya',
    			'pending' => 'Pending',
    			'sudah_transfer' => 'Sudah Transfer',
    		);

    	return form_dropdown('confirm_status', $options, NULL, 'id="confirm_status" class="form-control"');
    }



	function get_slider(){
		$_this =& get_instance();

		$hasil = $_this->Slider_model->get_by(array('slider_status'=>'ON', 'slider_type'=> 'slider'));

		return $hasil;
	}
	function get_related_portofolio(){
		$_this =& get_instance();

		$hasil = $_this->Slider_model->get_related();

		return $hasil;
	}
?>