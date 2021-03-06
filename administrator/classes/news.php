<?php
/**
	 *	Mambo Site Server Open Source Edition Version 3.0.5
	 *	Dynamic portal server and Content managment engine
	 *	27-11-2002
 	 *
	 *	Copyright (C) 2000 - 2002 Miro Contruct Pty Ltd
	 *	Distributed under the terms of the GNU General Public License
	 *	This software may be used without warrany provided these statements are left
	 *	intact and a "Powered By Mambo" appears at the bottom of each HTML page.
	 *	This code is Available at http://sourceforge.net/projects/mambo
	 *
	 *	Site Name: Mambo Site Server Open Source Edition Version 3.0.5
	 *	File Name: news.php
	 *	Developers: Danny Younes - danny@miro.com.au
	 *				Nicole Anderson - nicole@miro.com.au
	 *	Date: 27-11-2002
	 * 	Version #: 3.0.5
	 *	Comments:
	**/

	class news {
		function viewNews($database, $newshtml, $option, $categories, $mpre){
			$query = "SELECT categoryid, categoryname FROM " . $mpre . "categories WHERE section='News'";
			$result = $database->openConnectionWithReturn($query);
			$i = 0;
			while ($row = mysql_fetch_object($result)){
				$categoryid[$i] = $row->categoryid;
				$categoryname[$i] = $row->categoryname;
				$i++;
				}
			mysql_free_result($result);

			if ($categories == "all"){
				$query = "SELECT sid, title, published, checked_out, editor, archived, frontpage, approved FROM " . $mpre . "stories ORDER BY ordering";
			}elseif ($categories == "new"){
				$query = "SELECT sid, title, published, checked_out, editor, archived, frontpage, approved FROM " . $mpre . "stories WHERE approved=0 ORDER BY ordering";
			}elseif ($categories == "home"){
				$query = "SELECT sid, title, published, checked_out, editor, archived, frontpage, approved FROM " . $mpre . "stories WHERE frontpage=1 ORDER BY ordering";
			}elseif ($categories !=""){
				$query = "SELECT sid, title, published, checked_out, editor, archived, frontpage, approved FROM " . $mpre . "stories WHERE topic=$categories ORDER BY ordering";
			}

			if ($categories!=""){
				$result = $database->openConnectionWithReturn($query);
				$i = 0;
				while ($row = mysql_fetch_object($result)){
					$id[$i] = $row->sid;
					$title[$i] = $row->title;
					$published[$i] = $row->published;
					$checkedout[$i] = $row->checked_out;
					$editor[$i] = $row->editor;
					$archived[$i] = $row->archived;
					$frontpage[$i] = $row->frontpage;
					$approved[$i] = $row->approved;
					$i++;
				}
			}
			$newshtml->showNews($id, $title, $option, $published, $checkedout, $editor, $archived, $categoryid, $categoryname, $categories, $frontpage, $approved);
		}

		function newNews($database, $newshtml, $option, $text_editor, $categories, $mpre){
			require ("../configuration.php");
			$handle = opendir($image_path);
			$i = 0;
			while ($file = readdir($handle)) {
				if (($file <> ".") && ($file <> "..")){
					$imagename[$i] = $file;
					$i++;
					}
				}
			closedir($handle);

			$query = "SELECT categoryid, categoryname FROM " . $mpre . "categories WHERE section='$option'";
			$result = $database->openConnectionWithReturn($query);
			$i = 0;
			while ($row = mysql_fetch_object($result)){
				$topicid[$i] = $row->categoryid;
				$topictext[$i] = $row->categoryname;
				$i++;
				}
			mysql_free_result($result);

			$query = "SELECT * FROM " . $mpre . "stories WHERE frontpage=1";
			$result = $database->openConnectionWithReturn($query);
			$frontpagecount = mysql_num_rows($result);
			mysql_free_result($result);

			$query = "SELECT * FROM " . $mpre . "stories WHERE frontpage=0";
			$result = $database->openConnectionWithReturn($query);
			$restcount = mysql_num_rows($result);
			mysql_free_result($result);

			$newshtml->addNews($option, $topictext, $topicid, $id, $imagename, $restcount, $frontpagecount, $text_editor, $categories);
			}

		function saveNewNews($database, $option, $introtext, $fultext, $topic, $image, $title, $ordering, $position, $frontpage, $categories, $mpre){
			//if (($introtext == "") || ($topic == "") || ($fultext == "") || ($image == "")){
			if (($introtext == "") || ($topic == "")){
				print "<SCRIPT>alert('News must have an introduction and belong to a topic'); window.history.go(-1);</SCRIPT>\n";
				exit(0);
				}

			if ($frontpage == ""){
				$query = "SELECT sid FROM " . $mpre . "stories WHERE ordering >= $ordering AND frontpage=0 ORDER BY ordering";
				$result = $database->openConnectionWithReturn($query);
				while ($row = mysql_fetch_object($result)){
					$sid = $row->sid;
					$query = "UPDATE " . $mpre . "stories SET ordering=ordering+1 WHERE sid=$sid AND frontpage=0";
					$database->openConnectionNoReturn($query);
					}
				}
			else {
				$query = "SELECT sid FROM " . $mpre . "stories WHERE ordering >= $ordering AND frontpage=1 ORDER BY ordering";
				$result = $database->openConnectionWithReturn($query);
				while ($row = mysql_fetch_object($result)){
					$sid = $row->sid;
					$query = "UPDATE " . $mpre . "stories SET ordering=ordering+1 WHERE sid=$sid AND frontpage=1";
					$database->openConnectionNoReturn($query);
					}
				}

			if ($frontpage <> 1){
				$frontpage = 0;
				}
			$date = date("Y-m-d G:i:s");
// $introtext = addslashes($introtext);
// $fultext = addslashes($fultext);
// $title = addslashes($title);
			$query = "INSERT INTO " . $mpre . "stories SET introtext='$introtext', fultext='$fultext', topic='$topic', newsimage='$image', time='$date', title='$title', published='0', image_position='$position', ordering=$ordering, frontpage=$frontpage";
			$database->openConnectionNoReturn($query);?>
			<SCRIPT>
				document.location.href='index2.php?option=<?php
echo $option; ?>&categories=<?php
echo $categories; ?>';
			</SCRIPT>
			<?php
}

		function editNews($database, $newshtml, $option, $storyid, $myname, $categories, $text_editor, $mpre){
			require ("../configuration.php");

			$query = "SELECT title, checked_out, editor FROM " . $mpre . "stories WHERE sid='$storyid'";
			$result = $database->openConnectionWithReturn($query);
			while ($row = mysql_fetch_object($result)){
				$checked = $row->checked_out;
				$title = $row->title;
				$editor = $row->editor;
				}
			$stringcmp = strcmp($editor,$myname);
			if (($checked == 1) && ($stringcmp <> 0)){
				print "<SCRIPT>alert('The story $title is currently being edited by $editor'); document.location.href='index2.php?option=$option'</SCRIPT>\n";
				exit(0);
				}

			$date = date("H:i:s");
			$query = "UPDATE " . $mpre . "stories SET checked_out='1', checked_out_time='$date', editor='$myname' WHERE sid='$storyid'";
			$database->openConnectionNoReturn($query);

			$handle = opendir($image_path);

			$i = 0;
			while ($file = readdir($handle)) {
				if (($file <> ".") && ($file <> "..")){
					$imagename[$i] = $file;
					$i++;
					}
				}
			closedir($handle);

			$query = "SELECT categoryid, categoryname FROM " . $mpre . "categories WHERE section='News'";
			$result = $database->openConnectionWithReturn($query);
			$i = 0;
			while ($row = mysql_fetch_object($result)){
				$categoryid[$i] = $row->categoryid;
				$categoryname[$i] = $row->categoryname;
				$i++;
				}
			mysql_free_result($result);

			// $query = "SELECT image_position, title, sid, author, introtext, fultext, topic, newsimage, ordering, frontpage FROM stories WHERE sid='$storyid'";
			$query = "SELECT image_position, title, sid, introtext, fultext, topic, newsimage, ordering, frontpage FROM " . $mpre . "stories WHERE sid='$storyid'";
			$result = $database->openConnectionWithReturn($query);
			while ($row = mysql_fetch_object($result)){
				$sid = $row->sid;
				// $author = $row->author;
				$introtext = $row->introtext;
				$fultext = $row->fultext;
				$topicid = $row->topic;
				$title = $row->title;
				$position = $row->image_position;
				$newsimage = $row->newsimage;
				$ordering = $row->ordering;
				$frontpage = $row->frontpage;
				}
			mysql_free_result($result);

			$query = "SELECT * FROM " . $mpre . "stories WHERE frontpage=1";
			$result = $database->openConnectionWithReturn($query);
			$frontpagecount = mysql_num_rows($result);
			mysql_free_result($result);

			$query = "SELECT * FROM " . $mpre . "stories WHERE frontpage=0";
			$result = $database->openConnectionWithReturn($query);
			$restcount = mysql_num_rows($result);
			mysql_free_result($result);

			// $newshtml->editNews($imageid, $imagename, $categoryid, $categoryname, $sid, $author, $introtext, $fultext, $topicid, $title, $position, $newsimage, $ordering, $option, $restcount, $storyid, $categories, $frontpage, $frontpagecount, $text_editor);
			$newshtml->editNews($imageid, $imagename, $categoryid, $categoryname, $sid, $introtext, $fultext, $topicid, $title, $position, $newsimage, $ordering, $option, $restcount, $storyid, $categories, $frontpage, $frontpagecount, $text_editor);			}

		function saveeditnews($database, $option, $image, $introtext, $fultext, $title, $newstopic, $sid, $task, $position, $ordering1, $myname, $porder, $frontpage, $categories, $mpre){
			//print $ordering1;
			if (($title == "") || ($newstopic == "") || ($introtext == "")){
				print "<SCRIPT> alert('Please complete the Title, Topic and Story fields'); window.history.go(-1); </SCRIPT>\n";
				}
			if ($frontpage == ""){
					$frontpage = 0;
					}

			$query = "SELECT * FROM " . $mpre . "stories WHERE sid='$sid' AND checked_out=1 AND editor='$myname'";
			$result = $database->openConnectionWithReturn($query);
			if (mysql_num_rows($result) == 1){
				list($original) = mysql_fetch_array($result);
				if ($frontpage == $original){
					if ($ordering > $porder){
						$query = "SELECT sid FROM " . $mpre . "stories WHERE ordering<='$ordering1' AND ordering > $porder ORDER BY ordering";
						$result = $database->openConnectionWithReturn($query);
						while ($row = mysql_fetch_object($result)){
							$query = "UPDATE " . $mpre . "stories SET ordering=ordering - 1 WHERE sid=$row->sid";
							$database->openConnectionNoReturn($query);
							}
						}
					elseif ($ordering < $porder){
						$query = "SELECT sid FROM " . $mpre . "stories WHERE ordering >= $ordering1 AND ordering < $porder ORDER BY ordering";
						$result = $database->openConnectionWithReturn($query);
						while ($row = mysql_fetch_object($result)){
							$query = "UPDATE " . $mpre . "stories SET ordering=ordering+1 WHERE sid=$row->sid";
							$database->openConnectionNoReturn($query);
							}
						}
					}
				else {
					if ($ordering1){
						$query = "SELECT sid FROM " . $mpre . "stories WHERE ordering > $porder AND frontpage = $frontpage ORDER BY ordering";
						$result = $database->openConnectionWithReturn($query);
						while ($row = mysql_fetch_object($result)){
							$query = "UPDATE " . $mpre . "stories SET ordering=ordering - 1 WHERE sid=$row->sid";
							$database->openConnectionNoReturn($query);
							}

						$query = "SELECT sid FROM " . $mpre . "stories WHERE ordering >= $ordering1 AND frontpage = $frontpage ORDER BY ordering";
						$result = $database->openConnectionWithReturn($query);
						while ($row = mysql_fetch_object($result)){
							$query = "UPDATE " . $mpre . "stories SET ordering=ordering + 1 WHERE sid=$row->sid";
							$database->openConnectionNoReturn($query);
							}
						}
					}

				if ($ordering1 <> ""){
					$query = "UPDATE " . $mpre . "stories SET title='$title', introtext='$introtext', fultext='$fultext', topic='$newstopic', newsimage='$image', image_position='$position', checked_out=0, checked_out_time='00:00:00', editor=NULL, ordering=$ordering1, frontpage=$frontpage WHERE sid='$sid'";
					}
				else {
					if ($ordering1){
						$query = "UPDATE " . $mpre . "stories SET title='$title', introtext='$introtext', fultext='$fultext', topic='$newstopic', image_position='$position', checked_out=0, checked_out_time='00:00:00', editor=NULL, ordering=$ordering1, frontpage=$frontpage WHERE sid='$sid'";
						}
					else {
						$query = "UPDATE " . $mpre . "stories SET title='$title', introtext='$introtext', fultext='$fultext', topic='$newstopic', image_position='$position', checked_out=0, checked_out_time='00:00:00', editor=NULL, frontpage=$frontpage WHERE sid='$sid'";
						}
					}
				$database->openConnectionNoReturn($query);
				?>
				<SCRIPT>
					document.location.href='index2.php?option=<?php
echo $option; ?>&categories=<?php
echo $categories; ?>'
				</SCRIPT>
				<?php
}
			else {
				print "<SCRIPT>alert('Your job has timed out'); document.location.href='index2.php?option=$option'</SCRIPT>\n";
				}
			}

		function removenews($database, $option, $cid, $categories, $mpre){
			if (count($cid) == 0){
				print "<SCRIPT> alert('Select a News item to delete'); window.history.go(-1);</SCRIPT>\n";
				}

			for ($i = 0; $i < count($cid); $i++){
				$query = "SELECT topic, approved, newsimage FROM " . $mpre . "stories WHERE sid='$cid[$i]'";
				$result = $database->openConnectionWithReturn($query);
				while ($row = mysql_fetch_object($result)){
					$topic = $row->topic;
					$approved=$row->approved;
					$image=$row->newsimage;
				}

				$query = "DELETE FROM " . $mpre . "stories WHERE sid='$cid[$i]'";
				$database->openConnectionNoReturn($query);

				$query = "SELECT * FROM " . $mpre . "stories WHERE topic='$topic'";
				$result = $database->openConnectionWithReturn($query);
				$count = mysql_num_rows($result);
				if ($count == 0){
					$query = "UPDATE " . $mpre . "categories SET published=0 WHERE categoryid='$topic'";
					$database->openConnectionNoReturn($query);
					}
				}

			$query = "SELECT sid FROM " . $mpre . "stories ORDER BY ordering";
			$result = $database->openConnectionWithReturn($query);
			$i = 1;
			while ($row = mysql_fetch_object($result)){
				$sid = $row->sid;
				$query = "UPDATE " . $mpre . "stories SET ordering=$i WHERE sid=$sid";
				$database->openConnectionNoReturn($query);
				$i++;
				}

			print "<SCRIPT>document.location.href='index2.php?option=$option&categories=$categories'</SCRIPT>";
		}

		function publishnews($database, $option, $storyid, $cid, $categories, $mpre){
			if (count($cid) > 0){
				for ($i = 0; $i < count($cid); $i++){
					$query = "SELECT " . $mpre . "categories.published AS catpub, " . $mpre . "categories.categoryid AS topic FROM " . $mpre . "categories, " . $mpre . "stories WHERE " . $mpre . "stories.topic=" . $mpre . "categories.categoryid AND sid='$cid[$i]'";
					$result = $database->openConnectionWithReturn($query);
					while ($row = mysql_fetch_object($result)){
						$isitpub = $row->catpub;
						$topic = $row->topic;
						}
					if ($isitpub == 0){
						$query = "UPDATE " . $mpre . "stories SET published=1, editor=NULL, checked_out=0, checked_out_time='00:00:00' WHERE sid='$cid[$i]'";
						$database->openConnectionNoReturn($query);
						$query = "UPDATE " . $mpre . "categories SET published=1 WHERE categoryid='$topic'";
						$database->openConnectionNoReturn($query);
						}
					else {
						$query = "UPDATE " . $mpre . "stories SET published=1, editor=NULL, checked_out=0, checked_out_time='00:00:00' WHERE sid='$cid[$i]'";
						$database->openConnectionNoReturn($query);
						$query = "UPDATE " . $mpre . "categories SET published=1 WHERE categoryid='$topic'";
						$database->openConnectionNoReturn($query);
						}
					mysql_free_result($result);

					$query = "SELECT checked_out FROM " . $mpre . "stories WHERE sid='$cid[0]'";
					$result = $database->openConnectionWithReturn($query);
					while ($row = mysql_fetch_object($result)){
						$checked = $row->checked_out;
						}

					if ($checked == 1){
						print "<SCRIPT>alert('This story cannot be published because it is being edited by another administrator'); document.location.href='index2.php?option=$option&categories=$categories';</SCRIPT>\n";
						exit(0);
						}
					}
				}
			elseif (isset($storyid)){
				$query = "SELECT " . $mpre . "categories.published AS catpub, " . $mpre . "categories.categoryid AS topic FROM " . $mpre . "categories, " . $mpre . "stories WHERE " . $mpre . "stories.topic=" . $mpre . "categories.categoryid AND sid='$storyid'";
				$result = $database->openConnectionWithReturn($query);
				while ($row = mysql_fetch_object($result)){
					$isitpub = $row->published;
					$topic = $row->topic;
					}
				if ($isitpub == 0){
					$query = "UPDATE " . $mpre . "stories SET published='1', editor=NULL, checked_out=0, checked_out_time='00:00:00' WHERE sid='$storyid'";
					$database->openConnectionNoReturn($query);
					$query = "UPDATE " . $mpre . "categories SET published=1 WHERE categoryid='$topic'";
					$database->openConnectionNoReturn($query);
					}
				mysql_free_result($result);
				$query = "UPDATE " . $mpre . "stories SET published='1', editor=NULL, checked_out=0, checked_out_time='00:00:00' WHERE sid='$storyid'";
				$database->openConnectionNoReturn($query);
				}
			else {
				print "<SCRIPT> alert('Select a story to publish'); window.history.go(-1);</SCRIPT>\n";
				}
			print "<SCRIPT>document.location.href='index2.php?option=$option&categories=$categories';</SCRIPT>\n";
			}

		function unpublishnews($database, $option, $storyid, $cid, $categories, $mpre){
			if (count($cid) > 0){
				for ($i = 0; $i < count($cid); $i++){
					$query = "SELECT " . $mpre . "categories.categoryid AS topic FROM " . $mpre . "categories, " . $mpre . "stories WHERE " . $mpre . "stories.topic=" . $mpre . "categories.categoryid AND " . $mpre . "stories.sid='$cid[$i]'";
					$result = $database->openConnectionWithReturn($query);
					while ($row = mysql_fetch_object($result)){
						$topic = $row->topic;
						}

					mysql_free_result($result);

					$query = "SELECT checked_out FROM " . $mpre . "stories WHERE sid='$cid[0]'";
					$result = $database->openConnectionWithReturn($query);
					while ($row = mysql_fetch_object($result)){
						$checked = $row->checked_out;
						}

					if ($checked == 1){
						print "<SCRIPT>alert('This story cannot be published because it is being edited by another administrator'); document.location.href='index2.php?option=$option&categories=$categories';</SCRIPT>\n";
						exit(0);
						}

					$query1 = "UPDATE " . $mpre . "stories SET published=0, editor=NULL, checked_out=0, checked_out_time='00:00:00' WHERE sid='$cid[$i]'";
					$database->openConnectionNoReturn($query1);

					$query = "SELECT * FROM " . $mpre . "stories WHERE published=1 AND topic='$topic'";
					$result = $database->openConnectionWithReturn($query);
					if (mysql_num_rows($result) == 0){
						$query2 = "UPDATE " . $mpre . "categories SET published=0 WHERE categoryid='$topic'";
						$database->openConnectionNoReturn($query2);
						}
					}
				}
			elseif (isset($storyid)){
				$query = "SELECT " . $mpre . "categories.published AS catpub, " . $mpre . "categories.categoryid AS topic FROM " . $mpre . "categories, " . $mpre . "stories WHERE " . $mpre . "stories.topic=" . $mpre . "categories.categoryid AND sid='$storyid'";
				$result = $database->openConnectionWithReturn($query);
				while ($row = mysql_fetch_object($result)){
					$isitpub = $row->published;
					$topic = $row->topic;
					}
				if ($isitpub == 1){
					$query = "UPDATE " . $mpre . "stories SET published='0', editor=NULL, checked_out=0, checked_out_time='00:00:00' WHERE sid='$storyid'";
					$database->openConnectionNoReturn($query);
					//added this check 3/9/01
					if (mysql_num_rows($result) == 0){
						$query = "UPDATE " . $mpre . "categories SET published=0 WHERE categoryid='$topic'";
						$database->openConnectionNoReturn($query);
					}
				}
				mysql_free_result($result);
				$query = "UPDATE " . $mpre . "stories SET published='0', editor=NULL, checked_out=0, checked_out_time='00:00:00' WHERE sid='$storyid'";
				$database->openConnectionNoReturn($query);
				}
			else {
				print "<SCRIPT> alert('Select a story to unpublish'); window.history.go(-1);</SCRIPT>\n";
				}
			print "<SCRIPT>document.location.href='index2.php?option=$option&categories=$categories';</SCRIPT>\n";
			}

		function archivenews($database, $option, $storyid, $cid, $categories, $mpre){
			if (count($cid) > 0){
				$query = "SELECT checked_out FROM " . $mpre . "stories WHERE sid='$cid[0]'";
				$result = $database->openConnectionWithReturn($query);
				while ($row = mysql_fetch_object($result)){
					$checked = $row->checked_out;
					}

				if ($checked == 1){
					print "<SCRIPT>alert('This story cannot be archived because it is being edited by another administrator'); document.location.href='index2.php?option=$option&categories=$categories';</SCRIPT>\n";
					exit(0);
					}

				for ($i = 0; $i < count($cid); $i++){
					$query = "UPDATE " . $mpre . "stories SET archived='1' WHERE sid='$cid[$i]'";
					$database->openConnectionNoReturn($query);
					}
				}
			else {
				print "<SCRIPT>alert('Please select a news story to archive');</SCRIPT>\n";
				}
			print "<SCRIPT>document.location.href='index2.php?option=$option&categories=$categories';</SCRIPT>\n";
			}

		function unarchivenews($database, $option, $storyid, $cid, $categories, $mpre){
			if (count($cid) > 0){
				$query = "SELECT checked_out FROM " . $mpre . "stories WHERE sid='$cid[0]'";
				$result = $database->openConnectionWithReturn($query);
				while ($row = mysql_fetch_object($result)){
					$checked = $row->checked_out;
				}

				if ($checked == 1){
					print "<SCRIPT>alert('This story cannot be unarchived because it is being edited by another administrator'); document.location.href='index2.php?option=$option&categories=$categories';</SCRIPT>\n";
					exit(0);
				}

				for ($i = 0; $i < count($cid); $i++){
					$query = "UPDATE " . $mpre . "stories SET archived='0' WHERE sid='$cid[$i]'";
					$database->openConnectionNoReturn($query);
				}
			}else {
				print "<SCRIPT>alert('Please select a news story to unarchive');</SCRIPT>\n";
			}
			print "<SCRIPT>document.location.href='index2.php?option=$option&categories=$categories';</SCRIPT>\n";
		}

		function approvenews($database, $option, $introtext, $fultext, $newstopic, $image, $mytitle, $ordering, $position, $frontpage, $sid, $porder, $categories, $mpre){
			$query = "UPDATE " . $mpre . "stories SET approved=1, checked_out=0, checked_out_time='00:00:00', editor=NULL, topic=$newstopic, introtext='$introtext', fultext='$fultext',  title='$mytitle', newsimage='$image', image_position='$position', frontpage='$frontpage', published='1' WHERE sid=$sid";
			$database->openConnectionNoReturn($query);

			if ($frontpage==1){
				if ($ordering > $porder){
					$query = "SELECT sid FROM " . $mpre . "stories WHERE ordering<='$ordering' AND ordering > $porder ORDER BY ordering";
					$result = $database->openConnectionWithReturn($query);
					while ($row = mysql_fetch_object($result)){
						$query = "UPDATE " . $mpre . "stories SET ordering=ordering - 1 WHERE sid=$row->sid";
						$database->openConnectionNoReturn($query);
					}
				}elseif ($ordering < $porder){
					$query = "SELECT sid FROM " . $mpre . "stories WHERE ordering >= $ordering AND ordering < $porder ORDER BY ordering";
					$result = $database->openConnectionWithReturn($query);
					while ($row = mysql_fetch_object($result)){
						$query = "UPDATE " . $mpre . "stories SET ordering=ordering+1 WHERE sid=$row->sid";
						$database->openConnectionNoReturn($query);
					}
				}
			}
			print "<SCRIPT>document.location.href='index2.php?option=$option&categories=$categories';</SCRIPT>\n";
		}
}?>
