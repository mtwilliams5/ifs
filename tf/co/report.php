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
  * Comments: Prepares monthly report
 ***/

if (!defined("IFS"))
	echo "Hacking attempt!";
else
{
	$qry = "SELECT name, status, website
    		FROM {$spre}ships WHERE id='$sid'";
	$result = $database->openConnectionWithReturn($qry);
	list ($sname, $status, $site) = mysql_fetch_array($result);

	?>
    <form method="post" action="index.php?option=ifs&amp;task=co&amp;action=save_report">
        <?php
        if ($adminship)
            echo '<input type="hidden" name="adminship" value="' . $adminship . '">';
        if ($multiship)
            echo '<input type="hidden" name="multiship" value="' . $multiship . '">';
        ?>
        <input type="hidden" name="sid" value="<?php echo $sid ?>">
        <h4 class="text-center">Welcome to the monthly report generator.</h4>
        <p class="text-center">Your login will not time-out while submitting the report.</p>
        <br />
        <div class="form-horizontal">
            <div class="form-group">
                <label for="sname" class="col-xs-2">Ship Name:</label>
                <div class="col-xs-10">
                    <span class="form-control-static" id="sname"><?php echo $sname ?></span>
                </div>
            </div>
            <div class="form-group">
                <label for="status" class="col-xs-2">Status:</label>
                <div class="col-xs-10">
                    <span class="form-control-static" id="status"><?php echo $status ?></span>
                </div>
            </div>
            <div class="form-group">
                <label for="site" class="col-xs-2">Ship Website:</label>
                <div class="col-xs-10">
                    <span class="form-control-static" id="site"><?php echo $site ?></span>
                </div>
            </div>
        </div>
        <br />
        <h5>Current Crew:</h5>
        <table class="table manifest">
          <thead>
            <tr>
                <th>Rank</th>
                <th>Name</th>
                <th>Position</th>
                <th>E-mail</th>
            </tr>
          </thead>

            <?php
            $qry = "SELECT c.id, c.name, c.race, c.gender, c.rank, c.pos, c.player
            		FROM {$spre}characters AS c, {$spre}rank AS r WHERE c.ship='$sid' AND c.rank = r.rankid ORDER BY c.pending DESC, r.level DESC, c.rank ASC";
            $result=$database->openConnectionWithReturn($qry);

            if( !mysql_num_rows($result) )
            {
                ?>
                <tr>
                    <td width="100%" colspan="4">
                    	<center><i>No crew currently assigned</i><center>
                    </td>
                </tr>
                <?php
            }
            else
            {
                while( list($cid,$cname,$crace,$cgen,$rank,$pos,$pid)=mysql_fetch_array($result) )
                {
                    $qry2 = "SELECT rankid, rankdesc,image FROM {$spre}rank WHERE rankid=" . $rank;
                    $result2=$database->openConnectionWithReturn($qry2);
                    list($rid,$rname,$rimg)=mysql_fetch_array($result2);

                    $qry2 = "SELECT email FROM {$mpre}users WHERE id = '$pid'";
                    $result2=$database->openConnectionWithReturn($qry2);
                    list($email)=mysql_fetch_array($result2);
                    ?>
                    <tr>
                        <td width="100"><div class="rank">
                            <img src="<?php echo $relpath ?>images/ranks/<?php echo $rimg ?>" alt="<?php echo $rname ?>" border="0" /></div></td>
                        <td width="200"><?php echo $rname . " " . $cname; ?></td>
                        <td width="200"><?php echo $pos ?></td>
                        <td width="100"><?php echo $email ?></td>
                    </tr>
                    <?php
}
            }
            ?>
        </table>
        <br />

        <h5>Crew Information:</h5>

        <div class="form-group">
        	<label for="newcrew">New Crew Since Last Report:</label>
            <textarea class="form-control" name="newcrew" id="newcrew" rows="5" cols="60" wrap="PHYSICAL"></textarea>
        </div>
        <div class="form-group">
        	<label for="removedcrew">Crew Removed Since Last Report:</label>
            <textarea class="form-control" name="removedcrew" id="removedcrew" rows="5" cols="60" wrap="PHYSICAL"></textarea>
        </div>
        <div class="form-group">
        	<label for="promotions">Crew Promotions/Demotions Since Last Report:</label>
            <textarea class="form-control" name="promotions" id="promotions" rows="5" cols="60" wrap="PHYSICAL"></textarea>
        </div>
        
        <h5>Sim Information:</h5>

        <div class="form-group">
        	<label for="mission">Current Mission Title:</label>
        	<textarea class="form-control" name="mission" id="mission" rows="1" cols="60" wrap="PHYSICAL"></textarea>
        </div>
        <div class="form-group">
        	<label for="missdesc">Mission Description:</label>
        	<textarea class="form-control" name="missdesc" id="missdesc" rows="5" cols="60" wrap="PHYSICAL"></textarea>
        </div>
        <div class="form-group">
        	<label for="posts">Number of In-Character Posts This Month:</label>
        	<textarea class="form-control" name="posts" id="posts" rows="1" cols="60" wrap="PHYSICAL"></textarea>
        </div>
        <div class="form-group">
        	<label for="awards">Ship/Website Awards and Awards Given Crew:</label>
        	<textarea class="form-control" name="awards" id="awards" rows="5" cols="60" wrap="PHYSICAL"></textarea>
        </div>

        <h5>Misc Information:</h5>

        <div class="form-group">
        	<label for="comments">Sim Status, Updates and Additional Comments:</label>
        	<textarea class="form-control" name="comments" id="comments" rows="5" cols="60" wrap="PHYSICAL"></textarea>
        </div>
        <div class="form-group">
            <input class="btn btn-success" type="submit" name="Submit" value="Submit Report">
            <input class="btn btn-danger" type="reset" name="Reset" value="Clear Form">
        </div>
    </form>
	<?php
}
?>