<?
if(!defined('CMS_INCLUDE')) { die('Direct access not allowed!'); }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?= $website->LanguageAbbr; ?>" lang="<?= $website->LanguageAbbr; ?>">
<?
define('CMS_CSS', 'style.css');
include("header.php");
?>
<body id="page">
	<?
	if($website->Share == 1) {
	?>
    <div id="fb-root"></div>
	<script>
		(function(d, s, id) {
		var js, fjs = d.getElementsByTagName(s)[0];
		if (d.getElementById(id)) return;
		js = d.createElement(s); js.id = id;
		js.src = "//connect.facebook.net/<?= $website->Locale; ?>/all.js#xfbml=1";
		fjs.parentNode.insertBefore(js, fjs);
		}(document, 'script', 'facebook-jssdk'));
    </script>
    <?
	}
	?>
	<a id="<?= _("top"); ?>"></a>
        <div id="header">

            <div id="header-center">
                <div id="logo">
                    <?= $website->GetHtmlLogo(); ?>
                </div>
                <div id="menu-top">
                    <?= widget('Custom:3', 'sistem-menu', true, array(false)); ?>
                    <?= widget('Search', 'search-wrapper', false); ?>
                </div>
            </div>

            <div id="menu-bg">
                <div id="menu">

                <?= $website->Menu->GetHtml();?>

                </div>
            </div>

        </div>

        <div id="main">
           <div id="content">
            <?
            include("include/templates/" . $page->GetTemplateLink() . ".php");
            ?>
            </div>
        </div>

        <div class="clear"></div>

    <div id="footer-wrap">
    <?
    	include("footer.php");
    ?>
	<? if($website->Share == 1) {
		?>
		<script>
		!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src='//platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document,'script','twitter-wjs');
		</script>
		<script type="text/javascript">
		  (function() {
			var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
			po.src = 'https://apis.google.com/js/plusone.js';
			var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
		  })();
		</script>
		<?
		}
        ?>
    </div>
    <script type="text/javascript" src="js/tinymce-init.js"></script>
    <script type="text/javascript" src="js/html-processing.js"></script>
</body>
</html>