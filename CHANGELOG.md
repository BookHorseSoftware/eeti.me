# eeti.me changelog

eeti.me v2 is versioned semantically (for the most part). Each new minor release
is given a codename, usually after a Vocaloid song.

## Previous releases

### 2.1.x [There's Supposed to be a Cheat Code for Happiness](https://www.youtube.com/watch?v=wyUR1_19KAM) - 23 August 2015
Latest release in series: 2.1.0 (obsoleted by 2.2.0)

  * Initial upload of everything

### 2.2.x [LUVORATORRRRRY!](https://www.youtube.com/watch?v=oG2I17Zm8kI) - 29 August 2015
Latest release in series: 2.2.0 (obsoleted by 2.3.0)

  * Add administrative control panel
  * Add setup wizard
  * Add pasting
  * Add flags and enhanced permissions
  * Convert data structures from a custom format to JSON
  * Allow for more customization using configuration settings
  * Prepare for mailing
  * Fix several security/quality issues

### 2.3.x [Souaisei Riron](https://www.youtube.com/watch?v=XARnDyuwjIQ) - 12 September 2015
Latest release in series: 2.3.0 (obsoleted by 2.4.0)

  * Added interactive installer
  * Removed salutations
  * Added better invitations system
  * Several hotfixes for installer/upgrader
  * Added mail settings to prepare for future release
  * Added tweaking of core settings through Web interface

### 2.4.x [Ai Kotoba](https://www.youtube.com/watch?v=qUuK1rdmuv4) - 14 September 2015
Latest release in series: 2.4.0 (obsoleted by 2.5.0)

  * Refactored constant usage
  * Added more configuration settings
    * Added options in core to enable/disable features
  * Deleted old/unsupported/outdated way of creating users

### 2.5.x [Romeo and Cinderella](https://www.youtube.com/watch?v=swqbfMh467A) - 11 October 2015
Latest release in series: 2.5.2 (obsoleted by 2.5.2u)

  * Added site announcements
  * Added logging
  * Added 404/htaccess
  * First public release

### 2.5.2u, 2.6.x [Revolution of Liz's Innermost Heart](https://www.youtube.com/watch?v=lbFEPWAQp88) - 14 October 2015
Latest release in series: 2.6.1 (obsoleted by 2.7.0)

**DATABASE UPGRADE REQUIRED** (performed on upgrade from 2.5.2, "upgraded" version is 2.5.2u)

  * Added more precise upload logging
  * Added the ability for users to view their uploaded files
  * Consolidated some menus

### 2.7.x [Aimai na Uta](https://www.youtube.com/watch?v=geKcl361kEs) - 15 October 2015
Latest release in series: 2.7.0 (obsoleted by 2.8.0)

  * Added log filtering (with icons by the Tango project)
  * Added logging of access violations
  * Added option to disable access requests
  * Refactored some internal logging code
  * Fixed "uploaded by Anonymous" bug when using ShareX

### 2.8.x [Tokyo Summer Session](https://www.youtube.com/watch?v=q0T7Ex7MkLM) - 4 August 2016
Latest release in series: 2.8.6 (obsoleted by 2.9.0)

  * Rework of changelog
  * Lots of internal code refactoring
  * Pastes now appear in user-uploaded files list
  * Add ShareX integration instructions
  * Added file deletion
  * Added a better file viewing page
  * Fix a bug with join requests
  * Quick hotfix displaying "xxx's Web site" and "View your uploaded files" on a separate line
  * Quick hotfix adding the actual image location to the file preview (oops)
  * Quick hotfix for weird image displaying
  * Add better pagination functions to viewfiles.php
  * Add viewfiles.php to nav
  * Make the new file list look more like the old one

### 2.9.x [Love Logic](https://www.youtube.com/watch?v=-6oxY-quTOA)
Latest release in series: 2.9.1 (obsoleted by 2.10.0)

  * Remove the T flag because it's never actually used anymore
  * User profile descriptions are now parsed in Markdown
  * Users now have smaller label-badges to indicate their flags
  * Quick hotfix for broken user avatars

### 2.10.x [Sayonara Ryou Kataomoi](https://www.youtube.com/watch?v=hNEVPxh0c-I)
Latest release in series: 2.10.0 (obsoleted by 2.11.0)

  * Users are emailed when their invites are accepted, if email is configured
  * Several security fixes for sessions

### 2.11.x [Confession Rehearsal](https://www.youtube.com/watch?v=YNkL6A1WRY8)
Latest release in series: 2.11.0

  * Security: only create the admin user if they don't already exist
  * Security: don't leak user details when using eetiSSO with favicon (see social-media-leak)
  * eetiSSO, more details in setup
