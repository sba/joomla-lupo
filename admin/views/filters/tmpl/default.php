<?php
/**
 * @package		Joomla
 * @subpackage	LUPO
 * @copyright   Copyright (C) databauer / Stefan Bauer
 * @author		Stefan Bauer
 * @link		https://www.ludothekprogramm.ch
 * @license		License GNU General Public License version 2 or later
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

// load tooltip behavior
JHtml::_('behavior.tooltip');
?>

<script>
    jQuery(document).ready(function () {

    });
</script>

<style type="text/css">
    .right {
        text-align: right !important;
    }
</style>

<div id="j-sidebar-container" class="j-sidebar-container j-sidebar-visible">
    <?php echo JHtmlSidebar::render(); ?>
</div>
<div id="j-main-container" class="span10 j-toggle-main">

    <table class="table table-striped table-hover">
        <thead>
        <tr>
            <th width="25%">
                Kategorie
            </th>
            <th>
                Suchfilter
            </th>
            <th>
                &nbsp;
            </th>
        </tr>
        </thead>
        <tbody>
        <?php if (!empty($this->items)) : ?>
            <?php foreach ($this->items as $i => $row) :
                $link = JRoute::_('index.php?option=com_lupo&view=filter&task=filter.edit&id=' . $row['id']);
                ?>
                <tr>
                    <td><?=$row['title']?></td>
                    <td><?php
                        $subsets = json_decode($row['subsets'],true);
                        if(is_array($subsets)) {
                            foreach ($subsets as $caption => $subset){
                            ?>
                            <b><?=$caption?></b><br>
                                <em>Kategorien:</em> <?= implode(', ',$subset['categories']);?><br>
                                <em>Alterskategorien:</em> <?= implode(', ',$subset['agecategories']);?><br>
                                <em>Genres:</em> <?= implode(', ',$subset['genres']);?><br><br>
                            <?php
                            }
                        }
                        ?>
                    </td>
                    <td class="right">
                        <a href="<?php echo $link; ?>" class="btn btn-small">
                            <span class="icon-edit"></span> Bearbeiten
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
    </table>

</div>

