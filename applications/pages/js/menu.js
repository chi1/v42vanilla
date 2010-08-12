jQuery(document).ready(function($) {
   
   $('a.AddMenuItem, a.EditMenuItem').popup({
      onUnload: function(settings) {
         $('#Content').load(combinePaths(definition('WebRoot', ''), 'index.php/pages/menu?DeliveryType=VIEW'));
      }   
   });
   
   $('a.DeleteMenuItem').popup({
      confirm: true,
      followConfirm: false,
      afterConfirm: function(json, sender) {
      	 if ($(sender).parents('tr.MenuItem').siblings('tr.MenuItem').length == 0)
      	 	 $(sender).parents('tr.MenuGroup').remove();
      	 else
      	 	 $(sender).parents('tr.MenuItem').remove();
      }
   });
   
   if ($.tableDnD)
      $("table.MenuSortable").tableDnD({onDrop: function(table, row) {
         var tableId = $($.tableDnD.currentTable).attr('id');
         // Add in the transient key for postback authentication
         var transientKey = definition('TransientKey');
         var data = $.tableDnD.serialize() + '&DeliveryType=BOOL&TableID=' + tableId + '&TransientKey=' + transientKey;
         var webRoot = definition('WebRoot', '');
         $.post(combinePaths(webRoot, 'index.php/pages/menu/sort'), data, function(response) {
            if (response == 'TRUE')
               $('#'+tableId+' tbody tr td').effect("highlight", {}, 1000);

         });
      }});

});