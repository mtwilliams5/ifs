<?php
/***
  * INTEGRATED FLEET MANAGEMENT SYSTEM
  * OBSIDIAN FLEET
  * http://www.obsidianfleet.net
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
  * Comments: Awards Admin - edit/add/delete awards
 ***/

if (!defined("IFS"))
	echo "Hacking attempt!";
else
{
	echo '<h1 class="text-center">Awards Admin</h1>';
	echo '<br />';

	if (!$aid)
    {
		echo '<div class="list-group">';
		$qry = "SELECT id, name, level, intro FROM {$spre}awards
        		WHERE active='1' ORDER BY level ASC, name";
	    $result = $database->openConnectionWithReturn($qry);
	    while (list($aid, $name, $level, $intro) = mysql_fetch_array($result)) {
		    echo '<a class="list-group-item" href="index.php?option=ifs&amp;task=awards&amp;action=edit&amp;aid=' . $aid . '">';
			echo '<h4 class="list-group-item-heading">' . $name . ' <small>- Level ' . $level . '</small></h4>';
			echo '<p class="list-group-item-text">' . $intro . '</p>';
			echo '<p class="list-group-item-text help-block">Click to edit</p>';
			echo '</a>';
        }
        echo '</div>';
        echo '<a role="button" class="btn btn-default" href="index.php?option=ifs&amp;task=awards&amp;action=edit&amp;aid=add">Add an Award</a>';

        echo '<h3>Discontinued Awards</h3>';
		echo '<div class="list-group">';
		$qry = "SELECT id, name FROM {$spre}awards WHERE active='0' ORDER BY level ASC, name";
	    $result = $database->openConnectionWithReturn($qry);
	    while (list($aid, $name) = mysql_fetch_array($result))
		{
		    echo '<a class="list-group-item" href="index.php?option=ifs&amp;task=awards&amp;action=edit&amp;aid=' . $aid . '">';
			echo '<h4 class="list-group-item-heading"><span class="text-muted">' . $name . '</span></h4>';
			echo '<p class="list-group-item-text help-block">Click to edit</p>';
			echo '</a>';
		}
		echo '</div>';
    }
    else
    {
    	if ($aid != "add")
        {
			$qry = "SELECT name, image, level, active, intro, descrip
            		FROM {$spre}awards WHERE id='$aid'";
		    $result = $database->openConnectionWithReturn($qry);
		    list($name, $image, $level, $active, $intro, $descrip) = mysql_fetch_array($result);
        }
        ?>

        <form class="form-horizontal" action="index.php?option=ifs&amp;task=awards&amp;action=save" method="post">
		   	<input type="hidden" name="aid" value="<?php echo $aid ?>">
            <div class="form-group">
		    	<label for="aname" class="col-sm-2 control-label">Name:</label>
                <div class="col-sm-10 col-md-6 col-lg-4">
                	<input type="text" class="form-control" name="aname" id="aname" value="<?php echo $name ?>">
                </div>
            </div>
            <div class="form-group">
		    	<label for="level" class="col-sm-2 control-label">Level:</label>
                <div class="col-sm-1">
                	<input type="text" class="form-control" name="level" id="level" value="<?php echo $level ?>">
                </div>
            </div>
            <div class="form-group">
				<label for="image" class="col-sm-2 control-label">Image:</label>
                <div class="col-sm-10 col-md-6 col-lg-4">
                	<input type="text" class="form-control" name="image" id="image" value="<?php echo $image ?>">
                </div>
            </div>
            <div class="form-group">
            	<label for="intro" class="col-sm-2 control-label">Intro:</label>
            	<div class="col-sm-10 col-md-8 col-lg-6">
                	<textarea class="form-control" name="intro" id="intro" cols="60" rows="4"><?php echo $intro ?></textarea>
                </div>
            </div>
            <div class="form-group">
            	<label for="descrip" class="col-sm-2 control-label">Description:</label>
            	<div class="col-sm-10 col-md-8 col-lg-6">
                	<textarea class="form-control" name="descrip" id="descrip" cols="60" rows="4"><?php echo $descrip ?></textarea>
            	</div>
            </div>
            <div class="form-group">
            	<div class="col-sm-10 col-sm-offset-2">
            		<input class="btn btn-default" type="submit" value="Submit">
            	</div>
            </div>
        </form>

		<?php
        if ($aid != "add")
        {
            if ($active == "1")
                $submitname = "Discontinue this award";
            else
                $submitname = "Revive this award";
        	?>
	        <form class="form-horizontal" action="index.php?option=ifs&amp;task=awards&amp;action=del" method="post">
			   	<input type="hidden" name="aid" value="<?php echo $aid ?>">
                <div class="form-group">
                    <div class="col-sm-10 col-sm-offset-2">
                        <input class="btn btn-warning" type="submit" value="<?php echo $submitname ?>">
                    </div>
                </div>
	          	
	      	</form>
        	<?php
        }
    }
}
?>