<?php
if (!defined('CMS_INCLUDE')) {
	die('Direct access not allowed!');
}
?>
<head>
	<title><?= $website->Title; ?></title>
	<meta name="description" content="<?= $website->Description; ?>" />
	<meta name="keywords" content="<?= $website->Keywords; ?>" />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="Content-Style-Type" content="text/css" />
	<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-2">
	<meta name="robots" content="<?= $website->IndexFollowString; ?>" />
	<meta name="viewport" content="width=device-width" />
    <base href="<?= $website->GetFullURL(); ?>" />
	<link href="<?= $website->GetFullURL(); ?>favicon.ico" rel="icon" type="image/x-icon" />
	<link href="css/<?= CMS_CSS; ?>" rel="stylesheet" type="text/css" />
	<link href="css/jquery-validity.css" rel="stylesheet" type="text/css" />
	<link href="css/jquery-ui-1.8.24.custom.css" rel="stylesheet" type="text/css" />
	<link href="css/uploadify.css" rel="stylesheet" type="text/css" />
	<link href="js/lightbox/css/lightbox.css" rel="stylesheet" type="text/css" />
	<link href="css/cookiecuttr.css" rel="stylesheet" type="text/css" />
	<link href="css/import.css" rel="stylesheet" type="text/css" />
	<link href="css/style-tel.css" rel="stylesheet" type="text/css" />
	<link href="<?= $website->GetContactLink(); ?>" id="contact-link" />
	<script type="text/javascript" src="http://www.google.com/jsapi"></script>
	<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>
	<script type="text/javascript" src="js/jquery-ui-1.8.24.custom.min.js"></script>
	<script type="text/javascript" src="js/validity/jquery-validity-<?= $website->LanguageAbbr; ?>.js"></script>
	<script type="text/javascript" src="js/datepicker/jquery-ui-datepicker-<?= $website->LanguageAbbr; ?>.js"></script>
	<script type="text/javascript" src="js/tinymce/tiny_mce.js"></script>
	<script type="text/javascript" src="js/functions.js"></script>
	<script type="text/javascript" src="js/lightbox/js/lightbox.js"></script>
    <script type="text/javascript" src="js/pikachoose/lib/jquery.pikachoose.all.js"></script>
	<script type="text/javascript" src="js/jquery.cookie.js"></script>
	<script type="text/javascript" src="js/jquery.cookiecuttr.js"></script>
	<script type="text/javascript">
		jQuery(document).ready(function() {
			jQuery.cookieCuttr();
		});
    </script>
	<?php if ($website->HasUA()) { ?>
	<script type="text/javascript">
        if (jQuery.cookie('cc_cookie_accept') == "cc_cookie_accept") {
            var _gaq = _gaq || [];
            _gaq.push(['_setAccount', '<?= $website->UA; ?>']);
            _gaq.push(['_trackPageview']);
            (function() {
                var ga = document.createElement('script');
                ga.type = 'text/javascript';
                ga.async = true;
                ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
                var s = document.getElementsByTagName('script')[0];
                s.parentNode.insertBefore(ga, s);
            })();
        }
    </script>
    <?php
	}
	?>
</head>