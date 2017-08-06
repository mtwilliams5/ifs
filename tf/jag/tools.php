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
  * Comments: JAG tools
 ***/

if (!defined("IFS"))
	echo "Hacking attempt!";
else
{
	?>
	<h2 class="text-center">Welcome to JAG Tools</h2>
	<p class="text-center">Please note that your login will time out after about 10 minutes of inactivity.</p>

	<h3>Character list</h3>
	<form class="form-horizontal" action="index.php?option=ifs&amp;task=jag&amp;action=common&amp;lib=clistem" method="post">
		<p class="help-block">Find the characters associated with an email address or player ID#</p>
		<div class="form-group">
        	<label for="email" class="col-sm-2 control-label">Email address:</label>
            <div class="col-sm-10 col-md-6 col-lg-4">
            	<input type="text" class="form-control" name="email" id="email" size="30">
            </div>
        </div>
		<input type="hidden" name="op" value="list">
        <div class="form-group">
        	<div class="col-sm-10 col-sm-offset-2">
				<input class="btn btn-default btn-sm" type="submit" value="Submit">
            </div>
        </div>
    </form>

	<form class="form-horizontal" action="index.php?option=ifs&amp;task=jag&amp;action=common&amp;lib=clistid" method="post">
		<div class="form-group">
        	<label for="pid" class="col-sm-2 control-label">Player ID:</label>
            <div class="col-sm-1">
            	<input type="text" class="form-control" name="pid" id="pid" size="5">
            </div>
        </div>
		<input type="hidden" name="op" value="list2">
		<div class="form-group">
        	<div class="col-sm-10 col-sm-offset-2">
        		<input class="btn btn-default btn-sm" type="submit" value="Submit">
            </div>
        </div>
    </form>

	<h3>Find Service Record info</h3>
	<form class="form-horizontal" action="index.php?option=ifs&amp;task=jag&amp;action=common&amp;lib=rview" method="post">
		<div class="form-group">
        	<label for="cid" class="col-sm-2 control-label">Character ID:</label>
            <div class="col-sm-1">
            	<input type="text" class="form-control" name="cid" id="cid" size="5">
            </div>
        </div>
		<input type="hidden" name="op" value="record">
		<div class="form-group">
        	<div class="col-sm-10 col-sm-offset-2">
        		<input class="btn btn-default btn-sm" type="submit" value="Submit">
            </div>
        </div>
    </form>

    <h2>Assign Divisional JAG Officers</h2>
   	<?php
$qry = "SELECT tf, name, jag FROM {$spre}taskforces WHERE tg='0' ORDER BY tf";
    $result = $database->openConnectionWithReturn($qry);

    while (list ($tfid, $tfname, $djag) = mysql_fetch_array($result))
    {
    	?>
	    <form class="form-horizontal" action="index.php?option=ifs&amp;task=jag&amp;action=tools2" method="post">
        	<div class="form-group">
        		<label for="djag<?php echo $tfid ?>" class="col-sm-2 control-label">Task Force <?php echo $tfid ?>:<br /><?php echo $tfname ?></label>
            	<div class="col-sm-10 col-md-6 col-lg-4">
                	<input type="text" class="form-control" name="djag" id="djag<?php echo $tfid ?>" value="<?php echo $djag ?>" size="30">
                </div>
            </div>
            <input type="hidden" name="tfid" value="<?php echo $tfid ?>">
            <div class="form-group">
            	<div class="col-sm-10 col-sm-offset-2">
            		<input class="btn btn-default btn-sm" type="submit" value="Update TF<?php echo $tfid ?>">
                </div>
            </div>
        </form>
        <?php
}

}
?>