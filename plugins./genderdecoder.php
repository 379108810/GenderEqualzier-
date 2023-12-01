<?php
/*
Plugin Name: Gender Decoder
Description: A plugin to check gender bias in text.
Version: 1.0
Author: RENBO LEI
*/

// Enqueue necessary scripts
function gender_decoder_enqueue_scripts() {
    wp_enqueue_script('gender-decoder-ajax', plugin_dir_url(__FILE__) . 'ajax.js', array('jquery'), null, true);
    wp_localize_script('gender-decoder-ajax', 'genderDecoderAjax', array('ajax_url' => admin_url('admin-ajax.php')));
}
add_action('wp_enqueue_scripts', 'gender_decoder_enqueue_scripts');

// AJAX handler function
function gender_decoder_ajax_handler() {
    $text = $_POST['text_to_check'];
    $script_path = plugin_dir_path(__FILE__) . 'check_gender_bias.py';
    $text_escaped = escapeshellarg($text);
    $result = shell_exec('python3 ' . $script_path . ' ' . $text_escaped);
    $result_array = json_decode($result, true);

    $color = '';
    $icon = '';
    if (strpos($result_array['result'], 'Male Bias') !== false) {
       $color = '#0073aa';
       $icon = '<i class="dashicons dashicons-admin-users"></i> ';
    } elseif (strpos($result_array['result'], 'Female Bias') !== false) {
       $color = '#d54e21';
       $icon = '<i class="dashicons dashicons-admin-users"></i> ';
    } elseif (strpos($result_array['result'], 'Neutral') !== false) {
       $color = '#46a546';
       $icon = '<i class="dashicons dashicons-admin-users"></i> ';
    }

echo '<div style="color: white; background-color: ' . $color . '; font-size: 20px; line-height: 60px;">' . $icon . '<strong>Result:</strong> ' . $result_array['result'] . '</div>';
    echo '<div style="color: white; background-color: ' . $color . '; font-size: 20px; line-height: 60px;"><strong>Explanation:</strong> ' . $result_array['explanation'] . '</div>';
    echo '<div style="color: white; background-color: ' . $color . '; font-size: 20px; line-height: 60px;"><strong>Masculine Coded Words:</strong> ' . implode(", ", $result_array['masculine_coded_words']) . '</>
    echo '<div style="color: white; background-color: ' . $color . '; font-size: 20px; line-height: 60px;"><strong>Feminine Coded Words:</strong> ' . implode(", ", $result_array['feminine_coded_words']) . '</di>
    wp_die();
}
add_action('wp_ajax_gender_decoder', 'gender_decoder_ajax_handler');
add_action('wp_ajax_nopriv_gender_decoder', 'gender_decoder_ajax_handler');

// Shortcode to display the input form on any post or page
function gender_decoder_form() {
    ob_start();
    ?>
    <div id="gender-decoder-form">
        <textarea id="text-input" rows="20" cols="50" placeholder="Enter your text here..."></textarea><br>
        <br><br>
        <button id="check-button">Check Gender Bias</button>
        <div id="result-display"></div>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('gender-decoder', 'gender_decoder_form');
?>
