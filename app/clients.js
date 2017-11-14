$(function() {
	// UID клиента
	var clientID = document.getElementById('UID').value;
	// таблица VLAN
	var $tblVLAN = $('#tbl-vlan'),
		$tblVLANtbody = $tblVLAN.find('.tbl__body'),
		trNullHTML = '<tr class="tr-null"><td colspan="2"><p>Ни одного VLAN не подключено</p></td></tr>';

	// кнопка отображающая модалку для добавления VLAN
	$('.js-add-vlan').on('click',function() {
		$('.modal-overlay').addClass('_show');
		$('#modal-vlan').addClass('_show');
	});

	// кнопка сохранения VLAN
	$('.js-save-vlan').on('click',function(e) {
		e.preventDefault();
		var json = getFormValues('form-vlan');
		json.action = 'add';
		json.vlanID = null;
		json.clientID = clientID; // добавляем UID клиента
		if(json['vlan']) {
			makeRequest('POST', 'api/vlan.php', json).then(function (response) {
				var res = JSON.parse(response);
				console.log(res);
				alert(res.statusText);
				if (!res['isExist']) { // если такой записи в таблице нет, то вставляем
					// проверяем есть ли записи в таблице
					if ($tblVLANtbody.find('.tr-null').length) {
						$tblVLANtbody.find('.tr-null').remove();
					}
					// создаем новую строку
					var tr = document.createElement('tr');
					var td1 = document.createElement('td');
					td1.appendChild(document.createTextNode(res['vlan']));
					tr.appendChild(td1);
					var td2 = document.createElement('td');
					classie.add(td2, 'text-right');
					td2.appendChild(createBtnOff('js-off-vlan', res['vlanID']));
					tr.appendChild(td2);
					// вставляем новую строку в таблицу
					$tblVLANtbody.prepend(tr);
				}
			}).catch(function (err) {
				console.error('Упс! Что-то пошло не так.', err.statusText);
			});
		} else {
			alert('Не заполнено поле VLAN');
		}
	});

	// кнопки отключения VLAN
	$('body').on('click', '.js-off-vlan', function(e) {
		e.preventDefault();
		var self = this;
		var json = new Object();
		json = {
			action: 'edit',
			vlanID: self.dataset.uid,
			clientID: clientID
		};
		makeRequest('POST', 'api/vlan.php', json).then(function (response) {
			var res = JSON.parse(response);
			console.log(res);
			alert(res.statusText);
			$(self).parent().parent().remove();
			// проверяем не ли записей в таблице
			if (!$tblVLANtbody.find('tr').length) {
				$tblVLANtbody.append(trNullHTML);
			}
			// ip, которые надо удалить из таблицы
			//var listTr = $tblIPtbody.find('tr');
			//listTr.forEach(function(tr) {
			//	console.log(tr);
			//});
		}).catch(function (err) {
			console.error('Упс! Что-то пошло не так.', err.statusText);
		});
	});

	// таблица IP
	var $tblIP = $('#tbl-ip'),
		$tblIPtbody = $tblIP.find('.tbl__body'),
		trNullIPHTML = '<tr class="tr-null"><td colspan="2"><p>Ни одного IP не подключено</p></td></tr>';

	// кнопка отображающая модалку для добавления IP
	$('.js-add-ip').on('click',function() {
		$('.modal-overlay').addClass('_show');
		$('#modal-ip').addClass('_show');
	});

	// кнопка сохранения IP
	$('.js-save-ip').on('click',function(e) {
		e.preventDefault();
		var json = getFormValues('form-ip');
		json.action = 'add';
		json.clientID = clientID; // добавляем UID клиента
		json.uid = null;
		if(json['ip'] && json['vlanID']) {
			makeRequest('POST', 'api/ip.php', json).then(function (response) {
				var res = JSON.parse(response);
				console.log(res);
				alert(res.statusText);
				if (!res.isExist) { // если такой записи в таблице нет, то вставляем
					// проверяем есть ли записи в таблице
					if ($tblIPtbody.find('.tr-null').length) {
						$tblIPtbody.find('.tr-null').remove();
					}
					// создаем новую строку
					var tr = document.createElement('tr');
					var td1 = document.createElement('td');
					td1.appendChild(document.createTextNode(res['ip']));
					tr.appendChild(td1);
					var td2 = document.createElement('td');
					td2.appendChild(document.createTextNode(res['vlanID']));
					tr.appendChild(td2);
					var td3 = document.createElement('td');
					classie.add(td3, 'text-right');
					td3.appendChild(createBtnOff('js-off-ip', res['uid']));
					tr.appendChild(td3);
					// вставляем новую строку в таблицу
					$tblIPtbody.prepend(tr);
				}
			}).catch(function (err) {
				console.error('Упс! Что-то пошло не так.', err.statusText);
			});
		} else {
			alert('Не заполнено поле IP');
		}
	});

	// кнопки отключения IP
	$('body').on('click', '.js-off-ip', function(e) {
		e.preventDefault();
		var self = this;
		var json = new Object();
		json = {
			action: 'edit',
			uid: self.dataset.uid,
			clientID: clientID
		};
		makeRequest('POST', 'api/ip.php', json).then(function (response) {
			var res = JSON.parse(response);
			console.log(res);
			alert(res.statusText);
			$(self).parent().parent().remove();
			// проверяем не ли записей в таблице
			if (!$tblIPtbody.find('tr').length) {
				$tblIPtbody.append(trNullIPHTML);
			}
		}).catch(function (err) {
			console.error('Упс! Что-то пошло не так.', err.statusText);
		});
	});
});