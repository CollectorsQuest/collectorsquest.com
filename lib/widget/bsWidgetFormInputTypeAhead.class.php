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
    $this->addOption('submit_on_enter', true);
    $this->addOption('min_activation_chars', 1);

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

    // filter out up/down, tab, enter, and escape keys
    if( $.inArray(ev.keyCode,[40,38,9,13,27]) === -1 )
    {
      // active used so we aren't triggering duplicate keyup events
      if( !self.data('active') && self.val().length >= %d)
      {
        // the self executing getJsonLoop function will call itself again
        // if at the end of retrieving the search suggestions the value of the
        // search field differs from the one with which the first search was made
        (function getJsonLoop(){
          // the data active attribute is used to store the search term for the
          // currently active request
          self.data('active', self.val());

          // Do data request. Insert your own API logic here.
          $.getJSON("%s", { q: self.val() }, function(data)
          {
            // populate array for new typeahead source
            var arr = [];
            $.each(data, function(i, value) {
              arr.push(value);
            });

            // set your results into the typehead's source
            self.data('typeahead').source = arr;

            // trigger keyup on the typeahead to make execute its internal search
            self.trigger('keyup');

            // if the current search field value differs from our search
            if (self.data('active') != self.val()) {
              // execute the search loop again
              getJsonLoop();
            } else {
              // all done, set current search false as cleanup
              self.data('active', false);
            }
          });
        })();
      }
    }

    // if enter was pressed and we submit on enter
    if (13 === ev.keyCode && %s) {
      var Typeahead = self.data('typeahead');
      // and the typeahead is currently hidden, or there is no selection made
      if ( !Typeahead.shown || 0 === Typeahead.\$menu.find('.active').length) {
        // submit the search form
        self.parents('form').submit();
      }
    }
  });
});
</script>

EOF
          , $this->generateId($name),
          json_encode($typeAheadOptions),
          $this->getOption('min_activation_chars'),
          is_array($source) ? json_encode($source) : $source,
          $this->getOption('submit_on_enter') ? 'true' : 'false'
        );
  }

  public function getJavaScripts()
  {
    return array('/assets/js/bootstrap/typeahead.js');
  }

}
