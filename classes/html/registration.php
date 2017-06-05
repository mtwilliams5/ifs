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
  * Comments: Display registration info
 ***/

class registration
{
    function lostPassForm($option)
    {
    	?>
		<h3 class="articlehead">Lost your Password?</h3>
        <p>No problem. Just type your User name and click on send button.<br />
            You'll receive a Confirmation Code by Email, then return here and retype your
            User name and your Code, after that you'll receive your new Password by Email.</p>
        <form action="index.php" method="post">
        	<div class="form-group">
            	<label for="checkusername" class="control-label">User name:</label>
                <div>
                	<input type="text" class="form-control" name="checkusername" id="checkusername" size="25">
                </div>
            </div>
        	<div class="form-group">
            	<label for="checkusername" class="control-label">Email Address:</label>
                <div>
                	<input type="text" class="form-control" name="confirmEmail" id="confirmEmail" size="35">
                </div>
            </div>
            <input type="hidden" name="option" value="<?php echo $option ?>" />
            <input type="hidden" name="task" value="sendNewPass" />
        	<div class="form-group">
            	<div>
            		<input type="submit" value="Send Password" />
                </div>
            </div>
        </form>
		<?php
    }

	function registerForm($option)
    {
        ?>

		<h3 class="articlehead">Create an account</h3>
        <form action="index.php" method="post">
        	<div class="form-group">
                <label for="yourname" class="control-label">Name:</label>
                <div>
                	<input type="text" class="form-control" name="yourname" id="yourname">
                </div>
        	</div>
        	<div class="form-group">
                <label for="username1" class="control-label">User Name:</label>
                <div>
                	<input type="text" class="form-control" name="username1" id="username1">
                </div>
        	</div>
        	<div class="form-group">
                <label for="email" class="control-label">Email:</label>
                <div>
                	<input type="text" class="form-control" name="email" id="email" size="30">
                </div>
        	</div>
        	<div class="form-group">
                <label for="pass" class="control-label">Password:</label>
                <div>
                	<input type="password" class="form-control" name="pass" id="pass" size="15">
                </div>
        	</div>
        	<div class="form-group">
                <label for="verifyPass" class="control-label">Verify Password:</label>
                <div>
                	<input type="password" class="form-control" name="verifyPass" id="verifyPass" size="15">
                </div>
            </div>
            <input type="hidden" name="tc" value="<?php echo strtotime("now") ?>">
            <input type="hidden" name="option" value="<?php echo $option ?>">
            <input type="hidden" name="task" value="saveRegistration">
            <div class="form-group">
            	<div>
            		<input type="submit" value="Send Registration">
                </div>
            </div>
        </form>

        <?php
    }
}
?>