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
  * Version:	1.14n (Nolan Ed.)
  * Release Date: June 3, 2004
  * Patch 1.13n:  December 2009
  * Patch 1.14n:  March 2010
  *
  * Copyright (C) 2003-2004 Frank Anon for Obsidian Fleet RPG
  * Distributed under the terms of the GNU General Public License
  * See doc/LICENSE for details
  *
  * This program contains code from Mambo Site Server 4.0.12
  * Copyright (C) 2000 - 2002 Miro International Pty Ltd
  *
  * Date:	6/03/04
  *	Comments: Functions to retrieve lost passwords, register new users.
 ***/

require ("classes/html/registration.php");
$registration = new registration();

include ("configuration.php");
include_once ("includes/addslash.php");

switch($task)
{
    case "lostPassword":
        lostPassForm($registration, $option);
        break;
    case "sendNewPass":
        sendNewPass($checkusername, $confirmEmail, $option, $database, $live_site, $mpre);
        break;
    case "register":
        registerForm($registration, $option);
        break;
    case "saveRegistration":
        saveRegistration($yourname, $username1, $email, $pass, $verifyPass, $option, $database, $live_site, $mpre, $tc);
        break;
}

function lostPassForm($registration, $option)
{
    $registration->lostPassForm($option);
}

function sendNewPass($checkusername, $confirmEmail, $option, $database, $live_site, $mpre)
{
    $qry="SELECT email FROM {$mpre}users
      	  WHERE username='$checkusername' AND email='$confirmEmail'";
    $result=$database->openConnectionWithReturn($qry);
    if (mysql_num_rows($result)==0)
            print "<script> alert('Sorry, no corresponding user was found'); window.history.go(-1); </script>\n";
    else
    {
        $newpass= makepass();

        $checkusername = stripslashes($checkusername);
        $email = stripslashes($confirmEmail);

        require_once "includes/mail/registration_newpassword.mail.php";
        $newpass=md5($newpass);

        $qry="UPDATE {$mpre}users SET password='$newpass' WHERE username='$checkusername'";
        $database->openConnectionNoReturn($qry);
        echo "&nbsp;&nbsp;<br /><br /><b>New User Password Created And Sent!</b>";
    }
}

function makePass()
{
    $makepass="";
    $syllables="er,in,tia,wol,fe,pre,vet,jo,nes,al,len,son,cha,ir,ler,bo,ok,tio,nar,sim,ple,bla,ten,toe,cho,co,lat,spe,ak,er,po,co,lor,pen,cil,li,ght,wh,at,the,he,ck,is,mam,bo,no,fi,ve,any,way,pol,iti,cs,ra,dio,sou,rce,sea,rch,pa,per,com,bo,sp,eak,st,fi,rst,gr,oup,boy,ea,gle,tr,ail,bi,ble,brb,pri,dee,kay,en,be,se";
    $syllable_array=explode(",", $syllables);
    mt_srand((double)microtime()*1000000);
    for ($count=1;$count<=4;$count++)
        if (mt_rand()%10 == 1)
            $makepass .= sprintf("%0.0f",(mt_rand()%50)+1);
        else
            $makepass .= sprintf("%s",$syllable_array[mt_rand()%62]);
    return($makepass);
}

function registerForm($registration, $option)
{
    $registration->registerForm($option);
}

function saveRegistration($yourname, $username1, $email, $pass, $verifyPass, $option, $database, $live_site, $mpre, $tc)
{
	$bademail = "1";
	if (strstr ($email, '@'))
	{
	    $emaildomain = strstr ($email, '@');
	    if (strstr($emaildomain, "."))
	        $bademail = "0";
    }
	
	// Let's work out the difference in the time set in the form with the current time. Scripts are very fast, so anything under 3 seconds should be discarded.
	$diff = ''; //Make sure $diff is reset each time
	$now = strtotime("now");
	$diff = $now - $tc;
	
	if ($diff <= 3)
	{
		echo '<h3 class="text-success">Form Complete!</h3> <p>Your form has been submitted. Thank you for registering.</p>'; //Don't know how smart the scripts will be, so return a message which looks like a success.
		//Get details of the person or bot who submitted the form
		$browse = $_SERVER['HTTP_USER_AGENT'];
		$ip = $_SERVER['REMOTE_ADDR'];
		$rmethod = $_SERVER['REQUEST_METHOD'];
		$referer = $_SERVER['HTTP_REFERER'];
		$uri = $_SERVER['REQUEST_URI'];
		$now = date("c",$now); // set the time to a human-readable format for the log file
		
		//Now lets print the details into a log file
		$filename = "spam_log";
		$spacer = "\n";

		$handle= fopen($filename,'a');
		fputs($handle, "$ip [$now] \"$rmethod $uri\" \"$referer\" \"$browse\" $diff s\n");
		fclose($handle);
		
    }
	elseif (trim($yourname)=="")
        print "<script> alert('Please enter your name.'); window.history.go(-1); </script>\n";
    elseif (trim($username1)=="")
        print "<script> alert('Please enter a user name.'); window.history.go(-1); </script>\n";
    elseif (trim($email)=="")
        print "<script> alert('Please enter your email address.'); window.history.go(-1); </script>\n";
    elseif (trim($pass)=="")
        print "<script> alert('Please enter a password.'); window.history.go(-1); </script>\n";
    elseif (trim($verifyPass)=="")
        print "<script> alert('Please verify the password.'); window.history.go(-1); </script>\n";
    elseif ($pass!=$verifyPass)
        print "<script> alert('Password and verification do not match, please try again.'); window.history.go(-1); </script>\n";
    elseif ($bademail == "1")
    	print "<script> alert('Email address is not valid.'); window.history.go(-1); </script>\n";
    else
    {
        $qry="SELECT id FROM {$mpre}users WHERE username='$username1' OR email='$email'";
        $result=$database->openConnectionWithReturn($qry);
        if (mysql_num_rows($result)!=0)
            print "<script> alert('This username or email already in use. Please try another.'); window.history.go(-1); </script>\n";
        else
        {
            $cryptpass=md5($pass);
            $qry="INSERT INTO {$mpre}users
             	  (name, username, email, password)
                  VALUES ('$yourname', '$username1', '$email', '$cryptpass')";
            $database->openConnectionNoReturn($qry);

            require_once "includes/mail/registration_newuser.mail.php";

            echo '<h3 class="text-success">Registration Complete!</h3>';
            echo '<p>Registration is complete, you may now log in with your username and password.</p>';
        }
    }
}
?>
