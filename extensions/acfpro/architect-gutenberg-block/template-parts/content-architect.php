<?php
  /**
   * Block Name: Architect
   *
   * This is the template that displays the Architect Blueprint block.
   */


  if ( empty( get_field( 'default_blueprint' ) ) ) {
    if ( is_admin() ) {
      ?>
      <style>
        .arc-in-editor-block-wrapper {
          font-family: Helvetica, Arial, sans-serif;
          border: solid 1px #6c9698;
          padding: 1rem;
          background-color: #dff2f3;
          color: #555;
          border-radius: 3px;
        }
      </style>
      <div id="arc-in-editor-block-wrapper-<?php echo $block['id']; ?>">
      <div class="arc-acf-gutenberg-block-in-editor">
      <p><strong>Please select a default Blueprint</strong></p>
      <?php
    } else {
      ArcFun::message( 'none' );
    }

    return;
  }
  // create id attribute for specific styling
  $id            = 'architect-blueprint-' . $block['id'];
  $arc_blueprint = pzarc_render_block( $block );

  // create align class ("alignwide") from block setting ("wide")

?>
<div id="<?php echo $id; ?>" class="architect-blueprint-acf-block">
<?php

if ( function_exists( 'pzarc' ) ) {
  pzarc( $arc_blueprint['default']['shortname'], $arc_blueprint['overrides'], 'acf-gutenberg-block', NULL, $arc_blueprint['extra_overrides'], $arc_blueprint['tablet']['shortname'], $arc_blueprint['phone']['shortname'] );
} else {
  $arc_layout_imgs = ArcFun::get_layout_images();

  $arc_source_mismatch_tablet= $arc_blueprint['tablet']['source'] && $arc_blueprint['tablet']['source'] != $arc_blueprint['default']['source']?'arc-source-mismatch':'';
  $arc_source_mismatch_phone = $arc_blueprint['phone']['source'] && $arc_blueprint['phone']['source'] != $arc_blueprint['default']['source']?'arc-source-mismatch':'';

  ?>
  <div id="arc-in-editor-block-wrapper-<?php echo $block['id']; ?>" class="arc-in-editor-block-wrapper">
    <p class="arc-heading">Architect Blueprint</p>
    <div class="arc-acf-gutenberg-block-in-editor">
      <?php if ( $arc_blueprint['default'] ) { ?><p><span class="dashicons dashicons-desktop"></span></p><?php } ?>
      <?php if ( $arc_blueprint['default']['type'] ) { ?><img src="<?php echo $arc_layout_imgs[ $arc_blueprint['default']['type'] ]['img']; ?>" width="40"
                                                              alt="<?php echo $arc_blueprint['default']['type']; ?>"/><?php } ?>
      <div>
        <?php if ( $arc_blueprint['default']['title'] ) { ?><p><?php echo $arc_blueprint['default']['title']; ?></p><?php } ?>
        <?php if ( $arc_blueprint['default'] ) { ?><p class="arc-acf-block-source"><strong>Source: </strong> <?php echo ucfirst( $arc_blueprint['default']['source'] ); ?></p><?php } ?>
      </div>
      <?php if ( $arc_blueprint['tablet'] ) { ?><p><span class="dashicons dashicons-tablet"></span></p><?php } ?>
      <?php if ( $arc_blueprint['tablet']['type'] ) { ?><img src="<?php echo $arc_layout_imgs[ $arc_blueprint['tablet']['type'] ]['img']; ?>" width="40"
                                                             alt="<?php echo $arc_blueprint['tablet']['type']; ?>"/><?php } ?>
      <div>
        <?php if ( $arc_blueprint['tablet']['title'] ) { ?><p><?php echo $arc_blueprint['tablet']['title']; ?></p><?php } ?>
        <?php if ( $arc_blueprint['tablet'] ) { ?><p class="arc-acf-block-source <?php echo $arc_source_mismatch_tablet?>"><strong>Source: </strong> <?php echo ucfirst( $arc_blueprint['tablet']['source'] ); ?><?php echo !empty($arc_source_mismatch_tablet)?__(' (mismatched)','pzarchitect'):''?></p><?php } ?>
      </div>

      <?php if ( $arc_blueprint['phone'] ) { ?><p><span class="dashicons dashicons-smartphone"></span></p><?php } ?>
      <?php if ( $arc_blueprint['phone']['type'] ) { ?><img src="<?php echo $arc_layout_imgs[ $arc_blueprint['phone']['type'] ]['img']; ?>" width="40"
                                                            alt="<?php echo $arc_blueprint['phone']['type']; ?>"/><?php } ?>
      <div>
        <?php if ( $arc_blueprint['phone']['title'] ) { ?><p><?php echo $arc_blueprint['phone']['title']; ?></p><?php } ?>
        <?php if ( $arc_blueprint['phone'] ) { ?><p class="arc-acf-block-source <?php echo $arc_source_mismatch_phone?>"><strong>Source: </strong> <?php echo ucfirst( $arc_blueprint['phone']['source'] ); ?><?php echo !empty($arc_source_mismatch_phone)?__(' (mismatched)','pzarchitect'):''?></p><?php } ?>
      </div>
    </div>
  </div>
  <!--            <p style="line-height:1.2;margin:0;padding:0;font-size:11px;font-style: italic;">Blueprints can be too complex to display correctly in the post editor</p>-->
  </div>


  <?php
}

?></div><?php
