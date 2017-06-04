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
	 *	File Name: HTML_newsflash.php
	 *	Developers: Danny Younes - danny@miro.com.au
	 *				Nicole Anderson - nicole@miro.com.au
	 *	Date: 27-11-2002
	 * 	Version #: 4.0.11
	 *	Comments:
	**/
	
	class HTML_newsflash {
		function shownewsflash($nfid, $flashtitle, $status, $option, $editor){?>
			<FORM ACTION="index2.php" METHOD="POST" NAME="adminForm">
			<TABLE CELLPADDING="5" CELLSPACING="0" BORDER="0" WIDTH="100%">
			<TR BGCOLOR=#999999>
				<TD COLSPAN="2" CLASS="heading">Newsflash Items</TD>
				<TD ALIGN=CENTER CLASS="heading">Published</TD>
				<TD ALIGN=CENTER CLASS="heading">Checked Out</TD>
			</TR>
			<?php
$color = array("#FFFFFF", "#CCCCCC");
			$k = 0;
			for ($i = 0; $i < count($nfid); $i++){?>
			<TR BGCOLOR="<?php
echo $color[$k]; ?>">
				<TD WIDTH="20"><INPUT TYPE="checkbox" NAME="cid[]" VALUE="<?php
echo $nfid[$i]; ?>" onClick="isChecked(this.checked);"></TD>
				<TD WIDTH="77%"><?php
echo $flashtitle[$i]; ?></TD>
				<?php if ($status[$i] == "yes"){
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
				<?php
if ($editor[$i] <> ""){?>
						<TD WIDTH="10%" ALIGN=CENTER><?php
echo $editor[$i];?></TD>
				<?php
}
					else {?>
						<TD WIDTH="10%" ALIGN=CENTER>&nbsp;</TD>
					<?php
}
				
				 if ($k == 1){
				   		$k = 0;
					}else {
				   		$k++;
					}
				}?>
			</TR>
				<INPUT TYPE="hidden" NAME="option" VALUE="<?php
echo $option; ?>">
				<INPUT TYPE="hidden" NAME="task" VALUE="">
				<INPUT TYPE="hidden" NAME="boxchecked" VALUE="0">
			</FORM>
			</TABLE>
			<?php
}
			
			
		function addNewsflash($option, $text_editor){?>
			<FORM ACTION="index2.php" METHOD="POST" NAME="adminForm">
			<TABLE CELLPADDING="5" CELLSPACING="0" BORDER="0" WIDTH="100%">
			<TR BGCOLOR=#999999>
				<TD COLSPAN="2" CLASS="heading">Add New Newsflash</TD>
			</TR>
			<TR>
				<TD COLSPAN=2>&nbsp;</TD>
			</TR>
			<TR>
				<TD VALIGN="top">Title:</TD>
				<TD WIDTH="85%"><INPUT TYPE="text" NAME="flashtitle" SIZE="20"></TD>
			</TR>
			<TR>
				<TD VALIGN="top">Content (Max 600 chars):</TD>
				<TD><TEXTAREA COLS="70" ROWS="10" NAME="content" STYLE="WIDTH=500px" WIDTH=500><?php
echo htmlentities($flashcontent); ?></TEXTAREA></TD>
			</TR>
			<?php
if ($text_editor == true){?>
				<TR>
					<TD>&nbsp;</TD>
					<TD VALIGN="top"><A HREF="#" onClick="window.open('inline_editor/editor.htm', 'win1', 'width=650, height=450, resizable=yes');">Edit Text In Editor</A></TD>
				</TR>
			<?php
}?>
			<INPUT TYPE="hidden" NAME="option" VALUE="<?php
echo $option; ?>">
			<INPUT TYPE="hidden" NAME="task" VALUE="">
			</FORM>
			<TR>
				<TD COLSPAN=2>&nbsp;</TD>
			</TR>
			<TR BGCOLOR=#999999>
				<TD COLSPAN=2>&nbsp;</TD>
			</TR>
			</TABLE>
			<?php
}
		
			
		function editNewsflash($newsflashid, $flashtitle, $flashcontent, $option, $myname, $text_editor){?>
			<FORM ACTION="index2.php" METHOD="POST" NAME="adminForm">
			<TABLE CELLPADDING="5" CELLSPACING="0" BORDER="0" WIDTH="100%">
			<TR BGCOLOR=#999999>
				<TD COLSPAN="2" CLASS="heading">Edit Newsflash Item</TD>
			</TR>
			<TR>
				<TD COLSPAN=2>&nbsp;</TD>
			</TR>
			<TR>
				<TD VALIGN="top">Title:</TD>
				<TD WIDTH="85%"><INPUT TYPE="text" NAME="flashtitle" SIZE="25" VALUE="<?php
echo $flashtitle; ?>"></TD>
			</TR>
			<TR>
				<TD VALIGN="top">Content (Max 600 chars):</TD>
				<TD><TEXTAREA COLS="70" ROWS="10" NAME="content" STYLE="WIDTH=500px" WIDTH=500><?php
echo htmlentities($flashcontent); ?></TEXTAREA></TD>
			</TR>
			<?php
if ($text_editor == true){?>
				<TR>
					<TD>&nbsp;</TD>
					<TD VALIGN="top"><A HREF="#" onClick="window.open('inline_editor/editor.htm', 'win1', 'width=650, height=450, resizable=yes');">Edit Text In Editor</A></TD>
				</TR>
			<?php
}?>
			<INPUT TYPE="hidden" NAME="option" VALUE="<?php
echo $option; ?>">
			<INPUT TYPE="hidden" NAME="newsflashid" VALUE="<?php
echo $newsflashid; ?>">
			<INPUT TYPE="hidden" NAME="task" VALUE="">
			<INPUT TYPE="hidden" NAME="myname" VALUE="<?php echo $myname;?>">
			<TR>
				<TD COLSPAN=2>&nbsp;</TD>
			</TR>
			<TR BGCOLOR=#999999>
				<TD COLSPAN=2>&nbsp;</TD>
			</TR>
			</TABLE>
			</FORM>
			<?php	}
		}
?>
