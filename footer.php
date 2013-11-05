<?
if(!defined('CMS_INCLUDE')) { die('Direct access not allowed!'); }
?>
<div id="footer">
    <?= widget('Custom:7', 'footer-copyright', true); ?>
    <?= widget('Social', 'social-networks', true, array(false)); ?> 
    <?= widget('Custom:9', 'footer-column-3', true); ?>
    <div id="footer-admin">
		<?= $user->LoginHtml(); ?>
    </div>
    <div class="clear"></div>
</div>