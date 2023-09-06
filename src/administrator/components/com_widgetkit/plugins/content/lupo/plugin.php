<?php

$config = array(

    'name' => 'content/lupo',

    'main' => 'YOOtheme\\Widgetkit\\Content\\Type',

    'config' => array(

        'name' => 'lupo',
        'label' => 'LUPO',
        'icon' => 'plugins/content/lupo/content.svg',
        'item' => array('title', 'content', 'link', 'media'),
        'data' => array(
            'number' => 999,
            'sort' => 'alpha',
            'category' => '0',
            'agecategory' => '0',
            'genre' => '0',
            'content' => 'description_title-text',
            'title' => 'title',
            'badge' => '',
            'tags' => '',
            'limitchars' => '',
            'contenttable' => '',
            'imagesonly' => false,
        )

    ),

    'items' => function ($items, $content, $app) {


        if (!class_exists('LupoModelLupo')) {
            JLoader::import('lupo', JPATH_ROOT . '/components/com_lupo/models');
        }

        $args = array(
            'filter' => $content['filter'],
            'items' => $content['number'] ?: 999,
            'sort' => $content['sort'] ?: 'alpha',
            'category' => $content['category'] ?: 0,
            'agecategory' => $content['agecategory'] ?: 0,
            'genre' => $content['genre'] ?: 0,
            'game' => $content['game'] ?: 0
        );

        $lupoModel = new LupoModelLupo();

        //TODO: Implement sort and limit-function to game-getters to optimize performance!!
        switch ($args['filter']) {
            case 'category':
                $games = $lupoModel->getGamesByCategory($args['category']);
                break;
            case 'agecategory':
                $games = $lupoModel->getGamesByAgeCategory($args['agecategory']);
                break;
            case 'genre':
                $games = $lupoModel->getGamesByGenre($args['genre']);
                break;
            case 'game':
                $games = $lupoModel->getGamesByNumber($args['game']);
                break;
        }

        if (in_array($args['filter'], array('category', 'agecategory', 'genre'))) {
            switch ($args['sort']) {
                case 'random':
                    shuffle($games);
                    break;
                case 'date':
                    usort($games, function ($a, $b) {
                        return strtotime($b['acquired_date']) - strtotime($a['acquired_date']);
                    });
                    break;
            }
        }

        $image_prefix = $content['media'];
        $image_prefix = $image_prefix == 'thumb_' ? "" : $image_prefix; // fix for BC

        //remove items without image before limit rows
        foreach ($games as $key => $item) {
            if (isset($content['imagesonly']) && $content['imagesonly'] == true) {
                $photo = $lupoModel->getGameFoto($item['number'], $image_prefix);
                if ($photo['image'] == null) {
                    unset($games[$key]);
                }
            }
        }

        $games = array_slice($games, 0, $args['items']); //limit rows

        foreach ($games as $key => $item) {
            $photo = $lupoModel->getGameFoto($item['number'], $image_prefix);

            if ($photo['image'] == null) {
                $photo['image'] = '/images/spiele/dice-gray.jpg';
            }
            if ($image_prefix == "") {
                $item['image'] = $photo['image'];
            } else {
                $item['image'] = $photo['image_thumb'];
            }

            switch ($content['title']) {
                case 'description_title':
                    $item['title'] = $item['description_title'];
                    break;
                case 'title':
                default:
                    break;
            }


            switch ($content['content']) {
                case 'description_title':
                    $item['content'] = $item['description_title'];
                    break;
                case 'description_text':
                    $item['content'] = $item['description'];
                    break;
                case 'keywords':
                    $item['content'] = $item['keywords'];
                    break;
                case 'notext':
                    $item['content'] = '';
                    break;
                case 'description_title-text':
                default:
                    $item['content'] = $item['description_full'];
                    break;
            }
            if ($content['limitchars'] !== "") {
                $item['content'] = JHtmlString::truncateComplex($item['content'], $content['limitchars'], true);
            }


            $lang = JFactory::getLanguage();
            $lang->load('com_lupo');

            if (isset($content['contenttable']) && $content['contenttable'] !== "") {
                $contenttable = array_map('trim', explode(";", $content['contenttable']));
                $infos = array();
                $hascaption = false;
                foreach ($contenttable as $contentrow) {
                    if (strpos($contentrow, "|") === false) {
                        $caption = JText::_('COM_LUPO_' . strtoupper($contentrow));
                        $hascaption = true;
                        $fieldname = $contentrow;
                    } else {
                        list($fieldname, $caption) = explode('|', $contentrow);
                        $hascaption = $caption != '' || $hascaption;
                    }
                    if (array_key_exists($fieldname, $item) && $item[$fieldname] != "") {
                        $infos[] = array('caption' => $caption, 'value' => $item[$fieldname]);
                    }
                }
                if (count($infos) > 0) {
                    $item['content'] .= '<table class="uk-table uk-table-striped uk-table-condensed" id="lupo_detail_table">';
                    foreach ($infos as $inforow) {
                        $item['content'] .= "<tr>" . ($hascaption ? "<td style='white-space: nowrap; width: 1%'>{$inforow['caption']}</td>" : '') . "<td>{$inforow['value']}</td></tr>";
                    }
                    $item['content'] .= '</table>';
                }
            }


            switch ($content['badge']) {
                case 'number':
                    $item['badge'] = $item['number'];
                    break;
                case 'title':
                    $item['badge'] = $item['title'];
                    break;
                case 'category':
                    $item['badge'] = $item['category'];
                    break;
                case 'age_category':
                    $item['badge'] = $item['age_category'];
                    break;
                case 'tax':
                    $item['badge'] = 'Fr. ' . number_format($item['tax'], 2);
                    break;
                case 'players':
                    $item['badge'] = $item['players'];
                    break;
                case 'play_duration':
                    $item['badge'] = $item['play_duration'];
                    break;
                case 'userdefined':
                    $item['badge'] = $item['userdefined'];
                    break;
                default:
                    $item['badge'] = "";
                    break;
            }


            switch ($content['tags']) {
                case 'categories':
                    $item['tags'] = array($item['category']);
                    break;
                case 'agecategories':
                    $item['tags'] = array($item['age_category']);
                    break;
                case 'genres':
                    $item['tags'] = @explode(", ", $item['genres']);
                    break;
                case 'fabricator':
                    $item['tags'] = array($item['fabricator']);
                    break;
                case 'userdefined':
                    $item['tags'] = array($item['userdefined']);
                    break;
                default:
                    $item['tags'] = array();
                    break;
            }

            //remove empty items
            if ($content['tags'] != "" && $item['tags'][0] == "") {
                $item['tags'] = array();
            }


            $data = array(
                'title' => $item['title'],
                'media' => $item['image'],
                'content' => $item['content'],
                'link' => $item['link'],
                'tags' => $item['tags'],
                'badge' => $item['badge']
            );

            $items->add($data);
        }

    },

    'events' => array(

        'init.admin' => function ($event, $app) {
            $app['angular']->addTemplate('lupo.edit', 'plugins/content/lupo/views/edit.php');
        }

    )

);

return defined('_JEXEC') ? $config : false;
