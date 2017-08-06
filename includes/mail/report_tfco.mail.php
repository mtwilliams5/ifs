<?php

//Let's sanitise the content of the report (as I think magicquotes is adding backslashes that are unnecessary with the below:
$promotions = str_replace("\'", "'",$promotions);
$newco = str_replace("\'", "'",$newco);
$resigned = str_replace("\'", "'",$resigned);
$improvements = str_replace("\'", "'",$improvements);
$other = str_replace("\'", "'",$notes);

//Monthly TFCO report
$mailersubject = "Monthly Report for Task Force " . $tfid;
$mailerbody = "Task Force: $tfid - $tfname\n";
$mailerbody .= "CO: $tfco\n";
$mailerbody .= "\n";
$mailerbody .= "Total Ships: $ships\n";
$mailerbody .= "    CO'ed Ships: $coships\n";
$mailerbody .= "        Active Ships: $actships\n";
$mailerbody .= "        Inactive Ships: $inships\n";
$mailerbody .= "    Open Ships: $openships\n";
$mailerbody .= "\n";
$mailerbody .= "Total Characters: $totalchar\n";
$mailerbody .= "Average Characters per COed ship: $avchar\n";
$mailerbody .= "\n";
$mailerbody .= "Promotions:\n";
$mailerbody .= "$promotions\n\n";
$mailerbody .= "New COs:\n";
$mailerbody .= "$newco\n\n";
$mailerbody .= "Resigned COs:\n";
$mailerbody .= "$resigned\n\n";
$mailerbody .= "Improvements:\n";
$mailerbody .= "$improvements\n\n";
$mailerbody .= "General Notes:\n";
$mailerbody .= "$notes\n\n";
$mailerbody .= "Submitted " . date("F j, Y") . "\n";
?>