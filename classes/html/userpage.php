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
  * Patch 1.17:   August 2017
  *
  * Copyright (C) 2003-2004 Frank Anon for Obsidian Fleet RPG
  * Distributed under the terms of the GNU General Public License
  * See doc/LICENSE for details
  *
  * This file based on code from Mambo Site Server 4.0.12
  * Copyright (C) 2000 - 2002 Miro International Pty Ltd
  *
  * Comments: Display userpage
 ***/

class HTML_user
{
    function newsform($secid, $secname, $uid, $option, $Imagename, $text_editor)
    {
    	?>
        <h2 class="heading">Submit a News Story</h2>
        <form class="form-horizontal" action="index.php" method="post" name="adminform">
	        <div class="form-group">
	        	<label for="newstitle" class="col-sm-2 control-label">Title:</label>
	            <div class="col-sm-8">
                	<input type="text" class="form-control" name="newstitle" id="newstitle" value="<?php echo $title ?>">
                </div>
	        </div>
	        <div class="form-group">
	        	<label for="newssection" class="col-sm-2 control-label">Section:</label>
	            <div class="col-sm-8">
                	<select class="form-control" name="newssection" id="newssection">
	                        <option value="" selected="selected">select a Section</option>
	                    	<?php
                            for ($i = 0; $i < count($secid); $i++)
								echo '<option value="' . $secid[$i] . '">' . $secname[$i] . '</option>';
                            ?>
	                </select>
                    <span class="help-block">News about ships goes under the relevant TF section.</span>
                </div>
	        </div>
	        <div class="form-group">
	        	<label for="introtext" class="col-sm-2 control-label">Introduction:</label>
	            <div class="col-sm-8">
                	<textarea class="form-control" cols="70" rows="15" name="introtext" id="introtext"></textarea>
                </div>
	        </div>
	        <?php
            if ($text_editor == true)
            {
            	redirect("administrator/inline_editor/editor.htm?content=introtext", "Edit in Text Editor", 450, 650);
            }
            ?>
	        <div class="form-group">
	        	<label for="fultext" class="col-sm-2 control-label">Extended Text:</label>
	            <div class="col-sm-8">
                	<textarea class="form-control" cols="70" rows="15" name="fultext" id="fultext"></textarea>
                </div>
	        </div>
	        <?php
            if ($text_editor == true)
            {
            	redirect("administrator/inline_editor/editor.htm?content=fultext", "Edit in Text Editor", 450, 650);
            }
            ?><br />
            <input type="hidden" name="option" value="<?php echo $option ?>">
            <input type="hidden" name="op" value="SaveNewNews">
            <input type="hidden" name="uid" value="<?php echo $uid ?>">
            <input type="hidden" name="Imagename2" value="<?php echo $Imagename ?>">
            <div class="form-group">
            	<div class="col-sm-2"></div>
            	<input class="btn btn-default" type="submit" name="submit" value="Add News">
            </div>
	    </form>
		<?php
	}

	function articleform($secid, $secname, $uid, $option, $Imagename, $text_editor)
    {
    	?>
        <h2 class="heading">Submit An Article</h2>
	    <form class="form-horizontal" action="index.php" method="post" name="adminform">
	        <div class="form-group">
	        	<label for="Imagename" class="col-sm-2 control-label">Image:</label>
	            <div class="col-sm-8 col-md-6 col-lg-4">
                	<?php if ($Imagename=="") echo '<div class="input-group">' ?>
                	<input type="text" class="form-control" name="Imagename" id="Imagename" disabled="disabled" value="<?php echo $Imagename ?>">
	                <?php
                    if ($Imagename=="") {
						echo '<span class="input-group-addon">';
	                    redirect("upload.php?uid={$uid}&option={$option}&type=articles", "Upload Image", 180, 350);
						echo '</span></div>';
					}
                    ?>
	            </div>
                <div class="col-sm-2 col-md-3 col-lg-4 text-right">
                	<img src="images/6977transparent.gif" name="imagelib" class="img-responsive img-thumbnail">
                </div>
	        </div>
	        <div class="form-group">
	        	<label for="arttitle" class="col-sm-2 control-label">Title:</label>
	            <div class="col-sm-8">
                	<input type="text" class="form-control" name="arttitle" id="arttitle" value="<?php echo $title ?>">
                </div>
	        </div>
	        <div class="form-group">
	        	<label for="artsection" class="col-sm-2 control-label">Section:</label>
	            <div class="col-sm-8">
                	<select class="form-control" name="artsection" id="artsection">
	                        <option value="" selected="selected">select a Section</option>
	                    	<?php
                            for ($i = 0; $i < count($secid); $i++)
								echo '<option value="' . $secid[$i] . '">' . $secname[$i] . '</option>';
                            ?>
	                </select>
                </div>
	        </div>
	        <div class="form-group">
	        	<label for="pagecontent" class="col-sm-2 control-label">Content:</label>
	            <div class="col-sm-8">
                	<textarea class="form-control" cols="70" rows="15" name="pagecontent" id="pagecontent"></textarea>
                </div>
	        </div>
	        <?php
            if ($text_editor == true)
            {
            	redirect("administrator/inline_editor/editor.htm?content=pagecontent", "Edit in Text Editor", 450, 650);
            }
            ?>
	        <div class="checkbox">
            	<div class="col-sm-2"></div>
	            <label>
	                <input type="checkbox" name="anonymous" id="anonymous">
                	Remain Anonymous?
                </label>
	        </div><br />
            <input type="hidden" name="option" value="<?php echo $option ?>">
            <input type="hidden" name="op" value="SaveNewArticle">
            <input type="hidden" name="uid" value="<?php echo $uid ?>">
            <input type="hidden" name="Imagename2" value="<?php echo $Imagename ?>">
            <div class="form-group">
            	<div class="col-sm-2"></div>
            	<input class="btn btn-default" type="submit" name="submit" value="Add Article">
            </div>
	    </form>
		<?php
    }

	function FAQform($secid, $secname, $uid, $option, $text_editor)
    {
    	?>
        <h2 class="heading">Submit a FAQ</h2>
	    <form class="form-horizontal" action="index.php" method="post" name="adminform">
	        <div class="form-group">
	        	<label for="faqtitle" class="col-sm-2 control-label">Title:</label>
	            <div class="col-sm-8">
                	<input type="text" class="form-control" name="faqtitle" id="faqtitle" value="<?php echo $title ?>">
                </div>
	        </div>
	        <div class="form-group">
	        	<label for="faqsection" class="col-sm-2 control-label">Section:</label>
	            <div class="col-sm-8">
                	<select class="form-control" name="faqsection" id="faqsection">
	                        <option value="" selected="selected">select a Section</option>
	                    	<?php
                            for ($i = 0; $i < count($secid); $i++)
								echo '<option value="' . $secid[$i] . '">' . $secname[$i] . '</option>';
                            ?>
	                </select>
                </div>
	        </div>
	        <div class="form-group">
	        	<label for="pagecontent" class="col-sm-2 control-label">Content:</label>
	            <div class="col-sm-8">
                	<textarea class="form-control" cols="70" rows="15" name="pagecontent" id="pagecontent"></textarea>
                </div>
	        </div>
	        <?php
            if ($text_editor == true)
            {
            	redirect("administrator/inline_editor/editor.htm?content=pagecontent", "Edit in Text Editor", 450, 650);
            }
            ?><br />
            <input type="hidden" name="option" value="<?php echo $option ?>">
            <input type="hidden" name="op" value="SaveNewFAQ">
            <input type="hidden" name="uid" value="<?php echo $uid ?>">
            <input type="hidden" name="Imagename2" value="<?php echo $Imagename ?>">
            <div class="form-group">
            	<div class="col-sm-2"></div>
            	<input class="btn btn-default" type="submit" name="submit" value="Add FAQ">
            </div>
	    </form>
		<?php
    }

	function linkform($secid, $secname, $uid, $option)
    {
    	?>
        <h2 class="heading">Submit a Web Link</h2>
	    <form class="form-horizontal" action="index.php" method="post" name="NewLink">
	        <div class="form-group">
	        	<label for="linktitle" class="col-sm-2 control-label">Name:</label>
	            <div class="col-sm-8">
                	<input type="text" class="form-control" name="linktitle" id="linktitle" value="<?php echo $title ?>">
                </div>
	        </div>
	        <div class="form-group">
	        	<label for="linksection" class="col-sm-2 control-label">Section:</label>
	            <div class="col-sm-8">
                	<select class="form-control" name="linksection" id="linksection">
	                        <option value="" selected="selected">select a Section</option>
	                    	<?php
                            for ($i = 0; $i < count($secid); $i++)
								echo '<option value="' . $secid[$i] . '">' . $secname[$i] . '</option>';
                            ?>
	                </select>
                </div>
	        </div>
	        <div class="form-group">
	        	<label for="linkurl" class="col-sm-2 control-label">URL:</label>
	            <div class="col-sm-8">
                	<input type="text" class="form-control" name="linkurl" id="linkurl">
                </div>
	        </div><br />
            <input type="hidden" name="option" value="<?php echo $option ?>">
            <input type="hidden" name="op" value="SaveNewLink">
            <input type="hidden" name="uid" value="<?php echo $uid ?>">
            <div class="form-group">
            	<div class="col-sm-2"></div>
            	<input class="btn btn-default" type="submit" name="submit" value="Add Link">
            </div>
	    </form>
		<?php
    }

	function userEdit($uid, $name, $username, $email, $option, $result2, $shiplist, $bday)
    {
        ?>
	    <h2 class="heading">Edit Your Details</h2>
	    <form class="form-horizontal" action="index.php" method="post" name="EditUser">
	        <div class="form-group">
	            <label for="name2" class="col-sm-2 control-label">Your name:</label>
                <div class="col-sm-10 col-md-6 col-lg-4">
	            	<input type="text" class="form-control" name="name2" id="name2" value="<?php echo $name ?>">
	        	</div>
            </div>
	        <div class="form-group">
	            <label for="email2" class="col-sm-2 control-label">Email:</label>
                <div class="col-sm-10 col-md-6 col-lg-4">
	            	<input type="text" class="form-control" name="email2" id="email2" value="<?php echo $email ?>">
	        	</div>
            </div>
	        <div class="form-group">
	            <label for="username2" class="col-sm-2 control-label">User name:</label>
                <div class="col-sm-10 col-md-6 col-lg-4">
	            	<input type="text" class="form-control" name="username2" id="username2" value="<?php echo $username ?>">
	        	</div>
            </div>
	        <div class="form-group">
	            <label for="pass2" class="col-sm-2 control-label">Password:</label>
                <div class="col-sm-10 col-md-6 col-lg-4">
	            	<input type="password" class="form-control" name="pass2" id="pass2" value="">
	        	</div>
            </div>
	        <div class="form-group">
	            <label for="verifyPass" class="col-sm-2 control-label">Verify Password:</label>
                <div class="col-sm-10 col-md-6 col-lg-4">
	            	<input type="password" class="form-control" name="verifyPass" id="verifyPass">
	        	</div>
            </div>
	        <div class="form-group">
	            <label for="bdaymon" class="col-sm-2 control-label">Birthday:
                	<span class="help-block">(optional)</span></label>
                <div class="col-sm-10 col-md-6 col-lg-4">
                	<div class="row">
                        <div class="col-sm-6 col-md-8">
                            <select class="form-control" name="bdaymon" id="bdaymon">
                                <option value="00"<?php if ($bday['month'] == "00") echo 'selected="selected"' ?>>--</option>
                                <option value="01"<?php if ($bday['month'] == "01") echo 'selected="selected"' ?>>January</option>
                                <option value="02"<?php if ($bday['month'] == "02") echo 'selected="selected"' ?>>February</option>
                                <option value="03"<?php if ($bday['month'] == "03") echo 'selected="selected"' ?>>March</option>
                                <option value="04"<?php if ($bday['month'] == "04") echo 'selected="selected"' ?>>April</option>
                                <option value="05"<?php if ($bday['month'] == "05") echo 'selected="selected"' ?>>May</option>
                                <option value="06"<?php if ($bday['month'] == "06") echo 'selected="selected"' ?>>June</option>
                                <option value="07"<?php if ($bday['month'] == "07") echo 'selected="selected"' ?>>July</option>
                                <option value="08"<?php if ($bday['month'] == "08") echo 'selected="selected"' ?>>August</option>
                                <option value="09"<?php if ($bday['month'] == "09") echo 'selected="selected"' ?>>September</option>
                                <option value="10"<?php if ($bday['month'] == "10") echo 'selected="selected"' ?>>October</option>
                                <option value="11"<?php if ($bday['month'] == "11") echo 'selected="selected"' ?>>November</option>
                                <option value="12"<?php if ($bday['month'] == "12") echo 'selected="selected"' ?>>December</option>
                            </select>
                        </div>
                        <div class="col-sm-6 col-md-4">
                            <select class="form-control" name="bdayday" id="bdayday">
                                <option value="00"<?php if ($bday['day'] == "00") echo 'selected="selected"' ?>>--</option>
                                <?php
                                for ($i=1; $i<=31; $i++)
                                {
                                    if ($i < 10)
                                        $i = '0' . $i;
                                    echo '<option value="' . $i . '"';
                                    if ($bday['day'] == $i)
                                        echo ' selected="selected"';
                                    echo '>' . $i . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
	        	</div>
            </div>

	        <div class="form-group">
	            <label for="email2" class="col-sm-2 control-label">Your Characters:</label>
                <div class="col-sm-10 col-md-8 col-lg-6">
                	<div class="list-group">
	            		<?php echo $shiplist ?>
                    </div>
	        	</div>
            </div>
            <input type="hidden" name="uid" value="<?php echo $uid ?>">
            <input type="hidden" name="option" value="<?php echo $option ?>">
            <input type="hidden" name="op" value="saveUserEdit">
            <input class="btn btn-default" type="submit" name="submit" value="Save Changes">
	    </form>
		<?php
    }

	function confirmation()
    {
    	?>
	    <h3 class="heading">Submission Success!</h3>
	    <p>Your article has been successfully submitted to our administrators. It will be reviewed before being published on the site.</p>
		<?php
    }

	function frontpage()
    {
    	redirect("index.php?option=login");
    }
}
?>