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
*       matt@mtwilliams.uk
  *
  * Version:	1.17
  * Release Date: June 3, 2004
  * Patch 1.13n:  December 2009
  * Patch 1.14n:  March 2010
  * Patch 1.17:   August 2017
  *
  * Copyright (C) 2003-2004 Frank Anon for Obsidian Fleet RPG
  * Distributed under the terms of the GNU General Public License
  * See doc/LICENSE for details
  *
  * This file contains code from Mambo Site Server 4.0.12
  * Copyright (C) 2000 - 2002 Miro International Pty Ltd
  *
  * See CHANGELOG for patch details
  * Comments: Main ship admin page for COs
  *
 ***/  


if (!defined("IFS"))
	echo "Hacking attempt!";
else
{
	if ($action == 'save_report')
	   	include("tf/co/report2.php");
    else
    {
    	// Give people with admin access to CO area the ability to choose
        // which ship to play with
		if ($uflag['c'] == 2)
        {
        	// Haven't chosen a ship yet, eh?
			if (!$adminship && !$sid)
            {
				?>
				<h3 class="heading text-center">CO Area</h3>
				<p class="text-center">Hey, you're an admin!  That makes you special!<br />
                Choose which ship you want to modify.</p>
				<form class="form-inline text-center" action="index.php?option=ifs&amp;task=co&amp;action=<?php echo $action ?>" method="post">
					<select class="form-control" name="adminship">
						<?php
		    	    	$qry = "SELECT id, name FROM {$spre}ships WHERE tf!='99' ORDER BY name";
		    	    	$result = $database->openConnectionWithReturn($qry);
			        	while ( list ($sid, $sname) = mysql_fetch_array($result) )
							echo '<option value="' . $sid . '">' . $sname . '</option>';
						if ($uflag['c'] == 2) echo '<option value="4">Fleet Staff</option>';
						if ($uflag['c'] == 2) echo '<option value="1">Unassigned Characters</option>';
						?>
					</select>
					<input class="btn btn-default btn-small" type="submit" value="Submit" />
        	    </form>
				<?php
	        	$sid = "selecting";
		    }

            // Now that a ship has been chosen, we make the system think that
            // you're the CO so that it lets you in.
            elseif (!$sid)
            {
			$sid = $adminship;
	        	$name = "Mr. Big-Shot Admin";
		    }
		}

        // Regular CO access?  Well, we need to find your ship...
        elseif ($uflag['c'] == 1 && !$sid)
        {
			$qry = "SELECT name, ship FROM {$spre}characters
            		WHERE player='$uid' AND pos='Commanding Officer'
                    	AND ship!='" . UNASSIGNED_SHIP . "'
                        AND ship!='" . TRANSFER_SHIP. "'
                        AND ship!='" . DELETED_SHIP . "'
                        AND ship!='" . FSS_SHIP . "'";
			$result=$database->openConnectionWithReturn($qry);
			for($i = 0;list($name[$i],$sid[$i])=mysql_fetch_array($result); $i++) ;
   			// Delete last empty one
			if (is_array($sid)){
				array_pop($name);
				array_pop($sid);
			}
			if (is_array($sid) && (count($sid)<2)) {
				$name=$name[0];
				$sid=$sid[0];
			}
		}
		
		
		//CO of more than one ship? Let's pick the one to manage
		if (!$multiship && (count($sid) > 1))
        {
			?>
			<h3 class="heading text-center">CO Area</h3>
			<p class="text-center">Hey, you're a CO on more than one sim!  That makes you special!<br />
            Choose which ship you want to modify.</p>
			<form class="form-inline text-center" action="index.php?option=ifs&amp;task=co&amp;action=<?php echo $action ?>" method="post">
				<select class="form-control" name="multiship">
					<?php
					for ($i = 0; $i < count($sid); $i++)
					{
						$qry = "SELECT id, name FROM {$spre}ships WHERE id='$sid[$i]'";
		    	    	$result = $database->openConnectionWithReturn($qry);
			        	list($sid[$i], $sname[$i]) = mysql_fetch_array($result);
						
						echo '<option value="' . $sid[$i] . '">' . $sname[$i] . '</option>';
					}
					if ($uflag['c'] == 2) echo '<option value="4">Fleet Staff</option>';
					if ($uflag['c'] == 2) echo '<option value="1">Unassigned Characters</option>';
					?>
				</select>
				<input class="btn btn-default btn-sm" type="submit" value="Submit" />
            </form>
			<?php
	       	$sid = "selecting";
	    }

        // Now that a ship has been chosen, we make the system know that
        // you're the CO so that it lets you in.
        elseif (count($sid) > 1)
        {
			$sid = $multiship;
			
				$qry = "SELECT name from {$spre}characters
						WHERE ship='$sid' AND player='$uid'";
				$result = $database->openConnectionWithReturn($qry);
				$name = mysql_fetch_row($result);
		}	
		if ($uflag['c'] >= 1)
        {
			if (!$sid)
				echo '<h4 class="text-center text-warning text-center">You have a CO User Level, ' .
                	 'but you are not listed as the CO of a ship!<br />' .
                     'Sorry, can\'t let you in!</h4>';
			elseif ($sid != 'selecting')
            {
				switch ($action)
                {
                	case 'acad':
                    	include("tf/co/academy.php");
                        break;
                	case 'award':
                    	include("tf/co/award.php");
                        break;
                    case 'awardsave':
                    	include("tf/co/award2.php");
                    	break;
    	    		case 'common':
        	    		include("tf/tools.php");
            	    	break;
					case 'positions':
			    		include("tf/co/positions.php");
	    			    break;
		    		case 'save_pos':
			    		include("tf/co/positions2.php");
				        break;
    				case 'report':
			    		include("tf/co/report.php");
		    	    	break;
			    	case 'save_report':
				    	include("tf/co/report2.php");
				        break;
			    	case 'view':
				    	include("tf/co/view.php");
						break;
					default:
    	            	include("tf/co/view.php");
        	            break;
				}
		    }
		}
        else
			echo '<h3 class="text-danger text-center">You do not have access to this area!</h4>';
	}
}
?>
