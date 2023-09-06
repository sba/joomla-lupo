<?php
/**
 * @package     LUPO
 * @copyright   Copyright (C) databauer / Stefan Bauer
 * @author      Stefan Bauer
 * @link        https://www.ludothekprogramm.ch
 * @license     License GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;
?>
<style type="text/css">
    /* module mod_lupo_categories */
    .mod_lupo_categories ul {
        margin-left: 0;
    }

    /* for optional module-class suffix*/
    .lupo-icon-dice h3 {
        background: url("<?php echo JURI::base()?>/media/com_lupo/images/dice-16px.png") no-repeat 0 5px;
        padding-left: 20px;
    }
</style>

<div class="mod_lupo_categories <?php echo $moduleclass_sfx ?>">
    <?php echo JHtml::_('content.prepare', $module->content); ?>
    <ul class="uk-list uk-list-space">
        <?php
        $show_number       = $params->get('show_number', '1');
        $show_number_class = $params->get('show_number_class', 'uk-badge');

        $seperator = false;
        foreach ($list as $section => $items) {

            if ($params->get('show_' . $section) && $items != false) {
                if ($seperator) { ?>
                    <hr/><?php
                }
                $seperator = true;
                foreach ($items as $item) {
                    if ($item == '-') { ?>
                        <hr/>
                    <?php } else { ?>
                        <li><a href="<?php echo $item['link'] ?>"><?php echo $item['title'] ?></a>
                            <?php if ($show_number == "1") { ?>
                                <div class="<?php echo $show_number_class ?> uk-float-right"><?php echo $item['number'] ?></div>
                            <?php } ?>
                        </li>
                    <?php }
                }
            }
        }
        ?>
    </ul>
</div>
