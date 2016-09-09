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

//load lupo styles
JHTML::stylesheet('com_lupo.css', 'components/com_lupo/css/');
$componentParams = JComponentHelper::getParams('com_lupo');
$menu =JSite::getMenu()->getActive();
?>

<article class="tm-article">
	<div class="tm-article-content ">

		<?php if ($menu->params->get('show_page_heading', 1)) : ?>
			<h2 class="contentheading"><?php echo $menu->params->get('page_heading')?></h2>
		<?php endif; ?>

		<?php if($componentParams->get('cats_show_table_with_details', 0)=='0'){ ?>

		<ul class="lupo lupo_categories">
			<?php
			foreach($this->agecategories as $agecategory){ ?>
				<li><a href="<?php echo $agecategory['link']?>"><?php echo $agecategory['title']?></a>
				<?php if($componentParams->get('cats_nbr_games', '1')) { ?>
					(<?php echo $agecategory['number']?>)
				<?php } ?>
				</li>
			<?php } ?>
		</ul>

		<?php } else { ?>

		<table class="uk-table uk-table-striped uk-table-condensed" id="lupo_category_table">
			<?php
			foreach($this->agecategories as $agecategory){ ?>
				<tr>
					<td width="100">
						<a href="<?php echo $agecategory['link']?>">
							<?php if($agecategory['image_thumb']!=null) { ?>
								<img class="uk-align-left" src="<?php echo $agecategory['image_thumb']?>">
							<?php } else { ?>
								<img class="uk-align-left" src="images/spiele/mini_dice-gray.jpg">
							<?php } ?>
						</a>
					</td>
					<td>
						<a href="<?php echo $agecategory['link']?>"><?php echo $agecategory['title']?></a>
						<?php if($componentParams->get('cats_nbr_games', '1')) { ?>
							(<?php echo $agecategory['number']?>)
						<?php } ?>
						<p class="uk-margin-remove"><?php echo $agecategory['description']?></p>
					</td>
				</tr>
			<?php } ?>
		</table>

		<?php } ?>

	</div>
</article>