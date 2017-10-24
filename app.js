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