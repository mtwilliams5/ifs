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
  * Comments: Processes crew applications
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
if ($reason = check_ban($database, $mpre, $spre, $Email, $ip, 'crew'))
{
	echo '<h3 class="text-danger">You have been banned!</h3>';
    echo '<p>' . $reason . '</p>';
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

$qry = "SELECT c.id FROM {$spre}characters c, {$mpre}users u
		WHERE u.email='$Email' AND c.player=u.id AND
        c.ship!='" . DELETED_SHIP . "' && c.ship!='" . FSS_SHIP . "'";
$result = $database->openConnectionWithReturn($qry);
if ( (mysql_num_rows($result) >= maxchars) && maxchars > "0" )
{
	echo '<h3 class="text-warning">You may only have a maximum of ' . maxchars . ' characters.</h3>';
    $quit = "1";
}

$qry = "SELECT id FROM {$spre}ships WHERE name='$Ship'";
$result = $database->openConnectionWithReturn($qry);
list ($sid) = mysql_fetch_array($result);
$qry = "SELECT r.rankdesc, c.name FROM {$spre}characters c, {$mpre}users u, {$spre}rank r
		WHERE u.email='$Email' AND c.player=u.id AND c.ship='$sid' AND c.rank=r.rankid";
$result = $database->openConnectionWithReturn($qry);
if (mysql_num_rows($result))
{
	list ($rank, $cname) = mysql_fetch_array($result);
	echo '<h3 class="text-warning">You may only have one character per ship.</h3>';
    echo '<h5 class="text-info">Our records indicate that you already have a character on ' . $Ship . ' - ' . $rank . ' ' . $cname . '.</h5>';
    $quit = "1";
}

if ($Email == "")
{
    echo '<h3 class="text-warning">Please enter your email address.</h3>';
    $quit = "1";
}

if ($Follow_Rules != "Yes")
{
    echo '<h3 class="text-warning">Sorry, but you must agree to follow the rules.</h3>';
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

if ($Sample_Post=="")
{
	echo '<h3 class="text-warning">Please provide a sample post.</h3>';
    $quit = "1";
}

if ($First_Desired_Position == "-----Select Position----")
{
    echo '<h3 class="text-warning">Please choose a first choice position.</h3>';
    $quit = "1";
}


if ($Second_Desired_Position == "-----Select Position----")
{
    echo '<h3 class="text-warning">Please choose a second choice position.</h3>';
    $quit = "1";
}


if ($Officer_or_Enlisted == "<option selected>----- Enlisted Personnel or Officer ----</option>")
{
    echo '<h3 class="text-warning">Please choose your rank type -- officer, warrant, or enlisted.</h3>';
    $quit = "1";
}
$email=trim($email);
if ($quit != "1")
{

	if (($First_Desired_Position == "Other") || ($First_Desired_Position == "other"))
	    $First_Desired_Position = $otherpos1;

	if (($Second_Desired_Position == "Other") || ($Second_Desired_Position == "other"))
	    $Second_Desired_Position = $otherpos2;

	/* Find out who to send this thing to... */
	if ($Ship != "Any")
    {
	    $qry = "SELECT id, co FROM {$spre}ships WHERE name='$Ship'";
	    $result = $database->openConnectionWithReturn($qry);
	    list ($sid,$coid) = mysql_fetch_array($result);

	    $qry = "SELECT player FROM {$spre}characters WHERE id='$coid'";
	    $result = $database->openConnectionWithReturn($qry);
	    list ($uid) = mysql_fetch_array($result);

	    $qry = "SELECT email FROM {$mpre}users WHERE id='$uid'";
	    $result = $database->openConnectionWithReturn($qry);
	    list ($coemail) = mysql_fetch_array($result);
	}
    else
	    $sid = UNASSIGNED_SHIP;

if (strpos('x'.$Email.$Name.$Age.$Characters_Name.$Characters_Race.$Characters_Gender,'http:')>0) $badapp=true;
elseif (strpos('x'.$Email.$Name.$Age.$Characters_Name.$Characters_Race.$Characters_Gender,'www')>0) $badapp=true;
elseif (strpos('x'.$Email.$Name.$Age.$Characters_Name.$Characters_Race.$Characters_Gender,'[url')>0) $badapp=true;
elseif (strpos('x'.$Email.$Name.$Age.$Characters_Name.$Characters_Race.$Characters_Gender,'[link')>0) $badapp=true;
else {
	/*-------------------------------------------------------*/
	/* Add the applicant to the IFS database                 */
	/*-------------------------------------------------------*/

	// find out if this person already has a UID
	$qry = "SELECT id, password FROM {$mpre}users WHERE email='$Email'";
	$result = $database->openConnectionWithReturn($qry);
	list ($uid, $pass) = mysql_fetch_array($result);

	// if they don't have a UID, create one
	if (!$uid)
    {
	    list($username, $pass, $uid) = make_uid ($database, $mpre, $Name, $Characters_Name, $Email);
	    require_once "includes/mail/app_newplayer.mail.php";

	    $neednewuser = "1";
	    $details = 'UID created: ' . $uid . '<br />\n';
	}
    else
	    $details = 'UID found: ' . $uid . '<br />\n';

	// Insert character into db
	$date = date("F j, Y, g:i a", time());
	$ptime = time();
	$enterpos = $First_Desired_Position . " / " . $Second_Desired_Position;

	$qry = "INSERT INTO {$spre}characters
    		SET name='$Characters_Name', race='$Characters_Race', gender='$Characters_Gender',
            	rank='" . PENDING_RANK . "', ship='$sid', pos='$enterpos', player='$uid', 
				bio='Height: $Height <br />
				Weight: $Weight <br />
				Hair Colour: $Hair <br />
				Eye Colour: $Eye <br />
				Physical Description: $Physical_Desc <br /><br />
				Family<br />
				Spouse: $Spouse <br />
				Children: $Children <br />
				Parents: $Parents <br />
				Siblings: $Siblings <br />
				Other Family: $Other_Family <br /><br />
				Personality &amp; Traits <br />
				General Overview: $General_Overview <br />
				Strengths &amp; Weaknesses: $Strengths_Weaknesses <br />
				Ambitions: $Ambitions <br />
				Hobbies &amp; Interests: $Hobbies_Interests <br /><br />
				Character Bio:<br />
				$Character_Bio <br /><br />
				Service Record:<br />
				$Service_Record',
		        other='Applied on $date', pending='1', ptime='$ptime'";
	$result=$database->openConnectionWithReturn($qry);

	// Service record entry
	$details .= 'Sim: ' . $Ship . '<br />';
	$time = time();
	$qry = "INSERT INTO {$spre}record
    		SET pid='$uid', cid=LAST_INSERT_ID(), level='Out-of-Character', date='$time',
            	entry='Application Received: $Characters_Name', details='$details',
                name='IFS'";
	$database->openConnectionNoReturn($qry);

	$qry = "SELECT cid FROM {$spre}record WHERE id=LAST_INSERT_ID();";
	$result = $database->openConnectionWithReturn($qry);
	list ($cid) = mysql_fetch_array($result);
} //Applicant added to DB
	$subject = fleetname . " Application";
	$headers = "From: " . emailfrom . "\nX-Mailer:PHP\nip: $ip\n";

	$body = "Requested Sim: $Ship\n";
	$body .= "Requested Class: $desiredclass\n";
	$body .= "Alternate Class: $altclass\n\n";

	$body .= "Email Address: $Email\n";
	$body .= "Real Name: $Name\n";
	$body .= "Age: $Age\n\n";

	$body .= "Instant Messengers: $IM\n\n";

	$body .= "Character Name: $Characters_Name\n";
	$body .= "Character Species: $Characters_Race\n";
	$body .= "Character Gender: $Characters_Gender\n\n";

	$body .= "Desired Position: $First_Desired_Position\n";
	$body .= "Alternate Position: $Second_Desired_Position\n";
	if ($Officer_or_Enslisted == "Officer")
    {
	    $body .= "[x] Officer\n";
	    $body .= "[ ] Warrant\n";
	    $body .= "[ ] Enlisted\n\n";
	}
    elseif ($Officer_or_Enlisted == "Enlisted")
    {
	    $body .= "[ ] Officer\n";
	    $body .= "[ ] Warrant\n";
	    $body .= "[x] Enlisted\n\n";
	}
    elseif ($Officer_or_Enlisted == "Warrant")
    {
	    $body .= "[ ] Officer\n";
	    $body .= "[x] Warrant\n";
	    $body .= "[ ] Enlisted\n\n";
	}
	
	$body .= "Character Info:\n";
	$body .= "Height: $Height\n";
	$body .= "Weight: $Weight\n";
	$body .= "Hair Colour: $Hair\n";
	$body .= "Eye Colour: $Eye\n";
	$body .= "Physical Description: $Physical_Desc\n\n";
	
	$body .= "Family\n";
	$body .= "Spouse: $Spouse\n";
	$body .= "Children: $Children\n";
	$body .= "Parents: $Parents\n";
	$body .= "Siblings: $Siblings\n";
	$body .= "Other Family: $Other_Family\n\n";
	
	$body .= "Personality &amp; Traits\n";
	$body .= "General Overview: $General_Overview\n";
	$body .= "Strengths &amp; Weaknesses: $Strengths_Weaknesses\n";
	$body .= "Ambitions: $Ambitions\n";
	$body .= "Hobbies &amp; Interests: $Hobbies_Interests\n\n";
	
	$body .= "Character Bio:\n";
	$body .= "$Character_Bio\n\n";
	
	$body .= "Service Record:\n";
	$body .= "$Service_Record\n\n";

	$body .= "Sample Post:\n";
	$body .= "$Sample_Post\n\n";

	$body .= "Experience:\n";
	$body .= "$RPG_Experience\n\n";

	$body .= "Reference:\n";
	$body .= "$Reference\n";
	$body .= "$Reference_Other\n\n";

	$body = stripcslashes($body);
	$subject = stripslashes($subject);

	// This one goes to the applicant:
	require_once "includes/mail/app_playercopy.mail.php";
	$allemails = $Email;
if (!$badapp) {
	// This one goes to the CO:
	if ($Ship != "Any")
    {
	    require_once "includes/mail/app_cocopy.mail.php";
	    $allemails .= ", " . $coemail;
	}
    else
    {
	    require_once "includes/mail/app_pending.mail.php";
	    $allemails .= $webmasteremail . ", " . $personnelemail . ", " . $coemail;
	}
	
	// Save it in the db
	$body = addslashes($body);
	$nowtime = time();
	$body = htmlspecialchars($body);
	$qry = "INSERT INTO {$spre}apps
    		SET type='crew', date='$nowtime', app='$body', forward='$allemails', ip='$ip'";
	$database->openConnectionNoReturn($qry);

	$qry = "UPDATE {$spre}characters SET app=LAST_INSERT_ID() WHERE id='$cid'";
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
