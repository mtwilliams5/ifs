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
  *		matt@mtwilliams.uk
  *
  * Version:	1.17
  * Release Date: June 3, 2004
  * Patch 1.13n:  December 2009
  * Patch 1.14n:  March 2010
  * Patch 1.15n:  April 2010
  * Patch 1.17:   August 2017
  *
  * Copyright (C) 2003-2004 Frank Anon for Obsidian Fleet RPG
  * Distributed under the terms of the GNU General Public License
  * See doc/LICENSE for details
  *
  * This file based on code from Open Positions List
  * Copyright (C) 2002, 2003 Frank Anon
  *
  * Comments: Finds & displays stuff in the OPL
  *
  * See CHANGELOG for further details
 ***/

// if we're searching by class:
if ($srClass || $srName || $srFormat)
{
	// find ships that match the info entered on form
	if ($class=="All" || $ship == "All" || $format == "All") {
#		$qry = "SELECT * FROM {$spre}ships WHERE tf<>'99' ORDER BY name";
		$qry = "SELECT s.* FROM {$spre}ships as s, {$spre}characters as c WHERE s.tf<>'99' AND (c.ship=s.id OR s.co='0') GROUP BY s.name ORDER BY (count(*)*sign(s.co))"; }
    elseif ($class) {
#		$qry = "SELECT * FROM {$spre}ships WHERE class='$class' AND tf<>'99' ORDER BY name";
		$qry = "SELECT s.* FROM {$spre}ships as s, {$spre}characters as c WHERE s.tf<>'99' AND s.class='$class' AND (c.ship=s.id OR s.co='0') GROUP BY s.name ORDER BY (count(*)*sign(s.co))"; }
    elseif ($format) {
#		$qry = "SELECT * FROM {$spre}ships WHERE format='$format' AND tf<>'99' ORDER BY name";
		$qry = "SELECT s.* FROM {$spre}ships as s, {$spre}characters as c WHERE s.tf<>'99' AND s.format='$format' AND (c.ship=s.id OR s.co='0') GROUP BY s.name ORDER BY (count(*)*sign(s.co))"; }
	else {
#		$qry = "SELECT * FROM {$spre}ships WHERE name = '$ship' AND tf<>'99' ORDER BY name";
		$qry = "SELECT s.* FROM {$spre}ships as s, {$spre}characters as c WHERE s.tf<>'99' AND s.name = '$ship' AND (c.ship=s.id OR s.co='0') GROUP BY s.name ORDER BY (count(*)*sign(s.co))"; }
	$result = $database->openConnectionWithReturn($qry);

	// For each ship, list info and available positions
	while ( list($sid,$name,$reg,$class,$site,$co,$xo,$tf,$tg,$status,$image,,,$desc,$format)=mysql_fetch_array($result) )
    {
		$searchres = "1";
		ship_list ($database, $mpre, $spre, $sdb, $uflag, $textonly, "", $sid, $name, $reg, $site, $image, $co, $xo, $status, $class, $format, $tf, $tg, $desc);
		showpos ();
	}
}

// if we're going by position:
elseif ($srPos)
{

	if ($position == "-----Select Position----")
		echo '<h3 class="text-warning">Please select a position!</h3>';
	else
    {
	    $pos = $position;

	    // get all ships
	    if ($pos == "Commanding Officer" || $pos == "Executive Officer")
        {
        	if ($pos == "Commanding Officer")
            {
		        $qry = "SELECT * FROM {$spre}ships WHERE tf<>'99' AND co='0' ORDER BY name";
		        $rank = "";
		        $coname = "Open";
            }
            else {
#		        $qry = "SELECT * FROM {$spre}ships WHERE tf<>'99' AND co <>'0' AND xo='0' ORDER BY name";
			$qry = "SELECT s.* FROM {$spre}ships as s, {$spre}characters as c WHERE s.tf<>'99' AND co <>'0' AND xo='0' AND (c.ship=s.id OR s.co='0') GROUP BY s.name ORDER BY (count(*)*sign(s.co))"; }
	        $result = $database->openConnectionWithReturn($qry);
	        list($sid,$name,$reg,$class,$site,$co,$xo,$tf,$tg,$status,$image,,,$desc,$format)=mysql_fetch_array($result);


	        // print ship info
	        if ($sid)
            {
                $searchres = "1";
	            echo '<h5>The following ships have the <span class="text-success">' . $pos . '</span> position open:</h5>';

	            while ($sid)
                {
	                ship_list ($database, $mpre, $spre, $sdb, $uflag, $textonly, "", $sid, $name, $reg, $site, $image, $co, $xo, $status, $class, $format, $tf, $tg, $desc);

					if ($pos == "Commanding Officer")
						echo '<form action="index.php?option=app&task=co" method="post">';
                    else
						echo '<form action="index.php?option=app" method="post">';
                    ?>
                    <br />
                    <input type="hidden" name="position" value="<?php echo $pos ?>">
                    <input type="hidden" name="ship" value="<?php echo $name ?>">
                    <input class="btn btn-default" type="Submit" value="Apply for this ship"></form>

	                <?php
	                list($sid,$name,$reg,$class,$site,$co,$xo,$tf,$tg,$status,$image,,,$desc,$format)=mysql_fetch_array($result);
	            }
	        }
	    }
        else {
	        $filename = $relpath . "tf/positions.txt";
	        $handel=fopen($filename,'r');
    	    $IsIn=false;
        	while (!feof($handel)) {
				$pos2=(trim(fgets($handel,256)));
				if ($pos==$pos2) { $IsIn=true; }
			}
		fclose($handel);
#	        $qry = "SELECT * FROM {$spre}ships WHERE tf<>'99' AND co<>'0' ORDER BY name";
		$qry = "SELECT s.* FROM {$spre}ships as s, {$spre}characters as c WHERE s.tf<>'99' AND co<>'0' AND c.ship=s.id GROUP BY s.name ORDER BY count(*)";
	        $result = $database->openConnectionWithReturn($qry);

	        echo '<h5>The following ships have the <span class="text-success">' . $pos . '</span> position open:</h5>';

	        while ( list($sid,$name,$reg,$class,$site,$co,$xo,$tf,$tg,$status,$image,,,$desc,$format)=mysql_fetch_array($result) ) {
	        	if ($IsIn) {
				$ShowMe=true;
				$qry2="SELECT ship FROM {$spre}positions WHERE ship='$sid' AND pos='$pos' AND action='rem'";
	            		$result2 = $database->openConnectionWithReturn($qry2);
				if (mysql_num_rows($result2)!=0) { $ShowMe=false; } }
			 else {
				$ShowMe=false;
				$qry2="SELECT ship FROM {$spre}positions WHERE ship='$sid' AND pos='$pos' AND action='add'";
	            		$result2 = $database->openConnectionWithReturn($qry2);
				if (mysql_num_rows($result2)!=0) { $ShowMe=true; } }
				 
                if ($ShowMe) {
                    $searchres = "1";
                    ship_list ($database, $mpre, $spre, $sdb, $uflag, $textonly, "", $sid, $name, $reg, $site, $image, $co, $xo, $status, $class, $format, $tf, $tg, $desc);
                    ?>
                    <br />
                    <form action="index.php?option=app" method="post">
                    <input type="hidden" name="ship" value="<?php echo $name ?>">
                    <input type="hidden" name="position" value="<?php echo $pos ?>">
                    <input class="btn btn-default" type="submit" value="Apply for this ship"></form>
                    <?php
	            }
	        }
	    }
    }
}

if (!$searchres)
    echo '<h5 class="text-info">Sorry, no matches</h5>';
else
{
    echo '<hr />';
    echo '<h5 class="text-success">Done searching.</h5>';
}
?>

<?php
// shows open positions on a ship
function showpos ()
{
	global $database, $sid, $name, $reg, $class, $site, $co, $xo, $tf, $tg, $status, $image, $desc, $position, $relpath, $mpre, $spre,$missiontitle,$missionurl,$genre,$pipgenre;
	?>
	<div class="row openpos-header">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12"><h3>Open Positions:</h3></div>
    </div>
    <div class="row openpos-list">
    <?php
	if ($co == '0')
    {
		echo '<div class="col-xs-12 col-sm-6 col-md-4 col-lg-4"><span class="pos">Commanding Officer</span></div>';
		echo '</div>';
		echo '<form action="index.php?option=app&task=co" method="post">';
	}
    else
    {
		$filename = $relpath . "tf/positions.txt";
		$contents = file($filename);
		$length = sizeof($contents);
		$count = 0;
		$counter = 0;
		do
        {
			$counter = $counter + 1;
			$contents[$counter] = trim($contents[$counter]);

			$pos = addslashes($contents[$counter]);
			$qry2 = "SELECT id FROM {$spre}characters WHERE ship = '$sid' AND pos='$pos'";
			$result2 = $database->openConnectionWithReturn($qry2);

			if (!mysql_num_rows($result2))
            {
				$qry3 = "SELECT action FROM {$spre}positions
                		 WHERE ship = '$sid' AND pos='$pos' AND action='rem'";
				$result3 = $database->openConnectionWithReturn($qry3);

				if (!mysql_num_rows($result3))
                {
					echo '<div class="col-xs-12 col-sm-6 col-md-4 col-lg-4"><span class="pos">' . $contents[$counter] . '</span></div>';
					$count = $count + 1;
				}
			}
		} while ($counter < ($length - 1));
		
		$qry2 = "SELECT pos FROM {$spre}positions WHERE ship = '$sid' AND action = 'add'";
		$result2 = $database->openConnectionWithReturn($qry2);

		while (list ($pos) = mysql_fetch_array($result2) )
        {
			$pos = mysql_real_escape_string($pos);
        	$qry3 = "SELECT id FROM {$spre}characters
            		 WHERE ship='$sid' AND pos='$pos'";
        	$result3 = $database->openConnectionWithReturn($qry3);

            if (!mysql_num_rows($result3))
            {
	            echo '<div class="col-xs-12 col-sm-6 col-md-4 col-lg-4"><span class="pos">' . stripcslashes($pos) . '</span></div>';
	            $count = $count + 1;
            }
		}

		echo '</div>';
		echo '<form action="index.php?option=app" method="post">';
	}
	?>
    <input type="hidden" name="ship" value="<?php echo $name ?>">
    <input class="btn btn-default" type="Submit" value="Apply for this ship"></form>
    <?php
}

?>
