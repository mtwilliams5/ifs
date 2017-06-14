<?php
/***
  * INTEGRATED FLEET MANAGEMENT SYSTEM
  * OBSIDIAN FLEET
  * http://www.obsidianfleet.net/ifs/
  *
  * Developer:	Frank Anon
  * 	    	fanon@obsidianfleet.net
  *
  * Updated By: Matt Williams
  *             matt@mtwilliams.uk
  *
  * Version:	1.17
  * Release Date: June 3, 2004
  * Patch 1.17:   June 2017
  *
  * Copyright (C) 2003-2004 Frank Anon for Obsidian Fleet RPG
  * Distributed under the terms of the GNU General Public License
  * See doc/LICENSE for details
  *
  * Comments: IFS Error page; writes custom IFS errors to the ifs-errorlog
  *
 ***/
 
function co_access_error($uid,$uflag,$cid,$sid,$usership,$multiship)
{
		//Convert the uflag array into separate strings, since fputs doesn't seem to like arrays
		$uflagc = $uflag['c'];
		$uflagp = $uflag['p'];
		
		// Log the error
		$now = date("F j, Y, g:i a");
		if (phpversion() <= "4.2.1") //Determine the PHP version, and use the correct superglobal for the values if we're on 4.2.1 or above
		{ //Old PHP version, use getenv() function
			$ip = getenv("REMOTE_ADDR");
			$rmethod = getenv("REQUEST_METHOD");
			$referer = getenv("HTTP_REFERER");
			$uri = getenv("REQUEST_URI");
		}
		else
		{ //New PHP version, use $_SERVER superglobal
			$browse = $_SERVER['HTTP_USER_AGENT'];
			$ip = $_SERVER['REMOTE_ADDR'];
			$rmethod = $_SERVER['REQUEST_METHOD'];
			$referer = $_SERVER['HTTP_REFERER'];
			$uri = $_SERVER['REQUEST_URI'];
		}
		$filename = "ifs-errorlog";
		$spacer = "\n";

		$handle= fopen($filename,'a');
		fputs($handle, "CO Function Access Error\n");
		fputs($handle, "$now - $ip - $rmethod $uri - $referer\n");
		fputs($handle, "uid: $uid - cid: $cid - sid: $sid - usership: $usership - multiship: $multiship - uflagc: $uflagc - uflagp: $uflagp\n");
		fputs($handle, "-----------\n");

		fclose($handle);

		?>

        <p>
		Details of this attempt have been logged. If you received this message in error, please contact the webmaster and provide the time of the error and the action you were attempting to perform.
		</p>
        <?php
}
?>