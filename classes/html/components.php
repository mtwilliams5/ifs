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
  * Patch 1.17:   June 2017
  *
  * Copyright (C) 2003-2004 Frank Anon for Obsidian Fleet RPG
  * Distributed under the terms of the GNU General Public License
  * See doc/LICENSE for details
  *
  * This file contains code from Mambo Site Server 4.0.12
  * Copyright (C) 2000 - 2002 Miro International Pty Ltd
  *
  * Comments: Left/right menu components
  *
  * See CHANGELOG for patch details
  *
 ***/

class components
{
    function component($title, $content)
    {
    	?>
        <table width="95%" border="0" cellspacing="0" cellpadding="1" align="center">
	        <tr>
	            <td width="160">
                	<span class="componentHeading">
                    	<?php echo $title ?>
                    </span><br />
                    <?php echo $content ?>
                </td>
	        </tr>
        </table>
        <br /><br />
        <?php
    }

    function survey($pollTitle, $optionText, $pollID, $voters, $title)
    {
    	?>
        <table width="95%" border="0" cellspacing="0" cellpadding="0" align="center">
	        <tr>
	            <td colspan="2">
	                <form name="form2" method="post" action="pollBooth.php">
	                <p><span class="componentHeading"><?php echo $title ?></span><br /><br />
	                <span class="poll"><?php echo $pollTitle ?></span></p>
	            </td>
	        </tr>
	        <?php
            for ($i = 0; $i < count($optionText); $i++)
            {
            	?>
	            <tr>
	                <td valign="top"><input type="radio" name="voteID" value="<?php echo $voters[$i] ?>" /></td>
	                <td class="poll" valign="top"><?php echo $optionText[$i] ?></td>
	            </tr>
	            <?php
            }
            ?>
	        <tr>
	            <td colspan="2">
                	<div align="center"><br />
                        <input type="hidden" name="polls" value="<?php echo $pollID ?>" />
                        <input type="submit" name="task" value="Vote" /></form>&nbsp;&nbsp;
                        <form action="index.php?option=surveyresult&task=Results&polls=<?php echo $pollID ?>" method="post">
	                        <input type="submit" name="task" VALUE="Results" />
                        </form>
                    </div>
                </td>
	        </tr>
        </table>
        <br /><br />
	    <?php
    }

    function AuthorLogin($title, $option, $logintop)
    {
    	?>
        <span class="componentHeading">
			<?php
            if ($logintop)
                echo '<img src="' . $logintop . '" class="center-block" />';
            else
                echo 'Login';
            ?>
        </span>
        <form action="usermenu.php" method="post" name="login">
        	<div class="form-group">
            	<label for="username">Username</label>
                <input type="text" class="form-control input-sm" name="username" id="username" size="10">
            </div>
            <div class="form-group">
            	<label for="passwd">Password</label>
                <input type="password" class="form-control input-sm" name="passwd" id="passwd" size="10">
            </div>
            <?php /* <div class="checkbox"><label><input type="checkbox" name="remember" id="remember"> Remember Me</label></div> */ ?>
            <input type="hidden" name="op2" value="login">
            <input type="hidden" name="option" value="<?php echo $option ?>">
            <button class="btn btn-default btn-sm" type="submit">Login</button><br />
            <a href="index.php?option=registration&amp;task=register">Register</a><br />
            <a href="index.php?option=registration&amp;task=lostPassword">Lost password?</a>
        </form>
	    <?php
    }
}
?>
