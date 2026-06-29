USE `__DATABASE__`;

#TRUNCATE #__update_sites;
#TRUNCATE #__update_sites_extensions;

TRUNCATE #__session;

#UPDATE `#__extensions` SET enabled=0 WHERE folder='editors-xtd' AND element IN('pagebreak','contact','fields','weblink');
#UPDATE `#__extensions` SET enabled=1 WHERE folder='editors-xtd' AND element IN('lupotoy','module');

UPDATE #__extensions SET params = '{"upload_maxsize":"10","file_path":"images","image_path":"images","restrict_uploads":"1","allowed_media_usergroup":"3","restrict_uploads_extensions":"bmp,gif,jpg,jpeg,png,webp,avif,ico,mp3,m4a,mp4a,ogg,mp4,mp4v,mpeg,mov,odg,odp,ods,odt,pdf,ppt,txt,xcf,xls,csv","check_mime":"1","image_extensions":"bmp,gif,jpg,png,jpeg,webp,avif","audio_extensions":"mp3,m4a,mp4a,ogg","video_extensions":"mp4,mp4v,mpeg,mov,webm","doc_extensions":"odg,odp,ods,odt,pdf,ppt,txt,xcf,xls,csv","ignore_extensions":"","upload_mime":"image\/jpeg,image\/gif,image\/png,image\/bmp,image\/webp,image\/avif,audio\/ogg,audio\/mpeg,audio\/mp4,video\/mp4,video\/webm,video\/mpeg,video\/quicktime,application\/msword,application\/excel,application\/pdf,application\/powerpoint,text\/plain,application\/x-zip"}' WHERE NAME='com_media' LIMIT 1;


UPDATE `#__wf_profiles`
SET `params` = '{"formatselect":{"blockformats":"p,h1,h2,h3,h4,blockquote"},"editor":{"toolbar_theme":"default","toolbar_align":"left","path":"1","resize_quality":"80","allow_css":"1","custom_colors":"#ea488c,#7c1f46","max_size":"4096","text_editor_theme":"codemirror"},"clipboard":{"paste_use_dialog":"1","paste_dialog_width":"550","paste_force_cleanup":"1","paste_strip_class_attributes":"1","paste_remove_spans":"1","buttons":["paste","pastetext"]},"link":{"links":{"joomlalinks":{"contacts":"0","weblinks":"0","weblinks_alias":"0"}},"search":{"link":{"plugins":["lupogenres","lupo"]}},"tabs_advanced":"0","popups":{"jcemediabox":{"enable":"0"},"window":{"enable":"0"}}},"filemanager":{"option_size_check":"0","option_date_check":"0","folder_move":"0","popups":{"jcemediabox":{"enable":"0"}},"max_size":"4096"},"imgmanager_ext":{"tabs_rollover":"0","popups":{"jcemediabox":{"enable":"0"},"window":{"enable":"0"}},"tabs_advanced":"0"},"mediamanager":{"aggregator":{"vine":{"enable":"0"},"dailymotion":{"enable":"0"},"vimeo":{"enable":"1"},"youtube":{"width":"602","height":"400"}},"popups":{"jcemediabox":{"enable":"0"}},"extensions":"-windowsmedia=-avi,-wmv,-wm,-asf,-asx,-wmx,-wvx;-quicktime=-mov,-qt,mpg,mpeg,-m4a;-flash=-swf;-shockwave=-dcr;-real=-rm,-ra,-ram;-divx=-divx;video=mp4,-ogv,-ogg,-webm;audio=mp3,-ogg,-webm,-wav;-silverlight=-xap"},"table":{"show_buttons":"0"},"templatemanager":{"dir":"jce_templates","":"0","folder_new":"0","folder_delete":"0","folder_rename":"0","folder_move":"0","file_delete":"0","file_rename":"0","file_move":"0","template_dialog":"0"},"setup":{"custom":""}}'
WHERE `name` = 'Default';



/* ------------------------------------------------------------------------------------------------------- */

