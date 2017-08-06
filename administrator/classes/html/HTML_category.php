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
	 *	File Name: HTML_category.php
	 *	Developers: Danny Younes - danny@miro.com.au
	 *				Nicole Anderson - nicole@miro.com.au
	 *	Date: 27-11-2002
	 * 	Version #: 4.0.11
	 *	Comments:
	**/
	
	class HTML_category {
		function showcategory($option, $cid, $cname, $act, $count, $publish, $checkedout, $editor){ ?>
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
			<FORM ACTON='index2.php' METHOD='POST' NAME="adminForm">
			<TABLE CELLPADDING="5" CELLSPACING="0" BORDER="0" WIDTH="100%">
			<TR BGCOLOR="#999999">
				<TD WIDTH="20">&nbsp;</TD>
				<TD WIDTH="60%" CLASS="heading">Category Name - <?php
echo $option; ?></TD>
				<TD WIDTH="15%" ALIGN="center" CLASS="heading"># of <?php
echo $option; ?></TD>
				<TD WIDTH="10%" ALIGN="center" CLASS="heading">Published</TD>
				<TD WIDTH="15%" ALIGN="center" CLASS="heading">Checked Out</TD>
			</TR>
			<?php
$color = array("#FFFFFF", "#CCCCCC");
			$k = 0;
			for ($i = 0; $i < count($cid); $i++){?>
			<TR BGCOLOR="<?php
echo $color[$k]; ?>">
				<TD WIDTH="20"><INPUT TYPE="checkbox" NAME="cid[]" VALUE="<?php
echo $cid[$i]; ?>" onClick="isChecked(this.checked);"></TD>
				<TD WIDTH="60%"><?php
echo $cname[$i]; ?></TD>
				<TD WIDTH="15%" ALIGN="center"><?php
echo $count[$i]; ?></TD>
			<?php
if ($publish[$i] == 1){
					if ($color[$k] == "#FFFFFF"){?>
						<TD WIDTH="10%" ALIGN="center"><IMG SRC="../images/admin/greytic.gif"></TD>
			<?php
} else {?>
						<TD WIDTH="10%" ALIGN="center"><IMG SRC="../images/admin/whttic.gif"></TD>
			<?php
}
					}
				else {
					if ($color[$k] == "#FFFFFF"){?>
						<TD WIDTH="10%" ALIGN="center">&nbsp;</TD>
			<?php
} else {?>
						<TD WIDTH="10%" ALIGN="center">&nbsp;</TD>
			<?php
}
					}?>
			
			<?php
if ($checkedout[$i] == 0){?>
					<TD WIDTH="15%" ALIGN="center">&nbsp;</TD>
			<?php
}
				else {?>
					<TD WIDTH="15%" ALIGN="center"><?php
echo $editor[$i]; ?></TD>
			<?php
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
			<INPUT TYPE="hidden" NAME="chosen" VALUE="">
			<INPUT TYPE="hidden" NAME="act" VALUE="<?php
echo $act; ?>">
			<INPUT TYPE="hidden" NAME="boxchecked" VALUE="0">
			</FORM>
			</TABLE>
		<?php
}
		
		function editcategory($option, $cname, $uid, $act){ ?>
			<FORM ACTON='index2.php' METHOD='POST' NAME="adminForm">
			<TABLE CELLPADDING="5" CELLSPACING="0" BORDER="0" WIDTH="100%">
			<TR BGCOLOR="#999999">
				<TD ALIGN="left" CLASS="heading" COLSPAN="3">Edit Category - <?php
echo $option; ?></TD>
			</TR>
			<TR>
				<TD ALIGN="center" COLSPAN="3">&nbsp;</TD>
			</TR>
			<TR>
				<TD WIDTH='150'>Category Name:</TD>
				<TD WIDTH='85%'><INPUT TYPE='text' NAME='categoryname' VALUE="<?php
echo $cname; ?>" SIZE='25'></TD>
			</TR>
			</TABLE>
			
			
			<INPUT TYPE='hidden' NAME='task' VALUE="">
			<INPUT TYPE='hidden' NAME='option' VALUE="<?php
echo $option; ?>">
			<INPUT TYPE='hidden' NAME='uid' VALUE='<?php
echo $uid; ?>'>
			<INPUT TYPE="hidden" NAME="act" VALUE="<?php
echo $act; ?>">
			<INPUT TYPE="hidden" NAME="pname" VALUE="<?php
echo $cname; ?>">
			</FORM>
		<?php
}
		
		function addcategory($option, $act){?>
			<FORM ACTON='index2.php' METHOD='POST' NAME="adminForm">
			<TABLE CELLPADDING="5" CELLSPACING="0" BORDER="0" WIDTH="100%">
			<TR BGCOLOR="#999999">
				<TD ALIGN="left" CLASS="heading" COLSPAN="3">Add Category - <?php
echo $option; ?></TD>
			</TR>
			<TR>
				<TD ALIGN="center" COLSPAN="3">&nbsp;</TD>
			</TR>
			<TR>
				<TD WIDTH='150' VALIGN="top">Category Name:</TD>
				<TD WIDTH='85%' VALIGN="top"><INPUT TYPE='text' NAME='categoryname' VALUE="<?php
echo $cname; ?>" SIZE='25'></TD>
			</TR>
			</TABLE>
			
			<INPUT TYPE='hidden' NAME='task' VALUE="">
			<INPUT TYPE='hidden' NAME='option' VALUE="<?php
echo $option; ?>">
			<INPUT TYPE="hidden" NAME="act" VALUE="<?php
echo $act; ?>">
			</FORM>
		<?php
}
		}
?>
