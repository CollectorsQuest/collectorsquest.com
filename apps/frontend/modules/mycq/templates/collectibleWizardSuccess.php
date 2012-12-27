<div class="collecton-wizard" id="accordion2">
  <div class="accordion-group active">
    <div class="accordion-heading">
      <div class="accordion-toggle Chivo webfont">
        Step #1
        <span class="description">
          Categorization
        </span>
      </div>
    </div>
    <div class="accordion-body collapse">
      <div class="accordion-inner">

        <form class="form-horizontal form-modal">
          <?= $step1; ?>
        </form>

      </div>
    </div>
  </div>

  <div class="button-wrapper">
    <?= link_to('<i class="icon-caret-left f-16 text-v"></i>&nbsp; Previous Step', $sf_request->getUri() . '#',
    array('class' => 'btn pull-left')); ?>
    <?= link_to('Next Step &nbsp;<i class="icon-caret-right f-16 text-v"></i>', $sf_request->getUri() . '#',
    array('class' => 'btn btn-primary pull-right')); ?>
  </div>



  <div class="accordion-group active">
    <div class="accordion-heading">
      <div class="accordion-toggle Chivo webfont">
        Step #2
        <span class="description">
          Description
        </span>
      </div>
    </div>
    <div class="accordion-body collapse">
      <div class="accordion-inner">

        <form class="form-horizontal form-modal">
          <?= $step1; ?>
        </form>

      </div>
    </div>
  </div>

  <div class="button-wrapper">
    <?= link_to('<i class="icon-caret-left f-16 text-v"></i>&nbsp; Previous Step', $sf_request->getUri() . '#',
    array('class' => 'btn pull-left')); ?>
    <?= link_to('Next Step &nbsp;<i class="icon-caret-right f-16 text-v"></i>', $sf_request->getUri() . '#',
    array('class' => 'btn btn-primary pull-right')); ?>
  </div>



  <div class="accordion-group active">
    <div class="accordion-heading">
      <div class="accordion-toggle Chivo webfont">
        Step #3
        <span class="description">
          Alternative Images
        </span>
      </div>
    </div>
    <div class="accordion-body collapse">
      <div class="accordion-inner">

        <form class="form-horizontal form-modal">
          <?= $step1; ?>
        </form>

      </div>
    </div>
  </div>

  <div class="button-wrapper">
    <?= link_to('<i class="icon-caret-left f-16 text-v"></i>&nbsp; Previous Step', $sf_request->getUri() . '#',
    array('class' => 'btn pull-left')); ?>
    <?= link_to('Next Step &nbsp;<i class="icon-caret-right f-16 text-v"></i>', $sf_request->getUri() . '#',
    array('class' => 'btn btn-primary pull-right')); ?>
  </div>








</div>


