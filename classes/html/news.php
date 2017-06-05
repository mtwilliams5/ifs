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
  *	Comments: List all categories that belong to the news section,
  *			  and list news stories once a category has been selected.
 ***/

class news
{
    function shownewsmaker($time, $title, $introtext, $fultext, $topic, $image, $sid, $imageposition, $count)
    {
    	?>

		<div id="news-story">
            <h2 class="articlehead"><?php echo $title ?></h2>
            <span class="small"><?php echo $time ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $topic ?></span>
            <div>
                <?php
                if ($image!="")
                    echo "<img src=\"images/stories/{$image}\" align=\"{$imageposition}\" alt=\"{$title}\" />\n";
                echo '<p>' . $introtext . '</p>';

                echo '<p>' . $fultext . '</p>';
                ?>
            </div>
            <a href="javascript:window.history.go(-1);">[Back to list]</a>
			<div class="text-right">
	            <?php echo $count ?>
            </div>
		</div>

        <?php
    }

    function newsmaker($topicid, $title, $sid, $topictext, $id, $time, $counter)
   	{
    	?>
        <table width="98%" cellpadding="4" cellspacing="4" border="0" align="center">
            <tr>
                <td colspan="2"><p class="articlehead">News Stories</p></td>
            </tr>
            <tr>
				<td width="50%" valign="top" height="78">
				    <p>Select from the box below to choose news topics, then select a news
	                  article to read. Periodically we archive news and articles away to allow
	                  space for new ones. Don't forget to search the Archive if you can't
	                  find what you're looking for.</p>
                </td>

				<td width="50%" valign="top" height="78" >
              		<!-- insert image here -->
                </td>
        	</tr>
            <tr>
                <td valign="top" colspan="2">
                	<?php
                    if ($id <> "")
                    {
                        ?>
                        <hr noshade size="1" />
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                        	<td width="32" height="20" align="center" bgcolor="#999999">&nbsp;</td>
                            <td width="38%" height="20" bgcolor="#999999">
                            	<b><font color="#FFFFFF"><?php echo $topictext[$id] ?></font></b>
                            </td>
                            <td width="150" height="20" bgcolor="#999999" align="center">
                            	<b><font color="#FFFFFF">Submitted</font></b>
                            </td>
                        </tr>

                        <?php
                        $color = array("#000000", "#333333");
                        $k = 0;
                        for ($i = 0; $i < count($sid["$topictext[$id]"]); $i++)
                        {
                            $test = $time["$topictext[$id]"][$i];
                            $date = split(" ",$test);
                            $datesplit = split("-", $date[0]);
                            ?>

                            <tr bgcolor="<?php echo $color[$k] ?>">
	                            <td width="32" height="20" align="center">
                                	<img src="images/document.gif" align="absbottom" vspace="3" hspace="3" />
                                </td>
	                            <td width="70%" height="20">
                                	<a href="index.php?option=news&task=viewarticle&sid=<?php echo $sid["$topictext[$id]"][$i] ?>">
	                                <?php echo $title["$topictext[$id]"][$i] ?></a>
                                </td>
	                            <td width="150" height="20" align="center">
                                	<span class="small">
	                                <?php echo date ("F d Y", mktime (0,0,0,$datesplit[1],$datesplit[2],$datesplit[0]));?>
	                                </span>
                                </td>
	                        </tr>

                            <?php
                            if ($k == 1)
	                            $k = 0;
                        	else
	                            $k++;
	                    }
                        ?>
			            </table>
				        <hr noshade size="1" />
				        <?php
					}
                    ?>
			        <p><div class="articlehead">Categories</div><br />
					<?php
                    for ($i = 0; $i < count($topicid); $i++)
                        if (($id == $i) && ($id <> ""))
                            echo "<li> <a class=\"category\">$topictext[$i]</a>&nbsp;</li>\n";
                        else
                            echo "<li> <a class=\"category\" href=\"index.php?option=news&Itemid=5&topid=$i\">$topictext[$i]</a>&nbsp;</li>\n";
                    ?>
			        </p>
				</td>
			</tr>
		</table>
        <?php
    }
}
?>