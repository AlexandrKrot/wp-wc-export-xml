<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

?>
<div id="promPechenki" class="wpap_p_prom">
  <h2>Prom xml Generation</h2>
  <div class="ph-sub">
  <p class="ph-sub-title">Соответсвие </p>

<p>ID_категории_на_портале — уникальный идентификатор категории портала, в которой будет опубликован данный товар после импорта.</p>
  <em><a href=" https://my.prom.ua/cabinet/export_categories/xls">Список категории портала </a> </em>
  <div class="sub-content">
  <form id="pidportalsetting" method="post">
  <table class="utm_table" id="utm_repl_data">
    <thead>
      <tr>
          <td><span>ID категории на портале</span></td>
          <td><span>Kатегория на сайте </span></td>

      </tr>
    </thead>



    <tbody>

    <?php



      $i = 0;
      foreach ($this->list_portal_id as $key => $value) { ?>
          <tr class="block__utm">
              <td>
                <?=Phtml::inputText(['name'=>'id_portal','value'=>$value['id_portal']]);?>


              </td>
              <td>

                <?=Phtml::select([
                  'name'=>'id_category',
                  'value'=>$value['id_category'],
                  'data'=> Phtml::arrayDataConver($listCategory,'term_id','name'),
                ]
                );?>

              </td>
              <?php
              if ($i>=1) {
              echo "<td><span class='utm_repl_del'><span class='dashicons dashicons-minus'></span></td>";
              }
               ?>

          </tr>
        <?php $i++; } ?>
    </tbody>



</table>
<div class="utm_block_btn">
  <button id="utm_add_line" title='Добавить строку'><span class='dashicons dashicons-plus-alt'></button>
  <button class="btn" id="utm_repl_save" title="Save"><span class="dashicons dashicons-yes"></span> Save</button>
</div>
  </form>
  </div>
</div>
</div>
