<?php
/***
  * INTEGRATED FLEET MANAGEMENT SYSTEM
  * OBSIDIAN FLEET
  * http://www.obsidianfleet.net
  *
  * Developer:	Frank Anon
  * 	    	fanon@obsidianfleet.net
  *
  * Updated By: Matt Williams
  *             matt@mtwilliams.uk
  *
  * Version:	1.17
  * Release Date: June 3, 2004
  *
  * Copyright (C) 2003-2004 Frank Anon for Obsidian Fleet RPG
  * Distributed under the terms of the GNU General Public License
  * See doc/LICENSE for details
  *
  * Comments: Awards Director tools
  *
 ***/

if (!defined("IFS"))
	echo "Hacking attempt!";
else
{
	if ($uflag['w'] >= 1)
    {
		switch ($action)
        {
        	case 'award':
            	include("tf/awards/grant.php");
                break;
			case 'common':
	    		include("tf/tools.php");
	    	    break;
            case 'del':
            	include("tf/awards/awards2.php");
                break;
            case 'edit':
            	include("tf/awards/awards.php");
                break;
            case 'pending':
            	include("tf/awards/pending.php");
                break;
            case 'save':
            	include("tf/awards/awards2.php");
                break;
	        default:
	        	include("tf/awards/pending.php");
	            break;
	    }
	}
    else
		echo '<h3 class="text-warning">You do not have access to this area!</h3>';
}

?>