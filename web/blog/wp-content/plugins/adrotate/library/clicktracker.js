jQuery(document).ready(function(){
	trackerUrl = "../wp-content/plugins/adrotate/adrotate-out.php";
	
	jQuery(".adrotate_ad").click(function()
	{
		meta = jQuery(this).attr("id");
		meta.match(/adrotate_(0-9)/i);
		buffer = meta.replace("adrotate_", "")
		buffer.split("",3);

		alert("You clicked the ad with:\nid " + buffer[0] + "\ngroup " + buffer[1] + "\nblock " + buffer[2] + "\nContacting " + trackerUrl);

		jQuery.post(
			trackerUrl,
			{ad: buffer[0], group: buffer[1], block: buffer[2]},
			function(d){ alert(trackerUrl + "was successfully contacted and responded with the following data: " + d); },
			"text"
		);
			
		if(!confirm("Proceed to website?"))
		{
			return false;
		}
	});
});