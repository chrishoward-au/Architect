jQuery(document).ready(function(){var e=tl.pg.init({steps_element:"#tlyPageGuide",auto_refresh:!0,pg_caption:'<span class="title">Architect Guide</span>',refresh_interval:250}),t=jQuery.cookie("architect_welcome");"undefined"==typeof t&&(e.open(),jQuery.cookie("architect_welcome","seen",9999))});