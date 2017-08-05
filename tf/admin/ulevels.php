<?php
/***
  * INTEGRATED FLEET MANAGEMENT SYSTEM
  * OBSIDIAN FLEET
  * http://www.obsidianfleet.net/ifs/
  *
  * Developer:	Frank Anon
  * 	    	fanon@obsidianfleet.net
  *
  * Updated by: Matt Williams
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
  * Comments: Admin tool for playing with peoples' userlevels
  *
 ***/

if (!defined("IFS"))
	echo "Hacking attempt!";
else
{
    echo "<h1>Userlevel Admin</h1><br />\n";

	switch ($lib)
    {
		case "disp":
        	if ($cid)
            {
            	$qry = "SELECT player FROM {$spre}characters WHERE id='$cid'";
                $result = $database->openConnectionWithReturn($qry);
                list ($euid) = mysql_fetch_array($result);
            }

          	$qry = "SELECT name, flags FROM {$mpre}users WHERE id='$euid'";
            $result = $database->openConnectionWithReturn($qry);

			if (!mysql_num_rows($result))
            	echo '<h4 class="text-danger">UID not found</h4>';
            else
            {
	            list ($uname, $curflag) = mysql_fetch_array($result);
    	        echo '<h4>User ID: ' . $euid . ' - ' . $uname . '</h4>';
        	    ?>
				<form class="form-horizontal" action="index.php?option=ifs&amp;task=admin&amp;action=ulev&amp;lib=save" method="post">
    	        	<input type="hidden" name="euid" value="<?php echo $euid ?>">
    	        	<input type="hidden" name="uname" value="<?php echo $uname ?>">
	    	        <table class="table ulevels">
                      <thead>
    	    	    	<tr>
        	    	    	<th>
            	    	    	Flag
                	    	</th>
	                    	<th>
		                    	No Access
    		                </th>
        		            <th>
            		        	Regular Access
                		    </th>
                    		<th>
		                    	Super Access
    		                </th>
                            <th>
                            	Main Flag
                            </th>
        		        </tr>
                      </thead>
                      <tbody>

						<?php
			            $qry = "SELECT name, flag, admin FROM {$spre}flags ORDER BY flag";
    			        $result = $database->openConnectionWithReturn($qry);

						while (list ($fname, $fid, $admin) = mysql_fetch_array($result))
                        {
						?>
    	            	    <tr>
                            	<td>
									<?php
                                    	echo $fname;
										if ($admin == "1") echo '<span class="help-block">(Admin-level flag)</span>'
									?>
                                </td>
    	    	               	<td>
                                	<div class="radio">
                                    	<label>
                                        	<input type="radio" name="flag[<?php echo $fid ?>]" value="0" <?php if (!strstr($curflag, $fid)) echo ' checked="checked"' ?>>
                                            <span class="sr-only"><?php echo $fname ?> to <em>No Access</em></span>
                                    	</label>
                                    </div>
		                    	</td>
		                        <td>
                                	<div class="radio">
                                    	<label>
                                        	<input type="radio" name="flag[<?php echo $fid ?>]" value="1" <?php if (strstr($curflag, $fid)) echo ' checked="checked"' ?>>
                                            <span class="sr-only"><?php echo $fname ?> to <em>Regular Access</em></span>
                                    	</label>
                                    </div>
	                    		</td>
		                        <td>
                                	<div class="radio">
                                    	<label>
                                        	<input type="radio" name="flag[<?php echo $fid ?>]" value="2" <?php if (strstr($curflag, strtoupper($fid))) echo ' checked="checked"' ?>>
                                            <span class="sr-only"><?php echo $fname ?> to <em>Super Access</em></span>
                                    	</label>
                                    </div>
	                    		</td>
                                <td>
                                	<div class="radio">
                                    	<label>
                                        	<input type="radio" name="mainflag" value="<?php echo $fid ?>" <?php if ($curflag{0} == $fid) echo ' checked="checked"' ?>>
                                            <span class="sr-only"><?php echo $fname ?> to <em>Main Flag</em></span>
                                    	</label>
                                    </div>
                            	</td>
		                    </tr>
                        <?php
		            	}
	    	            ?>
                      </tbody>
	        	    </table>
                    <p class="help-block">Note that regular admin access includes super access to everything else, and that no other flags need to be set.</p>
                    
                    <div class="checkbox">
                    	<label>
                    		<input type="checkbox" name="clear" value="yes">
                            Remove all access?
                        </label>
                    </div>
					<br />
					<div class="form-group">
                        <label for="mainchar">Main Character:</label>
                        <select class="form-control" name="mainchar">
                            <?php
                            $qry = "SELECT mainchar FROM {$mpre}users WHERE id='$euid'";
                            $result = $database->openConnectionWithReturn($qry);
                            list ($mainchar) = mysql_fetch_array($result);
    
                            $qry = "SELECT c.id, r.rankdesc, c.name, s.name
                                    FROM {$spre}rank r, {$spre}characters c, {$spre}ships s
                                    WHERE c.player='$euid' AND c.ship=s.id AND c.rank=r.rankid";
                            $result = $database->openConnectionWithReturn($qry);
    
                            while (list ($cid, $rname, $coname, $sname) = mysql_fetch_array($result))
                            {
                                echo "<option value=\"$cid\"";
                                if ($cid == $mainchar)
                                    echo " selected=\"selected\"";
                                echo ">$rname {$coname}, $sname</option>\n";
                            }
                            ?>
                        </select>
                    </div>
                	<button type="submit" class="btn btn-default">Update</button>
	            </form>
    	        <?php
            }
        	break;


        case "save":
   	        echo '<h3>' . $euid . ' - ' . $uname . '</h3>';
			if ($clear =='yes')
			{
				$qry = "SELECT c.id, r.rankdesc, c.name, s.name FROM {$spre}rank r, {$spre}characters c, {$spre}ships s WHERE c.id='$mainchar' AND c.ship=s.id AND c.rank=r.rankid";
				$result = $database->openConnectionWithReturn($qry);
                list ($mcid, $mcrank, $mcname, $mcship) = mysql_fetch_array($result);
                echo '<h4>(' . $mcid . ') <em>' . $mcrank . ' ' . $mcname . ', ' . $mcship . '</em> set as main character.</h4>';
				
				$qry = "UPDATE {$mpre}users SET flags='', mainchar='$mainchar' WHERE id='$euid'";
               	$database->openConnectionNoReturn($qry);
               	echo '<h4 class="text-success">All access flags removed.</h4>';
			}
			else
			{
        		if ($flag[$mainflag] == "0")
            		echo '<h4 class="text-warning">The main flag must be set to have at least regular access!</h4>';
            	else
            	{
	        	    $qry = "SELECT name, flag FROM {$spre}flags ORDER BY flag";
		    	    $result = $database->openConnectionWithReturn($qry);

					if ($flag[$mainflag] == "1")
		            	$userflags = strtolower($mainflag);
                	elseif ($flag[$mainflag] == "2")
		            	$userflags = strtoupper($mainflag);
					while (list ($fname, $fid) = mysql_fetch_array($result))
                	{
                		if ($fid != $mainflag)
                    	{
	        	    	    if ($flag[$fid] == "1")
                        	{
					        	$userflags .= strtolower($fid);
                            	echo '<h4 class="text-success">' . $fname . ' set to regular access.</h4>';
        	        		}
                        	elseif ($flag[$fid] == "2")
                        	{
					        	$userflags .= strtoupper($fid);
                            	echo '<h4 class="text-success">' . $fname . ' set to super access.</h4>';
                        	}
                    	}
                    	else
	        	        	if ($flag[$fid] == "1")
                            	echo '<h4 class="text-success">' . $fname . ' set to regular access. <strong class="text-uppercase">Main Flag</strong></h4>';
        	        		elseif ($flag[$fid] == "2")
                            	echo '<h4 class="text-success">' . $fname . ' set to super access. <strong class="text-uppercase">Main Flag</strong></h4>';
        	    	}
                	$qry = "SELECT c.id, r.rankdesc, c.name, s.name FROM {$spre}rank r, {$spre}characters c, {$spre}ships s WHERE c.id='$mainchar' AND c.ship=s.id AND c.rank=r.rankid";
					$result = $database->openConnectionWithReturn($qry);
                	list ($mcid, $mcrank, $mcname, $mcship) = mysql_fetch_array($result);
                	echo '<h4 class="text-success">' . $mcid . ' <em>' . $mcrank . ' ' . $mcname . ', ' . $mcship . '</em> set as main character.</h4>';

                	$qry = "UPDATE {$mpre}users SET flags='$userflags', mainchar='$mainchar' WHERE id='$euid'";
                	$database->openConnectionNoReturn($qry);
                	echo '<h3 class="text-success">Done.</h3>';
            	}
			}
        	break;


        case "sname":
        	$qry = "SELECT id, username FROM {$mpre}users WHERE username LIKE '%{$uname}%' ORDER BY username";
            $result = $database->openConnectionWithReturn($qry);
			?>
            <div class="list-group ulevels-results">
				<?php
                while (list ($uid, $uname) = mysql_fetch_array($result)){
				?>
                    <a class="list-group-item" href="index.php?option=ifs&amp;task=admin&amp;action=ulev&amp;lib=disp&amp;euid=<?php echo $uid ?>">
						<h5 class="list-group-item-heading">User ID: <?php echo $uid ?></h5>
                        <p class="list-group-item-text"><?php echo $uname ?></p>
                    </a>
                <?php
				}
                ?>
            </div>
            <?php
        	break;
			
		case "audit":
			$qry2 = "SELECT id, username, mainchar FROM {$mpre}users WHERE flags LIKE '%{$audit}%' ORDER BY username";
			$result2 = $database->openConnectionWithReturn($qry2);
			?>
            <div class="list-group ulevels-results">
				<?php
                while (list ($uid, $uname, $mainchar) = mysql_fetch_array($result2)){
					$qry9 = "SELECT r.rankdesc, c.name, s.name
                            FROM {$spre}rank r, {$spre}characters c, {$spre}ships s
                            WHERE c.id='$mainchar' AND c.ship=s.id AND c.rank=r.rankid";
                    $result9 = $database->openConnectionWithReturn($qry9);
					list ($mcrank, $mchar, $mcship) = mysql_fetch_array($result9)
				?>
                    <a class="list-group-item" href="index.php?option=ifs&amp;task=admin&amp;action=ulev&amp;lib=disp&amp;euid=<?php echo $uid ?>">
						<h5 class="list-group-item-heading">User ID: <?php echo $uid ?></h5>
                        <p class="list-group-item-text"><?php echo $uname ?></p>
						<p class="list-group-item-text"><?php if (!$mainchar) echo 'No main character set'; else echo 'Main character: ' . $mcrank . ' ' . $mchar . ' on ' . $mcship ?></p>
                    </a>
                <?php
				}
                ?>
            </div>
            <?php
			break;


        default:
	    	?>
          <div class="ulevel-search">
			<form class="form-inline" action="index.php?option=ifs&amp;task=admin&amp;action=ulev&amp;lib=sname" method="post">
		    	<div class="form-group">
                	<label for="uname">Search for a user by username:</label>
	    	    	<input type="text" class="form-control" name="uname" id="uname" size="30">
                </div>
	        	<button type="submit" class="btn btn-default btn-sm">Search</button>
	        </form><br />

			<form class="form-inline" action="index.php?option=ifs&amp;task=admin&amp;action=ulev&amp;lib=disp" method="post">
		    	<div class="form-group">
                    <label for="euid">Select a user by ID:</label>
                    <input type="text" class="form-control" name="euid" name="euid" size="5">
                </div>
	        	<button type="submit" class="btn btn-default btn-sm">Search</button>
	        </form><br />

			<form class="form-inline" action="index.php?option=ifs&amp;task=admin&amp;action=ulev&amp;lib=disp" method="post">
		    	<div class="form-group">
                    <label for="cid">Select a user by characer ID (the player for this character):</label>
                    <input type="text" class="form-control" name="cid" id="cid" size="5">
                    </div>
	        	<button type="submit" class="btn btn-default btn-sm">Search</button>
	        </form><br />

			<form class="form-inline" action="index.php?option=ifs&amp;task=admin&amp;action=ulev&amp;lib=audit" method="post">
		    	<div class="form-group">
                    <label for="audit">Search for a user by access:</label>
                    <select class="form-control" name="audit" id="audit">
                        <option value="a">Admin</option>
                        <option value="c">Commanding Officer</option>
                        <option value="o">Fleet Chief Ops</option>
                        <option value="p">Personnel Management</option>
                        <option value="t">Task Force CO</option>
                        <option value="j">Judge Advocate General</option>
                        <option value="r">R &amp; D</option>
                        <option value="w">Awards Director</option>
                        <option value="g">Task Group CO</option>
                        <option value="d">Academy</option>
                    </select>
                </div>
	        	<button type="submit" class="btn btn-default btn-sm">Search</button>
	        </form>
          </div>
			<?php
        	break;
    }
}

?>