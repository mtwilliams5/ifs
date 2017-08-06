<?php
//Command application, administration copy
$realbody = "Please forward this app.\n\n";
$realbody .= $body;
$subject = "CO Application";
mail (webmasteremail . ", " . $fleetopsemail . ", " . $personnelemail, $subject, $realbody, $headers);
?>
