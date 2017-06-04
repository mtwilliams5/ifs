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
	 *	File Name: HTML_components.php
	 *	Developers: Danny Younes - danny@miro.com.au
	 *				Nicole Anderson - nicole@miro.com.au
	 *	Date: 27-11-2002
	 * 	Version #: 4.0.11
	 *	Comments:
	**/
	
	class HTML_components {
		function showComponents($id, $title, $option, $publish, $editor, $ordering, $position, $module){?>
			<FORM ACTION="index2.php" METHOD="POST" NAME="adminForm">
			<TABLE CELLPADDING="5" CELLSPACING="0" BORDER="0" WIDTH="100%">
			<TR BGCOLOR="#999999">
				<TD COLSPAN="2" CLASS="heading">Modules</TD>
				<TD CLASS="heading" ALIGN="center">Position</TD>
				<TD CLASS="heading" ALIGN="center">Ordering</TD>
				<TD ALIGN="center" CLASS="heading">Published</TD>
				<TD ALIGN="center" CLASS="heading">Checked Out</TD>
			</TR>
			<?php
$color = array("#FFFFFF", "#CCCCCC");
			$k = 0;
			for ($i = 0; $i < count($id); $i++){?>
			<TR BGCOLOR="<?php
echo $color[$k]; ?>">
				<TD WIDTH="20"><INPUT TYPE="checkbox" NAME="cid[]" VALUE="<?php
echo $id[$i]; ?>" onClick="isChecked(this.checked);"></TD>
			<?php
if ($module[$i] == ""){?>
				<TD WIDTH="60%"><?php
echo $title[$i]; ?></TD>
			<?php
}else{?>
					<TD WIDTH="60%"><?php
echo "$title[$i]- mambo component"; ?></TD>
			<?php
}?>
				<TD WIDTH="10%" ALIGN="center"><?php
echo $position[$i]; ?></TD>
				<TD WIDTH="10%" ALIGN="center"><?php
echo $ordering[$i]; ?></TD>
				<?php
if ($publish[$i] == "1"){
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
if ($editor[$i] == ""){?>
					<TD WIDTH="20%" ALIGN="center">&nbsp;</TD>
			<?php
}
				else {?>
					<TD WIDTH="20%" ALIGN="center"><?php
echo $editor[$i]; ?></TD>
				<?php
} 
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
			<INPUT TYPE="hidden" NAME="boxchecked" VALUE="0">
			</FORM>
			</TABLE>
			<?php
}
			
			
		function addComponent($leftorder, $rightorder, $option, $text_editor){?>
			<?php
$countleft = count($leftorder);
			   $countleft = $countleft+1;
			   $countright = count($rightorder);
			   $countright = $countright+1;
			?>
			<SCRIPT LANGUAGE="Javascript">
			<!--
				var leftorder = new Array;
				var rightorder = new Array;
				var leftarray = new Array;
				
				<?php
for ($i = 0; $i < $countleft; $i++){
					$k = $i + 1;
					?>
					
						leftarray[<?php
echo $i; ?>] = "<?php
echo $k; ?>";
						<?php
}?>
				
				<?php
for ($i = 0; $i < $countleft; $i++){
						$k = $i + 1;
				?>
						leftorder[<?php
echo $i; ?>] = "<?php
echo $k; ?>";
						<?php
}?>
						
				<?php
for ($i = 0; $i < $countright; $i++){
						$k = $i + 1;
				?>
						rightorder[<?php
echo $i; ?>] = "<?php
echo $k; ?>";
							<?php
}?>
			
				function changeMenu(pos)
				  {
				  if (pos == "left"){
				    	for (var x = 0; x < rightorder.length+1; x++){
				   			document.adminForm.order.options[x] = null;
				    		}
						
						for (var x = 0; x < leftorder.length; x++){
							document.adminForm.order.options[x] = new Option(leftorder[x]);
				   		 }
						document.adminForm.order.length = x;
					}
				  else
				  if (pos == "right"){
				  	for (var k = 0; k < leftorder.length+1; k++){
				   		document.adminForm.order.options[k] = null;
				    	}

				   	for (var k = 0; k < rightorder.length; k++){
						document.adminForm.order.options[k] = new Option(rightorder[k]);
				   		 }
					document.adminForm.order.length = k;
					}
				  document.adminForm.order.options[0].selected = true;  //selects the first option in the second dropdown
				}
			//-->
			</SCRIPT>
			<FORM ACTION="index2.php" METHOD="POST" NAME="adminForm">
			<TABLE CELLPADDING="5" CELLSPACING="0" BORDER="0" WIDTH="100%">
			<TR>
				<TD COLSPAN='2' CLASS='heading' BGCOLOR="#999999">Add New Module</TD>
			</TR>
			<TR>
				<TD COLSPAN='2' HEIGHT="40">&nbsp;</TD>
			</TR>
			<TR>
				<TD VALIGN="top">Title:</TD>
				<TD WIDTH="85%"><INPUT TYPE='text' NAME='mytitle' SIZE='50'></TD>
			</TR>
			<TR>
				<TD VALIGN="top">Content:</TD>
				<TD><TEXTAREA COLS='70' ROWS='16' NAME='content' STYLE="WIDTH:500px" WIDTH=500><?php
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
				<TD VALIGN="top">Position:</TD>
				<TD><INPUT TYPE='radio' NAME='position' VALUE='left' CHECKED onClick="changeMenu('left');">Left&nbsp;&nbsp;&nbsp;&nbsp;<INPUT TYPE='radio' NAME='position' VALUE='right' onClick="changeMenu('right');">Right</TD>
			</TR>
			<TR>
				<TD VALIGN="top">Component Order:</TD>
				<TD>
					<SCRIPT>
						<!--
							document.write("<SELECT NAME='order' SIZE='1'>");
							for (i = 0; i < leftarray.length; i++){
								document.write("<OPTION VALUE='" + leftarray[i] + "'>" + leftarray[i] + "</OPTION>");
								}
							document.write("</SELECT>");
						//-->
					</SCRIPT>
				</TD>
			</TR>
			<TR>
				<TD COLSPAN='2' HEIGHT="40">&nbsp;</TD>
			</TR>
			<TR>
				<TD COLSPAN='2' CLASS='heading' BGCOLOR="#999999">&nbsp;
					<INPUT TYPE='hidden' NAME='option' VALUE='<?php
echo $option; ?>'>
					<INPUT TYPE="hidden" NAME="task" VALUE="">
					
				</TD>
			</TR>
			</FORM>
			</TABLE>
			<?php
}
			
		function editComponent($componentid, $title, $content, $position, $option, $leftorder, $rightorder, $ordering, $module, $text_editor){?>
			<?php
$countleft = count($leftorder);
			   $countleft = $countleft;
			   $countright = count($rightorder);
			   $countright = $countright;
			?>
			<SCRIPT LANGUAGE="Javascript">
			<!--
				var leftarray = new Array;
				var rightarray = new Array;
				
				<?php
for ($i = 0; $i < $countleft; $i++){
					$k = $i + 1;
					?>
					
					leftarray[<?php
echo $i; ?>] = "<?php
echo $k; ?>";
					<?php
}
						if ($position == "right"){
							$k = $i + 1;?>
							leftarray[<?php
echo $i; ?>] = "<?php
echo $k; ?>";
						<?php
}?>
						
				<?php
for ($i = 0; $i < $countright; $i++){
					$k = $i + 1;
					?>
					
						rightarray[<?php
echo $i; ?>] = "<?php
echo $k; ?>";
						<?php
}
							if ($position == "left"){
							$k = $i + 1;?>
							rightarray[<?php
echo $i; ?>] = "<?php
echo $k; ?>";
						<?php
}?>
						
				function whatisselected(whatisselected){
					this.whatisselected = whatisselected-1;
					}
					
				whatisselected(<?php
echo $ordering; ?>);
						
				function changeMenu(pos, originalpos){
				  if (pos == "left"){
				    	for (var x = 0; x < rightarray.length+1; x++){
				   			document.adminForm.order.options[x] = null;
				    		}
						
						for (var x = 0; x < leftarray.length; x++){
							document.adminForm.order.options[x] = new Option(leftarray[x]);
				   		 }
						document.adminForm.order.length = x;
						if (originalpos == "left")
							document.adminForm.order.options[this.whatisselected].selected = true;
						else
							document.adminForm.order.options[0].selected = true;
					}
				  else
				  if (pos == "right"){
				  	for (var k = 0; k < leftarray.length+1; k++){
				   		document.adminForm.order.options[k] = null;
				    	}

				   	for (var k = 0; k < rightarray.length; k++){
						document.adminForm.order.options[k] = new Option(rightarray[k]);
				   		 }
					document.adminForm.order.length = k;
					if (originalpos == "right")
						document.adminForm.order.options[this.whatisselected].selected = true;
					else
						document.adminForm.order.options[0].selected = true;
					}
				  
				}
				
				
			//-->
			</SCRIPT>
			<FORM ACTON='index2.php' METHOD='POST' NAME="adminForm">
			<TABLE CELLPADDING="5" CELLSPACING="0" BORDER="0" WIDTH="100%">
			<TR>
				<TD COLSPAN='2' CLASS='heading' BGCOLOR="#999999">Edit Module</TD>
			</TR>
			<TR>
				<TD COLSPAN='2' HEIGHT="40">&nbsp;</TD>
			</TR>
			<TR>
				<TD VALIGN="top">Title:</TD>
				<TD WIDTH="85%"><INPUT TYPE='text' NAME='mytitle' SIZE='25' VALUE="<?php
echo $title; ?>"></TD>
			</TR>
		<?php
if ($module == ""){ ?>
				<TR>
					<TD VALIGN="top">Content:</TD>
					<TD><TEXTAREA COLS='70' ROWS='16' NAME='content' STYLE="WIDTH=500px" WIDTH=500><?php
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
		<?php
}?>
			<TR>
				<TD VALIGN="top">Position:</TD>
				<TD>
				<?php
if ($position == "left"){?>
					  <INPUT TYPE='radio' NAME='position' VALUE='left' CHECKED onClick="changeMenu('left', '<?php
echo $position; ?>');">Left&nbsp;&nbsp;&nbsp;&nbsp;
				<?php
}
					else {?>
					  <INPUT TYPE='radio' NAME='position' VALUE='left' onClick="changeMenu('left','<?php
echo $position; ?>');">Left&nbsp;&nbsp;&nbsp;&nbsp;
					 <?php
} ?>
				
				<?php
if ($position == "right"){?>
	    			  <INPUT TYPE='radio' NAME='position' VALUE='right' CHECKED onClick="changeMenu('right','<?php
echo $position; ?>');">Right
				<?php
}
				else {?>
					  <INPUT TYPE='radio' NAME='position' VALUE='right' onClick="changeMenu('right','<?php
echo $position; ?>');">Right
					<?php
} ?>
				</TD>
			</TR>
			<TR>
				<TD VALIGN="top">Component Order:</TD>
				<TD>
					<SCRIPT>
						<!--
							var positioning =  "<?php
echo $position; ?>";
							document.write("<SELECT NAME='order' SIZE='1'>");
							if (positioning == "left"){
								for (i = 0; i < leftarray.length; i++){
									if (leftarray[i] == <?php
echo $ordering; ?>){
										document.write("<OPTION VALUE='" + leftarray[i] + "' SELECTED>" + leftarray[i] + "</OPTION>");
										}
									else {
										document.write("<OPTION VALUE='" + leftarray[i] + "'>" + leftarray[i] + "</OPTION>");
										}
									}
								}
							else {
								for (i = 0; i < rightarray.length; i++){
									if (rightarray[i] == <?php
echo $ordering; ?>){
										document.write("<OPTION VALUE='" + rightarray[i] + "' SELECTED>" + rightarray[i] + "</OPTION>");
										}
									else {
										document.write("<OPTION VALUE='" + rightarray[i] + "'>" + rightarray[i] + "</OPTION>");
										}
									}
								}
							document.write("</SELECT>");
						//-->
					</SCRIPT>
				</TD>
			</TR>
			<TR>
				<TD COLSPAN='2' HEIGHT="40">&nbsp;</TD>
			</TR>
			<TR>
				<TD COLSPAN='2' CLASS='heading' BGCOLOR="#999999">&nbsp;</TD>
			</TR>
			<INPUT TYPE='hidden' NAME='option' VALUE='<?php
echo $option; ?>'>
			<INPUT TYPE='hidden' NAME='componentid' VALUE='<?php
echo $componentid; ?>'>
			<INPUT TYPE='hidden' NAME='original' VALUE='<?php
echo $ordering; ?>'>
			<INPUT TYPE='hidden' NAME='module' VALUE='<?php
echo $module; ?>'>
			<INPUT TYPE="hidden" NAME="task" VALUE="">
			</FORM>
			</TABLE>
			<?php
}
		}
?>
