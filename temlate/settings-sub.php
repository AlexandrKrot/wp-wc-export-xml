<style>
	label.is_not {
    background: #f87a7a;
    color: #fff;
}
</style>
<div class="wpap_p_prom">

<form method="post" action="options.php">
<div class="form name-file">
	<input type="text" name="ph_prom_file" value="<?=$this->ph_prom_file?>">.xml
	<a class="button-primary"  href="<?=get_site_url()?>/<?=$this->ph_prom_file?>.xml" target="_blank">XML - файл</a>
	<button class="button-primary exportxmlprom"  data-action="exportxmlprom" target="_blank">Обновить файл <div class="loader">
		<img src="https://jhr.pensoft.net/i/simple_loading.gif" alt="">
	</div></button>
	<em><a href="<?=get_site_url()?>/wp-admin/admin-ajax.php?action=exportxmlprom" target="_blank">Сылка для обновления файла</a></em>

	
</div>


<input type="text" name="ph_prom_text" value="<?=$this->ph_prom_text?>" placeholder="Название магазина">	
<em><a href="<?=get_site_url()?>/wp-admin/admin-ajax.php?action=exportxmlya" target="_blank">Сылка для на ya файл</a></em>
<div class="pe-message"></div>
<?php wp_nonce_field('update-options'); ?>

			

			<div class="ph-sub">
				<p class="ph-sub-title">Включить категории</p>
				<div class="sub-content">
				<?php foreach ($this->getCategoryListAll() as $key => $value): ?>
						<input <?=((in_array($value->term_id, $this->include_cat))? 'checked':'')?> type='checkbox' name="is_notcategory[]" value="<?=$value->term_id;?>"><?=$value->name;?>
				<?php endforeach ?>
			</div>
			</div>

		<div class="ph-sub">
			<p class="ph-sub-title">Исключить товары</p>
		<div class="sub-content">
			<select class="is_notproduct" multiple name="is_notproduct[]">
				<?php foreach ($productAll as $key => $value): ?>
						<option <?=((in_array($value->ID, $this->post__not_in))? 'selected':'')?> value="<?=$value->ID;?>"><?=$value->post_title;?></option>
				<?php endforeach ?>
			</select>
		</div>

	</div>
		  <input type="hidden" name="action" value="update" />
      <input type="hidden" name="page_options" value="is_notcategory,is_notproduct,ph_prom_file,ph_prom_text" />
      <p class="submit">
      <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
      </p>

 </form>
</div>
