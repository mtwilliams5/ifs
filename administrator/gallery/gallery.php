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
	 *	File Name: gallery.php
	 *	Developers: Danny Younes - danny@miro.com.au
	 *				Nicole Anderson - nicole@miro.com.au
	 *	Date: 27-11-2002
	 * 	Version #: 4.0.11
	 *	Comments:
	**/
?>
<html>
<head>
	<title>Mambo Site Server - Image Gallery</title>
<!-- frames -->
<FRAMESET  ROWS="83%,*">
    <FRAMESET  COLS="15%,*">
        <FRAME NAME="navigation" SRC="navigation.php?directory=<?php echo $directory;?>&Itemid=<?php echo $Itemid;?>" MARGINWIDTH="0" MARGINHEIGHT="0" SCROLLING="no" FRAMEBORDER="0" NORESIZE>
        <FRAME NAME="images" SRC="index.php?directory=<?php echo $directory;?>&Itemid=<?php echo $Itemid;?>" MARGINWIDTH="0" MARGINHEIGHT="0" SCROLLING="auto" FRAMEBORDER="0">
    </FRAMESET>
    <FRAME NAME="imagecode" SRC="imagecode.php" MARGINWIDTH="0" MARGINHEIGHT="0" SCROLLING="no" FRAMEBORDER="0" NORESIZE>
</FRAMESET><noframes></noframes>
	
</head>

<body BGCOLOR="#FFFFFF">



</body>
</html>
