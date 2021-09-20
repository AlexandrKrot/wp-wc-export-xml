<?php





// echo "<pre>";

// print_r($products);

// $term = get_post_meta(4569,'_product_attributes');

//  print_r($this->listAttrWc(4569));

// die();



// header('Content-type: text/xml');



$xml = new DomDocument('1.0','UTF-8');



$xml->formatOutput = true;



	$shop = $xml->createElement('feed');
	$shop->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:g', 'http://base.google.com/ns/1.0');
	$shop->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns', 'http://www.w3.org/2005/Atom');
	$xml->appendChild($shop);

	$shop->appendChild($xml->createElement('title',$this->ph_prom_text));
	$shop->appendChild($xml->createElement('updated',date("y-m-d G:i:s")));

	$link = $xml->createElement('link');
	$link ->setAttribute('href', 'https://www.oranka.com.ua/');
	$link ->setAttribute('rel', 'alternate');
	$link ->setAttribute('type', 'text/html');
	$shop->appendChild($link);
	
	$author = $xml->createElement('author');
	$author_name = $xml->createElement('name','oranka');
	$author->appendChild($author_name);
	$shop->appendChild($author);
	/* +catalog +*/

	// $catalog = $xml->createElement('catalog');



	// $shop->appendChild($catalog);

	/*- catalog-*/

	/*++CATEGORY++*/

	// foreach ($categories as $cat_key => $cat_value) {



	// 	$category = $xml->createElement('category',$cat_value->name);

	// 	$category->setAttribute('id', $cat_value->term_id);

	// 	if ($cat_value->parent) {

	// 		$category->setAttribute('parentID',$cat_value->parent);



	// 	}

	// 	$portal = $this->getPortal($cat_value->term_id);
	// 	$portal_parent = $this->getPortal($cat_value->parent);


	// 	if ($portal) {

	// 		$category->setAttribute('portal_id',$portal->id_portal);



	// 	}

	// 	if ($portal_parent) {

	// 		$category->setAttribute('portal_id',$portal_parent->id_portal);



	// 	}



	// 	$catalog->appendChild($category);

	// }

	/*--CATEGORY--*/



	// $items = $xml->createElement('items');

	// $shop->appendChild($items);





		foreach ($products as $product_key =>$product) {
		$prod  = wc_get_product($product->ID );
                    $listad_meta = get_post_meta($product->ID,'_stock_status',true);



					if ($listad_meta != 'instock') continue;

			$item = $xml->createElement('entry');

			// $item->setAttribute('id',$product->ID);

			// $item->setAttribute('selling_type','r');

			$shop->appendChild($item);


			$item->appendChild($xml->createElement('id',$product->ID));
		

			$name = $xml->createElement('title',$product->post_title);
			$item->appendChild($name);
			$updated = $xml->createElement('updated',get_the_modified_date("y-m-d G:i:s",$product->ID))		;

			$item->appendChild($updated);


				/* + the_excerpt() +*/

				$excerpt = $this->listAttrWc($product->ID);

			

				if ($excerpt) {
					
					$item->appendChild($xml->createElement('g:description',$excerpt));	

					
				}

			$item_li =  $xml->createElement('link');
			$item_li->setAttribute('href', get_the_permalink($product->ID));

			$item->appendChild($item_li);
				
			$item->appendChild($xml->createElement('g:image_link',get_the_post_thumbnail_url($product->ID,'fuul')));	

			$item->appendChild($xml->createElement('g:condition','new'));

			

			/* - the_excerpt() -*/


/*+ available +*/

				$listad_meta = get_post_meta($product->ID,'_stock_status',true);



					if ($listad_meta == 'instock') {

						$available = $xml->createElement('g:availability','in stock');

						$item->appendChild($available);
					}

					if ($listad_meta == 'outofstock') {

						$available = $xml->createElement('g:availability','out of stock');

						 $item->appendChild($available);	
					}
					if ($listad_meta == 'onbackorder') {

						$available = $xml->createElement('g:availability','preorder');

						 $item->appendChild($available);	
					}

					// $available = $xml->createElement('g:availability',' ');

					
			/*+ priceuah +*/

				$priceuah = $xml->createElement('g:price',$prod->get_regular_price().' UAH');

				$item->appendChild($priceuah);

			/*- priceuah -*/	





/*- available -*/
			/* + categoryId +*/


			/*- categoryId -*/




			/*+ image +*/

				// $image	 = $xml->createElement('image',get_the_post_thumbnail_url($product->ID,'fuul'));

				// $item->appendChild($image);

			/*- image -*/



			/*+ vendor +*/
			// $vendor_meta = get_post_meta($product->ID,'vendor-code',true);

			// 	if ($vendor_meta) {
			// 			$item->appendChild($xml->createElement('vendor',$vendor_meta));
			// 	}
			/*- vendor -*/

			/*+ vendorCode +*/
			$vendorCode = get_post_meta($product->ID,'_sku',true);

				if ($vendorCode) {
						$item->appendChild($xml->createElement('g:mpn',$vendorCode));
				}
			/*- vendor -*/
			$brand =  get_the_terms($product->ID,'pa_виробник');

			$item->appendChild($xml->createElement('g:brand',$brand[0]->name));	

			

			$term = get_the_terms($product->ID,$this->taxonomy);

			$categoryId = $xml->createElement('g:product_type',$term[0]->name.' '.$brand[0]->name);

			$item->appendChild($categoryId);


			/*+ keywords +*/

			// $keywords_meta = $this->keywordru($product->post_title);

			// $item->appendChild($xml->createElement('keywords',$keywords_meta));

			/*- keywords -*/





		}









 $xml->save('./../'.$this->ph_prom_file.'.xml');

 if($xml->saveXML()):

	echo 'true';

endif;



die();
