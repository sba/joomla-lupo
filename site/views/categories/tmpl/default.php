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
$menu = JSite::getMenu()->getActive();
?>

<article class="tm-article">
	<div class="tm-article-content ">

		<?php if ($menu->params->get('show_page_heading', 1)) : ?>
			<h2 class="contentheading"><?php echo $menu->params->get('page_heading')?></h2>
		<?php endif; ?>
		
		<?php if($componentParams->get('cats_show_table_with_details', 0)=='0'){ ?>

		<ul class="lupo lupo_categories">
			<?php
			foreach($this->categories as $category){?>
				<li><a href="<?php echo $category['link']?>"><?php echo $category['title']?></a>
				<?php if($componentParams->get('cats_nbr_games', '1')) { ?>
					(<?php echo $category['number']?>)
				<?php } ?>
				</li>
			<?php } ?>
		</ul>

		<?php } else { ?>

		<table class="uk-table uk-table-striped uk-table-condensed" id="lupo_category_table">
			<?php
			foreach($this->categories as $category){ ?>
				<tr>
					<td width="100">
						<a href="<?php echo $category['link']?>">
							<?php if($category['image_thumb']!=null) { ?>
							<img class="uk-align-left" src="<?php echo $category['image_thumb']?>">
							<?php } else { ?>
							<img class="uk-align-left" src="images/spiele/mini_dice-gray.jpg">
							<?php } ?>
						</a>
					</td>
					<td>
						<b><a href="<?php echo $category['link']?>"><?php echo $category['title']?></a></b>
						<?php if($componentParams->get('cats_nbr_games', '1')) { ?>
							(<?php echo $category['number']?>)
						<?php } ?>
						<p class="uk-margin-remove uk-padding-remove">
                            <?php echo $category['description']?>
                            <?php if($category['sample_games']){ ?>
                                <br>
                                <?php
	                            unset($gamelinks);
                                foreach ($category['sample_games'] as $sample_game){
                                    $gamelinks[] = '<a href="'.$sample_game['link'].'">'.$sample_game['title'].'</a>';
                                }
                                echo implode(", ", $gamelinks);
                            }; ?>
                        </p>
					</td>
				</tr>
			<?php } ?>
		</table>

		<?php } ?>

	</div>
</article>