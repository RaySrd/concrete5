<?php defined('C5_EXECUTE') or die("Access Denied.");

$sh = Loader::helper('concrete/dashboard/sitemap');
?>

<?php if ($sh->canRead()) { ?>
<form action="<?=URL::to('/dashboard/sitemap/search')?>"  class="ccm-search-fields-none ccm-search-fields">
    <div class="form-group">
        <div class="ccm-search-main-lookup-field">
            <i class="fa fa-search"></i>
			<?=$form->search('cKeywords', array('placeholder' => t('Name')))?>
			<input type="hidden" name="submitSearch" value="1">
            <button type="submit" class="ccm-search-field-hidden-submit" tabindex="-1"><?=t('Search')?></button>
        </div>
        <ul class="ccm-search-form-advanced list-inline">
            <li><a href="<?=URL::to('/dashboard/sitemap/search')?>"><?=t('Advanced Search')?></a>
        </ul>
    </div>
</form>

<div class="ccm-dashboard-content-full">
<?php
$u = new User();
if ($u->isSuperUser()) {
    if (Queue::exists('copy_page')) {
        $q = Queue::get('copy_page');
        if ($q->count() > 0) { ?>
		<div class="alert alert-warning">
			<?=t('Page copy operations pending.')?>
			<button class="btn btn-xs btn-default pull-right" onclick="ConcreteSitemap.refreshCopyOperations()"><?=t('Resume Copy')?></button>
		</div>
	<?php }
    }
}
?>

<div id="ccm-full-sitemap-container"></div>
<hr/>

<section>
	<div class="checkbox">
    	<label>
    		<input type="checkbox" name="includeSystemPages" <?php if ($includeSystemPages) { ?>checked<?php } ?> value="1" />
    		<?=t('Include System Pages in Sitemap')?>
    	</label>
	</div>
</section>

</div>
<?php
} else {
?>
<p><?=t("You do not have access to the sitemap."); ?></p>
<?php
}
?>

<script>
$(function() {
    $('div#ccm-full-sitemap-container').concreteSitemap({
        siteTreeID: <?=$site->getSiteTreeID()?>,
        includeSystemPages: $('input[name=includeSystemPages]').is(':checked')
    });

    $('input[name=includeSystemPages]').on('click', function() {
        var $tree = $('div#ccm-full-sitemap-container');
        $tree.fancytree('destroy');
        $tree.concreteSitemap({
            siteTreeID: <?=$site->getSiteTreeID()?>,
            includeSystemPages: $('input[name=includeSystemPages]').is(':checked')
        });
    });
});
</script>
