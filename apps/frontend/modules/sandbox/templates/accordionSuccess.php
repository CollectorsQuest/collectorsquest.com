<div class="collecton-wizard" id="accordion2">
  <div class="accordion-group finished">
    <div class="accordion-heading">
      <div class="accordion-toggle Chivo webfont">
        Step #1
      </div>
    </div>
    <div id="collapseOne" class="accordion-body collapse">
      <div class="accordion-inner">
        <div class="row-fluid">
          <span class="span1">
            <img src="http://placehold.it/70x70" alt="">
          </span>
          <div class="span9">
            <p>
              <span class="brown-bold">Collection Name:</span>
              <span class="f-20">New York Comic Con: DC Direct</span>
            </p>
            <p><span class="brown-bold">Category:</span> Toys, Games, Dolls / Action Figures / DC Comics</p>
            <p><span class="brown-bold">Tags:</span> Batman, comics, ,DC Direct, statue, Supermanx</p>
          </div>
          <div class="span2">
            <?= link_to('Edit', $sf_request->getUri() . '#',
            array('class' => 'btn btn-long pull-right')); ?>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="accordion-group active">
    <div class="accordion-heading">
      <div class="accordion-toggle Chivo webfont">
        Step #2
      </div>
    </div>
    <div class="accordion-body collapse">
      <div class="accordion-inner">

        <form class="form-horizontal form-modal">
          <div class="control-group ">
            <label class=" control-label" for="collection_thumbnail">Photo</label>
            <div class="controls">
              <div class="with-required-token">
                <span class="required-token">*</span>
                <input required="required" type="file" name="collection[thumbnail]" id="collection_thumbnail">
              </div>
            </div>
          </div>
          <div class="control-group ">
            <label class=" control-label" for="collection_thumbnail">Description</label>
            <div class="controls">
              <div class="with-required-token">
                <span class="required-token">*</span>
                <textarea rows="6" class="span10"></textarea>
              </div>
            </div>
          </div>
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

  <div class="accordion-group">
    <div class="accordion-heading">
      <div class="accordion-toggle Chivo webfont">
        Step #3
      </div>
    </div>
    <div class="accordion-body collapse">
      <div class="accordion-inner">
        Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
      </div>
    </div>
  </div>

  <div class="accordion-group">
    <div class="accordion-heading">
      <div class="accordion-toggle Chivo webfont">
        Step #4
      </div>
    </div>
    <div class="accordion-body collapse">
      <div class="accordion-inner">
        Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
      </div>
    </div>
  </div>


</div>


