<h1>Feedback</h1>
<?php
  include_partial('global/flash_messages');
?>
<script>
window.setTimeout(closeModal, 5000);

function closeModal()
{
	$('.modal a.close').click();
}
</script>