<?php
    use Ilia\Fnugg_Resort\Core;
    $resort_data = Core::instance()->fetch('Connector')->resort_all_info;
?>
<div class="fnugg-block">
    <?php if( $resort_data['is_content'] ) : ?>
    <div class="fnugg-block--header">
        <div class="fnugg-resort-title">
            <?php echo $resort_data['resort_name'] ?>
        </div>
        <div class="fnugg-main-img--wrapp">
            <img src="<?php echo $resort_data['image_url'] ?>" alt="" class="fnugg-main-img">
            <div class="fnugg-place--wrapp">
                <span class="fnugg-place-title">
                    <?php echo $resort_data['resort_city'] ?>, <?php echo $resort_data['resort_address'] ?>
                </span>
                <span class="fnugg-open-date">
                    Oppdatern: <?php echo $resort_data['resort_cond_updated_date'] ?>
                </span>
            </div>
        </div>
    </div>
    <div class="fnugg-resort-info--wrapp">
        <div class="fnugg-weather--wrapp">
            <img src="<?php echo WP_FNUGGRESORT_ASSETS_URL . '/img/resort-weather-blue-' . $resort_data['resort_weather_icon_id'] . '.svg' ?>" alt="" class="fnugg-weather-icon">
            <span class="fnugg-weather-name"><?php echo $this->weather_array[$resort_data['resort_weather_icon_id']] ?></span>
        </div>
        <div class="fnugg-temperature--wrapp">
            <span class="fnugg-temperature"><?php echo $resort_data['resort_temp'] ?></span>&#xb0;
        </div>
        <div class="fnugg-wind--wrapp">
            <div class="fnugg-wind-attributes--wrapp">
                <img src="<?php echo WP_FNUGGRESORT_ASSETS_URL . '/img/wind-pointer.svg' ?>" alt="" 
                    class="fnugg-weather-direction" style="transform:rotate(<?php echo $resort_data['resort_wind']['direction'] ?>deg)">
                <span class="fnugg-wind-mps"><?php echo $resort_data['resort_wind']['mps'] ?></span> m/s;
            </div>
            <span class="fnugg-wind-speed"><?php echo $resort_data['resort_wind']['speed'] ?></span>
        </div>
        <div class="fnugg-snow-type-wrapp">
            <img src="<?php echo WP_FNUGGRESORT_ASSETS_URL . '/img/snow-type.svg' ?>" alt="" class="fnugg-snow-type-icon">
            <span class="fnugg-snow-type-name"><?php echo $resort_data['resort_cond_description'] ?></span>
        </div>
    </div>
    <?php else : ?>
        <h4 style="margin: auto;">Nothing here yet :(</h4>
    <?php endif ?>
</div>