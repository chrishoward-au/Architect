<?php

/**
 * This file should be used to render each module instance.
 * You have access to two variables in this file: 
 * 
 * $module An instance of your module class.
 * $settings The module's settings.
 *
 * Example: 
 */

?>
<div class="fl-example-text">
    <?php
//      var_dump($settings);
      // pzarchitect($pzarc_blueprint = null, $pzarc_overrides = null, $tablet_bp = null, $phone_bp = null)
        pzarchitect($settings->blueprint_default, null,$settings->blueprint_tablet,$settings->blueprint_phone);
    ?>
</div>