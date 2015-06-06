<?php
/*
  $Id: osc_cfg_maintenance_pulldown_menu.php $
  TomatoCart Open Source Shopping Cart Solutions
  http://www.tomatocart.com
  http://www.tomatoshop.ir  Persian Tomatocart v1.1.8.6 / Khordad 1394
  Copyright (c) 2009 Wuxi Elootec Technology Co., Ltd

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License v2 (1991)
  as published by the Free Software Foundation.
*/

  function osc_cfg_maintenance_pulldown_menu($default, $key = null) {
    global $osC_Database, $osC_Language;

    $recoreds = array(array('id' => 0,'text' => $osC_Language->get('operation_heading_deactivate')),
                     array('id' => 1,'text' => $osC_Language->get('operation_heading_activate'))) ;

    $control = array();
    $control['name'] = $name;
    $control['type'] = 'combobox';
    $control['mode'] = 'local';
    $control['values'] = $recoreds;

    return $control;    
  }
?>
