<?php 

require_once("/_websrv/vhosts/collectorsquest.com/web/email_templates/cq_email.php");

function cq_event_submit($toname,$toaddr,$title) {
  $template = file_get_contents("/_websrv/vhosts/collectorsquest.com/web/email_templates/calendar/eventSubmit.html");
  $message = str_replace("%name%", $toname, $template);
  $message = str_replace("%title%", $title, $message);

  $fromname = "Collectors' Quest";
  $fromaddr = "calendar@collectorsquest.com";

  $subject = "Your Collectors' Quest Event has been Submitted";

  cq_email($fromname,$fromaddr,$toname,$toaddr,$subject,$message);
}

function cq_event_approve($toname,$toaddr,$title) {
  $template = file_get_contents("/_websrv/vhosts/collectorsquest.com/web/email_templates/calendar/eventAccept.html");
  $message = str_replace("%name%", $toname, $template);
  $message = str_replace("%event%", $title, $message);

  $fromname = "Collectors' Quest";
  $fromaddr = "calendar@collectorsquest.com";

  $subject = "Your Collectors' Quest Event has been Approved";

  cq_email($fromname,$fromaddr,$toname,$toaddr,$subject,$message);
}

function cq_event_duplicate($toname,$toaddr,$title) {
  $template = file_get_contents("/_websrv/vhosts/collectorsquest.com/web/email_templates/calendar/eventDuplicate.html");
  $message = str_replace("%name%", $toname, $template);
  $message = str_replace("%event%", $title, $message);

  $fromname = "Collectors' Quest";
  $fromaddr = "calendar@collectorsquest.com";

  $subject = "Your Collectors' Quest Event";

  cq_email($fromname,$fromaddr,$toname,$toaddr,$subject,$message);
}



function cq_event_reject($toname,$toaddr,$title) {
  $template = file_get_contents("/_websrv/vhosts/collectorsquest.com/web/email_templates/calendar/eventReject.html");
  $message = str_replace("%name%", $toname, $template);
  $message = str_replace("%event%", $title, $message);

  $fromname = "Collectors' Quest";
  $fromaddr = "calendar@collectorsquest.com";

  $subject = "Your Collectors' Quest Event has been Rejected";


  cq_email($fromname,$fromaddr,$toname,$toaddr,$subject,$message);
}

function cq_event_admin_notify($title,$toname) {
  $template = file_get_contents("/_websrv/vhosts/collectorsquest.com/web/email_templates/calendar/eventNotifyAdmin.html");
  $message = str_replace("%title%", $title, $template);
  $message = str_replace("%name%", $toname, $message);

  $fromname = "Collectors' Quest Calendar";
  $fromaddr = "calendar@collectorsquest.com";

  $toname = "Calendar Administrator";
  $toaddr = "calendar@collectorsquest.com";

  $subject = "A New Collectors' Quest Calendar Event Awaits Your Approval";

  cq_email($fromname,$fromaddr,$toname,$toaddr,$subject,$message);
}
?>
