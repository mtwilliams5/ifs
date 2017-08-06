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
  *	Comments: Display FAQ stuff
 ***/

class faq
{
	function faqlist($topictext, $topicid, $title, $sid, $id, $counter)
    {
	    ?>
	    <div>
	    	<h2 class="articlehead">FAQ's</h2>
	        <hr noshade size="1" />
			<p>From the list below choose one of our FAQ's topics, then select a article
				to read. If you have a question which is not in this section, please
				contact us. </p>
			<?php
			if ($id <> "")
			{
				?>
				<hr noshade size="1" />
				<div id="faqlist">
					<h4><?php echo $topictext[$id] ?></h4>

					<?php
					$color = array("#000000", "#666666");
					$k = 0;
					for ($i = 0; $i < count($sid["$topictext[$id]"]); $i++)
					{
						$test = $date["$topictext[$id]"][$i];
						$count = $counter["$topictext[$id]"][$i];
						$date = split(" ",$test);
						$datesplit = split("-", $date[0]);
						?>

						<div style="background-color:<?php echo $color[$k] ?>">
							<div class="text-center">
								<img src="images/document.gif" align="absmiddle" vspace="3" hspace="3">
							</div>
							<div class="text-left">
								<a href="index.php?option=faq&amp;task=show&amp;artid=<?php echo $sid["$topictext[$id]"][$i] ?>">
								<?php echo $title["$topictext[$id]"][$i] ?></a>
							</div>
						</div>
						<?php
						if ($k == 1)
							$k = 0;
						else
							$k++;
					}
					?>
				</div>
				<hr noshade size="1" />
				<?php
			}
			?>
			<h2 class="articlehead">Categories</h2>
			<ul class="list-unstyled">
			<?php
				for ($i = 0; $i < count($topicid); $i++)
				{
					if (($id == $i) && ($id <> ""))
						echo "<li> <a class=\"category\">$topictext[$i]</a>&nbsp;</li>\n";
					else
						echo "<li> <a class=\"category\" href=\"index.php?option=faq&Itemid=5&topid={$i}\">$topictext[$i]</a>&nbsp;</li>\n";
				}
			?>
			</ul>
		</div>

        <?php
	}

    function showfaq($title, $id, $content)
    {
    	?>
        <div is="faq">
            <h3 class="componentHeading"><?php echo $title ?></h3>
            <p><?php echo $content ?></p>
        </div>

        <?php
	}

}
?>