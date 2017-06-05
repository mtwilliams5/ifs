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
  *	Comments: Function displays selected weblink category and titles from the database.
 ***/

class weblinks {
	function displaylist($topictext, $topicid, $title, $sid, $id, $date, $url)
    {
    	?>
		<div>
			<h3 class="articlehead">Web Links</h3>
			<p>We are regularly out on the web. When we find some great site we list
				them here for to enjoy, however we would love you to come back. From
				the box below choose one of our Link topics, then select a URL to visit.</p>
			<?php
			if ($id <> "")
			{
				?>
				<hr noshade size="1" />
				<h4>Web Link</h4>
				<?php
				$color = array("#333333", "#666666");
				$k = 0;
				for ($i = 0; $i < count($sid["$topictext[$id]"]); $i++)
				{
					$test = $time["$topictext[$id]"][$i];
					$count = $counter["$topictext[$id]"][$i];
					$date = split(" ",$test);
					$datesplit = split("-", $date[0]);
					?>

					<div style="background-color:<?php echo $color[$k] ?>">
						<?php
						$today = date("n d Y");
						$todaydate = split(" ", $today);
						$sum = $todaydate[2] - $datesplit[0];
						?>
						<img src="images/wwwicon.gif" width="32" height="16" align="absbottom" vspace="3" hspace="10" />
						<a href="<?php echo $url["$topictext[$id]"][$i] ?>" target="_blank">
						<?php echo $title["$topictext[$id]"][$i] ?></a>
					</div>
					<?php
					if ($k == 1)
						$k = 0;
					else
						$k++;
				}
				?>
				<hr noshade size="1" />
				<?php
			}
			?>
			<h3 class="articlehead">Categories</h3>
			<?php
			for ($i = 0; $i < count($topicid); $i++)
			{
				if (($id == $i) && ($id <> ""))
					echo "<li> <a class=\"category\">$topictext[$i]</a>&nbsp;</li>\n";
				else
					echo "<li> <a class=\"category\" href=\"index.php?option=weblinks&Itemid=4&topid=$i\">$topictext[$i]</a>&nbsp;</li>\n";
			}
			?>
	    </div>
		<?php
    }
}
?>