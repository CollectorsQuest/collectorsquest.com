<?php
/**
 * File: bsWidgetFormInputTypeAhead.class.php
 *
 * Widget implementing typeahead control of bootstrap
 *
 * @author zecho
 * @version $Id$
 *
 */

class bsWidgetFormInputTypeAhead extends sfWidgetFormInput
{

  public function __construct($options = array(), $attributes = array())
  {
    $this->addRequiredOption('source');
    $this->addOption('items', 8);
    $this->addOption('matcher');
    $this->addOption('sorter');
    $this->addOption('highlighter');

    parent::__construct($options, $attributes);
  }

  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    $attributes['data-provide'] = 'typeahead';
    $attributes['autocomplete'] = 'off';

    $source = $this->options['source'];
    $typeAheadOptions = array_intersect_key($this->options, array_flip(array('items', 'matcher', 'sorter', 'highlighter')));

    return parent::render($name, $value, $attributes, $errors)
        . sprintf(<<<EOF

<script type="text/javascript">
jQuery(document).ready(function() {
  jQuery('#%s').typeahead()
    .on('keyup', function(ev){

        ev.stopPropagation();
        ev.preventDefault();

        //filter out up/down, tab, enter, and escape keys
        if( $.inArray(ev.keyCode,[40,38,9,13,27]) === -1 ){

            var self = $(this);

            //set typeahead source to empty
            self.data('typeahead').source = [];

            //active used so we aren't triggering duplicate keyup events
            if( !self.data('active') && self.val().length > 0){

                self.data('active', true);

                //Do data request. Insert your own API logic here.
                $.getJSON("%s", { q: $(this).val() }, function(data) {

                    //set this to true when your callback executes
                    self.data('active',true);

                    //Filter out your own parameters. Populate them into an array, since this is what typeahead's source requires
                    var arr = [];
                    $.each(data, function(i, value) {
                      arr.push(value);
                    });

                    //set your results into the typehead's source
                    self.data('typeahead').source = arr;

                    //trigger keyup on the typeahead to make it search
                    self.trigger('keyup');

                    //All done, set to false to prepare for the next remote query.
                    self.data('active', false);
                });
            }
        }
    });
});
</script>

EOF
          , $this->generateId($name),
          is_array($source) ? json_encode($source) : $source
        );
  }

  public function getJavaScripts()
  {
    return array('/iceAssetsPlugin/js/bootstrap/typeahead.js');
  }

}
