# Changelog

### {{version_pkg_lupo}} ({{creationDate_changelog}})
  - mod_lupo_login table more responsive
  
### 3.30.0 (May 09, 2020)
  - Add hardcoded string to language file

### 3.29.0 (May 07, 2020)
  - Revert: Always show retour date beside red dot

### 3.28.0 (April 24, 2020)
  - Always show retour date beside red dot
    
### 3.27.0 (April 20, 2020)
  - add toy variables to resemail subject

### 3.26.0 (April 06, 2020)
  - fix php warning and notice when showing related games
  - codestyle 

### 3.25.0 (March 27, 2020)
  - show category/agegategory image if jpg file named with alias exists in images/spiele folder

### 3.24.0 (January 27, 2020)
  - save EAN to toy recordset
  
### 3.23.0 (January 13, 2020)
  - categories module: show order of filtered genres as written
  - categories module: show <hr> for - in line

### 3.22.0 (December 27, 2019)
  - add another missing field to sql-install script
  
### 3.21.0 (November 04, 2019)
  - add missing field to sql-install script

### 3.20.0 (October 16, 2019)
  - store toy-content to database

### 3.19.1 (October 16, 2019)
  - bugfix: do not allow to extend games with reservation within next 14 days
  
### 3.19.0 (October 07, 2019)
  - extend title field to 100 chars, so that title and edition-title is not truncated
  - replace double spaces in title, this improves search experience
  - show date in prolong button
  - don't offer prolong if extended date in today or in past
  - make order of new games by aquired date optional
  
### 3.18.0 (July 02, 2019)
  - do not order new games by aquired date anymore
  - fix php notices
    
### 3.17.0 (July 02, 2019)
  - block prolongation only if reservation is in 14 days or less 

### 3.16.0 (June 11, 2019)
  - hyphens in category table-view 
    
### 3.15.0 (May 02, 2019)
  - support of client-login via url with get param 
  
### 3.14.0 (April 02, 2019)
  - show orange status dot if reservation-date is in less or equal 35 days 
  
### 3.13.0 (March 18, 2019)
  - category description is shown above toy list

### 3.12.0 (January 23, 2019)
  - fix mixing of borrowed games when uploading zip-file with new games
  - give placeholder image to widgetkit content provider in case of no image

### 3.11.0 (December 05, 2018)
  - fix genres search result link
  - add option to hide 'now' in res-form
  - fix wrong language-var in resform error
  - remove all reservation dates before uploading them
  - if toy is borrowed and reserved show reservation-text too

### 3.10.0 (November 20, 2018)
  - only show orange reservation dot if the reservation is in the next 4 weeks
  - show the return date for borrowed toys
  - add missing columns to sql installer script
  - improved error handling on zip-upload
  - code cleanup

### 3.9.0 (November 01, 2018)
  - SEO optimized routing for toy genres

### 3.8.1 (October 19, 2018)
  - new option to hide from-date in reservation dialog

### 3.8.0 (October 01, 2018)
  - author an artist added to game-object
  - multi-site support for zip-uploader
  - fixed broken link to game in client's gameslist

### 3.7.1 (June 28, 2018)
  - show admin button 'reprocess xml' to all admin-users

### 3.7.0 (Mai 29, 2018)
  - added date of next reservation to game to prevent prolongation

### 3.6.0 (April 11, 2018)
  - new SEO optimized routing for game and category URLs
  - only show photo-column if at least one foto exists
  - fix bug with placeholder photo thumbnail
  - use main image instead of thumb_
  - optimize database query performance
  - support search by toy-number
  - detect youtube and vimeo in document-link
  - captcha removed from reservation form
  - added id_databauer_id for toys from database
  - updated language strings
  - deprecated code updated
  - uikit updated to 2.27.5
  - plupload updated to version 2.3.6

### 3.5.1 (June 04, 2017)
  - error in php 7 static function call

### 3.5.0 (June 02, 2017)
  - error in php 7 with assignment by reference
  - added option to hide photo in search result 

### 3.4.0 (May 30, 2017)
  - added content plugin to show nubmer of total toys 
  - update installer SQL, change engine to InnoDB
  - author/artist added to toy
  - Fix php notices

### 3.3.0 (March 06, 2017)
  - show delete photos button in admin to all users
  - Fix php warning
  - Remove unnessesary css include

### 3.2.1 (November 07, 2016)
  - Option added to display infos about reservation costs 

### 3.2.0 (November 01, 2016)
  - Show tax for prelong toys
  - add option to sort categories alphabetical
  - added function to delete toy images
  - uikit updated to 2.27.2
  - fix js captcha error

### 3.1.0 (October 14, 2016)
  - new option to show reservation form only to registered users
  - show captcha in reservation form
  - make view tables more responsive
  - optional placeholder image
  - output sample games in categories list
  - show thumb-pic if full pic is not uploaded
  - sort genres alphabetical
  - added option to hide toy description in list
  - various other codestyle fixes and improvements

### 3.0.0 (August 31, 2016)
  - show availbility of toys
  - client login implemented
  - online prolongation added
  - added possibility to output toy categories with details
  - do not show ".0" in game-number
  - use of "showon" in config
  - uikit updated to 2.27.1
  - plupload updated to 2.1.9 (security update)
  - various other codestyle fixes and improvements

### 2.9.0 (April 19, 2016)
  - increase max upload fileseize to 250MB
  - show XML-reprocess button only to superadmins
  - sort list with new games first by acquiredate, then by title
  - show edition-text in title if only one toy in toy-family
  - uploader: fix error when showing upload errormessage
  - do not show reservation-form by default
  - uikit updated to 2.26.2

### 2.8.0 (February 17, 2016)
  - toy reservation form added
  - access.xml added for minimal ACL
  - wrong language variable fixed
  - field aquired_date added to game object  

### 2.7.0 (January 04, 2016)
  - show related toys in slider
  - shuffle list of related toys
  - changed behavior to fetch new toys
  - uikit updated
  - make detail-view more responsive
  - do not show photo if set in options
  - toy document logic moved to model
  - fix notice php-error
  - code cleanup
  - code quality updates

### 2.6.0 (November 19, 2015)
  - option to disable lightbox added
  - uikit updated
  - upload folder moved from admin to tmp dir
  - page-title according joomla-settings
  - quote query params
  - code quality updates

### 2.5.0 (September 30, 2015)
  - game object extended for use with widgetkit
  - uikit and plupload updated
  - show wikipedia-w in link-button

### 2.4.0 (June 24, 2015)
  - description-title added to all views
  - detect menu itemid in game view
  - fix display of image if multi-toy
  - uikit and plupload updated

### 2.3.0 (April 28, 2015)
  - description-title added

### 2.2.1 (April 27, 2015)
  - game navigation changed from bottons to arrows
  - uikit updated to 2.19.0

### 2.2.0 (March 26, 2015)
  - joomla one click update added
  - toy-library uploader improved
  - code cleanup

### 2.1.1 (March 25, 2015)
  - do not load uikit.js files
  - uikit-lightbox layout fix
  - bugfix annoying php notice
  - codestyle / cleanup

### 2.1.0 (March 13, 2015)
  - uikit updated
  - ajax uploader for lupo zipfile
  - list layout improved

### 2.0.4 (February 13, 2015)
  - several fixes
  - added admin-button to reprocess xml-file

### 2.0.3 (February 03, 2015)
  - game view updated
  - sql-update fixed
  - xml upload compatibility enhancement

### 2.0.2 (Januar 30, 2015)
  - views updated

### 2.0.1 (Januar 29, 2015)
  - display photo in list (optional)

### 2.0.0 (Januar 28, 2015)
  - listing of agecategories added (view, menutype)
  - listing of genres added (view, menutype)
  - game detail view: output of buttons with document-links
  - some as deprecated marked joomla-functions replaced
  - updated to uikit 2.16.2

### 1.8.0 (Januar 26, 2015)
  - fields added: keywords and genres

### 1.7.0 (November 19, 2014)
  - french translation added

### 1.6.0 (November 06, 2014)
  - some option fields renamed 

### 1.5.0 (October 14, 2014)
  - uikit integration added
  
