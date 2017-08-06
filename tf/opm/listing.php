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
  * Comments: Main OPM Ship Listing
 ***/

if (!defined("IFS"))
	echo "Hacking attempt!";
else
{
	?>
	<h2 class="text-center">Welcome to the  OPM Ship Listing</h2>
	<p class="text-center">Please note that your login will time out after about 10 minutes of inactivity.</p>

    <div class="row sort-options">
        <div class="col-xs-12 col-sm-12 col-md-12 text-center">
        <p><strong>Sort by:</strong></p>
            <div class="btn-group" role="group" aria-label="...">
                <?php 
                if ($sort != "")
                {
                    if ($show)
                        echo '<a role="button" class="btn btn-default btn-sm" href="index.php?option=ifs&amp;task=opm&amp;action=listing&amp;show=' . $show . '">';
                    else
                        echo '<a role="button" class="btn btn-default btn-sm" href="index.php?option=ifs&amp;task=opm&amp;action=listing">';
                    echo 'Name</a>';
                }
                else
                    echo '<a role="button" class="btn btn-default btn-sm active" href="">Name</a>';

               if ($sort != "class")
               {
                    if ($show)
                        echo '<a role="button" class="btn btn-default btn-sm" href="index.php?option=ifs&amp;task=opm&amp;action=listing&amp;show=' . $show . '&amp;sort=class">';
                    else
                        echo '<a role="button" class="btn btn-default btn-sm" href="index.php?option=ifs&amp;task=opm&amp;action=listing&amp;sort=class">';
                    echo 'Class</a>';
               }
               else
                    echo '<a role="button" class="btn btn-default btn-sm active" href="">Class</a>';

               if ($sort != "tf")
               {
                    if ($show)
                        echo '<a role="button" class="btn btn-default btn-sm" href="index.php?option=ifs&amp;task=opm&amp;action=listing&amp;show=' . $show . '&amp;sort=tf">';
                    else
                        echo '<a role="button" class="btn btn-default btn-sm" href="index.php?option=ifs&amp;task=opm&amp;action=listing&amp;sort=tf">';
                    echo 'TF</a>';
               }
               else
                    echo '<a role="button" class="btn btn-default btn-sm active" href="">TF</a>';

               if ($sort != "crew")
               {
                    if ($show)
                        echo '<a role="button" class="btn btn-default btn-sm" href="index.php?option=ifs&amp;task=opm&amp;action=listing&amp;show=' . $show . '&amp;sort=crew">';
                    else
                        echo '<a role="button" class="btn btn-default btn-sm" href="index.php?option=ifs&amp;task=opm&amp;action=listing&amp;sort=crew">';
                    echo 'Crew</a>';
               }
               else
                    echo '<a role="button" class="btn btn-default btn-sm active" href="">Crew</a>';
                ?>
            </div>
        </div>
    </div><!-- End of sort-options row -->
	<br />
    <div class="row show-options">
        <div class="col-xs-12 col-sm-12 col-md-12 text-center">
        <p><strong>Show:</strong></p>
            <div class="btn-group" role="group" aria-label="...">
                <?php 
                if ($show != "")
                {
                    if ($sort)
                        echo '<a role="button" class="btn btn-default btn-sm" href="index.php?option=ifs&amp;task=opm&amp;action=listing&amp;sort=' . $sort . '">';
                    else
                        echo '<a role="button" class="btn btn-default btn-sm" href="index.php?option=ifs&amp;task=opm&amp;action=listing">';
                    echo 'All</a>';
                }
                else
                    echo '<a role="button" class="btn btn-default btn-sm active" href="">All</a>';

               if ($show != "active")
               {
                    if ($sort)
                        echo '<a role="button" class="btn btn-default btn-sm" href="index.php?option=ifs&amp;task=opm&amp;action=listing&amp;sort=' . $sort . '&amp;show=active">';
                    else
                        echo '<a role="button" class="btn btn-default btn-sm" href="index.php?option=ifs&amp;task=opm&amp;action=listing&amp;show=active">';
                    echo 'Active</a>';
               }
               else
                    echo '<a role="button" class="btn btn-default btn-sm active" href="">Active</a>';

               if ($show != "inactive")
               {
                    if ($sort)
                        echo '<a role="button" class="btn btn-default btn-sm" href="index.php?option=ifs&amp;task=opm&amp;action=listing&amp;sort=' . $sort . '&amp;show=inactive">';
                    else
                        echo '<a role="button" class="btn btn-default btn-sm" href="index.php?option=ifs&amp;task=opm&amp;action=listing&amp;show=inactive">';
                    echo 'Inactive</a>';
               }
               else
                    echo '<a role="button" class="btn btn-default btn-sm active" href="">Inactive</a>';

               if ($show != "open")
               {
                    if ($sort)
                        echo '<a role="button" class="btn btn-default btn-sm" href="index.php?option=ifs&amp;task=opm&amp;action=listing&amp;sort=' . $sort . '&amp;show=open">';
                    else
                        echo '<a role="button" class="btn btn-default btn-sm" href="index.php?option=ifs&amp;task=opm&amp;action=listing&amp;show=open">';
                    echo 'Open</a>';
               }
               else
                    echo '<a role="button" class="btn btn-default btn-sm active" href="">Open</a>';
                ?>
            </div>
        </div>
    </div><!-- End of show-options row -->
	<br />

	<table class="table opm-listing">
      <thead>
		<tr>
			<th>Ship Name</th>
			<th>Class</th>
   			<th>Status</th>
   			<th>TF / TG</th>
   		</tr>
        <tr>
           	<th></th>
            <th>CO Name</th>
            <th>CO Email</th>
			<th>Crew</th>
		</tr>
      </thead>
      <tbody>

		<?php
		switch ($sort)
        {
			case "class":
				$sort = "class,";
				break;
			case "tf":
				$sort = "tf, tg,";
				break;
        	case "crew":
        		$sort = "cnum ASC,  ";
		}

   		$qry = "SELECT s.id, COUNT(c.ship) AS cnum, s.name, s.class, s.co, s.tf, s.tg, s.status, s.format, s.website FROM {$spre}characters c, {$spre}ships s WHERE (c.ship = s.id OR s.co = '0') AND tf<>'99' GROUP BY s.id ORDER BY $sort name";
	    $result = $database->openConnectionWithReturn($qry);

		while ( list($sid,$crew,$sname,$sclass,$coid,$tfid,$tgid,$status, $format, $site)=mysql_fetch_array($result) )
		{
			if ($coid)
            {
				$qry2 = "SELECT name, rank, player FROM {$spre}characters WHERE id='$coid'";
				$result2=$database->openConnectionWithReturn($qry2);
				list($coname,$rid,$uid)=mysql_fetch_array($result2);

				$qry2 = "SELECT rankdesc FROM {$spre}rank WHERE rankid='$rid'";
				$result2=$database->openConnectionWithReturn($qry2);
				list($rank)=mysql_fetch_array($result2);

				$qry2 = "SELECT email FROM " . $mpre . "users WHERE id='$uid'";
				$result2=$database->openConnectionWithReturn($qry2);
				list($email)=mysql_fetch_array($result2);
			}
            else
            {
				$coname = "None";
				$rank = "";
				$email = "";
			}

			$qry2 = "SELECT name FROM {$spre}taskforces WHERE tf='$tfid' AND tg='$tgid'";
			$result2=$database->openConnectionWithReturn($qry2);
			list($tg)=mysql_fetch_array($result2);

			if (($show == "inactive") && ($status != "Waiting for Crew") && ($status != "Waiting for Command Academy completion"))
				$noshow = '1';
			elseif (($show == "open") && (($coid != "0") || ($status != "Waiting for CO")))
				$noshow = '1';
			elseif (($show == "active") && ($status != "Operational") && ($status != "Docked at Starbase"))
				$noshow = '1';
			else
				$noshow = '0';

            if ($noshow != '1')
            {
				?>
				<tr>
					<td>
						<strong><?php echo $sname ?></strong><br />
                        (<?php echo $sid ?>)
					</td>
					<td>
						<?php
                        if (!$sclass)
							echo 'Undefined class';
						else
							echo '<em>' . $sclass . '</em> class';
						?>
					</td>
					<td>
						<?php echo $status; ?>
					</td>
					<td>
						<strong>TF<?php echo $tfid ?> / <?php echo $tg ?></strong>
					</td>
				</tr>
	            <tr>
	            	<td>
						<a href="<?php echo $site; ?>" target="_blank">View Website</a><br />
						<a href="index.php?option=ifs&amp;task=opm&amp;action=common&amp;lib=sview&amp;sid=<?php echo $sid ?>">View Manifest</a>
	                </td>
					<td>
						<?php echo $rank . " " . $coname; ?>
	                </td>
	                <td>
						<?php echo $email; ?>
					</td>
					<td>
						<?php
                        if (!$coid)
							echo "No Crew";
						else
							echo "Crew: $crew";
						?>
					</td>
				</tr>
	            <tr>
                	<td colspan="4">
                    	<hr />
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
?>