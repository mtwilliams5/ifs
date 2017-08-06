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
  * This program contains code from Mambo Site Server 4.0.12
  * Copyright (C) 2000 - 2002 Miro International Pty Ltd
  *
  * Comments: Allows Awards Director to nominate characters for awards
 ***/

if (!defined("IFS"))
	echo "Hacking attempt!";
else
{
	echo '<h1 class="text-center">Awards Admin</h1>';
	echo '<h2>Grant An Award:</h2>';

	if (!$sid)
    {
    	?>
        <form class="form-horizontal" action="index.php?option=ifs&amp;task=awards&amp;action=award" method="post">
            <div class="form-group">
                <label for="sid" class="col-sm-3 control-label">Which sim is this person on?</label>
                <div class="col-sm-9 col-md-6 col-lg-4">
                    <select class="form-control" name="sid" id="sid">
                        <?php
                        $qry = "SELECT id, name FROM {$spre}ships WHERE tf<>'99' AND co<>'0'
                                ORDER BY name";
                        $result = $database->openConnectionWithReturn($qry);
                        while ( list ($sid, $sname) = mysql_fetch_array($result) )
                            echo '<option value="' . $sid . '">' . $sname . '</option>';
                        ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
            	<div class="col-sm-9 col-sm-offset-3">
        			<input class="btn btn-default" type="submit" value="Go">
            	</div>
            </div>
        </form>
        <?php
    }
    elseif (!$cid)
    {
  	  $qry = "SELECT c.id, r.rankdesc, c.name FROM {$spre}rank r, {$spre}characters c
	            WHERE c.ship='$sid' AND c.rank=r.rankid ORDER BY r.level, c.name";
	    $result = $database->openConnectionWithReturn($qry);

	    $qry2 = "SELECT id, name, level FROM {$spre}awards WHERE active='1' ORDER BY level, name";
	    $result2 = $database->openConnectionWithReturn($qry2);
	    ?>
	    <form class="form-horizontal" action="index.php?option=ifs&amp;task=awards&amp;action=award" method="post">
	    	<input type="hidden" name="sid" value="<?php echo $sid ?>">
			<div class="form-group">
	    		<label for="cid" class="col-sm-2 control-label">Crew:</label>
                <div class="col-sm-10 col-md-6 col-lg-4">
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
	    		<label for="award" class="col-sm-2 control-label">Award:</label>
                <div class="col-sm-10 col-md-6 col-lg-4">
                	<select class="form-control" name="award" id="award">
                        <option selected="selected"></option>
                        <?php
                        while (list($aid, $aname, $level) = mysql_fetch_array($result2))
                            echo '<option value="' . $aid . '">' . $aname . ' (level ' . $level . ')</option>';
                        ?>
                    </select>
                </div>
            </div>
			<div class="form-group">
	    		<label for="reason" class="col-sm-2 control-label">Reason:
                	<span class="help-block">(include sample posts if necessary)</span>
                </label>
                <div class="col-sm-10">
	    			<textarea class="form-control" name="reason" id="reason" rows="10" cols="70"></textarea>
                </div>
            </div>
			<div class="form-group">
	    		<label for="submittedby" class="col-sm-2 control-label">Submitted By:</label>
				<p class="form-control-static col-sm-10"><?php echo get_usertype($database, $mpre, $spre, '0', $uflag) ?></p>
            </div>
			<div class="form-group">
	    		<div class="col-sm-10 col-sm-offset-2">
            		<input class="btn btn-default" type="submit" value="Submit">
        		</div>
            </div>
	    </form>
	    <?php
	}
    else
    {
	    $qry = "SELECT name, player, rank, ship FROM {$spre}characters WHERE id='$cid'";
	    $result = $database->openConnectionWithReturn($qry);
	    list ($cname, $player, $rank, $ship) = mysql_fetch_array($result);

	    $qry = "SELECT name, level FROM {$spre}awards WHERE id='$award'";
	    $result = $database->openConnectionWithReturn($qry);
	    list ($aname, $level) = mysql_fetch_array($result);

	    $qry = "SELECT email FROM {$mpre}users WHERE id='" . uid . "'";
	    $result = $database->openConnectionWithReturn($qry);
	    list ($nemail) = mysql_fetch_array($result);

	    $date = time();
        $approved = "2";

	    $pname = get_usertype($database, $mpre, $spre, $cid, $uflag);
	    $pname = addslashes($pname);

        $qry = "SELECT u.email
	            FROM {$mpre}users u, {$spre}ships s, {$spre}characters c
	            WHERE s.id='$ship' AND s.co=c.id AND c.player=u.id";
	    $result = $database->openConnectionWithReturn($qry);
	    list ($coemail) = mysql_fetch_array($result);

	    $qry = "INSERT INTO {$spre}awardees
	            SET date='$date', award='$award', recipient='$cid', player='$player',
	                rank='$rank', ship='$ship', reason='$reason', nominator='$pname',
	                nemail='$nemail', approved='$approved'";
	    $database->openConnectionNoReturn($qry);
	    $rid = mysql_insert_id();

        $aname = addslashes($aname);
        $qry = "INSERT INTO {$spre}record
                SET pid='$player', cid='$cid', level='In-Character', date='$date',
                    entry='Award: $aname', details='$reason', name='$pname', admin='n'";
        $database->openConnectionNoReturn($qry);

        $qry = "SELECT email FROM {$mpre}users WHERE id='$player'";
        $result = $database->openConnectionWithReturn($qry);
        list ($email) = mysql_fetch_array($result);

        require_once "includes/mail/award_player.mail.php";

        require_once "includes/mail/award_co.mail.php";

	    echo '<h1 class="text-center">Fleet Awards</h1>';
	    echo '<p class="text-success">And the award has been granted!  Poof!  Like magic!</p>';
    }
}
?>
