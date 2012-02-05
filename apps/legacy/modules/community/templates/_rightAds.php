<td valign ="top" style ="padding: 5px; background: #F5F8DD;">
<?php
	if (has_slot('above-right-ads'))
  {
		echo '<div style="width: 130px; margin-top: 5px;">';
    include_slot('above-right-ads');
		echo "</div>";
  }
 ?>
</td>
<div style ="width: 130px; text-align: center;">
<?php
	if (SF_ENVIRONMENT == 'prod')
  {
		if (has_slot('right-ads'))
    {
      echo get_slot('right-ads');
    }
		else
    {
			include_partial('global/ads/google_120x600');
    }
  }
  else
  {
	  echo '<img src="http://www.iab.net/media/image/120x600.gif" alt="" />';
	}
?>
</div>
