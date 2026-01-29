jQuery(function ($) {
    var config = $('html').data('config') || {};

    // Social buttons
    $('article[data-permalink]').socialButtons(config);

    //remove margin on bottom of page - only on homepage and if no article is shown
    if ($('.home-slider').length == 1 && $('.tm-leading-article').length == 0) {
        $('main.tm-content').css('margin-top', '0');
    }

    //hide Calendar-module if no events are available
    if (jQuery('.frontpage-news-autohide .tm-article-date').length == 0) {
        jQuery('.frontpage-news-autohide').hide();
    }

    //hide empty articles (mainly if client-info article for login has no content
    $article = jQuery('.tm-article .tm-article-content');
    if ($article.length == 1) {
        if ($article.text().trim() == "") {
            $article.parent().hide();
        }
    }

    //hide News-module if no news are available
    //its in most ludo sites in addition at the theme setting in 'Additional Scripts'. There its loaded earlier
    if(jQuery('.frontpage-news .tm-article-date').length==0){
        jQuery('.frontpage-news').hide();
    }

});
