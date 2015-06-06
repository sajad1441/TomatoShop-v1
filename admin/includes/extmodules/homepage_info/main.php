<?php
/*
  $Id: main.php $
  TomatoCart Open Source Shopping Cart Solutions
  http://www.tomatocart.com
  http://www.tomatoshop.ir  Persian Tomatocart v1.1.8.6 / Khordad 1394
  Copyright (c) 2009 Wuxi Elootec Technology Co., Ltd

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License v2 (1991)
  as published by the Free Software Foundation.
*/
  echo 'Ext.namespace("Toc.homepage_info");';
  
  include('home_info_dialog.php');
  include('meta_info_panel.php');
  include('homepage_info_panel.php');
?>

Ext.override(TocDesktop.HomepageInfoWindow, {
  createWindow : function() {
    var desktop = this.app.getDesktop();
    var win = desktop.getWindow('homepage_info-win');
    
    if (!win) {
      win = desktop.createWindow({owner:this}, Toc.homepage_info.HomepageInfoDialog);
    }
    
    win.show();
  }
});
