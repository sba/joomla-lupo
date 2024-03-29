<?php
/**
* @package   Warp Theme Framework
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

// no direct access
defined('_JEXEC') or die;

?><div id="searchresult"><?php

foreach ($this->results as $result) {

	$args = array(
        'permalink' => '',
        'image' => $result->image,
        'image_alignment' => 'left',
        'image_alt' => '',
        'image_caption' => '',
        'title' => $result->title,
        'title_link' => '1',
        'author' => '',
        'author_url' => '',
        'date' => '',
        'datetime' => '',
        'category' => ($result->section) ? $this->escape($result->section) : '',
        'category_url' => '',
        'hook_aftertitle' => '',
        'hook_beforearticle' => '',
        'hook_afterarticle' => '',
        'article' => $result->text,
        'tags' => '',
        'edit' => '',
        'url' => ($result->href) ? JRoute::_($result->href) : '',
        'more' => '',
        'previous' => '',
        'next' => ''
    );

	// Render template
	echo $warp['template']->render('article', $args);

}

?></div><?php

echo $this->pagination->getPagesLinks();