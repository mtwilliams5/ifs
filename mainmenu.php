<?php
/***
  * INTEGRATED FLEET MANAGEMENT SYSTEM
  * OBSIDIAN FLEET
  * http://www.obsidianfleet.net/ifs/
  *
  * Developer:	Frank Anon
  * 	    	fanon@obsidianfleet.net
  *
  * Version:	1.17
  * Release Date: June, 2017
  *
  * Copyright (C) 2003-2004 Frank Anon for Obsidian Fleet RPG
  * Distributed under the terms of the GNU General Public License
  * See doc/LICENSE for details
  *
  * This file based on code from Mambo Site Server 4.0.12
  * Copyright (C) 2000 - 2002 Miro International Pty Ltd
  *
  * Comments: Display main menu
 ***/
echo '<nav>';

if(!((trim($SubMenu)=="")||($SubMenu=="0")))
    $componentid=$SubMenu;

$qry="SELECT id, name, link, contenttype
      FROM {$mpre}menu
      WHERE componentid='$componentid' AND menutype='mainmenu' AND inuse=1
      ORDER BY ordering";
$result=$database->openConnectionWithReturn($qry);
while ( list($id, $name, $link, $contenttype)=mysql_fetch_array($result) )
{	
    $qry2="SELECT id FROM {$mpre}menu WHERE componentid='$id'";
    $result2=$database->openConnectionWithReturn($qry2);
    $numres=mysql_num_rows($result2);
	
	if ($numres!=0)
		$SubMenu=$id;
	else
		$SubMenu="";
	if ($contenttype=="mambo")
	{
		echo '<a href="' . $link . '&amp;id=' . $id . '">' . $name . '</a>';
	} 
	elseif ($contenttype=="web")
    {
        $correctLink= preg_match("~^https?://~i", $link);
        $isindex = preg_match("~index.php~i", $link);
        if ($isindex != 1 && $correctLink !=1 )
	            $link="//$link";
		echo '<a href="' . $link . '">' . $name . '</a>';
    }
    elseif ($contenttype=="file")
	{
        echo '<a href="index.php?option=displaypage&amp;Itemid=' . $id;
		echo '&amp;op=file&amp;SubMenu=' . $SubMenu . '">';
    	echo $name . '</a>';
	}
	elseif ($contenttype=="typed")
	{
        echo '<a href="index.php?option=displaypage&amp;Itemid=' . $id;
		echo '&amp;op=page&amp;SubMenu=' . $SubMenu . '">';
    	echo $name . '</a>';
	}
}
echo '</nav>';
?>