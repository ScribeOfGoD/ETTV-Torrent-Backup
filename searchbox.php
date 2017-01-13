<div class="search-sect">
    <form id="search-form" action="/search/" method="get">
    	<input type="hidden" id="search-tag" name="tag" value="<?=isset($tag)?$tag:''?>">
        <input placeholder="I want to watch..." value="<?= isset($search) ? htmlspecialchars($search) : '' ?>"
               autocomplete="off" id="search-q" name="q" type="text" class="hover-bottom big-search"/>
    </form>
    <script type="text/javascript">
    	$('#search-form').submit(function() {
    		location.href = (!$('#search-tag').val() || !$('#search-q').val()) ? '/' : '/search/'+encodeURIComponent($('#search-tag').val())+'/'+encodeURIComponent($('#search-q').val())+'/';
    		return false;
    	});
    </script>
    <?php include('./switchtagbox.php'); ?>
</div>