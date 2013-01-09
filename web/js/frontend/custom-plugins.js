// frontend/custom-plugins.js

(function (window, document, $, undefined) {
"use strict";


// A widget used on the collectible page to build a carousel showing the rest of
// the collectibles in the collection
jQuery.fn.collectionCollectiblesCarousel = function(options)
{
  var defaults = {
    limit: 3,
    collection_id: 0,
    collectible_id: null,
    nextControl:'.right-arrow',
    prevControl:'.left-arrow',
    itemsHolder:'.thumbnails'
  };

  options = $.extend({}, defaults, options);

  return this.each(function()
  {
    var widget = $(this);
    var curPage = widget.data('page');
    var lastPage = widget.data('lastpage');
    var url = widget.data('url');
    var cache = {};
    var holder = $(options.itemsHolder, widget);

    $(options.nextControl, widget).click(function() {
      loadPage(lastPage === curPage ? 1 : curPage + 1);
    });
    $(options.prevControl, widget).click(function() {
      loadPage(curPage === 1 ? lastPage : curPage - 1);
    });

    function loadPage(page)
    {
      curPage = page;
      if (page in cache)
      {
        update(page);
      }
      else
      {
        holder.showLoading();

        holder.load(url +' #carousel > *',
          {
            p: page,
            collection_id: options.collection_id,
            collectible_id: options.collectible_id,
            limit: options.limit
          },
          function(data)
          {
            var $carousel = $(data).find('#carousel');
            if ($carousel)
            {
              cache[page] = $carousel.html();
            }

            update(page);
          }
        );
      }
    }

    function update(page)
    {
      var html = cache[page];
      if (page !== widget.data('page') && html)
      {
        holder.fadeOut(0, function()
        {
          holder.html(html);
          holder.imagesLoaded(function()
          {
            $(this).fadeIn('fast', $(this).hideLoading);
          });
        });
      }
      widget.data('page', curPage);
    }
  });
}


})(this, this.document, jQuery);