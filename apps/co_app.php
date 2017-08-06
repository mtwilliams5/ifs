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
  * Patch 1.17:   August 2017
  *
  * Copyright (C) 2003-2004 Frank Anon for Obsidian Fleet RPG
  * Distributed under the terms of the GNU General Public License
  * See doc/LICENSE for details
  *
  * Comments: CO Application
  *
  *
  * See CHANGELOG for patch details
  *
 ***/

if (!defined("IFS"))
   	require("../includes/lib.php");
?>

<div class="row switch-app">
  <div class="col-xs-12 col-sm-12 col-md-12 text-center">
  	<div class="btn-group">
	  <a role="button" class="btn btn-default btn-sm" href="index.php?option=app&amp;task=crew">Player Application</a>
	  <a role="button" class="btn btn-default btn-sm active" href="index.php?option=app&amp;task=co">CO Application</a>
	  <a role="button" class="btn btn-default btn-sm" href="index.php?option=app&amp;task=ship">Sim Application</a>
    </div>
  </div>
</div>

<h1 class="text-center">Commanding Officer's Application Form</h1>
<form class="form-horizontal" action="index.php?option=app&amp;task=co2" method="post">
	<h3 class="heading">Player Information</strong></h3>
    <div class="form-group">
	    <label for="Name" class="col-sm-2 control-label">Your Real Name:</label>
        <div class="col-sm-10 col-md-6 col-lg-4">
	    	<input type="text" class="form-control" size="37" name="Name" id="Name">
        </div>
    </div>
    <div class="form-group">
	    <label for="Age" class="col-sm-2 control-label">Your Real Age:</label>
        <div class="col-sm-10 col-md-6 col-lg-4">
	    	<input type="text" class="form-control" size="37" name="Age" id="Age">
	    </div>
    </div>
    <div class="form-group">
	    <label for="Email" class="col-sm-2 control-label">Email Address:</label>
        <div class="col-sm-10 col-md-6 col-lg-4">
	    	<input type="text" class="form-control" size="37" name="Email" id="Email">
	    </div>
    </div>
    
    <div class="form-group"><h5 class="headblue col-xs-offset-1">Contact Info</strong></h5></div>
    <div class="form-group">
	    <label for="IM" class="col-sm-2 control-label">Instant Messengers:</label>
        <div class="col-sm-10 col-md-6 col-lg-4">
	    	<textarea class="form-control" name="IM" id="IM" cols="30" rows="2"></textarea>
	    </div>
    </div>
    
    <div class="form-group"><h5 class="headblue col-xs-offset-1">Player Experience</strong></h5></div>
    <div class="form-group">
	    <label for="RPG_Experience" class="col-sm-2 control-label">List all Sim Groups:
        <span class="help-block" id="RPG_HelpBlock">(Including group names, sims, etc)</span></label>
        <div class="col-sm-10 col-md-6 col-lg-4">
	    	<textarea class="form-control" name="RPG_Experience" id="RPG_Experience" rows="4" cols="50"></textarea>
	    </div>
    </div>
    <div class="form-group">
	    <label for="CO_Experience" class="col-sm-2 control-label">Do you have any previous CO experience? If so, describe:</label>
        <div class="col-sm-10 col-md-6 col-lg-4">
	    	<textarea class="form-control" name="CO_Experience" id="CO_Experience" rows="4" cols="50"></textarea>
	    </div>
    </div>
    <div class="form-group">
	    <label for="Time_In_Other_RPGs" class="col-sm-2 control-label">How long have you been simming?</label>
        <div class="col-sm-10 col-md-6 col-lg-4">
	    	<select class="form-control" name="Time_In_Other_RPGs" id="Time_In_Other_RPGs">
                <option selected="selected" value="-----Select----">-----Select----</option>
                <option value="0 - 3 Months">0 - 3 Months</option>
                <option value="4 - 6 Months">4 - 6 Months</option>
                <option value="7 - 9 Months">7 - 9 Months</option>
                <option value="10 months - 1 Year">10 months - 1 Year</option>
                <option value="1 - 5 Years">1 - 5 Years</option>
                <option value="Over 5 Years">Over 5 Years</option>
            </select>
	    </div>
    </div>
    <div class="form-group">
	    <label for="rules" class="col-sm-2 control-label">Will you follow all the rules?</label>
        <div class="col-sm-10 col-md-6 col-lg-4">
        	<div class="radio">
            	<label>
                	<input type="radio" name="rules" value="yes">
                	Yes
                </label>
            </div>
        	<div class="radio">
            	<label>
            		<input type="radio" name="rules" value="no">
                    No
                </label>
            </div>
	    </div>
    </div>
    
	<h3 class="heading">Sim Information</strong></h3>
    <div class="form-group">
	    <label for="desiredclass" class="col-sm-2 control-label">Sim Class:</label>
        <div class="col-sm-10 col-md-6 col-lg-4">
	    	<select class="form-control" name="desiredclass" id="desiredclass">
	    		<option value="Any" selected="selected">Any</option>
				<?php
        			$qry = "SELECT c.name
                            FROM {$sdb}classes c, {$sdb}category d, {$sdb}types t
                            WHERE c.category=d.id AND d.type=t.id AND t.support='n'
                            ORDER BY c.name";
                    $result = $database->openShipsWithReturn($qry);
                    while ( list($sname) = mysql_fetch_array($result) )
                        echo '<option value="' . $sname . '">' . $sname . '</option>';
                ?>
	    	</select>
	    </div>
    </div>
    <div class="form-group">
	    <label for="altclass" class="col-sm-2 control-label">Alternate Sim Class:</label>
        <div class="col-sm-10 col-md-6 col-lg-4">
	    	<select class="form-control" name="altclass" id="altclass">
                <option value="Any" selected="selected">Any</option>
                <?php
        			$qry = "SELECT c.name
                            FROM {$sdb}classes c, {$sdb}category d, {$sdb}types t
                            WHERE c.category=d.id AND d.type=t.id AND t.support='n'
                            ORDER BY c.name";
                    $result = $database->openShipsWithReturn($qry);
                    while ( list ($sname) = mysql_fetch_array($result) )
                        echo '<option value="' . $sname . '">' . $sname . '</option>';
                ?>
	    	</select>
	    </div>
    </div>
    <div class="form-group">
	    <label for="ship" class="col-sm-2 control-label">Desired Sim:</label>
        <div class="col-sm-10 col-md-6 col-lg-4">
	    	<select class="form-control" name="ship" id="ship">
                <option value="Any" selected="selected">Any</option>
                <?php
        			$ship = stripslashes($ship);
                    $qry = "SELECT name FROM {$spre}ships WHERE tf<>'99' AND co='0' ORDER BY name";
                    $result = $database->openConnectionWithReturn($qry);
                    while ( list($sname) = mysql_fetch_array($result) ) {
                        if ($ship == $sname) {
                            echo '<option selected="selected" value="' . $sname . '">' . $sname;
                            echo '</option>';}
                        else {
                            echo '<option value="' . $sname . '">' . $sname;
                            echo '</option>';}
					}
                ?>
            </select>
	    </div>
    </div>
    
	<h3 class="heading">Character Information</strong></h3>
    <div class="form-group">
	    <label for="Characters_Name" class="col-sm-2 control-label">Character's Name:</label>
        <div class="col-sm-10 col-md-6 col-lg-4">
	    	<input type="text" class="form-control" size="37" name="Characters_Name" id="Characters_Name">
	    </div>
    </div>
    <div class="form-group">
	    <label for="Characters_Race" class="col-sm-2 control-label">Character's Species:</label>
        <div class="col-sm-10 col-md-6 col-lg-4">
	    	<input type="text" class="form-control" size="37" name="Characters_Race" id="Characters_Race">
	    </div>
    </div>
    <div class="form-group">
	    <label for="Characters_Gender" class="col-sm-2 control-label">Character's Gender:</label>
        <div class="col-sm-10 col-md-6 col-lg-4">
	    	<input type="text" class="form-control" size="37" name="Characters_Gender" id="Characters_Gender">
	    </div>
    </div>
    <div class="form-group">
	    <label for="Character_Bio" class="col-sm-3 control-label">Character Bio:
            <span class="help-block">Please make this as detailed as you can. The better it is, the more likely you are to succeed in your application</span>
        </label>
        <div class="col-sm-9 col-md-8 col-lg-6">
	    	<textarea class="form-control" name="Character_Bio" id="Character_Bio" rows="20" cols="50"></textarea>
	    </div>
    </div>
    
	<h3 class="heading">Questionnaire</strong></h3>
	<p class="help-block">Instructions: Please answer the following questions honestly and completely. Take time to consider your answers. The answers are subjective, thus there are no &quot;right&quot; or &quot;wrong&quot; replies. Your answers will be judged by the TFCO the sim you are applying for is in. If, after review, they feel that you are not qualified, they will suggest you take a position as sim XO, Department Head, or Command Intern for further training before you attend Command Class. If they find you are ready for command they will assign you to a ship and you'll be entered into Command Class. Every opportunity for success is afforded the candidate.</p>
    <div class="form-group">
	    <label for="q1" class="col-sm-4 control-label">1) What do you think are the duties of a sim's Commanding Officer, both in-character and out-of-character?</label>
        <div class="col-sm-8 col-md-8 col-lg-6">
	    	<textarea class="form-control" name="q1" id="q1" rows="9" cols="50"></textarea>
	    </div>
    </div>
    <div class="form-group">
	    <label for="q2" class="col-sm-4 control-label">2) What role do you think XOs and department heads should play in helping run the sim?</label>
        <div class="col-sm-8 col-md-8 col-lg-6">
	    	<textarea class="form-control" name="q2" id="q2" rows="9" cols="50"></textarea>
	    </div>
    </div>
    <div class="form-group">
	    <label for="q3" class="col-sm-4 control-label">3) How do you plan to recruit?</label>
        <div class="col-sm-8 col-md-8 col-lg-6">
	    	<textarea class="form-control" name="q3" id="q3" rows="9" cols="50"></textarea>
	    </div>
    </div>
    <div class="form-group">
	    <label for="q4" class="col-sm-4 control-label">4) Posting has dropped to an all-time low, there are only 1 or 2 posts a week. What will you do to motive your crew into posting again?</label>
        <div class="col-sm-8 col-md-8 col-lg-6">
	    	<textarea class="form-control" name="q4" id="q4" rows="9" cols="50"></textarea>
	    </div>
    </div>
    <div class="form-group">
	    <label for="q5" class="col-sm-4 control-label">5) A crewmember has come to you, complaining about someone else and accusing them of being control-freaks. How do you handle it?</label>
        <div class="col-sm-8 col-md-8 col-lg-6">
	    	<textarea class="form-control" name="q5" id="q5" rows="9" cols="50"></textarea>
	    </div>
    </div>
    <div class="form-group">
	    <label for="q6" class="col-sm-4 control-label">6) You disagree with a decision made by the Admiralty and other CO's of the fleet. What do you do?</label>
        <div class="col-sm-8 col-md-8 col-lg-6">
	    	<textarea class="form-control" name="q6" id="q6" rows="9" cols="50"></textarea>
	    </div>
    </div>
    
	<h3 class="heading">Sample Post</strong></h3>
    <p class="help-block">Please reply to the situation below:</p>
    <div class="form-group">
	    <label for="Sample_Post" class="col-sm-4 control-label">You are in the lounge when suddenly the ship shakes violently and the lights go out. A few seconds later, the emergency lights come on. A few crewmen try the door, but it doesn't work -- it seems like power is out throughout the whole ship.</label>
        <div class="col-sm-8 col-md-8 col-lg-6">
	    	<textarea class="form-control" name="Sample_Post" id="Sample_Post" rows="9" cols="50"></textarea>
	    </div>
    </div>
    
	<h3 class="heading">Reference</strong></h3>
    <div class="form-group">
	    <label for="Reference" class="col-sm-2 control-label">How did you Hear About Us?:</label>
        <div class="col-sm-10 col-md-6 col-lg-4">
	    	<select class="form-control" name="Reference" id="Reference">
                <option selected="selected" value="select">-----Select Reference----</option>
                <option value="Already In Fleet">Already in the Fleet</option>
                <option value="Friend">Friend</option>
                <option value="Link from another site">Link from another site</option>
                <option value="Search Engine">Search Engine</option>
                <option value="Browsing the Internet">Browsing the Internet</option>
                <option value="Social Media">Social Media</option>
                <option value="Advertisement">Fleet Advertisement</option>
                <option value="Other">Other</option>
	    	</select>
            <div class="help-block form-inline">
            	<div class="form-group">
                	<label for="Reference_Other">If other:</label> <input class="form-control" type="text" name="Reference_Other" id="Reference_Other">
                </div>
            </div>
	    </div>
	</div>
    
	<h3 class="heading">Other Comments</strong></h3>
    <div class="form-group">
	    <label for="comments" class="col-sm-3 control-label">Do you have anything else you would like to add to your application?</label>
        <div class="col-sm-9 col-md-8 col-lg-6">
	    	<textarea class="form-control" name="comments" id="comments" rows="5" cols="50"></textarea>
	    </div>
    </div>
	<div class="form-group">
		<input class="btn btn-success" type="submit" name="Submit" value="Submit Application">
		<input class="btn btn-danger" type="reset" name="Reset" value="Reset Application">
    </div>
</form>
