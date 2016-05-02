<?php
/**
 * @package		Joomla
 * @subpackage	LUPO
 * @copyright	Copyright (C) databauer / Stefan Bauer
 * @author		Stefan Bauer
 * @link		http://www.ludothekprogramm.ch
 * @license		License GNU General Public License version 2 or later
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

//load lupo styles
JHTML::stylesheet('com_lupo.css', 'components/com_lupo/css/');
$componentParams = JComponentHelper::getParams('com_lupo');

?>
<article class="tm-article">
    <div class="tm-article-content">
        <h2 class="contentheading"><?php echo $this->category['title']?></h2>

        <table class="uk-table uk-table-striped uk-table-condensed" id="lupo_category_table">
            <?php if($componentParams->get('category_show_tableheader', '1')) { ?>
                <thead>
                <tr>
                    <th><?php echo JText::_('COM_LUPO_TOY'); ?></th>
                    <?php /* if($componentParams->get('category_show_toy_category', '1')) { ?>
                        <th nowrap="nowrap"><div align="right"><?php echo JText::_('COM_LUPO_CATEGORY'); ?></div></th>
                    <?php } */ ?>
                    <?php if($componentParams->get('category_show_toy_age_category', '1')) { ?>
                        <th nowrap="nowrap"><div align="right"><?php echo JText::_('COM_LUPO_AGE_CATEGORY'); ?></div></th>
                    <?php } ?>
                    <?php if($componentParams->get('category_show_tax', '1')) { ?>
                        <th nowrap="nowrap"><div align="right"><?php echo JText::_('COM_LUPO_TAX'); ?></div></th>
                    <?php } ?>
                    <?php if($componentParams->get('category_show_toy_nbrdays', '1')) { ?>
                        <th nowrap="nowrap"><div align="right"><?php echo JText::_('COM_LUPO_DAYS'); ?></div></th>
                    <?php } ?>
                </tr>
                </thead>
            <?php } ?>
            <tbody>
            <?php
            $i=0;
            foreach($this->games as $game){
                if($game['return_date']==null) {
                    $availability = '<i class="uk-icon uk-icon-circle green uk-float-right availability_dot" title="'.JText::_("COM_LUPO_AVAILABLE").'"></i>';
                } else {
                    $availability = '<i class="uk-icon uk-icon-circle red uk-float-right availability_dot" title="'.JText::_("COM_LUPO_BORROWED").'"></i>';
                }
                ?>
                <tr>
                    <td>
                        <?php if($componentParams->get('category_show_detail_link', '1')) {
                            $nbr_games = ($game['nbr']>1 && $componentParams->get('category_nbr_games', '1'))?'('.$game['nbr'].')':'';
                            ?>
                            <a class="category" href="<?php echo $game['link']?>"><?php echo $game['title']?> <?php echo $nbr_games?></a>
                            <br class="uk-visible-small" />
                        <?php } else { ?>
                            <?php echo $game['title']?> <?php echo $game['nbr']>1?' ('.$game['nbr'].')':''?>
                        <?php } ?>
                        <?php echo $availability ?>
                        <?php if($this->foto['show']!='0') {?>
                            <a class="category" href="<?php echo $game['link']?>"><?php
                                if ($game['image_thumb'] != NULL) {
                                    ?>
                                    <img class="uk-align-left" src="<?php echo $game['image_thumb']?>">
                                <?php } else { ?>
                                    <img class="uk-align-left" src="images/spiele/<?php echo $this->foto['prefix']?>dice-gray.jpg">
                                <?php }?>
                            </a>
                            <br><?php
                            $desc = preg_replace("'<(br[^/>]*?/)>'si", ' ', $game['description_full']); //replace <br/> with space
                            echo JHtmlString::truncateComplex($desc,220,true);
                            ?>
                        <?php }?>
                    </td>
                    <?php /* if($componentParams->get('category_show_toy_category', '1')) { ?>
                        <td align="right"><?php echo $game['category']?></td>
                    <?php } */ ?>
                    <?php if($componentParams->get('category_show_toy_age_category', '1')) { ?>
                        <td align="right"><?php echo $game['age_category']?></td>
                    <?php } ?>
                    <?php if($componentParams->get('category_show_tax', '1')) { ?>
                        <td align="right"><?php echo number_format($game['tax'],2)?></td>
                    <?php } ?>
                    <?php if($componentParams->get('category_show_toy_nbrdays', '1')) { ?>
                        <td align="right"><?php echo $game['days']?></td>
                    <?php } ?>
                </tr>
                <?php
            }
            ?>
            </tbody>
        </table>
    </div>
</article>
