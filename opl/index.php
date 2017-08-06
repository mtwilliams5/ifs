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
  * Comments: Open Positions List main page
  *
  * See CHANGELOG for patch details
 ***/


if (!defined("IFS"))
	redirect("index.php?option=opl");

?>
<h1 class="text-center">Open Positions List</h1>
<?php

switch ($task)
{
	case "find":
		include ("find.php");
		break;
	case "about":
		include ("about.php");
		break;
	default:
	    ?>

	    <h5 class="text-center">Search by:</h5>
        <div class="btn-group btn-group-justified">
        <a role="button" class="btn btn-default btn-sm" href="#class">Class</a>
        <a role="button" class="btn btn-default btn-sm" href="#format">Format</a>
        <a role="button" class="btn btn-default btn-sm" href="#name">Name</a>
        <a role="button" class="btn btn-default btn-sm" href="#pos">Position</a>
        </div>
        <br />

	    <a name="class" style="text-decoration: none;">
	    <h4>Search by class:</h4>
	    <form class="form-inline" action="index.php?option=opl&amp;task=find" method="post">
            <div class="form-group">
                <label for="class" class="sr-only">Class</label>
                <select class="form-control input-sm" id="class" name="class">
                    <option selected="selected" value="All">All</option>
                    <?php
                    $qry = "SELECT c.name
                            FROM {$sdb}classes c, {$sdb}types t, {$sdb}category d, {$spre}ships s
                            WHERE c.active='1' AND c.category=d.id AND d.type=t.id AND t.support='n' AND c.name=s.class AND s.tf<>'99'
                            GROUP By s.class
                            ORDER BY c.name";
                    $result = $database->openShipsWithReturn($qry);
                    while ( list ($sname) = mysql_fetch_array($result) )
                        echo '<option value="' . $sname . '">' . $sname . '</option>';
                    ?>
                </select>
            </div>
            <input type="hidden" name="srClass" id="srClass" value="yes">
            <input class="btn btn-default btn-sm" type="submit" value="Search" />
            <input class="btn btn-default btn-sm" type="reset" value="Reset" />
	    </form></a>

        <br /><br />

		<a name="format" style="text-decoration: none;">
	    <h4>Search by format:</h4>
	    <form class="form-inline" action="index.php?option=opl&task=find" method="post">
            <div class="form-group">
                <label for="format" class="sr-only">Format</label>
                <select class="form-control input-sm" name="format" id="format">
                    <option selected="selected" value="All">All</option>
                    <?php
                    $qry = "SELECT DISTINCT(format) FROM {$spre}ships WHERE tf<>'99' ORDER BY format ASC";
                    $result = $database->openShipsWithReturn($qry);
                    while ( list ($sname) = mysql_fetch_array($result) )
                        echo '<option value="' . $sname . '">' . $sname . '</option>';
                    ?>
                </select>
            </div>
            <input type="hidden" name="srFormat" id="srFormat" value="yes">
            <input class="btn btn-default btn-sm" type="submit" value="Search">
            <input class="btn btn-default btn-sm" type="reset" value="Reset">
	    </form></a>

        <br /><br />

	    <a name="name" style="text-decoration: none;">
	    <h4>Search by ship name:</h4>
	    <form class="form-inline" action="index.php?option=opl&task=find" method="post">
            <div class="form-group">
                <label for="ship" class="sr-only">Ship Name</label>
                <select class="form-control input-sm" name="ship" id="ship">
                    <option value="All" selected="selected">All</option>
                    <?php
                    $qry = "SELECT name FROM {$spre}ships WHERE tf<>'99' ORDER BY name";
                    $result = $database->openConnectionWithReturn($qry);
                    while ( list ($sname) = mysql_fetch_array($result) )
                        echo "<option value=\"{$sname}\">$sname</option>";
                    ?>
                </select>
            </div>
            <input type="hidden" name="srName" id="srName" value="yes">
            <input class="btn btn-default btn-sm" type="submit" value="Search" />
            <input class="btn btn-default btn-sm" type="reset" value="Reset" />
	    </form></a>

	    <br /><br />

	    <a name="pos" style="text-decoration: none;">
	    <h4>Search by Position</h4>
	    <form class="form-inline" action="index.php?option=opl&task=find" method="post">
            <div class="form-group">
                <label for="position" class="sr-only">Position</label>
                <select class="form-control input-sm" name="position" id="position">
                    <option selected="selected" value="-----Select Position----">-----Select Position----</option>
                    <?php
                    $filename = $relpath . "tf/positions.txt";
                    $handel=fopen($filename,'r');
                    $len=0;
                    while (!feof($handel)) {
                        $len++;
                        $pos[$len]=trim(trim(trim(fgets($handel,256)," "),chr(10)),chr(13));
                        if (strlen($pos[$len])<3) $len--;
					}
                    $qry = "SELECT p.pos FROM {$spre}positions AS p, {$spre}ships AS s WHERE p.ship=s.id AND s.tf<>'99' AND p.action='add' GROUP BY p.pos ORDER BY p.pos";
                    $result = $database->openConnectionWithReturn($qry);
                    while ( list ($sname) = mysql_fetch_array($result) ) {
						$IsIn=false;
						for ($x=1; $x<=$len; $x++) { 
							if ($sname==$pos[$x]) { 
								$IsIn=true; 
							} 
						}
						if (!$IsIn) { 
							$len++; 
							$pos[$len]=$sname; 
						}
					}
                    sort($pos);
                    for ($x=1; $x<$len; $x++) {
						echo '<option value="'.$pos[$x].'">'.$pos[$x].'</option>';
					}
            
                    ?>
                </select>
            </div>    
            <input type="hidden" name="srPos" id="srPos" value="yes">
            <input class="btn btn-default btn-sm" type="submit" value="Search">
            <input class="btn btn-default btn-sm" type="reset" value="Reset">
	    </form></a>
        
        <br /><br />

	    <?php
}

if ($task != "about")
	echo "<p><a href=\"index.php?option=opl&task=about\">About the Open Positions List</a></p>";

if ($task)
	echo "<p><a href=\"index.php?option=opl\">Return to Open Positions List Search page</a></p>";

?>
