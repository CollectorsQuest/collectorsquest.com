<footer>&nbsp;</footer>

<?php

// Output the javascripts for the page
cq_echo_javascripts();

if (sfConfig::get('sf_environment') == 'prod')
{
  include_partial('iceBackendModule/woopra');
}
