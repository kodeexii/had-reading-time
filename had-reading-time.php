<?php
/**
 * =================================================================
 * Plugin Name:       Had Reading Time
 * Plugin URI:        https://hadeeroslan.my/
 * Description:       Memaparkan anggaran masa bacaan secara automatik di atas artikel dan halaman. Sebuah plugin kustom untuk Tuan Hadee Roslan.
 * Version:           0.0.1
 * Author:            Hadee Roslan
 * Author URI:        https://hadeeroslan.my/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       had-reading-time
 * =================================================================
 */

// Keselamatan: Halang akses terus ke fail ini.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Bahagian 1: Memuatkan fail CSS dengan cara yang betul (Enqueue)
 * Fungsi ini akan memberitahu WordPress untuk load fail style.css kita
 * hanya di bahagian front-end.
 */
function had_reading_time_enqueue_styles() {
    wp_enqueue_style(
        'had-reading-time-style', // Handle unik untuk CSS kita
        plugin_dir_url( __FILE__ ) . 'style.css', // Path penuh ke fail style.css
        array(), // Tiada dependencies
        '1.0.0' // Versi fail CSS, bagus untuk caching
    );
}
add_action( 'wp_enqueue_scripts', 'had_reading_time_enqueue_styles' );


/**
 * Bahagian 2: Fungsi Teras Untuk Kira & Papar Masa Bacaan
 * Kod logik yang sama seperti sebelum ini.
 */
if ( ! function_exists( 'had_display_reading_time' ) ) {
    function had_display_reading_time( $content ) {
        if ( ( is_single() || is_page() ) && in_the_loop() && is_main_query() ) {
            $current_post_id = get_the_ID();
            $raw_content = get_post_field( 'post_content', $current_post_id );
            $stripped_content = strip_tags( $raw_content );
            $word_count = str_word_count( $stripped_content );

            if ( $word_count < 1 ) {
                return $content;
            }

            $reading_speed_wpm = 200;
            $reading_time_minutes = ceil( $word_count / $reading_speed_wpm );
            $timer_text = " minit";
            
            // Kita guna class name dari fail CSS
            $reading_time_html = '<p class="had-reading-time-indicator">⏱️ Anggaran Bacaan: ' . $reading_time_minutes . $timer_text . '</p>';

            return $reading_time_html . $content;
        }
        return $content;
    }
}
add_filter( 'the_content', 'had_display_reading_time', 10 );