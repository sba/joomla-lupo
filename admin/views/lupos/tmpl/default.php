<?php
/**
 * @package     LUPO
 * @copyright   Copyright (C) databauer / Stefan Bauer
 * @author      Stefan Bauer
 * @link        https://www.ludothekprogramm.ch
 * @license     License GNU General Public License version 2 or later
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

?>

<script src="components/com_lupo/assets/plupload/js/plupload.full.min.js"></script>
<script>
    jQuery(document).ready(function () {
        var uploader = new plupload.Uploader({
            runtimes: 'html5,html4',
            browse_button: 'pickfiles',
            container: 'container',

            max_file_size: '500mb',
            multi_selection: false,
            max_file_count: 1,

            chunk_size: "3000kb",
            max_retries: 3,

            url: 'components/com_lupo/models/upload.php?JPATH_SITE=<?=str_replace('\\', '\\\\', JPATH_SITE)?>',

            filters: [
                {title: "Zip files", extensions: "zip"}
            ],

            init: {
                PostInit: function () {
                    jQuery('#filelist').html('');

                    jQuery('#uploadfiles').click(function () {
                        uploader.start();
                        jQuery('#processzip').prop('disabled', true);
                        return false;
                    });
                },

                FilesAdded: function (up, files) {
                    var fileCount = up.files.length;
                    var ids = jQuery.map(up.files, function (item) {
                        return item.id;
                    });

                    for (i = 1; i < fileCount; i++) {
                        uploader.removeFile(uploader.getFile(ids[i]));
                    }

                    plupload.each(files, function (file) {
                        jQuery('#filelist').html(' <span id="' + file.id + '">' + file.name + ' (' + plupload.formatSize(file.size) + ') <b></b></span>');
                        jQuery('#upload_percent').html('');
                    });
                },

                UploadProgress: function (up, file) {
                    jQuery('#upload_percent').html(file.percent + "%");
                },

                FileUploaded: function (up, file) {
                    jQuery('#upload_percent').html('<b>100%</b>  <span class="icon-ok"> </span> ' + jQuery("#" + file.id).html());
                    jQuery('#processzip').prop('disabled', false);
                    jQuery('#filelist').html('');
                    uploader.splice();
                },

                Error: function (up, err) {
                    var errorMsg;
                    if (err.code == -600) {
                        errorMsg = "FEHLER: Die Datei " + err.file.name + " ist zu gross.";
                        errorMsg = '<span style="color:red"><b>' + errorMsg + '</b></span>';
                        jQuery('#filelist').html(errorMsg);
                    } else {
                        json = JSON.parse(err.response);
                        err = json.error;
                        errorMsg = '<span style="color:red"><b>' + "Error #" + err.code + ": " + err.message + '</b></span>';
                        jQuery('#upload_percent').html(errorMsg);
                    }
                }
            }
        });

        uploader.init();
    });
</script>

<style>
    #lupo .btn {
        min-width: 340px;
    }

    #upload_percent {
        font-size: 120%;
    }
    .stats-label {
        display: inline-block;
        width: 150px;
    }
</style>

<div id="j-sidebar-container" class="j-sidebar-container j-sidebar-visible">
    <?php echo JHtmlSidebar::render(); ?>
</div>
<div id="j-main-container" class="span10 j-toggle-main">
    <div id="lupo">
        <div id="container">
            <a id="pickfiles" href="javascript:;">
                <button class="btn btn-large">1. <?php echo JText::_("COM_LUPO_ADMIN_SELECT_ZIP") ?></button>
            </a> <span id="filelist">Your browser doesn't have HTML5 support.</span>
            <br/>
            <br/>
            <a id="uploadfiles" href="javascript:;"><button class="btn btn-large">2. <?php echo JText::_("COM_LUPO_ADMIN_UPLOAD_FILE") ?></button></a>
            <span id="upload_percent"></span>
        </div>

        <br/>

        <form action="<?php echo JRoute::_('index.php?option=com_lupo'); ?>" method="post" name="processZIPForm">
            <input type="hidden" name="act" value="processzip"/>
            <input type="submit" name="submit" class="btn btn-large" id="processzip" value="3. <?php echo JText::_("COM_LUPO_ADMIN_PROCESS") ?>"/>
        </form>

        <br/>
        <hr/>
        <br/>
        <br/>

        <form action="<?php echo JRoute::_('index.php?option=com_lupo'); ?>" method="post" name="processXMLForm">
            <input type="hidden" name="act" value="processxml"/>
            <input type="submit" name="submit" class="btn" value="<?php echo JText::_("COM_LUPO_ADMIN_PROCESS_AGAIN") ?>"/>
        </form>

        <form action="<?php echo JRoute::_('index.php?option=com_lupo'); ?>" method="post" name="deleteImagesForm">
            <input type="hidden" name="act" value="deleteimages"/>

            <input type="submit" name="submit" class="btn btn-danger" value="<?php echo JText::_("COM_LUPO_ADMIN_DELETE_IMAGES") ?>"/>
        </form>

        <?php
        //show upload date
        $stats_file = JPATH_ROOT . '/images/upload_stats.json';
        if (file_exists($stats_file)) {
            $json = json_decode(file_get_contents($stats_file), true);
            ?>
            <br/>
            <br/>
            <hr/>
            <br/>
            <h3><?php echo JText::_("COM_LUPO_ADMIN_STATS") ?></h3>
            <?php if (isset($json['toylist'])) { ?>
                <br/><span class="stats-label"><?php echo JText::_("COM_LUPO_ADMIN_STATS_UPLOAD") ?></span> <?= strtotime($json['toylist'])>0?date('d.m.Y H:i', strtotime($json['toylist'])):'-' ?>
            <?php } ?>
            <?php if (isset($json['websync_ausleihen'])) { ?>
                <br/><span class="stats-label"><?php echo JText::_("COM_LUPO_ADMIN_STATS_WEBSYNC") ?></span> <?= strtotime($json['websync_ausleihen'])>0?date('d.m.Y H:i', strtotime($json['websync_ausleihen'])):'-' ?>
            <?php } ?>

        <?php } ?>
    </div>
</div>

