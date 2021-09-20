<?php
// Подключаем библиотеки CMS WordPress

// Прописываем значения, чтобы файл читался и открывался как XML
header("Content-type: text/xml; charset=UTF-8");

// print (pack('CCC', 0xef, 0xbb, 0xbf));

// Заголовок. В get_bloginfo('name') и get_bloginfo('url') берутся значения из настройки админки.
print 
"<?xml version=\"1.0\" encoding=\"UTF-8\"?>
<yml_catalog date=\"".date('Y-m-d H:i')."\">
<shop>
<name>".get_bloginfo('name')."</name>
<url>".get_bloginfo('url')."</url>
";

// Валюта. Если у вас используется альтернативная валюта или + ещё какая-то, то поменяйте данный участок кода.
print "<currencies>
<currency id='UAH' rate='1'/>
</currencies>
";


// Список категории. В примере участвует кастомная таксономия 'products_category', её замените на ту, которая создана у вас.
print "<categories>
";

foreach($categories  as $item){
	print "<category id='".$item->term_id."'";

	if($item->parent!=0){
		print " parentId='".$item->parent."'";
	}
	print ">".htmlspecialchars(trim($item->name))."</category>
	";
}
print "</categories>
";

// Список товаров. В примере участвует кастомный тип записи 'products', его замените на тот, который создан у вас.
print "<offers>
";

foreach($products as $allproduct){
	// Определяем последую категорию в дереве, к которой присвоен конкретный товар в текущем цикле. В примере участвует кастомная таксономия 'products_category', её замените на ту, которая создана у вас.
	$lastcateg='';
    if($categorys=get_the_terms($allproduct->ID,$this->$taxonomy)){
        if(count($categorys)>1){
            $arrtemp=array();
            foreach($categorys as $category){
                $arrtemp[]=$category->term_id;
            }
            
            foreach($arrtemp as $arrtempz){
                $termchildren=get_term_children($arrtempz,$this->$taxonomy);
                if($termchildren==null){
                    $lastcateg=$arrtempz;
                    break;
                }

                foreach($termchildren as $child){
                    if(!in_array($child,$arrtemp)){
                        $lastcateg=$arrtempz;
                        break 2;
                    }
                }
            }     
        }else{
            $lastcateg=$categorys[0]->term_id;
        }
    }  
	
	// Получаем картинку товара. Если она у вас хранится в мета поле, берите из него.
    $product_img=wp_get_attachment_image_src(get_post_thumbnail_id($allproduct->ID),'full');  
	$prod = wc_get_product($allproduct->ID );
    $term = get_the_terms($allproduct->ID,$this->taxonomy);

print 
"
<offer id='".$allproduct->ID."' available='true'>
<url>".get_permalink($allproduct->ID)."</url>
<price>".$prod->get_regular_price()."</price>
<currencyId>UAH</currencyId>
<categoryId>".$term[0]->term_id."</categoryId>
";

if($product_img[0])
print "<picture>".$product_img[0]."</picture>
";

print "<name>".htmlspecialchars($allproduct->post_title)."</name>
<description><![CDATA['".htmlspecialchars( $this->listAttrWc($allproduct->ID))."]]></description>
</offer>
";
}

print "</offers>
";
print "</shop>
</yml_catalog>
";

wp_die();
?>