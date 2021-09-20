<?php





// echo "<pre>";

// // print_r($products);

// $term = get_post_meta(2988);

// print_r($term);

// die();



// header('Content-type: text/xml');



$xml = new DomDocument('1.0');

$xml->formatOutput = true;



	$shop = $xml->createElement('shop');

	$xml->appendChild($shop);



	/* +catalog +*/

	$catalog = $xml->createElement('catalog');



	$shop->appendChild($catalog);

	/*- catalog-*/

	/*++CATEGORY++*/

	foreach ($categories as $cat_key => $cat_value) {



		$category = $xml->createElement('category',$cat_value->name);

		$category->setAttribute('id', $cat_value->term_id);

		if ($cat_value->parent) {

			$category->setAttribute('parentID',$cat_value->parent);



		}

		$portal = $this->getPortal($cat_value->term_id);
		$portal_parent = $this->getPortal($cat_value->parent);


		if ($portal) {

			$category->setAttribute('portal_id',$portal->id_portal);



		}

		if ($portal_parent) {

			$category->setAttribute('portal_id',$portal_parent->id_portal);



		}



		$catalog->appendChild($category);

	}

	/*--CATEGORY--*/



	$items = $xml->createElement('items');

	$shop->appendChild($items);





		foreach ($products as $product_key =>$product) {



                          $listad_meta = get_post_meta($product->ID,'stock_status',true);



					if ($listad_meta != 'instock') continue;

			$item = $xml->createElement('item');

			$item->setAttribute('id',$product->ID);

			$item->setAttribute('selling_type','r');

			$items->appendChild($item);



			$name = $xml->createElement('name',$product->post_title);

			$item->appendChild($name);

			/* + categoryId +*/

				$term = get_the_terms($product->ID,$this->taxonomy);

				$categoryId = $xml->createElement('categoryId',$term[0]->term_id);

				$item->appendChild($categoryId);

			/*- categoryId -*/



			/*+ priceuah +*/

				$priceuah = $xml->createElement('priceuah',get_post_meta($product->ID,'_price',true));

				$item->appendChild($priceuah);

			/*- priceuah -*/

			/*+ image +*/

				$image	 = $xml->createElement('image',get_the_post_thumbnail_url($product->ID,'fuul'));

				$item->appendChild($image);

			/*- image -*/



			/*+ vendor +*/
			$vendor_meta = get_post_meta($product->ID,'vendor-code',true);

				if ($vendor_meta) {
						$item->appendChild($xml->createElement('vendor',$vendor_meta));
				}
			/*- vendor -*/

			/*+ vendorCode +*/
			$vendorCode = get_post_meta($product->ID,'_sku',true);

				if ($vendorCode) {
						$item->appendChild($xml->createElement('vendorCode',$vendorCode));
				}
			/*- vendor -*/

			/* + the_excerpt() +*/

				$excerpt = get_the_excerpt($product->ID);

				if ($excerpt) {
					$desc_xml = $xml->createElement('description');

					$desc_xml->appendChild($xml->createCDATASection($excerpt));

					$item->appendChild($desc_xml);
				}



			/* - the_excerpt() -*/

			/*+ available +*/

				$listad_meta = get_post_meta($product->ID,'stock_status',true);



					if ($listad_meta == 'instock') {

						$available = $xml->createElement('available','true');

					}

					if ($listad_meta == 'onbackorder') {

						$available = $xml->createElement('available','false');


					}

					$available = $xml->createElement('available',' ');

					$item->appendChild($available);





			/*- available -*/



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
