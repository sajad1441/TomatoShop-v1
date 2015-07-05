<?php
/*
  $Id: specials.php $
  TomatoCart Open Source Shopping Cart Solutions
  http://www.tomatocart.com
  http://www.tomatoshop.ir  Persian Tomatocart v1.1.8.6 / Khordad 1394
  Copyright (c) 2009 Wuxi Elootec Technology Co., Ltd;  Copyright (c) 2006 osCommerce

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License v2 (1991)
  as published by the Free Software Foundation.
*/

  class osC_Specials {

/* Private variables */

    var $_specials = array();

/* Class constructor */

    function osC_Specials() {
    }

/* Public methods */

    function activateAll() {
      global $osC_Database;

      $Qspecials = $osC_Database->query('select specials_id from :table_specials where status = 0 and now() >= start_date and start_date > 0 and now() < expires_date');
      $Qspecials->bindTable(':table_specials', TABLE_SPECIALS);
      $Qspecials->execute();

      while ($Qspecials->next()) {
        $this->_setStatus($Qspecials->valueInt('specials_id'), true);
      }

      $Qspecials->freeResult();
    }

    function expireAll() {
      global $osC_Database;

      $Qspecials = $osC_Database->query('select specials_id from :table_specials where status = 1 and now() >= expires_date and expires_date > 0');
      $Qspecials->bindTable(':table_specials', TABLE_SPECIALS);
      $Qspecials->execute();

      while ($Qspecials->next()) {
        $this->_setStatus($Qspecials->valueInt('specials_id'), false);
      }

      $Qspecials->freeResult();
    }

    function isActive($id) {
      global $osC_Database;

      if (!isset($this->_specials[$id])) {
        $this->_specials[$id] = $this->getPrice($id);
      }

      return is_numeric($this->_specials[$id]);
    }

    function getPrice($id) {
      global $osC_Database;

      if (!isset($this->_specials[$id])) {
        $Qspecial = $osC_Database->query('select specials_new_products_price from :table_specials where products_id = :products_id and status = 1');
        $Qspecial->bindTable(':table_specials', TABLE_SPECIALS);
        $Qspecial->bindInt(':products_id', $id);
        $Qspecial->setCache('product-specials-' . $id, $id);
        $Qspecial->execute();

        if ($Qspecial->numberOfRows() > 0) {
          $this->_specials[$id] = $Qspecial->valueDecimal('specials_new_products_price');
        } else {
          $this->_specials[$id] = null;
        }

        $Qspecial->freeResult();
      }

      return $this->_specials[$id];
    }
    /**
     * Get the variants specials
     *
     * @access public
     * @return array
     */
    function getVariantsSpecials() {
      global $osC_Language, $osC_Database;
             
      $Qspecials = $osC_Database->query('select vs.*, pv.products_price, pv.products_images_id, p.products_id, p.products_type, pd.products_name, p.products_tax_class_id, i.image from :table_variants_specials vs inner join :table_products_variants pv on vs.products_variants_id = pv.products_variants_id inner join :table_original_products p on pv.products_id = p.products_id inner join :table_products_description pd on (p.products_id = pd.products_id and pd.language_id = :language_id) left join :table_products_images i on (pv.products_images_id = i.id)');
      $Qspecials->bindTable(':table_variants_specials', TABLE_VARIANTS_SPECIALS);
      $Qspecials->bindTable(':table_original_products', TABLE_PRODUCTS);
      $Qspecials->bindTable(':table_products_images', TABLE_PRODUCTS_IMAGES);
      $Qspecials->bindTable(':table_products_description', TABLE_PRODUCTS_DESCRIPTION);
      $Qspecials->bindTable(':table_products_variants', TABLE_PRODUCTS_VARIANTS);
      $Qspecials->bindInt(':language_id', $osC_Language->getID());
      $Qspecials->setBatchLimit((isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1), MAX_DISPLAY_SPECIAL_PRODUCTS);
      $Qspecials->execute();
       
      $result = array('listing' => $Qspecials);
      if ($Qspecials->numberOfRows() > 0) {
        while($Qspecials->next()) {
          $special_product = array('specials_id' => $Qspecials->valueInt('variants_specials_id'),
                                   'products_id' => $Qspecials->valueInt('products_id'),
                                                         'products_type' => $Qspecials->valueInt('products_type'),
                                                         'image' =>  $Qspecials->value('image'),
                                                         'products_tax_class_id' => $Qspecials->valueInt('products_tax_class_id'),
                                   'products_price' => $Qspecials->value('products_price'),
                                   'variants_specials_price' => $Qspecials->value('variants_specials_price'));
           
          //attach the group and value for the products name
          $special_product['products_name'] = $Qspecials->value('products_name');
          $Qvariants = $osC_Database->query('select pvg.products_variants_groups_name, pvv.products_variants_values_name from :table_products_variants_entries pve inner join :table_products_variants_groups pvg on (pve.products_variants_groups_id = pvg.products_variants_groups_id and pvg.language_id = :group_language_id) inner join :table_products_variants_values pvv on (pve.products_variants_values_id = pvv.products_variants_values_id and pvv.language_id = :value_language_id) where pve.products_variants_id = :products_variants_id');
          $Qvariants->bindTable(':table_products_variants_entries', TABLE_PRODUCTS_VARIANTS_ENTRIES);
          $Qvariants->bindTable(':table_products_variants_groups', TABLE_PRODUCTS_VARIANTS_GROUPS);
          $Qvariants->bindTable(':table_products_variants_values', TABLE_PRODUCTS_VARIANTS_VALUES);
          $Qvariants->bindInt(':group_language_id', $osC_Language->getID());
          $Qvariants->bindInt(':value_language_id', $osC_Language->getID());
          $Qvariants->bindInt(':products_variants_id',  $Qspecials->valueInt('products_variants_id'));
          $Qvariants->execute();
           
          if ($Qvariants->numberOfRows() > 0) {
            while($Qvariants->next()) {
              $special_product['products_name'] .= '(<strong>' . $Qvariants->value('products_variants_groups_name') . ':' . $Qvariants->value('products_variants_values_name') . '</strong>)';
            }
          }
           
          $result['products'][] = $special_product;
        }
      }
       
      return $result;
    }
    /**
     * Get the variants special price
     * 
     * @access public
     * @param $variants_id int
     * @return mixed
     */
    function getVariantsPrice($variants_id) {
      global $osC_Database;
      
      if (!isset($this->_specials['variants'][$variants_id])) {
        $Qspecial = $osC_Database->query('select variants_specials_price from :table_variants_specials where products_variants_id = :products_variants_id and status = 1');
        $Qspecial->bindTable(':table_variants_specials', TABLE_VARIANTS_SPECIALS);
        $Qspecial->bindInt(':products_variants_id', $variants_id);
        $Qspecial->setCache('product-variants-specials-' . $variants_id);
        $Qspecial->execute();
        
        if ($Qspecial->numberOfRows() > 0) {
          $this->_specials['variants'][$variants_id] = $Qspecial->valueDecimal('variants_specials_price');
        } else {
          $this->_specials['variants'][$variants_id] = null;
        }
        
        $Qspecial->freeResult();
      }
      
      return  $this->_specials['variants'][$variants_id];
    }

    function &getListing() {
      global $osC_Database, $osC_Language, $osC_Image;

      $Qspecials = $osC_Database->query('select p.products_id, p.products_price, p.products_type, p.products_tax_class_id, pd.products_name, pd.products_keyword, s.specials_new_products_price, i.image from :table_products p left join :table_products_images i on (p.products_id = i.products_id and i.default_flag = :default_flag), :table_products_description pd, :table_specials s where p.products_status = 1 and s.products_id = p.products_id and p.products_id = pd.products_id and pd.language_id = :language_id and s.status = 1 order by s.specials_date_added desc');
      $Qspecials->bindTable(':table_products', TABLE_PRODUCTS);
      $Qspecials->bindTable(':table_products_images', TABLE_PRODUCTS_IMAGES);
      $Qspecials->bindTable(':table_products_description', TABLE_PRODUCTS_DESCRIPTION);
      $Qspecials->bindTable(':table_specials', TABLE_SPECIALS);
      $Qspecials->bindInt(':default_flag', 1);
      $Qspecials->bindInt(':language_id', $osC_Language->getID());
      $Qspecials->setBatchLimit((isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1), MAX_DISPLAY_SPECIAL_PRODUCTS);
      $Qspecials->execute();

      return $Qspecials;
    }

/* Private methods */

    function _setStatus($id, $status) {
      global $osC_Database;

      $Qstatus = $osC_Database->query('update :table_specials set status = :status, date_status_change = now() where specials_id = :specials_id');
      $Qstatus->bindTable(':table_specials', TABLE_SPECIALS);
      $Qstatus->bindInt(':status', ($status === true) ? '1' : '0');
      $Qstatus->bindInt(':specials_id', $id);
      $Qstatus->execute();

      $Qstatus->freeResult();
    }
  }
?>

