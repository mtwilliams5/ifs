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
  * Copyright (C) 2003-2004 Frank Anon for Obsidian Fleet RPG
  *
  * Date:	5/01/04
  * Comments: Ship Database
 ***/

if (!defined("IFS"))
{
	include("configuration.php");
	include("includes/header.php");
}

echo '<h1>Ship Database</h1>';

// Return results from searching technology
if ($techsearch)
{
	echo '<h2>Results for Technology Search</h2>';
    echo '<h5 class="text-muted text-uppercase">' . $techsearch . '</h5>';
   	$qry = "SELECT id, name
    		FROM {$sdb}weapons
            WHERE name like '%{$techsearch}%'
            	OR description like '%{$techsearch}%'
            GROUP BY name";
	$result = $database->openShipsWithReturn($qry);
    if (!mysql_num_rows($result))
    	echo '<h4 class="text-warning">No results.</h4>';

	echo '<ul>';
    while (list($did, $dname) = mysql_fetch_array($result))
    	if (!defined("IFS"))
	    	echo '<li><a href="shipdb.php?detail=' . $did . '">' . $dname . '</a></li>';
        else
	    	echo '<li><a href="index.php?option=shipdb&amp;detail=' . $did . '">' . $dname . '</a></li>';
	
	echo '</ul>';
}

// Details about a specific weapon or feature/addon
elseif ($detail)
{
	$qry = "SELECT w.name, w.description, w.sub, t.type, w.image
    		FROM {$sdb}weapons w, {$sdb}types t
            WHERE w.id='$detail' AND w.type=t.id
            GROUP BY w.name";
   	$result = $database->openShipsWithReturn($qry);
   	list ($dname, $ddesc, $sub, $type, $dimage) = mysql_fetch_array($result);

    echo '<h3>' . $dname . '</h3>';
    echo '<h5>Type: ' . $type . '</h5>';
    if ($sub != "0")
    {
    	$qry = "SELECT name FROM {$sdb}weapons WHERE id='$sub'";
        $result = $database->openShipsWithReturn($qry);
        list ($subname) = mysql_fetch_array($result);
        echo '<h6 class="text-muted">Associated With: ';
    	if (!defined("IFS"))
	    	echo '<a href="shipdb.php?detail=' . $sub . '">' . $subname . '</a><br />';
        else
	    	echo '<a href="index.php?option=shipdbdetail=' . $sub . '">' . $subname . '</a>';
		echo '</h6>';
    }

   	if ($dimage)
    	echo '<div id="image"><img class="img-rounded" src="images/shipdb/' . $dimage . '" alt="' . $dname . '"></div>';

	if ($ddesc)
		echo '<p>' . $ddesc . '</p>';
	else
		echo '<p>No Description Available.</p>';

	if ($pop != "y")
    {
		echo '<h6 class="text-muted">Found on:</h6>';
		echo '<ul>';
    	$qry = "SELECT c.id, c.name, d.name
        		FROM {$sdb}classes c, {$sdb}equip e, {$sdb}category d, {$sdb}weapons w
                WHERE w.name='$dname' AND e.equipment=w.id AND e.type='w'
                	AND e.ship=c.id AND c.category=d.id AND e.number>'0'";
		$result = $database->openShipsWithReturn($qry);
	    while (list($cid, $cname, $catname,) = mysql_fetch_array($result)) {
			if (!defined("IFS"))
				echo '<li><a href="shipdb.php?sclass=' . $cid . '">' . $cname . ' Class ' . $catname . '</a></li>';
			else
    			echo '<li><a href="index.php?option=shipdb&sclass=' . $cid . '">' . $cname . " Class " . $catname . '</a></li>';
		}
	    echo '</ul>';
    }

}

// Details about a specific class of ship
elseif ($sclass)
{
    $qry = "SELECT c.name, duration, resupply, refit, d.name, t.type, t.support, cruisevel,
	    		maxvel, emervel, eveltime, officers, enlisted, passengers, marines,
	            evac, shuttlebays, length, width, height, decks, notes, c.description, image
            FROM {$sdb}classes c, {$sdb}category d, {$sdb}types t
            WHERE c.id='$sclass' AND c.category=d.id AND d.type=t.id";
    $result = $database->openShipsWithReturn($qry);
    list ($cname, $duration, $resupply, $refit, $category, $type, $support, $cruisevel, $maxvel,
    	  $emervel, $eveltime,  $officers, $enlisted, $passengers, $marines, $evac,
          $shuttlebays, $length, $width, $height, $decks, $notes, $desc, $image)
          = mysql_fetch_array($result);

    echo '<h2>' . $cname . ' Class ' . $type . '</h2>';
    echo '<h4>Category: ' . $category . '</h4>';
    if ($image)
	    echo '<div id="image"><img class="img-rounded" src="images/shipdb/' . $image . '" alt="' . $cname . '"></div><br />';
	
	?>
	<ul class="list-unstyled specs-detail">
    	<li><strong>Expected Duration:</strong> <?php echo $duration ?> years</li>
        <?php if ($type != "Starbase") echo '<li><strong>Time Between Resupply:</strong> ' . $resupply . ' years</li>'; ?>
        <li><strong>Time Between Refit:</strong> <?php echo $refit ?> years</li>
    </ul>

    <h6><strong class="heading">Personnel</strong></h6>
	<ul class="list-unstyled specs-detail">
	<?php
    if ($officers != "0")
    {
	?>
	    <li><strong>Officers:</strong> <?php echo $officers ?></li>
    	<li><strong>Enlisted Crew:</strong> <?php echo $enlisted ?></li>
        <li><strong>Marines:</strong> <?php echo $marines ?></li>
    <?php 
    }
    else
		echo '<li><strong>Crew:</strong> ' . $enlisted . '</li>';

    if ($passengers != "0")
    	echo '<li><strong>Passengers:</strong> ' . $passengers . '</li>';

	if ($type != "Starbase")
    {
	    if ($evac != "0")
	        echo '<li><strong>Maximum (Evacuation) Capacity:</strong> ' . $evac . '</li>';
	?>
    </ul>
	<h6><strong class="heading">Speed</strong></h6>
	<ul class="list-unstyled specs-detail">
	    <li><strong>Cruising Velocity:</strong> Warp <?php echo $cruisevel ?></li>
	    <li><strong>Maximum Velocity:</strong> Warp <?php echo $maxvel ?></li>
	    <li><strong>Emergency Velocity:</strong> Warp <?php echo $emervel ?> (for <?php echo $eveltime ?> hours)</li>
    <?php
	}
    else
		echo '<li><strong>Starship Docking Capacity:</strong> ' . $evac . '</li>';
	?>
	</ul>
	<h6><strong class="heading">Dimensions</strong></h6>
	<ul class="list-unstyled specs-detail">
    <?php
	if ($type != "Starbase")
    {
	?>
	    <li><strong>Length:</strong> <?php echo $length ?> metres</li>
	    <li><strong>Width:</strong> <?php echo $width ?> metres</li>
	    <li><strong>Height:</strong> <?php echo $height ?> metres</li>
    <?php
	}
    else
    {
	?>
	    <li><strong>Diameter:</strong> <?php echo $length ?> metres</li>
	    <li><strong>Main Height:</strong> <?php echo $width ?> metres</li>
	    <li><strong>Overall Height:</strong> <?php echo $height ?> metres</li>
    <?php
	}
	?>
        <li><strong>Decks:</strong> <?php echo $decks ?></li>
    </ul>
	<?php
	if ($support == "n")
    {
		$qry = "SELECT e.number, c.id, c.name, d.name, t.type
    			FROM {$sdb}equip e, {$sdb}classes c, {$sdb}category d, {$sdb}types t
    			WHERE e.ship='$sclass' AND e.type='c' AND e.equipment=c.id
                	AND c.category=d.id AND d.type=t.id
            	ORDER BY t.type, c.name";
	    $result = $database->openShipsWithReturn($qry);
	?>
		<h6><strong class="heading">Auxiliary Craft</strong></h6>
		<ul class="list-unstyled specs-detail">
        	<li><strong>Shuttlebays:</strong> <?php echo $shuttlebays ?></li>
        <?php
	    while (list($enum, $cid, $cname, $catname, $type) = mysql_fetch_array($result))
        {
        	if ($oldtype != $type)
            {
				if ($oldtype!='') echo '</ul></li></div>';
				echo '<div class="row specs-detail-sub-row">';
            	echo '<li><h6><strong>' . $type . 's</strong></h6>';
				echo '<ul class="specs-detail-sub">';
                $oldtype = $type;
            }
			if (!defined("IFS"))
	    		echo '<a href="shipdb.php?sclass=' . $cid . '">' . $cname . ' ' . $catname . ': <span class="badge">' . $enum . '</span></a>';
			else
	    		echo '<a href="index.php?option=shipdb&amp;sclass=' . $cid . '">' . $cname . ' ' . $catname . ': <span class="badge">' . $enum . '</span></a>';
	    }
	    echo '</ul></li></div></ul>';
    }
	
	$qry = "SELECT e.number, w.id, w.name, t.type
    		FROM {$sdb}equip e, {$sdb}weapons w, {$sdb}types t
    		WHERE e.ship='$sclass' AND e.type='w' AND e.equipment=w.id
            	AND w.sub='0' AND w.type=t.id
            ORDER BY t.type, w.name";
    $result = $database->openShipsWithReturn($qry);
	?>
	<h6><strong class="heading">Armament</strong></h6>
	<ul class="list-unstyled specs-detail">
    <?php
    if (!mysql_num_rows($result))
    	echo '<li><h6 class="text-info">None</h6></li>';

    while (list($wnum, $wid, $wname, $type) = mysql_fetch_array($result))
    {
    	if ($oldwtype != $type)
        {
			if ($oldwtype!='') echo '</div></li></div>';
			echo '<div class="row specs-detail-sub-row">';
        	echo '<li><h6><strong>' . $type . 's</strong></h6>';
			echo '<ul class="specs-detail-sub">';
            $oldwtype = $type;
        }
    	if ($wnum == "1")
        	if (!defined("IFS")) { ?>
		    	<a href="javascript: var t=window.open('shipdb.php?detail=<?php echo $wid ?>&amp;pop=y','setPop','width=400,height=350,scrollbars=yes')"><?php echo $wname ?></a>
            <?php
			} else { ?>
		    	<a href="javascript: var t=window.open('shipdb.php?detail=<?php echo $wid ?>&amp;pop=y','setPop','width=400,height=350,scrollbars=yes')"><?php echo $wname ?></a>
        	<?php }
        elseif ($wnum != "0")
        	if (!defined("IFS")) { ?>
	    		<a href="javascript: var t=window.open('shipdb.php?detail=<?php echo $wid ?>&amp;pop=y','setPop','width=400,height=350,scrollbars=yes')"><?php echo $wname ?>: <span class="badge"><?php echo $wnum ?></span></a>
            <?php
            } else { ?>
	    		<a href="javascript: var t=window.open('shipdb.php?detail=<?php echo $wid ?>&amp;pop=y','setPop','width=400,height=350,scrollbars=yes')"><?php echo $wname ?>: <span class="badge"><?php echo $wnum ?></span></a>
			<?php }

		$qry2 = "SELECT e.number, w.id, w.name, t.type
    			FROM {$sdb}equip e, {$sdb}weapons w, {$sdb}types t
    			WHERE e.ship='$sclass' AND e.type='w' AND e.equipment=w.id
                	AND w.sub='$wid' AND w.type=t.id
    	        ORDER BY e.sort";
	    $result2 = $database->openShipsWithReturn($qry2);
	    
		while (list($wnum, $wid, $wname) = mysql_fetch_array($result2))
		{
        	if ($wnum == "1")
            	if (!defined("IFS")) { ?>
			    	<a href="javascript: var t=window.open('shipdb.php?detail=<?php echo $wid ?>&amp;pop=y','setPop','width=400,height=350,scrollbars=yes')">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $wname ?></a>
                <?php
                } else { ?>
			    	<a href="javascript: var t=window.open('shipdb.php?detail=<?php echo $wid ?>&amp;pop=y','setPop','width=400,height=350,scrollbars=yes')">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $wname ?></a>
                <?php }
            elseif ($wnum != "0")
            	if (!defined("IFS")) { ?>
			    	<a href="javascript: var t=window.open('shipdb.php?detail=<?php echo $wid ?>&amp;pop=y','setPop','width=400,height=350,scrollbars=yes')">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $wname ?>: <span class="badge"><?php echo $wnum ?></span></a>
                <?php
                } else { ?>
			    	<a href="javascript: var t=window.open('shipdb.php?detail=<?php echo $wid ?>&amp;pop=y','setPop','width=400,height=350,scrollbars=yes')">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $wname ?>: <span class="badge"><?php echo $wnum ?></span></a>
                <?php }
		}
    }
    echo '</ul></li></div></ul>';
	echo '<p class="text-muted"><em>' . $notes . '</em></p>';

	if ($desc)
    {
	    echo '<h6><strong class="heading">Description</strong></h6>';
	    echo '<p>' . $desc . '</p>';
	}

	$qry = "SELECT deck, descrip FROM {$sdb}decks WHERE ship='$sclass'";
	$result = $database->openShipsWithReturn($qry);

    if (mysql_num_rows($result))
    {
    	?>
	    <h5><strong class="heading">Deck Listing</strong></h5>
	    <table class="table table-bordered decklisting">
          <thead>
	    	<tr>
        	  <th>Deck</th>
              <th>Description</th>
        	</tr>
          </thead>
          <tbody>
        <?php
	    while ( list($decknum, $deckdesc) = mysql_fetch_array($result) )
        	$decklist[$decknum] = $deckdesc;

        for ($i = 1; $i <= $decks; $i++)
	    {
        	echo '<tr>';
            echo '<td>' . $i . '</td>';
            echo '<td>' . $decklist[$i] . '</td>';
            echo '</tr>';
		}

	    echo '</tbody></table>';
    }

	if ($pop != "y" && $support == "y")
    {
		echo '<h6><strong class="heading">Found on:</strong></h6>';
		echo '<div class="list-group specs-list">';
    	$qry = "SELECT c.id, c.name, d.name
        		FROM {$sdb}classes c, {$sdb}equip e, {$sdb}category d
                WHERE e.equipment='$sclass' AND e.type='c' AND e.ship=c.id
                	AND c.category=d.id AND e.number>'0'";
		$result = $database->openShipsWithReturn($qry);
	    while (list($cid, $cname, $catname,) = mysql_fetch_array($result)) {
			if (!defined("IFS"))
				echo '<a href="shipdb.php?sclass=' . $cid . '">' . $cname . ' Class ' . $catname . '</a>';
			else
    			echo '<a href="index.php?option=shipdb&sclass=' . $cid . '">' . $cname . " Class " . $catname . '</a>';
		}
	    echo '</div>';
    }

    if ($pop != 'y') echo "<a href=\"index.php?option=shipdb\">Back to the Ship Database</a>\n";

}

// Default: get listing of all classes
else
{
	echo '<h2 class="heading">Starships / Starbases</h2>';
	$qry = "SELECT c.id, c.name, c.active, t.type, d.name as catd
    		FROM {$sdb}classes c, {$sdb}category d, {$sdb}types t
            WHERE c.category=d.id AND d.type=t.id AND t.support='n'
            ORDER BY t.type, d.name, c.name";
	$result = $database->openShipsWithReturn($qry);
    while(list($cid, $cname, $cactive, $type, $catd) = mysql_fetch_array($result))
    {
    	if ($cactive == "1")
        {
	      	if ($oldtype != $type)
	        {
				$liststart = true;
				if ($oldtype!='') $liststart = false;
				if ($oldtype!='') echo '</ul>';
		       	echo '<h3>' . $type . 's</h3>';
	            $oldtype = $type;
	        } 
	      	if ($oldcatd != $catd)
	        {
				if (!$liststart) echo '</ul>';
				echo '<h4 class="specs-list-header">&nbsp;' . $catd . 's</h4>';
		       	echo '<ul class="list-unstyled specs-list">';
	            $oldcatd = $catd;
	        }

	    	if (!defined("IFS"))
		    	echo '<li><a href="shipdb.php?sclass=' . $cid . '">' . $cname .' Class</a></li>';
			else
		    	echo '<li><a href="index.php?option=shipdb&amp;sclass=' . $cid . '">' . $cname . ' Class</a></li>';
				
        }
        else
        {
        	$inactiveshipid[] = $cid;
            $inactiveshipname[] = $cname;
            $inactiveshiptype[] = $type;
        }
  	}
	echo '</ul>';

	echo '<h2 class="heading">Support Craft</h2>';
	$qry = "SELECT c.id, c.name, c.active, t.type, t.support
    		FROM {$sdb}classes c, {$sdb}category d, {$sdb}types t
            WHERE c.category=d.id AND d.type=t.id AND t.support='y'
            ORDER BY t.type, c.name";
	$result = $database->openShipsWithReturn($qry);
    while(list($cid, $cname, $cactive, $type) = mysql_fetch_array($result))
    {
    	if ($cactive == "1")
        {
	       	if ($oldstype != $type) { 
				if ($oldstype!='') echo '</ul>';
	           	echo '<h5 class="specs-list-header">&nbsp;' . $type . 's</h5>';
		       	echo '<ul class="specs-list">';
	            $oldstype = $type;
	        }
	    	if (!defined("IFS"))
		    	echo '<a href="shipdb.php?sclass=' . $cid . '">' . $cname .' Class</a>';
	        else
		    	echo '<a href="index.php?option=shipdb&amp;sclass=' . $cid . '">' . $cname .' Class</a>';
        }
        else
        {
        	$inactiveshipid[] = $cid;
            $inactiveshipname[] = $cname;
            $inactiveshiptype[] = $type;
        }
  	}
    echo '</ul>';

    echo '<h2 class="heading">Technology</h2>';
   	if (!defined("IFS"))
    	echo '<form action="shipdb.php" method="post">';
    else
    	echo '<form action="index.php?option=shipdb" method="post">';
	?>
        <div class="form-inline">
            <div class="form-group">
                <label for="techsearch">Search for technology:</label>
                <input type="text" class="form-control" length="20" name="techsearch" id="techsearch">
            </div>
        </div>
        <input type="submit" class="btn btn-default btn-sm" value="Search">
    </form>
	<?php
    if ( is_array($inactiveshipid) )
    {
	    echo '<h2 class="heading">Inactive Classes</h2>';
    	$displayed_types = array();
		$startoflist=true;
	    for ($i = 0; array_key_exists($i, $inactiveshipid); $i++)
	    {
	    	$cid = $inactiveshipid[$i];
	        $cname = $inactiveshipname[$i];
	        $type = $inactiveshiptype[$i];

	    	if ( !in_array($type, $displayed_types) )
	        {
				if (!$startoflist) echo '</ul>';
				echo '<h5 class="specs-list-inactive-header">&nbsp;' . $type . 's</h5>';
		       	echo '<ul class="specs-list-inactive">';
	            $displayed_types[] = $type;
				$startoflist=false;
	        }

		   	if (!defined("IFS"))
		    	echo '<a href="shipdb.php?sclass=' . $cid . '">' . $cname . ' Class</a>';
		    else
		    	echo '<a href="index.php?option=shipdb&amp;sclass=' . $cid . '">' . $cname . ' Class</a>';
	    }
    }
    echo '</ul>';

}

if (!defined("IFS"))
{
	$no_back_to = "1";
	include ("includes/footer.php");
}

?>