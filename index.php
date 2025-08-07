<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

  <div id="app">
    <div id="vue-app"></div>
  </div>


  <?php wp_footer(); ?>
  <script type="module" src="<?= demo_get_vite_asset_uri(); ?>"></script>
</body>

</html>