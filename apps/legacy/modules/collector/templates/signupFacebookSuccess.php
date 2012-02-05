<?php /** @var array $fields */ ?>

<?php
  include_partial(
    'global/wizard_bar',
    array('steps' => array(1 => __('Account Information'), __('Collector Information'), __('Personal Information')), 'active' => 1)
  );
?>

<div style="padding-left: 15px;">
<fb:registration
  fields='<?= json_encode($fields); ?>'
  redirect-uri='<?= url_for('@collector_signup?step=2', true); ?>'
  border_color='#ffffff'
  width='725'>
</fb:registration>
</div>
