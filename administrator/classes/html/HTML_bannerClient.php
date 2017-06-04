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
	 *	File Name: HTML_bannerClient.php
	 *	Developers: Danny Younes - danny@miro.com.au
	 *				Nicole Anderson - nicole@miro.com.au
	 *	Date: 27-11-2002
	 * 	Version #: 3.0.5
	 *	Comments:
	**/
	
	class HTML_bannerClient {
		function showClients($clientid, $cname, $option, $numBanners, $editor){?>
			<FORM ACTION="index2.php" METHOD="POST" NAME="adminForm">
			<TABLE CELLPADDING="5" CELLSPACING="0" BORDER="0" WIDTH="100%">
			<TR BGCOLOR=#999999>
				<TD COLSPAN="2" CLASS="heading">Banner Clients</TD>
				<TD ALIGN=CENTER CLASS="heading"># Active Banners</TD>
				<TD ALIGN=CENTER CLASS="heading">Checked Out</TD>
			</TR>
			<?php
$color = array("#FFFFFF", "#CCCCCC");
			$k = 0;
			for ($i = 0; $i < count($clientid); $i++){?>
			<TR BGCOLOR="<?php
echo $color[$k]; ?>">
				<TD WIDTH="20"><INPUT TYPE="checkbox" NAME="cid[]" VALUE="<?php
echo $clientid[$i]; ?>" onClick="isChecked(this.checked);"></TD>
				<TD WIDTH="80%"><?php
echo $cname[$i]; ?></TD>
				<TD WIDTH="10%" ALIGN=CENTER><?php
echo $numBanners[$i];?></TD>
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
			
			
		function addBannerClient($option){?>
			<FORM ACTION="index2.php" METHOD="POST" NAME="adminForm">
			<TABLE CELLPADDING="5" CELLSPACING="0" BORDER="0" WIDTH="100%">
			<TR BGCOLOR=#999999>
				<TD COLSPAN="2" CLASS="heading">Add New Banner Client</TD>
			</TR>
			<tr>
				<td colspan=3>&nbsp;</td>
			</tr>
			<tr>
				<td width=10%>Client Name:</td>
				<td><input type=text name=cname size=30 maxlength=60 valign=top></td>
			</tr>
			<tr>
				<td width=10%>Contact Name:</td>
				<td><input type=text name=contact size=30 maxlength=60></td>
			</tr>
			<tr>
				<td width=10%>Contact Email:</td>
				<td><input type=text name=email size=30 maxlength=60></td>
			</tr>
			<!--<tr>
				<td width=10%>Client Login:</td>
				<td><input type=text name=login size=12 maxlength=10></td>
			</tr>
			<tr>
				<td width=10%>Client Password:</td>
				<td><input type=password name=passwd size=12 maxlength=10></td>
			</tr>-->
			<tr>
				<td colspan=2>Extra Info:</td>
			</tr>
			<tr>
				<td colspan=2><textarea name=extrainfo cols=60 rows=10><?php
echo htmlentities($extrainfo);?></textarea>
					<INPUT TYPE="hidden" NAME="option" VALUE="<?php
echo $option; ?>">
					<INPUT TYPE="hidden" NAME="task" VALUE="">
				</td></tr>
			<tr>
				<td colspan=3></FORM>&nbsp;</td>
			</tr>
			<tr BGCOLOR=#999999>
				<td colspan=3>&nbsp;</td>
			</tr>
			</table>
			
			<?php
}
		
			
		function editBannerClient($clientid, $cname, $contact, $email, $extrainfo, $option, $myname){?>
			<FORM ACTION="index2.php" METHOD="POST" NAME="adminForm">
			<TABLE CELLPADDING="5" CELLSPACING="0" BORDER="0" WIDTH="100%">
			<TR BGCOLOR=#999999>
				<TD COLSPAN="2" CLASS="heading">Edit Banner Client</TD>
			</TR>
			<tr>
				<td colspan=3>&nbsp;</td>
			</tr>
			<tr>
				<td width=10%>Client Name:</td>
				<td><input type=text name=cname size=30 maxlength=60 value="<?php echo $cname;?>"></td>
			</tr>
			<tr>
				<td width=10%>Contact Name:</td>
				<td><input type=text name=contact size=30 maxlength=60 value="<?php echo $contact;?>"></td>
			</tr>
			<tr>
				<td width=10%>Contact Email:</td>
				<td><input type=text name=email size=30 maxlength=60 value="<?php echo $email;?>"></td>
			</tr>
			<!--<tr>
				<td width=10%>Client Login:</td>
				<td><input type=text name=login size=12 maxlength=10 value="<?php echo $login;?>"></td>
			</tr>
			<tr>
				<td width=10%>Client Password:</td>
				<td><input type=password name=passwd size=12 maxlength=10 value="<?php echo $passwd;?>"></td>
			</tr>-->
			<tr>
				<td colspan=2>Extra Info:</td>
			</tr>
			<tr>
				<td colspan=2><textarea name=extrainfo cols=60 rows=10><?php echo htmlentities($extrainfo);?></textarea></td>
			</tr>
			<tr>
				<td colspan=3>&nbsp;</td>
			</tr>
			<tr BGCOLOR=#999999>
				<td colspan=3>&nbsp;</td>
			</tr>
				<INPUT TYPE="hidden" NAME="option" VALUE="<?php
echo $option; ?>">
				<INPUT TYPE="hidden" NAME="clientid" VALUE="<?php
echo $clientid; ?>">
				<INPUT TYPE="hidden" NAME="task" VALUE="">
				<INPUT TYPE="hidden" NAME="myname" VALUE="<?php echo $myname;?>">
			</FORM>
			</TABLE>
			<?php	}
	}
?>
