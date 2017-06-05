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
  * Patch 1.17:   June 2017
  *
  * Copyright (C) 2003-2004 Frank Anon for Obsidian Fleet RPG
  * Distributed under the terms of the GNU General Public License
  * See doc/LICENSE for details
  *
  * This file based on code from Mambo Site Server 4.0.12
  * Copyright (C) 2000 - 2002 Miro International Pty Ltd
  *
  * Comments: Display usermenu
 ***/

class HTML_usermenu
{
	function showMenuComponent($uName, $uid, $usertype, $id, $name, $link, $option)
    {
            if ($usertype == "User")
            {
            	?>
				<h5>Hi <?php echo $uName ?>!</h5>
				<?php
            }
            ?>
            <nav>
                <h6 class="menuhead"><?php echo $usertype ?> Menu</h6>
                <?php
                $numItems=count($id);
                for ($i=0; $i < $numItems; $i++)
                {
                    if (trim($name[$i])!="")
                        {
                            ?>
                            <a href="<?php echo $link[$i] ?>"><?php echo $name[$i] ?></a>
                            <?php
                        }
                }
                ?>
            </nav>
		<?php
    }
}
?>