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
  * Comments: Admin interface for Academy settings and stuffs
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
    if (!$lib)
    {
        echo '<a role="button" class="btn btn-default" href="index.php?option=ifs&amp;task=academy&amp;action=admin&amp;lib=cadd">Add a course</a>';
        echo '<br /><hr />';

	    $qry = "SELECT course, name FROM {$spre}acad_courses
        		WHERE active='1' AND section='0'";
	    $result = $database->openConnectionWithReturn($qry);
	    while (list($cid, $cname) = mysql_fetch_array($result))
	    {
		?>
	        <h1><?php echo $cname ?></h1>
            <div class="row">
            	<div class="col-xs-12 text-center">
                	<div class="btn-group" role="group" aria-label="Course Actions">
                        <a role="button" class="btn btn-default btn-sm" href="index.php?option=ifs&amp;task=academy&amp;action=admin&amp;lib=cedit&amp;cid=<?php echo $cid ?>">Edit this course</a>
                        <a role="button" class="btn btn-default btn-sm" href="index.php?option=ifs&amp;task=academy&amp;action=save&amp;lib=cdel&amp;cid=<?php echo $cid ?>">Delete this course</a>
                        <a role="button" class="btn btn-default btn-sm" href="index.php?option=ifs&amp;task=academy&amp;action=admin&amp;lib=sadd&amp;cid=<?php echo $cid ?>">Add a Section</a>
                        <a role="button" class="btn btn-default btn-sm" href="index.php?option=ifs&amp;task=academy&amp;action=admin&amp;lib=iadd&amp;cid=<?php echo $cid ?>">Add an Instructor</a>
                        <a role="button" class="btn btn-default btn-sm" href="index.php?option=ifs&amp;task=academy&amp;action=list&amp;lib=<?php echo $cid ?>&view=current">View Current Students</a>
            		</div>
            	</div>
            </div>
			<h4>Sections / Chapters</h4>
            <div class="list-group">
				<?php
                $qry2 = "SELECT section, name FROM {$spre}acad_courses
                         WHERE course='$cid' AND section != '0' AND active='1'
                         ORDER BY section";
                $result2 = $database->openConnectionWithReturn($qry2);
                while (list ($sid, $sname) = mysql_fetch_array($result2))
                {
                ?>
                    <li class="list-group-item">
                        <div class="input-group">
                            <p class="form-control-static lead"><?php echo $sname ?></p>
                            <span class="input-group-btn">
                                <a role="button" class="btn btn-default" href="index.php?option=ifs&amp;task=academy&amp;action=admin&amp;lib=sedit&amp;cid=<?php echo $cid ?>&amp;sid=<?php echo $sid ?>">edit</a>
                            </span>
                            <span class="input-group-btn">
                                <a role="button" class="btn btn-default" href="index.php?option=ifs&amp;task=academy&amp;action=save&amp;lib=sdel&amp;cid=<?php echo $cid ?>&amp;sid=<?php echo $sid ?>">delete</a>
                            </span>
                        </div>
                    </li>
                <?php
                }
                ?>
            </div>
			<h4>Instructors</h4>
            <div class="row">
            <div class="col-sm-6">
            	<div class="list-group">
				<?php
                $qry2 = "SELECT c.name , i.id , r.rankdesc
                         FROM {$spre}acad_instructors i, {$spre}characters c, {$spre}rank r
                         WHERE i.course='$cid' AND i.active='1' AND i.cid=c.id AND r.rankid = c.rank
						 ORDER BY r.level DESC";
                $result2 = $database->openConnectionWithReturn($qry2);
                while (list ($instname, $instid, $instrank) = mysql_fetch_array($result2))
                {
				?>
                	<li class="list-group-item">
                    	<div class="input-group">
                        	<p class="form-control-static"><?php echo $instrank . ' ' . $instname ?></p>
                            <span class="input-group-btn">
                    			<a role="button" class="btn btn-default btn-sm" href="index.php?option=ifs&amp;task=academy&amp;action=save&amp;lib=idel&amp;cid=<?php echo $cid ?>&amp;iid=<?php echo $instid ?>">delete</a>
                            </span>
                        </div>
                    </li>
                <?php
                }
                ?>
            	</div>
			</div>
            </div>
        	<?php
	    }
    }
    else if ($lib == "cedit")
    {
    	$qry = "SELECT course, name, descrip, pass, coord
        		FROM {$spre}acad_courses
				WHERE active='1' AND section='0' AND course='$cid'";
        $result = $database->openConnectionWithReturn($qry);
        list ($cid, $cname, $desc, $pass, $coord) = mysql_fetch_array($result);
		?>
		<h2>Edit Course</h2>
        <form class="form-horizontal" action="index.php?option=ifs&amp;task=academy&amp;action=save&amp;lib=cedit" method="post">
			<input type="hidden" name="cid" value="<?php echo $cid ?>">
			<div class="form-group">
            	<label for="cname" class="col-sm-2 control-label">Course Name:</label>
                <div class="col-sm-10 col-md-8 col-lg-6">
                	<input type="text" class="form-control" name="cname" id="cname" value="<?php echo $cname ?>">
                </div>
            </div>
            <div class="form-group">
        		<label for="pass" class="col-sm-2 control-label">Passing Mark:</label>
                <div class="col-sm-10 col-md-8 col-lg-6">
                	<input class="form-control" name="pass" id="pass" type="text" value="<?php echo $pass ?>">
                </div>
            </div>
            <div class="form-group">
        		<label for="coord" class="col-sm-2 control-label">Course Coordinator:</label>
                <div class="col-sm-10 col-md-8 col-lg-6">
                	<select class="form-control" name="coord" id="coord">
                        <option value="0">None</option>
                        <?php
                        $qry2 = "SELECT i.id, c.name
                                 FROM {$spre}acad_instructors i, {$spre}characters c
                                 WHERE i.course='$cid' AND i.cid=c.id AND i.active='1'";
                        $result2 = $database->openConnectionWithReturn($qry2);
                        while (list($iid, $iname) = mysql_fetch_array($result2))
                        {
                            echo '<option value="' . $iid . '"';
                            if ($iid == $coord)
                                echo ' selected="selected"';
                            echo '>' . $iname . '</option>';
                        }
                        ?>
                    </select>
                </div>
			</div>
            <div class="form-group">
        		<label for="desc" class="col-sm-2 control-label">Course Description:</label>
                <div class="col-sm-10">
        			<textarea class="form-control" name="desc" id="desc" rows="5" cols="60"><?php echo $desc ?></textarea>
                </div>
            </div>
            <div class="form-group">
            	<div class="col-sm-10 col-sm-offset-2">
        			<input class="btn btn-default" type="submit" value="Update">
            	</div>
            </div>
        </form>
    <?php
    }
    else if ($lib == "sedit")
    {
    	$qry = "SELECT name, descrip
        		FROM {$spre}acad_courses WHERE active='1' AND course='$cid' AND section='$sid'";
        $result = $database->openConnectionWithReturn($qry);
        list ($sname, $desc) = mysql_fetch_array($result);
		?>
		<h2>Edit Section</h2>
        <form class="form-horizontal" action="index.php?option=ifs&amp;task=academy&amp;action=save&amp;lib=sedit" method="post">
			<input type="hidden" name="cid" value="<?php echo $cid ?>">
			<input type="hidden" name="sid" value="<?php echo $sid ?>">
			<div class="form-group">
            	<label for="sname" class="col-sm-2 control-label">Section Name:</label>
                <div class="col-sm-10 col-md-8 col-lg-6">
                	<input type="text" class="form-control" name="sname" id="sname" value="<?php echo $sname ?>">
                </div>
            </div>
			<div class="form-group">
        		<label for="desc" class="col-sm-2 control-label">Section Description:</label>
                <div class="col-sm-10">
        			<textarea class="form-control" name="desc" id="desc" rows="5" cols="60"><?php echo $desc ?></textarea>
                </div>
            </div>
            <div class="form-group">
        		<div class="col-sm-10 col-sm-offset-2">
            		<input class="btn btn-default" type="submit" value="Update">
                </div>
            </div>
        </form>
    <?php
    }
    else if ($lib == "cadd")
    {
	?>
		<h2>Add A Course</h2>
        <form class="form-horizontal" action="index.php?option=ifs&amp;task=academy&amp;action=save&amp;lib=cadd" method="post">
        	<div class="form-group">
            	<label for="cname" class="col-sm-2 control-label">Course Name:</label>
                <div class="col-sm-10 col-md-8 col-lg-6">
                	<input type="text" class="form-control" name="cname" id="cname">
                </div>
            </div>
        	<div class="form-group">
            	<label for="pass" class="col-sm-2 control-label">Passing Mark:</label>
                <div class="col-sm-10 col-md-8 col-lg-6">
                	<input class="form-control" name="pass" id="pass" type="text">
                </div>
            </div>
        	<div class="form-group">
            	<label for="coord" class="col-sm-2 control-label">
            		Course Coordinator:
                	<span class="help-block">(select this later)</span>
                </label>
                <div class="col-sm-10 col-md-8 col-lg-6">
                	<select class="form-control" name="coord" id="coord" disabled>
                    	<option></option>
                    </select>
                </div>
            </div>
        	<div class="form-group">
            	<label for="desc" class="col-sm-2 control-label">Course Description:</label>
                <div class="col-sm-10">
                	<textarea class="form-control" name="desc" id="desc" rows="5" cols="60"></textarea>
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
    else if ($lib == "sadd")
    {
    	$qry = "SELECT section, name
        		FROM {$spre}acad_courses
                WHERE active='1' AND course='$cid' AND section != '0'
                ORDER BY section";
        $result = $database->openConnectionWithReturn($qry);
		?>

		<h2>Add Section</h2>
        <form class="form-horizontal" action="index.php?option=ifs&amp;task=academy&amp;action=save&amp;lib=sadd" method="post">
            <input type="hidden" name="cid" value="<?php echo $cid ?>">
            <div class="form-group">
            	<label for="sname" class="col-sm-2 control-label">Section Name:</label>
                <div class="col-sm-10 col-md-8 col-lg-6">
                	<input type="text" class="form-control" name="sname" id="sname">
            	</div>
    		</div>
            <div class="form-group">
            	<label for="desc" class="col-sm-2 control-label">Section Description:</label>
                <div class="col-sm-10 col-md-8 col-lg-6">
                	<textarea class="form-control" name="desc" id="desc" rows="5" cols="60"></textarea>
            	</div>
    		</div>
            <div class="form-group">
            	<label for="order" class="col-sm-2 control-label">
                	Section Ordering:
                </label>
                <div class="col-sm-10 col-md-8 col-lg-6">
                    <strong class="help-block">Insert...</strong>
            		<select class="form-control" name="order" id="order">
            			<option value="0">At the beginning</option>
                        <?php
            			while (list ($sid, $sname) = mysql_fetch_array($result))
                			echo '<option value="' . $sid . '">After ' . $sname . '</option>';
						?>
            		</select>
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
    else if ($lib == "iadd")
    {
    	$qry = "SELECT course, name
        		FROM {$spre}acad_courses WHERE active='1' AND section='0'";
        $result = $database->openConnectionWithReturn($qry);
		?>
		<h2>Add Instructor</h2>
        <form class="form-horizontal" action="index.php?option=ifs&amp;task=academy&amp;action=admin&amp;lib=iadd2" method="post">
		<div class="form-group">
        	<label for="course" class="col-sm-2 control-label">Course:</label>
            <div class="col-sm-10 col-md-8 col-lg-6">
            	<select class="form-control" name="course" id="course">
                <?php
        		while (list ($cid, $cname) = mysql_fetch_array($result))
        			echo '<option value="' . $cid . '">' . $cname . '</option>';
				?>
            	</select>
			</div>
        </div>
        <div class="form-group">
        	<label for="iemail" class="col-sm-2 control-label">Instructor Email:</label>
            <div class="col-sm-10 col-md-8 col-lg-6">
            	<input class="form-control" name="iemail" id="iemail" type="text">
        	</div>
        </div>
        	<div class="form-group">
            	<div class="col-sm-10 col-sm-offset-2">
            		<input class="btn btn-default" type="submit" value="Continue">
                </div>
            </div>
        </form>
    <?php
    }
    else if ($lib == "iadd2")
    {
        $qry = "SELECT name FROM {$spre}acad_courses
        		WHERE course='$course' AND section='0'";
        $result = $database->openConnectionWithReturn($qry);
        list ($coursename) = mysql_fetch_array($result);

        $qry = "SELECT id FROM {$mpre}users WHERE email='$iemail'";
        $result = $database->openConnectionWithReturn($qry);
        list ($pid) = mysql_fetch_array($result);

    	$qry = "SELECT c.id, c.name, r.rankdesc, s.name FROM {$spre}characters c, {$mpre}users u, {$spre}rank r, {$spre}ships s
        		WHERE u.email='$iemail' AND c.player=u.id AND c.ship <> " . DELETED_SHIP . " AND s.id = c.ship AND r.rankid = c.rank";
        $result = $database->openConnectionWithReturn($qry);
		?>
		<h2>Add Instructor (part 2)</h2>
        <form class="form-horizontal" action="index.php?option=ifs&amp;task=academy&amp;action=save&amp;lib=iadd" method="post">
            <input name="course" type="hidden" value="<?php echo $course ?>">
            <input name="ipid" type="hidden" value="<?php echo $pid ?>">
            <div class="form-group">
                <label for="course" class="col-sm-2 control-label">Course:</label>
                <p class="col-sm-10 form-control-static" id="course"><?php echo $coursename ?></p>
            </div>
            <div class="form-group">
                <label for="iemail" class="col-sm-2 control-label">Instructor Email:</label>
                <p class="col-sm-10 form-control-static" id="iemail"><?php echo $iemail ?></p>
            </div>
            <div class="form-group">
                <label for="ichar" class="col-sm-2 control-label">Select Character:</label>
                <div class="col-sm-10 col-md-8 col-lg-6">
                    <select class="form-control" name="ichar" id="ichar">
                        <?php
                        while (list($cid, $cname, $rname, $sname) = mysql_fetch_array($result))
                            echo '<option value="' . $cid . '">' . $rname . ' ' . $cname . ' (' . $sname . ')</option>';
                        ?>
                    </select>
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
}
?>
