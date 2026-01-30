<?php
/**
 * @package     LUPO
 * @copyright   Copyright (C) databauer / Stefan Bauer
 * @author      Stefan Bauer
 * @link        https://www.ludothekprogramm.ch
 * @license     License GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

class plgContentLupotoy extends JPlugin {
	public function onContentPrepare($context, &$article, &$params, $limitstart = 0) {
		$lang = JFactory::getLanguage();
		$lang->load('com_lupo', JPATH_SITE);

		if (!class_exists('LupoModelLupo')) {
			JLoader::import('lupo', JPATH_BASE . '/components/com_lupo/models');
		}

		//regex matches: [spiel 9007.13;200.2] lorem ipsum [spiel table 9000] hallo welt [spiel 10003.3]
		$pattern = '/\[(spiel|spiele|jeu|jeux|gioco|giochi|toy|toys)(?:[\s\x{00A0}]|&nbsp;){0,4}(?:tabelle|table|tabella|tableau)?(?:[\s\x{00A0}]|&nbsp;){0,4}((?:[0-9;,.]|[\s\x{00A0}]|&nbsp;)+)\]/iu';
		preg_match_all($pattern, $article->text, $matches);
		if (count($matches[0]) > 0) {
			JHtml::_('stylesheet', JUri::root() . 'components/com_lupo/css/com_lupo.css', array('version' => 'auto'));

			foreach ($matches[0] as $match_key => $match) {
				$toy_numbers = $matches[2][$match_key];
				$toy_numbers = str_replace([',', ' ', "\xC2\xA0", '&nbsp;'], ';', $toy_numbers);

				$model = new LupoModelLupo();
				$toys  = $model->getGamesByNumber($toy_numbers);
				if ($toys !== false) {
					$replacement = '';
					$is_table = preg_match('/tabelle|table|tabella|tableau/iu', $match);

					foreach ($toys as $toy) {
						if ($is_table) {
							$componentParams = JComponentHelper::getParams('com_lupo');
							$show_without_age = (int) $componentParams->get('show_without_age', '1');
							$show_diverse     = (int) $componentParams->get('show_diverse', '1');

							$replacement .= '<h3><a href="' . $toy['link'] . '">' . $toy['title'] . ' ' . $toy['edition'] . '</a></h3>';
							$replacement .= JLayoutHelper::render('toy_detail_table', [
								'game' => $toy,
								'componentParams' => $componentParams,
								'show_diverse' => $show_diverse,
								'show_without_age' => $show_without_age
							], JPATH_BASE . '/components/com_lupo/tmpl');
						} else {
							$replacement .= '<div class="lupo_content_image"><a href="' . $toy['link'] . '"><img src="' . $toy['image'] . '" title="' . $toy['title'] . '"></a></div>';
						}
					}
					$article->text = str_replace($matches[0][$match_key],$replacement, $article->text);
				}
			}
		}

		return true;
	}
}
