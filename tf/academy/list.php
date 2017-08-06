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
  *             matt@mtwilliams.uk
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
  * Comments: List Academy students
  * See CHANGELOG for patch details
  *
 ***/
if (!defined("IFS"))
	echo "Hacking attempt!";
else
{
	?>
	<h2 class="text-center">Welcome to Academy Tools</h2>
	<p class="text-center">Please note that your login will time out after about 10 minutes of inactivity.</p>

    <?php
    if ($lib == "old")
    {
    	echo '<h3>Archived Course List</h3>';
		echo '<div class="btn-group-vertical">';
    	$qry = "SELECT course, name FROM {$spre}acad_courses WHERE active='0' AND section='0'";
        $result = $database->openConnectionWithReturn($qry);
        while (list($cid, $cname) = mysql_fetch_array($result))
        	echo '<a role="button" class="btn btn-default" href="index.php?option=ifs&amp;task=academy&amp;action=list&amp;lib=' . $cid . '">' . $cname . '</a>';
		echo '</div>';
	}
    else
    {
	    // Do listing thingy at top to let person choose the course to view
	    $qry = "SELECT course, name FROM {$spre}acad_courses
        		WHERE active='1' AND section='0'";
	    $result = $database->openConnectionWithReturn($qry);
		
		?>
        <div class="row switch-course">
            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
            <?php
            if (defined("IFS"))
            {
			?>
                <p><strong>Switch Course List:</strong></p>
                	<div class="btn-group" role="group" aria-label="...">	
					<?php
					echo '<a role="button" class="btn btn-default btn-xs smallwhite';
					if (!$lib)
						echo ' active" href="">';
					else
						echo '" href="index.php?option=ifs&amp;task=academy&amp;action=list">';
					echo 'Current Students</a>';
					
                    while ( list($courseid,$coursename)=mysql_fetch_array($result) )
					{
						echo '<a role="button" class="btn btn-default btn-xs smallwhite';
						if ($lib == $courseid)
							echo ' active" href="">';
						else
							echo '" href="index.php?option=ifs&amp;task=academy&amp;action=list&amp;lib=' . $courseid . '">';
						echo $coursename;
						echo '</a>';
					}
					?>
                    </div>
                <?php
            }
            ?>
            </div>
        </div><!-- End of switch-course row -->
		<?php
	    if (!$lib)
	    {
	        ?>
	        <table class="table academy_list">
              <thead>
	        	<tr>
	            	<th>Date Submitted</th>
	            	<th>Course</th>
	            	<th>Character</th>
	            	<th>Ship</th>
	            	<th>Assigned To</th>
	            	<th>Status</th>
	        	</tr>
              </thead>
			  <tbody>
	        <?php
			$qry = "SELECT st.id, st.sdate, co.course, co.name, ch.name,
                        sh.name, c2.name, st.status
                    FROM {$spre}acad_students st, {$spre}acad_courses co,
                        {$spre}characters ch, {$spre}characters c2,
                        {$spre}ships sh, {$spre}acad_instructors i
                    WHERE st.status NOT IN ('c','d','f') AND st.course=co.course
                        AND co.section='0' AND st.cid=ch.id
                        AND ch.ship=sh.id AND st.inst=i.id AND i.cid=c2.id";
			if($_GET['view'] == "current") { $qry .= " AND st.edate = NULL"; }
        	$qry .= " ORDER BY st.sdate";
	    	$result = $database->openConnectionWithReturn($qry);
	        while (list($sid, $sdate, $cid, $cname, $character, $ship, $inst, $status)
	            = mysql_fetch_array($result) )
	        {
			?>
	            <tr>
                    <td><?php echo date("F j, Y, g:i a", $sdate) ?></td>
                    <td><?php echo $cname ?></td>
                    <td><?php echo $character ?></td>
                    <td><?php echo $ship ?></td>
                    <td><?php echo $inst ?></td>
                    <?php
					if ($status == "w")
						echo '<td class="warning">';
					elseif ($status == "d" || $status == "f")
						echo '<td class="danger">';
					elseif ($status == "c")
						echo '<td class="success">';
					elseif ($status == "p")
						echo '<td class="info">';
					else
						echo '<td>';
					
						if ($status == "w")
							echo "Waiting List";
						else if ($status == "d")
							echo "Dropped Out";
						else if ($status == "c" || $status == "f")
						{
							$qry2 = "SELECT grade FROM {$spre}acad_marks
									 WHERE sid='$sid'";
							$result2 = $database->openConnectionWithReturn($qry2);
							list ($grade) = mysql_fetch_array($result2);
	
							if ($status == "c")
								echo "Completed: $grade";
							else
								echo "Failed: $grade";
						}
						else if ($status == "p")
							echo "In Progress";
						else
							echo "Unknown";
					?>
	            	</td>
                </tr>
            <?php
	        }
			?>
	          </tbody>
            </table>
          </form>
        <?php
	    }
	    else
	    {
			$qry9 = "SELECT name FROM {$spre}acad_courses WHERE course = '$lib' AND section = '0'";
			$result9 = $database->openConnectionWithReturn($qry9);
			list($cname)=mysql_fetch_array($result9);
			
		 	echo '<h3>' . $cname . '</h3>';	
			echo '<a href="index.php?option=ifs&amp;task=academy&amp;action=list&amp;lib='.$_GET['lib'];
			if($_GET['view'] == "current") {
				echo '">Show All Students</a>';
			} else {
				echo '&amp;view=current">Show Current Students Only</a>';
			}
			echo "<br/><br/>";
	        ?>
	        <table class="table academy_list">
	          <thead>
                <tr>
	            	<th>Date Submitted / Completed</th>
	            	<th>Character</th>
	            	<th>Ship</th>
	            	<th>Assigned To</th>
	            	<th>Status</th>
	        	</tr>
              </thead>
              <tbody>

	        <?php
	        $qry = "SELECT st.id, st.sdate, st.edate, st.status, c2.name,
	                    ch.name, sh.name, st.cid
	                FROM {$spre}acad_students st, {$spre}acad_instructors i,
	                    {$spre}ships sh, {$spre}characters c2, {$spre}characters ch
	                WHERE st.course='$lib' AND st.cid=ch.id AND ch.ship=sh.id
	                    AND st.inst=i.id AND i.cid=c2.id";
			if($_GET['view'] == "current") { $qry .= " AND st.edate is null"; }
	                $qry .= " ORDER BY st.sdate DESC";
	        $result = $database->openConnectionWithReturn($qry);
	        while (list($sid, $sdate, $edate, $status, $inst, $character, $ship, $cid)
	            = mysql_fetch_array($result) )
	        {
			?>
	            <tr>
	            	<td>
                    	<strong>Submitted:</strong> <?php echo date("F j, Y", $sdate) ?><br />
	            		<?php 
						if ($edate)
	                		echo '<strong>Completed:</strong> ' . date("F j, Y", $edate);
						?>
					</td>
	            	<td><?php echo $character ?></td>
	            	<td><?php echo $ship ?></td>
					<?php
	                if ($status == "w" || $status == "d")
                		$inst = "n/a";
					?>
                	<td><?php echo $inst ?></td>
                    <?php
					if ($status == "w")
						echo '<td class="warning">';
					elseif ($status == "d" || $status == "f")
						echo '<td class="danger">';
					elseif ($status == "c")
						echo '<td class="success">';
					elseif ($status == "p")
						echo '<td class="info">';
					else
						echo '<td>';
					
						if ($status == "w")
							echo "Waiting List";
						else if ($status == "d")
							echo "Dropped Out";
						else if ($status == "c" || $status == "f")
						{
							$qry2 = "SELECT grade FROM {$spre}acad_marks
									 WHERE sid='$sid'";
							$result2 = $database->openConnectionWithReturn($qry2);
							list ($grade) = mysql_fetch_array($result2);
	
							if ($status == "c")
								echo "Completed: $grade";
							else
								echo "Failed: $grade";
						}
						else if ($status == "p")
							echo "In Progress";
						else
							echo "Unknown";
					?>
	            	</td>
                </tr>
            <?php
	        }
			?>
	          </tbody>
            </table>
        </form>
        <?php
	    }

	    echo '<br /><br />';
	    echo '<a role="button" class="btn btn-default" href="index.php?option=ifs&amp;task=academy&amp;action=list&amp;lib=old">View Old Archived Courses</a>';
    }
}
?>
