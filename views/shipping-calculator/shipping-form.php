<?php defined( 'ABSPATH' ) || exit; ?>  

<div id="tpsm-shipping-calculator-location-form">
    <p>
        <!-- <label for="">Country</label> -->
        <select name="country" id="custom_country" class="country_to_state country_select">
            <?php
            $countries = WC()->countries->get_countries();
            foreach ($countries as $code => $name) {
                $selected = $code === 'BD' ? 'selected' : '';
                echo "<option value='$code' $selected>$name</option>";
            }
            ?>
        </select>
    </p>
    <p><input type="text" id="custom_city" placeholder="State"></p>
    <p><input type="text" id="custom_city" placeholder="City"></p>
    <p><input type="text" id="custom_postcode" placeholder="Postcode"></p>
    <button type="button" id="save_location_btn">Update Location</button>
</div>
<div id="shipping-results"></div>