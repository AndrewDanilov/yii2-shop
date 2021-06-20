$(function () {
	var attirbute_form = $('.shop-attribute-form');
	var property_type = attirbute_form.find('[name="Property[type]"]')
	var is_filtered = attirbute_form.find('[type="checkbox"][name="Property[is_filtered]"]');
	function check_filter_type_field_block_visibility() {
		var property_type_value = property_type.val();
		var is_filtered_value = is_filtered.prop('checked');
		var filter_type_field_block = attirbute_form.find('.field-property-filter_type');
		if (is_filtered_value && (property_type_value == 'string' || property_type_value == 'integer')) {
			filter_type_field_block.show();
		} else {
			filter_type_field_block.hide();
			filter_type_field_block.find('[name="Property[filter_type]"]').val('checkboxes');
		}
	}
	property_type.change(check_filter_type_field_block_visibility);
	is_filtered.change(check_filter_type_field_block_visibility);
	check_filter_type_field_block_visibility();
});