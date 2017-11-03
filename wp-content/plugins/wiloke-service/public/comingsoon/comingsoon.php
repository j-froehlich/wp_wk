<!DOCTYPE html>
<!--[if IE 7 ]> <html class="ie ie7"> <![endif]-->
<!--[if IE 8 ]> <html class="ie ie8"> <![endif]-->
<!--[if IE 9 ]> <html class="ie ie9"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <html class="" lang="en"> <!--<![endif]-->
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="format-detection" content="telephone=no">
    <meta name="apple-mobile-web-app-capable" content="yes">

    <!-- GOOGLE FONT -->
    <link href='http://fonts.googleapis.com/css?family=Raleway:700,900,400' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Droid+Serif:400,400italic' rel='stylesheet' type='text/css'>
    <title><?php echo esc_html(get_option('blogname')); ?></title>
    <?php do_action('wiloke_comingsoon_head'); ?>
</head>
<body>
    <div id="page-wrap">
        <section class="page-comingsoon" style="background-image: url(<?php echo esc_url($this->aData['background']); ?>)">
            <div class="container">
                <div class="page-content">
                    <div class="tb">
                        <div class="tb-cell">
                            <h4 class="text-uppercase text-center"><?php echo esc_html($this->aData['heading']); ?></h4>
                            <div id="countdown"></div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <?php do_action('wiloke_comingsoon_footer'); ?>
</body>
</html>