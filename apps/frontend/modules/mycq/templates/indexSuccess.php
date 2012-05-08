<div class="row-fluid header-bar">
  <div class="span9">
    <h1 class="Chivo webfont">ROBOTBACON’S  PROFILE</h1>
  </div>
  <div class="span3 text-right">
    <a href="#">log off &raquo;</a>
  </div>
</div>

<div id="profile-subnavbar" class="navbar">
  <div class="navbar-inner">
    <div class="container">
      <div class="nav-collapse">
        <ul class="nav">
          <li class="active"><a href="#">Home</a></li>
          <li><a href="#">Profile</a></li>
          <li class="dropdown open">
            <a data-toggle="dropdown" class="dropdown-toggle" href="#">Inbox (2) <b class="caret"></b></a>
            <ul class="dropdown-menu">
              <li><a href="#">Action</a></li>
              <li><a href="#">Another action</a></li>
              <li><a href="#">Something else here</a></li>
              <li class="divider"></li>
              <li class="nav-header">Nav header</li>
              <li><a href="#">Separated link</a></li>
              <li><a href="#">One more separated link</a></li>
            </ul>
          </li>
          <li><a href="#">Collections (102)</a></li>
          <li><a href="#">Store (34)</a></li>
          <li><a href="#">Wanted (58)</a></li>
        </ul>
      </div><!-- /.nav-collapse -->
    </div>
  </div><!-- /navbar-inner -->
</div>


<div class="row-fluid cp-collector-info">
  <div class="span2">
    <div class="cp-collector-avatar">
      <img src="http://placehold.it/140x140" alt="">
      <span>Serious Collector</span>
    </div>
  </div>
  <div class="span10">
    <?php
    cq_sidebar_title(
      'My Public Profile',
      link_to('Edit my Profile &raquo;', '@content_categories', array('class' => 'text-v-middle link-align'))
    );
    ?>
    <div class="row-fluid">
      <div class="span5">
        <p><strong>I collect:</strong> everything.</p>
        <p><strong>About me:</strong>
          I am a staff writer for CQ, NES over-enthusiast, sushi devourer, daytime librarian, former teacher, painter, non-functional robot builder.
        </p>

      </div>
      <div class="span5">
        <p>
          <strong>My collections are:&nbsp;</strong>
          Infinite and delicious.
        </p>
          <p>
           <strong>From:&nbsp;</strong>
           The US
          </p>
      </div>
      <div class="span2">
        <strong>Linked accounts:</strong>
      </div>
    </div>
  </div>
</div>

<div id="cp-tabs">
  <div class="tabbable">
    <ul class="nav nav-tabs">
      <li class="active">
        <a href="#tab1" data-toggle="tab">Show Items for Sale</a>
      </li>
      <li>
        <a href="#tab2" data-toggle="tab">Show Collections</a>
      </li>
    </ul>
    <div class="tab-content">
      <div class="tab-pane" id="tab1">
        <p>I'm in Section 1.</p>
      </div>
      <div class="tab-pane active" id="tab2">
        <div class="tab-content-inner">
          <div class="row-fluid sidebar-title">
            <div class="span9 link-align">
              <h3 class="Chivo webfont">Items to Sort (14)</h3>
            </div>
            <div class="span3 text-right">
              <a href="#" class="add-new-items-button">&nbsp;</a>
            </div>
          </div>
        </div><!-- /.tab-content-inner -->
        <div class="row-fluid instruction-box">
          <div class="row-fluid instruction-box-inner">
            <div class="span2">
              <span class="gray-arrow pull-right"></span>
            </div>
            <div class="span8">
              <span class="hint-text"><strong>Hint:</strong> Drag and drop your items into collections.</span>
            </div>
            <div class="span2">
              <span class="gray-arrow"></span>
            </div>
          </div>
        </div><!-- /.instruction-box -->

        <div class="tab-content-inner spacer-top">
          <div class="row-fluid sidebar-title spacer-inner-bottom">
            <div class="span5 link-align">
              <h3 class="Chivo webfont">My Collections (102)</h3>
            </div>
            <div class="span7">
              <div id="sort-search-box-mini">
                <div class="input-append">
                  <form id="form-explore-collections" method="post" action="/search/collections">
                    <div class="btn-group">
                      <div class="append-left-gray">Sort by <strong id="sortByName">Most Relevant</strong></div>
                      <a class="btn gray-button dropdown-toggle" data-toggle="dropdown" href="#">
                        <span class="caret arrow-up"></span><br><span class="caret arrow-down"></span>
                      </a>
                      <ul class="dropdown-menu">
                        <li><a data-sort="most-relevant" data-name="Most Relevant" class="sortBy" href="javascript:">Sort by <strong>Most Relevant</strong></a></li>
                        <li><a data-sort="most-recent" data-name="Most Recent" class="sortBy" href="javascript:">Sort by <strong>Most Recent</strong></a></li>
                        <li><a data-sort="most-popular" data-name="Most Popular" class="sortBy" href="javascript:">Sort by <strong>Most Popular</strong></a></li>
                      </ul>
                    </div>
                    <input type="text" class="input-sort-by" id="appendedPrependedInput" name="q"><button class="btn gray-button" type="submit"><strong>Search</strong></button>
                    <input type="hidden" value="most-relevant" id="sortByValue" name="s">
                  </form>
                </div>
              </div>
            </div>
          </div>


          <div id="items-for-sale">
            <div class="row thumbnails">

              <div class="span4 thumbnail link">
                <span>LOG! From Blammo!</span>
                <a href="#">
                  <img src="http://placehold.it/64x64" alt="">
                </a>
                <a href="#">
                  <img src="http://placehold.it/64x64" alt="">
                </a>
                <a href="#">
                  <img src="http://placehold.it/64x64" alt="">
                </a>
              </div>
              <div class="span4 thumbnail link">
                <span>LOG! From Blammo!</span>
                <a href="#">
                  <img src="http://placehold.it/64x64" alt="">
                </a>
                <a href="#">
                  <img src="http://placehold.it/64x64" alt="">
                </a>
                <a href="#">
                  <img src="http://placehold.it/64x64" alt="">
                </a>
              </div>
              <div class="span4 thumbnail link">
                <span>LOG! From Blammo!</span>
                <a href="#">
                  <img src="http://placehold.it/64x64" alt="">
                </a>
                <a href="#">
                  <img src="http://placehold.it/64x64" alt="">
                </a>
                <a href="#">
                  <img src="http://placehold.it/64x64" alt="">
                </a>
              </div>
              <div class="span4 thumbnail link">
                <span>LOG! From Blammo!</span>
                <a href="#">
                  <img src="http://placehold.it/64x64" alt="">
                </a>
                <a href="#">
                  <img src="http://placehold.it/64x64" alt="">
                </a>
                <a href="#">
                  <img src="http://placehold.it/64x64" alt="">
                </a>
              </div>

            </div>
          </div>


          <a href="#"
             class="btn btn-small gray-button see-more-button">
            See more
          </a>

        </div><!-- /.tab-content-inner -->

      </div>
    </div><!-- /.tab-content -->
  </div>
</div>


