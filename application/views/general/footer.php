<style>

.wde-pro
{
    opacity: 0.4;
}

.white-popup-block {
    display: inline-block;
    padding: 25px 40px !important;
}

.mfp-auto-cursor .mfp-content {
    text-align: left;
}

.mfp-wrap
{
	z-index: 100000;
	background: white;
	/*margin:25px 15px 15px 15px;
	padding:25px 15px 15px 15px;*/
    position: fixed;   /* Take it out of the flow of the document */
    left: 0;           /* Left edge at left for now */
    right: 0;          /* Right edge at right for now, so full width */ 
    top: 20px;         /* Move it down from top of window */
    width: 96%;      /* Give it the desired width */ 
	height: 96%;
    margin: auto;      /* Center it */
    max-width: 100%;   /* Make it fit window if under 500px */ 

}

.mfp-wrap #wpfooter
{
	display:none;
}

</style>

<script>

(function( $ ) {
	'use strict';

	$( window ).load(function() {
        $('.wde-pro, .wde-pro a, .wde-pro button, .wde-pro input').unbind();

        $('.wde-pro, .wde-pro a, .wde-pro button, .wde-pro input').on('click', bpdf_gopremium);
        
        $('.wde-pro, .wde-pro a, .wde-pro button, .wde-pro input').on('focus', function(){
            $(this).trigger('blur'); 
            return false;
        });
	});

    function bpdf_gopremium(){
        $.confirm({
            boxWidth: '400px',
            useBootstrap: false,
            title: '<?php wmvc_echo_js (__('Your version doesn\'t support this functionality, please upgrade', 'blocks-detector-finder'));?>',
            content: '<?php wmvc_echo_js (__('We constantly maintain compatibility and improving this plugin for living, please support us and purchase, we provide very reasonable prices and will always do our best to help you!','blocks-detector-finder'));?>',
            buttons: {
                cancel: function () {
                    return true;
                },
                somethingElse: {
                    text: 'Purchase Now',
                    btnClass: 'btn-blue',
                    keys: ['enter', 'shift'],
                    action: function(){

                        window.location = '<?php menu_page_url( 'bpdf-addons', true ); ?>';

                        return false;
                    }
                }
            }
        });

        return false;
    }

})( jQuery );

</script>

