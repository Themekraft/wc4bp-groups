/*global wc_enhanced_select_params, wc4bp_groups */
jQuery(function ($) {
	function getEnhancedSelectFormatString() {
		var formatString = {
			formatMatches: function (matches) {
				if (1 === matches) {
					return wc_enhanced_select_params.i18n_matches_1;
				}

				return wc_enhanced_select_params.i18n_matches_n.replace('%qty%', matches);
			},
			formatNoMatches: function () {
				return wc_enhanced_select_params.i18n_no_matches;
			},
			formatAjaxError: function () {
				return wc_enhanced_select_params.i18n_ajax_error;
			},
			formatInputTooShort: function (input, min) {
				var number = min - input.length;

				if (1 === number) {
					return wc_enhanced_select_params.i18n_input_too_short_1;
				}

				return wc_enhanced_select_params.i18n_input_too_short_n.replace('%qty%', number);
			},
			formatInputTooLong: function (input, max) {
				var number = input.length - max;

				if (1 === number) {
					return wc_enhanced_select_params.i18n_input_too_long_1;
				}

				return wc_enhanced_select_params.i18n_input_too_long_n.replace('%qty%', number);
			},
			formatSelectionTooBig: function (limit) {
				if (1 === limit) {
					return wc_enhanced_select_params.i18n_selection_too_long_1;
				}

				return wc_enhanced_select_params.i18n_selection_too_long_n.replace('%qty%', limit);
			},
			formatLoadMore: function () {
				return wc_enhanced_select_params.i18n_load_more;
			},
			formatSearching: function () {
				return wc_enhanced_select_params.i18n_searching;
			}
		};

		return formatString;
	}

	$(document.body).on('wc-enhanced-select-init', function () {
		$(':button.add_groups').filter(':not(.enhanced)').each(function () {
			$(this).click(function () {
				//Add the groups
			});
			$(this).addClass('enhanced');
		});
		// Ajax product search box
		$(':input.wc4bp-group-search').filter(':not(.enhanced)').each(function () {
			var select2_args = {
				allowClear: $(this).data('allow_clear') ? true : false,
				placeholder: $(this).data('placeholder'),
				minimumInputLength: $(this).data('minimum_input_length') ? $(this).data('minimum_input_length') : '3',
				escapeMarkup: function (m) {
					return m;
				},
				ajax: {
					url: wc4bp_groups.ajax_url,
					dataType: 'json',
					quietMillis: 250,
					data: function (term) {
						return {
							term: term,
							action: $(this).data('action'),
							security: wc4bp_groups.search_groups_nonce,
							exclude: $(this).data('exclude'),
							include: $(this).data('include'),
							limit: $(this).data('limit')
						};
					},
					results: function (data) {
						var terms = [];
						if (data) {
							$.each(data, function (id, text) {
								terms.push({id: id, text: text});
							});
						}
						return {
							results: terms
						};
					},
					cache: true
				}
			};

			if ($(this).data('multiple') === true) {
				select2_args.multiple = true;
				select2_args.initSelection = function (element, callback) {
					var data = $.parseJSON(element.attr('data-selected'));
					var selected = [];

					$(element.val().split(',')).each(function (i, val) {
						selected.push({
							id: val,
							text: data[val]
						});
					});
					return callback(selected);
				};
				select2_args.formatSelection = function (data) {
					return '<div class="selected-option" data-id="' + data.id + '">' + data.text + '</div>';
				};
			} else {
				select2_args.multiple = false;
				select2_args.initSelection = function (element, callback) {
					var data = {
						id: element.val(),
						text: element.attr('data-selected')
					};
					return callback(data);
				};
			}

			select2_args = $.extend(select2_args, getEnhancedSelectFormatString());

			$(this).select2(select2_args).addClass('enhanced');
		});
	});


	function wc4bp_add_groups() {

		function save_groups(e) {
			var form = jQuery(this);
			if (form) {
				var json_handler = jQuery('#_wc4bp_groups_json');
				var groups = jQuery('.wc4bp-group-item').map(function (i, v) {
					var member_type = jQuery('#_membership_level', this),
						optional = jQuery('#_membership_optional', this);
					return {
						'group_id': jQuery(this).attr('group_id'),
						'group_name': jQuery(this).attr('group_name'),
						'member_type': member_type.val(),
						'is_optional': optional.val()
					}
				}).get();

				var json = JSON.stringify(groups);
				json_handler.val(json);
			}
		}

		function add_group() {
			var searched = jQuery('#wc4bp-group-ids').select2('data');
			if (searched) {
				var group_item = jQuery('.wc4bp-group-item'),
					inserted = false;
				var existing = group_item.map(function (i, v) {
					jQuery(this).removeClass('wc4bp-group-error');
					return jQuery(this).attr('group_id');
				}).get();
				$.each(searched, function (index, value) {
					var exist = false;
					$.each(existing, function (i, v) {
						if (value['id'] == v) {
							exist = true;
							jQuery('#wc4bp_item_' + v).addClass('wc4bp-group-error');
							return false;
						}
					});
					if (!exist) {
						insert_container(value);
					}
				});
			}
			else {
				alert('Need to select some groups to add');
			}
		}

		function insert_container(item) {
			var container = jQuery('.wc4bp-group-container');
			jQuery(".wc4bp-group-loading").attr('style', 'display:inline-block');
			jQuery.post(wc4bp_groups.ajax_url, {
				'action': 'wc4bp_get_group_view',
				'group': item,
				'security': wc4bp_groups.search_groups_nonce
			}, function (data) {
				if (data || data == '0') {
					container.append(data);
				}
				else {
					alert(wc4bp_groups.general_error);
				}
			}).fail(function () {
				alert(wc4bp_groups.general_error);
			}).always(function () {
				jQuery(".wc4bp-group-loading").attr('style', 'display:none');
			});
		}

		function clean_error(e) {
			jQuery(this).removeClass('wc4bp-group-error');
		}

		function remove_item(e) {
			e.preventDefault();
			var group_id = jQuery(this).attr('group_id');
			jQuery('#wc4bp_item_' + group_id).remove();
		}

		return {
			init: function () {
				if (document.getElementById('post') !== null) {
					// Bind event handlers for form Settings page
					add_groups_var.formActionsInit();
				}
			},

			formActionsInit: function () {
				var form = jQuery('#post'),
					items_container = jQuery('.wc4bp-group-container');
				form.on('submit', save_groups);
				jQuery('.add_groups').click(add_group);
				items_container.on('click', '.wc4bp-group-item', clean_error);
				items_container.on('click', '.wc4bp-group-group-remove', remove_item);
			}
		};
	}

	var add_groups_var = wc4bp_add_groups();
	jQuery(document).ready(function ($) {
		add_groups_var.init();
	});
});
