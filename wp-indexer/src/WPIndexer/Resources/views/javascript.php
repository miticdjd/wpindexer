<script type="text/javascript" >
jQuery(document).ready(function($) {

	var wipeData = {
		action: 'wp_indexer_wipe_data'
	};
        
        var reIndexData = {
            action: 'wp_indexer_re_index_data'
        };
        
        $('#wipe-data').click(function(){
            beforceClick('wipe-data');
            $.post(ajaxurl, wipeData, function(response) {
                if (response.status === 200) {
                    /* Everything is good, display success message */
                    showSuccessMsg(response.msg);
                } else {
                    /* Ooops, there was some problem... display error message */
                    showErrorMsg(response.msg);
                }
                afterClick();
            }, 'json');
        });
        
        $('#re-index-data').click(function(){
            beforceClick('re-index');
            $.post(ajaxurl, reIndexData, function(response) {
                if (response.status === 200) {
                    /* Everything is good, display success message */
                    showSuccessMsg(response.msg);
                } else {
                    /* Ooops, there was some problem... display error message */
                    showErrorMsg(response.msg);
                }
                afterClick();
            }, 'json');
        });
        
        function showSuccessMsg(msg) {
            $('#wpindexer-manage-index-success-massage').find('strong').html(msg);
            $('#wpindexer-manage-index-success-massage').fadeIn();
        }
        
        function showErrorMsg(msg) {
            $('#wpindexer-manage-index-error-massage').find('strong').html(msg);
            $('#wpindexer-manage-index-error-massage').fadeIn();
        }
        
        /**
         * Remove all messages before we click on
         */
        function beforceClick(type) {
            if (type === 're-index') {
                $('.re-index-data.spinner').show();
            } else {
                $('.wipe-data.spinner').show();
            }
            
            $('#wpindexer-manage-index-success-massage').hide();
            $('#wpindexer-manage-index-error-massage').hide();
        }
        
        /**
         * Turn off spinner
         */
        function afterClick() {
            $('.spinner').hide();
        }
});
</script>