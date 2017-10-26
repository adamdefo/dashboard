var makeRequest = function (method, url, data) {
	return new Promise(function (resolve, reject) {
		var xhr = new XMLHttpRequest();
		xhr.open(method, url);
		// xhr.setRequestHeader('Content-Type', 'application/json');
		xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		xhr.onload = function () {
			if (this.status >= 200 && this.status < 300) {
				resolve(xhr.response);
			} else {
				reject({
					status: this.status,
					statusText: xhr.statusText
				});
			}
		};
		xhr.onerror = function () {
			reject({
				status: this.status,
				statusText: xhr.statusText
			});
		};
		method === 'POST' ? xhr.send('data=' + JSON.stringify(data)) : xhr.send(null); 
	});
}

// получает значение инпутов в форме
var getFormValues = function(form) {
	var form = document.getElementById(form);
	var data = {
		statusText: ''
	};
	[].slice.call(form.querySelectorAll('.form-control')).forEach(function(input) {
		data[input.getAttribute('name')] = input.value;
	});
	
	return data;
}

// создает кнопку для новой строки в таблице, которая отключает запись
var createBtnOff = function(jsClass, attrVal) {
	var btn = document.createElement('button');
	classie.add(btn, 'btn');
	classie.add(btn, 'btn-small');
	classie.add(btn, 'btn-danger');
	classie.add(btn, jsClass);
	btn.setAttribute('data-uid', attrVal);
	btn.appendChild(document.createTextNode('Отключить'));
	return btn;
}