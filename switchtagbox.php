<div class="switch-tag-box">
	<?php $switchtags = array('ettv'=>'ETTV', 'ethd'=>'EtHD', 'etrg'=>'ETRG');?>
	<?php foreach ($switchtags as $switchtag=>$switchtagC): ?>
		<a class="switch-tag-button <?=isset($tag)&&$tag==$switchtag?'current-tag':''?>" data-tag="<?=$switchtag?>" href="/search/<?=$switchtag?>/<?=isset($search)?$search.'/':''?>"><?=$switchtagC?></a>
	<?php endforeach; ?>
	<?php if (!isset($search)): ?>
		<script type="text/javascript">
			$('.switch-tag-button').unbind('click').click(function() {
				$(this).parent().find('.current-tag').removeClass('current-tag');
				$(this).addClass('current-tag');
				$('#search-tag').val($(this).attr('data-tag'));
				return false;
			});
		</script>
	<?php endif;?>
</div>