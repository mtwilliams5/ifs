<?php
/***
  * INTEGRATED FLEET MANAGEMENT SYSTEM
  * OBSIDIAN FLEET
  * http://www.obsidianfleet.net/ifs/
  *
  * Developer:	Frank Anon
  * 	    	fanon@obsidianfleet.net
  *
  * Version:	1.11
  * Release Date: June 3, 2004
  *
  * Copyright (C) 2003-2004 Frank Anon for Obsidian Fleet RPG
  * Distributed under the terms of the GNU General Public License
  * See doc/LICENSE for details
  *
  * This file based on code from Ship Database
  * Copyright (C) 2003 Frank Anon for Obsidian Fleet RPG
  *
  * Date:	2/26/04
  * Comments: Ship Database Admin
 ***/

if (!defined("IFS")) {
	echo "Hacking attempt!";
} else {

	echo "<h1>Obsidian Fleet Ship Database Admin</h1><br />\n\n";

    // Editing categories
	if ($cat)
    {
    	if ($cat == "edit")
        {
        	$qry = "SELECT c.id, c.name, c.type, c.description
            		FROM {$sdb}category c, {$sdb}types t
                    WHERE c.type=t.id AND t.support='{$ctype}'
                    ORDER BY c.type, c.name";
            $result = $database->openShipsWithReturn($qry);
            while (list($cid, $cname, $ctype2, $cdesc) = mysql_fetch_array($result))
            {
            	?>
				<form action="index.php?option=ifs&amp;task=rd&amp;action=shipdb&amp;cat=save" method="post">
                <input type="hidden" name="cid" value="<?php echo $cid?>" /><br />
                Name: <input type="text" name="cname" value="<?php echo $cname ?>" size="30" /><br />
                Type: <select name="ctype">
                <?php
                $qry2 = "SELECT id, type FROM {$sdb}types WHERE support='{$ctype}' ORDER BY type";
                $result2 = $database->openShipsWithReturn($qry2);
                while(list($tid, $type) = mysql_fetch_array($result2))
                {
                	echo "<option value=\"$tid\"";
                	if ($tid == $ctype2)
                    	echo " selected=\"selected\"";
                    echo ">$type</option>\n";
            	}
                ?>
                </select><br /><br />
                Description:<br />
                <textarea name="cdesc" cols="60" rows="5"><?php echo $cdesc ?></textarea><br />
				<table border="0"><tr><td>
	                <input type="submit" value="Update" /></form></td><td>
					<form action="index.php?option=ifs&amp;task=rd&amp;action=shipdb&amp;cat=delete" method="post">
        	        <input type="hidden" name="cid" value="<?php echo $cid ?>" />
            	    <input type="submit" value="DELETE!" /></form>
                </td></tr></table>
                <?php
            }
            $qry = "SELECT id, type FROM {$sdb}types WHERE support='{$ctype}' ORDER BY type";
            $result = $database->openShipsWithReturn($qry);
            if ( mysql_num_rows($result) )
            {
	            ?><br />
	            <form action="index.php?option=ifs&amp;task=rd&amp;action=shipdb&amp;cat=add" method="post">
	            Name: <input type="text" name="cname" size="30" /><br />
	            Type: <select name="ctype">
	            <?php
	            while(list($tid, $type) = mysql_fetch_array($result))
	                echo "<option value=\"$tid\">$type</option>\n";
	            ?>
	            </select><br /><br />
	            Description:<br />
	            <textarea name="cdesc" cols="60" rows="5"></textarea><br />
	            <input type="submit" value="Add" /></form><br /><br />
	            <?php
            }
            else
            	echo "You must create a type first (ie Starship or Starbase).<br />\n";
        }
        elseif ($cat == "add")
        {
        	$qry = "INSERT INTO {$sdb}category
            		SET name='$cname', type='$ctype', description='$cdesc'";
			$database->openShipsWithReturn($qry);
            redirect("index.php?option=ifs&task=rd");
        }
        elseif ($cat == "save")
        {
        	$qry = "UPDATE {$sdb}category
            		SET name='$cname', type='$ctype', description='$cdesc'
                   	WHERE id='$cid'";
			$database->openShipsWithReturn($qry);
            redirect("");
        }
        elseif ($cat == "delete")
        {
        	$qry = "SELECT id FROM {$sdb}classes WHERE category='$cid'";
            $result = $database->openShipsWithReturn($qry);
            if (mysql_num_rows($result))
            	echo "Cannot delete category - you must make sure that no ships are classified under this category first!";
            else
            {
	        	$qry = "DELETE FROM {$sdb}category WHERE id='$cid'";
                $database->openShipsWithReturn($qry);
                redirect("");
            }
        }

	}

    // Editing types
    elseif ($type)
    {
    	if (!$typeaction)
        {
            if ($type == "w")
            	echo "Edit Weapon types<br /><br />\n";
            elseif ($type == "y")
            	echo "Edit Support Craft types<br /><br />\n";
            elseif ($type == "n")
            	echo "Edit Starship types<br /><br />\n";

        	$qry = "SELECT id, type FROM {$sdb}types WHERE support='{$type}' ORDER BY type";
            $result = $database->openShipsWithReturn($qry);
            while (list($tid, $tname) = mysql_fetch_array($result))
            {
            	?>
				<form action="index.php?option=ifs&amp;task=rd&amp;action=shipdb&amp;type=<?php echo $type?>&amp;typeaction=save" method="post">
                <input type="hidden" name="tid" value="<?php echo $tid?>" /><br />
                Name: <input type="text" name="tname" value="<?php echo $tname?>" size="30" /><br />
				<table border="0"><tr><td>
                	<input type="submit" value="Update" /></form></td><td>
	                <form action="index.php?option=ifs&amp;task=rd&amp;action=shipdb&amp;type=<?php echo $type?>&amp;typeaction=delete" method="post">
	                <input type="hidden" name="tid" value="<?php echo $tid ?>" />
	                <input type="submit" value="DELETE!" /></form>
                </td></tr></table>
                <?php
            }
            ?>
			<form action="index.php?option=ifs&amp;task=rd&amp;action=shipdb&amp;type=<?php echo $type ?>&amp;typeaction=add" method="post">
            Name: <input type="text" name="tname" size="30" /><br />
            <input type="submit" value="Add" /></form><br /><br />
            <?php
        }
        elseif ($typeaction == "add")
        {
        	$qry = "INSERT INTO {$sdb}types
            		SET type='$tname', support='$type'";
			$database->openShipsWithReturn($qry);
			redirect("");
        }
        elseif ($typeaction == "save")
        {
        	$qry = "UPDATE {$sdb}types SET type='$tname' WHERE id='$tid'";
			$database->openShipsWithReturn($qry);
            redirect("");
        }
        elseif ($typeaction == "delete")
        {
        	if ($type == "w")
	        	$qry = "SELECT id FROM {$sdb}weapons WHERE type='$tid'";
            else
	        	$qry = "SELECT id FROM {$sdb}category WHERE type='$tid'";
            $result = $database->openShipsWithReturn($qry);
            if (mysql_num_rows($result))
            	echo "Cannot delete type - you must make sure that nothing is classified under this type first!";
            else
            {
	        	$qry = "DELETE FROM {$sdb}types WHERE id='$tid'";
                $database->openShipsWithReturn($qry);
                redirect("");
            }
        }

    }
    elseif ($detail)
    {
		if ($save == "delete")
        {
        	$qry = "SELECT id FROM {$sdb}equip WHERE equipment='$detail' AND type='w'";
   	        $result = $database->openShipsWithReturn($qry);
       	    if (mysql_num_rows($result))
           		echo "Cannot delete weapon - you must make sure that it is not in use first!";
            else
            {
	        	$qry = "DELETE FROM {$sdb}weapons WHERE id='$detail'";
       	        $database->openShipsWithReturn($qry);
                redirect("");
    	    }
		}
        elseif ($save == "yes")
        {
        	if ($detail == "add")
            {
                if ($sub{0} == "s")
                {
                    $sub = substr($sub, 1);
                    $qry = "SELECT type FROM {$sdb}weapons WHERE id='$sub'";
                    $result = $database->openShipsWithReturn($qry);
                    list($type) = mysql_fetch_array($result);
                }
                elseif ($sub{0} == "t")
                {
                    $type = substr($sub, 1);
                    $sub = "0";
                }
                $qry = "INSERT INTO {$sdb}weapons SET name='$dname', description='$ddesc', image='$dimage', sub='$sub', type='$type'";
                $database->openShipsWithReturn($qry);
                $qry = "SELECT id FROM {$sdb}weapons WHERE name='$dname' AND description='$ddesc' AND image='$dimage' AND sub='$sub' AND type='$type'";
                $result = $database->openShipsWithReturn($qry);
                list ($detail) = mysql_fetch_array($result);
            }
            else
            {
            	if ($sub{0} == "s")
                {
                	$sub = substr($sub, 1);
                    $qry = "SELECT type FROM {$sdb}weapons WHERE id='$sub'";
                    $result = $database->openShipsWithReturn($qry);
                    list($type) = mysql_fetch_array($result);
                }
                elseif ($sub{0} == "t")
                {
                	$type = substr($sub, 1);
                    $sub = "0";
                }
	    		$qry = "UPDATE {$sdb}weapons SET name='$dname', description='$ddesc', image='$dimage', sub='$sub', type='$type' WHERE id='$detail'";
		        $database->openShipsWithReturn($qry);
            }
            redirect("");
	    }

		$qry = "SELECT name, description, image, sub, type FROM {$sdb}weapons WHERE id='$detail'";
   		$result = $database->openShipsWithReturn($qry);
	    list ($dname, $ddesc, $dimage, $sub, $type) = mysql_fetch_array($result);

        if ($detail != "add")
        {
	        ?>
	        <form action="index.php?option=ifs&amp;task=rd&amp;action=shipdb" method="post">
	        <input type="hidden" name="save" value="delete" />
	        <input type="hidden" name="detail" value="<?php echo $detail ?>" />
	        <input type="submit" value="DELETE" /></form>
        <?php
        }
        $qry = "SELECT id, type FROM {$sdb}types WHERE support='w'";
        $result = $database->openShipsWithReturn($qry);
        if ( mysql_num_rows($result) )
        {
	        ?>
	        <form action="index.php?option=ifs&amp;task=rd&amp;action=shipdb" method="post">
	        <input type="hidden" name="save" value="yes" />
	        <input type="hidden" name="detail" value="<?php echo $detail ?>" />
	        Name: <input type="text" name="dname" value="<?php echo $dname ?>" size="30" /><br /><br />
	        Image URL: <input type="text" name="dimage" value="<?php echo $dimage ?>" size="30" /><br /><br />
	        Description:<br />
	        <textarea name="ddesc" rows="5" cols="60"><?php echo $ddesc ?></textarea>
	        <br /><br />
	        Parent or type:
	        <select name="sub">
	        <?php
	        while(list($tid, $tname) = mysql_fetch_array($result))
	        {
	            echo "<option value=\"t{$tid}\"";
	            if ($sub == "0" && $type == $tid)
	                echo " selected=\"selected\"";
	            echo ">{$tname}</option>\n";

	            $qry2 = "SELECT id, name FROM {$sdb}weapons
	                     WHERE type='$tid' AND sub='0'";
	            $result2 = $database->openShipsWithReturn($qry2);
	            while (list ($wid, $wname) = mysql_fetch_array($result2))
	            {
	                echo "<option value=\"s{$wid}\"";
	                if ($sub == $wid)
	                    echo " selected=\"selected\"";
	                echo ">&nbsp;&nbsp;{$wname}</option>\n";
	            }
	        }
	        ?>
	        </select><br /><br />
	        <input type="submit" value="Submit" /></form>
	        <br /><br />
	        <?php
        }
        else
        	echo "You must create a weapon type first (ie phaser or torpedo)<br />\n";
	}
    elseif ($sclass)
    {
		if ($save == "yes")
        {
        	if ($sclass == "add")
            {
            	if ($active == "on")
                	$active = "1";
                else
                	$active = "0";

	        	$qry = "INSERT INTO {$sdb}classes
			            SET name='$cname', duration='$duration', resupply='$resupply', refit='$refit',
        	            category='$category', cruisevel='$cruisevel', maxvel='$maxvel', emervel='$emervel',
						eveltime='$eveltime', officers='$officers', enlisted='$enlisted',
                	    passengers='$passengers', marines='$marines', evac='$evac', shuttlebays='$shuttlebays',
						length='$length', width='$width', height='$height', decks='$decks', notes='$notes',
                        description='$desc', image='$image', active='$active'";
    	        $database->openShipsWithReturn($qry);

	        	$qry = "SELECT id FROM {$sdb}classes
			            WHERE name='$cname' AND duration='$duration' AND resupply='$resupply' AND refit='$refit' AND
        	            category='$category' AND cruisevel='$cruisevel' AND maxvel='$maxvel' AND emervel='$emervel' AND
						eveltime='$eveltime' AND officers='$officers' AND enlisted='$enlisted' AND
                	    passengers='$passengers' AND marines='$marines' AND evac='$evac' AND shuttlebays='$shuttlebays' AND
						length='$length' AND width='$width' AND height='$height' AND decks='$decks' AND description='$desc' AND
                        notes='$notes' AND image='$image'";
		    	$result = $database->openShipsWithReturn($qry);
				list ($sclass) = mysql_fetch_array($result);
            }
            else
            {
            	if ($active == "on")
                	$active = "1";
                else
                	$active = "0";

	        	$qry = "UPDATE {$sdb}classes
			            SET name='$cname', duration='$duration', resupply='$resupply', refit='$refit',
        	            category='$category', cruisevel='$cruisevel', maxvel='$maxvel', emervel='$emervel',
						eveltime='$eveltime', officers='$officers', enlisted='$enlisted',
                	    passengers='$passengers', marines='$marines', evac='$evac', shuttlebays='$shuttlebays',
						length='$length', width='$width', height='$height', decks='$decks', description='$desc',
                        notes='$notes', image='$image', active='$active'
	                    WHERE id='$sclass'";
    	        $database->openShipsWithReturn($qry);
            }

            $qry = "DELETE FROM {$sdb}decks
            		WHERE ship='$sclass' AND deck > '$decks'";
            $database->openShipsWithReturn($qry);

            $qry = "SELECT deck, descrip FROM {$sdb}decks WHERE ship='$sclass'";
            $result = $database->openShipsWithReturn($qry);

            while (list($decknum, $deckdesc) = mysql_fetch_array($result) )
            	$decklist[$decknum] = $deckdesc;

            for ($i = 1; $i <= $decks; $i++)
            {
            	if ($decklist[$i] != $deck_desc[$i])
                {
                	if ($deck_desc[$i] == "")
                    	$qry2 = "DELETE FROM {$sdb}decks
                        	  	 WHERE ship='$sclass' AND deck='$i'";
                    elseif ($decklist[$i] == "")
                    	$qry2 = "INSERT INTO {$sdb}decks
                        		 SET ship='$sclass', deck='$i', descrip='$deck_desc[$i]'";
					else
	                	$qry2 = "UPDATE {$sdb}decks SET descrip='$deck_desc[$i]'
                        		 WHERE ship='$sclass' AND deck='$i'";
                    $database->openShipsWithReturn($qry2);
                }
            }

			$qry = "SELECT c.id FROM {$sdb}classes c, {$sdb}category d, {$sdb}types t
            		WHERE c.category=d.id AND d.type=t.id AND t.support='y'";
		    $result = $database->openShipsWithReturn($qry);
		    while (list($cid) = mysql_fetch_array($result))
            {
       			if ($auxcr[$cid] != "0" && $auxcr[$cid])
                {
           			$qry2 = "SELECT id FROM {$sdb}equip WHERE ship='$sclass' AND type='c' AND equipment='$cid'";
					$result2 = $database->openShipsWithReturn($qry2);
                    if (mysql_num_rows($result2))
                    {
                    	list($eid) = mysql_fetch_array($result2);
	           			$qry2 = "UPDATE {$sdb}equip SET number='$auxcr[$cid]' WHERE id='$eid'";
						$result2 = $database->openShipsWithReturn($qry2);
                    }
                    else
                    {
	                	$qry2 = "INSERT INTO {$sdb}equip SET number='$auxcr[$cid]', ship='$sclass', type='c', equipment='$cid'";
						$database->openShipsWithReturn($qry2);
       		        }
           		}
                else
                {
	               	$qry2 = "DELETE FROM {$sdb}equip WHERE ship='$sclass' AND type='c' AND equipment='$cid'";
	                $database->openShipsWithReturn($qry2);
   		        }
			}

			$qry = "SELECT id FROM {$sdb}weapons";
		    $result = $database->openShipsWithReturn($qry);
		    while (list($wid) = mysql_fetch_array($result))
            {
           		if ($weap[$wid] != "0" && $weap[$wid])
                {
	                $qry2 = "SELECT id FROM {$sdb}equip WHERE ship='$sclass' AND type='w' AND equipment='$wid'";
				    $result2 = $database->openShipsWithReturn($qry2);
                    if (mysql_num_rows($result2))
                    {
                    	list($eid) = mysql_fetch_array($result2);
	               		$qry2 = "UPDATE {$sdb}equip SET number='$weap[$wid]' WHERE id='$eid'";
						$result2 = $database->openShipsWithReturn($qry2);
                    }
                    else
                    {
   	                	$qry2 = "INSERT INTO {$sdb}equip SET number='$weap[$wid]', ship='$sclass', type='w', equipment='$wid'";
						$database->openShipsWithReturn($qry2);
           	        }
               	}
                else
                {
               		$qry2 = "DELETE FROM {$sdb}equip WHERE ship='$sclass' AND type='w' AND equipment='$wid'";
                    $database->openShipsWithReturn($qry2);
   	            }
			}
            redirect("");
    	}
        elseif ($save == "delete")
        {
        	$qry = "DELETE FROM {$sdb}classes WHERE id='$sclass'";
            $database->openShipsWithReturn($qry);
        	$qry = "DELETE FROM {$sdb}equip WHERE ship='$sclass' OR (equipment='$sclass' AND type='c')";
            $database->openShipsWithReturn($qry);
            redirect("");
        }

        if ($sclass != "add")
        {
		    $qry = "SELECT c.name, duration, resupply, refit, d.name, t.type, t.support, cruisevel,
    				maxvel, emervel, eveltime, officers, enlisted, passengers, marines,
        		    evac, shuttlebays, length, width, height, decks, notes, c.description, image, active
            		FROM {$sdb}classes c, {$sdb}category d, {$sdb}types t
	            	WHERE c.id='$sclass' AND c.category=d.id AND d.type=t.id";
	    	$result = $database->openShipsWithReturn($qry);
		    list ($cname, $duration, $resupply, $refit, $category, $type, $support, $cruisevel, $maxvel,
    			  $emervel, $eveltime,  $officers, $enlisted, $passengers, $marines, $evac,
        		  $shuttlebays, $length, $width, $height, $decks, $notes, $desc, $image, $active)
		          = mysql_fetch_array($result);
        }
        else
        	$support = $sup;
        if ($sclass != "add")
        {
	        ?>
	        <form action="index.php?option=ifs&amp;task=rd&amp;action=shipdb" method="post">
	        <input type="hidden" name="save" value="delete" />
	        <input type="hidden" name="sclass" value="<?php echo $sclass ?>" />
	        <input type="submit" value="DELETE THIS CLASS" /></form>
        	<?php
        }
    	$qry = "SELECT c.id, c.name, t.type
        		FROM {$sdb}category c, {$sdb}types t
                WHERE c.type=t.id AND t.support='$support'
				ORDER BY t.type, c.name";
	    $result = $database->openShipsWithReturn($qry);
        if ( mysql_num_rows($result) )
        {
	        ?>

	        <form action="index.php?option=ifs&amp;task=rd&amp;action=shipdb" method="post">
	        <input type="hidden" name="save" value="yes" />
	        <input type="hidden" name="sclass" value="<?php echo $sclass ?>" />
	        Class: <input type="text" name="cname" value="<?php echo $cname ?>" size="30" /><br />

	        <select name="category">
	        <?php
	        while(list($catid, $catname, $cattype) = mysql_fetch_array($result))
	        {
	            echo "<option value=\"$catid\"";
	            if ($catname == $category)
	                echo " selected=\"selected\"";
	            echo ">$catname ($cattype)</option>\n";
	        }
	        echo "</select>\n<br /><br />\n";

	        if ($active == "1")
	            echo "<input type=\"checkbox\" name=\"active\" checked=\"checked\"> Active<br /><br />\n";
	        else
	            echo "<input type=\"checkbox\" name=\"active\"> Active<br /><br />\n";
	        ?>
	        Image: <input type="text" name="image" value="<?php echo $image ?>" size="30" /><br />

	        Expected Duration: <input type="text" name="duration" value="<?php echo $duration ?>" size="4" maxlength="4" /> years<br />
	        Time Between Resupply: <input type="text" name="resupply" value="<?php echo $resupply ?>" size="4" maxlength="4" /> years<br />
	        Time Between Refit: <input type="text" name="refit" value="<?php echo $refit ?>" size="4" maxlength="4" /> years<br /><br />

	        <a class="heading">Personnel</a><br />
	        Officers: <input type="text" name="officers" value="<?php echo $officers ?>" size="6" maxlength="6" /><br />
	        Enlisted Crew: <input type="text" name="enlisted" value="<?php echo $enlisted ?>" size="6" maxlength="6" /><br />
	        Marines: <input type="text" name="marines" value="<?php echo $marines ?>" size="6" maxlength="6" /><br />
	        Passengers: <input type="text" name="passengers" value="<?php echo $passengers ?>" size="6" maxlength="6" /><br />
	        Maximum (Evacuation) Capacity: <input type="text" name="evac" value="<?php echo $evac ?>" size="6" maxlength="6" /><br />
	        <I>If there is no difference between officers & enlisted (ie fighters, shuttles), enter '0' for Officers and the crew number for Enlisted.<br />
	        There Marines number also will not be displayed on such craft.<br />
	        If Passengers or Max Capacity are set to '0', they will not be displayed.</I><br /><br />

	        <a class="heading">Speed</a><br />
	        Cruising Velocity: Warp <input type="text" name="cruisevel" value="<?php echo $cruisevel ?>" size="5" maxlength="5" /><br />
	        Maximum Velocity: Warp <input type="text" name="maxvel" value="<?php echo $maxvel ?>" size="5" maxlength="5" /><br />
	        Emergency Velocity: Warp <input type="text" name="emervel" value="<?php echo $emervel ?>" size="5" maxlength="5" />
	        (for <input type="text" name="eveltime" value="<?php echo $eveltime ?>" size="3" maxlength="3" /> hours)<br /><br />

	        <a class="heading">Dimensions</a><br />
	        Length: <input type="text" name="length" value="<?php echo $length ?>" size="10" maxlength="10" /> metres<br />
	        Width: <input type="text" name="width" value="<?php echo $width ?>" size="10" maxlength="10" /> metres<br />
	        Height: <input type="text" name="height" value="<?php echo $height ?>" size="10" maxlength="10" /> metres<br />
	        Decks: <input type="text" name="decks" value="<?php echo $decks ?>" size="6" maxlength="6" /><br /><br />
	        <?php

	        if ($support == "n")
	        {
	            echo "<a class=\"heading\">Auxiliary Craft</a><br />\n";
	            echo "Shuttlebays: <input type=\"text\" name=\"shuttlebays\" value=\"$shuttlebays\" size=\"4\" maxlength=\"4\" /><br /><br />\n";
	            $qry = "SELECT c.id, c.name, d.name, t.type
	                    FROM {$sdb}classes c, {$sdb}category d, {$sdb}types t
	                    WHERE c.category=d.id AND d.type=t.id AND t.support='y' AND d.type=t.id
	                    ORDER BY t.type, c.name";
	            $result = $database->openShipsWithReturn($qry);
	            while (list($cid, $cname, $catname, $type) = mysql_fetch_array($result))
	            {
	                if ($oldtype != $type)
	                {
	                    echo "{$type}s<br />\n";
	                    $oldtype = $type;
	                }
	                if ($sclass != "add")
	                {
	                    $qry2 = "SELECT number FROM {$sdb}equip
	                             WHERE ship='$sclass' AND type='c' AND equipment='$cid'";
	                    $result2 = $database->openShipsWithReturn($qry2);
	                    if (mysql_num_rows($result2))
	                        list ($cnum) = mysql_fetch_array($result2);
	                    else
	                        $cnum = "0";
	                }
	                else
	                    $cnum = "0";
	                echo "&nbsp;&nbsp;&nbsp;<input type=\"text\" name=\"auxcr[{$cid}]\" value=\"$cnum\" size=\"3\" /> $cname $catname<br />\n";
	            }
	            echo "<br />\n\n";
	        }

	        echo "<a class=\"heading\">Armament</a><br />\n";
	        $qry = "SELECT w.id, w.name, t.type
	                FROM {$sdb}weapons w, {$sdb}types t
	                WHERE w.sub='0' AND w.type=t.id
	                ORDER BY t.type, w.name";
	        $result = $database->openShipsWithReturn($qry);
	        while (list($wid, $wname, $type) = mysql_fetch_array($result))
	        {
	            if ($oldtype != $type)
	            {
	                echo "{$type}s<br />\n";
	                $oldtype = $type;
	            }
	            if ($sclass != "add")
	            {
	                $qry2 = "SELECT number FROM {$sdb}equip
	                         WHERE ship='$sclass' AND type='w' AND equipment='$wid'";
	                $result2 = $database->openShipsWithReturn($qry2);
	                if (mysql_num_rows($result2))
	                    list ($wnum) = mysql_fetch_array($result2);
	                else
	                    $wnum = "0";
	            }
	            else
	                $wnum = "0";
	            echo "&nbsp;&nbsp;&nbsp;<input type=\"text\" name=\"weap[{$wid}]\" value=\"$wnum\" size=\"3\" /> $wname<br />\n";

	            $qry2 = "SELECT id, name FROM {$sdb}weapons
	                     WHERE sub='$wid' ORDER BY name";
	            $result2 = $database->openShipsWithReturn($qry2);
	            while (list($wid, $wname) = mysql_fetch_array($result2))
	            {
	                if ($sclass != "add")
	                {
	                    $qry3 = "SELECT number FROM {$sdb}equip WHERE ship='$sclass' AND type='w' AND equipment='$wid'";
	                    $result3 = $database->openShipsWithReturn($qry3);
	                    if (mysql_num_rows($result3))
	                        list ($wnum) = mysql_fetch_array($result3);
	                    else
	                        $wnum = "0";
	                }
	                else
	                    $wnum = "0";
	                echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type=\"text\" name=\"weap[{$wid}]\" value=\"$wnum\" size=\"3\" /> $wname<br />\n";
	            }
	        }
	        ?>
	        <br /><br />
            Notes<br />
	        <textarea name="notes" cols="40" rows="3"><?php echo $notes ?></textarea><br /><br />

            Description<br />
	        <textarea name="desc" cols="60" rows="5"><?php echo $desc ?></textarea><br />

            <b>Deck Listing</b><br />
            <table border="0" align="center">
            	<tr><th>Deck</th><th>Description</th></tr>
	            <?php
				$qry = "SELECT deck, descrip FROM {$sdb}decks WHERE ship='$sclass'";
				$result = $database->openShipsWithReturn($qry);

	            while (list($decknum, $deckdesc) = mysql_fetch_array($result) )
	            	$decklist[$decknum] = $deckdesc;

	            for ($i = 1; $i <= $decks; $i++)
                {
                	echo "<tr><td>$i</td>\n";
                    echo "<td><input type=\"text\" size=\"70\" name=\"deck_desc[$i]\" " .
                    	 "value=\"{$decklist[$i]}\" />\n";
					echo "</td></tr>";
				}
                ?>
            </table>
            <br /><br />

	        <input type="submit" value="Submit" /></form><br /><br />
	        <br /><br />
	        <?php
        }
        else
        	echo "You must create categories first! (ie destroyer, cruiser...)<br />\n";
	}
    else
    {
		echo "<h1>Starships</h1>\n";
		$qry = "SELECT c.id, c.name, t.type
        		FROM {$sdb}classes c, {$sdb}category d, {$sdb}types t
                WHERE c.category=d.id AND d.type=t.id AND t.support='n'
                ORDER BY t.type, c.name";
		$result = $database->openShipsWithReturn($qry);
	    while(list($cid, $cname, $type) = mysql_fetch_array($result))
	    	echo "<a href=\"index.php?option=ifs&amp;task=rd&amp;action=shipdb&amp;sclass={$cid}\">{$cname}-class $type</a><br />\n";
    	echo "<br /><a href=\"index.php?option=ifs&amp;task=rd&amp;action=shipdb&amp;sclass=add&amp;sup=n\">Add a Starship</a><br />\n";
    	echo "<a href=\"index.php?option=ifs&amp;task=rd&amp;action=shipdb&amp;type=n\">Edit Starship types</a><br />\n";
    	echo "<a href=\"index.php?option=ifs&amp;task=rd&amp;action=shipdb&amp;cat=edit&amp;ctype=n\">Edit Starship Categories</a><br />\n";
	    echo "<br />\n";

		echo "<h1>Support Craft</h1>\n";
		$qry = "SELECT c.id, c.name, t.type
        		FROM {$sdb}classes c, {$sdb}category d, {$sdb}types t
                WHERE c.category=d.id AND d.type=t.id AND t.support='y'
                ORDER BY t.type, c.name";
		$result = $database->openShipsWithReturn($qry);
	    while(list($cid, $cname, $type) = mysql_fetch_array($result))
        {
        	if ($oldtype != $type)
            {
            	echo "{$type}s<br />\n";
                $oldtype = $type;
            }
	    	echo "&nbsp;&nbsp;&nbsp;<a href=\"index.php?option=ifs&amp;task=rd&amp;action=shipdb&amp;sclass={$cid}\">{$cname} class</a><br />\n";
	  	}
    	echo "<br /><a href=\"index.php?option=ifs&amp;task=rd&amp;action=shipdb&amp;sclass=add&amp;sup=y\">Add a Support Craft</a><br />\n";
    	echo "<a href=\"index.php?option=ifs&amp;task=rd&amp;action=shipdb&amp;type=y\">Edit Support Craft types</a><br />\n";
    	echo "<a href=\"index.php?option=ifs&amp;task=rd&amp;action=shipdb&amp;cat=edit&amp;ctype=y\">Edit Support Craft Categories</a><br />\n";
	    echo "<br />\n";

		echo "<h1>Weaponry</h1>\n";
		$qry = "SELECT w.id, w.name, t.type
        		FROM {$sdb}weapons w, {$sdb}types t
                WHERE w.sub='0' AND w.type=t.id
                ORDER BY t.type, w.name";
	    $result = $database->openShipsWithReturn($qry);
	    while (list($wid, $wname, $type) = mysql_fetch_array($result))
        {
        	if ($oldtype != $type)
            {
            	echo "{$type}s<br />\n";
                $oldtype = $type;
            }
	    	echo "&nbsp;&nbsp;&nbsp;<a href=\"index.php?option=ifs&amp;task=rd&amp;action=shipdb&amp;detail={$wid}\">{$wname}</a><br />\n";

			$qry2 = "SELECT id, name FROM {$sdb}weapons WHERE sub='$wid' ORDER BY name";
		    $result2 = $database->openShipsWithReturn($qry2);
    		while (list($wid, $wname) = mysql_fetch_array($result2))
		    	echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"index.php?option=ifs&amp;task=rd&amp;action=shipdb&amp;detail={$wid}\">{$wname}</a><br />\n";
	    }
    	echo "<br /><a href=\"index.php?option=ifs&amp;task=rd&amp;action=shipdb&amp;detail=add\">Add a Weapon</a><br />\n";
    	echo "<a href=\"index.php?option=ifs&amp;task=rd&amp;action=shipdb&amp;type=w\">Edit Weapon types</a><br />\n";
	    echo "<br /><br />\n\n";
	}

}
?>
