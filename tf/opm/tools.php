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
  * Comments: OPM tools
 ***/

if (!defined("IFS"))
	echo "Hacking attempt!";
else
{
	?>
	<h2 class="text-center">Welcome to OPM Tools</h2>
	<p class="text-center">Please note that your login will time out after about 10 minutes of inactivity.</p>

	<form class="form-horizontal" action="index.php?option=ifs&amp;task=opm&amp;action=common&amp;lib=ctrans" method="post">
		<h5>Transfer:</h5>
        <div class="form-group">
			<label for="transcid" class="col-sm-2 control-label">Character ID:</label>
            <div class="col-sm-1">
            	<input type="text" class="form-control" name="cid" id="transcid" size="3">
            </div>
		</div>
        <div class="form-group">
        	<label for="transsid" class="col-sm-2 control-label">Transfer Destination ID:</label>
        	<div class="col-sm-10 col-md-6 col-lg-4">
            	<select class="form-control" name="sid" id="transsid">
					<?php
                    $res69=mysql_query("SELECT id, name FROM ifs_ships WHERE tf<>'99' ORDER BY name ASC");
                    while (list($vd, $ve)=mysql_fetch_array($res69)) {
                        echo '<option value="'.$vd.'">'.stripslashes($ve).'</option>
                    '; }
                    ?>      
        		</select>
        	</div>
        </div>
        <div class="form-group">
        	<div class="col-sm-10 col-sm-offset-2">
				<input class="btn btn-default btn-sm" type="submit" value="Submit">
        	</div>
        </div>
    </form>
	<br />
	<form class="form-horizontal" action="index.php?option=ifs&amp;task=opm&amp;action=common&amp;lib=clistem" method="post">
		<h5>Character list</h5>
        <p class="help-block">Find the characters associated with an email address or player ID#</p>
		<div class="form-group">
        	<label for="listemail" class="col-sm-2 control-label">Email address:</label>
            <div class="col-sm-10 col-md-6 col-lg-4">
            	<input type="text" class="form-control" name="email" id="listemail" size="30">
            </div>
		</div>
        <div class="form-group">
        	<div class="col-sm-10 col-sm-offset-2">
				<input class="btn btn-default btn-sm" type="submit" value="Submit">
            </div>
        </div>
    </form>
	<br />
	<form class="form-horizontal" action="index.php?option=ifs&amp;task=opm&amp;action=common&amp;lib=clistid" method="post">
		<div class="form-group">
        	<label for="listpid" class="col-sm-2 control-label">Player ID:</label>
            <div class="col-sm-1">
            	<input type="text" class="form-control" name="pid" id="listpid" size="5">
			</div>
        </div>
        <div class="form-group">
        	<div class="col-sm-10 col-sm-offset-2">
				<input class="btn btn-default btn-sm" type="submit" value="Submit">
            </div>
        </div>
    </form>
	<br />
	<form class="form-horizontal" action="index.php?option=ifs&amp;task=opm&amp;action=common&amp;lib=cview" method="post">
		<h5>Character Lookup</h5>
		<p class="help-block">Find the character info for an ID#</p>
        <div class="form-group">
			<label for="chcid" class="col-sm-2 control-label">Character ID:</label>
            <div class="col-sm-1">
            	<input type="text" class="form-control" name="cid" id="chcid" size="5">
            </div>
        </div>
        <div class="form-group">
			<div class="col-sm-10 col-sm-offset-2">
            	<input class="btn btn-default btn-sm" type="submit" value="Submit">
            </div>
        </div>
    </form>
    
	<form class="form-horizontal" action="index.php?option=ifs&amp;task=opm&amp;action=common&amp;lib=rview" method="post">
		<p class="help-block">Find Service Record info</p>
		<div class="form-group">
        	<label for="srcid" class="col-sm-2 control-label">Character ID:</label>
        	<div class="col-sm-1">
        		<input type="text" class="form-control" name="cid" id="srcid" size="5">
			</div>
        </div>
        <div class="form-group">
        	<div class="col-sm-10 col-sm-offset-2">
        		<input class="btn btn-default btn-sm" type="submit" value="Submit">
        	</div>
        </div>
    </form>
    <br />
	<form class="form-horizontal" action="index.php?option=ifs&amp;task=opm&amp;action=common&amp;lib=sview" method="post">
		<h5>Ship Lookup</h5>
		<p class="help-block">Find the ship info for an ID#</p>
        <div class="form-group">
			<label for="sid" class="col-sm-2 control-label">Ship ID:</label>
            <div class="col-sm-1">
            	<input type="text" class="form-control" name="sid" id="sid" size="5">
            </div>
        </div>
        <div class="form-group">
        	<div class="col-sm-10 col-sm-offset-2">
				<input class="btn btn-default btn-sm" type="submit" value="Submit">
            </div>
        </div>
    </form>
	<br />
    <h5>In-Depth Character Lookup</h5>
    <p class="help-block">Find characters by:</p>
    <div class="row">
        <div class="col-xs-12 col-sm-6 col-md-4">
            <form action="index.php?option=ifs&amp;task=opm&amp;action=common&amp;lib=clname" method="post">
                <div class="form-group">
                    <label for="charname">Name</label>
                    <input type="text" class="form-control" name="charname" id="charname" size="30">
                </div>
                <input class="btn btn-default btn-sm" type="submit" value="Submit">
            </form>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-4">
            <form action="index.php?option=ifs&amp;task=opm&amp;action=common&amp;lib=clrace" method="post">
                <div class="form-group">
                    <label for="charrace">Species</label>
                    <input type="text" class="form-control" name="charrace" id="charrace" size="30">
                </div>
                <input class="btn btn-default btn-sm" type="submit" value="Submit">
            </form>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-4">
            <form action="index.php?option=ifs&amp;task=opm&amp;action=common&amp;lib=clgender" method="post">
                <div class="form-group">
                    <label for="chargender">Gender</label>
                    <select class="form-control" name="chargender" id="chargender">
                        <?php
                        $res69=mysql_query("SELECT DISTINCT gender FROM ifs_characters ORDER BY gender");
                        while (list($gender)=mysql_fetch_array($res69)) {
                            echo '<option value="'.$gender.'">'.$gender.'</option>
                        '; }
                        ?>
                    </select>
                </div>
                <input class="btn btn-default btn-sm" type="submit" value="Submit">
            </form>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-4">
            <form action="index.php?option=ifs&amp;task=opm&amp;action=common&amp;lib=clrank" method="post">
            	<div class="form-group">
                    <label for="charrank">Rank</label>
                    <select class="form-control" name="charrank" id="charrank">
                    	<?php
						$res1=mysql_query("SELECT DISTINCT genre FROM ifs_rank WHERE hidden = 0 ORDER BY genre");
						while (list ($rgn)=mysql_fetch_array($res1)) {
							echo '<optgroup label="' . $rgn . ' genre">';
							$res12=mysql_query("SELECT rankid, rankdesc, helpertext, division FROM ifs_rank WHERE genre = '".$rgn."' AND hidden = 0 ORDER BY rankid");
							while (list($rid, $rnm, $rht, $rdv)=mysql_fetch_array($res12)) {
								echo '<option value="';
								echo $rid;
								echo '">';
								echo $rnm;
								if ($rht) echo ' ('.$rht.')';
								if ($rdv) echo ' - '.$rdv;
								echo '</option>';
							}
							echo '</optgroup>';
						}
						?>
                    </select>
                </div>
                <input class="btn btn-default btn-sm" type="submit" value="Submit">
            </form>
        </div>
        <div class="col-xs-12 col-sm-8 col-md-8">
            <form action="index.php?option=ifs&amp;task=opm&amp;action=common&amp;lib=clpos" method="post">
            	<div class="form-group">
                    <label for="charpos">Position</label>
                    <select class="form-control" name="charpos" id="charpos">
                        <?php
                        $res69=mysql_query("SELECT DISTINCT pos FROM ifs_characters ORDER BY pos ASC");
                        while (list($pos)=mysql_fetch_array($res69)) {
                            echo '<option value="'.$pos.'">'.$pos.'</option>
                        '; }
                        ?>
                    </select>
                </div>
                <input class="btn btn-default btn-sm" type="submit" value="Submit">
            </form>
        </div>
    </div>
	<?php
}
?>