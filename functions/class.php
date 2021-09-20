<?php
/**
 *
 */
class PechenkiExport
{

	static $instanse;
	public $taxonomy ='product_cat';
	public $taxonomy_type  ='product';
	public $include_cat = [];
	public $post__not_in = [];
	public $posts_per_page = '-1';
	public $category__not_in = [];
	public $list_portal_id = [];
	public $ph_prom_file = 'shop';
	public $ph_prom_text =  '';



	function __construct()
	{
		if(!empty(self::$instanse)) return new WP_Error( 'duplicate_object','error');

		add_action('wp_ajax_exportxmlprom',array($this,'pechenki_function_export'));
		add_action('wp_ajax_nopriv_exportxmlprom', array($this,'pechenki_function_export'));

		add_action('wp_ajax_exportxmlya',array($this,'pechenki_function_exportxmlya'));
		add_action('wp_ajax_nopriv_exportxmlya', array($this,'pechenki_function_exportxmlya'));

		add_action('wp_ajax_pexportprom_ajax',array($this,'pexportprom_ajax_function'));
		add_action('wp_ajax_psaveportaid',array($this,'psaveportaid_fun'));
		add_action('admin_menu', array($this,'utm_repl_settings'));
		if (get_option('is_notcategory')) {
			$this->include_cat = get_option('is_notcategory');
		}
		if (get_option('is_notproduct')) {
			$this->post__not_in = get_option('is_notproduct');
		}
		if (get_option('p_alldata')) {
			$this->list_portal_id = unserialize(get_option('p_alldata'));
		}
		if (get_option('ph_prom_file')) {
				$this->ph_prom_file = get_option('ph_prom_file');
		}

		if (get_option('ph_prom_text')) {
				$this->ph_prom_text = get_option('ph_prom_text');
		}


	}


	public function utm_repl_settings()
		{
		  add_menu_page ('Pechenki-export', 'Ph-Prom', 'manage_options', 'pechenki-export', array($this,'utm_repl_setting_page'), plugin_dir_url( __FILE__ ) .'icon-plugin.png');
		  add_submenu_page( 'pechenki-export', 'Осн. Настройки', 'Доп. Настройки', 'manage_options', 'pechenki-export-sub', array($this,'pechenki_export_sub' ));

		}

	public function utm_repl_setting_page()
		{

				$listCategory = $this->getCategoryListAll();
		    wp_enqueue_script('utm_repl_ajax', plugin_dir_url( __FILE__ ) . '../assets/js/utm_ajax.js');
		    wp_enqueue_style('utm_repl_css', plugin_dir_url( __FILE__ ) . '../assets/css/utm_css.css');
		    require_once( PH_DIR . 'temlate/settings.php' );
		}

	public function pechenki_export_sub(){
	$productAll = $this->getListProduct(array(
			'post_type' => $this->taxonomy_type,
			'posts_per_page'=>$this->posts_per_page,
		));

    	wp_enqueue_style('utm_repl_css', plugin_dir_url( __FILE__ ) . '../assets/css/utm_css.css');
	    wp_enqueue_script('utm_repl_ajax', plugin_dir_url( __FILE__ ) . '../assets/js/utm_ajax.js');

    	require_once( PH_DIR . 'temlate/settings-sub.php' );
	}

	public static function instance(){

		if (empty(self::$instanse)){


			self::$instanse = new self;

		}

		return self::$instanse;


	}

	/*
	* catigories
	*/
	public function getCategoryListAll(){
		return get_categories( [
			'taxonomy'     => $this->taxonomy,
			'type'         => $this->taxonomy_type,
			'child_of'     => 0,
			'parent'       => '',
			'orderby'      => 'name',
			'order'        => 'ASC',
			'hide_empty'   => 1,
			'hierarchical' => 1,
			'number'       => 0,
			'pad_counts'   => false,

		] );
	}

	/*
	* catigories
	*/
	public function getCategoryList(){
		return get_categories( [
			'taxonomy'     => $this->taxonomy,
			'type'         => $this->taxonomy_type,
			'child_of'     => 0,
			'parent'       => '',
			'orderby'      => 'name',
			'order'        => 'ASC',
			'hide_empty'   => 1,
			'hierarchical' => 1,
			'exclude'      => $this->category__not_in,
			'include'      => $this->include_cat,
			'number'       => 0,
			'pad_counts'   => false,

		] );
	}

	public function pechenki_function_export(){

		$categories = $this->getCategoryList();
		$products = $this->getListProduct(array(
				'post_type' => $this->taxonomy_type,
				'post__not_in'=>$this->post__not_in,
				'posts_per_page'=>$this->posts_per_page,
				'post_status'=>'publish',
				'tax_query'=> array(
					'relation' => 'AND',[
					'taxonomy' => $this->taxonomy,
					'field'    => 'id',
					'terms'    => $this->include_cat,
					'operator' => 'IN',
				])
			));

		// include PH_DIR . 'functions/export.php' ;
		include PH_DIR . 'functions/export-google.php' ;
	}


	public function pechenki_function_exportxmlya(){

		$categories = $this->getCategoryList();
		$products = $this->getListProduct(array(
				'post_type' => $this->taxonomy_type,
				'post__not_in'=>$this->post__not_in,
				'posts_per_page'=>$this->posts_per_page,
				'post_status'=>'publish',
				'tax_query'=> array(
					'relation' => 'AND',[
					'taxonomy' => $this->taxonomy,
					'field'    => 'id',
					'terms'    => $this->include_cat,
					'operator' => 'IN',
				])
			));

		// include PH_DIR . 'functions/export.php' ;
		include PH_DIR . 'functions/export-ya.php' ;
	}

	/*
	*
	*
	*/
	public  function getPortal($id){
		  if ($id) {

		   	foreach ($this->list_portal_id as $key => $value) {
		   	 	if ($id ==  $value['id_category']) {
		   	 		return (object)$value;
		   	 	}
		   	 }
		  }
		  return false;

	}


	public function psaveportaid_fun()
	{
		$post = $_POST;
		if ($post['arr']) {
		 	update_option('p_alldata',serialize($post['arr']));
			 print_r($_POST);
		}

		die();
	}


	public function getListProduct($ars){

		$my_posts = new WP_Query;

		// делаем запрос
		$myposts = $my_posts->query($ars);

		return $myposts;
	}


	/**
	*
	*/

	public function pexportprom_ajax_function()
	{
		$fn = $_POST['fn'];
		if (isset($fn) && !empty($fn)) {
			print_r($this->$fn());

		}
			die();
	}

	public function add_line($value='')
	{
	 	return "<tr class=\"block__utm\">
					<td>
						".Phtml::inputText(['name'=>'id_portal'])."


					</td>
					<td>

						".Phtml::select([
							'name'=>'id_category',
							'value'=>27,
							'data'=> Phtml::arrayDataConver($this->getCategoryListAll(),'term_id','name'),
						]
						)."

					</td>
					<td><span class='utm_repl_del'><span class='dashicons dashicons-minus'></span></td>
			</tr>";
	}



	public function keywordru ($contents,$symbol=5,$words=35){
	$contents = @preg_replace(array("'<[\/\!]*?[^<>]*?>'si","'([\r\n])[\s]+'si","'&[a-z0-9]{1,6};'si","'( +)'si"),
	array("","\\1 "," "," "),strip_tags($contents));
	$rearray = array("~","!","@","#","$","%","^","&","*","(",")","_","+",
		                 "`",'"',"№",";",":","?","-","=","|","\"","\\","/",
		                 "[","]","{","}","'",",",".","<",">","\r\n","\n","\t","«","»");

	$adjectivearray = array("ые","ое","ие","ий","ая","ый","ой","ми","ых","ее","ую","их","ым",
		                        "как","для","что","или","это","этих",
		                        "всех","вас","они","оно","еще","когда",
		                        "где","эта","лишь","уже","вам","нет",
		                        "если","надо","все","так","его","чем",
		                        "при","даже","мне","есть","только","очень",
		                        "сейчас","точно","обычно"
	                        );


	$contents = @str_replace($rearray," ",$contents);
	$keywordcache = @explode(" ",$contents);
	$rearray = array();

	foreach($keywordcache as $word){
		if(strlen($word)>=$symbol && !is_numeric($word)){
			$adjective = substr($word,-2);
			if(!in_array($adjective,$adjectivearray) && !in_array($word,$adjectivearray)){
				$rearray[$word] = (array_key_exists($word,$rearray)) ? ($rearray[$word] + 1) : 1;
			}
		}
	}

	@arsort($rearray);
	$keywordcache = @array_slice($rearray,0,$words);
	$keywords = "";

	foreach($keywordcache as $word=>$count){
		$keywords.= ",".$word;
	}

	return substr($keywords,1);
}
	public function listAttrWc($productId){
		 $attributes = wc_get_product($productId)->get_attributes();
		 	$out ='';
			 foreach ( $attributes as $v => $attribute ) :


					$values = wc_get_product_terms($productId, $attribute['name'], array( 'fields' => 'names' ) );
					$att_val = apply_filters( 'woocommerce_attribute', wpautop( wptexturize( implode( ', ', $values ) ) ), $attribute, $values );

					if( empty( $att_val ) )
						continue;

					$has_row = true;
					

					
					$out .= wc_attribute_label( $attribute['name'] );
					$out .= ': ';

				 	$out .= strip_tags($att_val); 
				 	$out .= PHP_EOL;
				

				endforeach; 
				return $out;
	}

}
