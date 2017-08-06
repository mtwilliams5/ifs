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
  *
  * Comments: Submit crew to the Academy
  *
 ***/


if (!defined("IFS"))
	echo "Hacking attempt!";
else
{
	echo "<h1>Academy Information</h1>\n";

    if (!$lib)
    {
    	?>
        <form action="index.php?option=ifs&amp;task=co&amp;action=acad&amp;lib=new" method="post">
            <input type="hidden" name="sid" value="<?php echo $sid ?>">
            <?php
            if ($multiship)
                echo '<input type="hidden" name="multiship" value="' . $multiship . '">';
            ?>
            <div class="form-group">
                <label for="cid"><h4>Submit Player:</h4></label>
                <select class="form-control" name="cid" id="cid">
                    <?php
                    $qry = "SELECT id, name FROM {$spre}characters WHERE ship='$sid'";
                    $result = $database->openConnectionWithReturn($qry);
                    while (list($cid, $cname) = mysql_fetch_array($result))
                        echo '<option value="' . $cid . '">' . $cname . '</option>';
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="course"><h5>Submit to Course:</h5></label>
                <select class="form-control" name="course" id="course">
                    <?php
                    $qry = "SELECT course, name FROM {$spre}acad_courses
                            WHERE active='1' AND section='0'";
                    $result = $database->openConnectionWithReturn($qry);
                    while (list($courseid, $coursename) = mysql_fetch_array($result))
                        echo '<option value="' . $courseid . '">' . $coursename . '</option>';
                    ?>
                </select>
            </div>
    
            <input class="btn btn-default" type="submit" value="Submit">
        </form>

        <?php
	    $qry = "SELECT s.id, s.cid, r.rankdesc, c.name, c.player, co.name,
        			s.status, s.sdate
	            FROM {$spre}acad_students s, {$spre}characters c,
                	{$spre}rank r, {$spre}acad_courses co
	            WHERE s.cid=c.id AND c.ship='$sid' AND c.rank=r.rankid
                	AND s.course=co.course AND co.section='0'
                ORDER BY s.sdate DESC";
	    $result = $database->openConnectionWithReturn($qry);
        ?>
        <table class="table academy_list">
        <thead>
          <tr>
        	<th>Character</th>
            <th>Course</th>
            <th>Instructor</th>
            <th>Status</th>
            <th>Registration Date</th>
            <th>&nbsp;</th>
          </tr>
        </thead>
        <tbody>
        <?php
        while (list($stuid, $cid, $rank, $cname, $pid, $course,
                $status, $start) = mysql_fetch_array($result) )
        {
		?>
        	<tr>
            	<td><?php echo $cname ?></td>
            	<td><?php echo $course ?></td>
			<?php
				$qry2 = "SELECT c.name, u.email
						 FROM {$spre}characters c, {$mpre}users u,
							{$spre}acad_students s, {$spre}acad_instructors i
						 WHERE s.id='$stuid' AND s.inst=i.id AND i.cid=c.id
							AND c.player=u.id";
				$result2 = $database->openConnectionWithReturn($qry2);
				if (list($instname, $instemail) = mysql_fetch_array($result2))
					echo '<td>' . $instname . '<br />' . $instemail . '</td>';
				else
					echo '<td>n/a</td>';
	
				if ($status == "w")
					echo '<td>Waiting List</td>';
				else if ($status == "p")
					echo '<td>In Progress</td>';
				else if ($status == "c")
					echo '<td>Completed (passed)</td>';
				else if ($status == "f")
					echo '<td>Failed</td>';
				else if ($status == "d")
					echo '<td>Dropped Out</td>';
			?>
            	<td><?php echo date("F j, Y", $start) ?></td>
            	<td><a class="btn btn-default btn-sm" href="index.php?option=ifs&amp;task=co&amp;action=common&amp;lib=cacad&amp;pid=<?php echo $pid;
					if($adminship != 0) { echo '&amp;adminship=' . $sid; }
					if($multiship) { echo '&amp;multiship=' . $multiship; }
					?>">Details</a>
            	</td>
            </tr>
		<?php
        }
		?> </tbody>
        </table> <?php
    }

    // Submitting a player
    else if ($lib == "new")
    {
    	$qry = "SELECT s.status FROM {$spre}acad_students s, {$spre}characters c
				WHERE c.id='$cid' AND c.player=s.pid AND s.course='$course'
                	AND (s.status='w' OR s.status='p' OR s.status='c')";
    	$result = $database->openConnectionWithReturn($qry);

    	if (list ($status) = mysql_fetch_array($result))
        {
        	echo '<h3 class="text-info">';
        	if ($status == "w")
            	echo 'This person is already on the waiting list!';
            else if ($status == "p")
            	echo 'This person is already taking the course!';
            else if ($status == "c")
            	echo 'This person has already completed the course!';
            echo '  The request was not processed.';
			echo '</h3>';
        }
        else
        {
        	$qry = "SELECT player FROM {$spre}characters WHERE id='$cid'";
            $result = $database->openConnectionWithReturn($qry);
            list ($pid) = mysql_fetch_array($result);

			$now = time();
        	$qry = "INSERT INTO {$spre}acad_students
            		SET cid='$cid', pid='$pid', course='$course',
                    	status='w', sdate='$now'";
            $database->openConnectionNoReturn($qry);

            echo '<h3 class="text-success">Player has been submitted to the Academy.</h3>';
        }
        $lib = "";
        include("tf/co/academy.php");
    }
}
?>
