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
    $this->addOption('autoselect');

    parent::__construct($options, $attributes);
  }

  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    $attributes['data-provide'] = 'typeahead';
    $attributes['autocomplete'] = 'off';

    $source = $this->getOption('source');
    $typeAheadOptions = array();
    foreach (array('items', 'matcher', 'sorter', 'highlighter', 'autoselect') as $option)
    {
      if (null !== $this->getOption($option))
      {
        $typeAheadOptions[$option] = $this->getOption($option);
      }
    }

    return parent::render($name, $value, $attributes, $errors)
        . sprintf(<<<EOF

<script type="text/javascript">
$(document).ready(function()
{
  $('#%s').typeahead(%s).on('keyup', function(ev)
  {
    var self = $(this);
    ev.stopPropagation();
    ev.preventDefault();

    //filter out up/down, tab, enter, and escape keys
    if( $.inArray(ev.keyCode,[40,38,9,13,27]) === -1 )
    {

      //active used so we aren't triggering duplicate keyup events
      if( !self.data('active') && self.val().length > 0)
      {
        (function getJsonLoop(){
          self.data('active', self.val());

          //Do data request. Insert your own API logic here.
          $.getJSON("%s", { q: self.val() }, function(data)
          {
            //set this to true when your callback executes
            //self.data('active',true);

            //Filter out your own parameters. Populate them into an array, since this is what typeahead's source requires
            var arr = [];
            $.each(data, function(i, value) {
              arr.push(value);
            });

            //set your results into the typehead's source
            self.data('typeahead').source = arr;

            //trigger keyup on the typeahead to make it search
            self.trigger('keyup');

            if (self.data('active') != self.val()) {
              getJsonLoop();
            } else {
              //All done, set to false to prepare for the next remote query.
              self.data('active', false);
            }
          });
        })();
      }
    }

    // if enter was pressed
    if (13 === ev.keyCode) {
      var Typeahead = self.data('typeahead');
      if ( !Typeahead.shown || 0 === Typeahead.\$menu.find('.active').length) {
        self.parents('form').submit();
      }
    }
  });
});
</script>

EOF
          , $this->generateId($name),
          json_encode($typeAheadOptions),
          is_array($source) ? json_encode($source) : $source
        );
  }

  public function getJavaScripts()
  {
    return array('/assets/js/bootstrap/typeahead.js');
  }

}
