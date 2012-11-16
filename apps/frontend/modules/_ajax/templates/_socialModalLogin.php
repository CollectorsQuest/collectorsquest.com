<?php

include_partial(
  'global/rpxnow_iframe',
  array(
    'flags' => 'hide_sign_in_with,show_provider_list',
    'provider' => $sf_params->get('provider')
  )
);
