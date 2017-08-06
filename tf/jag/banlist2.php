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
  * Comments: JAG banlist admin
 ***/

if (!defined("IFS"))
	echo "Hacking attempt!";
else
{
	switch ($lib)
    {
    	// Add a ban
    	case "badd":
        	if ($email != "")
            {
	        	$qry = "SELECT id FROM {$spre}banlist WHERE email='$email' AND active='1'";
	            $result = $database->openConnectionWithReturn($qry);
                if (mysql_num_rows($result))
                	$alreadyemail = 1;
            }
        	if ($banip != "")
            {
	        	$qry = "SELECT id FROM {$spre}banlist WHERE ip='$banip' AND active='1'";
	            $result = $database->openConnectionWithReturn($qry);
                if (mysql_num_rows($result))
                	$alreadyip = 1;
            }

			if ($email == "" && $banip == "")
            	echo '<h3 class="text-warning">We need at least the email address <em>or</em> IP address!</h3>';
            elseif ($alreadyemail)
            	echo '<h3 class="text-info">This email is already banned...</h3>';
            elseif ($alreadyip)
            	echo '<h3 class="text-info">This IP is already banned...</h3>';
            else
            {
            	$expire = time() + ($length * 60 * 60 * 24);
				$qry = "INSERT INTO {$spre}banlist SET date='$bandate', auth='$auth', reason='$reason', alias='0', email='$email', ip='$banip', active='1', expire='$expire', level='$level'";
				$database->openConnectionNoReturn($qry);
                ?>

                <h2 class="text-center">Banlist Admin</h2>
                <h4>The following user has been <strong class="text-uppercase">banned</strong>:</h4>
                <strong>Email:</strong> <?php echo $email ?><br />
                <strong>IP:</strong> <?php echo $banip ?><br /><br />
                (<?php echo date("F j, Y", $bandate) ?>, by <?php echo $auth ?>)<br />
                <?php echo stripslashes($reason) ?>
                <br />
                <p class="help-block">All future applications matching this record will be denied.<br />
                Please note that this does not affect any current characters this person
                may have; these need to be removed by the CO/TFCO/FCops/Admin/etc</p>

                <?php
            }
            break;

		//Details on a ban
        case "bdet":
			$qry = "SELECT id, date, auth, reason, active, expire, level
            		FROM {$spre}banlist WHERE id='$bid'";
	    	$result = $database->openConnectionWithReturn($qry);
		    list ($mid, $mdate, $mauth, $reason, $active, $expire, $level) = mysql_fetch_array($result);

			if ($expire == "0")
            	$length = "0";
            else
				$length = round(($expire - time()) / (60*60*24));
   	    	?>
            <h2 class="text-center">Banlist Admin</h2>
			<strong>Ban ID:</strong> <?php echo $mid ?><br />
            <strong>Date of Original Ban:</strong> <?php echo date("F j, Y", $mdate) ?><br />
            <strong>Authorized By:</strong> <?php echo $mauth ?><br /><br />

            <?php
            if ($active == "1")
            	echo '<h3>THIS BAN IS ACTIVE</h3>';
            else
            	echo '<h3>THIS BAN IS INACTIVE</h3>';
            ?>
			<br />
            <form class="form-horizontal" action="index.php?option=ifs&amp;task=jag&amp;action=bans&amp;lib=save" method="post">
	            <div class="form-group">
                	<label for="length" class="col-sm-2 control-label">Ban expires in:</label>
                    <div class="col-sm-3">
                    	<div class="input-group">
                        	<input type="text" class="form-control text-right" name="length" id="length" size="5" value="<?php echo $length ?>">
                            <span class="input-group-addon">days</span>
                        </div>
						<?php
                        if ($expire == "0")
                            echo '<span class="help-block">(that\'s a permanent ban)</span>';
                        else
                            echo '<span class="help-block">(on ' . date("F d, Y", $expire) . ')</span>';
                        ?>
                    </div>
                </div>
				<div class="form-group">
                    <label for="level" class="col-sm-2 control-label">Ban type:</label>
                    <div class="col-sm-10 col-md-6 col-lg-4">
                        <select class="form-control" name="level" id="level">
                            <option value="all"<?php if ($level == "all") echo ' selected="selected"' ?>>Full Ban</option>
                            <option value="command"<?php if ($level == "command") echo ' selected="selected"' ?>>Ban from Command</option>
                        </select>
                    </div>
	            </div>
				<div class="form-group">
            		<label for="reason" class="col-sm-2 control-label">Reason:</label>
                    <div class="col-sm-10 col-md-8 col-lg-6">
	    	    		<textarea class="form-control" name="reason" id="reason" rows="5" cols="30"><?php echo $reason ?></textarea>
                	</div>
                </div>
                <input type="hidden" name="bid" value="<?php echo $mid ?>">
            	<div class="form-group">
                	<div class="col-sm-10 col-sm-offset-2">
                		<input class="btn btn-default" type="submit" value="Update">
                	</div>
                </div>
            </form>

            <h3>Email Addresses:</h3>
            <ul class="list-group">
            <?php
            $qry = "SELECT email, date, auth FROM {$spre}banlist
            		WHERE alias='$bid' OR id='$bid' ORDER BY date";
            $result = $database->openConnectionWithReturn($qry);
            while (list ($email, $date, $auth) = mysql_fetch_array($result))
               	if ($email != "")
                {
				?>
                	<li class="list-group-item">
                   		<h4 class="list-group-item-heading"><?php echo $email ?></h4>
                    	<p class="list-group-item-text">Added on <?php echo date("F j, Y", $date) ?></p>
                        <p class="list-group-item-text">by <?php echo $auth ?></p>
                	</li>
				<?php
                }
				else
				{
					echo '<h4>No email addresses listed.</h4>';
				}
    	    $auth = get_usertype($database, $mpre, $spre, $cid, $uflag);
            ?>
            </ul>

            <form class="form-inline" action="index.php?option=ifs&amp;task=jag&amp;action=bans&amp;lib=semail" method="post">
            	<div class="form-group">
                	<label for="email">Add:</label> <input type="text" class="form-control" name="email" id="email" size="30">
                </div><br />
                <div class="form-group">
                	<label for="auth">Authorized by:</label> <p class="form-control-static" id="auth"><?php echo $auth ?></p>
            		<input type="hidden" name="auth" value="<?php echo $auth ?>">
                </div><br />
                <div class="form-group">
                	<label for="bandate">Date:</label> <p class="form-control-static" id="bandate"><?php echo date("F j, Y", time()) ?></p>
	    	    	<input type="hidden" name="bandate" value="<?php echo time() ?>">
                </div><br />
	    	    <input type="hidden" name="bid" value="<?php echo $bid ?>">
                <input class="btn btn-default" type="submit" value="Add Alias">
            </form>

			<h3>IP Addresses:</h3>
            <ul class="list-group">
            <?php
            $qry = "SELECT ip, date, auth FROM {$spre}banlist
            		WHERE alias='$bid' OR id='$bid' ORDER BY date";
            $result = $database->openConnectionWithReturn($qry);
            while (list ($banip, $date, $auth) = mysql_fetch_array($result))
               	if ($banip != "")
                {
				?>
                	<li class="list-group-item">
                   		<h4 class="list-group-item-heading"><?php echo $banip ?></h4>
                    	<p class="list-group-item-text">Added on <?php echo date("F j, Y", $date) ?></p>
                        <p class="list-group-item-text">by <?php echo $auth ?></p>
                	</li>
				<?php
                }
				else
				{
					echo '<h4>No IP addresses listed.</h4>';
				}
    	    $auth = get_usertype($database, $mpre, $spre, $cid, $uflag);
            ?>
            </ul>

            <form class="form-inline" action="index.php?option=ifs&amp;task=jag&amp;action=bans&amp;lib=sip" method="post">
            	<div class="form-group">
                	<label for="banip">Add:</label> <input type="text" class="form-control" name="banip" id="banip" size="15" maxlength="15">
               	</div><br />
                <div class="form-group">
                	<label for="auth">Authorized by:</label> <p class="form-control-static" id="auth"><?php echo $auth ?></p>
            		<input type="hidden" name="auth" value="<?php echo $auth ?>">
                </div><br />
                <div class="form-group">
                	<label for="bandate">Date:</label> <p class="form-control-static" id="bandate"><?php echo date("F j, Y", time()) ?></p>
	    	    	<input type="hidden" name="bandate" value="<?php echo time() ?>">
                </div><br />
	    	    <input type="hidden" name="bid" value="<?php echo $bid ?>">
                <input class="btn btn-default" type="submit" value="Add Alias">
            </form>
            <?php
        	break;


        case "bdel":
        	$qry = "UPDATE {$spre}banlist SET active='0' WHERE id='$bid' OR alias='$bid'";
            $database->openConnectionNoReturn($qry);
			echo '<h1 class="text-center">Banlist Admin</h1>';
            echo '<h4 class="text-success">The user has been <strong class="text-uppercase">unbanned</strong>.</h4>';

			$lib = "";
            include("tf/jag/banlist.php");
            break;


        case "bundel":
        	$qry = "SELECT expire FROM {$spre}banlist WHERE id='$bid'";
            $result = $database->openConnectionWithReturn($qry);
            list ($expire) = mysql_fetch_array($result);

            if ($expire < time())
            	$expire = "0";

        	$qry = "UPDATE {$spre}banlist SET active='1', expire='$expire'
            		WHERE id='$bid' OR alias='$bid'";
            $database->openConnectionNoReturn($qry);
			echo '<h1 class="text-center">Banlist Admin</h1>';
            echo '<h4 class="text-success">The user has been <strong class="text-uppercase">banned</strong>.</h4>';

			$lib = "";
            include("tf/jag/banlist.php");
            break;


        case "semail":
        	$qry = "SELECT id FROM {$spre}banlist WHERE email='$email'";
            $result = $database->openConnectionWithReturn($qry);
            if (mysql_num_rows($result))
              	echo '<h4 class="text-info">This email is already banned...</h4>';
            else
            {
	        	$qry = "INSERT INTO {$spre}banlist
                		SET date='$bandate', auth='$auth', alias='$bid',
                        	email='$email', active='1'";
				$database->openConnectionNoReturn($qry);
				redirect("&amp;action=bans&amp;lib=bdet&amp;bid={$bid}");
            }
            break;


        case "sip":
        	$qry = "SELECT id FROM {$spre}banlist WHERE ip='$banip'";
            $result = $database->openConnectionWithReturn($qry);
            if (mysql_num_rows($result))
              	echo '<h4 class="text-info">This IP is already banned...</h4>';
            else
            {
	        	$qry = "INSERT INTO {$spre}banlist
                		SET date='$bandate', auth='$auth', alias='$bid',
                        	ip='$banip', active='1'";
				$database->openConnectionNoReturn($qry);
                redirect("&amp;action=bans&amp;lib=bdet&amp;bid={$bid}");
            }
            break;


        case "save":
        	$length = time() + ($length * 60 * 60 * 24);
			$qry = "UPDATE {$spre}banlist
            		SET reason='$reason', expire='$length', level='$level'
                    WHERE id='$bid'";
            $database->openConnectionNoReturn($qry);
			redirect("&amp;action=bans&amp;lib=bdet&amp;bid={$bid}");
    }

}
?>