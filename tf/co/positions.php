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
  *       matt@mtwilliams.uk
  *
  * Version:	1.17
  * Release Date: June 3, 2004
  * Patch 1.17:   August 2017
  *
  * Copyright (C) 2003-2004 Frank Anon for Obsidian Fleet RPG
  * Distributed under the terms of the GNU General Public License
  * See doc/LICENSE for details
  *
  * This file based on code from Open Positions List
  * Copyright (C) 2002, 2003 Frank Anon
  *
  * Comments: Allows COs to edit positions for the OPL
 ***/

if (!defined("IFS"))
	echo "Hacking attempt!";
else
{
	?>
	<h2 class="text-center">Edit Positions</h2>
    <p>Below is a list of positions for your ship<br />
    This list is used in generating your ship's listing on the Open Positions List.</p>

    <form class="form-horizontal" method="post" action="index.php?option=ifs&amp;task=co&amp;action=save_pos">
    	<input type="hidden" name="sid" value="<?php echo $sid ?>" />
		<?php
        if ($multiship)
            echo '<input type="hidden" name="multiship" value="' . $multiship . '">';
        ?>
        <input type="hidden" name="pos_act" value="remove">
        <?php
        if ($adminship)
            echo '<input type="hidden" name="adminship" value="' . $adminship . '">';
        ?>
        <h5>Remove Current Positions</h5>
        <div class="form-group">
        	<strong class="col-xs-8 col-sm-6 col-lg-4">Position</strong>
            <strong class="col-xs-4 col-sm-3">Remove</strong>
        </div>
        <?php
		$filename = "tf/positions.txt";
        $contents = file($filename);
        $length = sizeof($contents);
        $counter = 0;

        do
        {
            $pos = trim($contents[$counter]);
            $pos = mysql_real_escape_string($pos);

            $qry = "SELECT pos FROM {$spre}positions WHERE ship='$sid' AND action='rem' AND pos='$pos'";
            $result = $database->openConnectionWithReturn($qry);

            if (!mysql_num_rows($result))
            {
                $pos = stripslashes($pos);
				?>
                <div class="checkbox">
                	<label class="col-xs-10 col-sm-6 col-lg-4"><?php echo $pos ?></label>
                	<div class="col-xs-2">
                    	<input type="checkbox" class="pull-right" name="check[]" value="<?php echo $pos ?>">
                    </div>
                </div>
                <?php
            }
            $counter = $counter + 1;
        } while ($counter < ($length));

        $qry = "SELECT pos FROM {$spre}positions WHERE ship='$sid' AND action='add'";
        $result = $database->openConnectionWithReturn($qry);
        while ( list ($pos) = mysql_fetch_array($result) )
        {
            $pos = htmlentities($pos);
			?>
                <div class="checkbox">
                	<label class="col-xs-10 col-sm-6 col-lg-4"><?php echo $pos ?></label>
                	<div class="col-xs-2">
                    	<input type="checkbox" class="pull-right" name="check[]" value="<?php echo $pos ?>">
                    </div>
                </div>
            <?php
        }

        ?>
        <br />
        <div class="form-group">
        	<div class="col-xs-7 col-sm-5 col-lg-3"></div>
            <div class="col-xs-4 col-sm-3">
            	<input class="btn btn-danger btn-sm" type="submit" value="Remove">
            </div>
        </div>
    </form>

    <form class="form-horizontal" method="post" action="index.php?option=ifs&amp;task=co&amp;action=save_pos">
        <input type="hidden" name="sid" value="<?php echo $sid ?>">
        <?php
        if ($multiship)
            echo '<input type="hidden" name="multiship" value="' . $multiship . '">';
        ?>
        <input type="hidden" name="pos_act" value="add">
        <?php
        if ($adminship)
            echo '<input type="hidden" name="adminship" value="' . $adminship .'" />';
        ?>
        <h5>Add Positions</h5>
        <div class="form-group">
        	<strong class="col-xs-8 col-sm-6 col-lg-4">Position</strong>
            <strong class="col-xs-4 col-sm-3">Add</strong>
        </div>

        <?php
        $qry = "SELECT pos FROM {$spre}positions WHERE ship='$sid' AND action='rem'";
        $result = $database->openConnectionWithReturn($qry);
        while ( list ($pos) = mysql_fetch_array($result) )
        {
		?>
            <div class="checkbox">
                <label class="col-xs-10 col-sm-6 col-lg-4"><?php echo $pos ?></label>
                <div class="col-xs-2">
                    <input type="checkbox" class="pull-right" name="check[]" value="<?php echo $pos ?>">
                </div>
            </div>
        <?php
        }
        ?>
        <div class="form-group">
        	<div class="col-xs-9 col-sm-6 col-lg-4 other-checkbox-input">
            	<input type="text" class="form-control input-sm" length="25" name="other">
            </div>
            <div class="col-xs-2 checkbox other-checkbox">
                <input type="checkbox" class="pull-right" name="o1">
            </div>
        </div>
        <div class="form-group">
        	<div class="col-xs-9 col-sm-6 col-lg-4 other-checkbox-input">
            	<input type="text" class="form-control input-sm" length="25" name="other2">
            </div>
            <div class="col-xs-2 checkbox other-checkbox">
                <input type="checkbox" class="pull-right" name="o2">
            </div>
        </div>
        <div class="form-group">
        	<div class="col-xs-9 col-sm-6 col-lg-4 other-checkbox-input">
            	<input type="text" class="form-control input-sm" length="25" name="other3">
            </div>
            <div class="col-xs-2 checkbox other-checkbox">
                <input type="checkbox" class="pull-right" name="o3">
            </div>
        </div>
        <br />
        <div class="form-group">
        	<div class="col-xs-7 col-sm-5 col-lg-3"></div>
            <div class="col-xs-4 col-sm-3">
            	<input class="btn btn-success btn-sm" type="submit" value="Add">
            </div>
        </div>
    </form>
	<?php
}
?>