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
		if (!class_exists('LupoModelLupo')) {
			JLoader::import('lupo', JPATH_BASE . '/components/com_lupo/models');
		}

		//regex matches: [spiel 9007.13;200.2] lorem ipsum [spiel 9000] hallo welt [spiel 10003.3]
		$pattern = '/(\[spiel|spiele|jeu|jeux|gioco|giochi)[\s]{0,4}([0-9;,. ]+)(\])/i';
		preg_match_all($pattern, $article->text, $matches);

		if (count($matches[0]) > 0) {
			$toy_numbers = $matches[2][0];
			$toy_numbers = str_replace([',', ' '], [';', ''], $toy_numbers); // support comma as delimiter, ignore space

			$model = new LupoModelLupo();
			$toys  = $model->getGamesByNumber($toy_numbers);
			if ($toys !== false) {
				$replacement = '';
				foreach ($toys as $toy) {
					$replacement .= '<a href="' . $toy['link'] . '"><img src="' . $toy['image'] . '" title="' . $toy['title'] . '" style="max-width: 280px; margin: 0 20px 20px 0" ></a>';
				}
				$pattern       = '/(\[spiel|spiele|jeu|jeux|gioco|giochi)[\s]([0-9;,. ]+)(\])/i';
				$article->text = preg_replace($pattern, $replacement, $article->text);
				//$article->text .= '<pre>'. print_r($toy[0],true).'</pre>';
			}
		}

		return true;
	}
}
