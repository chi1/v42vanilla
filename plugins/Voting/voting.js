jQuery(document).ready(function($) {
 
	//show/hide-link on hidden comments
	$('a.showhidecomment').live('click', function() {
		var parentli = $(this).parent().get(0);
		var parentol = $(parentli).parent().get(0);
		var hidden = $(parentol).children().get(1);
		$(hidden).toggle();
		return false;

//onclick="javascript: document.getElementById(\'CommentNo' . $CommentID . '\').style.display = \'block\'; return false;"
	});

  
   // Handle Vote button clicks   
   $('div.VotesBox a').live('click', function() {
      var btn = this;
      var parent = $(this).parents('.Bookmarks');
      var oldClass = $(btn).attr('class');
      // $(btn).addClass('Bookmarking');
      $.ajax({
         type: "POST",
         url: btn.href,
         data: 'DeliveryType=BOOL&DeliveryMethod=JSON',
         dataType: 'json',
         error: function(XMLHttpRequest, textStatus, errorThrown) {
            // Popup the error
            $(btn).attr('class', oldClass);
            $.popup({}, definition('TransportError').replace('%s', textStatus));
         },
         success: function(json) {
            // Remove this row if looking at a list of bookmarks
            // Is this the last item in the list?
            if ($(parent).children().length == 1) {
               // Remove the entire list
               $(parent).slideUp('fast', function() { $(this).remove(); });
            } else if ($(parent).length > 0) {
               // Remove the affected row
               $(btn).parents('.DiscussionRow').slideUp('fast', function() { $(this).remove(); });
            } else {
               // Otherwise just change the class & title on the anchor
               $(btn).attr('title', json.AnchorTitle);

               // Change the Vote count
               // count = $(btn).html();
               // count = count.substr(count.lastIndexOf('>')+1);
               // count = json.FinalVote == '1' ? ++count : --count;
               txt = $(btn).find('span').text();
               $(btn).html('<span>' + txt + '</span>' + json.TotalScore);
               $(btn).blur();
            }
         }
      });
      return false;
   });   

   // Handle Vote button clicks   
   $('.Votes a').live('click', function() {
      if (!$(this).hasClass('SignInPopup')) {
         var btn = this;
         var parent = $(this).parents('.Votes');
         var votes = $(parent).find('span');
         $.ajax({
            type: "POST",
            url: btn.href,
            data: 'DeliveryType=BOOL&DeliveryMethod=JSON',
            dataType: 'json',
            error: function(XMLHttpRequest, textStatus, errorThrown) {
               // Popup the error
               $(btn).attr('class', oldClass);
               $.popup({}, definition('TransportError').replace('%s', textStatus));
            },
            success: function(json) {
               // Change the Vote count
               $(votes).text(json.TotalScore);
            }
         });
         return false;
      }
   });

});
