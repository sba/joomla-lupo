<?php
/**
* @package   yoo_avenue
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

// get theme configuration
include($this['path']->path('layouts:theme.config.php'));

?>
<!DOCTYPE HTML>
<html lang="<?php echo $this['config']->get('language'); ?>" dir="<?php echo $this['config']->get('direction'); ?>"  data-config='<?php echo $this['config']->get('body_config','{}'); ?>'>

<head>
<?php echo $this['template']->render('head'); ?>
<style>
<?php
$background_image = $this['config']->get('background-image');
$background_style = $this['config']->get('background-style');
$header_image = $this['config']->get('header-image')!='';
$header_wide = $this['config']->get('header-wide')!='';

$app    = JFactory::getApplication();
$template_path   = JURI::base(true).'/templates/'.$app->getTemplate().'/';
$headerimage   = JURI::base(true).'/images/headers/logo-header.png';


if($background_image != ""){
    ?>
    body {
        background: url('<?=$template_path?>/images/background/<?=$background_image?>') <?=$background_style?>;
    }
<?php } ?>

<?php if($header_image){?>
.tm-headerbar {
    background-color: white;
    background-image: url('<?=$headerimage?>');
    height: 157px;
}
<?php } ?>

<?php if($header_wide){?>
body.header-wide .tm-search {
    margin-top: 15px;
}

#lupo_loginlink {
    margin-top: 15px;
    position: absolute;
    width: 220px;
}
.tm-headerbar .uk-search {
    margin-top: 45px;
}  <?php } ?>

</style>
</head>

<body class="<?php echo $this['config']->get('body_classes'); ?> <?=$header_wide?'header-wide':''?> <?=$header_image?'':'header-nobgimg'?>">
    <?php if($header_wide){?>
    <div class="header-bg1 uk-hidden-small"></div>
    <div class="header-bg2 uk-hidden-small"></div>
    <?php } ?>

    <div class="tm-page-bg">

        <div class="uk-container uk-container-center">

            <div class="tm-container">

                <?php if ($this['widgets']->count('logo') || $this['widgets']->count('search') || $this['widgets']->count(' headerbar')) : ?>
                <div class="tm-headerbar uk-clearfix uk-hidden-small">

                    <?php if ($this['widgets']->count('logo')) : ?>
                    <a class="tm-logo" href="<?php echo $this['config']->get('site_url'); ?>"><?php echo $this['widgets']->render('logo'); ?></a>
                    <?php endif; ?>

                    <?php if ($this['widgets']->count('search')) : ?>
                    <div class="tm-search uk-float-right">
                        <?php echo $this['widgets']->render('search'); ?>
                    </div>
                    <?php endif; ?>

                    <?php echo $this['widgets']->render('headerbar'); ?>

                </div>
                <?php endif; ?>

                <?php if ($this['widgets']->count('menu') || $this['widgets']->count('toolbar-l ') || $this['widgets']->count('toolbar-r')) : ?>
                <div class="tm-top-block tm-grid-block">

                    <?php if ($this['widgets']->count('menu')) : ?>
                    <nav class="tm-navbar uk-navbar">

                        <?php if ($this['widgets']->count('menu')) : ?>
                        <?php echo $this['widgets']->render('menu'); ?>
                        <?php endif; ?>

                        <?php if ($this['widgets']->count('offcanvas')) : ?>
                        <a href="#offcanvas" class="uk-navbar-toggle uk-visible-small" data-uk-offcanvas></a>
                        <?php endif; ?>

                        <?php if ($this['widgets']->count('logo-small')) : ?>
                        <div class="uk-navbar-content uk-navbar-center uk-visible-small"><a class="tm-logo-small" href="<?php echo $this['config']->get('site_url'); ?>"><?php echo $this['widgets']->render('logo-small'); ?></a></div>
                        <?php endif; ?>

                    </nav>
                    <?php endif; ?>

                    <?php if ($this['widgets']->count('toolbar-l') || $this['widgets']->count('toolbar-r')) : ?>
                    <div class="tm-toolbar uk-clearfix uk-hidden-small">

                        <?php if ($this['widgets']->count('toolbar-l')) : ?>
                        <div class="uk-float-left"><?php echo $this['widgets']->render('toolbar-l'); ?></div>
                        <?php endif; ?>

                        <?php if ($this['widgets']->count('toolbar-r')) : ?>
                        <div class="uk-float-right"><?php echo $this['widgets']->render('toolbar-r'); ?></div>
                        <?php endif; ?>

                    </div>
                    <?php endif; ?>

                </div>
                <?php endif; ?>

                <?php if ($this['widgets']->count('top-a')) : ?>
                <section class="<?php echo $grid_classes['top-a']; ?> tm-grid-block" data-uk-grid-match="{target:'> div > .uk-panel'}"><?php echo $this['widgets']->render('top-a', array('layout'=>$this['config']->get('grid.top-a.layout'))); ?></section>
                <?php endif; ?>

                <?php if ($this['widgets']->count('top-b')) : ?>
                <section class="<?php echo $grid_classes['top-b']; ?> tm-grid-block" data-uk-grid-match="{target:'> div > .uk-panel'}"><?php echo $this['widgets']->render('top-b', array('layout'=>$this['config']->get('grid.top-b.layout'))); ?></section>
                <?php endif; ?>

                <?php if ($this['widgets']->count('main-top') || $this['widgets']->count('main-bottom') || $this['widgets']->count('sidebar-a') || $this['widgets']->count('sidebar-b') || $this['config']->get('system_output', true)) : ?>
                <div class="tm-middle uk-grid" data-uk-grid-match>

                    <?php if ($this['widgets']->count('main-top') || $this['widgets']->count('main-bottom') || $this['config']->get('system_output', true)) : ?>
                    <div class="<?php echo $columns['main']['class'] ?>">

                        <?php if ($this['widgets']->count('main-top')) : ?>
                        <section class="<?php echo $grid_classes['main-top']; ?> tm-grid-block" data-uk-grid-match="{target:'> div > .uk-panel'}"><?php echo $this['widgets']->render('main-top', array('layout'=>$this['config']->get('grid.main-top.layout'))); ?></section>
                        <?php endif; ?>

                        <?php if ($this['config']->get('system_output', true)) : ?>
                        <main class="tm-content">

                            <?php if ($this['widgets']->count('breadcrumbs')) : ?>
                            <?php echo $this['widgets']->render('breadcrumbs'); ?>
                            <?php endif; ?>

                            <?php echo $this['template']->render('content'); ?>

                        </main>
                        <?php endif; ?>

                        <?php if ($this['widgets']->count('main-bottom')) : ?>
                        <section class="<?php echo $grid_classes['main-bottom']; ?> tm-grid-block" data-uk-grid-match="{target:'> div > .uk-panel'}"><?php echo $this['widgets']->render('main-bottom', array('layout'=>$this['config']->get('grid.main-bottom.layout'))); ?></section>
                        <?php endif; ?>

                    </div>
                    <?php endif; ?>

                    <?php foreach($columns as $name => &$column) : ?>
                    <?php if ($name != 'main' && $this['widgets']->count($name)) : ?>
                    <aside class="<?php echo $column['class'] ?>"><?php echo $this['widgets']->render($name) ?></aside>
                    <?php endif ?>
                    <?php endforeach ?>

                </div>
                <?php endif; ?>

                <?php if ($this['widgets']->count('bottom-a')) : ?>
                <section class="<?php echo $grid_classes['bottom-a']; ?> tm-grid-block" data-uk-grid-match="{target:'> div > .uk-panel'}"><?php echo $this['widgets']->render('bottom-a', array('layout'=>$this['config']->get('grid.bottom-a.layout'))); ?></section>
                <?php endif; ?>

                <?php if ($this['widgets']->count('bottom-b') || $this['widgets']->count('bottom-c') || $this['widgets']->count('footer') || $this['widgets']->count('debug')) : ?>
                <div class="tm-block-bottom">

                    <?php if ($this['widgets']->count('bottom-b')) : ?>
                    <section class="<?php echo $grid_classes['bottom-b']; ?> tm-grid-block" data-uk-grid-match="{target:'> div > .uk-panel'}"><?php echo $this['widgets']->render('bottom-b', array('layout'=>$this['config']->get('grid.bottom-b.layout'))); ?></section>
                    <?php endif; ?>

                    <?php if ($this['widgets']->count('bottom-c')) : ?>
                    <section class="<?php echo $grid_classes['bottom-c']; ?> tm-grid-block" data-uk-grid-match="{target:'> div > .uk-panel'}"><?php echo $this['widgets']->render('bottom-c', array('layout'=>$this['config']->get('grid.bottom-c.layout'))); ?></section>
                    <?php endif; ?>

                    <?php if ($this['widgets']->count('footer + debug') || $this['config']->get('warp_branding', true) || $this['config']->get('totop_scroller', true)) : ?>
                    <footer class="tm-footer">

                        <?php if ($this['config']->get('totop_scroller', true)) : ?>
                        <a class="tm-totop-scroller" data-uk-smooth-scroll href="#"></a>
                        <?php endif; ?>

                        <?php
                            echo $this['widgets']->render('footer');
                            $this->output('warp_branding');
                            echo $this['widgets']->render('debug');
                        ?>

                    </footer>
                    <?php endif; ?>

                </div>
                <?php endif; ?>

            </div>

        </div>

    </div>

    <?php echo $this->render('footer'); ?>

    <?php if ($this['widgets']->count('offcanvas')) : ?>
    <div id="offcanvas" class="uk-offcanvas">
        <div class="uk-offcanvas-bar"><?php echo $this['widgets']->render('offcanvas'); ?></div>
    </div>
    <?php endif; ?>

</body>
</html>