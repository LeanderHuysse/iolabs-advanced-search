<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       https://www.iolabs.nl
 * @since      1.0.0
 *
 * @package    Iolabs_Advanced_Search
 * @subpackage Iolabs_Advanced_Search/public/partials
 */

$data = $_POST;

?>


<div class="iosearch">
    <div class="iosearch__title">
        <h1>Advanced Search</h1>
    </div>
    <div class="iosearch__form">
        <div class="spinner" style="display: none;"></div>
        <form method="post" action="/search-results" id="iosearch__form"
              name="iosearch__process">
            <div class="iosearch__column">
                <div class="iosearch__row">
                    <input type="text" name="s_author_name" placeholder="Author" value="<?php echo $data['s_author_name'] ?? '';
                    ?>">
                </div>
                <div class="iosearch__row iosearch__double">
                    <input type="number" placeholder="Price from" name="s_price_from"
                           value="<?php echo $data['s_price_from'] ?? ''; ?>">
                    <input type="number" placeholder="Price to" name="s_price_to"
                           value="<?php echo $data['s_price_to'] ?? ''; ?>">
                </div>
            </div>

            <div class="iosearch__column">
                <div class="iosearch__row">
                    <input type="text" name="s_post_title" placeholder="Title" value="<?php echo
                    $data['s_post_title'] ?? '';
                    ?>">
                </div>
                <div class="iosearch__row iosearch__double">
                    <input type="number" placeholder="Published year from" name="s_year_from" value="<?php echo
                    $data['s_year_from'] ?? ''; ?>">
                    <input type="number" placeholder="Published year to" name="s_year_to"
                           value="<?php echo $data['s_year_to'] ?? ''; ?>">
                </div>
            </div>
            <div class="iosearch__loading">
                <img src='<?php echo plugin_dir_url(__FILE__); ?>../img/loading.gif' class='loadingIndicator'>
            </div>
            <div class="iosearch__results">

            </div>
            <div class="iosearch__actions">
                <div class="iosearch__column">
                    <input type="submit" value="Search">
                </div>
                <div class="iosearch__column">
                    <a class="iosearch__reset">Reset</a>
                </div>
            </div>
        </form>
    </div>
</div>
