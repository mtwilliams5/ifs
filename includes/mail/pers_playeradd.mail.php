<?php
//Personnel record for player addition
$mailersubject = "Personnel - Player Added on " . $sname;
$mailerbody = "Sim Name: " . $sname . "\n";
$mailerbody .= "TF: " . $tfid . "\n";
$mailerbody .= "Character: " . $cname . "\n";
$mailerbody .= "Rank: ". $rankname . "\n";
$mailerbody .= "Position: ". $position . "\n";
$mailerbody .= "Email: " . $email . "\n";
$mailerbody .= "Performed by: " . $coname . " ({$coemail})\n\n";
$mailerbody .= "\n\nThis message was automatically generated.";
$header = "From: ". email-from;
mail ($personnelemail, $mailersubject, $mailerbody, $header);
?>
