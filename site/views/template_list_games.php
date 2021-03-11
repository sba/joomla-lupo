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
defined('_JEXEC') or die('Restricted access');

$componentParams = JComponentHelper::getParams('com_lupo');

if ($this->foto['show'] == '1') { ?>
    <style>
        #lupo_category_table tr td > p {
            padding-left: 115px
        }
    </style>
<?php } ?>

<article class="tm-article">
    <div class="tm-article-content">
        <h2 class="contentheading"><?php echo $this->title ?></h2>
        <br class="clearfix">
        <?php echo $this->description ?>
        <?php
        //TODO: fix joomla bug
        //$text = "<b>Lorem ipsum dolor sit amet,</b><br>consectetur adipisicing elit. Animi aspernatur aut deserunt dolorum excepturi, laudantium maxime minus molestiae nihil nobis numquam, quam quasi voluptatibus. Accusamus ipsum nullase isquam eos.";
        //$text = "<b>zwei DODO sind ein DiDeLiDi</b><br>Neben dem verflixten DoDeLiDo wird jetzt auch DoDo Didelidi gerufen. Wer bei dieser extremen Variante blitzschnell schaltet und die richtige Aussage macht, wird als erster alle seine Karten los.";
        //echo JHtmlString::truncateComplex($text,220,true);
        ?>

        <?php
        $subsets = json_decode($this->{$catType}['subsets'], true);
        if (!is_array($subsets)) {
            if(count($this->subsets['filters'])>1) {
                $subsets = $this->subsets;
            }
        }

        if (is_array($subsets)) {
            if(isset($subsets['style']) && $subsets['style']=='buttons'){?>
                <div class="uk-width-1-1 uk-margin-top lupo_buttons">
                    <div class="uk-button-group" data-uk-button-radio>
                        <button class="uk-button lupo_btn_subset uk-active" data-categories="*"  data-agecategories="*" data-genres="*"><?php echo JText::_('COM_LUPO_ALL'); ?></button>
                        <?php
                        foreach ($subsets['filters'] as $subset_desc => $subset) {
                            $data_categories = json_encode($subset['categories']);
                            $data_agecategories = json_encode($subset['agecategories']);
                            $data_genres        = json_encode($subset['genres']);
                            ?>
                            <button class="uk-button lupo_btn_subset" data-categories='<?= $data_categories ?>' data-agecategories='<?= $data_agecategories ?>' data-genres='<?= $data_genres ?>'><?= $subset_desc ?></button>
                        <?php } ?>
                    </div>
                </div>
            <?php } else {
                if($this->description!=""){ ?>
                    <br class="clearfix">
                    <br>
                <?php } ?>
                <?php echo JText::_('COM_LUPO_FILTER_LIST'); ?>
                <div class="uk-button-group uk-margin-small-left lupo_dropdown">
                    <div class="uk-button-dropdown uk-dropdown-close" data-uk-dropdown="{mode:'click'}">
                        <button class="uk-button" id="btn-dropdown-filter"><span><?php echo JText::_('COM_LUPO_ALL'); ?></span> <i class="uk-icon-caret-down"></i></button>
                        <div class="uk-dropdown uk-dropdown-small">
                            <ul class="uk-nav uk-nav-dropdown">
                                <li><a href="#" class="lupo_btn_subset" data-categories="*" data-agecategories="*" data-genres="*"><?php echo JText::_('COM_LUPO_ALL'); ?></a></li>
                                <?php
                                foreach ($subsets['filters'] as $subset_desc => $subset) {
                                    $data_categories = json_encode($subset['categories']);
                                    $data_agecategories = json_encode($subset['agecategories']);
                                    $data_genres        = json_encode($subset['genres']);
                                    ?>
                                    <li><a href="#" class="lupo_btn_subset" data-categories='<?= $data_categories ?>' data-agecategories='<?= $data_agecategories ?>' data-genres='<?= $data_genres ?>'><?= $subset_desc ?></a></li>
                                <?php } ?>
                            </ul>
                        </div>
                    </div>
                </div>
            <?php } ?>
        <?php } ?>

        <table class="uk-table uk-table-striped uk-table-condensed" id="lupo_category_table">
            <?php if ($componentParams->get('category_show_tableheader', '1')) { ?>
                <thead>
                <tr>
                    <th>
                        <?php echo JText::_('COM_LUPO_TOY'); ?>
                        <?php if ($componentParams->get('lupo_show_toystatus', '0')) { ?>
                            <span class="uk-float-right"><?php echo JText::_('COM_LUPO_BORROWING_STATUS'); ?></span>
                        <?php } ?>
                    </th>
                    <?php if($componentParams->get('category_show_toy_category', '1') && $catType!='category') { ?>
                        <th nowrap="nowrap" class="uk-hidden-small"><div align="right"><?php echo JText::_('COM_LUPO_CATEGORY'); ?></div></th>
                    <?php } ?>
                    <?php if ($componentParams->get('category_show_toy_age_category', '1') && $catType!='agecategory') { ?>
                        <th nowrap="nowrap" class="uk-hidden-small">
                            <div align="right"><?php echo JText::_('COM_LUPO_AGE_CATEGORY'); ?></div>
                        </th>
                    <?php } ?>
                    <?php if ($componentParams->get('category_show_tax', '1')) { ?>
                        <th nowrap="nowrap" class="uk-hidden-small">
                            <div align="right"><?php echo JText::_('COM_LUPO_TAX'); ?></div>
                        </th>
                    <?php } ?>
                    <?php if ($componentParams->get('category_show_toy_nbrdays', '1')) { ?>
                        <th nowrap="nowrap" class="uk-hidden-small">
                            <div align="right"><?php echo str_replace(" ", "<br>", JText::_('COM_LUPO_DAYS')); ?></div>
                        </th>
                    <?php } ?>
                </tr>
                </thead>
            <?php } ?>
            <tbody>
            <?php
            $i = 0;
            foreach ($this->games as $game) {
                if ($componentParams->get('lupo_show_toystatus', '0')) {
                    $availability = '<i class="uk-icon uk-icon-circle ' . $game['availability_color'] . ' uk-float-right availability_dot" title="' . $game['availability_text'] . '"></i>';
                } else {
                    $availability = '';
                }
                ?>
                <tr data-category='<?= $game['category_alias'] ?>' data-agecategory='<?= $game['agecategory_alias'] ?>' data-genres='<?= json_encode(explode(",", $game['genres'])) ?>'>
                    <td>
                        <?php if ($this->foto['show'] != '0') { ?>
                            <a class="category" href="<?php echo $game['link'] ?>"><?php
                                if ($game['image_thumb'] != null) {
                                    ?>
                                    <img class="uk-align-left" src="<?php echo $game['image_thumb'] ?>">
                                <?php } else { ?>
                                    <?php if ($componentParams->get('foto_list_show_placeholder', '1')) { ?>
                                        <img class="uk-align-left" src="images/spiele/<?php echo $this->foto['prefix'] ?>dice-gray.jpg">
                                    <?php } ?>
                                <?php } ?>
                            </a>
                        <?php } ?>
                        <?php echo $availability ?>
                        <?php if ($componentParams->get('category_show_detail_link', '1')) {
                            ?>
                            <p>
                                <a class="category" href="<?php echo $game['link'] ?>"><?php echo $game['title'] ?></a>
                            </p>
                        <?php } else { ?>
                            <?php echo $game['title'] ?>
                        <?php } ?>
                        <?php if ($componentParams->get('foto_list_show_description', '1')) { ?>
                            <p>
                                <?php
                                $desc = preg_replace("'<(br[^/>]*?/)>'si", ' ', $game['description_full']); //replace <br/> with space
                                echo JHtmlString::truncateComplex($desc, 220, true);
                                ?>
                            </p>
                        <?php } ?>
                    </td>
                    <?php if($componentParams->get('category_show_toy_category', '1') && $catType!='category') { ?>
                        <td align="right" class="uk-hidden-small"><?php echo $game['category']?></td>
                    <?php }  ?>
                    <?php if ($componentParams->get('category_show_toy_age_category', '1') && $catType!='agecategory') { ?>
                        <td align="right" class="uk-hidden-small"><?php echo $game['age_category'] ?></td>
                    <?php } ?>
                    <?php if ($componentParams->get('category_show_tax', '1')) { ?>
                        <td align="right" class="uk-hidden-small"><?php echo number_format($game['tax'], 2) ?></td>
                    <?php } ?>
                    <?php if ($componentParams->get('category_show_toy_nbrdays', '1')) { ?>
                        <td align="right" class="uk-hidden-small"><?php echo $game['days'] ?></td>
                    <?php } ?>
                </tr>
                <?php
            }
            ?>
            </tbody>
        </table>
    </div>
</article>
