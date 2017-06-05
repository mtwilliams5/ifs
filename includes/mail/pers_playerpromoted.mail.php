<?php
//Personnel record for player promotion
$mailersubject = "Personnel - Player Promoted on " . $sname;
$mailerbody = "Sim Name: " . $sname . "\n";
$mailerbody .= "Crew: " . $cname . "\n";
$mailerbody .= "Old Rank: " . $oldrank . "\n";
$mailerbody .= "New Rank: " . $newrank . "\n";
$mailerbody .= "Email: " . $pemail . "\n";
$mailerbody .= "Performed by: " . $coname . "\n\n";
$mailerbody .= "Reason:\n";
$mailerbody .= $reason;
$mailerbody .= "\n\nThis message was automatically generated.";

$header = "From: " . $email-from;
mail ($personnelemail, $mailersubject, $mailerbody, $header);
?>
