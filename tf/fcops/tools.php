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
  * Comments: FCOps Tools - fun!
 ***/

if (!defined("IFS"))
	echo "Hacking attempt!";
else
{
	?>
	<h2 class="text-center">Welcome to FCOps Tools</h2>
	<p class="text-center">Please note that your login will time out after about 10 minutes of inactivity.</p>

	<form class="form-horizontal" action="index.php?option=ifs&amp;task=fcops&amp;action=common&amp;lib=ctrans" method="post">
		<h5>Transfer Character:</h5>
        <div class="form-group">
			<label for="cid" class="col-sm-2 control-label">Character ID:</label>
            <div class="col-sm-1">
            	<input type="text" class="form-control" name="cid" id="cid" size="3">
            </div>
        </div>
        <div class="form-group">
			<label for="sid" class="col-sm-2 control-label">Transfer Destination ID:</label>
            <div class="col-sm-10 col-md-6 col-lg-4">
            	<select class="form-control" name="sid" id="sid">
					<?php
                    $res69=mysql_query("SELECT id, name FROM ifs_ships WHERE tf<>'99' ORDER BY name ASC");
                    while (list($vd, $ve)=mysql_fetch_array($res69)) {
                        echo '<option value="'.$vd.'">'.stripslashes($ve).'</option>
                    '; }
                    ?>      
        		</select>
            </div>
        </div>
		<input type="hidden" name="op" value="tchar">
		<input class="btn btn-default btn-sm" type="submit" value="Submit">
    </form>

	<form class="form-horizontal" action="index.php?option=ifs&amp;task=fcops&amp;action=common&amp;lib=strans" method="post">
		<h5>Transfer Ship:</h5>
        <div class="form-group">
			<label for="sid" class="col-sm-2 control-label">Ship ID:</label>
            <div class="col-sm-10 col-md-6 col-lg-4">
            	<select class="form-control" name="sid" id="sid">
					<?php
                    $res69=mysql_query("SELECT id, name FROM ifs_ships WHERE id>'4' ORDER BY name ASC");
                    while (list($vd, $ve)=mysql_fetch_array($res69)) {
                        echo '<option value="'.$vd.'">'.stripslashes($ve).'</option>
                    '; }
                    ?>      
				</select>
            </div>
        </div>
        <div class="form-group">
			<label for="tfid" class="col-sm-2 control-label">Destination TF:</label>
            <div class="col-sm-1">
            	<input type="text" class="form-control" name="tfid" id="tfid" size="3">
            </div>
        </div>
        <div class="form-group">
			<label for="tgid" class="col-sm-2 control-label">Destination TG:</label>
            <div class="col-sm-1">
            	<input type="text" class="form-control" name="tgid" id="tgid" size="3">
            </div>
        </div>
		<input type="hidden" name="op" value="tship">
		<input class="btn btn-default btn-sm" type="submit" value="Submit">
    </form>

	<h2 class="heading">Task Force Command Staff Admin</h2>
    <div class="tf-mgmt">
	<?php
    $qry = "SELECT tf, name FROM {$spre}taskforces WHERE tg='0' ORDER BY tf";
    $result = $database->openConnectionWithReturn($qry);

    while (list ($tfid, $tfname) = mysql_fetch_array($result))
    {
        $qry2 = "SELECT c.name, s.name
                 FROM {$spre}taskforces t, {$spre}characters c, {$spre}ships s
                 WHERE t.tf='$tfid' AND t.tg='0' AND t.co=c.id AND s.co=c.id";
        $result2 = $database->openConnectionWithReturn($qry2);
        list($tfco, $tfflag) = mysql_fetch_array($result2);
        ?>
        <form class="form-inline" action="index.php?option=ifs&amp;task=fcops&amp;action=tools2" method="post">
            <input type="hidden" name="reftf" value="<?php echo $tfid ?>">
            <input type="hidden" name="reftg" value="0">
            <input type="hidden" name="tgid" value="0">
            <div class="row">
            	<div class="col-xs-12">
                    <div class="form-group">
                        <label for="tfid<?php echo $tfid ?>">Task Force</label>
                        <input type="text" class="form-control" name="tfid" id="tfid<?php echo $tfid ?>" value="<?php echo $tfid ?>" size="3">
                    </div>
                    &nbsp;-&nbsp;
                    <div class="form-group">
                        <label for="tfname<?php echo $tfid ?>" class="sr-only">Task Force Name</label>
                        <input type="text" class="form-control" name="tfname" id="tfname<?php echo $tfid ?>" value="<?php echo $tfname ?>" size="35">
                    </div>
                </div>
            </div>
            <div class="row">
            	<div class="col-xs-12">
                    <div class="form-group">
                        <label for="tfcoid<?php echo $tfid ?>">CO:</label>
                        <select class="form-control" name="tfcoid" id="tfcoid<?php echo $tfid ?>">
                            <?php
                            $qry2 = "SELECT c.id, c.name, s.name, r.rankdesc FROM
                                    {$spre}characters c, {$spre}ships s, {$spre}rank r WHERE
                                    c.id=s.co AND s.tf='$tfid' AND c.rank=r.rankid
                                    ORDER BY r.level DESC, c.rank ASC, c.name";
                            $result2 = $database->openConnectionWithReturn($qry2);
                            while (list($coid, $cname, $sname, $rname)=mysql_fetch_array($result2))
                                if ($cname == $tfco)
                                    echo "<option value=\"{$coid}\" selected=\"selected\">{$rname} {$cname}, {$sname}</option>\n";
                                else
                                    echo "<option value=\"{$coid}\">{$rname} {$cname}, {$sname}</option>\n";
                            ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
            	<div class="col-xs-6 col-sm-2">
                    <input class="btn btn-success btn-sm" type="submit" value="Update Task Force">
        </form>
        		</div>
            	<div class="col-xs-6 col-sm-2">
        <form class="form-inline" action="index.php?option=ifs&amp;task=fcops&amp;action=tools2" method="post">
                    <input type="hidden" name="reftf" value="<?php echo $tfid ?>">
                    <input type="hidden" name="tfid" value="delete">
                    <input class="btn btn-danger btn-sm" type="submit" value="Delete Task Force">
                </div>
            </div>
        </form>
        <br />
        
        <?php
        $qry2 = "SELECT tg, name FROM {$spre}taskforces
                 WHERE tf='$tfid' AND tg<>'0' ORDER BY tg";
        $result2 = $database->openConnectionWithReturn($qry2);

        while (list ($tgid, $tgname) = mysql_fetch_array($result2))
        {
            $qry3 = "SELECT c.name, s.name
                     FROM {$spre}characters c, {$spre}ships s, {$spre}taskforces t
                     WHERE t.tf='$tfid' AND c.id=t.co AND s.co=c.id";
            $result3 = $database->openConnectionWithReturn($qry3);
            list($tgco, $tgflag) = mysql_fetch_array($result3);
            ?>
            <form class="form-inline" action="index.php?option=ifs&amp;task=fcops&amp;action=tools2" method="post">
                <input type="hidden" name="reftf" value="<?php echo $tfid ?>">
                <input type="hidden" name="reftg" value="<?php echo $tgid ?>">
                <input type="hidden" name="tfid" value="<?php echo $tfid ?>">
            <div class="row">
            	<div class="col-xs-11 col-xs-offset-1">
                    <div class="form-group">
                        <label for="tgid<?php echo $tfid ?>-<?php echo $tgid ?>">Task Group</label>
                        <input type="text" class="form-control" name="tgid" id="tgid<?php echo $tfid ?>-<?php echo $tgid ?>" value="<?php echo $tgid ?>" size="3">
                    </div>
                    &nbsp;-&nbsp;
                    <div class="form-group">
                        <label for="tfname<?php echo $tfid ?>-<?php echo $tgid ?>" class="sr-only">Task Group Name</label>
                        <input type="text" class="form-control" name="tfname" id="tfname<?php echo $tfid ?>-<?php echo $tgid ?>"value="<?php echo $tgname ?>" size="35">
                    </div>
                </div>
            </div>
            <div class="row">
            	<div class="col-xs-11 col-xs-offset-1">
                    <div class="form-group">
                        <label for="tfcoid<?php echo $tfid ?>-<?php echo $tgid ?>">CO:</label>
                        <select class="form-control" name="tfcoid" id="tfcoid<?php echo $tfid ?>-<?php echo $tgid ?>">
							<?php
                            $qry3 = "SELECT c.id, c.name, s.name, r.rankdesc FROM
                                    {$spre}characters c, {$spre}ships s, {$spre}rank r WHERE
                                    c.id=s.co AND s.tf='$tfid' AND s.tg='$tgid' AND c.rank=r.rankid
                                    ORDER BY r.level DESC, c.rank ASC, c.name";
                            $result3 = $database->openConnectionWithReturn($qry3);
                            while (list($coid, $cname, $sname, $rname)=mysql_fetch_array($result3))
                                if ($cname == $tgco)
                                    echo "<option value=\"{$coid}\" selected=\"selected\">{$rname} {$cname}, {$sname}</option>\n";
                                else
                                    echo "<option value=\"{$coid}\">{$rname} {$cname}, {$sname}</option>\n";
                            ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
            	<div class="col-xs-5 col-sm-2 col-xs-offset-1">
                    <input class="btn btn-success btn-sm" type="submit" value="Update Task Group">
            </form>
        		</div>
            	<div class="col-xs-5 col-sm-2 col-xs-offset-1">
            <form class="form-inline" action="index.php?option=ifs&amp;task=fcops&amp;action=tools2" method="post">
                    <input type="hidden" name="reftf" value="<?php echo $tfid ?>">
                    <input type="hidden" name="reftg" value="<?php echo $tgid ?>">
                    <input type="hidden" name="tfid" value="<?php echo $tfid ?>">
                    <input type="hidden" name="tgid" value="delete">
                    <input class="btn btn-danger btn-sm" type="submit" value="Delete Task Group">
                </div>
            </div>
            </form>
            <br />
            <?php
        }
        ?>
        <form class="form-inline" action="index.php?option=ifs&amp;task=fcops&amp;action=tools2" method="post">
            <input type="hidden" name="reftf" value="<?php echo $tfid ?>">
            <input type="hidden" name="reftg" value="new">
            <input type="hidden" name="tfid" value="<?php echo $tfid ?>">
            <div class="row">
            	<div class="col-xs-11 col-xs-offset-1">
                    <div class="form-group">
                        <label for="tgid<?php echo $tfid ?>-new">Task Group</label>
                        <input type="text" class="form-control" name="tgid" id="tgid<?php echo $tfid ?>-new" value="" size="3">
                    </div>
                    &nbsp;-&nbsp;
                    <div class="form-group">
                        <label for="tfname<?php echo $tfid ?>-new" class="sr-only">Task Group Name</label>
                        <input type="text" class="form-control" name="tfname" id="tfname<?php echo $tfid ?>-new" value="" size="35">
                    </div>
                </div>
            </div>
            <div class="row">
            	<div class="col-xs-11 col-xs-offset-1">
                    <div class="form-group">
                        <label for="tfcoid<?php echo $tfid ?>-new">CO:</label>
                        <select class="form-control" name="tfcoid" id="tfcoid<?php echo $tfid ?>-new">
							<?php
                            $qry3 = "SELECT c.id, c.name, s.name, r.rankdesc FROM
                                    {$spre}characters c, {$spre}ships s, {$spre}rank r WHERE
                                    c.id=s.co AND s.tf='$tfid' AND c.rank=r.rankid
                                    ORDER BY r.level DESC, c.rank ASC, c.name";
                            $result3 = $database->openConnectionWithReturn($qry3);
                            while (list($coid, $cname, $sname, $rname)=mysql_fetch_array($result3))
                                echo "<option value=\"{$coid}\">{$rname} {$cname}, {$sname}</option>\n";
                            ?>
                    	</select>
                    </div>
                </div>
            </div>
            <div class="row">
            	<div class="col-xs-11 col-sm-2 col-xs-offset-1">
                	<input class="btn btn-default btn-sm" type="submit" value="Add Task Group">
                </div>
            </div>
        </form>
        <?php
    echo '<hr />';
    }
    ?>
    <form class="form-inline" action="index.php?option=ifs&amp;task=fcops&amp;action=tools2" method="post">
        <input type="hidden" name="reftf" value="new">
        <input type="hidden" name="reftg" value="0">
        <input type="hidden" name="tgid" value="0">
            <div class="row">
            	<div class="col-xs-12">
                    <div class="form-group">
                        <label for="tfid-new">Task Force</label>
			            <input type="text" class="form-control" name="tfid" id="tfid-new" value="" size="3">
                    </div>
                    &nbsp;-&nbsp;
                    <div class="form-group">
                        <label for="tfname-new" class="sr-only">Task Force Name:</label>
                        <input type="text" class="form-control" name="tfname" id="tfname-new" value="" size="35">
                    </div>
                </div>
            </div>
            <div class="row">
            	<div class="col-xs-12">
                    <div class="form-group">
                        <label for="tfcoid-new">CO:</label>
                        <select class="form-control" name="tfcoid" id="tfcoid-new">
							<?php
                            $qry2 = "SELECT c.id, c.name, s.name, r.rankdesc FROM
                                    {$spre}characters c, {$spre}ships s, {$spre}rank r WHERE
                                    c.id=s.co AND c.rank=r.rankid
                                    ORDER BY r.level DESC, c.rank ASC, c.name";
                            $result2 = $database->openConnectionWithReturn($qry2);
                            while (list($coid, $cname, $sname, $rname)=mysql_fetch_array($result2))
                                echo "<option value=\"{$coid}\">{$rname} {$cname}, {$sname}</option>\n";
                            ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
            	<div class="col-xs-12">
                	<input class="btn btn-default btn-sm" type="submit" value="Add Task Force">
                </div>
            </div>
        </form>
    </div>
    <?php
}
?>