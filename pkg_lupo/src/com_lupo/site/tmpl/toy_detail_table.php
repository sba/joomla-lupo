<?php
defined('_JEXEC') or die;

$game = $displayData['game'];
$componentParams = $displayData['componentParams'];
$show_diverse = $displayData['show_diverse'];
$show_without_age = $displayData['show_without_age'];
?>
<table class="uk-table uk-table-striped uk-table-condensed" id="lupo_detail_table">
    <colgroup>
        <col style="width:150px">
        <col/>
    </colgroup>
    <?php if ($componentParams->get('detail_show_toy_no', '1')) { ?>
        <tr>
            <td><?php echo JText::_("COM_LUPO_ART_NR") ?>:</td>
            <td><?php echo (substr($game['number'], -2, 2) == '.0') ? (int) $game['number'] : $game['number'] ?></td>
        </tr>
    <?php } ?>
    <?php if ($componentParams->get('detail_show_toy_category', '1') && $show_diverse) { ?>
        <tr>
            <td><?php echo JText::_("COM_LUPO_CATEGORY") ?>:</td>
            <td>
                <a href="<?php echo $game['link_cat'] ?>"><?php echo $game['category'] ?></a>
            </td>
        </tr>
    <?php } ?>
    <?php if ($componentParams->get('detail_show_toy_age_category', '1') && $show_without_age) { ?>
        <tr>
            <td><?php echo JText::_("COM_LUPO_AGE_CATEGORY") ?>:</td>
            <td>
                <a href="<?php echo $game['link_agecat'] ?>"><?php echo $game['age_category'] ?></a>
            </td>
        </tr>
    <?php } ?>
    <?php if ($componentParams->get('detail_show_toy_genres', '1') && $game['genres'] != "") { ?>
        <tr>
            <td><?php echo JText::_("COM_LUPO_GENRES") ?>:</td>
            <td>
                <?php $separator = "";
                foreach ($game['genres_list'] as $genre) {
                    echo $separator ?><a
                    href="<?php echo $genre['link'] ?>"><?php echo $genre['genre'] ?></a><?php
                    $separator = ", ";
                }
                ?>
            </td>
        </tr>
    <?php } ?>
    <?php if ($componentParams->get('detail_show_toy_play_duration', '1') && $game['play_duration'] != "") { ?>
        <tr>
            <td><?php echo JText::_("COM_LUPO_PLAY_DURATION") ?>:</td>
            <td><?php echo $game['play_duration'] ?></td>
        </tr>
    <?php } ?>
    <?php if ($componentParams->get('detail_show_toy_players', '1') && $game['players'] != "") { ?>
        <tr>
            <td><?php echo JText::_("COM_LUPO_PLAYERS") ?>:</td>
            <td><?php echo $game['players'] ?></td>
        </tr>
    <?php } ?>
    <?php if ($componentParams->get('detail_show_toy_fabricator', '1') && $game['fabricator'] != "") { ?>
        <tr>
            <td><?php echo JText::_("COM_LUPO_FABRICATOR") ?>:</td>
            <td><?php echo $game['fabricator'] ?></td>
        </tr>
    <?php } ?>
    <?php if ($componentParams->get('detail_show_toy_author', '1') && $game['author'] != "") { ?>
        <tr>
            <td><?php echo JText::_("COM_LUPO_AUTHOR") ?>:</td>
            <td><?php echo $game['author'] ?></td>
        </tr>
    <?php } ?>
    <?php if ($componentParams->get('detail_show_toy_artist', '1') && $game['artist'] != "") { ?>
        <tr>
            <td><?php echo JText::_("COM_LUPO_ARTIST") ?>:</td>
            <td><?php echo $game['artist'] ?></td>
        </tr>
    <?php } ?>
    <?php if ($componentParams->get('detail_show_toy_nbrdays', '1')) { ?>
        <tr>
            <td><?php echo JText::_("COM_LUPO_DAYS") ?>:</td>
            <td><?php echo $game['days'] ?></td>
        </tr>
    <?php } ?>
    <?php if ($componentParams->get('detail_show_toy_tax', '1') && ($componentParams->get('detail_show_toy_tax_not_null', '1') == '0' || $game['tax'] > 0)) { ?>
        <tr>
            <td><?php echo JText::_("COM_LUPO_TAX") ?>:</td>
            <td>Fr. <?php echo number_format($game['tax'], 2) ?></td>
        </tr>
    <?php } ?>
    <?php if ($componentParams->get('lupo_show_toystatus', '0')) { ?>
        <tr>
            <td><?php echo JText::_("COM_LUPO_AVAILABILITY") ?>:</td>
            <td>
                <i class="uk-icon uk-icon-circle <?= $game['availability_color'] ?> availability_dot"></i> <?= $game['availability_text'] ?>
            </td>
        </tr>
    <?php } ?>
    <?php if ($componentParams->get('detail_show_toy_prolongable', '0')) { ?>
        <tr>
            <td><?php echo JText::_("COM_LUPO_PROLONGABLE") ?>:</td>
            <td><?= $game['prolongable'] ? JText::_("JYES") : JText::_("JNO") ?></td>
        </tr>
    <?php } ?>
</table>
