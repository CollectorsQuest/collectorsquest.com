<?php

// sends an email with proper headers for html message
function cq_email($fromname, $fromaddress, $toname, $toaddress, $subject, $message)
{
   $headers  = "MIME-Version: 1.0\n";
   $headers .= "Content-type: text/html; charset=iso-8859-1\n";
   $headers .= "Content-Transfer-Encoding: 7bit\n";
   $headers .= "X-Priority: 3\n";
   $headers .= "X-MSMail-Priority: Normal\n";
   $headers .= "X-Mailer: php\n";
   $headers .= "From: \"".$fromname."\" <".$fromaddress.">\n";

   $toaddress = "\"$toname\" <$toaddress>";
  
   return mail($toaddress, $subject, $message, $headers);
}


?>
