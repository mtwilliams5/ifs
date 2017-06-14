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
  *             matt@mtwilliams.uk
  *
  * Version:	1.17
  * Release Date: June 3, 2004
  * Patch 1.17:   June 2017
  *
  * Copyright (C) 2003-2004 Frank Anon for Obsidian Fleet RPG
  * Distributed under the terms of the GNU General Public License
  * See doc/LICENSE for details
  *
  * Comments: Functions for Fleet Awards
  *
 ***/

// Get award description page
function awards_detail ($database, $mpre, $spre, $award)
{
	$qry = "SELECT id, name, level, active, image, intro, descrip
    		FROM {$spre}awards WHERE id='$award'";
    $result = $database->openConnectionWithReturn($qry);
    list ($id, $name, $level, $active, $image, $intro, $descrip) = mysql_fetch_array($result);

    $qry = "SELECT a.id, a.date, a.rank, r.rankdesc, c.name, s.name
    	 	FROM {$spre}rank r, {$spre}characters c, {$spre}ships s, {$spre}awardees a
            WHERE a.award='$id' AND a.recipient=c.id AND (a.rank=r.rankid OR (a.rank='0'
            	AND r.rankid='1')) AND a.ship=s.id AND a.approved='2'
            ORDER BY a.date DESC LIMIT 0,10";
    $result = $database->openConnectionWithReturn($qry);

    echo '<h1>' . $name . '</h1>';
    echo '<h4>Level ' . $level . ' Award</h4>';
    if ($active < '1')
    	echo '<h4 class="text-uppercase"><em>Discontinued</em></h4>';
    if ($image)
    	echo '<div class="award-image"><img class="img-responsive" src="awards/' . $image . '" alt="' . $name . '"></div>';

    echo '<p class="lead">' . $intro . '</p>';
    echo '<p>' . $descrip . '</p>';

	if (mysql_num_rows($result))
    {
	    echo '<h2>Recent Recipients</h2>';
		echo '<ul class="list-unstyled">';
	    while (list ($rid, $rdate, $rankid, $rrank, $rname, $sname) = mysql_fetch_array($result)) {
        	if ($rdate != '0')
	        	$rdate = date("F j, Y", $rdate) . ": ";
            else
            	$rdate = "";

            if ($rankid == '0')
            	$rrank = "";

	    	echo '<li>' . $rdate . '<a href="index.php?option=' . option . '&action=' . action . '&task=common&lib=areason&rid=' . $rid . '">' . stripcslashes($rrank) . ' ' . stripcslashes($rname) . ', ' . stripcslashes($sname) . '</a></li>';
		}
		echo '</ul>';
	    echo '<a href="index.php?option=' . option . '&action=' . action . '&task=common&lib=arecip&award=' . $id . '">View All Recipients</a>';
	}
}

// General page listing all awards plus short intro
function awards_list ($database, $mpre, $spre)
{
   	echo "<h1>Awards</h1>\n";

    $qry = "SELECT level FROM {$spre}awards GROUP BY level ORDER BY level ASC";
    $result = $database->openConnectionWithReturn($qry);
    while (list($level) = mysql_fetch_array($result))
    {
		echo '<h2>Level ' . $level . ' Awards</h2>';
		echo '<ul class="list-unstyled">';
		$qry2 = "SELECT id, name, intro FROM {$spre}awards
        		 WHERE active='1' AND level='$level' ORDER BY name";
	    $result2 = $database->openConnectionWithReturn($qry2);
	    while (list($id, $award, $intro) = mysql_fetch_array($result2)) {
	        echo '<li><a href="index.php?option=' . option . '&task=' . task . '&action=common&lib=adet&award=' . $id . '">';
			echo '<h4>' . $award . '</h4>';
			echo '<p>' . $intro . '</p></a></li>';
		}
	    echo '</ul>';
    }

	$qry = "SELECT id, name, intro, level FROM {$spre}awards WHERE active<'1' ORDER BY level, name";
    $result = $database->openConnectionWithReturn($qry);
	if (mysql_num_rows) echo '<h3>Discontinued Awards</h3><ul class="list-unstyled">';
    while (list($id, $award, $intro, $level) = mysql_fetch_array($result)) {
        echo '<li><a href="index.php?option=' . option . '&task=' . task . '&action=common&lib=adet&award=' . $id . '">';
		echo '<h4><span class="text-muted">' . $award . ' <small>- Level ' . $level . '</small></span></h4>';
		echo '<p class="text-muted">' . $intro . '</p></a></li>';
	}
    echo '</ul>';
}

// Award page for recipients
function awards_reason ($database, $mpre, $spre, $rid)
{
    $qry = "SELECT a.date, a.reason, a.rank, b.name, b.image, r.rankdesc, r.level, c.id, c.name, s.name
    	 	FROM {$spre}rank r, {$spre}characters c, {$spre}ships s, {$spre}awardees a, {$spre}awards b
            WHERE a.id='$rid' AND a.recipient=c.id
            	AND (a.rank=r.rankid OR(a.rank='0' AND r.rankid='1'))
                AND a.ship=s.id AND a.award=b.id
            ORDER BY a.date DESC";
    $result = $database->openConnectionWithReturn($qry);
    list ($rdate, $reason, $rankid, $aname, $image, $rrank, $ranklev, $cid, $rname, $sname)
    	= mysql_fetch_array($result);

    $qry = "SELECT r.level, r.rankdesc, s.name
    		FROM {$spre}rank r, {$spre}ships s, {$spre}characters c
            WHERE c.id='$cid' AND c.rank=r.rankid AND c.ship=s.id";
    $result = $database->openConnectionWithReturn($qry);
    list ($nowranklev, $nowrank, $nowship) = mysql_fetch_array($result);

    if ($rankid == '0')
    {
    	$rrank = "";
        $ranklev = $nowranklev;
        $nowrank = "";
    }
    if ($rdate == '0')
    	$rdate = "";
    else
		$rdate = date("F j, Y", $rdate);

    echo '<h3>On ' . $rdate . '<br />';
    echo $rrank . ' ' . $rname . ' <small><em>(' . $sname . ')</em></small><br />';
    echo 'received the:</h3>';
    echo '<h1>' . $aname . '</h1>';
    echo '<div class="award-image"><img src="awards/' . $image . '" alt="' . $aname . '"></div>';

    if ($reason)
    {
	    echo '<h4>with the following reason:</h4>';
	    echo '<p class="lead">' . $reason . '</p>';
    }

    if ($ranklev < $nowranklev)
    	echo '<p class="text-info">Since receiving this award, ' . $rname . ' has been promoted to ' . $nowrank . '.</p>';
    elseif ($ranklev > $nowranklev)
    	echo '<p class="text-muted">Since receiving this award, ' . $rname . ' has been demoted to ' . $nowrank . '.</p>';
    elseif ($rrank != $nowrank)
    	echo '<p class="text-info">Since receiving this award, ' . $rname . '\'s rank changed to ' . $nowrank . '.</p>';

    if ($sname != $nowship)
    	echo '<p class="text-info">Since receiving this award, ' . $rname . ' transferred to ' . $nowship . '.</p>';
}

// List recipients of award
function awards_recipients($database, $mpre, $spre, $award)
{
	$qry = "SELECT name, level, active FROM {$spre}awards WHERE id='$award'";
    $result = $database->openConnectionWithReturn($qry);
    list ($name, $level, $active) = mysql_fetch_array($result);

    $qry = "SELECT a.id, a.date, a.rank, r.rankdesc, c.name, s.name
    	 	FROM {$spre}rank r, {$spre}characters c, {$spre}ships s, {$spre}awardees a
            WHERE a.award='$award' AND a.recipient=c.id
            	AND (a.rank=r.rankid OR (a.rank='0' AND r.rankid='1'))
                AND c.ship=s.id AND a.approved='2'
            ORDER BY a.date DESC";
    $result = $database->openConnectionWithReturn($qry);

    echo '<h1>' . $name . '</h1>';
    echo '<h4>Level ' . $level . ' Award</h4>';
    if ($active < '1')
    	echo '<h4 class="text-uppercase"><em>Discontinued</em></h4>';

	echo '<h2>Recipients</h2>';
	echo '<ul class="list-unstyled">';
    while (list ($rid, $rdate, $rankid, $rrank, $rname, $sname) = mysql_fetch_array($result))
    {
       	if ($rdate != '0')
        	$rdate = date("F j, Y", $rdate) . ": ";
        else
           	$rdate = "";

        if ($rankid == '0')
           	$rrank = "";

    	echo '<li>' . $rdate . '<a href="index.php?option=' . option . '&action=' . action . '&task=common&lib=areason&rid=' . $rid . '">' . stripcslashes($rrank) . ' ' . stripcslashes($rname) . ', ' . stripcslashes($sname) . '</a></li>';
	}
	echo '</ul>';
}

?>