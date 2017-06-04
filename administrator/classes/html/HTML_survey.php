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
	 *	File Name: HTML_survey.php
	 *	Developers: Danny Younes - danny@miro.com.au
	 *				Nicole Anderson - nicole@miro.com.au
	 *	Date: 27-11-2002
	 * 	Version #: 4.0.11
	 *	Comments:
	**/
	
	class HTML_survey {
		function showSurvey($option, $pollid, $polltitle, $published, $editor){ ?>
			<FORM ACTION='index2.php' METHOD='GET' NAME="adminForm">
			<TABLE CELLPADDING="5" CELLSPACING="0" BORDER="0" WIDTH="100%">
			<TR BGCOLOR="#999999">
				<TD COLSPAN="2" CLASS="heading">Poll/Survey Manager</TD>
				<TD WIDTH="10%" CLASS="heading" ALIGN="center">Published</TD>
				<TD WIDTH="15%" CLASS="heading" ALIGN="center">Checked Out</TD>
			</TR>
			<?php
$color = array("#FFFFFF", "#CCCCCC");
			$k = 0;
			for ($i = 0; $i < count($pollid); $i++){?>
			<TR BGCOLOR="<?php
echo $color[$k]; ?>">
				<TD WIDTH="20"><INPUT TYPE="checkbox" NAME="cid[]" VALUE="<?php
echo $pollid[$i]; ?>" onClick="isChecked(this.checked);"></TD>
				<TD WIDTH="85%"><?php
echo $polltitle[$i]; ?></TD>
				<?php
if ($published[$i] == 1){
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
if ($editor[$i] <> ""){?>
						<TD WIDTH="10%" ALIGN="center"><?php
echo $editor[$i]; ?></TD>
				<?php
}
					else { ?>
						<TD WIDTH="10%" ALIGN="center">&nbsp;</TD>
				<?php
} ?>
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
			<INPUT TYPE="hidden" NAME="option" VALUE="<?php
echo $option; ?>">
			<INPUT TYPE="hidden" NAME="task" VALUE="">
			<INPUT TYPE="hidden" NAME="boxchecked" VALUE="0">
			</FORM>
			</TABLE>
			<?php
}
			
		function editSurvey($pollTitle, $optionText, $optionCount, $option, $pollid, $menuid, $id, $menu){?>
			<FORM ACTION="index2.php" METHOD="GET" NAME="adminForm">
			<TABLE CELLPADDING="5" CELLSPACING="0" BORDER="0" WIDTH="100%">
			<TR>
				<TD VALIGN="top">Page:</TD>
				<!--<TD WIDTH="100%">
					<SELECT NAME="menu" SIZE="10" MULTIPLE >
				<?php
//$k = 0;
						//while ($k < count($menuid)){
							//for ($i = 0; $i < count($id); $i++){
								//if ($id[$i] == $menuid[$k]){
									//$k++;?>
									<OPTION VALUE="<?php
echo $id[$i]; ?>" SELECTED><?php
echo $menu[$i]; ?></OPTION>
						<?php
//}
								//else {?>
									<OPTION VALUE="<?php
echo $id[$i]; ?>"><?php
echo $menu[$i]; ?></OPTION>
						<?php
//}
								//}
							//}?>
					</SELECT>
				</TD>-->
				<TD WIDTH="100%">
					<SELECT NAME="menu" SIZE="10" MULTIPLE >
						<?php
$k = 0;
						while ($k < count($id)){
							$selected="";
							for ($i = 0; $i < count($menuid); $i++){
								if ($menuid[$i] == $id[$k]){
									$selected="selected";
								}
							}?>
							<OPTION VALUE="<?php
echo $id[$k]; ?>" <?php echo $selected;?>><?php
echo $menu[$k]; ?></OPTION>
							<?php
$k++;
						}?>
					</SELECT>
				</TD>
			</TR>
			<TR>
				<TD>Title:</TD>
				<TD><INPUT TYPE="text" NAME="mytitle" SIZE="75" VALUE="<?php
echo htmlspecialchars($pollTitle,ENT_QUOTES); ?>"></TD>
			</TR>
			<?php for ($i = 0; $i < count($optionText); $i++){ ?>
				<TR>
				<?php
$s = $i + 1;?>
				<TD><?php
echo "$s."; ?></TD>
				<TD><INPUT TYPE="text" NAME="polloption[]" VALUE="<?php
echo $optionText[$i]; ?>" SIZE="75"></TD>
				<INPUT TYPE="hidden" NAME="pollorder[]" VALUE="<?php
echo $s; ?>">
				<INPUT TYPE="hidden" NAME="optionCount[]" VALUE="<?php
echo $optionCount[$i]; ?>"></TD>
				</TR>
			<?php
}
			$t = 12 - $i;
			for ($j = 0; $j < $t; $j++){?>
				<TR>
					<?php
$s++;?>
					<TD><?php
echo "$s."; ?></TD>
					<TD><INPUT TYPE="text" NAME="polloption[]" VALUE="" SIZE="75"></TD>
					<INPUT TYPE="hidden" NAME="pollorder[]" VALUE="<?php
echo "$s."; ?>">
					<INPUT TYPE="hidden" NAME="optionCount[]" VALUE="0">
				</TR>
			<?php
}?>
			<INPUT TYPE="hidden" NAME="task" VALUE="">
			<INPUT TYPE="hidden" NAME="option" VALUE="<?php
echo $option; ?>">
			<INPUT TYPE="hidden" NAME="pollid" VALUE="<?php
echo $pollid; ?>">
			<INPUT TYPE="hidden" NAME="textfieldcheck" VALUE="<?php
echo $k+1; ?>">
			</TABLE>
			</FORM>
			<?php
}
			
		function addSurvey($id, $name, $option){?>
			<SCRIPT LANGUAGE="Javascript">
			<!--
				function checktextfield(textfield){
					if (textfield == ""){
						if (document.adminForm.textfieldcheck.value != 0)
							document.adminForm.textfieldcheck.value--;
						}
					else {
						document.adminForm.textfieldcheck.value++;
						}
					}
			//-->
			</SCRIPT>
			<FORM ACTION='index2.php' METHOD='GET' NAME="adminForm">
			<TABLE CELLPADDING="5" CELLSPACING="0" BORDER="0" WIDTH="100%">
			<TR BGCOLOR="#999999">
				<TD ALIGN="left" CLASS="heading" COLSPAN="2">Add Survey</TD>
			</TR>
			<TR>
				<TD ALIGN="center" COLSPAN="2">&nbsp;</TD>
			</TR>
			<TR>
				<TD VALIGN="top">Page:</TD>
				<TD WIDTH="100%">
					<SELECT NAME="menu" SIZE="10" MULTIPLE>
					<?php
for ($i = 0; $i < count($id); $i++){?>
							<OPTION VALUE="<?php
echo $id[$i]; ?>"><?php
echo $name[$i]; ?></OPTION>
					<?php
}?>
					</SELECT>
				</TD>
			</TR>
			<TR>
				<TD>Title:</TD>
				<TD><INPUT TYPE='text' NAME='mytitle' SIZE='75'></TD>
			</TR>
			<?php for ($i = 0; $i < 12; $i++){
				$s = $i + 1;?>
				<TR>
					<TD><?php
echo "$s."; ?></TD>
					<TD><INPUT TYPE='text' NAME='polloption[]' SIZE='75' onChange="checktextfield(this.value);"></TD>
					<INPUT TYPE='hidden' NAME='pollorder[]' VALUE="<?php
echo $s; ?>">
				</TR>
			<?php
}?>
			<TR>
				<TD ALIGN="center" COLSPAN="2">&nbsp;</TD>
			</TR>
			<TR BGCOLOR="#999999">
				<TD ALIGN="left" CLASS="heading" COLSPAN="2">&nbsp;</TD>
			</TR>
			<INPUT TYPE='hidden' NAME='task' VALUE="">
			<INPUT TYPE='hidden' NAME='textfieldcheck' VALUE="0">
			<INPUT TYPE='hidden' NAME='option' VALUE="<?php	echo $option; ?>">
			</FORM>
			</TABLE>
			<?php	}
		}
?>
