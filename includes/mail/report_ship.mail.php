<?php
//Monthly Co Report
$mailersubject = "Monthly Report for the " . $sname;
$mailerbody = "Sim Name: " . $sname . " ({$sid})\n";
$mailerbody .= "Commanding Officer: " . $commoff . ".\n";
$mailerbody .= "Sim's Website: " . $site . "\n";
$mailerbody .= "Sim's Status: " . $status . "\n";
$mailerbody .= "\n\nCrew List:\n";

$mailerbody .= $crewlisting;

$mailerbody .= "Crew Information:\n";
$mailerbody .= "~~~~~~~~~~~~~~~~~\n";
$mailerbody .= "New Crew Since Last Report:\n";
$mailerbody .= "$newcrew\n\n";
$mailerbody .= "Crew Removed Since Last Report:\n";
$mailerbody .= "$removedcrew\n\n";
$mailerbody .= "Crew Promotions/Demotions Since Last Report:\n";
$mailerbody .= "$promotions\n\n\n";

$mailerbody .= "Sim Information:\n";
$mailerbody .= "~~~~~~~~~~~~~~~~~\n";
$mailerbody .= "Current Mission Title: " . $mission . "\n\n";
$mailerbody .= "Mission Description:\n";
$mailerbody .= "$missdesc\n\n";
$mailerbody .= "Total Number of In-Character Posts This Month: " . $posts . "\n\n";
$mailerbody .= "Ship/Website Awards and Awards Given Crew:\n";
$mailerbody .= "$awards\n\n\n";

$mailerbody .= "Misc Information:\n";
$mailerbody .= "~~~~~~~~~~~~~~~~~\n";
$mailerbody .= "Sim Status, Updates and Additional Comments:\n";
$mailerbody .= "$comments\n\n";
?>
