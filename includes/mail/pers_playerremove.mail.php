<?php
//Personnel record for player removal
$mailersubject = "Personnel - Player Removed on " . $sname;
$mailerbody = "Sim Name: " . $sname . "\n";
$mailerbody .= "TF: " . $tfid . "\n";
$mailerbody .= "Crew: " . $cname . "\n";
$mailerbody .= "Rank: ". $rankname . "\n";
$mailerbody .= "Email: " . $pemail . "\n";
$mailerbody .= "Performed by: " . $coname . " ({$coemail})\n\n";
$mailerbody .= "Reason:\n";
$mailerbody .= $reason;
$mailerbody .= "\n\nThis message was automatically generated.";
$header = "From: ". $email-from;
mail ($personnelemail, $mailersubject, $mailerbody, $header);
?>
