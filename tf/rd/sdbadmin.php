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

	echo '<h1 class="text-center">' . $fleetname . ' Ship Database Admin</h1>';

    // Editing categories
	if ($cat)
    {
    	if ($cat == "edit")
        {
            if ($ctype == "w")
            	echo '<h2>Edit Weapon categories</h2>';
            elseif ($ctype == "y")
            	echo '<h2>Edit Support Craft categories</h2>';
            elseif ($ctype == "n")
            	echo '<h2>Edit Starship categories</h2>';
			
        	$qry = "SELECT c.id, c.name, c.type, c.description
            		FROM {$sdb}category c, {$sdb}types t
                    WHERE c.type=t.id AND t.support='{$ctype}'
                    ORDER BY c.type, c.name";
            $result = $database->openShipsWithReturn($qry);
            while (list($cid, $cname, $ctype2, $cdesc) = mysql_fetch_array($result))
            {
            	?>
				<form class="form-horizontal" action="index.php?option=ifs&amp;task=rd&amp;action=shipdb&amp;cat=save" method="post">
                    <input type="hidden" name="cid" value="<?php echo $cid?>">
                    <div class="form-group">
                        <label for="cname<?php echo $cid ?>" class="col-sm-2 control-label">Name:</label>
                        <div class="col-sm-10 col-md-8 col-lg-6">
                            <input type="text" class="form-control" name="cname" id="cname<?php echo $cid ?>" value="<?php echo $cname ?>" size="30">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="ctype<?php echo $cid ?>" class="col-sm-2 control-label">Type:</label>
                        <div class="col-sm-10 col-md-8 col-lg-6">
                            <select class="form-control" name="ctype" id="ctype<?php echo $cid ?>">
                                <?php
                                $qry2 = "SELECT id, type FROM {$sdb}types WHERE support='{$ctype}' ORDER BY type";
                                $result2 = $database->openShipsWithReturn($qry2);
                                while(list($tid, $type) = mysql_fetch_array($result2))
                                {
                                    echo '<option value="' . $tid . '"';
                                    if ($tid == $ctype2)
                                        echo ' selected="selected"';
                                    echo '>' . $type . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="cdesc<?php echo $cid ?>" class="col-sm-2 control-label">Description:</label>
                        <div class="col-sm-10 col-md-8 col-lg-6">
                            <textarea class="form-control" name="cdesc" id="cdesc<?php echo $cid ?>" cols="60" rows="5"><?php echo $cdesc ?></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-6 col-sm-1 col-sm-offset-2">
                            <input class="btn btn-success" type="submit" value="Update"> &nbsp;
                        </div>
                </form>
				<form action="index.php?option=ifs&amp;task=rd&amp;action=shipdb&amp;cat=delete" method="post">
                    <input type="hidden" name="cid" value="<?php echo $cid ?>">
                        <div class="col-xs-6 col-sm-1">
                        <input class="btn btn-danger" type="submit" value="Delete">
                        </div>
                    </div>
                </form>
                <hr />
                <?php
            }
            $qry = "SELECT id, type FROM {$sdb}types WHERE support='{$ctype}' ORDER BY type";
            $result = $database->openShipsWithReturn($qry);
            if ( mysql_num_rows($result) )
            {
	            ?>
                <h3>Add a category</h3>
	            <form class="form-horizontal" action="index.php?option=ifs&amp;task=rd&amp;action=shipdb&amp;cat=add" method="post">
                    <div class="form-group">
                        <label for="cname" class="col-sm-2 control-label">Name:</label>
                        <div class="col-sm-10 col-md-8 col-lg-6">
                            <input type="text" class="form-control" name="cname" id="cname" size="30">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="ctype" class="col-sm-2 control-label">Type:</label>
                        <div class="col-sm-10 col-md-8 col-lg-6">
                            <select class="form-control" name="ctype" id="ctype">
								<?php
                                while(list($tid, $type) = mysql_fetch_array($result))
                                    echo '<option value="' . $tid . '">' . $type . '</option>';
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="cdesc" class="col-sm-2 control-label">Description:</label>
                        <div class="col-sm-10 col-md-8 col-lg-6">
                            <textarea class="form-control" name="cdesc" id="cdesc" cols="60" rows="5"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
	            		<div class="col-sm-10 col-sm-offset-2">
                    		<input class="btn btn-default" type="submit" value="Add">
                		</div>
                    </div>
                </form>
	            <?php
            }
            else
            	echo '<h3 class="text-warning">You must create a type first (ie Starship or Starbase).</h3>';
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
            	echo '<h3 class="text-warning">Cannot delete category - you must make sure that no ships are classified under this category first!</h3>';
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
            	echo '<h2>Edit Weapon types</h2>';
            elseif ($type == "y")
            	echo '<h2>Edit Support Craft types</h2>';
            elseif ($type == "n")
            	echo '<h2>Edit Starship types</h2>';

        	$qry = "SELECT id, type FROM {$sdb}types WHERE support='{$type}' ORDER BY type";
            $result = $database->openShipsWithReturn($qry);
            while (list($tid, $tname) = mysql_fetch_array($result))
            {
            	?>
				<form class="form-horizontal" action="index.php?option=ifs&amp;task=rd&amp;action=shipdb&amp;type=<?php echo $type?>&amp;typeaction=save" method="post">
                	<input type="hidden" name="tid" value="<?php echo $tid?>">
                	<div class="form-group">
                		<label for="tname<?php echo $tid ?>" class="col-sm-2 control-label">Name:</label>
                        <div class="col-sm-10 col-md-8 col-lg-6">
                        	<input type="text" class="form-control" name="tname" id="tname<?php echo $tid ?>" value="<?php echo $tname?>" size="30">
                        </div>
                    </div>
                    <div class="row">
                    	<div class="col-xs-6 col-sm-1 col-sm-offset-2">
                			<input class="btn btn-success" type="submit" value="Update"> &nbsp;
                    	</div>
                </form>
	            <form action="index.php?option=ifs&amp;task=rd&amp;action=shipdb&amp;type=<?php echo $type?>&amp;typeaction=delete" method="post">
	                <input type="hidden" name="tid" value="<?php echo $tid ?>">
                    	<div class="col-xs-6 col-sm-1">
                			<input class="btn btn-danger" type="submit" value="Delete">
                    	</div>
                    </div>
                </form>
                <hr />
                <?php
            }
            ?>
			<form class="form-horizontal" action="index.php?option=ifs&amp;task=rd&amp;action=shipdb&amp;type=<?php echo $type ?>&amp;typeaction=add" method="post">
            	<div class="form-group">
            		<label for="tname" class="col-sm-2 control-label">Name:</label>
                    <div class="col-sm-10 col-md-8 col-lg-6">
                    	<input type="text" class="form-control" name="tname" id="tname" size="30">
            		</div>
                </div>
                <div class="form-group">
            		<div class="col-sm-10 col-sm-offset-2">
            			<input class="btn btn-default" type="submit" value="Add">
            		</div>
            	</div>
            </form>
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
            	echo '<h3 class="text-warning">Cannot delete type - you must make sure that nothing is classified under this type first!</h3>';
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
           		echo '<h3 class="text-warning">Cannot delete weapon - you must make sure that it is not in use first!</h3>';
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
		if ($detail == 'add')
			echo '<h2>Add system details</h2>';
		else
			echo '<h2>Update system details</h2>';

		$qry = "SELECT name, description, image, sub, type FROM {$sdb}weapons WHERE id='$detail'";
   		$result = $database->openShipsWithReturn($qry);
	    list ($dname, $ddesc, $dimage, $sub, $type) = mysql_fetch_array($result);
        $qry = "SELECT id, type FROM {$sdb}types WHERE support='w'";
        $result = $database->openShipsWithReturn($qry);
        if ( mysql_num_rows($result) )
        {
	        ?>
	        <form class="form-horizontal" action="index.php?option=ifs&amp;task=rd&amp;action=shipdb" method="post">
	        	<input type="hidden" name="save" value="yes">
	        	<input type="hidden" name="detail" value="<?php echo $detail ?>">
                <div class="form-group">
	        		<label for="dname" class="col-sm-2 control-label">Name:</label>
                    <div class="col-sm-10 col-md-8 col-lg-6">
                    	<input type="text" class="form-control" name="dname" id="dname" value="<?php echo $dname ?>" size="30">
                    </div>
	        	</div>
                <div class="form-group">
	        		<label for="dimage" class="col-sm-2 control-label">Image URL:</label>
                    <div class="col-sm-10 col-md-8 col-lg-6">
                    	<input type="text" class="form-control" name="dimage" id="dimage" value="<?php echo $dimage ?>" size="30" />
                    </div>
	        	</div>
                <div class="form-group">
	        		<label for="ddesc" class="col-sm-2 control-label">Description:</label>
                    <div class="col-sm-10 col-md-8 col-lg-6">
                    	<textarea class="form-control" name="ddesc" id="ddesc" rows="5" cols="60"><?php echo $ddesc ?></textarea>
                    </div>
	        	</div>
                <div class="form-group">
	        		<label for="sub" class="col-sm-2 control-label">Parent or type:</label>
                    <div class="col-sm-10 col-md-8 col-lg-6">
                    	<select class="form-control" name="sub" id="sub">
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
                        </select>
                    </div>
	        	</div>
	        <?php

			if ($detail == "add")
			{
				?>
                <div class="form-group">
                	<div class="col-sm-10 col-sm-offset-2">
                    	<input class="btn btn-default" type="submit" value="Add">
                    </div>
                </div>
            <?php
			}
			else
			{
				?>
                <div class="row">
                	<div class="col-xs-6 col-sm-1 col-sm-offset-2">
                    	<input class="btn btn-success" type="submit" value="Update"> &nbsp;
                    </div>
                </form>
				<form action="index.php?option=ifs&amp;task=rd&amp;action=shipdb" method="post">
					<input type="hidden" name="save" value="delete">
					<input type="hidden" name="detail" value="<?php echo $detail ?>">
                    <div class="col-xs-6 col-sm-1">
						<input class="btn btn-danger" type="submit" value="Delete">
                    </div>
				</form>
                </div>
			<?php
			}
        }
        else
        	echo '<h3 class="text-warning">You must create a system type first (ie phaser or torpedo)</h3>';
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
		
		if ($sclass == 'add')
			echo '<h2>Add Ship Class</h2>';
		else
			echo '<h2>Update Ship Class</h2>';
			
    	$qry9 = "SELECT id, type
        		FROM {$sdb}types
                WHERE support='$support'
				ORDER BY type";
	    $result9 = $database->openShipsWithReturn($qry9);
			
    	$qry = "SELECT c.id, c.name, t.type
        		FROM {$sdb}category c, {$sdb}types t
                WHERE c.type=t.id AND t.support='$support'
				ORDER BY t.type, c.name";
	    $result = $database->openShipsWithReturn($qry);
        if ( mysql_num_rows($result) )
        {
	        ?>
	
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                	<p><strong>Skip to:</strong></p>
                    <div class="btn-group" role="group" aria-label="skip to">
                        <a role="button" class="btn btn-default btn-sm" href="#personnel">Personnel</a>
                        <a role="button" class="btn btn-default btn-sm" href="#speed">Speed</a>
                        <a role="button" class="btn btn-default btn-sm" href="#dimensions">Dimensions</a>
	        			<?php if ($support == "n") echo '<a role="button" class="btn btn-default btn-sm" href="#aux">Auxiliary Craft</a>'; ?>
                        <a role="button" class="btn btn-default btn-sm" href="#armament">Armament</a>
                        <a role="button" class="btn btn-default btn-sm" href="#desc">Description</a>
                        <a role="button" class="btn btn-default btn-sm" href="#deck-listing">Deck Listing</a>
                    </div>
                </div>
            </div>
			<br /><br />
	        <form class="form-horizontal" action="index.php?option=ifs&amp;task=rd&amp;action=shipdb" method="post">
	        	<input type="hidden" name="save" value="yes">
	        	<input type="hidden" name="sclass" value="<?php echo $sclass ?>">
                <div class="form-group">
	        		<label for="cname" class="col-sm-2 control-label">Class name:</label>
                    <div class="col-sm-10 col-md-8 col-lg-6">
                    	<input type="text" class="form-control" name="cname" id="cname" value="<?php echo $cname ?>" size="30">
                    </div>
                </div>
                <div class="form-group">
                	<label for="category" class="col-sm-2 control-label">Category:</label>
					<div class="col-sm-10 col-md-8 col-lg-6">
                        <select class="form-control" name="category" id="category">
                        	<?php
                        	while(list($tid, $type) = mysql_fetch_array($result9))
                        	{
								echo '<optgroup label="' . $type . '">';
								
								$qry8 = "SELECT id, name
										FROM {$sdb}category
										WHERE type={$tid}
										ORDER BY name";
								$result8 = $database->openShipsWithReturn($qry8);
								
								while(list($catid,$catname) = mysql_fetch_array($result8))
								{
									echo '<option value="' . $catid . '"';
									if ($catname == $category)
										echo ' selected="selected"';
									echo '>' . $catname . '</option>';
								}
								echo '</optgroup>';
                        	}
							?>
                        </select>
                    </div>
                </div>
                <div class="checkbox">
                	<label class="col-sm-10 col-sm-offset-2">
                    	<input type="checkbox" name="active"<?php if ($active =="1") echo ' checked="checked"' ?>>
                        Active
                    </label>
                </div>
                <div class="form-group">
	        		<label for="image" class="col-sm-2 control-label">Image:</label>
                    <div class="col-sm-10 col-md-8 col-lg-6">
                    	<input type="text" class="form-control" name="image" id="image" value="<?php echo $image ?>" size="30">
                    </div>
                </div>
				<div class="form-group">
	        		<label for="duration" class="col-sm-2 control-label">Expected Duration:</label>
                    <div class="col-sm-2">
                    	<div class="input-group">
                        	<input type="text" class="form-control text-right" name="duration" id="duration" value="<?php echo $duration ?>" size="4" maxlength="4">
                            <span class="input-group-addon">years</span>
                        </div>
                    </div>
                </div>
				<div class="form-group">
	        		<label for="resupply" class="col-sm-2 control-label">Time Between Resupply:</label>
                    <div class="col-sm-2">
                    	<div class="input-group">
                        	<input type="text" class="form-control text-right" name="resupply" id="resupply" value="<?php echo $resupply ?>" size="4" maxlength="4">
                            <span class="input-group-addon">years</span>
                        </div>
                    </div>
                </div>
				<div class="form-group">
	        		<label for="refit" class="col-sm-2 control-label">Time Between Refit:</label>
                    <div class="col-sm-2">
                    	<div class="input-group">
                        	<input type="text" class="form-control text-right" name="refit" id="refit" value="<?php echo $refit ?>" size="4" maxlength="4">
                            <span class="input-group-addon">years</span>
                        </div>
                    </div>
                </div>

	        	<a name="personnel"></a>
                <h3 class="heading">Personnel</h3>
                <div class="form-group">
	        		<label for="officers" class="col-sm-2 control-label">Officers:</label>
                    <div class="col-sm-2">
                    	<input type="text" class="form-control" name="officers" id="officers" value="<?php echo $officers ?>" size="6" maxlength="6">
                    </div>
                </div>
                <div class="form-group">
	        		<label for="enlisted" class="col-sm-2 control-label">Enlisted Crew:</label>
                    <div class="col-sm-2">
                    	<input type="text" class="form-control" name="enlisted" id="enlisted" value="<?php echo $enlisted ?>" size="6" maxlength="6">
                    </div>
                </div>
                <div class="form-group">
	        		<label for="marines" class="col-sm-2 control-label">Marines:</label>
                    <div class="col-sm-2">
                    	<input type="text" class="form-control" name="marines" id="marines" value="<?php echo $marines ?>" size="6" maxlength="6">
                    </div>
                </div>
                <div class="form-group">
	        		<label for="passengers" class="col-sm-2 control-label">Passengers:</label>
                    <div class="col-sm-2">
                    	<input type="text" class="form-control" name="passengers" id="passengers" value="<?php echo $passengers ?>" size="6" maxlength="6">
                    </div>
                </div>
                <div class="form-group">
	        		<label for="evac" class="col-sm-2 control-label">Maximum (Evacuation) Capacity:</label>
                    <div class="col-sm-2">
                    	<input type="text" class="form-control" name="evac" id="evac" value="<?php echo $evac ?>" size="6" maxlength="6">
                    </div>
                </div>
                <span class="help-block">If there is no difference between officers &amp; enlisted (ie fighters, shuttles), enter '0' for Officers and the crew number for Enlisted.</span>
                <span class="help-block">The Marines number will also not be displayed on such craft.</span>
                <span class="help-block">If Passengers or Max Capacity are set to '0', they will not be displayed.</span>


	        	<a name="speed"></a>
                <h3 class="heading">Speed</h3>
                <div  class="form-group">
	        		<label for="cruisevel" class="col-sm-2 control-label">Cruising Velocity:</label>
                    <div class="col-sm-2">
                    	<div class="input-group">
                        	<span class="input-group-addon">Warp</span>
                            <input type="text" class="form-control" name="cruisevel" id="cruisevel" value="<?php echo $cruisevel ?>" size="5" maxlength="5">
                        </div>
                    </div>
                </div>
                <div class="form-group">
	        		<label for="maxvel" class="col-sm-2 control-label">Maximum Velocity:</label>
                    <div class="col-sm-2">
                    	<div class="input-group">
                        	<span class="input-group-addon">Warp</span>
                            <input type="text" class="form-control" name="maxvel" id="maxvel" value="<?php echo $maxvel ?>" size="5" maxlength="5">
                        </div>
                    </div>
                </div>
                <div class="form-group">
	        		<label for="emervel" class="col-sm-2 control-label">Emergency Velocity:</label>
                    <div class="col-sm-2">
                    	<div class="input-group">
                        	<span class="input-group-addon">Warp</span>
                            <input type="text" class="form-control" name="emervel" id="emervel" value="<?php echo $emervel ?>" size="5" maxlength="5">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                	<label for="eveltime" class="col-sm-2 control-label sr-only">Maximum Emergency Velocity Period:</label>
                    <div class="col-sm-3 form-inline">
                    	<div class="input-group input-group-sm">
                        	<span class="input-group-addon">(for</span>
                            <input type="text" class="form-control text-center" name="eveltime" id="eveltime" value="<?php echo $eveltime ?>" size="3" maxlength="3">
                            <span class="input-group-addon">hours)</span>
                        </div>
                    </div>
                </div>

	        	<a name="dimensions"></a>
                <h3 class="heading">Dimensions</h3>
                <div class="form-group">
                	<label for="length" class="col-sm-2 control-label">Length:</label>
                    <div class="col-sm-3">
                    	<div class="input-group">
                        	<input type="text" class="form-control text-right" name="length" id="length" value="<?php echo $length ?>" size="10" maxlength="10">
                            <span class="input-group-addon">metres</span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                	<label for="width" class="col-sm-2 control-label">Width:</label>
                    <div class="col-sm-3">
                    	<div class="input-group">
                        	<input type="text" class="form-control text-right" name="width" id="width" value="<?php echo $width ?>" size="10" maxlength="10">
                            <span class="input-group-addon">metres</span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                	<label for="height" class="col-sm-2 control-label">Height:</label>
                    <div class="col-sm-3">
                    	<div class="input-group">
                        	<input type="text" class="form-control text-right" name="height" id="height" value="<?php echo $height ?>" size="10" maxlength="10">
                            <span class="input-group-addon">metres</span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                	<label for="decks" class="col-sm-2 control-label">Decks:</label>
                    <div class="col-sm-2">
                        <input type="text" class="form-control" name="decks" id="decks" value="<?php echo $decks ?>" size="6" maxlength="6">
                    </div>
                </div>
				<?php
    
                if ($support == "n")
                {
    				?>
                    <a name="aux"></a>
                    <h3 class="heading">Auxiliary Craft</h3>
                    <div class="form-group">
                    	<label for="shuttlebays" class="col-sm-2 control-label">Shuttlebays:</label>
                        <div class="col-sm-2">
                        	<input type="text" class="form-control" name="shuttlebays" id="shuttlebays" value="<?php echo $shuttlebays ?>" size="4" maxlength="4">
                        </div>
                    </div>
                    <?php
                    $qry = "SELECT c.id, c.name, d.name, t.type
                            FROM {$sdb}classes c, {$sdb}category d, {$sdb}types t
                            WHERE c.category=d.id AND d.type=t.id AND t.support='y' AND d.type=t.id
                            ORDER BY t.type, c.name";
                    $result = $database->openShipsWithReturn($qry);
                    while (list($cid, $cname, $catname, $type) = mysql_fetch_array($result))
                    {
                        if ($oldtype != $type)
                        {
							?>
                            <div class="form-group">
                            	<label class="col-sm-12"><h5><?php echo $type ?>s:</h5></label>
                            </div>
                            <?php
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
						?>
                        <div class="form-group">
                        	<label for="auxcr[<?php echo $cid ?>]" class="col-sm-3 control-label"><?php echo $cname . ' ' . $catname ?>:</label>
                            <div class="col-sm-1">
                            	<input type="text" class="form-control" name="auxcr[<?php echo $cid ?>]" value="<?php echo $cnum ?>" size="3">
                            </div>
                        </div>
                        <?php
                    }
                }
                ?>


	        	<a name="armament"></a>
                <h3 class="heading">Armament</h3>
				<?php
                $qry = "SELECT w.id, w.name, t.type
                        FROM {$sdb}weapons w, {$sdb}types t
                        WHERE w.sub='0' AND w.type=t.id
                        ORDER BY t.type, w.name";
                $result = $database->openShipsWithReturn($qry);
                while (list($wid, $wname, $type) = mysql_fetch_array($result))
                {
                    if ($oldtype != $type)
                    {
						?>
						<div class="form-group">
							<label class="col-sm-12"><h5><?php echo $type ?>s:</h5></label>
						</div>
						<?php
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
					?>
					<div class="form-group">
						<label for="weap[<?php echo $wid ?>]" class="col-sm-3 control-label"><?php echo $wname ?>:</label>
						<div class="col-sm-1">
							<input type="text" class="form-control" name="weap[<?php echo $wid ?>]" value="<?php echo $wnum ?>" size="3">
						</div>
					</div>
					<?php
    
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
						?>
						<div class="form-group">
							<label for="weap[<?php echo $wid ?>]" class="col-xs-11 col-sm-3 col-xs-offset-1 control-label"><?php echo $wname ?>:</label>
							<div class="col-xs-11 col-xs-offset-1 col-sm-1 col-sm-offset-0">
								<input type="text" class="form-control" name="weap[<?php echo $wid ?>]" value="<?php echo $wnum ?>" size="3">
							</div>
						</div>
						<?php
                    }
                }
                ?>
				<div class="form-vertical">
                    <div class="form-group">
                        <label for="notes"><h4 class="heading">Notes</h4></label>
                        <textarea class="form-control" name="notes" id="notes" cols="40" rows="3"><?php echo $notes ?></textarea>
                    </div>
                </div>

	        	<a name="desc"></a>
				<div class="form-vertical">
                    <div class="form-group">
                        <label for="desc"><h3 class="heading">Description</h3></label>
                        <textarea class="form-control" name="desc" id="desc" cols="60" rows="5"><?php echo $desc ?></textarea>
                    </div>
                </div>

	        	<a name="deck-listing"></a>
                <h3 class="heading">Deck Listing</h3>
                <div class="form-group">
                	<label class="col-sm-1 text-right"><h5>Deck</h5></label>
                    <label class="col-sm-11"><h5>Description</h5></label>
                </div>
	            <?php
				$qry = "SELECT deck, descrip FROM {$sdb}decks WHERE ship='$sclass'";
				$result = $database->openShipsWithReturn($qry);

	            while (list($decknum, $deckdesc) = mysql_fetch_array($result) )
	            	$decklist[$decknum] = $deckdesc;

	            for ($i = 1; $i <= $decks; $i++)
                {
				?>
                	<div class="form-group">
                    	<label for="deck_desc[<?php echo $i ?>]" class="col-sm-1 control-label">Deck <?php echo $i ?>:</label>
                    	<div class="col-sm-11">
                        	<textarea class="form-control" size="70" name="deck_desc[<?php echo $i ?>]" rows="2"><?php echo $decklist[$i] ?></textarea>
                        </div>
                    </div>
                <?php
				}
                ?>
	        <?php

			if ($sclass == "add")
			{
				?>
                <div class="form-group">
                	<div class="col-sm-10 col-sm-offset-1">
                    	<input class="btn btn-default" type="submit" value="Add">
                    </div>
                </div>
            <?php
			}
			else
			{
				?>
                <div class="row">
                	<div class="col-xs-6 col-sm-1 col-sm-offset-1">
                    	<input class="btn btn-success" type="submit" value="Update"> &nbsp;
                    </div>
                </form>
				<form action="index.php?option=ifs&amp;task=rd&amp;action=shipdb" method="post">
					<input type="hidden" name="save" value="delete">
					<input type="hidden" name="detail" value="<?php echo $detail ?>">
                    <div class="col-xs-6 col-sm-1">
						<input class="btn btn-danger" type="submit" value="Delete">
                    </div>
				</form>
                </div>
			<?php
			}
        }
        else
        	echo '<h3 class="text-warning">You must create categories first! (ie destroyer, cruiser...)</h3>';
	}
    else
    {
		echo '<h2>Starships</h2>';
		echo '<div class="list-group">';
		$qry = "SELECT c.id, c.name, t.type
        		FROM {$sdb}classes c, {$sdb}category d, {$sdb}types t
                WHERE c.category=d.id AND d.type=t.id AND t.support='n'
                ORDER BY t.type, c.name";
		$result = $database->openShipsWithReturn($qry);
	    while(list($cid, $cname, $type) = mysql_fetch_array($result))
		{
        	if ($oldtype != $type)
            {
            	echo '<h5 class="list-group-item">' . $type . 's</h5>';
                $oldtype = $type;
            }
	    	echo '<a class="list-group-item" href="index.php?option=ifs&amp;task=rd&amp;action=shipdb&amp;sclass=' . $cid . '">' . $cname . '-class ' . $type . '</a>';
		}
		echo '</div><div class="btn-group-vertical" role="group" aria-label="Starship actions">';
    	echo '<a role="button" class="btn btn-default btn-sm" href="index.php?option=ifs&amp;task=rd&amp;action=shipdb&amp;sclass=add&amp;sup=n">Add a Starship</a>';
    	echo '<a role="button" class="btn btn-default btn-sm" href="index.php?option=ifs&amp;task=rd&amp;action=shipdb&amp;type=n">Edit Starship types</a>';
    	echo '<a role="button" class="btn btn-default btn-sm" href="index.php?option=ifs&amp;task=rd&amp;action=shipdb&amp;cat=edit&amp;ctype=n">Edit Starship Categories</a>';
	    echo '</div>';

		echo '<h2>Support Craft</h2>';
		echo '<div class="list-group">';
		$qry = "SELECT c.id, c.name, t.type
        		FROM {$sdb}classes c, {$sdb}category d, {$sdb}types t
                WHERE c.category=d.id AND d.type=t.id AND t.support='y'
                ORDER BY t.type, c.name";
		$result = $database->openShipsWithReturn($qry);
	    while(list($cid, $cname, $type) = mysql_fetch_array($result))
        {
        	if ($oldtype != $type)
            {
            	echo '<h5 class="list-group-item">' . $type . 's</h5>';
                $oldtype = $type;
            }
	    	echo '<a class="list-group-item" href="index.php?option=ifs&amp;task=rd&amp;action=shipdb&amp;sclass=' . $cid . '">' . $cname . ' class</a>';
	  	}
		echo '</div><div class="btn-group-vertical" role="group" aria-label="Support craft actions">';
    	echo '<a role="button" class="btn btn-default btn-sm" href="index.php?option=ifs&amp;task=rd&amp;action=shipdb&amp;sclass=add&amp;sup=y">Add a Support Craft</a>';
    	echo '<a role="button" class="btn btn-default btn-sm" href="index.php?option=ifs&amp;task=rd&amp;action=shipdb&amp;type=y">Edit Support Craft types</a>';
    	echo '<a role="button" class="btn btn-default btn-sm" href="index.php?option=ifs&amp;task=rd&amp;action=shipdb&amp;cat=edit&amp;ctype=y">Edit Support Craft Categories</a>';
	    echo '</div>';

		echo '<h2>Systems &amp; Weapons</h2>';
		echo '<div class="list-group">';
		$qry = "SELECT w.id, w.name, t.type
        		FROM {$sdb}weapons w, {$sdb}types t
                WHERE w.sub='0' AND w.type=t.id
                ORDER BY t.type, w.name";
	    $result = $database->openShipsWithReturn($qry);
	    while (list($wid, $wname, $type) = mysql_fetch_array($result))
        {
        	if ($oldtype != $type)
            {
            	echo '<h5 class="list-group-item">' . $type . 's</h5>';
                $oldtype = $type;
            }
	    	echo '<a class="list-group-item" href="index.php?option=ifs&amp;task=rd&amp;action=shipdb&amp;detail=' . $wid . '">' . $wname . '</a>';

			$qry2 = "SELECT id, name FROM {$sdb}weapons WHERE sub='$wid' ORDER BY name";
		    $result2 = $database->openShipsWithReturn($qry2);
    		while (list($wid, $wname) = mysql_fetch_array($result2))
		    	echo '<a class="list-group-item" href="index.php?option=ifs&amp;task=rd&amp;action=shipdb&amp;detail=' . $wid . '">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $wname . '</a>';
	    }
		echo '</div><div class="btn-group-vertical" role="group" aria-label="System actions">';
    	echo '<a role="button" class="btn btn-default btn-sm" href="index.php?option=ifs&amp;task=rd&amp;action=shipdb&amp;detail=add">Add a System</a>';
    	echo '<a role="button" class="btn btn-default btn-sm" href="index.php?option=ifs&amp;task=rd&amp;action=shipdb&amp;type=w">Edit System types</a>';
	    echo '</div>';
	}

}
?>
