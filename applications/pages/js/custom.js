jQuery(document).ready(function($) {
   
   $('a.AddPage, a.EditPage').popup({
      onUnload: function(settings) {
         $('#Content').load(combinePaths(definition('WebRoot', ''), 'index.php/pages/custom?DeliveryType=VIEW'));
      }   
   });
   
   $('a.DeletePage').popup({
      confirm: true,
      followConfirm: false,
      afterConfirm: function(json, sender) {
      	 $(sender).parents('tr').remove();
      }
   });
   
});