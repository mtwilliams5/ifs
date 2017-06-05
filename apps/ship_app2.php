<?php
/***
  * INTEGRATED FLEET MANAGEMENT SYSTEM
  * OBSIDIAN FLEET
  * http://www.obsidianfleet.net/ifs/
  *
  * Developer:	Frank Anon
  * 	    	fanon@obsidianfleet.net
  *
  * Updated By: Nolan
  *		john.pbem@gmail.com
  *
  * Updated By: Matt Williams
  *     matt@mtwilliams.uk
  *
  * Version:	1.17
  * Release Date: June 3, 2004
  * Patch 1.13n:  December 2009
  * Patch 1.14n:  March 2010
  * Patch 1.17:   June 2017
  *
  * Copyright (C) 2003-2004 Frank Anon for Obsidian Fleet RPG
  * Distributed under the terms of the GNU General Public License
  * See doc/LICENSE for details
  *
  * This program contains code from Mambo Site Server 4.0.12
  * Copyright (C) 2000 - 2002 Miro International Pty Ltd
  *
  * Comments: Ship application
  *
 ***/

if (!defined("IFS"))
{
	echo "Hacking attempt";
    $quit = "1";
}


/*-------------------------------------------------------*/
/* Check to make sure all fields are filled out, and	 */
/* if not, give them an error screen and exit			 */
/*-------------------------------------------------------*/

$ip = getenv("REMOTE_ADDR");

if ($reason2 = check_ban($database, $mpre, $spre, $Email, $ip, 'ship'))
{
	echo '<h3 class="text-danger">You have been banned!</h3>';
    echo '<p>' . $reason2 . '</p>';
	$quit = "1";
}

if (strstr ($Email, '@'))
{
	$emaildomain = strstr ($Email, '@');
	if (!strstr($emaildomain, "."))
    {
	    echo '<h3 class="text-warning">Please enter a valid email address.</h3>';
	    $quit = "1";
    }
}
else
{
	echo '<h3 class="text-warning">Please enter a valid email address.</h3>';
    $quit = "1";
}

$recentdate = time() - 5*60;
$qry = "SELECT id FROM {$spre}apps WHERE ip='$ip' AND date>'$recentdate'";
$result = $database->openConnectionWithReturn($qry);
$qry2 = "SELECT a.id FROM {$spre}apps a, {$spre}characters c, {$mpre}users u
		 WHERE u.email='$Email' AND c.player=u.id AND c.app=a.id AND a.date>'$recentdate'";
$result2 = $database->openConnectionWithReturn($qry2);
if (mysql_num_rows($result) || mysql_num_rows($result2))
{
	echo '<h3 class="text-warning">Please wait at least five minutes between submitting applications.</h3>';
    $quit = "1";
}

if ($Email == "")
{
    echo '<h3 class="text-warning">Please enter your email address.</h3>';
    $quit = "1";
}

if ($rules != "yes")
{
    echo '<h3 class="text-warning">Sorry, but you must agree to follow the rules.</h3>';
    $quit = "1";
}

if ($ship == "")
{
    echo '<h3 class="text-warning">Please enter the name of your sim.</h3>';
    $quit = "1";
}

if ($shipclass == "")
{
    echo '<h3 class="text-warning">Please enter the class of your sim.</h3>';
    $quit = "1";
}

if ($website == "")
{
    echo '<h3 class="text-warning">Please enter your sim\'s webpage.</h3>';
    $quit = "1";
}

if ($active == "")
{
    echo '<h3 class="text-warning">Please enter the length of time your sim has been active.</h3>';
    $quit = "1";
}

if ($reason == "")
{
    echo '<h3 class="text-warning">Please state your reasons for wanting to join the fleet.</h3>';
    $quit = "1";
}

if ($Characters_Name == "")
{
    echo '<h3 class="text-warning">Please enter a character name.</h3>';
    $quit = "1";
}

if ($Characters_Race == "")
{
    echo '<h3 class="text-warning">Please enter a character species.</h3>';
    $quit = "1";
}

if ($Characters_Gender == "")
{
    echo '<h3 class="text-warning">Please enter a character gender.</h3>';
    $quit = "1";
}

if ($Character_Bio == "")
{
    echo '<h3 class="text-warning">Please enter a biography for your character.</h3>';
    $quit = "1";
}

if ($quit != "1")
{

	$body = "Ship Name: $ship\n";
	$body .= "Ship Class: $shipclass\n";
	$body .= "Website: $website\n";
	$body .= "Time Active: $active\n";
	$body .= "Reasons for joining:\n";
	$body .= "$reason\n\n";
if (strpos('x'.$reason,'http')>0 || strpos('x'.$reason,'www')>0) $fake=true; else $fake=false;
	$body .= "Real Name: $Name\n";
	$body .= "Email Address: $Email\n\n";

	$body .= "Age: $Age\n";
	$body .= "Location: $Location\n";
	$body .= "ISP: $ISP\n\n";

	$body .= "Character Name: $Characters_Name\n";
	$body .= "Character Species: $Characters_Race\n";
	$body .= "Character Gender: $Characters_Gender\n\n";

	$body .= "Bio:\n";
	$body .= "$Character_Bio\n\n";

	$body .= "Sample Post:\n";
	$body .= "$Sample_Post\n\n";

	$body .= "RPG experience:\n";
	$body .= "$RPG_Experience\n";
	$body .= "Simming for $Time_In_Other_RPGs\n\n";

	$body .= "Reference:\n";
	$body .= "$Reference\n";
	$body .= "$Reference_Other\n\n";

	$body .= "Instant Messengers: $IM\n\n";

	$body .= "Extra Comments:\n";
	$body .= "~~~~~~~~~~~~~~~\n";
	$body .= "$extra_comments\n\n";

	$body = stripcslashes($body);

if (!$fake)	{ 
	require_once "includes/mail/shipapp.mail.php";


	// Save it in the db
	$body = addslashes($body);
	$date = time();
	$body = htmlspecialchars($body);
	$qry = "INSERT INTO {$spre}apps
    		SET type='ship', date='$date', app='$body', forward='$to', ip='$ip'";
	$database->openConnectionNoReturn($qry);
}

	/*-------------------------------------------------------*/
	/* Display a Thank-You page                              */
	/*-------------------------------------------------------*/
	?>
	<h3 class="text-success">Form received.  Thank you!</h3>

	<?php
}
?>
