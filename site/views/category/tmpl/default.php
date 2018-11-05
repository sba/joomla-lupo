<?php
/**
 * @package		Joomla
 * @subpackage	LUPO
 * @copyright   Copyright (C) databauer / Stefan Bauer 
 * @author		Stefan Bauer
 * @link		http://www.ludothekprogramm.ch
 * @license		License GNU General Public License version 2 or later
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

$componentParams = JComponentHelper::getParams('com_lupo');

if($this->foto['show']=='1') {?>
    <style>
        #lupo_category_table tr td > p {
            padding-left: 115px
        }
    </style>
<?php } ?>

<article class="tm-article">
    <div class="tm-article-content">
        <h2 class="contentheading"><?php echo $this->title?></h2>

        <table class="uk-table uk-table-striped uk-table-condensed" id="lupo_category_table">
            <?php if($componentParams->get('category_show_tableheader', '1')) { ?>
                <thead>
                <tr>
                    <th>
                        <?php echo JText::_('COM_LUPO_TOY'); ?>
                        <?php if($componentParams->get('lupo_show_toystatus', '0')) {?>
                        <span class="uk-float-right">Ausleihstatus</span>
                        <?php } ?>
                    </th>
                    <?php /* if($componentParams->get('category_show_toy_category', '1')) { ?>
                        <th nowrap="nowrap" class="uk-hidden-small"><div align="right"><?php echo JText::_('COM_LUPO_CATEGORY'); ?></div></th>
                    <?php } */ ?>
                    <?php if($componentParams->get('category_show_toy_age_category', '1')) { ?>
                        <th nowrap="nowrap" class="uk-hidden-small"><div align="right"><?php echo JText::_('COM_LUPO_AGE_CATEGORY'); ?></div></th>
                    <?php } ?>
                    <?php if($componentParams->get('category_show_tax', '1')) { ?>
                        <th nowrap="nowrap" class="uk-hidden-small"><div align="right"><?php echo JText::_('COM_LUPO_TAX'); ?></div></th>
                    <?php } ?>
                    <?php if($componentParams->get('category_show_toy_nbrdays', '1')) { ?>
                        <th nowrap="nowrap" class="uk-hidden-small"><div align="right"><?php echo str_replace(" ", "<br>", JText::_('COM_LUPO_DAYS')); ?></div></th>
                    <?php } ?>
                </tr>
                </thead>
            <?php } ?>
            <tbody>
            <?php
            $i=0;
            foreach($this->games as $game){
                if($componentParams->get('lupo_show_toystatus', '0')) {
	                if ($game['return_date'] != null) {
		                $availability = '<i class="uk-icon uk-icon-circle red uk-float-right availability_dot" title="' . JText::_("COM_LUPO_BORROWED") . '"></i>';
	                } elseif ($game['next_reservation'] != null) {
		                $availability = '<i class="uk-icon uk-icon-circle orange uk-float-right availability_dot" title="' . JText::_("COM_LUPO_RESERVED") . '"></i>';
	                } else {
		                $availability = '<i class="uk-icon uk-icon-circle green uk-float-right availability_dot" title="' . JText::_("COM_LUPO_AVAILABLE") . '"></i>';
	                }
                } else {
                    $availability = '';
                }
                ?>
                <tr>
                    <td>
	                    <?php if($this->foto['show']!='0') {?>
                            <a class="category" href="<?php echo $game['link']?>"><?php
			                    if ($game['image_thumb'] != NULL) {
				                    ?>
                                    <img class="uk-align-left" src="<?php echo $game['image_thumb']?>">
			                    <?php } else { ?>
                                    <?php if($componentParams->get('foto_list_show_placeholder', '1')) { ?>
                                        <img class="uk-align-left" src="images/spiele/<?php echo $this->foto['prefix']?>dice-gray.jpg">
                                    <?php } ?>
			                    <?php }?>
                            </a>
	                    <?php }?>
	                    <?php echo $availability ?>
                        <?php if($componentParams->get('category_show_detail_link', '1')) {
                            ?>
                            <p>
                                <a class="category" href="<?php echo $game['link']?>"><?php echo $game['title']?></a>
                            </p>
                        <?php } else { ?>
                            <?php echo $game['title']?>
                        <?php } ?>
                        <?php if($componentParams->get('foto_list_show_description', '1')) { ?>
                        <p>
		                    <?php
		                    $desc = preg_replace("'<(br[^/>]*?/)>'si", ' ', $game['description_full']); //replace <br/> with space
		                    echo JHtmlString::truncateComplex($desc,220,true);
		                    ?>
                        </p>
                        <?php } ?>
                    </td>
                    <?php /* if($componentParams->get('category_show_toy_category', '1')) { ?>
                        <td align="right" class="uk-hidden-small"><?php echo $game['category']?></td>
                    <?php } */ ?>
                    <?php if($componentParams->get('category_show_toy_age_category', '1')) { ?>
                        <td align="right" class="uk-hidden-small"><?php echo $game['age_category']?></td>
                    <?php } ?>
                    <?php if($componentParams->get('category_show_tax', '1')) { ?>
                        <td align="right" class="uk-hidden-small"><?php echo number_format($game['tax'],2)?></td>
                    <?php } ?>
                    <?php if($componentParams->get('category_show_toy_nbrdays', '1')) { ?>
                        <td align="right" class="uk-hidden-small"><?php echo $game['days']?></td>
                    <?php } ?>
                </tr>
                <?php
            }
            ?>
            </tbody>
        </table>
    </div>
</article>
