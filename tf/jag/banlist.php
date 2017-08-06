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
	if ($lib != "")
    	include ("tf/jag/banlist2.php");
    else
    {
		// Expire old bans
		$time = time();
		$qry = "SELECT id FROM {$spre}banlist WHERE expire<'$time' AND expire!='0' AND active='1'";
	    $result = $database->openConnectionWithReturn($qry);
	    while (list($bid) = mysql_fetch_array($result))
        {
	    	$qry2 = "UPDATE {$spre}banlist SET active='0' WHERE id='$bid' OR alias='$bid'";
	    	$database->openConnectionNoReturn($qry2);
		}

   	    $auth = get_usertype($database, $mpre, $spre, $cid, $uflag);
		?>
		<h2 class="text-center">Welcome to JAG Banlist Admin</h2>
		<p class="text-center">Please note that your login will time out after about 10 minutes of inactivity.</p>

	    <h1>Add a ban:</h1>
    	<form class="form-horizontal" action="index.php?option=ifs&amp;task=jag&amp;action=bans&amp;lib=badd" method="post">
	    	<div class="form-group">
            	<label for="email" class="col-sm-2 control-label">Email Address:</label>
                <div class="col-sm-10 col-md-6 col-lg-4">
                	<input type="text" class="form-control" name="email" id="email" size="30">
                </div>
            </div>
            <div class="form-group">
    	    	<label for="banip" class="col-sm-2 control-label">IP Address:<span class="help-block">(use * for wildcard)</span></label>
                <div class="col-sm-10 col-md-6 col-lg-4">
                	<input type="text" class="form-control" name="banip" id="banip" size="15" maxlength="15">
        			<span class="help-block"><strong>Both</strong> email <em>and</em> IP are <strong>not</strong> required</span>
                </div>
            </div>
			<div class="form-group">
	        	<label for="reason" class="col-sm-2 control-label">Reason:</label>
    	    	<div class="col-sm-10 col-md-8 col-lg-6">
                	<textarea class="form-control" name="reason" id="reason" rows="5" cols="30"></textarea>
                </div>
            </div>
			<div class="form-group">
            	<label for="length" class="col-sm-2 control-label">Length:</label>
                <div class="col-sm-2">
                	<div class="input-group">
                		<input type="text" class="form-control" name="length" id="length" size="5" aria-describedby="length-addon">
                        <span class="input-group-addon" id="length-addon">days</span>
                    </div>
                    <span class="help-block">(0 for no expiry)</span>
                </div>
			</div>
            <div class="form-group">
            	<label for="level" class="col-sm-2 control-label">Type:</label>
				<div class="col-sm-10 col-md-6 col-lg-4">
                    <select class="form-control" name="level" id="level">
                        <option value="all">Full Ban</option>
                        <option value="command">Ban from Command</option>
                    </select>
                </div>
            </div>
			<div class="form-group">
				<label for="bandate" class="col-sm-2 control-label">Date:</label>
				<p class="form-control-static col-sm-10" id="bandate"><?php echo date("F j, Y", time()) ?></p>
    	    	<input type="hidden" name="bandate" value="<?php echo time() ?>">
            </div>
			<div class="form-group">
				<label for="auth" class="col-sm-2 control-label">Authorized by:</label>
				<p class="form-control-static col-sm-10" id="auth"><?php echo $auth ?></p>
    	    	<input type="hidden" name="auth" value="<?php echo $auth ?>">
            </div>
            <div class="form-group">
	        	<div class="col-sm-10 col-sm-offset-2">
                	<input class="btn btn-default" type="submit" value="Ban!">
                </div>
            </div>
    	</form>
	    <br />

		<h1>Active Bans:</h1>
    	<table class="table table-bordered banlist">
          <thead>
	    	<tr>
	        	<th>Ban ID</th>
    	        <th>Date</th>
        	    <th>Authorized By</th>
            	<th>Email</th>
    	        <th colspan="2">IP</th>
	        </tr>
    	    <tr>
        		<th colspan="3">Reason</th>
                <th>Expiry</th>
                <th colspan="2">Level</th>
	        </tr>
          </thead>
          <tbody>
    	    <tr>
	        	<td colspan="6">&nbsp;</td>
        	</tr>
			<?php
			$qry = "SELECT id, date, auth, reason, expire, level
            		FROM {$spre}banlist WHERE alias='0' AND active='1'";
	    	$result = $database->openConnectionWithReturn($qry);

            if (!mysql_num_rows($result))
			{ ?>
            	<tr>
                	<td colspan="6" class="text-center">We're such a good fleet! Not a single ban!</td>
                </tr>
			<?php
            }

		    while (list ($mid, $mdate, $mauth, $reason, $expire, $level) = mysql_fetch_array($result))
            {
            	if ($expire == '0')
                	$expire = '<strong>Permanent</strong>';
                else
                	$expire = date("F d, Y", $expire);

                if ($level == "all")
                	$level = '<strong>Full Ban</strong>';
                elseif ($level == "command")
                	$level = "Ban from Command";
    	    	?>
				<tr>
            		<td><?php echo $mid ?></td>
                	<td><?php echo date("F j, Y", $mdate) ?></td>
	                <td><?php echo $mauth ?></td>
    	            <td>
        	        	<?php
            	        $qry2 = "SELECT email FROM {$spre}banlist WHERE (alias='$mid' OR id='$mid') AND active='1' ORDER BY date";
                	    $result2 = $database->openConnectionWithReturn($qry2);
                    	while (list ($email) = mysql_fetch_array($result2))
	                    	if ($email != "")
		                    	echo $email . '<br />';
							else
								echo 'No email address listed';
                		?>
	                </td>
    	            <td>
        	        	<?php
            	        $qry2 = "SELECT ip FROM {$spre}banlist WHERE (alias='$mid' OR id='$mid') AND active='1' ORDER BY date";
                	    $result2 = $database->openConnectionWithReturn($qry2);
                    	while (list ($banip) = mysql_fetch_array($result2))
							if ($banip != "")
		                    	echo $banip . '<br />';
							else
								echo 'No IP address listed';
                		?>
	                </td>
    	            <td class="text-center">
        	        	<a role="button" class="btn btn-default btn-sm btn-block" href="index.php?option=ifs&amp;task=jag&amp;action=bans&amp;lib=bdet&amp;bid=<?php echo $mid ?>">Details/Edit</a>
        	        	<a role="button" class="btn btn-default btn-sm btn-block" href="index.php?option=ifs&amp;task=jag&amp;action=bans&amp;lib=bdel&amp;bid=<?php echo $mid ?>">Unban</a>
                	</td>
	            </tr>
		        <tr>
	    	    	<td colspan="3"><?php echo $reason ?></td>
                    <td><?php echo $expire ?></td>
                    <td colspan="2"><?php echo $level ?></td>
    	    	</tr>
	        	<tr>
		        	<td colspan="6">&nbsp;</td>
    		    </tr>
	        <?php
    	    }
			?>
	      </tbody>
        </table>
        <br />
        <hr />

		<h1>Inactive Bans:</h1>
    	<table class="table table-bordered banlist">
          <thead>
	    	<tr>
	        	<th>Ban ID</th>
    	        <th>Date</th>
        	    <th>Authorized By</th>
            	<th>Email</th>
    	        <th colspan="2">IP</th>
	        </tr>
    	    <tr>
        		<th colspan="3">Reason</th>
                <th>Expiry</th>
                <th colspan="2">Level</th>
	        </tr>
          </thead>
          <tbody>
    	    <tr>
	        	<td colspan="6">&nbsp;</td>
        	</tr>
			<?php
			$qry = "SELECT id, date, auth, reason, expire, level
            		FROM {$spre}banlist WHERE alias='0' AND active='0'";
	    	$result = $database->openConnectionWithReturn($qry);

            if (!mysql_num_rows($result))
			{ ?>
            	<tr>
                	<td colspan="6" class="text-center">We're such a good fleet! Not a single ban!</td>
                </tr>
			<?php
            }

		    while (list ($mid, $mdate, $mauth, $reason, $expire, $level) = mysql_fetch_array($result))
            {
            	if ($expire == '0')
                	$expire = '<strong>Permanent</strong>';
                else
                	$expire = date("F d, Y", $expire);

                if ($level == "all")
                	$level = '<strong>Full Ban</strong>';
                elseif ($level == "command")
                	$level = "Ban from Command";
    	    	?>
				<tr>
            		<td><?php echo $mid ?></td>
                	<td><?php echo date("F j, Y", $mdate) ?></td>
	                <td><?php echo $mauth ?></td>
    	            <td>
        	        	<?php
            	        $qry2 = "SELECT email FROM {$spre}banlist WHERE (alias='$mid' OR id='$mid') AND active='0' ORDER BY date";
                	    $result2 = $database->openConnectionWithReturn($qry2);
                    	while (list ($email) = mysql_fetch_array($result2))
	                    	if ($email != "")
		                    	echo $email . '<br />';
							else
								echo 'No email address listed';
                		?>
	                </td>
    	            <td>
        	        	<?php
            	        $qry2 = "SELECT ip FROM {$spre}banlist WHERE (alias='$mid' OR id='$mid') AND active='0' ORDER BY date";
                	    $result2 = $database->openConnectionWithReturn($qry2);
                    	while (list ($banip) = mysql_fetch_array($result2))
							if ($banip != "")
		                    	echo $banip . '<br />';
							else
								echo 'No IP address listed';
                		?>
	                </td>
    	            <td class="text-center">
        	        	<a role="button" class="btn btn-default btn-sm btn-block" href="index.php?option=ifs&amp;task=jag&amp;action=bans&amp;lib=bundel&amp;bid=<?php echo $mid ?>">Activate</a>
                	</td>
	            </tr>
		        <tr>
	    	    	<td colspan="3"><?php echo $reason ?></td>
                    <td><?php echo $expire ?></td>
                    <td colspan="2"><?php echo $level ?></td>
    	    	</tr>
	        	<tr>
		        	<td colspan="6">&nbsp;</td>
    		    </tr>
	        <?php
    	    }
			?>
	      </tbody>
        </table>
        <?php
    }
}
?>