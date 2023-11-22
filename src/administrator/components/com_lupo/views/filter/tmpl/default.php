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
<style>
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
        <textarea name="subsets" id="subsets" style="width: 80%" rows="20"><?php
            if ($subsets != null) {
                print_r(json_encode($subsets, JSON_PRETTY_PRINT));
            }
            ?></textarea>
        <input type="hidden" name="id" value="<?= $this->item['id'] ?>"/>
        <input type="hidden" name="task" value=""/>
        <?php echo JHtml::_('form.token'); ?>
    </form>
    <p>
        Die Filter müssen in gültigem JSON-Format und mit den alias-Namen gespeichert werden.
        <br>
        Filter-Attribute müssen nicht gesetzt werden, z.B. ist <code>{"filters": {"BUTTONNAME": {"players": ["1-spieler", "1-4-spieler"]}}}</code> zulässig.
        Ist "style" nicht gesetzt, so wird automatisch "dropdown" definiert.
        <br>
        Die einzelnen Filter werden logisch mit ODER verknüpft: <code>{"players": ["1", "2"], "genres": ["lesen"]}</code> Zeigt alle Spiele an, bei welchen eines der Argumente zutrifft.
        <br><br>
        Markup Filter-Button:
    </p>

    <?php
    $json_arr = ['filters' => ['BUTTONNAME' => ['categories' => [], 'agecategories' => [], 'genres' => [], 'players' => []]], 'style' => 'dropdown|buttons']; ?>
    <code><pre><?= json_encode($json_arr, JSON_PRETTY_PRINT); ?></pre></code>

    <p>
        Verfügbare Kategorien (alias):
    </p>
    <?php
    foreach ($this->categories as $category){
        $categories[] = '"'. $category['alias'].'"';
    } ?>
    <code><pre><?=implode(", \n", $categories)?></pre></code>

    <p>
        Verfügbare Alterskategorien (alias):
    </p>
    <?php
    foreach ($this->agecategories as $agecategory){
        $agecategories[] = '"'. $agecategory['alias'].'"';
    } ?>
    <code><pre><?=implode(", \n", $agecategories)?></pre></code>

    <p>
        Verfügbare Genres (alias):
    </p>
    <?php
    foreach ($this->genres as $genre){
        $genres[] = '"'. $genre['alias'].'"';
    } ?>
    <code><pre><?=implode(", \n", $genres)?></pre></code>

    <p>
        Verfügbare Anzahl Spieler (alias):
    </p>
    <?php
    foreach ($this->players as $player){
        $players[] = '"'. JApplicationHelper::stringURLSafe($player['players']).'"';
    } ?>
    <code><pre><?=implode(", \n", $players)?></pre></code>

    <p>
        Alle möglichen Filter einzeln aufgelistet:
    </p>
	<?php
	foreach ($this->categories as $category){
		$filter_def[$category['title']] = ["categories" => [$category['alias']]];
	}
	foreach ($this->agecategories as $agecategory){
		$filter_def[$agecategory['title']] = ["agecategories" => [$agecategory['alias']]];
	}
	foreach ($this->genres as $genre){
		$filter_def[$genre['genre']] = ["genres" => [$genre['alias']]];
	}
	foreach ($this->players as $player){
		$filter_def[$player['players']] = ["players" => [$player['players']]] ; //Caution: Different array structure
	}
    ?>
    <code><pre><?= json_encode(['filters' => $filter_def], JSON_PRETTY_PRINT); ?></pre></code>


</div>

