<?php
/**
 * @package   Warp Theme Framework
 * @author    YOOtheme http://www.yootheme.com
 * @copyright Copyright (C) YOOtheme GmbH
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

// no direct access
defined('_JEXEC') or die;

?>


<?php if (!$params->get('intro_only')) echo $item->afterDisplayTitle; ?>

<?php echo $item->beforeDisplayContent; ?>
<?php
$date = $item->created;
$images = json_decode($item->images);
$image_intro = $images->image_intro;
$image_intro_caption = $images->image_intro_caption;
$image_intro_alt = $images->image_intro_alt;

$introtext = $item->introtext;
$introtext = $introtext==""?'<p>&nbsp;</p>':$introtext;
?>
	<article class="tm-article">
		<div class="tm-article-content tm-article-date-true">
			<div class="tm-article-date">
				<?php $date = printf('<span class="tm-article-date-day">'.JHtml::_('date', $date, JText::_('d M')).'</span>'.'<span class="tm-article-date-year">'.JHtml::_('date', $date, JText::_('Y')).'</span>'); ?>
			</div>

			<h3><a href="<?php echo $item->link; ?>"><?php echo $item->title;?></a></h3>
			<?php echo $introtext; ?>
        </div>
	</article>
