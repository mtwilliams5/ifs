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
  *
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
  * This file contains code from Mambo Site Server 4.0.12
  * Copyright (C) 2000 - 2002 Miro International Pty Ltd
  *
  * Comments: List individual instructors' class list
  *
  * See CHANGELOG for patch details
  *
 ***/

if (!defined("IFS"))
	echo "Hacking attempt!";
else
{
	?>
	<h2 class="text-center">Welcome to Academy Tools</h2>
	<p class="text-center">Please note that your login will time out after about 10 minutes of inactivity.</p>

	<?php
	
	if ($uflag['d'] == 2 && $lib) {
	//Can be expanded at a later date to include all instructors when logged as admin	
	//	if($lib) {
			$pid = $lib;
	//	}
	} else {
		$pid = UID;
	}
	//Fetch name of instructor
	$qry = "SELECT c.name FROM {$spre}characters c, {$spre}acad_instructors i
			WHERE i.cid=c.id AND i.pid='$pid'";
	$result = $database->openConnectionWithReturn($qry);
	list ($iname) = mysql_fetch_array($result);
	//

    echo '<h3>Instructor Class List';
	if ($iname) echo ' - ' . $iname;
	echo '</h3>';
	?>

    <form class="form-inline" action="index.php?option=ifs&amp;task=academy&amp;action=save&amp;lib=inst&amp;iid=<?php echo $pid; ?>" method="post">
    <table class="table academy_list">
      <thead>
	    <tr>
          <th>Date Submitted</th>
          <th>Course</th>
          <th>Section</th>
          <th>Character</th>
          <th>Ship</th>
          <th>CO</th>
    	</tr>
      </thead>
      <tbody>

    <?php
	//this query has been rewritten so i understand it
	$qry = "SELECT st.id, st.sdate, co.course, co.name, ch.id, ch.name, sh.name,
    			c2.id, c2.name, u.email, MAX(m.section), ch.player, u2.email
			FROM {$spre}acad_students st 
			LEFT JOIN {$spre}acad_instructors i ON(i.id = st.inst) 
			LEFT JOIN {$spre}acad_courses co ON(st.course = co.course) 
			LEFT JOIN {$spre}acad_marks m ON(m.sid = st.id) 
			LEFT JOIN {$spre}characters ch ON(ch.id = st.cid) 
			LEFT JOIN {$mpre}users u ON(u.id = ch.player)
			LEFT JOIN {$spre}ships sh ON(sh.id = ch.ship) 
			LEFT JOIN {$spre}characters c2 ON(sh.co=c2.id) 
			LEFT JOIN {$mpre}users u2 ON(c2.player = u2.id)
			WHERE co.section='0' AND st.status = 'p'";
	if(isset($iid)) {$pid = $iid; }
	if(isset($pid)) { $qry .= " AND i.pid = '$pid'"; }
	$qry .= " GROUP BY co.course, st.sdate";
/*            FROM {$spre}acad_students st, {$spre}acad_courses co, {$mpre}users u,
                {$spre}characters ch, {$spre}ships sh, {$spre}characters c2,
                {$spre}acad_marks m, {$spre}acad_instructors i, 
              WHERE st.status='p' AND st.inst=i.id AND st.course=co.course
            	AND co.section='0' AND st.cid=ch.id AND ch.ship=sh.id AND ch.player=u.id
                AND sh.co=c2.id AND c2.player=u.id AND m.sid=st.id AND i.pid='$pid'
              GROUP BY co.course, st.sdate";
*/
    $result = $database->openConnectionWithReturn($qry);

	if (!mysql_num_rows($result))
		$noUpdateButton = 1;

    while (list($sid, $sdate, $cid, $cname, $chid, $character, $ship,
    	$coid, $co, $chemail, $secid, $pid, $coemail) = mysql_fetch_array($result) )
    {
	if($sid != "") {
    	$secid++;
        $qry2 = "SELECT name FROM {$spre}acad_courses
        		 WHERE course='$cid' AND section='$secid'";
        $result2 = $database->openConnectionWithReturn($qry2);
		list ($secname) = mysql_fetch_array($result2);
		?>
        <tr>
        	<td rowspan="2"><?php echo date("F j, Y", $sdate) ?></td>
        	<td rowspan="2"><?php echo $cname ?></td>
        	<td rowspan="2"><?php echo $secid . ' - ' . $secname ?></td>
        	<td rowspan="2"><?php echo $character . '<br />' .$chemail ?></td>
        	<td rowspan="2"><?php echo $ship ?></td>
		<?php
		if ($chid == $coid)
        {
			$qrytf = "SELECT c.name, u.email, s.tf, r.rankdesc
					FROM {$spre}characters c, {$mpre}users u, {$spre}ships s, {$spre}taskforces t, {$spre}rank r
					WHERE s.co='$coid' AND s.tf=t.tf AND t.tg='0' AND t.co=c.id AND c.player=u.id AND c.rank=r.rankid";
			$resulttf = $database->openConnectionWithReturn($qrytf);
			list ($tfco, $tfcoemail, $tfid, $tfcorank) = mysql_fetch_array($resulttf);
			
			$qrytg = "SELECT c.name, u.email, s.tg, r.rankdesc
					FROM {$spre}characters c, {$mpre}users u, {$spre}ships s, {$spre}taskforces t, {$spre}rank r
					WHERE s.co='$coid' AND s.tf=t.tf AND t.tg=s.tg AND t.co=c.id AND c.player=u.id AND c.rank=r.rankid";
			$resulttg = $database->openConnectionWithReturn($qrytg);
			list ($tgco, $tgcoemail, $tgid, $tgcorank) = mysql_fetch_array($resulttg);
			
        	/*$qry2 = "SELECT c1.name, u1.email, c2.name, u2.email, s.tf
            		 FROM {$spre}characters c1, {$spre}characters c2,
                     	{$mpre}users u1, {$mpre}users u2, {$spre}ships s,
                        {$spre}taskforces t1, {$spre}taskforces t2
                     WHERE s.co='$coid' AND s.tf=t1.tf AND t1.tg='0'
                     	AND s.tf=t2.tf AND s.tg=t2.tg AND t1.co=c1.id
                        AND t2.co=c2.id AND c1.player=u1.id AND c2.player=u2.id";
            $result2 = $database->openConnectionWithReturn($qry2);
            list ($otfco, $otfcoemail, $tgco, $tgcoemail, $otfid)
            	= mysql_fetch_array($result2); */

            echo '<td>TFCO: ' . $tfcorank . ' ' . $tfco . ' ' . $tfcoemail;
            echo '<br />TGCO: ' . $tgcorank . ' ' . $tgco . ' ' . $tgcoemail; //We have no TGCOs, so line isn't needed
			echo '</td>';
        }
		else if ($ship == "Unassigned Characters" || $ship == "Deleted Characters" || $ship == "Transferred Characters")
		{
			if (preg_match('/Commanding Officer/',$cname))
			{
				echo '<td>Chief of Fleet Operations: ' . $fleetopsemail . '</td>';
			}
			else
			{
				//If the course isn't a Commanding Officer one, let's default to the Academy Commandant
				echo '<td>Academy Commandant: ' . $academail . '</td>';
			}
		}
        else
        	echo '<td>CO: ' . $co . ' ' . $coemail . '</td>';
        ?>
        </tr>
        <tr>
            <td rowspan="2">
                <a role="button" class="btn btn-default btn-sm btn-block" href="index.php?option=ifs&amp;task=academy&amp;action=common&amp;lib=cacad&amp;pid=<?php echo $pid ?>">View Academy Records</a>
                <a role="button" class="btn btn-default btn-sm btn-block" href="index.php?option=ifs&amp;task=academy&amp;action=common&amp;lib=rview&amp;pid=<?php echo $pid . "&amp;cid=$chid" ?>">View Service Record</a>
            </td>
        </tr>
        <tr>
            <td colspan="5">
            	<div class="form-group">
                	<label for="stupdate[<?php echo $sid ?>]" class="sr-only">Update Status:</label>
                    <select class="form-control input-sm" name="stupdate[<?php echo $sid ?>]" id="stupdate[<?php echo $sid ?>]">
                        <option value="1" selected="selected">No change</option>
                        <option value="2">Complete Section</option>
                        <option value="3">Fail Course</option>
                        <option value="4">Drop Out</option>
                    </select>
                </div>
                &nbsp;
                <div class="form-group">
                	<label for="mark[<?php echo $sid ?>]">Grade:</label> 
                    <input type="textbox" class="form-control input-sm" name="mark[<?php echo $sid ?>]" id="mark[<?php echo $sid ?>]" size="3">
                </div>
                <span class="help-block">To graduate from the course, complete the last section called "Graduation".</span>
            </td>
        </tr>
        <tr>
        	<td colspan="6">
            	<hr />
            </td>
        </tr>
        <?php
	} else { $noUpdateButton = 1; }
    }
	if (!$noUpdateButton)
	{
	?>
	    <tr>
        	<td><input class="btn btn-default btn-block" type="submit" value="Update"></td>
        </tr>
	<?php
	}
	?>
      </tbody>
    </table>
    </form>
    <?php
	if ($uflag['d'] ==2)
	{
		?>
    <br />
		<form action="index.php?option=ifs&amp;task=academy&amp;action=inst" method="post">
			<div class="form-group">
        		<label for="lib">Switch Instructor:</label>
                <div class="row">
                    <div class="col-sm-8 col-md-4">
                        <select class="form-control" name="lib" id="lib">
                            <option value=""></option>
                            <?php
                            $qry = "SELECT DISTINCT i.pid, c.name
                                    FROM {$spre}acad_instructors i, {$spre}characters c
                                    WHERE i.active='1' AND c.id=i.cid";
                            $result = $database->openConnectionWithReturn($qry);
                            while (list($ipid, $iname) = mysql_fetch_array($result))
                                if ($ipid == $lib)
                                    echo "<option value=\"$ipid\" selected=\"selected\">$iname</option>\n";
                                else if ($ipid == uid && !$lib)
                                    echo "<option value=\"$ipid\" selected=\"selected\">$iname</option>\n";
                                else
                                    echo "<option value=\"$ipid\">$iname</option>\n";
                            ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="form-group">
				<input class="btn btn-default" type="submit" value="Switch">
            </div>
        </form>
    <?php
	}
}
?>
