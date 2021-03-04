<?php
/**
 * @package        Joomla
 * @subpackage    LUPO
 * @copyright   Copyright (C) databauer / Stefan Bauer
 * @author        Stefan Bauer
 * @link        https://www.ludothekprogramm.ch
 * @license        License GNU General Public License version 2 or later
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

    <h1><?= $this->item['title'] ?></h1>


    <?php
    $subsets = json_decode($this->item['subsets'], true);

    ?>
    <form action="<?= JRoute::_('index.php?option=com_lupo&view=filter') ?>" method="post" id="adminForm" name="adminForm">
        <textarea name="subsets" id="subsets" style="width: 98%" rows="30"><?php
            if ($subsets != null) {
                print_r(json_encode($subsets, JSON_PRETTY_PRINT));
            }
            ?></textarea>
        <input type="hidden" name="id" value="<?= $this->item['id'] ?>"/>
        <input type="hidden" name="task" value=""/>
        <?php echo JHtml::_('form.token'); ?>
    </form>
    <p>
        Die Filter müssen in gültigem JSON-Format und mit den alias-Namen gespeichert werden. Markup Filter-Button:
    </p>

    <?php
    $json_arr = ['filters' => ['BUTTONNAME' => ['categories' => [], 'agecategories' => [], 'genres' => []]], 'style' => 'dropdown|buttons']; ?>
    <pre><?= json_encode($json_arr, JSON_PRETTY_PRINT); ?></pre>

    <p>
        Verfügbare Kategorien (alias):
    </p>
    <?php
    foreach ($this->categories as $category){
        $categories[] = '"'. $category['alias'].'"';
    } ?>
    <pre><?=implode(", ", $categories)?></pre>

    <p>
        Verfügbare Alterskategorien (alias):
    </p>
    <?php
    foreach ($this->agecategories as $agecategory){
        $agecategories[] = '"'. $agecategory['alias'].'"';
    } ?>
    <pre><?=implode(", ", $agecategories)?></pre>

    <p>
        Verfügbare Genres (alias):
    </p>
    <?php
    foreach ($this->genres as $genre){
        $genres[] = '"'. $genre['alias'].'"';
    } ?>
    <pre><?=implode(", ", $genres)?></pre>

</div>

