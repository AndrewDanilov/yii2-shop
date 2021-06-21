$(function() {
	$('.btn-add-to-cart').click(function (event) {
		event.preventDefault();
		var product_id = $(this).attr('data-product-id');
		$.ajax({
			url: '/shop-api/cart-add',
			type: 'post',
			dataType: 'json',
			data: {
				product_id: product_id
			}
		}).done(function (data) {
			if (data !== null) {
				if (data.success) {
					updateCart(data.cart);
					showCartModal(product_id, data.cart);
				} else if (data.error) {
					modalConfirm(data.error);
				} else {
					modalConfirm(shop.messages.errorAddToCart);
				}
			} else {
				modalConfirm(shop.messages.errorAddToCart);
			}
		});
		return;
	});

	var focusedElement;
	$('.cart-table').on('focus', 'input[type="number"]', function () {
		if (focusedElement === this) return; //already focused, return so user can now place cursor at specific point in input.
		focusedElement = this;
		setTimeout(function () { focusedElement.select(); }, 100); //select all text in any field on focus for easy re-entry. Delay sightly to allow focus to "stick" before selecting.
	}).on('blur', 'input[type="number"]', function () {
		var count = $(this).val();
		var position_id = $(this).parents('tr').attr('data-position-id');
		$.ajax({
			url: '/shop-api/cart-update-count',
			type: 'post',
			dataType: 'json',
			data: {
				position_id: position_id,
				count: count
			}
		}).done(function (data) {
			if (data !== null && data.success) {
				updateCart(data.cart);
			}
		});
	}).on('click', '.cart-remove-position', function () {
		var cartItem = $(this).parents('tr');
		modalConfirm(shop.messages.questionRemoveProductFromCart, function () {
			var position_id = cartItem.attr('data-position-id');
			$.ajax({
				url: '/shop-api/cart-remove',
				type: 'post',
				dataType: 'json',
				data: {
					position_id: position_id
				}
			}).done(function (data) {
				if (data !== null && data.success) {
					updateCart(data.cart);
				}
			});
		});
	});

	$('.btn-checkout').click(function () {
		var client_data = $('.client-data');
		var client_name = client_data.find('input[name="client_name"]').val();
		var client_phone = client_data.find('input[name="client_phone"]').val();
		var client_address = client_data.find('input[name="client_address"]').val();
		if (!client_name || !client_phone) {
			modalConfirm(shop.messages.errorEmptyFormFields);
			return;
		}
		$.ajax({
			url: '/shop-api/send-order',
			type: 'post',
			dataType: 'json',
			data: {
				client_name: client_name,
				client_phone: client_phone,
				client_address: client_address
			}
		}).done(function (data) {
			if (data && data.success) {
				location.href = '/checkout/send-order-success';
			} else {
				location.href = '/checkout/send-order-error';
			}
		}).fail(function () {
			location.href = '/checkout/send-order-error';
		});
	});
});

function updateCart(cart) {
	var cart_rows_wrapper = $('.cart-table tbody');
	var cart_rows = cart_rows_wrapper.find('tr');
	$.each(cart.items, function(position_id, item) {
		var row = cart_rows.filter('[data-position-id="' + position_id + '"]');
		if (row.length) {
			// update
			row.find('.price').text(item['product']['priceFormated']);
			row.find('.count input').val(item['count']);
			row.find('.total-price').text(item['product']['totalPriceFormated']);
		} else {
			// insert
			var row_tpl = '<tr data-position-id="">\n\t<td class="image"><img height="50" src="" alt=""/></td>\n\t<td class="title"></td>\n\t<td class="price"></td>\n\t<td class="count"><input type="number" value=""/></td>\n\t<td class="total-price"></td>\n\t<td><a href="javascript:;" class="cart-remove-position">' + shop.messages.removeBtn + '</a></td>\n</tr>';
			row = $(row_tpl);
			row.attr('data-position-id', position_id);
			row.find('.image img').attr('src', item['product']['images'][0]);
			row.find('.title').html(item['product']['name']);
			row.find('.price').text(item['product']['priceFormated']);
			row.find('.count input').val(item['count']);
			row.find('.total-price').text(item['product']['totalPriceFormated']);
			cart_rows_wrapper.append(row);
		}
	});
	cart_rows = cart_rows_wrapper.find('tr');
	cart_rows.each(function() {
		var row = $(this);
		var position_id = row.attr('data-position-id');
		if (!cart.items.hasOwnProperty(position_id)) {
			row.remove();
		}
	});
	$('.cart-table .total-summ').text(cart.totalSumFormated);
	$('.mini-cart .indicator').text(cart.totalCount);
	if (cart.totalCount == 0) {
		location.href = location.href;
	}
}

function showCartModal(product_id, cart) {
	var product;
	$.each(cart.items, function(key, item) {
		if (item['product_id'] === product_id) {
			product = item['product'];
			return false;
		}
	});
	if (product && product['id']) {
		var modal = $('#cart_success_modal');
		var image = modal.find('.ordered-product-image img');
		var title = modal.find('.ordered-product-title');
		var price = modal.find('.ordered-product-price');
		image.attr('src', product['images'][0]);
		title.text(product['name']);
		price.text(product['priceFormated'] + ' ' + shop.currency);
		$.fancybox.open(modal);
	}
}

function modalConfirm(question, callback) {
	var modal = $('#confirm_modal');
	modal.find('.question').text(question);
	modal.find('.actions .action-confirm').off().click(function (event) {
		event.preventDefault();
		$.fancybox.close();
		if (callback && typeof callback === 'function') {
			callback();
		}
	});
	$.fancybox.open(modal);
}
