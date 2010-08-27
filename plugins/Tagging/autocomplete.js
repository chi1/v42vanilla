jQuery(document).ready(function($) {
   $("#Form_Tags").tokenInput("'.Gdn::Request()->Url('plugin/tagsearch').'", {
      hintText: "Start to type...",
      searchingText: "Searching...",
      searchDelay: 300,
      minChars: 1,
      maxLength: 25,
      onFocus: function() { $(".Help").hide(); $(".HelpTags").show(); }
   });
});
