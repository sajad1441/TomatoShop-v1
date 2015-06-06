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

  echo 'Ext.namespace("Toc.gift_certificates");';
  
  include('gift_certificates_grid.php');
?>

Ext.override(TocDesktop.GiftCertificatesWindow, {

  createWindow : function(){
    var desktop = this.app.getDesktop();
    var win = desktop.getWindow('gift_certificates-win');
     
    if (!win) {
      grd = new Toc.gift_certificates.GiftCertificatesGrid({owner: this});
      
      win = desktop.createWindow({
        id: 'gift_certificates-win',
        title: '<?php echo $osC_Language->get('heading_gift_certificates_title'); ?>',
        width: 800,
        height: 400,
        iconCls: 'icon-gift_certificates-win',
        layout: 'fit',
        items: grd
      });
    }
    
    win.show();
  },
  
  createGiftCertificatesDialog: function() {
    var desktop = this.app.getDesktop();
    var dlg = desktop.getWindow('gift_certificates-dialog-win');
    
    if (!dlg) {
      dlg = desktop.createWindow({}, Toc.gift_certificates.GiftCertificatesDialog);
      
      dlg.on('saveSuccess', function(feedback) {
        this.app.showNotification({title: TocLanguage.msgSuccessTitle, html: feedback});
      }, this);
    }

    return dlg;
  }

});
