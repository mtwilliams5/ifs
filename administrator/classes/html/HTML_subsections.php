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
	 *	File Name: HTML_subsections.php
	 *	Developers: Danny Younes - danny@miro.com.au
	 *				Nicole Anderson - nicole@miro.com.au
	 *	Date: 27-11-2002
	 * 	Version #: 4.0.11
	 *	Comments:
	**/
	
	class HTML_subsections {
		function showSubsections($itemid, $itemName, $type, $published, $path, $option, $editor, $sections, $ItemIdList, $ItemNameList, $subs){?>
			<FORM ACTION="index2.php" METHOD="POST" NAME="adminForm">
			<TABLE CELLPADDING="5" CELLSPACING="0" BORDER="0" WIDTH="100%">
				<TR>
					<TD COLSPAN="6" align=right>Select A Section:&nbsp;&nbsp;
						<SELECT NAME="sections" onChange="document.location.href='index2.php?option=SubSections&sections=' + document.adminForm.sections.options[selectedIndex].value">
							<OPTION VALUE="all">Select All</OPTION>
						<?php
for ($i = 0; $i < count($ItemIdList); $i++){
								if ($sections == $ItemIdList[$i]){?>
									<OPTION VALUE="<?php
echo $ItemIdList[$i]; ?>" SELECTED><?php
echo $ItemNameList[$i]; ?></OPTION>
						<?php
} else {?>
									<OPTION VALUE="<?php
echo $ItemIdList[$i]; ?>"><?php
echo $ItemNameList[$i]; ?></OPTION>
						<?php
}
								}?>
						</SELECT>
					</TD>
				</TR>
			
				<TR BGCOLOR="#999999">
					<TD COLSPAN="2" CLASS="heading">Sub Level Menu Items</TD>
					<TD ALIGN="CENTER" CLASS="heading">Published</TD>
					<TD ALIGN="CENTER" CLASS="heading">Sub-Sections</TD>
					<TD ALIGN="CENTER" CLASS="heading">Content Type</TD>
					<TD ALIGN="CENTER" CLASS="heading">Checked Out</TD>
				</TR>
				<?php
$color = array("#FFFFFF", "#CCCCCC");
				$k = 0;
				for ($i = 0; $i < count($itemid); $i++){?>
				<TR BGCOLOR="<?php
echo $color[$k]; ?>">
					<TD WIDTH="20"><INPUT TYPE="checkbox" NAME="cid[]" VALUE="<?php echo "$itemid[$i]";?>" onClick="isChecked(this.checked);"></TD>
					<?php if ($path[$i]!=""){?>
						<TD WIDTH="53%"><?php
echo "$path[$i]/ <a href=index2.php?option=SubSections&task=edit&checkedID=$itemid[$i]&categories=$sections>$itemName[$i]</a>" ?></TD>
					<?php
}else{?>
						<TD WIDTH="53%"><?php
echo "<a href=index2.php?option=SubSections&task=edit&checkedID=$itemid[$i]&categories=$sections>$itemName[$i]</a>" ?></TD>
					<?php
}?>
					
					<?php if ($published[$i] == "yes"){
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
										
					<?php if ($subs[$i] == "1"){
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
					
					<TD WIDTH="15%" ALIGN="CENTER"><?php
echo $type[$i];?></TD>
					
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
		
		function addSubsection($option, $ItemIdList, $ItemNameList, $sections){?>
			<SCRIPT LANGUAGE="javascript">
			<!--
				function checkstep1(form){
					if ((document.adminForm.ItemName.value == "") || (document.adminForm.SectionID.options.value == "")){
						alert('Page must have a section and name');
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
					<TD COLSPAN="2" CLASS="heading">Add Menu Sub-section</TD>
				</TR>
				<TR>
					<TD COLSPAN=2>&nbsp;</TD>
				</TR>
				<TR>
					<TD>Section Name</TD>
					<TD><select name="SectionID">
							<?php for ($i = 0; $i < count($ItemIdList); $i++){?>
								<OPTION VALUE='<?php
echo $ItemIdList[$i]; ?>'><?php
echo $ItemNameList[$i]; ?></OPTION>
							<?php
}?>
						</select>
					</TD>
				</TR>
				<TR>
					<TD>Item Name:</TD>
					<TD WIDTH=85%><input type=text name="ItemName"></TD>
				</TR>
				<TR>
					<TD>Item Type:</TD>
					<TD><select name=ItemType>
							<option value="Own">Own Content</option>
							<option value="Mambo">Mambo Component</option>
							<option value="Web">Web Link</option>
						</select>
					</TD>
				</TR>
				<TR>
					<TD>&nbsp;</TD>
					<TD><INPUT TYPE="hidden" NAME="option" VALUE="<?php
echo $option; ?>">
						<INPUT TYPE="hidden" NAME="task" VALUE="AddStep2">
						<INPUT TYPE="hidden" NAME="sections" VALUE="<?php echo $sections;?>">
						<INPUT TYPE="button" value="Next" onClick="checkstep1(this.form);">
					</TD>
				</TR>
				<TR>
					<TD COLSPAN=2>&nbsp;</TD>
				</TR>
				<TR BGCOLOR=#999999>
					<TD COLSPAN=2>&nbsp;</TD>
				</TR>
			</TABLE>
			</FORM>
		<?php
}
			
		function addMamboStep2($option, $ItemName, $moduleid, $modulename, $SectionID, $sections){?>
			<FORM ACTION="index2.php" METHOD="POST" NAME="adminForm">
			<TABLE CELLPADDING="5" CELLSPACING="0" BORDER="0" WIDTH="100%">
				<TR BGCOLOR=#999999>
					<TD COLSPAN="2" CLASS="heading">Page Content Entry</TD>
				</TR>
				<TR>
					<TD COLSPAN=2>&nbsp;</TD>
				</TR>
				<TR>
					<TD>Page Name:</TD>
					<TD WIDTH=85%><?php echo $ItemName;?></TD>
				</TR>
				<TR>
					<TD>Remaining Mambo Modules:</TD>
					<TD><select name="moduleID">
							<?php for ($i = 0; $i < count($moduleid); $i++){
								if ($moduleid[$i]!=""){?>
									<OPTION VALUE='<?php
echo $moduleid[$i]; ?>'><?php
echo $modulename[$i]; ?></OPTION>
								<?php
}
							  }?>
						</select>
					</TD>
				</TR>
				<TR>
					<TD>&nbsp;</TD>
					<TD><INPUT TYPE="hidden" NAME="ItemName" VALUE="<?php
echo $ItemName;?>">
						<INPUT TYPE="hidden" NAME="option" VALUE="<?php
echo $option; ?>">
						<INPUT TYPE="hidden" NAME="task" VALUE="">
						<INPUT TYPE="hidden" NAME="SectionID" VALUE="<?php
echo $SectionID;?>">
						<INPUT TYPE="hidden" NAME="sections" VALUE="<?php echo $sections;?>">
					</TD>
				</TR>
				<TR>
					<TD COLSPAN=2>&nbsp;</TD>
				</TR>
				<TR BGCOLOR=#999999>
					<TD COLSPAN=2>&nbsp;</TD>
				</TR>
			</TABLE>
			</FORM>	
		<?php
}
		
		function addOwnStep2($option, $ItemName, $SectionID, $sections){?>
			<FORM ACTION="index2.php" METHOD="POST" NAME="adminForm">
			<TABLE CELLPADDING="5" CELLSPACING="0" BORDER="0" WIDTH="100%">
				<TR BGCOLOR=#999999>
					<TD COLSPAN="2" CLASS="heading">Page Content Entry</TD>
				</TR>
				<TR>
					<TD COLSPAN=2>&nbsp;</TD>
				</TR>
				<TR>
					<TD>Page Name:</TD>
					<TD WIDTH=85%><?php echo $ItemName;?></TD>
				</TR>
				<TR>
					<TD>Page Content Source:</TD>
					<TD><select name="PageSource">
							<option value="Type">Type In</option>
							<option value="Link">Upload Page</option>
						</select>
					</TD>
				</TR>
				<TR>
					<TD>&nbsp;</TD>
					<TD><INPUT TYPE="hidden" NAME="ItemName" VALUE="<?php
echo $ItemName;?>">
						<INPUT TYPE="hidden" NAME="option" VALUE="<?php
echo $option; ?>">
						<INPUT TYPE="hidden" NAME="task" VALUE="AddStep3">
						<INPUT TYPE="hidden" NAME="SectionID" VALUE="<?php
echo $SectionID;?>">
						<INPUT TYPE="hidden" NAME="sections" VALUE="<?php echo $sections;?>">
						<INPUT TYPE="submit" NAME="submit" value="Next">
					</TD>
				</TR>
				<TR>
					<TD COLSPAN=2>&nbsp;</TD>
				</TR>
				<TR BGCOLOR=#999999>
					<TD COLSPAN=2>&nbsp;</TD>
				</TR>
			</TABLE>
			</FORM>	
		<?php
}
		
		function  addWebStep2($option, $ItemName, $SectionID, $sections){?>
			<FORM ACTION="index2.php" METHOD="POST" NAME="adminForm">
			<TABLE CELLPADDING="5" CELLSPACING="0" BORDER="0" WIDTH="100%">
				<TR BGCOLOR=#999999>
					<TD COLSPAN="2" CLASS="heading">Page Content Entry</TD>
				</TR>
				<TR>
					<TD COLSPAN=2>&nbsp;</TD>
				</TR>
				<TR>
					<TD>Page Name:</TD>
					<TD WIDTH=85%><?php echo $ItemName;?></TD>
				</TR>
				<TR>
					<TD>Web Link:</TD>
					<TD><input type=text name="Weblink" size=50></TD>
				</TR>
				<TR>
					<TD colspan=2>&nbsp;</TD>
				</TR>
				<TR>
					<TD colspan=2><input type="radio" NAME="browserNav" VALUE=1 checked>Open with Browser Navigation&nbsp;&nbsp;&nbsp;
								<input type="radio" NAME="browserNav" VALUE=0>Open Without Browser Navigation</TD>
				</TR>
				<TR>
					<TD>&nbsp;</TD>
					<TD><INPUT TYPE="hidden" NAME="ItemName" VALUE="<?php
echo $ItemName; ?>">
						<INPUT TYPE="hidden" NAME="option" VALUE="<?php
echo $option; ?>">
						<INPUT TYPE="hidden" NAME="task" VALUE="">
						<INPUT TYPE="hidden" NAME="SectionID" VALUE="<?php
echo $SectionID; ?>">
						<INPUT TYPE="hidden" NAME="sections" VALUE="<?php echo $sections;?>">
					</TD>
				</TR>
				<TR>
					<TD COLSPAN=2>&nbsp;</TD>
				</TR>
				<TR BGCOLOR=#999999>
					<TD COLSPAN=2>&nbsp;</TD>
				</TR>
			</TABLE>
			</FORM>	
		<?php
}
		
		function  addTypeStep3($option, $ItemName, $SectionID, $sections){?>
			<FORM ACTION="index2.php" METHOD="POST" NAME="adminForm">
			<TABLE CELLPADDING="5" CELLSPACING="0" BORDER="0" WIDTH="100%">
				<TR BGCOLOR=#999999>
					<TD COLSPAN="2" CLASS="heading">Page Content Entry</TD>
				</TR>
				<TR>
					<TD COLSPAN=2>&nbsp;</TD>
				</TR>
				<TR>
					<TD>Page Name:</TD>
					<TD WIDTH=85%><?php echo $ItemName;?> </TD>
				</TR>
				<TR>
					<TD>Page Heading</TD>
					<TD><INPUT TYPE="text" NAME="heading"></TD>
				</TR>
				<TR>
					<TD VALIGN=top>Page Content:</TD>
					<TD><textarea name="pagecontent" cols=60 rows=5><?php
echo htmlentities($pagecontent); ?></textarea>
						<BR>
						<A HREF="#" onClick="window.open('inline_editor/editor.htm?pagecontent', 'win1', 'width=650, height=450, resizable=yes');">Edit Text In Editor</A>
					</TD>
				</TR>
				<TR>
					<TD>&nbsp;</TD>
					<TD><INPUT TYPE="hidden" NAME="ItemName" VALUE="<?php
echo $ItemName;?>">
						<INPUT TYPE="hidden" NAME="option" VALUE="<?php
echo $option; ?>">
						<INPUT TYPE="hidden" NAME="task" VALUE="">
						<INPUT TYPE="hidden" NAME="SectionID" VALUE="<?php
echo $SectionID; ?>">
						<INPUT TYPE="hidden" NAME="sections" VALUE="<?php echo $sections;?>">
					</TD>
				</TR>	
				<TR>
					<TD COLSPAN=2>&nbsp;</TD>
				</TR>
				<TR BGCOLOR=#999999>
					<TD COLSPAN=2>&nbsp;</TD>
				</TR>
  		  </TABLE>
		  </FORM>	
		<?php
}
		
		function  addLinkStep3($option, $ItemName, $Itemid, $sections){?>
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
				<TR>
					<TD COLSPAN=2>&nbsp;</TD>
				</TR>
				<TR>
					<TD>Page Name:</TD>
					<TD WIDTH=85%><?php echo $ItemName;?></TD>
				</TR>
				<TR> 
					<TD>Select file:</TD>
					<TD WIDTH='85%'><INPUT NAME="userfile" TYPE="file"></TD>
				</TR>
				<TR>
					<TD COLSPAN='2'><input type=hidden name="Itemid" value="<?php echo $Itemid;?>">
									<INPUT TYPE="hidden" NAME="option" VALUE="<?php
echo $option; ?>">
									<INPUT TYPE="hidden" NAME="task" VALUE="Upload">
									<INPUT TYPE="hidden" NAME="sections" VALUE="<?php echo $sections;?>">
									<input type="button" value="Upload File" onClick="checkstep1(this.form);"></TD>
				</TR>
				<TR>
					<TD COLSPAN=2>&nbsp;</TD>
				</TR>
				<TR BGCOLOR=#999999>
					<TD COLSPAN=2>&nbsp;</TD>
				</TR>
			</TABLE>
			</FORM>
		<?php
}
			
		function editSubsection($Itemid, $ItemName, $pagecontent, $status, $link, $fileEdit, $filecontent, $mamboEdit, $moduleid, $modulename, $moduleidlist, $modulenamelist, $option, $SectionID, $SectionName, $ItemIdList, $ItemNameList, $order, $maxOrder, $myname, $orderSectionid, $orderSectionName, $orderingSection, $sections, $heading, $browserNav){?>
			<SCRIPT LANGUAGE="Javascript">
			<!--
					sections = new Array();
				<?php
for ($i = 0; $i < count($orderSectionid); $i++){?>
						sections["<?php
echo $orderSectionid[$i]; ?>"] = <?php
echo $orderingSection["$orderSectionid[$i]"]; ?>;
			<?php
if ($orderSectionid[$i] == $Itemid){?>
						var originalSection = "<?php
echo $$orderSectionid[$i]; ?>";
			<?php
}?>
			<?php
unset($newvar); ?>
			<?php
}?>
				
				function changeOrder(section){
					var newsection = section.split(" ");
					var newsec = "";
					for (var j = 0; j < newsection.length; j++){
						newsec = newsec.concat(newsection[j]);
						}
					
					var orderlength = document.adminForm.order.options.length;
					for (var k = 0; k < orderlength; k++){
						document.adminForm.order.options[k] = null;
						}
					document.adminForm.order.options.length = 0;
					var numsec = sections[newsec];
					if (originalSection.indexOf(newsec) == -1){
						numsec++;
						}
						
					for (var i = 0; i < numsec; i++){
						var num = i + 1;
						document.adminForm.order.options[i] = new Option(num);	
						}
					document.adminForm.order.length = i;
				 	document.adminForm.order.options[0].selected = true;  //selects the first option in the second dropdown
					}
			//-->
			</SCRIPT>
			<FORM ACTION="index2.php" METHOD="POST" NAME="adminForm">
			<TABLE CELLPADDING="5" CELLSPACING="0" BORDER="0" WIDTH="100%">
				<TR BGCOLOR=#999999>
					<TD COLSPAN="2" CLASS="heading">Edit Menu Sub SectionItem</TD>
				</TR>
				<TR>
					<TD COLSPAN=2>&nbsp;</TD>
				</TR>
				<TR>
					<TD VALIGN="top">Item Name:</TD>
					<TD WIDTH="85%"><INPUT TYPE="text" NAME="ItemName" SIZE="25" VALUE="<?php
echo $ItemName; ?>"></TD>
				</TR>
				<TR>
					<TD>Section Name</TD>
					<TD><select name='SectionID' onChange='changeOrder(document.adminForm.SectionID.options[selectedIndex].value)'>
						<?php
for ($i = 0; $i < count($ItemIdList); $i++){
								if ($SectionID == $ItemIdList[$i]){?>
									<OPTION VALUE="<?php echo $SectionID;?>" SELECTED><?php echo $SectionName;?></OPTION>
								<?php
} else {?>
								<OPTION VALUE='<?php
echo $ItemIdList[$i]; ?>'><?php
echo $ItemNameList[$i]; ?></OPTION>
							<?php
}
								}?>
						</select>
					</TD>
				</TR>
				<?php if (trim($link)!=""){
					if ($fileEdit==1){?>
						<TR>
							<TD VALIGN=top>File content</TD>
							<TD><TEXTAREA COLS="70" ROWS="10" NAME="filecontent" STYLE="WIDTH=500px" WIDTH=500><?php
echo $filecontent; ?></TEXTAREA>
								<INPUT TYPE="hidden" NAME="link2" VALUE="<?php
echo $link; ?>">
								<BR>
								<A HREF="#" onClick="window.open('inline_editor/editor.htm?filecontent', 'win1', 'width=650, height=450, resizable=yes');">Edit Text In Editor</A>
							</TD>
						</TR>
					<?php
}else if ($mamboEdit==1){?>
						<TR>
							<TD>Mambo Module:</TD>
							<TD><select name="moduleID">
									<option value="<?php echo $moduleid;?>" selected><?php echo $modulename;?></option>
									<?php for ($i = 1; $i < count($moduleidlist); $i++){?>
										<OPTION VALUE='<?php
echo $moduleidlist[$i]; ?>'><?php
echo $modulenamelist[$i]; ?></OPTION>
									<?php
}?>
								</select>
							</TD>
						</TR>
					<?php
}else{?>
						<TR>
							<TD VALIGN="top">Link:</TD>
							<TD><INPUT TYPE="text" NAME="Weblink" SIZE="25" VALUE="<?php
echo $link; ?>"></TD>
						</TR>
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
						<TD>Page Heading:</TD>
						<TD><INPUT TYPE="text" NAME="heading" VALUE="<?php echo $heading;?>"></TD>
					</TR>
					<TR>
						<TD VALIGN="top">Content:</TD>
						<TD><TEXTAREA COLS="70" ROWS="10" NAME="pagecontent" STYLE="WIDTH=500px" WIDTH=500><?php
echo htmlentities($pagecontent); ?></TEXTAREA>
							<BR>
							<A HREF="#" onClick="window.open('inline_editor/editor.htm?pagecontent', 'win1', 'width=650, height=450, resizable=yes');">Edit Text In Editor</A>
						</TD>
					</TR>
				<?php
}?>
				<TR>
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
				</TR>
				<TR>
					<TD COLSPAN=2>&nbsp;</TD>
				</TR>
				<TR BGCOLOR=#999999>
					<TD COLSPAN=2>&nbsp;</TD>
				</TR>
				<INPUT TYPE="hidden" NAME="option" VALUE="<?php
echo $option; ?>">
				<INPUT TYPE="hidden" NAME="Itemid" VALUE="<?php
echo $Itemid; ?>">
				<INPUT TYPE="hidden" NAME="task" VALUE="">
				<INPUT TYPE="hidden" NAME="sections" VALUE="<?php echo $sections;?>">
				<INPUT TYPE="hidden" NAME="origOrder" VALUE="<?php echo $order;?>">
				<INPUT TYPE="hidden" NAME="myname" VALUE="<?php echo $myname;?>">
				<INPUT TYPE="hidden" NAME="origSecID" VALUE="<?php echo $SectionID;?>">
			</FORM>
			</TABLE>
			<?php	}
		}
?>

