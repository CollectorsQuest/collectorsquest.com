jQuery(document).ready(function($) {

  // The number of the next page to load (/page/x/).
  var pageNum = parseInt(cq.startPage) + 1;

  // The maximum number of pages the current query can return.
  var max = parseInt(cq.maxPages);

  // The link of the next page of posts.
  var nextLink = cq.nextLink;

  /**
   * Replace the traditional navigation with our own,
   * but only if there is at least one page of new posts to load.
   */
  if(pageNum <= max) {
    // Insert the "More Posts" link.
    $('#blog-contents')
      .append('<div class="cq-placeholder-'+ pageNum +'"></div>')
      .append('<p id="cq-load-posts"><a href="#">Load More Posts</a></p>');

    // Remove the traditional navigation.
    $('.navigation').remove();
  }


  /**
   * Load new posts when the link is clicked.
   */
  $('#cq-load-posts a').click(function() {

    // Are there more posts to load?
    if(pageNum <= max) {

      // Show that we're working.
      $(this).text('Loading posts...');

      $('.cq-placeholder-'+ pageNum).load(nextLink + ' .post',
        function() {
          // Update page number and nextLink.
          pageNum++;
          nextLink = nextLink.replace(/\/page\/[0-9]?/, '/page/'+ pageNum);

          // Add a new placeholder, for when user clicks again.
          $('#cq-load-posts')
            .before('<div class="cq-placeholder-'+ pageNum +'"></div>')

          // Update the button message.
          if(pageNum <= max) {
            $('#cq-load-posts a').text('Load More Posts');
          } else {
            $('#cq-load-posts a').text('No more posts to load.');
          }
        }
      );
    } else {
      $('#cq-load-posts a').append('.');
    }

    return false;
  });
});
