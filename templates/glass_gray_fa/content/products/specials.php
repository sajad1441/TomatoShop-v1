<?php
/*
  $Id: specials.php $
  TomatoCart Open Source Shopping Cart Solutions
  http://www.tomatocart.com
 
  Copyright (c) 2009 Wuxi Elootec Technology Co., Ltd;  Copyright (c) 2007 osCommerce
 
  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License v2 (1991)
  as published by the Free Software Foundation.
*/
     
    $products_type = 'genearl';
     
    if (isset($_GET['specials']) && isset($_GET['v']) && $_GET['v'] == 1){
        $products_type = 'variants';
        $variants_result = osC_Specials::getVariantsSpecials();
        $variants_specials = $variants_result['products'];
        $Qspecials = $variants_result['listing'];
    }else {
        $products_type = 'general';
        $Qspecials = osC_Specials::getListing();
    }
?>
 
<h1><?php echo $osC_Template->getPageTitle(); ?></h1>
 
<div class="btn-group">
    <?php if ($products_type == 'general') { ?>
    <a class="btn btn-default" href="<?php echo osc_href_link(FILENAME_PRODUCTS, 'specials'); ?>"><?php echo $osC_Language->get('general_products'); ?></a>
    <a class="btn btn-black" href="<?php echo osc_href_link(FILENAME_PRODUCTS, 'specials&v=1'); ?>"><?php echo $osC_Language->get('variants_products'); ?></a>
    <?php }else { ?>
    <a class="btn btn-black" href="<?php echo osc_href_link(FILENAME_PRODUCTS, 'specials'); ?>"><?php echo $osC_Language->get('general_products'); ?></a>
    <a class="btn btn-default" href="<?php echo osc_href_link(FILENAME_PRODUCTS, 'specials&v=1'); ?>"><?php echo $osC_Language->get('variants_products'); ?></a>
    <?php }?>
</div>
 
<div class="moduleBox">
    <div class="content">
  <?php if ($products_type == 'general' && $Qspecials->numberOfRows() > 0) { ?>
      <?php
        $i = 0;
        while ($Qspecials->next()) {
          if(($i % 3 == 0) && ($i != 0))
            echo '<div style="clear:both"></div>';
       
            echo '<div style="margin-top: 10px; text-align: center; float: right; width: 31%; margin-right: 5px;">' .
                   '<span style="display:block; height: 32px; text-align: center">' . osc_link_object(osc_href_link(FILENAME_PRODUCTS, $Qspecials->value('products_id')), $Qspecials->value('products_name')) . '</span>' .
                   osc_link_object(osc_href_link(FILENAME_PRODUCTS, $Qspecials->value('products_id')), $osC_Image->show($Qspecials->value('image'), $Qspecials->value('products_name')), 'id="img_ac_specials_'. $Qspecials->value('products_id') . '"') .
                   '<span style="display:block; padding: 3px; text-align: center"><s>' . $osC_Currencies->displayPrice($Qspecials->value('products_price'), $Qspecials->valueInt('products_tax_class_id')) . '</s> <span class="productSpecialPrice">' . $osC_Currencies->displayPrice($Qspecials->value('specials_new_products_price'), $Qspecials->valueInt('products_tax_class_id')) . '</span>';
                    
                   if ($Qspecials->value('products_type') == PRODUCT_TYPE_SIMPLE) {
                    echo '<div class="qtyBlock"><input type="text" id="qty_' . $Qspecials->valueInt('products_id') . '" value="1" size="1" class="qtyField" /></div>';
                   }
                    
                   echo osc_link_object(osc_href_link(FILENAME_PRODUCTS, $Qspecials->value('products_id') . '&action=cart_add'), osc_draw_image_button('button_add_to_cart.png', $osC_Language->get('button_add_to_cart'), 'class="ajaxAddToCart" id="ac_specials_' . $Qspecials->value('products_id') . '"'));
            echo '</div>';
       
          $i++;
        }
      ?>
  <?php } ?>
   
  <?php
    if ($products_type == 'variants') {
                $count_variants_specials = count($variants_specials);
                 
        if ($count_variants_specials > 0) {
            for ($i=0; $i < $count_variants_specials; $i++) {
                        $variants_special = $variants_specials[$i];
                         
                        if(($i % 3 == 0) && ($i != 0)) {
                            echo '<div style="clear:both"></div>';
                        }
                         
                        echo '<div style="margin-top: 10px; text-align: center; float: right; width: 31%; margin-right: 5px;">' .
                                '<span style="display:block; height: 32px; text-align: center">' . osc_link_object(osc_href_link(FILENAME_PRODUCTS, $variants_special['products_id']), $variants_special['products_name']) . '</span>' .
                                osc_link_object(osc_href_link(FILENAME_PRODUCTS, $variants_special['products_id']), $osC_Image->show($variants_special['image'], $variants_special['products_name']), 'id="img_ac_specials_'. $variants_special['products_id'] . '"') .
                                '<span style="display:block; padding: 3px; text-align: center"><s>' . $osC_Currencies->displayPrice($variants_special['products_price'], $variants_special['products_tax_class_id']) . '</s> <span class="productSpecialPrice">' . $osC_Currencies->displayPrice($variants_special['variants_specials_price'], $variants_special['products_tax_class_id']) . '</span>';
                          
                        if ($variants_special['products_type'] == PRODUCT_TYPE_SIMPLE) {
                            echo '<div class="qtyBlock"><input type="text" id="qty_' . $variants_special['products_id'] . '" value="1" size="1" class="qtyField" /></div>';
                        }
                          
                        echo osc_link_object(osc_href_link(FILENAME_PRODUCTS, $variants_special['products_id'] . '&action=cart_add'), osc_draw_image_button('button_add_to_cart.png', $osC_Language->get('button_add_to_cart'), 'class="ajaxAddToCart" id="ac_specials_' . $variants_special['products_id'] . '"'));
                        echo '</div>';
                         
            }
        }
        }
    ?>
        <div style="clear:both"></div>
    </div>
     
    <div class="listingPageLinks">
      <span style="float: right;"><?php echo $Qspecials->getBatchPageLinks('page', 'specials'); ?></span>
     
      <?php echo $Qspecials->getBatchTotalPages($osC_Language->get('result_set_number_of_products')); ?>
    </div>
</div>
