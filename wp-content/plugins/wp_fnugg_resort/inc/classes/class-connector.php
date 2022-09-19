<?php

namespace Ilia\Fnugg_Resort;

class Connector {

    public $resort_all_info;

    public function __construct() {

        add_action( 'ilia\fnugg_resort\search', [ $this, 'get_fnugg_resort_info' ], 10, 1 );
    }

    public function get_fnugg_resort_info( $atr ) {

        if( substr( $atr['searchName'], 0 ) == '/'){

            $search_name = substr($atr['searchName'], 1);
        } else{

            $search_name = $atr['searchName'];
        }

        $cache =  json_decode( get_transient( $search_name ), JSON_OBJECT_AS_ARRAY );

        if( $cache ){

            $this->resort_all_info = $cache;
        }else{

            $this->make_fnugg_api_call( $search_name );
        }
    }

    public function make_fnugg_api_call( $search_name ) {

        $response = wp_remote_get( 'https://api.fnugg.no/search?q=' . $search_name . '&sourceFields=name,images,contact.city,contact.address,conditions.combined.top' );
        $response_body = json_decode( wp_remote_retrieve_body($response) );
        $is_content = false;

        if( $response_body->hits->total != 0 ){

            $resort_name = $response_body->hits->hits['0']->_source->name;
            $resort_city = $response_body->hits->hits['0']->_source->contact->city;
            $resort_address = $response_body->hits->hits['0']->_source->contact->address;
            $resort_weather_icon_id = $response_body->hits->hits['0']->_source->conditions->combined->top->symbol->fnugg_id;
            $resort_wind = array(
                'mps' => $response_body->hits->hits['0']->_source->conditions->combined->top->wind->mps,
                'direction' => $response_body->hits->hits['0']->_source->conditions->combined->top->wind->degree,
                'speed' => $response_body->hits->hits['0']->_source->conditions->combined->top->wind->speed
            );
            $resort_temp = $response_body->hits->hits['0']->_source->conditions->combined->top->temperature->value;
            $resort_cond_description = $response_body->hits->hits['0']->_source->conditions->combined->top->condition_description;
            $resort_cond_updated_date_ISO = $response_body->hits->hits['0']->_source->conditions->combined->top->last_updated;
            $resort_cond_updated_date = date( 'd-m-Y - H:i', strtotime( $resort_cond_updated_date_ISO ) );
            $image_url = $response_body->hits->hits['0']->_source->images->image_16_9_s;
            $is_content = true;
        }

        $this->resort_all_info = [
            'resort_name'               => $resort_name,
            'resort_city'               => $resort_city,
            'resort_address'            => $resort_address,
            'resort_weather_icon_id'    => $resort_weather_icon_id,
            'resort_wind'               => $resort_wind,
            'resort_temp'               => $resort_temp,
            'resort_cond_description'   => $resort_cond_description,
            'resort_cond_updated_date'  => $resort_cond_updated_date,
            'image_url'                 => $image_url,
            'is_content'                => $is_content,
        ];

        // Store data for one day (24 hours X 3600 seconds in one hour)
        set_transient( $search_name, json_encode( $this->resort_all_info ), 3600 * 24 );
    }
}