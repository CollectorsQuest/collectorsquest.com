<?php
  /**
   * @var $pager PropelModelPager object;
   * @var $filter_by string read|unread|all $filter_by;
   */
  cq_sidebar_title(
    'Inbox', null,
    array(
      'left' => 8, 'right' => 4,
      'class'=>'mycq-red-title row-fluid messages-header'
    )
  );

  SmartMenu::setSelected('mycq_messages_sidebar', 'inbox');
?>

<form action="<?= url_for('@messages_batch_actions'); ?>" method="post" id="inbox-form">
  <input type="hidden" id="batchAction" name="" value="on"/>

  <div class="row-fluid messages-header gray-well cf">
    <div class="span6 spacer-top-5">
      <div class="checkbox-arrow pull-left"></div>
      <div class="private-messages-list-select control-group pull-left">
        <div class="btn-group">
          <button class="btn btn-mini dropdown-toggle" data-toggle="dropdown">
            Select
            <span class="caret"></span>
          </button>
          <ul class="dropdown-menu">
            <li><a href="javascript:void(0)" data-select="all">All</a></li>
            <li><a href="javascript:void(0)" data-select="none">None</a></li>
            <li><a href="javascript:void(0)" data-select="read">Read</a></li>
            <li><a href="javascript:void(0)" data-select="unread">Unread</a></li>
          </ul>
        </div>
      </div>

      <div class="private-messages-list-actions control-group pull-left">
        <div class="btn-group pull-left">
          <input type="submit" class="btn btn-mini" name="batch_action[mark_as_read]" value="Mark as Read" />
          <input type="submit" class="btn btn-mini" name="batch_action[mark_as_unread]" value="Mark as Unread" />
        </div>

        <div class="btn-group pull-left">
          <input type="submit" data-confirm="Are you sure you sure you want to delete these messages?" class="btn btn-mini" name="batch_action[delete]" value="Delete" />
          <input type="submit" data-confirm="Are you sure you sure you want to report these messages as spam?" class="btn btn-mini" name="batch_action[report_spam]" value="Report Spam" />
        </div>
      </div>
    </div>

    <div class="span3 spacer-top-5">
      <span class="pull-left show-all-text">Show:</span>
      <div class="control-group pull-left">
        <div class="btn-filter-all btn-group">
          <?= link_to('All', '@messages_inbox?filter=all', array('id' => 'filter-all', 'class' => 'btn btn-mini btn-filter '.('all' == $filter_by ? 'active' : '') )); ?>
          <?= link_to('Unread', '@messages_inbox?filter=unread', array('id' => 'filter-unread', 'class' => 'btn btn-mini btn-filter '.('unread' == $filter_by ? 'active' : '') )); ?>
          <?= link_to('Read', '@messages_inbox?filter=read', array('id' => 'filter-read', 'class' => 'btn btn-mini btn-filter '.('read' == $filter_by ? 'active' : '') )); ?>
        </div>
      </div> <!-- .control-group.pull-left -->
    </div>

    <div class="span3">
      <div class="mini-input-append-search">
        <div class="input-append pull-right">
            <input type="text" class="input-sort-by" id="search-input" name="search" value="<?= $sf_request->getParameter('search'); ?>"><button class="btn gray-button" id="search-button" type="submit"><strong>Search</strong></button>
            <input type="hidden" name="filter" id="filter-hidden" value="<?= $filter_by; ?>">
        </div>
      </div>
    </div>

  </div> <!-- .row-fluid.messages-header -->

  <div id="messages-table">
    <?php include_partial('inbox_table', array(
        'filter_by' => $filter_by,
        'pager' => $pager,
        'search' => $search,
    ))?>
  </div>

</form>


<script>

  $(document).ready(function()
  {

    $('#search-button').click(function()
    {
      loadingTable();

      return false;
    });

    $('#search-input').on('keydown', function(e) {
      if (13 === e.which) {
        e.preventDefault();
        loadingTable();

        return false;
      }

      return true;
    });

    $('.btn-filter').click(function()
    {
      $('.btn-filter-all .active').removeClass('active');
      $(this).addClass('active');
      $('#filter-hidden').val($(this).attr('id').replace('filter-', ''));
      loadingTable();

      return false;
    });

    $('.private-messages-list-actions input').click(function(e)
    {
      var $this = $(this);
      if ($this.data('confirm') && !confirm($this.data('confirm'))) {
        e.preventDefault();
        return false;
      }

      var $form = $('#inbox-form');

      var name = $this.attr('name');
      $('#batchAction').attr('name', name);

      $('#messages-table').showLoading();
      $('#messages-table').load(
        $form.attr('action'),
        $form.serialize(),
        function() {
          $('#messages-table').hideLoading();
          $('#batchAction').attr('name', '');

          APP.messages.inbox();
        }
      );

      return false;
    });
  });

  function loadingTable()
  {
    var $url = '<?= url_for('@messages_inbox') ?>';
    var $form = $('#inbox-form');

    $('#messages-table').showLoading();
    $('#messages-table').load(
      $url,
      $form.serialize(),
      function() {
        $('#messages-table').hideLoading();

        APP.messages.inbox();
      }
    );
  }
</script>
