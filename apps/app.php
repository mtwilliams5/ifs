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
  *     matt@mtwilliams.uk
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
  * Comments: Crew application
  *
  * See CHANGELOG for patch details
  *
 ***/

if (!defined("IFS"))
	require ("../includes/lib.php");

if (!defined("IFS")) { echo '<html><head></head><body>'; }
?>

<div class="switch-app">
  <div class="text-center">
	<a href="index.php?option=app&amp;task=crew">Player Application</a>
	<a href="index.php?option=app&amp;task=co">CO Application</a>
	<a href="index.php?option=app&amp;task=ship">Sim Application</a>
  </div>
</div>

<form action="index.php?option=app&amp;task=crew2" method="post" name="Application">
	<h3 class="heading">Player Information</strong></h3>
    <div class="form-group">
	    <label for="Name" class="control-label">Your Real Name:</label>
        <div>
	    	<input type="text" class="form-control" size="37" name="Name" id="Name">
        </div>
    </div>
    <div class="form-group">
	    <label for="Age" class="control-label">Your Real Age:</label>
        <div>
	    	<input type="text" class="form-control" size="37" name="Age" id="Age">
	    </div>
    </div>
    <div class="form-group">
	    <label for="Email" class="control-label">Email Address:</label>
        <div>
	    	<input type="text" class="form-control" size="37" name="Email" id="Email">
	    </div>
    </div>
    <div class="form-group">
	    <label for="IM" class="control-label">Instant Messengers:</label>
        <div>
	    	<textarea class="form-control" name="IM" id="IM" cols="30" rows="2"></textarea>
	    </div>
    </div>
    <div class="form-group">
	    <label for="Follow_Rules" class="control-label">Will you follow all the Rules:</label>
        <div>
	    	<select class="form-control" name="Follow_Rules" id="Follow_Rules" size="1">
                <option selected="selected" value="-----Select----">-----Select----</option>
                <option value="Yes">Yes</option>
                <option value="No">No</option>
	    	</select>
	    </div>
    </div>
    <div class="form-group">
	    <label for="RPG_Experience" class="control-label">Role Playing Experience:</label>
        <div>
	    	<textarea class="form-control" name="RPG_Experience" id="RPG_Experience" rows="4" cols="50"></textarea>
	    </div>
    </div>
    
	<h3 class="heading">Sim Information</strong></h3>
    <div class="form-group">
	    <label for="desiredclass" class="control-label">Desired Sim Class:</label>
        <div>
	    <?php
	    if (!$ship)
	    {
	        echo '<select class="form-control" name="desiredclass" id="desiredclass">';
	        echo '<option selected="selected">Any</option>';
	        $qry = "SELECT c.name
	                FROM {$sdb}classes c, {$sdb}category d, {$sdb}types t
	                WHERE c.category=d.id AND d.type=t.id AND t.support='n'
	                ORDER BY c.name";
	        $result = $database->openShipsWithReturn($qry);
	        while ( list ($sname) = mysql_fetch_array($result) )
	            echo '<option>' . $sname . '</option>';
			echo '</select>';

	    }
	    else
	    {
	        $qry = "SELECT class FROM {$spre}ships WHERE name='$ship'";
	        $result = $database->openConnectionWithReturn($qry);
	        list ($shipclass) = mysql_fetch_array($result);
	        echo '<input type="hidden" name="desiredclass" value="' . $shipclass . '">';
	        echo '<p class="form-control-static" id="desiredclass">' . $shipclass . '</p>';
	    }
	    ?>
        </div>
    </div>
    <div class="form-group">
	    <label for="altclass" class="control-label">Alternate Sim Class:</label>
        <div>
	    	<select class="form-control" name="altclass" id="altclass">
                <option selected="selected">Any</option>
                <?php
                $qry = "SELECT c.name
                        FROM {$sdb}classes c, {$sdb}category d, {$sdb}types t
                        WHERE c.category=d.id AND d.type=t.id AND t.support='n'
                        ORDER BY c.name";
                $result = $database->openShipsWithReturn($qry);
                while ( list ($sname) = mysql_fetch_array($result) )
                    echo '<option>' . $sname . '</option>';
                ?>
            </select>
	    </div>
    </div>
    <div class="form-group">
	    <label for="Ship" class="control-label">Desired Sim:</label>
        <div>
			<?php
            if (!$ship)
            {
                echo '<select class="form-control" name="Ship" id="Ship">';
                echo '<option selected="selected">Any</option>';
                $qry = "SELECT name FROM {$spre}ships WHERE tf<>'99' AND co<>'0' ORDER BY name";
                $result = $database->openConnectionWithReturn($qry);
                while ( list ($sname) = mysql_fetch_array($result) )
                    echo '<option>' . $sname . '</option>';
                echo '</select>';
            }
            else
            {
                $ship = stripslashes($ship);
                echo '<input type="hidden" name="Ship" value="' . $ship . '">';
                echo '<p class="form-control-static" id="Ship">' . $ship . '</p>';
            }
            ?>
	    </div>
    </div>
    
	<h3 class="heading">Character Information</strong></h3>
    <div class="form-group">
	    <label for="Characters_Name" class="control-label">Character's Name:</label>
        <div>
	    	<input type="text" class="form-control" size="37" name="Characters_Name" id="Characters_Name">
	    </div>
	</div>
    <div class="form-group">
	    <label for="Characters_Gender" class="control-label">Character's Gender:</label>
        <div>
	    	<input type="text" class="form-control" size="37" name="Characters_Gender" id="Characters_Gender">
	    </div>
	</div>
    <div class="form-group">
	    <label for="Characters_Race" class="control-label">Character's Species:</label>
        <div>
	    	<input type="text" class="form-control" size="37" name="Characters_Race" id="Characters_Race">
	    </div>
	</div>

	<div class="form-group"><h5 class="heading">Position</strong></h5></div>
    <div class="form-group">
	    <label for="First_Desired_Position" class="control-label">First Choice:</label>
        <div>
	    	<select class="form-control" name="First_Desired_Position" id="First_Desired_Position">
	    		<option>-----Select Position----</option>
				<?php
                $posoptions = "";
                if (!$ship)
                {
                    $filename = $relpath . "tf/positions.txt";
                    $contents = file($filename);
                    $length = sizeof($contents);
                    if ($length == 1)
                    {
                        $filename = $relpath . "tf/positions.txt";
                        $contents = file($filename);
                        $length = sizeof($contents);
                    }
        
                    $counter = 0;
                    do
                    {
                        $counter = $counter + 1;
                        $contents[$counter] = trim($contents[$counter]);
                        $posoptions .= '<option value="' . $contents[$counter] . '">' . $contents[$counter] . '</option>';
                    } while ($counter < ($length - 1));
                }
                else
                {
                    $qry = "SELECT id FROM {$spre}ships WHERE name='$ship'";
                    $result = $database->openConnectionWithReturn($qry);
                    list ($sid) = mysql_fetch_array($result);
        
                    $filename = $relpath . "tf/positions.txt";
                    $contents = file($filename);
                    $length = sizeof($contents);
                    $counter = 0;
                    do
                    {
                        $counter = $counter + 1;
                        $contents[$counter] = trim($contents[$counter]);
        
                        $pos = addslashes($contents[$counter]);
                        $qry = "SELECT id FROM {$spre}characters WHERE ship='$sid' AND pos='$pos'";
                        $result = $database->openConnectionWithReturn($qry);
                        if (!mysql_num_rows($result))
                        {
                            $qry2 = "SELECT action FROM {$spre}positions WHERE ship='$sid' AND pos='$pos' AND action='rem'";
                            $result2 = $database->openConnectionWithReturn($qry2);
                            if (!mysql_num_rows($result2))
                                $posoptions .= '<option value="' . $contents[$counter] . '">' . $contents[$counter] . '</option>';
                        }
                    } while ($counter < ($length - 1));
        
                    $qry = "SELECT pos FROM {$spre}positions WHERE ship = '$sid' AND action = 'add'";
                    $result = $database->openConnectionWithReturn($qry);
                    while ( list ($pos) = mysql_fetch_array($result2) )
                        $posoptions .= '<option value="' . $pos . '">' . $pos . '</option>';
                }
                echo $posoptions;
                ?>
                <option value="Other">Other</option>
            </select>
            <div class="help-block form-inline"><div class="form-group"><label for="otherpos1">If other:</label> <input class="form-control" type="text" name="otherpos1" id="otherpos1"></div></div>
	    </div>
	</div>
    <div class="form-group">
	    <label for="Second_Desired_Position" class="control-label">Second Choice:</label>
        <div>
	    	<select class="form-control" name="Second_Desired_Position" id="Second_Desired_Position">
            <option selected="selected">-----Select Position----</option>
            <?php
            echo $posoptions;
            ?>
            <option value="Other">Other</option>
            </select>
            <div class="help-block form-inline"><div class="form-group"><label for="otherpos2">If other:</label> <input class="form-control" type="text" name="otherpos2" id="otherpos2"></div></div>
	    </div>
	</div>
    <div class="form-group">
	    <label for="Officer_or_Enlisted" class="control-label">Officer or Enlisted:</label>
        <div>
	    	<select class="form-control" name="Officer_or_Enlisted" size="1">
                <option selected="selected">----- Enlisted Personnel or Officer ----</option>
                <option value="Officer">Officer</option>
                <option value="Warrant">Warrant</option>
                <option value="Enlisted">Enlisted</option>
            </select>
	    </div>
	</div>

	    <div class="form-group"><h5 class="heading">Physical Appearance</strong></h5></div>
    <div class="form-group">
	    <label for="Height" class="control-label">Height:</label>
        <div>
	    	<input type="text" class="form-control" size="37" name="Height" id="Height">
	    </div>
	</div>
    <div class="form-group">
	    <label for="Weight" class="control-label">Weight:</label>
        <div>
	    	<input type="text" class="form-control" size="37" name="Weight" id="Weight">
	    </div>
	</div>
    <div class="form-group">
	    <label for="Hair" class="control-label">Hair Colour:</label>
        <div>
	    	<input type="text" class="form-control" size="37" name="Hair" id="Hair">
	    </div>
	</div>
    <div class="form-group">
	    <label for="Eye" class="control-label">Eye Colour:</label>
        <div>
	    	<input type="text" class="form-control" size="37" name="Eye" id="Eye">
	    </div>
	</div>
    <div class="form-group">
	    <label for="Physical_Desc" class="control-label">Physical Description:</label>
        <div>
	    	<textarea class="form-control" name="Physical_Desc" id="Physical_Desc" rows="4" cols="50"></textarea>
	    </div>
	</div>

	    <div class="form-group"><h5 class="heading">Family</strong></h5></div>
    <div class="form-group">
	    <label for="Spouse" class="control-label">Spouse:</label>
        <div>
	    	<input type="text" class="form-control" size="37" name="Spouse" id="Spouse">
	    </div>
	</div>
    <div class="form-group">
	    <label for="Children" class="control-label">Children:</label>
        <div>
	    	<input type="text" class="form-control" size="37" name="Children" id="Children">
	    </div>
	</div>
    <div class="form-group">
	    <label for="Parents" class="control-label">Parents:</label>
        <div>
	    	<input type="text" class="form-control" size="37" name="Parents" id="Parents">
	    </div>
	</div>
    <div class="form-group">
	    <label for="Siblings" class="control-label">Siblings:</label>
        <div>
	    	<input type="text" class="form-control" size="37" name="Siblings" id="Siblings">
	    </div>
	</div>
    <div class="form-group">
	    <label for="Other_Family" class="control-label">Other Family:</label>
        <div>
	    	<textarea class="form-control" name="Other_Family" id="Other_Family" rows="4" cols="50"></textarea>
	    </div>
	</div>

	    <div class="form-group"><h5 class="heading">Personality &amp; Traits</strong></h5></div>
    <div class="form-group">
	    <label for="General_Overview" class="control-label">General Overview:</label>
        <div>
	    	<textarea class="form-control" name="General_Overview" id="General_Overview" rows="4" cols="50"></textarea>
	    </div>
	</div>
    <div class="form-group">
	    <label for="Strengths_Weaknesses" class="control-label">Strengths &amp; Weaknesses:</label>
        <div>
	    	<textarea class="form-control" name="Strengths_Weaknesses" id="Strengths_Weaknesses" rows="4" cols="50"></textarea>
	    </div>
	</div>
    <div class="form-group">
	    <label for="Ambitions" class="control-label">Ambitions:</label>
        <div>
	    	<textarea class="form-control" name="Ambitions" id="Ambitions" rows="4" cols="50"></textarea>
	    </div>
	</div>
    <div class="form-group">
	    <label for="Hobbies_Interests" class="control-label">Hobbies &amp; Interests:</label>
        <div>
	    	<textarea class="form-control" name="Hobbies_Interests" id="Hobbies_Interests" rows="4" cols="50"></textarea>
	    </div>
	</div>

	    <div class="form-group"><h5 class="heading">Character Bio</strong></h5></div>
    <div class="form-group">
	    <label for="Character_Bio" class="control-label">Character Bio:</label>
        <div>
	    	<textarea class="form-control" name="Character_Bio" id="Character_Bio" rows="8" cols="50"></textarea>
	    </div>
	</div>
    <div class="form-group">
	    <label for="Service_Record" class="control-label">Service Record:</label>
        <div>
	    	<textarea class="form-control" name="Service_Record" id="Service_Record" rows="8" cols="50"></textarea>
	    </div>
	</div>
    
	<h3 class="heading">Sample Post</strong>
	    <small class="help-block">Please reply to the situation with you in the same position as your first desired position.</small></h3>
    <div class="form-group">
	    <label for="Sample_Post" class="control-label">You are in the lounge when suddenly the ship shakes violently and the lights go out. A few seconds later, the emergency lights come on. A few crewmen try the door, but it doesn't work -- it seems like power is out throughout the whole ship.</label>
        <div>
	    	<textarea class="form-control" name="Sample_Post" id="Sample_Post" rows="11" cols="50"></textarea>
	    </div>
	</div>
    
	<h3 class="heading">Reference</strong></h3>
    <div class="form-group">
	    <label for="Reference" class="control-label">How did you Hear About Us?:</label>
        <div>
	    	<select class="form-control" name="Reference" id="Reference">
                <option selected="selected">-----Select Reference----</option>
                <option value="Already In PF">Already in the Fleet</option>
                <option value="Friend">Friend</option>
                <option value="Link">Link from another site</option>
                <option value="Search Engine">Search Engine</option>
                <option value="Browsing the Internet">Browsing the Internet</option>
                <option value="Advertisement">Fleet Advertisement</option>
                <option value="Social">Social Media</option>
                <option value="Other">Other</option>
            </select>
            <div class="help-block form-inline">
            	<div class="form-group">
                	<label for="Reference_Other">If other:</label> <input class="form-control" type="text" name="Reference_Other" id="Reference_Other">
                </div>
            </div>
	    </div>
	</div>
	<div class="form-group">
		<input type="submit" name="Submit" value="Submit Application">
		<input type="reset" name="Reset" value="Reset Application">
    </div>
</form>
<?php if (!defined("IFS")) { echo '</body></html>'; }