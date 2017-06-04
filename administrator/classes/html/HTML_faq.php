<?php	
	/**	
	 *	Mambo Site Server Open Source Edition Version 4.0.11
	 *	Dynamic portal server and Content managment engine
	 *	27-11-2002
 	 *
	 *	Copyright (C) 2000 - 2002 Miro Contruct Pty Ltd
	 *	Distributed under the terms of the GNU General Public License
	 *	This software may be used without warrany provided these statements are left 
	 *	intact and a "Powered By Mambo" appears at the bottom of each HTML page.
	 *	This code is Available at http://sourceforge.net/projects/mambo
	 *
	 *	Site Name: Mambo Site Server Open Source Edition Version 4.0.11
	 *	File Name: HTML_faq.php
	 *	Developers: Danny Younes - danny@miro.com.au
	 *				Nicole Anderson - nicole@miro.com.au
	 *	Date: 27-11-2002
	 * 	Version #: 4.0.11
	 *	Comments:
	**/
	
	class HTML_faq {
		function showFaq($option, $artid, $title, $published, $editor, $archived, $checkedout, $categoryid, $categoryname, $categories, $approved){ ?>
			<SCRIPT LANGUAGE="javascript">
			<!--
				function isChecked(isitchecked){
					if (isitchecked == false){
						document.adminForm.boxchecked.value--;
						}
					else {
						document.adminForm.boxchecked.value++;
						}
					}
			//-->
			</SCRIPT>
			<FORM ACTION='index2.php' METHOD='POST' NAME="adminForm">
			<TABLE CELLPADDING="5" CELLSPACING="0" BORDER="0" WIDTH="100%">
			<TR>
				<TD COLSPAN="6" align=right>Select A Category:&nbsp;&nbsp;
					<SELECT NAME="categories" onChange="document.location.href='index2.php?option=Faq&categories=' + document.adminForm.categories.options[selectedIndex].value">
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
				<TD COLSPAN="2" CLASS="heading">FAQ Manager</TD>
				<TD WIDTH="10%" ALIGN="center" CLASS="heading">Published</TD>
				<TD WIDTH="10%" ALIGN="center" CLASS="heading">Archived</TD>
				<TD WIDTH="15%" ALIGN="center" CLASS="heading">Checked Out</TD>
				<TD WIDTH="5%" ALIGN="center" CLASS="heading">Approved</TD>
			</TR>
			<?php
$color = array("#FFFFFF", "#CCCCCC");
			$k = 0;
			for ($i = 0; $i < count($artid); $i++){?>
				<TR BGCOLOR="<?php
echo $color[$k]; ?>">
					<TD WIDTH="20"><INPUT TYPE="checkbox" NAME="cid[]" VALUE="<?php
echo $artid[$i]; ?>" onClick="isChecked(this.checked);"></TD>
					<?php
if ($approved[$i] == 0){?>
						<TD WIDTH="70%"><A HREF="index2.php?option=<?php
echo $option; ?>&task=edit&artid=<?php
echo $artid[$i]; ?>&categories=<?php
echo $categories; ?>"><?php
echo $title[$i]; ?></A></TD>
					<?php
}else {?>
						<TD WIDTH="70%"><?php
echo $title[$i]; ?></A></TD>
					<?php
}?>	
					
					<?php if ($published[$i] == 1){
						if ($color[$k] == "#FFFFFF"){?>
							<TD WIDTH="10%" ALIGN="center"><IMG SRC="../images/admin/greytic.gif"></TD>
						<?php
} else {?>
							<TD WIDTH="10%" ALIGN="center"><IMG SRC="../images/admin/whttic.gif"></TD>
						<?php
}
					}else {
						if ($color[$k] == "#FFFFFF"){?>
							<TD WIDTH="10%" ALIGN="center">&nbsp;</TD>
						<?php
} else {?>
							<TD WIDTH="10%" ALIGN="center">&nbsp;</TD>
						<?php
}
					}?>
				
					<?php if ($archived[$i] == 1){
						if ($color[$k] == "#FFFFFF"){?>
							<TD WIDTH="10%" ALIGN="center"><IMG SRC="../images/admin/greytic.gif"></TD>
						<?php
} else {?>
							<TD WIDTH="10%" ALIGN="center"><IMG SRC="../images/admin/whttic.gif"></TD>
						<?php
}
					}else {
						if ($color[$k] == "#FFFFFF"){?>
							<TD WIDTH="10%" ALIGN="center">&nbsp;</TD>
						<?php
} else {?>
							<TD WIDTH="10%" ALIGN="center">&nbsp;</TD>
						<?php
}
					}?>
			
					<?php if ($checkedout[$i] == 1){?>
						<TD WIDTH="10%" ALIGN="center"><?php
echo $editor[$i]; ?></TD>
					<?php
}	else {?>
						<TD WIDTH="10%" ALIGN="center">&nbsp;</TD>
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
				
					<?php if ($k == 1){
						$k = 0;
					}else {
				 		$k++;
					}?>
				</TR>
			<?php
}?>
			
			<INPUT TYPE='hidden' NAME='option' VALUE='<?php
echo $option; ?>'>
			<INPUT TYPE="hidden" NAME="task" VALUE="">
			<INPUT TYPE="hidden" NAME="cat" VALUE="">
			<INPUT TYPE="hidden" NAME="boxchecked" VALUE="0">
			</FORM>
			</TABLE>
			<?php
}
			
		function editFaq($artid, $title, $content, $categoryid, $categoryname, $faqcategoryid, $faqcategoryname, $option, $ordering, $maxnum, $categorycid, $categorytitle, $faqid, $orderingfaq, $categories, $text_editor){?>
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
if ($categorycid[$i] == $faqid){?>
						var originalcategory = "<?php
echo $newvar; ?>";
			<?php
}?>
			<?php
unset($newvar); ?>
			<?php
}?>
				
			//-->
			</SCRIPT>
			
			<FORM ACTION='index2.php' METHOD='POST' NAME="adminForm">
			<TABLE CELLPADDING="5" CELLSPACING="0" BORDER="0" WIDTH="100%">
			<TR BGCOLOR="#999999">
				<TD ALIGN="left" CLASS="heading" COLSPAN="2">Edit Faq's</TD>
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
					<SELECT NAME='category'>
						<?php
for ($i = 0; $i < count($categorycid); $i++){
							if ($faqcategoryid == $categorycid[$i]){?>
								<OPTION VALUE='<?php
echo $categorycid[$i]; ?>' SELECTED><?php
echo $categorytitle[$i]; ?></OPTION>
							<?php
}
							else {?>
								<OPTION VALUE='<?php
echo $categorycid[$i]; ?>'><?php
echo $categorytitle[$i]; ?></OPTION>
						<?php
}
							}?>
					</SELECT>
				</TD>
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
			<INPUT TYPE='hidden' NAME='porder' VALUE="<?php
echo $orderingfaq; ?>">
			<INPUT TYPE='hidden' NAME='categories' VALUE="<?php
echo $categories; ?>">
			<INPUT TYPE="hidden" NAME="pcategory" VALUE="<?php
echo $faqid; ?>">
			</FORM>
		<?php
}
		
		function addFaq($option, $categorycid, $categorytitle, $ordering, $text_editor, $categories){?>
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
			<FORM ACTON='index2.php' METHOD='POST' NAME="adminForm">
			<TABLE CELLPADDING="5" CELLSPACING="0" BORDER="0" WIDTH="100%">
			<TR BGCOLOR="#999999">
				<TD ALIGN="left" CLASS="heading" COLSPAN="2">Add Faq's</TD>
			</TR>
			<TR>
				<TD ALIGN="center" COLSPAN="2">&nbsp;</TD>
			</TR>
			<TR>
				<TD WIDTH='100'>Title:</TD>
				<TD><INPUT TYPE='text' NAME='mytitle' SIZE='70' VALUE=""></TD>
			</TR>
			<TR>
				<TD>Section:</TD>
				<TD>
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
				<TD VALIGN="top">Content:</TD>
				<TD VALIGN='top'><TEXTAREA COLS='70' ROWS='15' NAME='content'><?php
echo htmlentities($content);?></TEXTAREA></TD>
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
