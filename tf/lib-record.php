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
  * Comments: Functions for viewing & manipulating service records
  *
 ***/

// Add details to service record
function record_add_details ($database, $spre, $mpre, $cid, $level, $date, $entry, $name, $radmin, $uflag, $multiship)
{
    $qry = "SELECT name FROM {$spre}characters WHERE id='$cid'";
    $result = $database->openConnectionWithReturn($qry);
    list($cname) = mysql_fetch_array($result);

    $entry = stripslashes($entry);
	?>
	<h2 class="text-center">Service Record for <?php echo $cname ?></h2>

	<form action="index.php?option=<?php echo option ?>&amp;task=<?php echo task ?>&action=common&lib=rsave" method="post">
        <div class="form-group">
            <label for="level" class="control-label">Type:</label>
            <div>
                <p class="form-control-static" id="level"><?php echo $level ?>
                <input type="hidden" name="level" value="<?php echo $level ?>">
                <?php
                if ($radmin == "on")
                    echo '<span class="help-block">Admin-level</span>';
                ?></p>
                <input type="hidden" name="radmin" value="<?php echo $radmin ?>">
            </div>
        </div>
        <div class="form-group">
            <label for="date" class="control-label">Date:</label>
            <div>
                <p class="form-control-static" id="date"><?php echo date("F j, Y", $date) ?></p>
                <input type="hidden" name="date" value="<?php echo $date ?>">
            </div>
        </div>
        <div class="form-group">
            <label for="entry" class="control-label">Entry:</label>
            <div>
                <p class="form-control-static" id="entry"><?php echo $entry ?></p>
                <input type="hidden" name="entry" value="<?php echo $entry ?>">
            </div>
        </div>
        <div class="form-group">
            <label for="pname" class="control-label">By:</label>
            <div>
                <p class="form-control-static" id="pname"><?php echo $name ?></p>
                <input type="hidden" name="pname" value="<?php echo $name ?>">
            </div>
        </div>
        <div class="form-group">
            <label for="details" class="control-label">Details:</label>
            <div>
                <textarea class="form-control" name="details" id="details" rows="5" cols="50">Enter details</textarea>
                <span class="help-block">Please use &lt;br /&gt; to indicate new lines, or &lt;p&gt; and &lt;/p&gt; to indicate paragraphs.</span>
            </div>
        </div>
    
        <input type="hidden" name="cid" value="<?php echo $cid ?>">
        <input type="hidden" name="sid" value="na">
        <?php
        if ($multiship)
            echo '<input type="hidden" name="multiship" value="' . $multiship . '">';
        ?>
        <div class="form-group">
        	<div>
        		<input type="Submit" value="Submit">
    		</div>
        </div>
    </form>

	<?php
}

// Save new service record entry
function record_add_save ($database, $spre, $mpre, $cid, $level, $date, $entry, $name, $details, $radmin, $uflag, $multiship)
{
	$qry = "SELECT player FROM {$spre}characters WHERE id='$cid'";
    $result = $database->openConnectionWithReturn($qry);
    list ($pid) = mysql_fetch_array($result);

    if ($radmin == "on")
    	$radmin = "y";
    else
    	$radmin = "";
	
	$name = mysql_real_escape_string($name);
	$entry = mysql_real_escape_string($entry);
	$details = mysql_real_escape_string($details);

	$qry = "INSERT INTO {$spre}record
    		SET pid='$pid', cid='$cid', level='$level',
            	date='$date', entry='$entry', details='$details',
                name='$name', admin='$radmin'";
   	$result = $database->openConnectionNoReturn($qry);

    $entry = stripslashes($entry);
    $details = stripslashes($details);
    $name = stripslashes($name);
	?>

    <h3>Entry Made</h3>
	<div>
        <div class="form-group">
            <label for="level" class="control-label">Level:</label>
            <div>
                <p class="form-control-static" id="level"><?php echo $level ?>
                    <?php
                    if ($radmin == "y")
                        echo '<span class="help-block">Admin-level</span>';
                    ?>
                </p>
            </div>
        </div>
        <div class="form-group">
            <label for="date" class="control-label">Date:</label>
            <div>
                <p class="form-control-static" id="date"><?php echo date("F j, Y", $date) ?></p>
            </div>
        </div>
        <div class="form-group">
            <label for="entry" class="control-label">Entry:</label>
            <div>
                <p class="form-control-static" id="entry"><?php echo $entry ?></p>
            </div>
        </div>
        <div class="form-group">
            <label for="details" class="control-label">Details:</label>
            <div>
                <p class="form-control-static" id="details"><?php echo $details ?></p>
            </div>
        </div>
        <div class="form-group">
            <label for="pname" class="control-label">By:</label>
            <div>
                <p class="form-control-static" id="pname"><?php echo $name ?></p>
            </div>
        </div>
	</div>
    <?php
}

// View details on service record
function record_details ($database, $spre, $mpre, $rid, $op, $uflag)
{
	$qry = "SELECT level, date, entry, details, name, cid, admin
    		FROM {$spre}record WHERE id='$rid'";
   	$result = $database->openConnectionWithReturn($qry);
    list ($level, $date, $entry, $details, $name, $cid, $radmin) = mysql_fetch_array($result);

    $qry = "SELECT name FROM {$spre}characters WHERE id='$cid'";
    $result = $database->openConnectionWithReturn($qry);
    list ($cname) = mysql_fetch_array($result);
	?>

	<h2 class="text-center">Service Record for <?php echo stripslashes($cname) ?></h2>

	<table class="table service-record record-details">
    	<tr>
        	<th>
            	Type:
            </th>
        	<td>
            	<?php
                echo $level;
                if ($radmin == "y")
                	echo '<span class="help-block">Admin-level</span>';
                ?>
            </td>
        </tr>

        <tr>
        	<th>
            	Date:
            </th>
            <td>
            	<?php echo date("F j, Y", $date) ?>
            </td>
        </tr>

        <tr>
        	<th>
            	Entry:
            </th>
            <td>
            	<?php echo stripslashes($entry) ?>
            </td>
        </tr>

        <tr>
        	<th>
                Details:
            </th>
            <td>
            	<?php echo stripslashes($details) ?>
            </td>
        </tr>

		<tr>
        	<th>
				By:
            </th>
            <td>
            	<?php echo stripslashes($name) ?>
            </td>
        </tr>
	</table>

	<?php
    if ($op == "RecordDetails")
		echo '<a href="index.php?option=user&amp;op=ServiceRecord&amp;cid=' . $cid . '">Back to Service Record</a>';
    else
	    echo '<a href="index.php?option=' . option . '&amp;task=' . task . '&amp;action=common&amp;lib=rview&amp;cid=' . $cid . '">Back to Service Record</a>';
}

// View service record
function record_view ($database, $spre, $mpre, $cid, $op, $uflag, $multiship)
{
	$qry = "SELECT flag FROM {$spre}flags WHERE admin='1'";
    $result = $database->openConnectionWithReturn($qry);

	$isadmin = 0;
	while ( list($adminflags) = mysql_fetch_array($result) )
    	if ($uflag[$adminflags] >= 1)
        	$isadmin = 1;

    $qry = "SELECT name, player FROM {$spre}characters WHERE id='$cid'";
    $result = $database->openConnectionWithReturn($qry);
    list($cname, $pid) = mysql_fetch_array($result);

    if (!mysql_num_rows($result))
    	echo "Character ID not found!<br />";
    else
    {
		?>
		<h2 class="text-center">Service Record for <?php echo stripslashes($cname) ?></h2>

		<table class="table table-bordered service-record">
          <thead>
			<tr>
	    	    <th>Date</th>
    			<th>Type</th>
		        <th>Entry</th>
    		    <th>Entered By</th>
        		<th>Details</th>
		    </tr>
          </thead>
          <tbody>

			<?php
    	    $qry = "SELECT id, level, date, entry, name, admin
            		FROM {$spre}record WHERE cid='$cid'
                    	AND level='In-Character' ORDER BY level,date";
    		$result = $database->openConnectionWithReturn($qry);
			?>
   			<tr>
            	<td colspan="5">
            	 	<h3 class="text-center">In-Character Records</h3>
                </td>
            </tr>
			<?php
			if (mysql_num_rows($result)<1)
			{ ?>
            	<tr>
                	<td colspan="5">
                    	<h4 class="text-center text-info">No In-Character Records Found</h4>
                    </td>
                </tr>
			<?php	
			} else {
				while ( list($rid, $lvl, $date, $record, $name, $radmin) = mysql_fetch_array($result) )
				{
					if ($radmin == "n" || $radmin == "" || ($isadmin == 1 && $radmin == "y"))
					{
					?>
						<tr>
                        	<td>
								<?php echo date("F j, Y", $date) ?>
							</td>
                            <td>
							<?php
								echo $lvl;
								if ($radmin == "y")
									echo '<span class="help-block">Admin-level</span>';
							?>
							</td>
                            <td>
								<?php echo stripslashes($record) ?>
							</td>
                            <td>
								<?php echo stripslashes($name) ?>
							</td>
                            <td class="text-center">
							<?php
                                if ($op == "ServiceRecord")
									echo '<form action="index.php?option=user&amp;op=RecordDetails" method="post">';
								else
									echo '<form action="index.php?option=' . option . '&amp;task=' . task . '&amp;action=common&amp;lib=rdetails" method="post">';
							?>
									<input type="hidden" name="rid" value="<?php echo $rid ?>">
									<input type="hidden" name="sid" value="na">
									<input type="submit" value="Details">
                                </form>
                            </td>
						</tr>
                    <?php
					}
				}
			}

	        $qry = "SELECT id, level, date, entry, name, admin
            		FROM {$spre}record
                    WHERE cid='$cid' AND level='Out-of-Character'
                    ORDER BY level,date";
    		$result = $database->openConnectionWithReturn($qry);
			?>
   			<tr>
            	<td colspan="5">
            		<h3 class="text-center">Out-of-Character Records</h3>
                </td>
            </tr>
			<?php
			if (mysql_num_rows($result)<1)
			{ ?>
            	<tr>
                	<td colspan="5">
                    	<h4 class="text-center text-info">No Out-of-Character Records Found</h4>
                    </td>
                </tr>
			<?php	
			} else {
				while ( list($rid, $lvl, $date, $record, $name, $radmin) = mysql_fetch_array($result) )
				{
					if ($radmin == "n" || $radmin == "" || ($isadmin == 1 && $radmin == "y"))
					{
					?>
						<tr>
                        	<td>
								<?php echo date("F j, Y", $date) ?>
							</td>
                        	<td>
							<?php
                            	echo $lvl;
								if ($radmin == "y")
									echo '<span class="help-block">Admin-level</span>';
							?>
							</td>
                            <td>
								<?php echo stripslashes($record) ?>
							</td>
                            <td>
								<?php echo stripslashes($name) ?>
							</td>
                            <td class="text-center">
                            <?php
								if ($op == "ServiceRecord")
									echo '<form action="index.php?option=user&amp;op=RecordDetails" method="post">';
								else
									echo '<form action="index.php?option=' . option . '&amp;task=' . task . '&amp;action=common&amp;lib=rdetails" method="post">';
							?>
									<input type="hidden" name="rid" value="<?php echo $rid ?>">
									<input type="hidden" name="sid" value="na">
									<input type="submit" value="Details">
                                </form>
                            </td>
						</tr>
                    <?php
					}
				}
			}

	        $qry = "SELECT id, level, date, entry, name, admin FROM {$spre}record WHERE pid='$pid' AND level='Player' ORDER BY level,date";
    		$result = $database->openConnectionWithReturn($qry);
			?>
   			<tr>
            	<td colspan="5">
            		<h3 class="text-center">Player Records</h3>
                </td>
            </tr>
			<?php
			if (mysql_num_rows($result)<1)
			{ ?>
            	<tr>
                	<td colspan="5">
                    	<h4 class="text-center text-info">No Player Records Found</h4>
                    </td>
                </tr>
			<?php	
			} else {
				while ( list($rid, $lvl, $date, $record, $name, $radmin) = mysql_fetch_array($result) )
				{
					if ($radmin == "n" || $radmin == "" || ($isadmin == 1 && $radmin == "y"))
					{
					?>
						<tr>
                        	<td>
                            	<?php echo date("F j, Y", $date) ?>
                            </td>
                            <td>
                            <?php
                            	echo $lvl;
                            	if ($radmin == "y")
                            	    echo '<span class="help-block">Admin-level</span>';
                            ?>
							</td>
                            <td>
                            	<?php echo stripslashes($record) ?>
                            </td>
                            <td>
                            	<?php echo stripslashes($name) ?>
                            </td>
                            <td class="text-center">
                            <?php
                                if ($op == "ServiceRecord")
                                    echo '<form action="index.php?option=user&amp;op=RecordDetails" method="post">';
                                else
                                    echo '<form action="index.php?option=' . option . '&amp;task=' . task . '&amp;action=common&amp;lib=rdetails" method="post">';
                            ?>
									<input type="hidden" name="rid" value="<?php echo $rid ?>">
                                    <input type="hidden" name="sid" value="na">
                                    <input type="submit" value="Details">
                                </form>
							</td>
                        </tr>
                    <?php
					}
				}
			}
			?>
   			<tr>
            	<td colspan="5">
            		<h3 class="text-center">Add New Record</h3>
                </td>
            </tr>
			<?php
			if ((($isadmin == 1) || ((task == "co") && (get_usertype($database, $mpre, $spre, $cid, $uflag)))) && $op != "ServiceRecord")
            {
       	       	$uname = get_usertype($database, $mpre, $spre, $cid, $uflag);
                if ($uname)
                {
					?>
					<form action="index.php?option=<?php echo option ?>&amp;task=<?php echo task ?>&amp;action=common&amp;lib=radd" method="post">
					    <tr>
						    <td class="vcenter"><input type="hidden" name="date" value="<?php echo time() ?>"><?php echo date("F j, Y") ?></td>
							<td class="vcenter">
                            	<div class="form-group">
                                	<label for="level" class="sr-only">Select Record Level:</label>
                                    <select class="form-control" name="level" id="level">
                                        <option value="In-Character">In-Character</option>
                                        <option value="Out-of-Character">Out-of-Character</option>
                                        <option value="Player">Player</option>
                                    </select>
                                </div>
                                <?php
                                if ($isadmin == 1)
								{
								?>
                                	<div class="checkbox">
                                    	<label>
	                                		<input type="checkbox" name="radmin" id="radmin">
                                    		Admin-level
                                        </label>
                                    </div>
                                <?php
								}
								?>
					    	</td>

						    <td class="vcenter">
                            	<div class="form-group">
                                	<label for="entry" class="sr-only">Entry title:</label>
                                    <input type="text" class="form-control" name="entry" id="entry">
                                </div>
                            </td>

				            <td class="vcenter">
			    	        	<?php
			           			echo '<input type="hidden" name="pname" value="' . $uname . '">' . stripslashes($uname);
			    	    		?>
				             </td>
						    <td class="text-center vcenter">
			    				<input type="hidden" name="cid" value="<?php echo $cid ?>">
				                <input type="hidden" name="sid" value="na">
                                <?php
    							if ($multiship)
        							echo '<input type="hidden" name="multiship" value="' . $multiship . '">';
								?>
			    				<input type="submit" value="Add">
							</td>
						</tr>
                    </form>
                    <?php
				}
                else
                {
				?>
					<tr>
                    	<td colspan="5">
							<h4 class="text-danger text-center">Error!  L1 - Cannot get user</h4>
                  		</td>
                    </tr>
                <?php 
                }
			}
			?>
		  </tbody>
        </table>
        <?php
	}
}
?>