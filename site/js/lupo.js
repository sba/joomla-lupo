jQuery(document).ready(function ($) {
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
                row_category = $(this).data('category');
                row_agecategory = $(this).data('agecategory');
                row_genres = $(this).data('genres');

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

        $('#btn-dropdown-filter > span').html($(this).html());

        //bugfix: remove fixed height
        $('.tm-main, .tm-sidebar-a').css('min-height', '');
    })
})