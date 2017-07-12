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
  * Patch 1.17: June 2017
  *
  * Copyright (C) 2003-2004 Frank Anon for Obsidian Fleet RPG
  * Distributed under the terms of the GNU General Public License
  * See doc/LICENSE for details
  *
  * Comments: TFCO Tools!
 ***/

if (!defined("IFS"))
	echo "Hacking attempt!";
else
{
	?>
	<h2 class="text-center">Welcome to TFCO Tools</h2>
	<p class="text-center">Please note that your login will time out after about 10 minutes of inactivity.</p>

	<h3><strong class="ifsheading">Add a Ship:</strong></h3>
    <?php
    $qry = "SELECT c.name
            FROM {$sdb}classes c, {$sdb}category d, {$sdb}types t
            WHERE c.category=d.id AND d.type=t.id AND t.support='n'
            ORDER BY c.name";
    $result = $database->openShipsWithReturn($qry);
    if ( mysql_num_rows($result) )
    {
    	?>
	    <form class="form-horizontal" action="index.php?option=ifs&amp;task=tfco&amp;action=common&amp;lib=sadd" method="post">
	        <input type="hidden" name="sid" value="na">
	        <input type="hidden" name="tf" value="<?php echo $tfid ?>">
	        <input type="hidden" name="format" value="Play By Nova">
			<div class="form-group">
	        	<label for="sname" class="col-sm-2 control-label">Ship name:</label>
	        	<div class="col-sm-10 col-md-6 col-lg-4">
                	<input type="text" class="form-control" name="sname" id="sname" size="20">
                </div>
            </div>
			<div class="form-group">
	        	<label for="class" class="col-sm-2 control-label">Class:</label>
	        	<div class="col-sm-10 col-md-6 col-lg-4">
                    <select class="form-control" name="class" id="class">
                        <option selected="selected"></option>
                        <?php
                        while ( list ($sname) = mysql_fetch_array($result) )
                            echo "<option value=\"$sname\">$sname</option>\n";
                        ?>
                    </select>
                </div>
            </div>
			<div class="form-group">
	        	<label for="registry" class="col-sm-2 control-label">Registry Number:</label>
	        	<div class="col-sm-10 col-md-6 col-lg-4">
                    <input type="text" class="form-control" name="registry" id="registry" size="20">
                </div>
            </div>
			<div class="form-group">
	        	<label for="status" class="col-sm-2 control-label">Status:</label>
	        	<div class="col-sm-10 col-md-6 col-lg-4">
                    <select class="form-control" name="status" id="status">
                        <?php
                        $filename = "tf/status.txt";
                        $contents = file($filename);
                        $length = sizeof($contents);
                        $count = 0;
                        $counter = 0;
                        do
                        {
                            $contents[$counter] = trim($contents[$counter]);
                            echo "<option value=\"$contents[$counter]\">$contents[$counter]</option>\n";
                            $counter = $counter + 1;
                        } while ($counter < $length);
                        ?>
                    </select>
                </div>
            </div>
			<div class="form-group">
	        	<label for="grpid" class="col-sm-2 control-label">Task Group:</label>
	        	<div class="col-sm-10 col-md-6 col-lg-4">
                    <select class="form-control" name="grpid" id="grpid">
                        <?php
                        $qry = "SELECT tg, name FROM {$spre}taskforces WHERE tf='$tfid' AND tg<>'0'";
                        $result = $database->openConnectionWithReturn($qry);
                        while (list($grp,$grpname)=mysql_fetch_array($result))
                            echo "<option value=\"{$grp}\">{$grpname}</option>\n";
                        ?>
                    </select>
                </div>
            </div>
			<div class="form-group">
		        <input class="btn btn-default" type="submit" value="Add">
            </div>
	    </form>
        <?php
    }
	else
    	echo "You must create a ship class first...<br />\n";
    ?>
	<br /><br />

	<h3><strong class="heading">Add a CO:</strong></h3>
	<span class="help-block">Only use this to add a CO that is <strong>not</strong> already in IFS!
    If they are already in the system (ie, they submitted an app through IFS),
    assign the CO through sim admin!</span>

	<?php
    $qry = "SELECT id, name FROM {$spre}ships WHERE tf='$tfid' AND co='0'";
	$result = $database->openConnectionWithReturn($qry);

    if (mysql_num_rows($result))
    {
	?>
		<form class="form-inline" action="index.php?option=ifs&amp;task=tfco&amp;action=common&amp;lib=cadd" method="post">
            <div class="form-group">
                <label for="sid" class="sr-only">Ship to add a CO to:</label>
                <select class="form-control" name="sid" id="sid">
                <?php 
                while (list ($sid, $sname) = mysql_fetch_array($result))
                    echo '<option value="' . $sid . '">' . $sname . '</option>';
                ?>
                </select>
            </div>
            <input class="btn btn-default btn-sm" type="submit" value="Submit">
        </form>
    <?php
    }
    else
    	echo '<h5>(not applicable - all your sims have COs!  Congrats!)</h5>';
}
?>