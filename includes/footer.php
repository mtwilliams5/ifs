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
  * Comments: Footer included on every page
  *
 ***/

if (!$no_back_to)
{
	?>
	<div class="text-center"><a href="<?php echo $relpath ?>index.php">Back to main page</a></div>
	<?php
}

if ( $pop == "y" && defined("JS") )
	echo '<div class="text-center"><a href="javascript:window.close();">Close This Window</a></div>';

?>

<footer>
	<div class="container">
	<div class="row">
		<div class="smallgrey text-center">
            <div style="float:right"><a href="http://ifs.obsidianfleet.net"><img src="images/ifs.png" border="0" class="img-obsidian img-responsive" /></a></div>
            <p class="smallgrey">IFS software - All Rights Reserved Obsidian Fleet RPG &#169; 2001 - 2003.</p>
            <?php
            if ($pop != "y")
            {
                ?>
                <p class="smallgrey">This page generated by the IFS system.<br />
                IFS originally created by Frank Anon for use in Obsidian Fleet, http://www.obsidianfleet.net.<br />
                See <a href="https://github.com/mtwilliams5/ifs" target="_blank">here</a> for details on how to obtain this software.</p>
                <?php
                //  End TIMER
                GLOBAL $stimer;
                list ($etimer1, $etimer2) = explode (" ", microtime ());
                $etimer = (float)$etimer1 + (float)$etimer2;
                echo '<p>Page Rendered in: <strong>' . round($etimer - $stimer, 3) . '</strong> seconds.</p>';
                ?>
		</div>
	</div>
    <?php
    if ($poweredby)
    { ?>

        <div class="row">
            <div class="text-center">
            <?php
                echo '<a href="http://www.mamboserver.com" target="_blank">';
                echo '<img src="' . $relpath . $poweredby . '" width="278" height="9" alt="Powered by Mambo Site Server" border="0" />';
                echo '</a>';
            ?>
            </div>
        </div>
    <?php
        }
    }
    ?>
    </div>
</footer>

</body>
</html>

<?php
ob_end_flush();
?>