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
  * Patch 1.17:   August 2017
  *
  * Copyright (C) 2003-2004 Frank Anon for Obsidian Fleet RPG
  * Distributed under the terms of the GNU General Public License
  * See doc/LICENSE for details
  *
  * Comments: Main ship admin page for FCOps
  *
 ***/

if (!defined("IFS"))
	echo "Hacking attempt!";
else
{
	if ($uflag['o'] >= 1)
    {
		switch ($action)
        {
	       	case 'common':
    	       	include("tf/tools.php");
        	    break;
			case 'listing':
    			include("tf/fcops/listing.php");
	   		    break;
	   		case 'report':
		    	include("tf/fcops/report.php");
	    	    break;
		    case 'save_report':
		    	include("tf/fcops/report2.php");
	    	    break;
		    case 'tools':
		    	include("tf/fcops/tools.php");
	    	    break;
		    case 'tools2':
		    	include("tf/fcops/tools2.php");
	    	    break;
	        case 'stats':
    	    	include("tf/fcops/stats.php");
        	    break;
	        default:
    	      	include("tf/fcops/tools.php");
        	    break;
		}
	}
    else
		echo '<h3 class="text-warning">You do not have access to this area!</h3>';
}
?>