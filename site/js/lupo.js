jQuery(document).ready(function ($) {
    const cookieName = 'lupo_searchfilter';

    $('.lupo_btn_subset').click(function () {
        let filter_categories = $(this).data('categories');
        let filter_agecategories = $(this).data('agecategories');
        let filter_genres = $(this).data('genres');

        //filter table rows
        if (filter_categories === "*") {
            $('#lupo_category_table > tbody > tr').show();
        } else {
            let row_category, row_agecategory, row_genres, has_category, has_agecategory, has_genres;
            $('#lupo_category_table > tbody > tr').each(function () {
                row_category = String($(this).data('category'));
                row_agecategory = String($(this).data('agecategory'));
                row_genres = String($(this).data('genres'));

                has_category = filter_categories.includes(row_category);
                has_agecategory = filter_agecategories.includes(row_agecategory);
                has_genres = filter_genres.filter(element => row_genres.includes(element));

                if (has_category || has_agecategory || has_genres.length > 0) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            })
        }

        let lupo_searchfilter = $(this).html();
        $('#btn-dropdown-filter > span').html(lupo_searchfilter);

        //store selection
        document.cookie = cookieName+"="+lupo_searchfilter+"; path=/";

        //bugfix: remove fixed height
        $('.tm-main, .tm-sidebar-a').css('min-height', '');
    })


    function setLastFilter(){
        try {
            const cookieValue = document.cookie
                .split('; ')
                .find(row => row.startsWith(cookieName + '='))
                .split('=')[1];

            if($('.lupo_dropdown').length>0) {
                //search item and 'click' it
                $('.lupo_btn_subset').each(function () {
                    if ($(this).html() === cookieValue) {
                        $(this).trigger('click');
                        $('#btn-dropdown-filter > span').html(cookieValue);
                        return false; //exit each loop
                    }
                })
            }

            if($('.lupo_buttons').length>0) {
                //search button 'click' it and set active
                $('.lupo_btn_subset').each(function () {
                    if ($(this).html() === cookieValue) {
                        $(this).trigger('click');
                        $(this).addClass('uk-active');
                    } else {
                        $(this).removeClass('uk-active');
                    }
                })
            }

        }
        catch (e) {
            //no filter set: NOP
        }
    }

    setLastFilter();
})