<?php
/**
 * @package		Joomla
 * @subpackage	LUPO
 * @copyright	Copyright (C) databauer / Stefan Bauer
 * @author		Stefan Bauer
 * @link		http://www.ludothekprogramm.ch
 * @license		License GNU General Public License version 2 or later
 */

 // No direct access to this file
defined('_JEXEC') or die('Restricted access');

//load lupo styles
JHTML::stylesheet('com_lupo.css', 'components/com_lupo/css/');
$componentParams = &JComponentHelper::getParams('com_lupo');

//TODO: support Markdown-Syntax?
//$github = new JGithub;
//$description = $github->markdown->render($description);

//TODO: remove? is not documented and nobody is using it..
//beautify some (german) strings...
/*$description = str_replace(
					array("Beschreibung:","Beschreibung:<br>","Aufgabe/Ziel:")
					,array("<b>Beschreibung:</b>","<b>Beschreibung:</b>","<b>Aufgabe/Ziel:</b>")
					,$this->game['description']);
*/

$description = $this->game['description'];

?>
<article class="tm-article">
    <div class="tm-article-content ">
<?php
if($this->game == 'error'){
	?><h2 class="contentheading">Fehler - Spiel nicht gefunden</h2><?php
} else {

	?><h2 class="contentheading"><?php echo $this->game['title']?></h2>

	<?php
	if($componentParams->get('show_toy_photo', '1') || $this->game['description']!=""){
		if($componentParams->get('show_toy_photo', '1') && $this->game['image_thumb']!=NULL && $this->game['description']!=""){
			$grid_width="2";
		} else {
			$grid_width="1";
		}
		?>
		<div class="uk-grid">
		<?php
		if($this->game['description']!="") {?>
			<div class="uk-width-1-<?php echo $grid_width?>">
			<?php

			?><div class="lupo_description"><?php echo $description;?></div>
			</div><?php
		} ?>
		<?php
		if($componentParams->get('show_toy_photo', '1')){?>
			<div class="uk-width-1-<?php echo $grid_width?>">
			<?php
			if($this->game['image_thumb']==null){
				if(!$this->game['image']==null){
					$image_size=getimagesize($this->game['image']);
					?><img class="lupo_image"width="<?php echo $image_size[0]?>" height="<?php echo $image_size[1]?>"  src="<?php echo $this->game['image']?>"><?php
				}
			} else {
				$image_thumb_size=getimagesize($this->game['image_thumb']);
				if($this->game['image']==null){
					?><img class="lupo_image" width="<?php echo $image_thumb_size[0]?>" height="<?php echo $image_thumb_size[1]?>" src="<?php echo $this->game['image_thumb']?>"><?php
				} else {
					?>
					<a href="<?php echo $this->game['image']?>" data-uk-lightbox title="<?php echo $this->game['title']?>"><img width="<?php echo $image_thumb_size[0]?>" height="<?php echo $image_thumb_size[1]?>" class="lupo_image" alt="<?php echo JText::_("COM_LUPO_TOY").' '.$this->game['number']?>" src="<?php echo $this->game['image_thumb']?>" /></a>
					<div id="img-toy" class="uk-modal">
						<div>
							<img src="<?php echo $this->game['image']?>" alt="<?php echo JText::_("COM_LUPO_TOY").' '.$this->game['number']?>" />
						</div>
					</div>
					<?php
				}
			}?>
			</div>
		<?php
		}?>
		</div>
	<?php
	}?>

	<table class="uk-table uk-table-striped uk-table-condensed" id="lupo_detail_table">
		<colgroup>
			<col style="width:150px">
			<col />
		</colgroup>
		<?php if($componentParams->get('detail_show_toy_no', '1')){ ?>
		<tr>
			<td><?php echo JText::_("COM_LUPO_ART_NR")?>:</td>
			<td><?php echo $this->game['number']?></td>
		</tr>
		<?php } ?>
		<?php if($componentParams->get('detail_show_toy_category', '1')){ ?>
		<tr>
		  <td><?php echo JText::_("COM_LUPO_CATEGORY")?>:</td>
		  <td><a href="<?php echo $this->game['link_cat']?>"><?php echo $this->game['category']?></a></td>
		</tr>
		<?php } ?>
		<?php if($componentParams->get('detail_show_toy_age_category', '1')){ ?>
		<tr>
		  <td><?php echo JText::_("COM_LUPO_AGE_CATEGORY")?>:</td>
			<td><a href="<?php echo $this->game['link_agecat']?>"><?php echo $this->game['age_category']?></a></td>
		</tr>
		<?php } ?>
		<?php if($componentParams->get('detail_show_toy_genres', '1') && $this->game['genres']!=""){ ?>
		<tr>
		  <td><?php echo JText::_("COM_LUPO_GENRES")?>:</td>
		  <td><?php echo $this->game['genres']?></td>
		</tr>
		<?php } ?>
		<?php if($componentParams->get('detail_show_toy_play_duration', '1') && $this->game['play_duration']!="") {?>
		<tr>
		  <td><?php echo JText::_("COM_LUPO_PLAY_DURATION")?>:</td>
		  <td><?php echo $this->game['play_duration']?></td>
		</tr>
		<?php } ?>
		<?php if($componentParams->get('detail_show_toy_players', '1') && $this->game['players']!="") {?>
		<tr>
		  <td><?php echo JText::_("COM_LUPO_PLAYERS")?>:</td>
		  <td><?php echo $this->game['players']?></td>
		</tr>
		<?php } ?>
		<?php if($componentParams->get('detail_show_toy_fabricator', '1') && $this->game['fabricator']!="") {?>
		<tr>
		  <td><?php echo JText::_("COM_LUPO_FABRICATOR")?>:</td>
		  <td><?php echo $this->game['fabricator']?></td>
		</tr>
		<?php } ?>
		<?php if($componentParams->get('detail_show_toy_nbrdays', '1')){ ?>
		<tr>
		  <td><?php echo JText::_("COM_LUPO_DAYS")?>:</td>
		  <td><?php echo $this->game['days']?></td>
		</tr>
		<?php } ?>
		<?php if(count($this->game['editions'])==1){?>
		<?php if($componentParams->get('detail_show_toy_tax', '1') && ($componentParams->get('detail_show_toy_tax_not_null', '1')=='0' || $this->game['editions'][0]['tax'] > 0)){ ?>
		<tr>
		  <td><?php echo JText::_("COM_LUPO_TAX")?>:</td>
		  <td>Fr. <?php echo number_format($this->game['editions'][0]['tax'],2)?></td>
		</tr>
		<?php } ?>
		<?php } else { ?>
		<?php if($componentParams->get('detail_show_toy_nbrtoys', '1')){ ?>
		<tr>
		  <td><?php echo JText::_("COM_LUPO_NBR_TOYS")?>:</td>
		  <td><?php echo count($this->game['editions'])?></td>
		</tr>
		<?php } ?>
		<?php if($componentParams->get('detail_show_toy_tax', '1') && ($componentParams->get('detail_show_toy_tax_not_null', '1')=='0' || $this->game['tax_max'] > 0)){ ?>
		<tr>
		  <td><?php echo JText::_("COM_LUPO_TAX")?>:</td>
		  <td>
			<?php if(!isset($this->game['tax_max']) || $this->game['tax_min']==$this->game['tax_max']) {
				echo JText::_("COM_LUPO_CURRENCY"). " " . number_format($this->game['tax_min'],2);
			} else {
				echo JText::_("COM_LUPO_CURRENCY")." " . number_format($this->game['tax_min'],2);
				echo " " . JText::_("COM_LUPO_TO") . " ". JText::_("COM_LUPO_CURRENCY") . number_format($this->game['tax_max'],2);
			} ?>
		  </td>
		</tr>
		<?php }
		}?>
	</table>

	<?php
	// TODO: Move to model?
	foreach($this->game['documents'] as $document) {
		switch($document['code']){
			case 'youtube':
				$href='https://www.youtube.com/watch?v='.$document['value'];
				$desc='YouTube';
				$icon='youtube-play';
				$lightbox=true;
				break;
			case 'vimeo':
				$href='http://vimeo.com/'.$document['value'];
				$desc='Vimeo';
				$icon='vimeo-square';
				$lightbox=true;
				break;
			case 'facebook':
				$href=$document['value'];
				$desc='Facebook';
				$icon='facebook-square';
				$lightbox=false;
				break;
			case 'wikipedia':
				$href=$document['value'];
				$desc='Wikipedia';
				$icon='external-link';
				$lightbox=false;
				break;
			case 'link_manual':
				$href=$document['value'];
				$desc='Spielanleitung';
				$icon='file-pdf-o';
				$lightbox=false;
				break;
 			case 'link_review':
 			case 'website':
			default:
				$href=$document['value'];
				$desc='Link';
				$icon='external-link';
				$lightbox=false;
				break;
		}
		if($document['desc']!=""){
			$desc=$document['desc'];
		}
		if($lightbox){
			$lightbox="data-uk-lightbox=\"{group:'grp-docs'}\"";
		} else {
			$lightbox='target="_blank"';
		}
		?><a class="uk-button" href="<?php echo $href?>" <?php echo $lightbox?>><i class="uk-icon-<?php echo $icon?>"></i> <?php echo $desc?></a> <?php
	}
	if(count($this->game['documents'])>0 ){
		?><br><br><?php
	}

    //related games
    if($componentParams->get('detail_show_toy_related', '1')) {
        if (count($this->game['related']) > 0) {?>
            <br>
            <?php echo JText::_("COM_LUPO_RELATED_TOYS");?>
            <br>
            <ul><?php
                foreach ($this->game['related'] as $related) {
                    ?>
                    <li>
                        <a href="<?php echo $related['link']?>"><?php echo $related['title']?> <?php echo $related['edition']?></a>
                    </li>
                <?php }?>
            </ul>
        <?php }
    }?>

	<br />
	<?php
	//navigation
	$session = JFactory::getSession();
	$session_lupo=$session->get('lupo');
	if($componentParams->get('detail_show_toy_nav', '1') && $session_lupo!=null && isset($_GET['pos'])){
		?><ul class="pagenav"><?php
		$pos=$_GET['pos'];
		if($pos>=1){
			$nav_game=$session_lupo[$pos-1]; ?>
			<li class="pagenav-prev">
				<a class="uk-button uk-button-primary" accesskey="<?php echo JText::_('COM_LUPO_NAV_PREV_GAME')[0]; ?>" href="<?php echo JRoute::_('index.php?option=com_lupo&view=game&id='.$nav_game['id'].'&pos='.($pos-1))?>"><i class="uk-icon-caret-left"></i> <?php echo JText::_('COM_LUPO_NAV_PREV_GAME'); ?></a>
			</li>
		<?php }
		if($pos+1<count($session_lupo)){
			$nav_game=$session_lupo[$pos+1]; ?>
			<li class="pagenav-next">
				<a class="uk-button uk-button-primary" accesskey="<?php echo JText::_('COM_LUPO_NAV_NEXT_GAME')[0]; ?>" href="<?php echo JRoute::_('index.php?option=com_lupo&view=game&id='.$nav_game['id'].'&pos='.($pos+1))?>"><?php echo JText::_('COM_LUPO_NAV_NEXT_GAME'); ?> <i class="uk-icon-caret-right"></i></a>
			</li>
		<?php }?>
		</ul><?php
	}
}  // endif get_game=error?>

</div>
</article>