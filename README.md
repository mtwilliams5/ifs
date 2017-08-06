This edition of IFS was altered from the 1.12 release, to correct several inherent flaws in the install. In addition, i've made several key changes to what is installed to the system by default and tweaked the default theme a little to give you a head start on getting this thing to look the way you want.

All subsequent work on IFS was done without the support, knowledge or approval of Frank Anon, the orginal Designer (Couldn't contact for comment). I keep an eye on the IFS forums at the Obsidian Fleet Forums and will respond to any support requests for this version there.

Good Luck,
Nolan

UPDATED: March 2014
I have applied various fixes in an attempt to get IFS running on php 5.5+. Note this software is now 10 years old (the mambo backend is from 2001!), The administration area no longer works and who knows what else. Due to the possibly considerable amount of work required to update the mambo components, this will be the final patch I issue for IFS.

UPDATED: June 2017
I have forked Nolan's repo of IFS in order to apply the various bug fixes and a number of enhancements that have been added into Pegasus Fleet's version of IFS over the years. This fork have been created with the permission of Nolan (provided in the issues of his IFS repo), but all subsequent work is done without any further support knowledge or approval of either Frank Anon or Nolan.

UPDATED: August 2017
With Patch 1.17, IFS has had a significant number of updates, which now mean that if you are upgrading from an existing install of IFS, you will need to make some changes to the database, and perhaps to files as well. It goes without saying that any modifications webmasters have made to IFS sites over the years will need to be checked against the files in this update, as we won't always have gone in the same direction for fixes. In short, here are the key points for updaters (line numbers correct at time of writing, but subjet to change in any future update):
- Compare your skin's css file with default.css, particularly in regards to the overridden bootstrap section and the various page-specific sections near the bottom of the file. The inclusion of Bootstrap has meant that a lot of styles need tweaking to get them to look appropriate in the IFS layout.
- The reports table has a new structure to match a more expanded monthly report layout. Experienced IFS maintainers will know how to add and remove sections of reports themselves, but make sure to check manual_install.php at line 248 for the new default structure.
- The tfreports table has renamed the webupdates column to the more generic improvements.
- There are several new entries in the menu table to accomodate new functions, particularly under Personnel Management. See manual_install.php lines 553-555 for details.
- The ranks directory for rank images is now assumed through the code, rather than defined in the database entries for each rank. If your rank database still starts the image location with ``ranks/``, then you have two options: either do a mass edit to remove that directory from the entries, or move your ranks directory into another one of the same name (e.g. ``images/ranks/ranks/rankimg``).
