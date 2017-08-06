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
  * Patch 1.17:   August 2017
  *
  * Copyright (C) 2003-2004 Frank Anon for Obsidian Fleet RPG
  * Distributed under the terms of the GNU General Public License
  * See doc/LICENSE for details
  *
  * This file contains code from Mambo Site Server 4.0.12
  * Copyright (C) 2000 - 2002 Miro International Pty Ltd
  *
  * Comments: Main front page
  *
  * See CHANGELOG for patch details
  *
 ***/

class body
{
    function indexbody($sid, $introtext, $exttext, $title, $time, $newsimage, $imageposition, $category, $count, $charnum, $shipnum, $newstop)
    {
	global $fleetname, $fleetdesc;
        if ($newstop)
        	echo '<img src="' . $newstop . '"  alt="News"><br>';
        ?>
        <div class="news">
            <?php
            for ($i = 0; $i < count($sid); $i++)
            {
                echo '<h3 class="articlehead">' . $title[$i] . '</h3>';
                echo '<span class="small">' . $time[$i] . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
                echo $category[$i] . '</span><br />';

                if ($newsimage[$i] != "")
                {
                    $size = getimagesize("images/stories/$newsimage[$i]");
                    echo '<img src="images/stories/' . $newsimage[$i] . '" hspace="12" vspace="12" ' .
                            'align="' . $imageposition[$i] . '" width="' . $size[0] . '" height="' . $size[1] . '"><br />';
                }

                echo '<span class="newsarticle">' . $introtext[$i] . '</span><br />';
                if ($exttext[$i])
                {
                    echo '<span class="small">';
                    echo '<a href="index.php?option=news&task=viewarticle&sid=' . $sid[$i] . '">';
                    echo '<span class="small">Read On</span></a>... &nbsp;&nbsp;';
                    echo '(' . $count[$i] . ')</span><br />';
                }
                echo '<br /><img src="images/divider.gif" width="100%" height="1"><br /><br />';
            }
            ?>
		</div>
        <br />
	    <!-- Fleet Intro -->
	    <div class="text-center">
	        <div class="highlight">
	            <strong class="fleetstats"><?php echo $fleetname; ?> is the proud home of <?php echo $charnum ?> characters serving on <?php echo $shipnum ?> ships.</strong>
	        </div>
	        <div class="fleetdesc">
	            <?php echo $fleetdesc;?>
	        </div>
	    </div>

    	<?php
	}
}
?>
