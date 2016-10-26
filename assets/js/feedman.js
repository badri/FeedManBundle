
var fjq= null;
if (typeof jQuery != 'undefined') {  
   fjq = jQuery;
}else if(typeof $ == 'undefined'){
   fjq = $;
}
fjq(document).ready(function(){
	if(typeof selectedFeedNumber !='undefined' && selectedFeedNumber != null) {
		fjq("input[name='last_xx_no']").val(parseInt(selectedFeedNumber));
	}
	if(typeof selectedFeeds !='undefined' && selectedFeeds != null && selectedFeeds != '')  {
		selectedFeeds = selectedFeeds.split(',');
		fjq.each(selectedFeeds,function(i,f){
			fjq('#feed-'+f).prop('checked',true);
		});
		
	}
	fjq("input[name='form[send_feed_type]']").change(function(){
		fjq('.feedman-hide').hide();
		fjq('.'+fjq(this).val()).show();
	
	});
	if(typeof sendFeedType !='undefined' ){
		fjq("input[name='form[send_feed_type]'][value='"+sendFeedType+"']").prop("checked", true).trigger('change');
	}
});
/*
Mautic.feedman = function () {
	alert("teeded");
}*/