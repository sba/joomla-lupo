<?php

use Joomla\CMS\HTML\HTMLHelper;

if (!class_exists( 'LupoModelLupo' )){
    JLoader::import( 'lupo', JPATH_ROOT . '/components/com_lupo/models' ); //TODO: use use
}

$model = new LupoModelLupo();
$categories = $model->getCategories(false);
$agecategories = $model->getAgecategories(false);
$genres = $model->getGenres();
?>

<div class="uk-form-horizontal" ng-class="vm.name == 'contentCtrl' ? 'uk-width-2-3@l uk-width-1-2@xl' : ''">

    <h3 class="uk-heading-divider">{{'Content' | trans}}</h3>

    <div class="uk-margin">
        <label class="uk-form-label" for="wk-category">{{'Filter games by' | trans}}</label>
        <div class="uk-form-controls">
            <select id="wk-filter" class="uk-select uk-form-width-large" ng-model="content.data['filter']">
                <option value="all">{{'All toys' | trans}}</option>
                <option value="category">{{'Category' | trans}}</option>
                <option value="agecategory">{{'Age-Category' | trans}}</option>
                <option value="genre">{{'Genre' | trans}}</option>
                <option value="game">{{'Game(s)' | trans}}</option>
            </select>
        </div>
    </div>

    <div class="uk-margin" ng-if="content.data.filter === 'category'">
        <label class="uk-form-label" for="wk-category">{{'Category' | trans}}</label>
        <div class="uk-form-controls">
            <select id="wk-category" class="uk-select uk-form-width-large" ng-model="content.data['category']">
                <option value="new">{{'New games' | trans}}</option>
                <?php foreach ($categories as $option) : ?>
                    <option value="<?php echo $option['alias'] ?>"><?php echo $option['title'] ?></option>
                <?php endforeach ?>
            </select>
        </div>
    </div>

    <div class="uk-margin" ng-if="content.data.filter === 'agecategory'">
        <label class="uk-form-label" for="wk-agecategory">{{'Age-Category' | trans}}</label>
        <div class="uk-form-controls">
            <select id="wk-agecategory" class="uk-select uk-form-width-large" ng-model="content.data['agecategory']">
                <?php foreach ($agecategories as $option) : ?>
                    <option value="<?php echo $option['alias'] ?>"><?php echo $option['title'] ?></option>
                <?php endforeach ?>
            </select>
        </div>
    </div>

    <div class="uk-margin" ng-if="content.data.filter === 'genre'">
        <label class="uk-form-label" for="wk-genre">{{'Genre' | trans}}</label>
        <div class="uk-form-controls">
            <select id="wk-genre" class="uk-select uk-form-width-large" ng-model="content.data['genre']">
                <?php foreach ($genres as $option) : ?>
                    <option value="<?php echo $option['alias'] ?>"><?php echo $option['title'] ?></option>
                <?php endforeach ?>
            </select>
        </div>
    </div>

    <div class="uk-margin" ng-if="content.data.filter === 'game'">
        <label class="uk-form-label" for="wk-game">{{'Game-Number(s), seperate with ;' | trans}}</label>
        <div class="uk-form-controls">
            <input id="wk-game" class="uk-input uk-form-width-large" ng-model="content.data['game']">
        </div>
    </div>

    <div class="uk-margin" ng-if="content.data.filter === 'all' || content.data.filter === 'category' || content.data.filter === 'agecategory' || content.data.filter === 'genre'">
        <label class="uk-form-label" for="wk-sort">{{'Sorting' | trans}}</label>
        <div class="uk-form-controls">
            <select id="wk-sort" class="uk-select uk-form-width-large" ng-model="content.data['sort']">
                <option value="alpha">{{'Alphabetic' | trans}}</option>
                <option value="date">{{'Aquired Date' | trans}}</option>
                <option value="random">{{'Random' | trans}}</option>
            </select>
        </div>
    </div>

    <div class="uk-margin" ng-if="content.data.filter === 'all' || content.data.filter === 'category' || content.data.filter === 'agecategory' || content.data.filter === 'genre'">
        <label class="uk-form-label" for="wk-number">{{'Limit' | trans}}</label>
        <div class="uk-form-controls">
            <input id="wk-number" class="uk-select uk-form-width-large" type="number" value="999" min="1" step="1" ng-model="content.data['number']">
        </div>
    </div>

    <div class="uk-margin">
        <label class="uk-form-label" for="wk-game">{{'Limit content characters' | trans}}</label>
        <div class="uk-form-controls">
            <input id="wk-limitchars" class="uk-select uk-form-width-large" type="number" value="" min="1" step="1" ng-model="content.data['limitchars']">
        </div>
    </div>

    <div class="uk-margin">
        <label class="uk-form-label" for="wk-game">{{'Add Game-Attributes to Content' | trans}}</label>
        <div class="uk-form-controls">
            <input id="wk-contenttable" class="uk-input uk-form-width-large" type="text" value="" ng-model="content.data['contenttable']">
            <span class="uk-text-small uk-text-muted uk-form-width-large">{{'Seperate with ; and | for custom desc' | trans}}</span>
        </div>
    </div>

    <div class="uk-margin">
        <label class="uk-form-label">{{'Image' | trans}}</label>
        <div class="uk-form-controls uk-form-controls-text">
            <label class="uk-form-label"><input type="checkbox" class="uk-checkbox" ng-model="content.data['imagesonly']"> {{'Load only toys with photo' | trans}}</label>
        </div>
    </div>


    <h3 class="uk-heading-divider uk-margin-large-top">{{'Mapping' | trans}}</h3>

    <div class="uk-margin">
        <span class="uk-form-label">{{'Fields' | trans}}</span>
        <div class="uk-form-controls">
            <div class="uk-grid uk-grid-small uk-child-width-1-2">
                <div>
                    <input class="uk-input" type="text" value="media" disabled>
                </div>
                <div>
                    <select class="uk-select" ng-model="content.data['media']">
                        <option value="">{{'Full image' | trans}}</option>
                        <option value="mini_">{{'Mini-Thumbnail' | trans}}</option>
                    </select>
                </div>
            </div>

            <div class="uk-grid uk-grid-small uk-child-width-1-2">
                <div>
                    <input class="uk-input" type="text" value="content" disabled>
                </div>
                <div>
                    <select class="uk-select" ng-model="content.data['title']">
                        <option value="title">{{'Title' | trans}}</option>
                        <option value="description_title">{{'Description Title' | trans}}</option>
                    </select>
                </div>
            </div>

            <div class="uk-grid uk-grid-small uk-child-width-1-2">
                <div>
                    <input class="uk-input" type="text" value="content" disabled>
                </div>
                <div>
                    <select class="uk-select" ng-model="content.data['content']">
                        <option value="description_title">{{'Description Title' | trans}}</option>
                        <option value="description_text">{{'Description Text' | trans}}</option>
                        <option value="description_title-text">{{'Description Title + Text' | trans}}</option>
                        <option value="keywords">{{'Keywords' | trans}}</option>
                        <option value="notext">{{'No Text' | trans}}</option>
                    </select>
                </div>
            </div>

            <div class="uk-grid uk-grid-small uk-child-width-1-2">
                <div>
                    <input class="uk-input" type="text" value="badge" disabled>
                </div>
                <div>
                    <select class="uk-select" ng-model="content.data['badge']">
                        <option value="">{{'None' | trans}}</option>
                        <option value="number">{{'Number' | trans}}</option>
                        <option value="title">{{'Title' | trans}}</option>
                        <option value="category">{{'Category' | trans}}</option>
                        <option value="age_category">{{'Age-Category' | trans}}</option>
                        <option value="tax">{{'Tax' | trans}}</option>
                        <option value="players">{{'Players' | trans}}</option>
                        <option value="play_duration">{{'Play Duration' | trans}}</option>
                        <option value="userdefined">{{'Userdefined' | trans}}</option>
                    </select>
                </div>
            </div>

            <div class="uk-grid uk-grid-small uk-child-width-1-2">
                <div>
                    <input class="uk-input" type="text" value="tags" disabled>
                </div>
                <div>
                    <select class="uk-select" ng-model="content.data['tags']">
                        <option value="">{{'None' | trans}}</option>
                        <option value="categories">{{'Categories' | trans}}</option>
                        <option value="agecategories">{{'Age-Categories' | trans}}</option>
                        <option value="genres">{{'Genres' | trans}}</option>
                        <option value="fabricator">{{'Fabricator' | trans}}</option>
                        <option value="userdefined">{{'Userdefined' | trans}}</option>
                    </select>
                </div>
            </div>
        </div>
    </div>


</div>