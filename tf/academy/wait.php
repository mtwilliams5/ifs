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
  * Comments: List Academy waiting list
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

    <h3>Academy Waiting List</h3>

    <form action="index.php?option=ifs&amp;task=academy&amp;action=save&amp;lib=lupdate" method="post">
    <table class="table academy_list">
      <thead>
    	<tr>
        	<th>Date Submitted</th>
        	<th>Course</th>
        	<th>Character</th>
        	<th>Ship</th>
        	<th>Assign To</th>
        </tr>
      </thead>
	  <tbody>
    <?php
    $qry = "SELECT st.id, st.sdate, co.course, co.name, ch.name, sh.name
            FROM {$spre}acad_students st, {$spre}acad_courses co,
                {$spre}characters ch, {$spre}ships sh
            WHERE st.status='w' AND st.course=co.course AND co.section='0'
                AND st.cid=ch.id AND ch.ship=sh.id
            ORDER BY st.sdate";
    $result = $database->openConnectionWithReturn($qry);
    while (list($sid, $sdate, $cid, $cname, $character, $ship)
        = mysql_fetch_array($result) )
    {
	?>
        <tr>
            <td><?php echo date("F j, Y, g:i a", $sdate) ?></td>
            <td><?php echo $cname ?></td>
            <td><?php echo $character ?></td>
            <td><?php echo $ship ?></td>
            <td>
            	<div class="form-group">
                	<label for="student[<?php echo $sid ?>]" class="sr-only">Assign to instructor</label>
                    <select class="form-control input-sm" name="student[<?php echo $sid ?>]" id="student[<?php echo $sid ?>]">
                        <option value="0" selected="selected">Leave on Wait List</option>
                        <?php
                        $qry2 = "SELECT i.id, c.name
                                 FROM {$spre}acad_instructors i, {$spre}characters c
                                 WHERE i.course='$cid' AND i.cid=c.id AND i.active='1'";
                        $result2 = $database->openCOnnectionWithReturn($qry2);
                        while (list($iid, $iname) = mysql_fetch_array($result2))
                            echo '<option value="' . $iid . '">' . $iname . '</option>';
                        ?>
                    </select>
                </div>
            </td>
        </tr>
        <?php
    }
	?>
    	<tr>
        	<td colspan="5" class="text-right">
            	<input class="btn btn-default" type="submit" value="Update">
    			<span class="help-block">Note that instructors are <em>not</em> automatcally notified of changes;</span>
    			<span class="help-block">You must email them and tell them to check their IFS class lists for updates.</span>
    		</td>
    	</tr>
      </tbody>
    </table>
</form>
<?php
}
?>
