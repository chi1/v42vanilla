var QuotesPlugin = {
   ConvertQuoteLinks: function() {
      $('span.CommentQuote a').each(function(i,el){
         if (el.QuoteFunctioning) return;
         el.QuoteFunctioning = true;
         el = $(el);
         var ObjectID = el.attr('href').split('/').pop();
         el.attr('href',"javascript:QuotesPlugin.Quote('"+ObjectID+"');");
      });
   },
   
   Quote: function(QuotedElement) {
      var Comment = QuotesPlugin.GetQuoteData(QuotedElement);
      if (!Comment) return;
      
      var x = $('#Form_Body').offset().top - 100; // 100 provides buffer in viewport
      $('html,body').animate({scrollTop: x}, 800);
   },
   
   QuoteResponse: function(Data, Status, XHR) {
      
      Data = jQuery.parseJSON(Data);
      if (Data.Quote.status == 'failed' || !Data) {
         if (Data && Data.Quote.selector)
            QuotesPlugin.RemoveSpinner(Data.Quote.selector);
         return;
      }
         
      switch (Data.Quote.format) {
         case 'Html':   // HTML
            var Append = '<blockquote rel="'+Data.Quote.authorname+'">'+Data.Quote.body+'</blockquote>'+"\n";
            break;
            
         case 'BBcode':
            var Append = '[quote="'+Data.Quote.authorname+'"]'+Data.Quote.body+'[/quote]'+"\n";
            break;
         
         case 'Display':
         case 'Text':   // Plain
            var Append = ' > '+Data.Quote.authorname+" said:\n";
            Append = Append+' > '+Data.Quote.body+"\n";
            break;
            
         default:
            var Append = '';
            return;
      
      }
      
      $('textarea#Form_Body').val($('textarea#Form_Body').val() + Append);
   },
   
   AddSpinner: function(QuotedElement) {
      
   },
   
   RemoveSpinner: function(QuotedElement) {
      
   },
   
   GetQuoteData: function(QuotedElement) {
      var Quoted = $('#'+QuotedElement);
      if (!Quoted) return false;
      QuotesPlugin.AddSpinner(QuotedElement);
      var QuotebackURL = gdn.url('plugin/Quotes/getquote/'+QuotedElement);
      jQuery.ajax({
         url: QuotebackURL,
         type: 'GET',
         success: QuotesPlugin.QuoteResponse
      });
      return Quoted;
   }

}

jQuery(document).ready(QuotesPlugin.ConvertQuoteLinks);
jQuery(document).bind('CommentPagingComplete', QuotesPlugin.ConvertQuoteLinks);
jQuery(document).bind('CommentAdded', QuotesPlugin.ConvertQuoteLinks);
