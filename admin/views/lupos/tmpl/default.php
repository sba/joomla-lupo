<?php
/**
 * @package		Joomla
 * @subpackage	LUPO
 * @copyright	Copyright (C) databauer / Stefan Bauer
 * @author		Stefan Bauer
 * @link				http://www.ludothekprogramm.ch
 * @license		License GNU General Public License version 2 or later
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

// load tooltip behavior
JHtml::_('behavior.tooltip');
?>
<br />

<script src="http://rawgithub.com/moxiecode/plupload/master/js/plupload.full.min.js" ></script>
<script>
	// jQuery not really required, it's here to overcome an inability to pass configuration options to the fiddle remotely
	jQuery(document).ready(function() {

		// Custom example logic
		/*function $(id) {
			return document.getElementById(id);
		}*/

		var uploader = new plupload.Uploader({
			runtimes : 'html5,flash,silverlight,html4',
			browse_button : 'pickfiles',
			container: 'container',

			max_file_size : '100mb',
			multi_selection: false,
			max_file_count: 1,

			url : 'components/com_lupo/models/upload.php',

			flash_swf_url : 'http://rawgithub.com/moxiecode/moxie/master/bin/flash/Moxie.cdn.swf',
			silverlight_xap_url : 'http://rawgithub.com/moxiecode/moxie/master/bin/silverlight/Moxie.cdn.xap',
			filters : [
				{title : "Zip files", extensions : "zip"}
			],

			init: {
				PostInit: function() {
					$('filelist').innerHTML = '';

					$('uploadfiles').onclick = function() {
						uploader.start();
						return false;
					};
				},

				FilesAdded: function(up, files) {

					var fileCount = up.files.length;
					var ids = jQuery.map(up.files, function (item) { return item.id; });

					for (i = 1; i < fileCount; i++) {
						uploader.removeFile(uploader.getFile(ids[i]));
					}

					plupload.each(files, function(file) {
						$('filelist').innerHTML = ' <span id="' + file.id + '">' + file.name + ' (' + plupload.formatSize(file.size) + ') <b></b></span>';
					});
				},

				UploadProgress: function(up, file) {
					//$(file.id).getElementsByTagName('b')[0].innerHTML = '<span>' + file.percent + "%</span>";
					jQuery('#upload_percent').html(file.percent + "%");
				},

				FileUploaded: function(up, file) {
					jQuery('#upload_percent').html('100%  <span class="icon-ok"> </span>');
				},

				Error: function(up, err) {
					jQuery('#upload_percent').html += "\nError #" + err.code + ": " + err.message;
				}
			}
		});

		uploader.init();

	});

</script>

<style type="text/css">
	#lupo .btn {
		min-width: 340px;
	}
	#upload_percent {
		font-size: 120%;
	}
</style>

<div id="lupo">
	<div id="container">
		<a id="pickfiles" href="javascript:;"><button class="btn btn-large">1. <?php echo JText::_("COM_LUPO_ADMIN_SELECT_ZIP")?></button></a> <span id="filelist">Your browser doesn't have Flash, Silverlight or HTML5 support.</span>
		<br />
		<br/>
		<a id="uploadfiles" href="javascript:;"><button class="btn btn-large">2. <?php echo JText::_("COM_LUPO_ADMIN_UPLOAD_FILE")?></button></a>
		<span id="upload_percent"></span>
	</div>


	<br />

	<form action="<?php echo JRoute::_('index.php?option=com_lupo'); ?>" method="post" name="processZIPForm">
		<input type="hidden" name="act" value="processzip" />

		<input type="submit" name="submit" class="btn btn-large" value="3. <?php echo JText::_("COM_LUPO_ADMIN_PROCESS")?>" />
	</form>

	<br />
	<hr />
	<br />

	<form action="<?php echo JRoute::_('index.php?option=com_lupo'); ?>" method="post" name="processXMLForm">
		<input type="hidden" name="act" value="processxml" />
		<br>

		<input type="submit" name="submit" class="btn btn" value="<?php echo JText::_("COM_LUPO_ADMIN_PROCESS_AGAIN")?>" />
	</form>
</div>

