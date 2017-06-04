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
	 *	File Name: HTML_articles.php
	 *	Developers: Danny Younes - danny@miro.com.au
	 *				Nicole Anderson - nicole@miro.com.au
	 *	Date: 27-11-2002
	 * 	Version #: 3.0.5
	 *	Comments:
	**/
	
	class HTML_articles {
		function showArticles($artid, $title, $approved, $author, $usertype, $option, $published, $checkedout, $editor, $archived, $categoryid, $categoryname, $categories){ ?>
			<FORM ACTION='index2.php' METHOD='POST' NAME="adminForm">
			<TABLE CELLPADDING="5" CELLSPACING="0" BORDER="0" WIDTH="100%">
			<TR>
				<TD COLSPAN = "7" align=right>
					<SELECT NAME="categories" onChange="document.location.href='index2.php?option=Articles&categories=' + document.adminForm.categories.options[selectedIndex].value">
						<OPTION VALUE="">Select a category</OPTION>
						<?php if ($categories =="all"){?>
							<OPTION VALUE="all" selected>Select All</OPTION>
							<OPTION VALUE="new">Select NEW</OPTION>
						<?php
}elseif ($categories == "new"){?>
							<OPTION VALUE="all">Select All</OPTION>
							<OPTION VALUE="new"selected>Select NEW</OPTION>
						<?php
}else{?>
							<OPTION VALUE="all">Select All</OPTION>
							<OPTION VALUE="new">Select NEW</OPTION>
						 <?php
}
						for ($i = 0; $i < count($categoryid); $i++){
							if ($categories == $categoryid[$i]){?>
								<OPTION VALUE="<?php
echo $categoryid[$i]; ?>" SELECTED><?php
echo $categoryname[$i]; ?></OPTION>
					<?php
} else {?>
								<OPTION VALUE="<?php
echo $categoryid[$i]; ?>"><?php
echo $categoryname[$i]; ?></OPTION>
					<?php
}
							}?>
					</SELECT>
				</TD>
			</TR>
			<TR BGCOLOR="#999999">
				<TD COLSPAN="2" WIDTH="60%" CLASS="heading">Articles Manager</TD>
				<TD WIDTH="15%" ALIGN="center" CLASS="heading">Submitted By</TD>
				<TD WIDTH="5%" ALIGN="center" CLASS="heading">Published</TD>
				<TD WIDTH="5%" ALIGN="center" CLASS="heading">Archived</TD>
				<TD WIDTH="15%" ALIGN="center" CLASS="heading">Checked Out</TD>
				<TD WIDTH="5%" ALIGN="center" CLASS="heading">Approved</TD>
			</TR>
			<?php
$color = array("#FFFFFF", "#CCCCCC");
			$k = 0;
			for ($i = 0; $i < count($artid); $i++){?>
			<TR BGCOLOR="<?php
echo $color[$k]; ?>">
				<TD WIDTH=2><INPUT TYPE="checkbox" NAME="cid[]" VALUE="<?php
echo $artid[$i]; ?>" onClick="isChecked(this.checked);"></TD>
			<?php
if ($approved[$i] == 0){?>
					<TD WIDTH="60%"><A HREF="index2.php?option=<?php
echo $option; ?>&task=edit&artid=<?php
echo $artid[$i]; ?>&categories=<?php
echo $categories; ?>"><?php
echo $title[$i]; ?></A></TD>
			<?php
}
				else {?>
					<TD WIDTH="60%"><?php
echo $title[$i]; ?></A></TD>
			<?php
}?>
			
			<?php
if ($author[$i] == ""){?>
					<TD WIDTH="15%" ALIGN="center">&nbsp;</TD>
			<?php
}
				else {?>
					<TD WIDTH="15%" ALIGN="center"><?php echo $author[$i];?></TD>
			<?php
}?>
					
			<?php
if ($published[$i] == 1){
					if ($color[$k] == "#FFFFFF"){?>
						<TD WIDTH="5%" ALIGN="center"><IMG SRC="../images/admin/greytic.gif"></TD>
			<?php
} else {?>
						<TD WIDTH="5%" ALIGN="center"><IMG SRC="../images/admin/whttic.gif"></TD>
			<?php
}
					}
				else {
					if ($color[$k] == "#FFFFFF"){?>
						<TD WIDTH="5%" ALIGN="center">&nbsp;</TD>
			<?php
} else {?>
						<TD WIDTH="5%" ALIGN="center">&nbsp;</TD>
			<?php
}
					}?>
				
			<?php
if ($archived[$i] == 1){
					if ($color[$k] == "#FFFFFF"){?>
						<TD WIDTH="5%" ALIGN="center"><IMG SRC="../images/admin/greytic.gif"></TD>
			<?php
} else {?>
						<TD WIDTH="5%" ALIGN="center"><IMG SRC="../images/admin/whttic.gif"></TD>
			<?php
}
					}
				else {
					if ($color[$k] == "#FFFFFF"){?>
						<TD WIDTH="5%" ALIGN="center">&nbsp;</TD>
			<?php
} else {?>
						<TD WIDTH="5%" ALIGN="center">&nbsp;</TD>
			<?php
}
					}?>
			
			<?php
if ($checkedout[$i] == 1){?>
					<TD WIDTH="15%" ALIGN="center"><?php
echo $editor[$i]; ?></TD>
			<?php
}
				else {?>
					<TD WIDTH="15%" ALIGN="center">&nbsp;</TD>
			<?php
}?>
				
				<?php if ($approved[$i]==1){
					if ($color[$k] == "#FFFFFF"){?>
						<TD WIDTH="5%" ALIGN="center"><IMG SRC="../images/admin/greytic.gif"></TD>
			<?php
} else {?>
						<TD WIDTH="5%" ALIGN="center"><IMG SRC="../images/admin/whttic.gif"></TD>
			<?php
}
				}else {
					if ($color[$k] == "#FFFFFF"){?>
						<TD WIDTH="5%" ALIGN="center">&nbsp;</TD>
			<?php
} else {?>
						<TD WIDTH="5%" ALIGN="center">&nbsp;</TD>
			<?php
}
				}?>
				
				<?php
if ($k == 1){
						$k = 0;
						}
				   else {
				   		$k++;
						}?>
				<?php
}?>
			</TR>
			<INPUT TYPE='hidden' NAME='option' VALUE='<?php
echo $option; ?>'>
			<INPUT TYPE="hidden" NAME="task" VALUE="">
			<INPUT TYPE="hidden" NAME="cat" VALUE="<?php
echo $categories; ?>">
			<INPUT TYPE="hidden" NAME="boxchecked" VALUE="0">
			</FORM>
			</TABLE>
			<?php
}
			
		function editarticle($secid, $artid, $title, $content, $secname, $secsecid, $sectionsecid, $sectionname, $sectionimage, $option, $task, $ordering, $maxnum, $categorycid, $categorytitle, $orderingarticles, $categories, $author, $text_editor){?>
			<SCRIPT LANGUAGE="Javascript">
			<!--
					categories = new Array();
			<?php
for ($i = 0; $i < count($categorytitle); $i++){?>
			<?php
$var = split(" ", $categorytitle[$i]);
					$count = count($var);
					for ($j = 0; $j < $count; $j++)
						$newvar .= $var[$j];?>
					categories["<?php
echo $newvar; ?>"] = <?php
echo $ordering["$categorytitle[$i]"]; ?>;
			<?php
unset($newvar); ?>
			<?php
}?>
			
			//-->
			</SCRIPT>
			<FORM ACTION='index2.php' METHOD='POST' NAME="adminForm">
			<TABLE CELLPADDING="5" CELLSPACING="0" BORDER="0" WIDTH="100%">
			<TR BGCOLOR="#999999">
				<TD ALIGN="left" CLASS="heading" COLSPAN="2">Edit Articles</TD>
			</TR>
			<TR>
				<TD ALIGN="center" COLSPAN="2">&nbsp;</TD>
			</TR>
			<TR>
				<TD WIDTH='100'>Title:</TD>
				<TD><INPUT TYPE='text' NAME='mytitle' SIZE='70' VALUE="<?php
echo htmlspecialchars($title,ENT_QUOTES); ?>"></TD>
			</TR>
			<TR>
				<TD>Section:</TD>
				<TD>
					<SELECT NAME='category' >
						<?php
for ($i = 0; $i < count($sectionsecid); $i++){
							if ($secsecid == $sectionsecid[$i]){?>
								<OPTION VALUE='<?php
echo $sectionsecid[$i]; ?>' SELECTED><?php
echo $sectionname[$i]; ?></OPTION>
							<?php
}
							else {?>
								<OPTION VALUE='<?php
echo $sectionsecid[$i]; ?>'><?php
echo $sectionname[$i]; ?></OPTION>
						<?php
}
							}?>
					</SELECT>
				</TD>
			</TR>
			<TR>
				<TD>Author</TD>
				<TD><INPUT TYPE='text' NAME='author' VALUE='<?php echo $author;?>'></TD>
			</TR>
			<TR>
				<TD VALIGN="top">Content:</TD>
				<TD VALIGN='top'><TEXTAREA COLS='70' ROWS='15' NAME='content'><?php
echo htmlentities($content); ?></TEXTAREA></TD>
			</TR>
			<?php
if ($text_editor == true){?>
				<TR>
					<TD>&nbsp;</TD>
					<TD VALIGN="top"><A HREF="#" onClick="window.open('inline_editor/editor.htm', 'win1', 'width=650, height=450, resizable=yes');">Edit Text In Editor</A></TD>
				</TR>
			<?php
}?>
			<TR>
				<TD ALIGN="center" COLSPAN="2">&nbsp;</TD>
			</TR>
			<TR BGCOLOR="#999999">
				<TD ALIGN="left" CLASS="heading" COLSPAN="2">&nbsp;</TD>
			</TR>
			</TABLE>
			<INPUT TYPE='hidden' NAME='artid' VALUE="<?php
echo $artid; ?>">
			<INPUT TYPE='hidden' NAME='option' VALUE="<?php
echo $option; ?>">
			<INPUT TYPE='hidden' NAME='task' VALUE="">
			<INPUT TYPE="hidden" NAME="porder" VALUE="<?php
echo $orderingarticles; ?>">
			<INPUT TYPE="hidden" NAME="pcategory" VALUE="<?php
echo $secsecid; ?>">
			<INPUT TYPE="hidden" NAME="categories" VALUE="<?php
echo $categories; ?>">
			</FORM>
		<?php
}
		
		function addArticle($categorycid, $categorytitle, $option, $ordering, $categories, $text_editor){?>
			<SCRIPT LANGUAGE="Javascript">
			<!--
					categories = new Array();
			<?php
for ($i = 0; $i < count($categorytitle); $i++){?>
			<?php
$var = split(" ", $categorytitle[$i]);
					$count = count($var);
					for ($j = 0; $j < $count; $j++)
						$newvar .= $var[$j];?>
					categories["<?php
echo $newvar; ?>"] = <?php
echo $ordering["$categorytitle[$i]"]; ?>;
			<?php
unset($newvar); ?>
			<?php
}?>
			
			//-->
			</SCRIPT>
			<FORM ACTION='index2.php' METHOD='POST' NAME="adminForm">
			<TABLE CELLPADDING="5" CELLSPACING="0" BORDER="0" WIDTH="100%">
			<TR BGCOLOR="#999999">
				<TD ALIGN="left" CLASS="heading" COLSPAN="2">Add Article</TD>
			</TR>
			<TR>
				<TD ALIGN="center" COLSPAN="2">&nbsp;</TD>
			</TR>
			<TR>
				<TD WIDTH='100'>Title:</TD>
				<TD><INPUT TYPE='text' NAME='mytitle' SIZE='70' VALUE="<?php
echo $title; ?>"></TD>
			</TR>
			<TR>
				<TD>Section:</TD>
				<TD COLSPAN='3'>
					<SELECT NAME='category'>
						<?php
for ($i = 0; $i < count($categorycid); $i++){?>
								<OPTION VALUE='<?php
echo $categorycid[$i]; ?>'><?php
echo $categorytitle[$i]; ?></OPTION>
						<?php
}?>
					</SELECT>
				</TD>
			</TR>
			<TR>
				<TD>Author</TD>
				<TD><INPUT TYPE='text' NAME='author'></TD>
			</TR>
			<TR>
				<TD VALIGN="top">Content:</TD>
				<TD VALIGN='top'><TEXTAREA COLS='70' ROWS='15' NAME='content'><?php
echo htmlentities($content); ?></TEXTAREA></TD>
			</TR>
			<?php
if ($text_editor == true){?>
				<TR>
					<TD>&nbsp;</TD>
					<TD VALIGN="top"><A HREF="#" onClick="window.open('inline_editor/editor.htm', 'win1', 'width=650, height=450, resizable=yes');">Edit Text In Editor</A></TD>
				</TR>
			<?php
}?>
			<TR>
				<TD ALIGN="center" COLSPAN="2">&nbsp;</TD>
			</TR>
			<TR BGCOLOR="#999999">
				<TD ALIGN="left" CLASS="heading" COLSPAN="2">&nbsp;</TD>
			</TR>
			</TABLE>
			<INPUT TYPE='hidden' NAME='option' VALUE="<?php
echo $option; ?>">
			<INPUT TYPE='hidden' NAME='task' VALUE="">
			<INPUT TYPE='hidden' NAME='categories' VALUE="<?php
echo $categories; ?>">
			</FORM>
		<?php
}
		
		}
?>
