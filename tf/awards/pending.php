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
  * Comments: View Pending Awards to approve/deny them
 ***/

if (!defined("IFS"))
	echo "Hacking attempt!";
else
{
	echo '<h1 class="text-center">Awards Admin</h1>';
	echo '<br />';
	if (!$save)
    {
		$qry = "SELECT a.id, a.date, a.nominator, b.name, c.name, s.name, a.reason
	    		FROM {$spre}awardees a, {$spre}awards b, {$spre}characters c, {$spre}ships s
	            WHERE a.award=b.id AND a.recipient=c.id AND c.ship=s.id AND a.approved='1'
	            ORDER BY date";
	    $result = $database->openConnectionWithReturn($qry);

	    ?>
	    <table class="table award-list">
          <thead>
	    	<tr>
	        	<th>Date Submitted</th>
	            <th>Award</th>
	            <th>Character</th>
	            <th colspan="2">Ship</th>
	        </tr>
            <tr>
            	<th colspan="5">Reason Given</th>
            </tr>
          </thead>
          <tbody>

	        <?php
            while (list($aid, $date, $nominator, $aname, $cname, $sname, $reason) = mysql_fetch_array($result))
            {
            	?>
	        	<tr>
	            	<td><?php echo date("F j, Y", $date) ?></td>
	                <td><?php echo $aname ?></td>
					<td><?php echo $cname ?></td>
	                <td><?php echo $sname ?></td>
	                <td rowspan="2" class="vcenter">
	                	<form action="index.php?option=ifs&amp;task=awards&amp;action=pending&amp;save=<?php echo $aid ?>" method="post">
                            <input type="hidden" name="approve" value="approve">
                            <input class="btn btn-success btn-block" type="submit" value="Approve">
	                    </form>
                        <br />
	                	<form action="index.php?option=ifs&amp;task=awards&amp;action=pending&amp;save=<?php echo $aid ?>" method="post">
                            <input type="hidden" name="approve" value="deny">
                            <input class="btn btn-danger btn-block" type="submit" value="Reject">
	                    </form>
	                </td>
	            </tr>
	            <tr>
	                <td colspan="4">
                    	<p><?php echo $reason ?></p>
                        <strong>Submitted By:</strong> <?php echo $nominator ?>
                    </td>
                </tr>
                <tr>
                	<td colspan="5"><hr /></td>
                </tr>
            	<?php
            }
			?>
          </tbody>
        </table>
        <?php
    }
    else
    {
    	if ($approve == "approve")
        {
	    	$qry = "UPDATE {$spre}awardees SET approved='2' WHERE id='$save'";
            $database->openConnectionNoReturn($qry);

		    $qry = "SELECT a.date, a.nemail, b.name, r.rankdesc, c.name, s.name
		    	 	FROM {$spre}rank r, {$spre}characters c, {$spre}ships s, {$spre}awardees a, {$spre}awards b
		            WHERE a.award=b.id AND a.recipient=c.id AND a.rank=r.rankid AND a.ship=s.id AND a.id='$save'";
		    $result = $database->openConnectionWithReturn($qry);
            list ($date, $email, $aname, $rank, $cname, $sname) = mysql_fetch_array($result);

		   	require_once "includes/mail/award_accepted.mail.php";
        }
        elseif ($approve == "deny")
        {
	    	$qry = "UPDATE {$spre}awardees SET approved='0' WHERE id='$save'";
            $database->openConnectionNoReturn($qry);

		    $qry = "SELECT a.date, a.nemail, b.name, r.rankdesc, c.name, s.name
		    	 	FROM {$spre}rank r, {$spre}characters c, {$spre}ships s, {$spre}awardees a, {$spre}awards b
		            WHERE a.award=b.id AND a.recipient=c.id AND a.rank=r.rankid AND a.ship=s.id AND a.id='$save'";
		    $result = $database->openConnectionWithReturn($qry);
            list ($date, $email, $aname, $rank, $cname, $sname) = mysql_fetch_array($result);

		   	require_once "includes/mail/award_denied.mail.php";
		}
        redirect("");
    }
}
?>
