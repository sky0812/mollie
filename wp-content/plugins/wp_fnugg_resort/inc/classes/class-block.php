<?php

namespace Ilia\Fnugg_Resort;

use DateTime;

class Block {

    public function __construct() {
        add_action( 'init', [ $this, 'register_fnugg_block' ] );
        
        $this->weather_array = [
            '0', 
            'Strålende sol', 
            'For det meste sol',
            'Overskyet', 
            'Her er det meldt snø',
            'Det snør!', 
            'Fare for regn', 
            'Det regner', 
            'Fare for sludd', 
            'Det sludder..'
        ];
    }

    public function register_fnugg_block ( ) {

        $block_name = 'fnugg-block';

        $block_namespace = 'gutenberg-block/' . $block_name;

        wp_enqueue_script( 
            'main-script', 
            WP_FNUGGRESORT_ASSETS_URL . '/js/script.js', 
            ['wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor', 'wp-api-fetch', 'wp-polyfill']
        );

        wp_enqueue_style(
            'main-style',
            WP_FNUGGRESORT_ASSETS_URL . '/css/style.css'
        );

        register_block_type(
            $block_namespace,
            [
                'editor_script' => 'main-script',  
                'render_callback' => [$this, 'render_fnugg_block'],
                'style' => 'main-style',
            ]
        );

    }

    public function render_fnugg_block( $atr ) {

        do_action( 'ilia\fnugg_resort\search', $atr );

        ob_start();

        require WP_FNUGGRESORT_PATH . '/templates/block-template.php';

        $html = ob_get_clean();

        return $html;

    }
}