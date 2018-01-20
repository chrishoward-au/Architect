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
  <div class="arc-bb-any-field">
    <?php echo ArcFun::render_any_field((array)$settings);?>
  </div>
