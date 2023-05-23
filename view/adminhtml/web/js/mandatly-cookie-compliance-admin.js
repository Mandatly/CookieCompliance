require(['jquery', 'jquery/ui'], function( $ ) {
	//'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

	$(document).ready(function () {
		if($('#server_mode').is(':checked')) {
			$('.mcc_live_content').hide();
			$('.mcc_demo_content').show();
		} else {
			$('.mcc_live_content').show();
			$('.mcc_demo_content').hide();
		}	
	$("#activation-notice").hide();
	$('#wpbody-content').children().filter(':not(#mdt-settings-area)').hide();
	$("#dismiss").click(function () {
		$("#activation-notice").hide();
	});
	var url = $("#cookie").find('input[name="url"]').val();
	$("#cookie").submit(function (event) {
		event.preventDefault();
		var save_banner_slider = '';
		$("#activation-notice").hide();
		if($('#slider-status').is(':checked')) {    
			save_banner_slider = 'true';
		} else {
			save_banner_slider = 'false';
		}
		var save_gid = $("#cookie").find('input[name="gid"]').val();
		var save_activated_msg = $('.activate_msg').html();
		var save_deactivated_msg = $('.deactivate_msg').html();
		$.ajax({
			url: url,
			data:   {
				'action': 'save_banner_cookie_settings',
				'banner_guid': save_gid,
				'slider_status' : save_banner_slider,
			},
			showLoader: true,
			type: 'POST',
			async: true,
                cache: false,
			success:function(response) {
				if (response.message == 'not_validate_guid') {
					$('.not_exists_msg').addClass('mcc_hide');
					$('.not_exists_msg').removeClass('mcc_show');
					$('.not_validated_msg').addClass('mcc_show');
					$('.not_validated_msg').removeClass('mcc_hide');
					$('#gidbox').addClass('error-input');

				} else if (response.message == 'not_exists_guid') {
					$('.not_validated_msg').addClass('mcc_hide');
					$('.not_validated_msg').removeClass('mcc_show');
					$('.not_exists_msg').addClass('mcc_show');
					$('.not_exists_msg').removeClass('mcc_hide');
					$('#gidbox').addClass('error-input');
				} else {
					$('.not_exists_msg').addClass('mcc_hide');
					$('.not_exists_msg').removeClass('mcc_show');
					$('.not_validated_msg').addClass('mcc_hide');
					$('.not_validated_msg').removeClass('mcc_show');
					$('#gidbox').removeClass('error-input');
					$("#activation-notice").show();
					if(save_banner_slider == 'true'){
						$("#msg-notice").html(save_activated_msg);
					}
					else if(save_banner_slider == 'false'){
						$("#msg-notice").html(save_deactivated_msg);
					}
					else {
						$("#msg-notice").html('something went wrong');
					}
				}
			},
			error: function() {
				// Display error message
				$('#msg-notice').html('An error occurred while processing your AJAX request.');
			},
		});
	});

	$("#slider-status").click(function () {
		$("#activation-notice").hide();
		if ($('#slider-status').is(':checked')) {
			var chk_banner_slider = 'true';
		} else {
			var chk_banner_slider = 'false';
		} if($('#server_mode').is(':checked')) {
			var server_mode_val = 'true';
			var activated_msg = $('.activate_msg_demo_opt').html();
			var deactivated_msg = $('.deactivate_msg_demo_opt').html();
		} else {
			var server_mode_val = 'false';
			var activated_msg = $('.activate_msg_live_opt').html();
			var deactivated_msg = $('.deactivate_msg_live_opt').html();
		}

		$.ajax({
			url: url,
			data:   {
				'action': 'banner_slider_status',
				'slider_status': chk_banner_slider,
				'server_mode' : server_mode_val,
			},
			type: 'POST',
			async: true,
                cache: false,
			success:function(response) {
				if(chk_banner_slider == 'true'){
					if($("#gidbox").val() != '' || server_mode_val == 'true'){
						$("#activation-notice").show();
						$("#msg-notice").html(activated_msg);
					}
					$("#slider-status").prop("checked", true);
				} else{
					if($("#gidbox").val() != '' || server_mode_val == 'true'){
						$("#activation-notice").show();
						$("#msg-notice").html(deactivated_msg);
					}
					$("#slider-status").removeAttr('checked');
				}
			},
			error:function(){
				$("#msg-notice").html('something went wrong on wordpress..');
			}
		});
	});

	$("#server_mode").on('click', function () {
		$("#activation-notice").hide();
		if($('#slider-status').is(':checked')) {
			var chk_banner_slider = 'true';
		} else {
			var chk_banner_slider = 'false';
		}
		if($('#server_mode').is(':checked')) {
			var server_mode = 'true';
			$('.mcc_live_content').hide();
			$('.mcc_demo_content').show();
			var activated_msg = $('.activate_msg_demo_opt').html();
			var deactivated_msg = $('.deactivate_msg_demo_opt').html();
		} else {
			var server_mode = 'false';
			$('.mcc_live_content').show();
			$('.mcc_demo_content').hide();
			var activated_msg = $('.activate_msg_live_opt').html();
			var deactivated_msg = $('.deactivate_msg_live_opt').html();
		}
		$.ajax({
			url: url,
			data:   {
				'action': 'save_server_mode',
				'mcc_server_mode': server_mode,
			},
			type: 'POST',
			async: true,
                cache: false,
			success:function(response) {
				if (chk_banner_slider == 'true') {
					if($("#gidbox").val() != '' || server_mode == 'true' ) {
						$("#activation-notice").show();
						$("#msg-notice").html(activated_msg);
					}
				} else {
					if ($("#gidbox").val() != '' || server_mode == 'true') {
						$("#activation-notice").show();
						$("#msg-notice").html(deactivated_msg);
					}
				}
			}
		});
	});

	
});
});