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
  *     matt@mtwilliams.uk
  *
  * Version:	1.17
  * Release Date: June 3, 2004
  * Patch 1.17: June 2017
  *
  * Copyright (C) 2003-2004 Frank Anon for Obsidian Fleet RPG
  * Distributed under the terms of the GNU General Public License
  * See doc/LICENSE for details
  *
  * Comments: Displays ship listing, by TF and TG
 ***/

if ($database=="")

	require("../includes/header.php?pop=y");
else
{
	$relpath = "";
	?>
    <div class="row">
    	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-center">
    		<h2>Sim Listing</h2>
        </div>
    </div>
	
    <?php
	$qry = "SELECT name, co FROM {$spre}taskforces
    		WHERE tf='$tf' AND tg='$tg'";
	$result=$database->openConnectionWithReturn($qry);
	list($tgname,$tfco)=mysql_fetch_array($result);

	if (((!$tgname) && ($tf)) && $tf != "all")
    {
		echo "<br /><center>Invalid Task Force / Task Group.</center>\n";
		$tf = '0';
	}

	if ($tf == '0' || $tf == '')
    {		
		/****************************\
		|*	  SELECT TASK FORCE 	*|
		\****************************/
		?>
        <div class="row">
        	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-center">
				<?php
                echo 'Please choose a Task Force:';
                echo '<p><a href="' . $relpath . 'index.php?option=ships&tf=all" />Show All</a></p>';
                
                    $qry = "SELECT tf,name FROM {$spre}taskforces WHERE tg='0' ORDER BY tf";
                    $result=$database->openConnectionWithReturn($qry);
        
                    while ( list($tfid,$tfname)=mysql_fetch_array($result) )
                        if ($tfid != '99')
                        {
                            echo '<p>
                                <a href="' . $relpath . 'index.php?option=ships&tf=' . $tfid . '" />
                                    <img src="' . $relpath . 'images/tfbanners/tf' . $tfid . '.png" border="0" class="img-responsive center-block">
                                    Task Force ' . $tfid . ' -- ' . $tfname . '
                                </a>
                                <a href="' . $relpath . 'index.php?option=ships&tf=' . $tfid . '&textonly=on" class="help-block" />
                                    (Text only)
                                </a>
                            </p>';
                        }
				?>
            </div>
        </div>
    <?php
	}
    elseif (($tg == '0' || $tg == '') && $tf != "all")
    {
		/************************\
		|*	SELECT TASK GROUP	*|
		\************************/
		$qry = "SELECT tg,name FROM {$spre}taskforces
        		WHERE tf='$tf' AND tg<>'0' ORDER BY tg";
		$result=$database->openConnectionWithReturn($qry);

    	if (mysql_num_rows($result) == 1)
        {
        	list ($tgid) = mysql_fetch_array($result);
			if ($option)
				if ($textonly)
					redirect("index.php?option=ships&tf={$tf}&tg={$tgid}&textonly=on");
				else
					redirect("index.php?option=ships&tf={$tf}&tg={$tgid}");
			else
				if ($textonly)
					redirect("tf/ships.php?tf={$tf}&tg={$tgid}&textonly=on");
				else
					redirect("tf/ships.php?tf={$tf}&tg={$tgid}");
        }

		?>
        <div class="row">
        	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-center">
				<?php
                echo 'Please choose a Task Group:';
                echo '<p><a href="' . $relpath . 'index.php?option=ships&tf=' . $tf . '&tg=all" />Show All</a></p>';
        
                    while ( list($tgid,$tgname)=mysql_fetch_array($result) )
                                echo '<p>';
                                if($textonly) {
                                    echo '<a href="' . $relpath . 'index.php?option=ships&tf=' . $tf . '&tg=' . $tgid . '" />';
                                } else {
                                    echo '<a href="' . $relpath . 'index.php?option=ships&tf=' . $tf . '&tg=' . $tgid . '" />
                                        <img src="' . $relpath . 'images/tfbanners/tg' . $tf . '-'. $tgid . '.png" border="0" class="img-responsive center-block">';
                                }
                                echo    'Task Group ' . $tgid . ' -- ' . $tgname . '
                                    </a>
                                </p>';
				?>
            </div>
        </div>
    <?php
	}
    else
    {
		$seltf = $tf;
        $seltg = $tg;

	    switch ($sort)
	    {
	        case "class":
	            $sort = "class, ";
	            break;
	        case "tf":
	            $sort = "tf, tg, ";
	            break;
	        case "status":
	            $sort = "status, ";
	            break;
	    }

		if ($tf != "all")
        {
			$qry = "SELECT name,co FROM {$spre}taskforces WHERE tf='$tf' AND tg='0'";
			$result=$database->openConnectionWithReturn($qry);
			list($tfname,$tfco)=mysql_fetch_array($result);
        }
        else
        {
        	$tfname = "Ships of $fleetname";
            $tfco = "Admin";
        }

		if ($tf == "all")
	   		$qry = "SELECT * FROM {$spre}ships WHERE tf<>'99' order by sorder, {$sort}name asc";
        elseif ($tg == "all")
	   		$qry = "SELECT * FROM {$spre}ships WHERE tf='$tf' order by sorder, {$sort}name asc";
        else
    		$qry = "SELECT * FROM {$spre}ships WHERE tf='$tf' AND tg='$tg' order by sorder, {$sort}name asc";
		$result=$database->openConnectionWithReturn($qry);

	    ?>
        <div class="row taskforce-info">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-center">
            <?php
            if ($seltf != "all")
            {
                echo '<p><strong>';
                echo 'Task Force ';
                
                echo $tf .' -- ' .$tfname . '</strong></p>';
                if (!$textonly)
                    echo '<img src="' . $relpath . 'images/tfbanners/tf' . $tf . '.png" alt="Task Force ' . $tf . ' -- ' . $tfname . '" class="img-responsive center-block" />';

                if (($tf == '99' || $tg != '1') && $seltf != "all" && $seltg != "all")
                {
                    echo '<p><strong>Task Group ' . $tg. ' -- ' .$tgname . '</strong></p>';
                    if (!$textonly)
                        echo '<img src="' . $relpath . 'images/tfbanners/tg' . $tf . '-' . $tg . '.png" alt="Task Group ' . $tg . ' -- ' . $tgname . '" class="img-responsive center-block" />';
                }
            }
            ?>
            </div>
        </div><!-- End of taskforce-info row -->
        <div class="row switch-tf">
            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
            <?php
            if (defined("IFS"))
            {
			?>
                <p><strong>Switch Task Force:</strong></p>
                	<div class="btn-group" role="group" aria-label="...">	
					<?php
                
                    $qry9 = "SELECT tf,name FROM {$spre}taskforces WHERE tg='0' ORDER BY tf";
                    $result9=$database->openConnectionWithReturn($qry9);

                    if (mysql_num_rows($result9) > 2) {
        
                        while ( list($tfid,$tfname)=mysql_fetch_array($result9) )
                            if ($tfid != '99')
                            {
                                echo '<a role="button" class="btn btn-default btn-xs smallgrey';
                                if ($seltf == $tfid)
                                    echo ' active" href="" />';
                                else
                                    echo '" href="' . $relpath . 'index.php?option=ships&tf=' . $tfid . '" />';
                                
                                if ($tfid == '74')
                                    echo 'Division ' . $tfid;
                                else
                                    echo 'Task Force ' . $tfid;
                                    
                                echo '<br />' . $tfname . '</a>';
                            }
                    }
						?>
                    </div>
                <?php
            }
            ?>
            </div>
        </div><!-- End of switch-tf row -->
        <div class="row sort-options">
            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
            <?php
            if (defined("IFS"))
            {
			?>
                <p><strong>Sort by:</strong></p>
                	<div class="btn-group" role="group" aria-label="...">
						<?php 
                        if ($sort != "")
                        {
                            if ($textonly)
                                echo '<a role="button" class="btn btn-default btn-sm" href="index.php?option=ships&amp;tf=' . $seltf. '&amp;tg=' . $seltg . '&amp;textonly=on">';
                            else
                                echo '<a role="button" class="btn btn-default btn-sm" href="index.php?option=ships&amp;tf=' . $seltf. '&amp;tg=' . $seltg . '">';
                            echo 'Name</a>';
                        }
                        else
                            echo '<a role="button" class="btn btn-default btn-sm active" href="">Name</a>';
        
                       if ($sort != "class, ")
                       {
                            if ($textonly)
                                echo '<a role="button" class="btn btn-default btn-sm" href="index.php?option=ships&amp;tf=' . $seltf. '&amp;tg=' . $seltg . '&amp;sort=class&amp;textonly=on">';
                            else
                                echo '<a role="button" class="btn btn-default btn-sm" href="index.php?option=ships&amp;tf=' . $seltf. '&amp;tg=' . $seltg . '&amp;sort=class">';
                            echo 'Class</a>';
                       }
                       else
                            echo '<a role="button" class="btn btn-default btn-sm active" href="">Class</a>';
        
                       if ($sort != "tf, tg, ")
                       {
                            if ($textonly)
                                echo '<a role="button" class="btn btn-default btn-sm" href="index.php?option=ships&amp;tf=' . $seltf. '&amp;tg=' . $seltg . '&amp;sort=tf&amp;textonly=on">';
                            else
                                echo '<a role="button" class="btn btn-default btn-sm" href="index.php?option=ships&amp;tf=' . $seltf. '&amp;tg=' . $seltg . '&amp;sort=tf">';
                            echo 'TF</a>';
                       }
                       else
                            echo '<a role="button" class="btn btn-default btn-sm active" href="">TF</a>';
        
                       if ($sort != "status, ")
                       {
                            if ($textonly)
                                echo '<a role="button" class="btn btn-default btn-sm" href="index.php?option=ships&amp;tf=' . $seltf. '&amp;tg=' . $seltg . '&amp;sort=status&amp;textonly=on">';
                            else
                                echo '<a role="button" class="btn btn-default btn-sm" href="index.php?option=ships&amp;tf=' . $seltf. '&amp;tg=' . $seltg . '&amp;sort=status">';
                            echo 'Status</a>';
                       }
                       else
                            echo '<a role="button" class="btn btn-default btn-sm active" href="">Status</a>';
						?>
                    </div>
                <?php
            }
            ?>
            </div>
        </div><!-- End of sort-options row -->
        
        <?php
            while( list($sid,$sname,$reg,$class,$site,$co,$xo,$tf,$tg,$status,$image,,,$desc,$format)=mysql_fetch_array($result) )
                ship_list ($database, $mpre, $spre, $sdb, $uflag, $textonly, $relpath, $sid, $sname, $reg, $site, $image, $co, $xo, $status, $class, $format, $tf, $tg, $desc);
            ?>
		<?php
    }
}
?>