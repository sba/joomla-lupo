# Changelog

### 3.82.0 (May 02, 2023)
  - fix toylist upload-error for genre with strlen > 30

### 3.81.0 (March 31, 2023)
  - increase upload max_file_size to 500mb 

### 3.80.0 (March 14, 2023)
  - add search by fabricator, author and artist

### 3.79.0 (January 6, 2023)
  - fix toylist upload error if genre erroneously starting with a comma

### 3.78.0 (December 1, 2022)
  - fix php 8 warning

### 3.77.0 (November 8, 2022) 
  - show genres in autofilter-select
  - allow deletion of filters

### 3.76.0 (September 1, 2022)
  - delete in online-reservation table if reservation in lupo exists for toy

### 3.75.0 (August 29, 2022)
  - store online reservations to cache table

### 3.74.0 (April 07, 2022)
  - send reservation: change button caption to "next"

### 3.73.0 (March 23, 2022)
  - plupload updated to version 2.3.9

### 3.72.0 (February 17, 2022)
  - fix SQL-error if toys are mistakenly duplicated in database

### 3.71.0 (January 31, 2022)
  - fix php-notice

### 3.70.0 (Januar 24, 2022)
  - fix php-notice and cleanup code

### 3.69.0 (November 29, 2021)
  - add open graph properties to toy view

### 3.68.0 (November 09, 2021)
  - bugfix: remove reserved toy as well from list in reservation form

### 3.67.0 (October 12, 2021)
  - enable prolongation for toys with today's return date

### 3.66.0 (September 30, 2021)
  - rename button: "in cart" to "show cart" (german only)

### 3.65.0 (September 22, 2021)
  - fix error showing related toys with index in toynumber 

### 3.64.0 (September 17, 2021)
  - new content plugin to display toy with image in content

### 3.63.0 (September 14, 2021)
  - add option to set reservation email sender address
  - add (german) default values for res-email to config
  - remove install postflight which was storing default res. mailtext 
  - imporove display of navigation-arrows in toy detail view

### 3.62.0 (September 11, 2021) 
  - add description to reservation option 

### 3.61.0 (September 10, 2021)
  - revert: add base url to loginlink

### 3.60.0 (September 10, 2021)
  - add base url to loginlink
  - revert: use own session for com_lupo

### 3.59.0 (September 10, 2021)
  - use own session for com_lupo  

### 3.58.0 (September 09, 2021)
  - start session when loading com_lupo

### 3.57.0 (September 08, 2021)
  - fix display of cart-link

### 3.56.0 (September 07, 2021)
  - remove margin if client login is disabled
  - caption for cart added if login is disabled

### 3.55.0 (September 04, 2021)
  - disable password-reset if no email addresses are uploaded

### 3.54.0 (September 03, 2021)
  - passwort forgotten function to login form added 
  - email and phone number added to client table

### 3.53.0 (August 31, 2021)
  - option to hide client login and only show cart-link for reservations

### 3.52.0 (August 23, 2021)
  - allow sending multiple reservations with one mail
  - reservation email recipient
  - new option to allow reservation only for loaned toys
  - upload/websync time display fixed   

### 3.51.0 (June 15, 2021)
  - show prolongable field within toy detail
  - show upload stats in admin upload view

### 3.50.0 (April 09, 2021)
  - some translated strings updated (it/fr) 

### 3.49.0 (March 29, 2021)
  - add support for filter by number of players

### 3.48.0 (March 25, 2021)
  - codestyle and unnecessary character deleted

### 3.47.0 (March 15, 2021)
  - compare typesafe in filter javascript
  - cleanup and codestyle

### 3.46.0 (March 11, 2021)
  - new option to control if filter shows categories or agecategories
  - filter can be disabled at menu level

### 3.45.0 (March 10, 2021)
  - show generic filter if no specific filter is defined
  - save input filter value and reselect on page load
  - refactor list views

### 3.44.0 (March 08, 2021)
  - toy detail view: move buttons above details table
  - remove flash and silverlight upload routines

### 3.43.0 (March 04, 2021)
  - show filter-items as buttons or dropdown
  - output ordered items in admin-filterlist
  - bugfix list genres in filter data-attribute
  - add missing files to installer

### 3.42.0 (March 02, 2021)
  - add buttons to filter list by a category or genre
  - add mobile number to reservation form  
  - refactor category views
  - custom css class to style badge with number of toys in mod_lupo_categories
  - bugfix view agecategory by menuitem
  - codestyle / cleanup

### 3.41.0 (November 25, 2020)
  - add missing field in sql-install script 
  - ignore double genre alias
  - \#__lupo_genres: make alias unique, not genre

### 3.40.0 (November 16, 2020)
  - fix display of quarantined returns 
  
### 3.39.0 (November 05, 2020)
  - plg_content_luporandomquote added to pkg_lupo

### 3.38.0 (September 18, 2020)
  - don't show link in toy-view for userdefined document attributes

### 3.37.0 (August 18, 2020)
  - add field age_number to #__lupo_agecategories

### 3.36.0 (May 28, 2020)
  - fix some typos in fr-FR
  - add language files to mod_lupo_quickicon zip

### 3.35.0 (May 27, 2020)
  - add language files to mod_lupo_quickicon install xml

### 3.34.0 (May 27, 2020)
  - hidden prolongation button was shown on mobile device anyway

### 3.33.0 (May 26, 2020)
  - improve translation possibilities

### 3.32.0 (May 17, 2020)
  - add missing field in sql install script 
  - complete french language files
  
### 3.31.0 (May 11, 2020)
  - mod_lupo_login table more responsive
  - fix de-DE translation
  
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
  - categories module: show \<hr> for - in line

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
  - make order of new games by acquired date optional
  
### 3.18.0 (July 02, 2019)
  - do not order new games by acquired date anymore
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
  
