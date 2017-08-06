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
  * Patch 1.17:   August 2017
  *
  * Copyright (C) 2003-2004 Frank Anon for Obsidian Fleet RPG
  * Distributed under the terms of the GNU General Public License
  * See doc/LICENSE for details
  *
  * Comments: View & Process Deleted Characters Characters
 ***/

if (!defined("IFS"))
	echo "Hacking attempt!";
else
{
	?>
	<h2 class="text-center">Welcome to the Deleted Characters Pool</h2>
	<p class="text-center">Please note that your login will time out after about 10 minutes of inactivity.</p>

	<h2>Deleted Characters:</h2>

	<table class="table manifest">
		<?php
        $qry = "SELECT c.id, c.name, c.race, c.gender, c.rank, c.pos, c.player, c.ship, c.other
        		FROM {$spre}characters c, {$spre}rank r
                WHERE ship='" . DELETED_SHIP . "' ORDER BY r.level DESC, c.rank ASC, c.ptime";
		$result=$database->openConnectionWithReturn($qry);

		if ( !mysql_num_rows($result) )
        {
			?>
			<tr>
				<td><em class="text-center">No deleted characters</em></td>
			</tr>
			<?php
		}
        else
        {
			?>
          <thead>
		 	<tr>
		 		<th>ID #</th>
		 		<th>Rank</th>
		 		<th>Name</th>
		 		<th>Position</th>
		 		<th>E-mail</th>
		 		<th>Deletion Date</th>
		 	</tr>
          </thead>
          <tbody>
			<?php
while( list($cid,$cname,$crace,$cgen,$rank,$pos,$pid,$sid,$other)=mysql_fetch_array($result) )
            {
				$qry2 = "SELECT rankid, rankdesc, image FROM {$spre}rank WHERE rankid=" . $rank;
				$result2=$database->openConnectionWithReturn($qry2);
				list($rid,$rname,$rimg)=mysql_fetch_array($result2);

				$qry2 = "SELECT email FROM " . $mpre . "users WHERE id = '$pid'";
				$result2=$database->openConnectionWithReturn($qry2);
				list($email)=mysql_fetch_array($result2);

				?>
                <tr>
					<td><?php echo $cid ?></td>
					<td rowspan="2">
                    	<?php
						$rnkimg = $relpath . 'images/ranks/' . $rimg;
						echo '<div class="rank">';
						echo '<img src="' . $rnkimg .'" alt="' .$rname .'" border="0" class="img-responsive" />';
						echo '</div>'; ?>
                    </td>
					<td><?php echo $rname . " " . $cname; ?><br /><?php echo $crace . " " . $cgen; ?></td>
					<td><?php echo $pos ?></td>
					<td><?php echo $email ?></td>
					<td><?php echo $other ?></td>
				</tr>
                <tr>
					<td>&nbsp;</td>
                    <td colspan="2" class="text-center">
                        <form class="btn-group" action="index.php?option=ifs&amp;task=opm&amp;action=common&amp;lib=capp" method="post">
                            <input type="hidden" name="cid" value="<?php echo $cid; ?>">
                            <input type="hidden" name="sid" value="<?php echo $sid ?>">
                            <input class="btn btn-default btn-sm" type="submit" value="View App">
                            <input type="hidden" class="btn" /><!-- Fake button sibling -->
                            <input type="hidden" class="btn" /><!-- Fake button sibling -->
                            <input type="hidden" class="btn" /><!-- Fake button sibling -->
                        </form>
                        <form class="btn-group" action="index.php?option=ifs&amp;task=opm&amp;action=common&amp;lib=cview" method="post">
                            <input type="hidden" name="cid" value="<?php echo $cid; ?>">
                            <input type="hidden" name="sid" value="<?php echo $sid ?>">
                            <input type="hidden" class="btn" /><!-- Fake button sibling -->
                            <input class="btn btn-default btn-sm" type="submit" value="Edit">
                            <input type="hidden" class="btn" /><!-- Fake button sibling -->
                            <input type="hidden" class="btn" /><!-- Fake button sibling -->
                        </form>
                        <form class="btn-group" action="index.php?option=ifs&amp;task=opm&amp;action=common&amp;lib=cdel" method="post">
                            <input type="hidden" name="sid" value="<?php echo $sid ?>">
                            <input type="hidden" name="cid" value="<?php echo $cid ?>">
                            <input type="hidden" class="btn" /><!-- Fake button sibling -->
                            <input type="hidden" class="btn" /><!-- Fake button sibling -->
                            <input class="btn btn-default btn-sm" type="submit" value="Delete">
                            <input type="hidden" class="btn" /><!-- Fake button sibling -->
                        </form>
                        <form class="btn-group" action="index.php?option=ifs&amp;task=opm&amp;action=common&amp;lib=rview" method="post">
                            <input type="hidden" name="cid" value="<?php echo $cid ?>">
                            <input type="hidden" name="sid" value="<?php echo $sid ?>">
                            <input type="hidden" class="btn" /><!-- Fake button sibling -->
                            <input type="hidden" class="btn" /><!-- Fake button sibling -->
                            <input type="hidden" class="btn" /><!-- Fake button sibling -->
                            <input class="btn btn-default btn-sm" type="submit" value="Service Record">
                        </form>
                      </td>
                      <td colspan="2" class="text-right">
                        <form class="form-inline" action="index.php?option=ifs&amp;task=opm&amp;action=common&amp;lib=ctrans" method="post">
                            <input type="hidden" name="cid" value="<?php echo $cid ?>">
                            <div class="form-group">
                            	<label for="transsid<?php echo $cid ?>" class="sr-only">Transfer to ship:</label>
                                <select class="form-control input-sm" name="sid" id="transsid<?php echo $cid ?>">
                                    <?php
                                    $res69=mysql_query("SELECT id, name FROM ifs_ships WHERE tf != 99 OR id = " . DELETED_SHIP . " ORDER BY tf DESC, name ASC");
                                    while (list($vd, $ve)=mysql_fetch_array($res69)) {
                                        echo '<option value="'.$vd.'">'.stripslashes($ve).'</option>
                                    '; }
                                    ?>      
                                </select>
                            </div>
                            <input class="btn btn-default btn-sm" type="submit" value="Transfer">
                        </form>
					</td>
				</tr>
				<?php
			}
		}
        ?>
        </tbody>
    </table>
<?php } ?>