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
  * Comments: Search
 ***/

class search
{
    function openhtml()
    {
    	?>
        <div class="newspane">
            <h3 class="articlehead">Search Engine</h3>
            <form action="index.php" method="post">
                <input class="inputbox" type="text" name="searchword" value="<?php echo $search ?>" size="30">
                &nbsp;<input class="button" type="submit" value="Search">
                <input type="hidden" name="option" value="search">
            </form>
    	<?php
    }

    function nokeyword()
    {
    	?>
        <h3 class="text-warning">Please enter search criteria</h3>
    	<?php
    }

    function searchintro($searchword)
    {
    	?>
        <h3>Search Keyword: <strong><?php echo $searchword ?></strong></h3>
    	<?php
    }

    function stories($id, $title, $time, $text, $searchword)
    {
    	?>
        <hr />
        <span class="componentheading">Stories Results</span><br />
        Number of results: <?php echo count($id) ?><br /><br />
        <ul>
            <?php
            for ($i=0; $i<count($id); $i++)
            {
                echo "<li><a href=\"index.php?option=news&task=viewarticle&sid={$id[$i]}\">";
                echo $title[$i] . "</a>, <span class=\"small\">{$time[$i]}</span><br />\n";

                $words = $text[$i];
                $words = preg_replace("'<script[^>]*>.*?</script>'si","",$words);
                $words = preg_replace('/<a\s+.*?href="([^"]+)"[^>]*>([^<]+)<\/a>/is','\2 (\1)', $words);
                $words = preg_replace('/<!--.+?-->/','',$words);
                $words = preg_replace('/{.+?}/','',$words);
                $words = strip_tags($words);
                echo substr($words,0,200) . "&#133;</li><br /><br />\n";
            }
        echo "</ul>\n";
    }

    function articles($id, $title, $time, $text, $searchword)
    {
    	?>
        <hr />
        <span class="componentheading">Articles Results</span><br />
        Number of results: <?php echo count($id) ?><br /><br />
        <ul>
            <?php
            for ($i=0; $i<count($id); $i++)
            {
                echo "<li><a href=\"index.php?option=articles&task=show&artid={$id[$i]}\">";
                echo $title[$i] . "</a><br />\n";

                $words = $text[$i];
                $words = preg_replace("'<script[^>]*>.*?</script>'si","",$words);
                $words = preg_replace('/<a\s+.*?href="([^"]+)"[^>]*>([^<]+)<\/a>/is','\2 (\1)', $words);
                $words = preg_replace('/<!--.+?-->/','',$words);
                $words = preg_replace('/{.+?}/','',$words);
                $words = strip_tags($words);
                echo substr($words,0,300) . "&#133;</li><br /><br />\n";
            }
        echo "</ul>\n";
    }

    function faqs($id, $title, $text, $searchword)
    {
    	?>
        <hr />
        <span class="componentheading">FAQ Results</span><br />
        Number of results: <?php echo count($id) ?><br /><br />
        <ul>
            <?php
            for ($i=0; $i<count($id); $i++)
            {
                echo "<li><a href=\"index.php?option=faq&task=show&artid={$id[$i]}\">";
                echo $title[$i] . "</a><br />\n";

                $words = $text[$i];
                $words = preg_replace("'<script[^>]*>.*?</script>'si","",$words);
                $words = preg_replace('/<a\s+.*?href="([^"]+)"[^>]*>([^<]+)<\/a>/is','\2 (\1)', $words);
                $words = preg_replace('/<!--.+?-->/','',$words);
                $words = preg_replace('/{.+?}/','',$words);
                $words = strip_tags($words);
                echo substr($words,0,200) . "&#133;</li><br /><br />\n";
            }
        echo "</ul>\n";
    }

    function content($id, $mid, $heading, $content, $sublevel, $searchword)
    {
    	?>
        <hr />
        <span class="componentheading">Content Results</span><br />
        Number of results: <?php echo count($id) ?><br /><br />
        <ul>
        <?php
            for ($i=0; $i<count($id); $i++)
            {
                echo "<li><a href=\"index.php?option=displaypage&Itemid={$mid[$i]}&op=page&SubMenu=";
                if ($sublevel[$i] == 0)
                    echo $mid[$i];
                echo "\">{$heading[$i]}</a><br />";

                $words = $content[$i];
                $words = preg_replace("'<script[^>]*>.*?</script>'si","",$words);
                $words = preg_replace('/<a\s+.*?href="([^"]+)"[^>]*>([^<]+)<\/a>/is','\2 (\1)', $words);
                $words = preg_replace('/<!--.+?-->/','',$words);
                $words = preg_replace('/{.+?}/','',$words);
                $words = strip_tags($words);
                echo substr($words,0,200) . "&#133;</li><br /><br />\n";
            }
        echo "</ul>\n";
    }

    function conclusion($totalRows, $searchword)
    {
    	?>
        <hr />
        <p>Total <?php echo $totalRows ?> results found.<br />
        Search for <strong><?php echo $searchword ?></strong> with
        <a href="//www.google.com/search?q=<?php echo $searchword ?>" target="_blank">google</a></p>
	    <?php
    }

    function closehtml()
    {
        echo "</div>";
    }
}
?>