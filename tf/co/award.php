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
  * Comments: Allows COs to nominate characters for awards
 ***/

if (!defined("IFS"))
	echo "Hacking attempt!";
else
{
	$qry = "SELECT c.id, r.rankdesc, c.name FROM {$spre}rank r, {$spre}characters c
    		WHERE c.ship='$sid' AND c.rank=r.rankid ORDER BY r.level DESC, c.name";
    $result = $database->openConnectionWithReturn($qry);

	$qry2 = "SELECT id, name, level FROM {$spre}awards WHERE active='1' ORDER BY level, name";
    $result2 = $database->openConnectionWithReturn($qry2);
    ?>
    <h2>Nominate Crew for Awards</h2>
    <form action="index.php?option=ifs&amp;task=co&amp;action=awardsave" method="post">
        <input type="hidden" name="sid" value="<?php echo $sid ?>">
        <?php
        if ($multiship)
            echo '<input type="hidden" name="multiship" value="' . $multiship . '">';
        ?>
        <div class="form-horizontal">
            <div class="form-group">
                <label for="cid" class="col-sm-1 control-label">Crew:</label>
                <div class="col-sm-11 col-md-6 col-lg-4">
                    <select class="form-control" name="cid" id="cid">
                        <option selected="selected"></option>
                        <?php
                        while (list($cid, $rname, $cname) = mysql_fetch_array($result))
                            echo '<option value="' . $cid . '">' . $rname . ' ' . $cname . '</option>';
                        ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="award" class="col-sm-1 control-label">Award:</label>
                <div class="col-sm-11 col-md-6 col-lg-4">
                    <select class="form-control" name="award" id="award">
                        <option selected="selected"></option>
                        <?php
                        while (list($aid, $aname, $level) = mysql_fetch_array($result2))
                            echo '<option value="' . $aid . '">' . $aname . ' (level ' . $level . ')</option>';
                        ?>
                    </select>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="reason">Reason:
            <span class="help-block">(include sample posts if necessary)</span></label>
            <textarea class="form-control" name="reason" id="reason" rows="10" cols="70"></textarea>
        </div>
    
        <p>Submitted By: <?php echo get_usertype($database, $mpre, $spre, '0', $uflag) ?></p>
    
        <input class="btn btn-default" type="submit" value="Submit">
    </form>
    <?php
}
?>