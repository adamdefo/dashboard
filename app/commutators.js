$(function() {
	var commutator = $('.js-toggle-commutator');
	commutator.on('click',function(e) {
		e.preventDefault();
		var $this = $(this);
		var json = {
			id: $this.data('id')
		};

		var trInfo = $('.commutators-tree__info-' + $this.data('id')); // строка, куда вставлю контент по клиентам

		if (trInfo.hasClass('_show')) {
			trInfo.html('');
			trInfo.removeClass('_show');
		} else {
			makeRequest('POST', 'api/commutator_tree.php', json).then(function (response) {
				trInfo.addClass('_show');
				var res = JSON.parse(response);
				res.forEach(function (item) {
					var td = '<td>';
					td += '<p>' + item['port'] + '</p>';
					td += '<p>' + item['vlan'] + '</p>';
					if (item['clients']) {
						td += '<h6>Клиенты</h6>';
						item['clients'].forEach(function (client) {
							td += '<p>' + client['full_name'] + '</p>';
							td += '<p><b>Контактное лицо:</b> ' + client['contact_person'] + '</p>';
							td += '<p><b>Телефон:</b> ' + client['contact_person_phone'] + '</p>';
							td += '<p><b>Статус:</b> ' + client['status'] + '</p>';
						});
					} else {
						td += '<p>Порт свободен</p>';
					}
					td += '</td>';

					trInfo.append(td);
				});
			}).catch(function (err) {
				console.error('Упс! Что-то пошло не так.', err.statusText);
			});
		}
	});
});