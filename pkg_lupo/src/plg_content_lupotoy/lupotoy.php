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

		// regex matches: [spiel 9007.13;200.2] lorem ipsum [spiel table 9000] hallo welt [spiel 10003.3]
		// alter shortcode syntax, für legacy support: [spiel 9007.13;200.2], [spiel table 9000], [spiel tabelle 9000], [spiel tabella 9000], [spiel tableau 9000]
		$pattern = '/\[(spiel|spiele|jeu|jeux|gioco|giochi|toy|toys)(?:[\s\x{00A0}]|&nbsp;){0,4}(?:tabelle|table|tabella|tableau)?(?:[\s\x{00A0}]|&nbsp;){0,4}((?:[0-9;,.]|[\s\x{00A0}]|&nbsp;)+)\]/iu';
		preg_match_all($pattern, $article->text, $matches);
		if (count($matches[0]) > 0) {
			JHtml::_('stylesheet', JUri::root() . 'components/com_lupo/css/com_lupo.css', array('version' => 'auto'));

			$wa = JFactory::getApplication()->getDocument()->getWebAssetManager();
			$wa->registerAndUseScript('uikit', 'components/com_lupo/uikit/js/uikit.min.js', [], [], ['jquery']);
			$wa->registerAndUseScript('uikit.lightbox', 'components/com_lupo/uikit/js/components/lightbox.min.js', [], [], ['uikit']);

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
					$article->text = str_replace($match, $replacement, $article->text);
				}
			}
		}

		// new shortcode syntax: [lupo spiele="1234, 2000.2" layout="tabelle" link="true"]
		$new_pattern = '/\[lupo\s+([^\]]+)\]/iu';
		preg_match_all($new_pattern, $article->text, $new_matches);
		if (count($new_matches[0]) > 0) {
			JHtml::_('stylesheet', JUri::root() . 'components/com_lupo/css/com_lupo.css', array('version' => 'auto'));

			$wa = JFactory::getApplication()->getDocument()->getWebAssetManager();
			$wa->registerAndUseScript('uikit', 'components/com_lupo/uikit/js/uikit.min.js', [], [], ['jquery']);
			$wa->registerAndUseScript('uikit.lightbox', 'components/com_lupo/uikit/js/components/lightbox.min.js', [], [], ['uikit']);

			foreach ($new_matches[0] as $match_key => $match) {
				$attr_string = $new_matches[1][$match_key];
				$attrs = [];
				preg_match_all('/(\w+)\s*=\s*["\']([^"\']+)["\']/u', $attr_string, $attr_matches);
				if (count($attr_matches[0]) > 0) {
					foreach ($attr_matches[1] as $k => $key) {
						$attrs[$key] = $attr_matches[2][$k];
					}
				}

				if (!isset($attrs['spiele'])) {
					if (!isset($attrs['spiel'])) {
						continue;
					} else {
						$attrs['spiele'] = $attrs['spiel'];
					}
				}

				$toy_numbers = $attrs['spiele'];
				$toy_numbers = str_replace([',', "\xC2\xA0", '&nbsp;', ' '], ';', $toy_numbers);

				$model = new LupoModelLupo();
				$toys  = $model->getGamesByNumber($toy_numbers); //relates games werden nicht geladen, ist aber ok, wird glaub nicht benötigt
				if ($toys !== false) {
					$replacement = '';
					$layout      = isset($attrs['layout']) ? $attrs['layout'] : 'image';
					$is_table    = (in_array($layout, ['tabelle', 'table', 'tabella', 'tableau']));
					$is_full     = ($layout === 'full');
					$nolink      = isset($attrs['nolink']) && $attrs['nolink'];
					$columns     = isset($attrs['columns']) ? (int) $attrs['columns'] : 2;

					if (!$is_table && !$is_full) {
						$replacement .= '<div class="uk-grid" data-uk-grid-margin>';
					}

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
						} elseif ($is_full) {
							// Render full game view
							$view_path = JPATH_BASE . '/components/com_lupo/views/game/tmpl/default.php';
							if (file_exists($view_path)) {
								$renderer = new class($toy) {
									public $game;
									public function __construct($game) { $this->game = $game; }
									public function render($__path) { ob_start(); include $__path; return ob_get_clean(); }
								};
								$replacement .= $renderer->render($view_path);
								$replacement .= '<br><br>';
							}
						} else {
							$width_class = 'uk-width-medium-1-' . $columns;
							$replacement .= '<div class="' . $width_class . '">';
							if ($nolink) {
								$replacement .= '<div><img src="' . $toy['image'] . '" title="' . $toy['title'] . '"></div>';
							} else {
								$replacement .= '<div><a href="' . $toy['link'] . '"><img src="' . $toy['image'] . '" title="' . $toy['title'] . '"></a></div>';
							}
							$replacement .= '</div>';
						}
					}

					if (!$is_table && !$is_full) {
						$replacement .= '</div>';
					}
					$article->text = str_replace($match, $replacement, $article->text);
				}
			}
		}

		return true;
	}
}
