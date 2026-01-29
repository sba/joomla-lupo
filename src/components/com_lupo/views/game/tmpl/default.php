<?php
/**
 * @package     LUPO
 * @copyright   Copyright (C) databauer / Stefan Bauer
 * @author      Stefan Bauer
 * @link        https://www.ludothekprogramm.ch
 * @license     License GNU General Public License version 2 or later
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

$componentParams = JComponentHelper::getParams('com_lupo');
?>


<article class="tm-article">
    <div class="tm-article-content">
        <?php
        if ($this->game == 'error') {
            ?><h2 class="contentheading"><?php echo JText::_("COM_LUPO_ERROR_NOT_FOUND") ?></h2><?php
        } else {
        //description-text
        $description_title = $this->game['description_title'];
        if ($description_title != "") {
            $description_title = '<p><b>' . $description_title . '</b></p>';
        }
        $description = $description_title . $this->game['description'];

        //for navigation
        $pos          = isset($_GET['pos']) ? $_GET['pos'] : 0;
        $session      = JFactory::getSession();
        $session_lupo = $session->get('lupo');


        //navigation
        if ($componentParams->get('detail_show_toy_nav', '1') && $session_lupo != null && isset($_GET['pos'])){
        $style    = $pos + 1 < count($session_lupo) ? '' : 'visibility: hidden';
        $nav_game = isset($session_lupo[$pos + 1]) ? $session_lupo[$pos + 1] : ['id' => null]; ?>
            <div style="<?= $style ?>; right: 0" class="uk-h3 uk-position-absolute"><a
                        href="<?php echo JRoute::_('index.php?option=com_lupo&view=game&id=' . $nav_game['id'] . '&pos=' . ($pos + 1)) ?>"
                        title="<?php echo JText::_('COM_LUPO_NAV_NEXT_GAME'); ?>"
                        class="uk-icon-hover uk-icon-chevron-right"></a></div>
            <?php
            $style    = $pos >= 1 ? '' : 'visibility: hidden;';
            $nav_game = isset($session_lupo[$pos - 1]) ? $session_lupo[$pos - 1] : ['id' => null]; ?>
            <div style="<?= $style ?> right: 15px" class="uk-h3 uk-position-absolute"><a
                        href="<?php echo JRoute::_('index.php?option=com_lupo&view=game&id=' . $nav_game['id'] . '&pos=' . ($pos - 1)) ?>"
                        title="<?php echo JText::_('COM_LUPO_NAV_PREV_GAME'); ?>"
                        class="uk-icon-hover uk-icon-chevron-left">&nbsp;&nbsp;</a></div>
        <?php } ?>

            <h2 class="contentheading" style="padding-right: 30px;"><?php echo $this->game['title'] . ' ' . $this->game['edition'] ?></h2>

        <?php
        $show_without_age = (int) $componentParams->get('show_without_age', '1');
        $show_diverse     = (int) $componentParams->get('show_diverse', '1');

        if ($componentParams->get('detail_show_toy_photo', '1') || $description != ""){
        if ($componentParams->get('detail_show_toy_photo', '1') && $this->game['image_thumb'] != null && $description != "") {
            $grid_width = "2";
        } else {
            $grid_width = "1";
        }
        ?>
            <div class="uk-grid">
                <?php
                if ($description != "") { ?>
                    <div class="uk-width-1-1 uk-width-small-1-<?php echo $grid_width ?> uk-margin-bottom">
                        <div class="lupo_description"><?php echo $description; ?></div>
                    </div>
                <?php }
                if ($componentParams->get('detail_show_toy_photo', '1') && $this->game['image'] != null) { ?>
                    <div class="uk-width-1-1 uk-width-small-1-<?php echo $grid_width ?> uk-margin-bottom">
                        <?php
                        if ($this->game['image'] == null) {
                            ?><img class="lupo_image" src="<?php echo $this->game['image_thumb'] ?>"><?php
                        } else {
                            ?>
                            <?php if ($componentParams->get('detail_photo_lightbox', '1')) { ?>
                                <a href="<?php echo substr($this->game['image'], 0, strpos($this->game['image'], '?v=')); ?>" data-uk-lightbox
                                   title="<?php echo htmlspecialchars($this->game['title'] . ' ' . $this->game['edition']) ?>"><img
                                            class="lupo_image"
                                            alt="<?php echo JText::_("COM_LUPO_TOY") . ' ' . $this->game['number'] ?>"
                                            src="<?php echo $this->game['image_thumb'] ?>"/></a>
                            <?php } else { ?>
                                <img class="lupo_image"
                                     alt="<?php echo JText::_("COM_LUPO_TOY") . ' ' . $this->game['number'] ?>"
                                     src="<?php echo $this->game['image_thumb'] ?>"/>
                            <?php } ?>
                            <div id="img-toy" class="uk-modal">
                                <div>
                                    <img src="<?php echo $this->game['image'] ?>"
                                         alt="<?php echo JText::_("COM_LUPO_TOY") . ' ' . $this->game['number'] ?>"/>
                                </div>
                            </div>
                            <?php
                        } ?>
                    </div>
                    <?php
                } ?>
            </div>
        <?php
        } ?>


        <?php
        if ($componentParams->get('detail_show_toy_res', '0')) {
        $session                      = JFactory::getSession();
        $client                       = $session->get('lupo_client');
        $show_only_to_logged_in       = $componentParams->get('detail_show_toy_res_only_logged_in', '0');
        $detail_res_allow_only_loaned = $componentParams->get('detail_res_allow_only_loaned', '0');
        $show_res_because_loaned      = $this->game['return_date'] !== null || $detail_res_allow_only_loaned == 0;

        if ((!$show_only_to_logged_in || ($show_only_to_logged_in && $client)) && $show_res_because_loaned) {
        ?>
        <?php if ($this->game['in_cart']) { ?>
            <a class="uk-button uk-button-success uk-margin-right uk-margin-bottom" id="btnresadd"><i
                        class="uk-icon-check"></i> <?php echo JText::_("COM_LUPO_RES_ADDED"); ?></a>
        <?php } else { ?>
            <a class="uk-button uk-button-primary uk-margin-right uk-margin-bottom" id="btnresadd"><i
                        class="uk-icon-cart-plus"></i> <?php echo JText::_("COM_LUPO_RES_TOY"); ?></a>
        <?php } ?>
            <script type="text/javascript">
                jQuery(document).ready(function ($) {
                    $('#btnresadd').on('click', function () {
                        $.ajax({
                            method: "POST",
                            url: "/index.php?option=com_lupo&task=resadd&format=raw",
                            data: {
                                toynr: "<?php echo $this->game['number']?>",
                                toyname: "<?php echo htmlspecialchars($this->game['title'])?>"
                            }
                        })
                            .done(function (response) {
                                response = JSON.parse(response)
                                if (response.msg === 'ok') {
                                    $('.lupo_loginlink_reservations').removeClass('uk-hidden');
                                    $('#lupo_loginlink_reservations_nbr').html(response.reservations_nbr);
                                    $('#btnresadd')
                                        .html('<i class="uk-icon-check"></i> <?php echo JText::_("COM_LUPO_RES_ADDED"); ?>')
                                        .addClass('uk-button-success')
                                        .attr('href', $('#lupo_loginlink a').attr("href"))
                                } else {
                                    $('#btnresadd').after('<div class="uk-alert uk-alert-danger">ERROR</div>');
                                }
                            });
                    })
                });
            </script>
        <?php } ?>
        <?php } ?>


        <?php
        //document links
        if ($componentParams->get('detail_show_toy_documents', '1')) {
        foreach ($this->game['documents'] as $document) {
        if ($document['code'] != 'userdefined'){ ?>
            <a class="uk-button uk-margin-right uk-margin-bottom"
               href="<?php echo $document['href'] ?>" <?php echo $document['lightbox'] ?>><i
                        class="uk-icon-<?php echo $document['icon'] ?>"></i> <?php echo $document['desc'] ?></a>
        <?php
        }
        }
        }
        ?>

        <?php
        echo JLayoutHelper::render('toy_detail_table', [
                'game'             => $this->game,
                'componentParams'  => $componentParams,
                'show_diverse'     => $show_diverse,
                'show_without_age' => $show_without_age
        ], JPATH_COMPONENT . '/tmpl');
        ?>

            <?php

            //related games
            if ($componentParams->get('detail_show_toy_related', '1')) {
                if (count($this->game['related']) > 0) {
                    ?><p class="uk-margin-large-top"><?php echo JText::_("COM_LUPO_RELATED_TOYS"); ?></p><?php
                    if ($componentParams->get('detail_show_toy_photo', '1') == 0) { ?>
                        <ul><?php
                            foreach ($this->game['related'] as $related) {
                                ?>
                                <li>
                                    <a href="<?php echo $related['link'] ?>"><?php echo $related['title'] ?><?php echo $related['edition'] ?></a>
                                </li>
                            <?php } ?>
                        </ul>
                    <?php } else {
                        $infinite = count($this->game['related']) < 6 ? '{infinite: false}' : ''; ?>
                        <style>
                            .uk-overlay:hover img {
                                transform: none;
                            }
                        </style>
                        <div class="uk-slidenav-position" data-uk-slider="<?= $infinite ?>">
                            <div class="uk-slider-container">
                                <ul class="uk-slider uk-grid uk-grid-width-1-3 uk-grid-width-small-1-4 uk-grid-width-medium-1-4 uk-grid-width-large-1-5"><?php
                                    foreach ($this->game['related'] as $related) {
                                        if ($related['image_thumb'] != null) {
                                            $image = $related['image_thumb'];
                                        } else {
                                            $image = 'images/spiele/mini_dice-gray.jpg';
                                        }
                                        ?>
                                        <li>
                                            <a href="<?php echo $related['link'] ?>">
                                                <figure class="uk-overlay uk-overlay-hover">
                                                    <img src="<?php echo $image; ?>"/>
                                                    <figcaption
                                                            class="uk-overlay-panel uk-overlay-background uk-flex uk-flex-center uk-flex-middle uk-text-center"><?php echo $related['title'] . ' ' . $related['edition'] ?></figcaption>
                                                </figure>
                                            </a>
                                        </li>
                                    <?php } ?>
                                </ul>
                            </div>

                            <?php if (count($this->game['related']) > 6) { ?>
                                <a href="" class="uk-slidenav uk-slidenav-contrast uk-slidenav-previous"
                                   data-uk-slider-item="previous"></a>
                                <a href="" class="uk-slidenav uk-slidenav-contrast uk-slidenav-next"
                                   data-uk-slider-item="next"></a>
                            <?php } ?>
                        </div>
                        <?php
                    }
                }
            }
        }  // endif get_game=error?>
    </div>
</article>