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
	 *	File Name: HTML_menusections.php
	 *	Developers: Danny Younes - danny@miro.com.au
	 *				Nicole Anderson - nicole@miro.com.au
	 *	Date: 27-11-2002
	 * 	Version #: 4.0.11
	 *	Comments:
	**/
	
	class HTML_menusections {
		function showMenusections($itemid, $itemName, $type, $status, $option, $editor){?>
			<FORM ACTION="index2.php" METHOD="POST" NAME="adminForm">
			<TABLE CELLPADDING="5" CELLSPACING="0" BORDER="0" WIDTH="100%">
			<TR BGCOLOR="#999999">
				<TD COLSPAN="2" CLASS="heading">Top Level Menu Items</TD>
				<TD ALIGN=CENTER CLASS="heading">Content Type</TD>
				<TD ALIGN=CENTER CLASS="heading">Published</TD>
				<TD ALIGN=CENTER CLASS="heading">Checked Out</TD>
			</TR>
			<?php
$color = array("#FFFFFF", "#CCCCCC");
			$k = 0;
			for ($i = 0; $i < count($itemid); $i++){?>
			<TR BGCOLOR="<?php
echo $color[$k]; ?>">
				<TD WIDTH="20"><INPUT TYPE="checkbox" NAME="cid[]" VALUE="<?php
echo $itemid[$i]; ?>" onClick="isChecked(this.checked);"></TD>
				<TD WIDTH="63%"><?php
echo $itemName[$i]; ?></TD>
				<TD WIDTH="15%" ALIGN=CENTER><?php
echo $type[$i];?></TD>
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
			
			
		function addMenusection($option){?>
			<SCRIPT LANGUAGE="javascript">
			<!--
				function checkstep1(form){
					if (document.adminForm.ItemName.value == ""){
						alert('must have title');
						}
					else {
						document.adminForm.action = 'index2.php';
						document.adminForm.submit(form);
						}
					}
			//-->
			</SCRIPT>
			<FORM action="index2.php" METHOD="POST" NAME="adminForm">
			<TABLE CELLPADDING="5" CELLSPACING="0" BORDER="0" WIDTH="100%">
			<TR BGCOLOR=#999999>
				<TD COLSPAN="2" CLASS="heading">Add Menu Section</TD>
			</TR>
			<tr>
				<td colspan=2>&nbsp;</td>
			</tr>
			<tr>
				<td>Item Name:</td>
				<td width=85%><input type=text name="ItemName"></td>
			</tr>
			<tr>
				<td>Item Type:</td>
				<td><select name=ItemType>
						<option value="Own">Own Content</option>
						<option value="Mambo">Mambo Component</option>
						<option value="Web">Web Link</option>
					</select>
				</td>
			</tr>
			<!--<tr><td>Place Order:</td>
				<td><SELECT NAME="order">
						<OPTION>Please Select</OPTION>
					<?php
// for ($i = 1; $i < $numItems+2; $i++){
						//print "<OPTION VALUE='$i'>$i</OPTION>\n";
					//}?>
					</SELECT>
				</td>
			</tr>-->
			<tr>
				<td>&nbsp;</td>
				<td><INPUT TYPE="hidden" NAME="option" VALUE="<?php
echo $option; ?>">
					<INPUT TYPE="hidden" NAME="task" VALUE="AddStep2">
					<INPUT TYPE="button" value="Next" onClick="checkstep1(this.form);">
				</td>
			</tr>
			<tr>
				<td colspan=2>&nbsp;</td>
			</tr>
			<tr BGCOLOR=#999999>
				<td colspan=2>&nbsp;</td>
			</tr>
			</table>
			</FORM>
		<?php
}
			
		function addMamboStep2($option, $ItemName, $moduleid, $modulename){?>
			<FORM ACTION="index2.php" METHOD="POST" NAME="adminForm">
				<TABLE CELLPADDING="5" CELLSPACING="0" BORDER="0" WIDTH="100%">
				<TR BGCOLOR=#999999>
					<TD COLSPAN="2" CLASS="heading">Page Content Entry</TD>
				</TR>
				<tr>
					<td colspan=2>&nbsp;</td>
				</tr>
				<tr>
					<td>Page Name:</td>
					<td width=85%><?php echo $ItemName;?></td>
				</tr>
				<tr>
					<td>Remaining Mambo Modules:</td>
					<td><select name="moduleID">
							<?php for ($i = 0; $i < count($moduleid); $i++){
								if ($moduleid[$i]!=""){?>
									<OPTION VALUE='<?php
echo $moduleid[$i]; ?>'><?php
echo $modulename[$i]; ?></OPTION>
								<?php
}
							  }?>
						</select>
					</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td><INPUT TYPE="hidden" NAME="ItemName" VALUE="<?php
echo $ItemName;?>">
						<INPUT TYPE="hidden" NAME="option" VALUE="<?php
echo $option; ?>">
						<INPUT TYPE="hidden" NAME="task" VALUE="">
						<INPUT TYPE="hidden" NAME="ItemType" VALUE="<?php echo $ItemType;?>">
					</td>
				</tr>
				<tr>
					<td colspan=2>&nbsp;</td>
				</tr>
				<tr BGCOLOR=#999999>
					<td colspan=2>&nbsp;</td>
				</tr>
			</table>
			</FORM>	
		<?php
}
		
		function addOwnStep2($option, $ItemName){?>
			<FORM ACTION="index2.php" METHOD="POST" NAME="adminForm">
				<TABLE CELLPADDING="5" CELLSPACING="0" BORDER="0" WIDTH="100%">
				<TR BGCOLOR=#999999>
					<TD COLSPAN="2" CLASS="heading">Page Content Entry</TD>
				</TR>
				<tr>
					<td colspan=2>&nbsp;</td>
				</tr>
				<tr>
					<td>Page Name:</td>
					<td width=85%><?php echo $ItemName;?></td>
				</tr>
				<tr>
					<td>Page Content Source:</td>
					<td><select name="PageSource">
							<option value="Type">Type In</option>
							<option value="Link">Upload Page</option>
						</select>
					</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td><INPUT TYPE="hidden" NAME="ItemName" VALUE="<?php
echo $ItemName;?>">
						<INPUT TYPE="hidden" NAME="option" VALUE="<?php
echo $option; ?>">
						<INPUT TYPE="hidden" NAME="task" VALUE="AddStep3">
						<INPUT TYPE="submit" NAME="submit" value="Next">
					</td>
				</tr>
				<tr>
					<td colspan=2>&nbsp;</td>
				</tr>
				<tr BGCOLOR=#999999>
					<td colspan=2>&nbsp;</td>
				</tr>
			</table>
			</FORM>	
		<?php
}
		
		function  addWebStep2($option, $ItemName){?>
			<FORM ACTION="index2.php" METHOD="POST" NAME="adminForm">
				<TABLE CELLPADDING="5" CELLSPACING="0" BORDER="0" WIDTH="100%">
				<TR BGCOLOR=#999999>
					<TD COLSPAN="2" CLASS="heading">Page Content Entry</TD>
				</TR>
				<tr>
					<td colspan=2>&nbsp;</td>
				</tr>
				<tr>
					<td>Page Name:</td>
					<td width=85%><?php echo $ItemName;?></td>
				</tr>
				<tr>
					<td>Web Link:</td>
					<td><input type=text name="Weblink" size=50></td>
				</tr>
				<tr>
					<td colspan=2>&nbsp;</td>
				</tr>
				<tr>
					<td colspan=2><input type="radio" NAME="browserNav" VALUE=1 checked>Open with Browser Navigation&nbsp;&nbsp;&nbsp;
								<input type="radio" NAME="browserNav" VALUE=0>Open Without Browser Navigation</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td><INPUT TYPE="hidden" NAME="ItemName" VALUE="<?php
echo $ItemName;?>">
						<INPUT TYPE="hidden" NAME="option" VALUE="<?php
echo $option; ?>">
						<INPUT TYPE="hidden" NAME="ItemType" VALUE="<?php echo $ItemType;?>">
						<INPUT TYPE="hidden" NAME="task" VALUE="">
					</td>
				</tr>
				<tr>
					<td colspan=2>&nbsp;</td>
				</tr>
				<tr BGCOLOR=#999999>
					<td colspan=2>&nbsp;</td>
				</tr>
			</table>
			</FORM>	
		<?php
}
		
		function  addTypeStep3($option, $ItemName, $text_editor){?>
			<FORM ACTION="index2.php" METHOD="POST" NAME="adminForm">
				<TABLE CELLPADDING="5" CELLSPACING="0" BORDER="0" WIDTH="100%">
				<TR BGCOLOR=#999999>
					<TD COLSPAN="2" CLASS="heading">Page Content Entry</TD>
				</TR>
				<tr>
					<td colspan=2>&nbsp;</td>
				</tr>
				<tr>
					<td>Page Name:</td>
					<td width=85%><?php echo $ItemName;?> </td>
				</tr>
				<tr>
					<td>Page Heading:</td>
					<td><Input type="text" name="heading"></td>
				</tr>
			
				<tr>
					<td valign=top>Page Content:</td>
					<td><textarea name="pagecontent" cols=60 rows=5><?php
echo htmlentities($pagecontent); ?></textarea>
						<BR>
						<?php
if ($text_editor == "true"){?>
						<A HREF="#" onClick="window.open('inline_editor/editor.htm?pagecontent', 'win1', 'width=650, height=450, resizable=yes');">Edit Text In Editor</A>
						<?php
} ?>
					</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td><INPUT TYPE="hidden" NAME="ItemName" VALUE="<?php
echo $ItemName;?>">
						<INPUT TYPE="hidden" NAME="option" VALUE="<?php
echo $option; ?>">
						<INPUT TYPE="hidden" NAME="task" VALUE="">
					</td>
				</tr>
				<tr>
					<td colspan=2>&nbsp;</td>
				</tr>
				<tr BGCOLOR=#999999>
					<td colspan=2>&nbsp;</td>
				</tr>	
  		  </table>
		  </FORM>	
		<?php
}
		
		function  addLinkStep3($option, $ItemName, $Itemid){?>
		<SCRIPT LANGUAGE="javascript">
			<!--
				function checkstep1(form){
					var chosenfile = document.filename.userfile.value;
					
					if (chosenfile == ""){
						alert('Please select a file to upload');
						}
					else {
						var searchresult= chosenfile.search("htm");
						if (searchresult == -1){
							alert('Upload file must be html');
						}else{
							//document.filename.action = 'index2.php';
							document.filename.submit(form);
						}
					}
				}
			//-->
			</SCRIPT>
		
		<FORM ENCTYPE="multipart/form-data" METHOD="POST" NAME="filename" ACTION="index2.php">
			<TABLE CELLPADDING="5" CELLSPACING="0" BORDER="0" WIDTH="100%">
				<TR BGCOLOR=#999999>
					<TD COLSPAN="2" CLASS="heading">Page Content Entry</TD>
				</TR>
				<tr>
					<td colspan=2>&nbsp;</td>
				</tr>
				<tr><td>Page Name:</td>
					<td width=85%><?php echo $ItemName;?></td>
				</tr>
				<TR> 
					<TD>Select file:</TD>
					<TD WIDTH='85%'><INPUT NAME="userfile" TYPE="file"></TD>
				</TR>
				<TR>
					<TD COLSPAN='2'><input type=hidden name="Itemid" value="<?php echo $Itemid;?>">
									<INPUT TYPE="hidden" NAME="option" VALUE="<?php
echo $option; ?>">
									<INPUT TYPE="hidden" NAME="task" VALUE="Upload">
									<input type="button" value="Upload File" onClick="checkstep1(this.form);"></TD>
				</TR>
				<tr>
					<td colspan=2>&nbsp;</td>
				</tr>
				<tr BGCOLOR=#999999>
					<td colspan=2>&nbsp;</td>
				</tr>
			</TABLE>
		</FORM>
		<?php
}
			
		function editMenusection($Itemid, $ItemName, $pagecontent, $link, $fileEdit, $filecontent, $mamboEdit, $moduleid, $modulename, $moduleidlist, $modulenamelist, $option, $order, $maxOrder, $myname, $heading, $browserNav, $text_editor){?>
			<FORM ACTION="index2.php" METHOD="POST" NAME="adminForm">
			<TABLE CELLPADDING="5" CELLSPACING="0" BORDER="0" WIDTH="100%">
			<TR BGCOLOR=#999999>
				<TD COLSPAN="2" CLASS="heading">Edit Menu Item</TD>
			</TR>
			<TR>
				<TD colspan=2>&nbsp;</td>
			</TR>
			<TR>
				<TD>Item Name:</TD>
				<TD WIDTH="85%"><INPUT TYPE="text" NAME="ItemName" SIZE="25" VALUE="<?php
echo $ItemName; ?>"></TD>
			</TR>
			<?php if (trim($link)!=""){
				if ($fileEdit==1){?>
				<tr>
					<td VALIGN=top>File content</td>
					<td><TEXTAREA COLS="70" ROWS="10" NAME="filecontent" STYLE="WIDTH=500px" WIDTH=500><?php
echo $filecontent; ?></TEXTAREA>
						<INPUT TYPE="hidden" NAME="link2" VALUE="<?php
echo $link; ?>">
						<BR>
						<A HREF="#" onClick="window.open('inline_editor/editor.htm?filecontent', 'win1', 'width=650, height=450, resizable=yes');">Edit Text In Editor</A>
					</td>
				</tr>
				<?php
}else if ($mamboEdit==1){?>
					<tr>
						<td>Mambo Module:</td>
						<td><select name="moduleID">
									<option value="<?php echo $moduleid;?>" selected><?php echo $modulename;?></option>
								<?php for ($i = 1; $i < count($moduleidlist); $i++){?>
									<OPTION VALUE='<?php
echo $moduleidlist[$i]; ?>'><?php
echo $modulenamelist[$i]; ?></OPTION>
								<?php
}?>
							</select>
						</td>
					</tr>
				<?php
}else{?>
					<tr>
						<TD>Link:</TD>
						<td><INPUT TYPE="text" NAME="Weblink" SIZE="25" VALUE="<?php
echo $link; ?>"></td>
					</tr>
					<?php if ($browserNav==1){?>
						<tr>
							<td colspan=2><input type="radio" NAME="browserNav" VALUE=1 checked>Open with Browser Navigation&nbsp;&nbsp;&nbsp;
											<input type="radio" NAME="browserNav" VALUE=0>Open Without Browser Navigation</td>
						</tr>
					<?php
}else{?>
						<tr>
							<td colspan=2><input type="radio" NAME="browserNav" VALUE=1>Open with Browser Navigation&nbsp;&nbsp;&nbsp;
											<input type="radio" NAME="browserNav" VALUE=0 checked>Open Without Browser Navigation</td>
						</tr>
					<?php
}
				}
			}else{?>
				<TR>
					<td>Heading:</td>
					<td><Input type="text" name="heading" value="<?php echo $heading;?>"></td>
				</TR>
				<TR>
					<TD VALIGN="top">Content:</TD>
					<TD><TEXTAREA COLS="70" ROWS="10" NAME="pagecontent" STYLE="WIDTH=500px" WIDTH=500><?php
echo htmlentities($pagecontent); ?></TEXTAREA>
					<BR>
						<A HREF="#" onClick="window.open('inline_editor/editor.htm?pagecontent', 'win1', 'width=650, height=450, resizable=yes');">Edit Text In Editor</A></TD>
				</TR>
			<?php
}?>
				<tr>
					<TD>Display Order:</TD>
					<TD><SELECT NAME="order">
						<?php for ($i = 1; $i < $maxOrder + 1; $i++){
							if ($i == $order){?>
								<OPTION VALUE="<?php
echo $order; ?>" SELECTED><?php
echo $order; ?></OPTION>
							<?php
}else {?>
								<OPTION VALUE="<?php
echo $i; ?>"><?php
echo $i; ?></OPTION>
							<?php
}
						}?>
						</SELECT>
					</TD>
				</tr>
				<INPUT TYPE="hidden" NAME="option" VALUE="<?php
echo $option; ?>">
				<INPUT TYPE="hidden" NAME="Itemid" VALUE="<?php
echo $Itemid; ?>">
				<INPUT TYPE="hidden" NAME="task" VALUE="">
				<INPUT TYPE="hidden" NAME="origOrder" VALUE="<?php echo $order;?>">
				<INPUT TYPE="hidden" NAME="myname" VALUE="<?php echo $myname;?>">
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
		}
?>
