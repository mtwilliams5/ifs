<?php
/***
  * INTEGRATED FLEET MANAGEMENT SYSTEM
  * OBSIDIAN FLEET
  * http://www.obsidianfleet.net/ifs/
  *
  * Developer:	Frank Anon
  * 	    	fanon@obsidianfleet.net
  *
  * Updated By: David VanScott
  *		davidv@anodyne-productions.com
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
  *
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
	
	<h3>Reassign Academy Students</h3>
	<p class="help-block">Using the fields below, you can select a character and a new instructor so the student can be reassigned.</p>

    <?php
    if (!$lib)
    {
		$qry = "SELECT course, name FROM {$spre}acad_courses WHERE active = 1 AND section = 0";
	    $result = $database->openConnectionWithReturn($qry);
	
		while ($fetch = mysql_fetch_assoc($result)) {
			extract($fetch, EXTR_OVERWRITE);
			
            $array[$course] = array(
                'course' => $name,
                'students' => array()
            );
		}
		
		$today = getdate();
		$constraint = 86400 * 90;
		$date = $today[0] - $constraint;
		
		$qry = "SELECT a.*, b.* FROM {$spre}acad_students AS a, {$spre}characters AS b WHERE a.status = 'p' AND b.id = a.cid";
		$result = $database->openConnectionWithReturn($qry);
		
		while ($fetch = mysql_fetch_assoc($result)) {
			extract($fetch, EXTR_OVERWRITE);
			
			$array[$course]['students'][] = array(
				'cid' => $cid,
				'name' => $name
			);
		}
		?>
		<form class="form-horizontal" method="post" action="index.php?option=ifs&amp;task=academy&amp;action=reassign&amp;lib=move">
			<div class="form-group">
				<label for="character" class="col-sm-2 control-label">Student:</label>
                <div class="col-sm-10 col-md-6 col-lg-4">
                	<select class="form-control" name="character" id="character">
						<?php
                        foreach ($array as $key => $value)
                        {
                            echo '<optgroup label="'. $value['course'] .'">';
                            
                            foreach ($value['students'] as $a => $b)
                            {
                                echo '<option value="'. $b['cid'] .','. $key .'">'. $b['name'] .'</option>';
                            }
                            
                            echo '</optgroup>';
                        }
						?>
					</select>
                </div>
            </div>
			<?php
            $qry = "SELECT a.*, b.* FROM {$spre}acad_instructors AS a, {$spre}characters AS b WHERE a.active = 1 AND a.cid = b.id";
            $result = $database->openConnectionWithReturn($qry);
            
            while ($fetch = mysql_fetch_array($result)) {
                extract($fetch, EXTR_OVERWRITE);
                
                $array[$course]['instructors'][] = array(
                    'id' => $fetch[0],
                    'name' => $fetch[6]
                );
            }
            ?>
			<div class="form-group">
				<label for="instructor" class="col-sm-2 control-label">Instructor:</label>
                <div class="col-sm-10 col-md-6 col-lg-4">
                	<select class="form-control" name="instructor" id="instructor">
						<?php
                        foreach ($array as $key => $value)
                        {
                            echo '<optgroup label="'. $value['course'] .'">';
                            
                            foreach ($value['instructors'] as $a => $b)
                            {
                                echo '<option value="'. $b['id'] .'">'. $b['name'] .'</option>';
                            }
                            
                            echo '</optgroup>';
                        }
						?>		
					</select>
                </div>
            </div>
			<div class="form-group">
            	<div class="col-sm-10 col-sm-offset-2">
                	<input class="btn btn-default" type="submit" name="submit" value="Submit">
                </div>
			</div>
		</form>
    <?php
    }
    else if ($lib == "move")
    {
		$info = explode(',', $_POST['character']);
		$instructor = $_POST['instructor'];
		
		if (is_numeric($info[0]) && is_numeric($instructor) && is_numeric($info[1]))
		{
			$qry = "UPDATE {$spre}acad_students SET inst = $instructor WHERE cid = $info[0] AND course = $info[1]";
			$result = $database->openConnectionWithReturn($qry);
			//$rows = mysql_affected_rows($result);
			
			echo '<h3 class="text-success">Student instructor was successfully updated!</h3>';
			echo '<p class="lead"><a role="button" class="btn btn-default btn-sm" href="index.php?option=ifs&task=academy&action=reassign">Click here</a> to reassign another student.</p>';
		}
    }
}
?>
