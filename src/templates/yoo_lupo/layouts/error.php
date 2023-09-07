<?php
/**
* @package   Warp Theme Framework
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

// add css
$this['asset']->addFile('css', 'css:theme.css');

$app    = JFactory::getApplication();
$path   = JURI::base(true).'/templates/'.$app->getTemplate().'/';

?>

<!DOCTYPE HTML>
<html lang="<?php echo $this['config']->get('language'); ?>" dir="<?php echo $this['config']->get('direction'); ?>" class="uk-height-1-1 tm-error">

<head>
<?php echo $this->render('head', compact('error', 'title')); ?>
	<style type="text/css">
		html {
			background: url(<?=$path?>images/background/404-lego.jpg) no-repeat center center fixed;
			-webkit-background-size: cover;
			-moz-background-size: cover;
			-o-background-size: cover;
			background-size: cover;
		}
		body {
			background-color: transparent;
		}
		p, .tm-error-icon {
			color: #cbcbcb;
		}
		.tm-error-headline, .uk-h3 {
			color: #eeeeee;
		}
		a {
			color: white;
			font-weight: bold;
		}
		a:hover {
			color: #9e9e9e;
			text-decoration: underline;
		}
	</style>
</head>

<body class="uk-height-1-1 uk-vertical-align uk-text-center">

	<div class="uk-vertical-align-middle uk-container-center">

		<i class="tm-error-icon uk-icon-frown-o"></i>

		<h1 class="tm-error-headline"><?php echo $error; ?></h1>

		<h2 class="uk-h3"><?php echo $title; ?></h2>

		<p><?php echo $message; ?></p>

	</div>

</body>
</html>