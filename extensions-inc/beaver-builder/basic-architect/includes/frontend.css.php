<?php if(!empty($settings->blueprint_default) && $settings->blueprint_default !== 'none') { ?>
/* Architect Module - Blueprint: <?php echo $settings->blueprint_default;?>*/
<php var_dump($settings); ?>
.fl-node-<?php echo $id; ?> #pzarc-blueprint_<?php echo $settings->blueprint_default;?> .pzarc-panel_<?php echo $settings->blueprint_default;?> <?php echo $settings->entry_title; ?>

.fl-node-<?php echo $id; ?> #pzarc-blueprint_<?php echo $settings->blueprint_default;?> .pzarc-panel_<?php echo $settings->blueprint_default;?> <?php echo $settings->entry_title_a; ?>

<?php } ?>

<?php if(!empty($settings->blueprint_tablet) && $settings->blueprint_tablet !==  'none') { ?>
  /* Architect Module - Blueprint: <?php echo $settings->blueprint_tablet;?>*/

.fl-node-<?php echo $id; ?> #pzarc-blueprint_<?php echo $settings->blueprint_tablet;?> .pzarc-panel_<?php echo $settings->blueprint_tablet;?> <?php echo $settings->entry_title; ?>

.fl-node-<?php echo $id; ?> #pzarc-blueprint_<?php echo $settings->blueprint_tablet;?> .pzarc-panel_<?php echo $settings->blueprint_tablet;?> <?php echo $settings->entry_title_a; ?>

<?php } ?>

<?php if(!empty($settings->blueprint_phone) && $settings->blueprint_phone !== 'none') { ?>
  /* Architect Module - Blueprint: <?php echo $settings->blueprint_phone;?>*/

.fl-node-<?php echo $id; ?> #pzarc-blueprint_<?php echo $settings->blueprint_phone;?> .pzarc-panel_<?php echo $settings->blueprint_phone;?> <?php echo $settings->entry_title; ?>

.fl-node-<?php echo $id; ?> #pzarc-blueprint_<?php echo $settings->blueprint_phone;?> .pzarc-panel_<?php echo $settings->blueprint_phone;?> <?php echo $settings->entry_title_a; ?>

<?php } ?>
