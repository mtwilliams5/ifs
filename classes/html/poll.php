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
  * Comments: Display poll
 ***/

class poll
{
    function pollresult($pollTitle, $last_vote, $first_vote, $voters, $percentInt, $optionText, $count, $sum, $month, $pollID)
    {
        $months = array("January","Februrary","March","April","May","June","July","August","September","October","November","December");
        $months_num = array("01","02","03","04","05","06","07","08","09","10","11","12");
        ?>

	    <div id="pollresult">
			<h3 class="articlehead">
            	<p>&nbsp;&nbsp;Polls/Surveys - Results</p>
	    	<ul class="list-unstyled">
				<li><strong>Survey Title:</strong>
            	&nbsp;<?php echo $pollTitle ?></li>

	    		<li><strong>Number of voters:</strong> &nbsp;
	    		<?php echo $voters ?></li>

				<li><strong>First Vote:</strong> &nbsp;
	        	<?php echo $first_vote ?></li>
	    		
				<li><strong>Last Vote:</strong> &nbsp;
	    		<?php echo $last_vote ?></li>
	    	</ul>
	    </div>
	    <div>
			<strong>Select a month:</strong>
			<select name="months" width="200" style="width:200px" onChange="document.location.href='index.php?option=surveyresult&amp;task=Results&amp;polls=<?php echo $pollID ?>&amp;month=' + this.options[selectedIndex].value">
			<option value="">Show All Months</option>
			<?php
				for ($i = 0; $i < count($months); $i++)
				{
					if ($month == $months_num[$i])
					{
						?>
						<option value="<?php echo $month ?>" selected="selected">
							<?php echo $months[$i] ?>
						</option>
						<?php
					}
					else
					{
						?>
						<option value="<?php echo $months_num[$i] ?>">
							<?php echo $months[$i] ?>
						</option>
						<?php
					}
				}
			?>
			</select>
		</div>
		<div>
			<?php
			if (count($percentInt) <> 0)
			{
				for ($i = 0; $i < count($optionText); $i++)
				{
					if ($percentInt[$i] <> "")
					{
						$percentage = $count[$i]/$sum * 100;
						$percentage = round($percentage, 2);
						?>
						<div>
							<img src="images/polls/Col<?php echo $i+1 ?>M.gif" width="<?php echo $percentInt[$i] ?>" height="15" vspace="5" hspace="0" /><img src="images/polls/Col<?php echo $i+1 ?>R.gif" width="10" height="15" vspace="5" hspace="0" /><br />
							<?php echo "$optionText[$i] - $count[$i] ($percentage%)" ?>
						</div>
						<?php
					}
					else
					{
						?>
						<div>
							<img src="images/polls/Col<?php echo $i+1 ?>M.gif" width="3" height="15" vspace="5" hspace="0" /><img src="images/polls/Col<?php echo $i+1 ?>R.gif" width="10" height="15" vspace="5" hspace="0" /><br />
							<?php echo "$optionText[$i] - $count[$i] (0%)" ?>
						</div>
						<?php
					}
					unset($percentage);
				}
			}
			else
			{
				?>
				<p>There are no results for this month.</p>
				<?php
			}
			?>
		</div>
    	<?php
	}
}
?>