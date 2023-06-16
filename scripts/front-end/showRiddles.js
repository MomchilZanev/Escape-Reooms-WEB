var listRiddles = document.querySelectorAll('.objectBox a');
for (var i = 0; i < listRiddles.length; i++) {
	listRiddles[i].addEventListener('click', function() {
		window.location = 'dataPageRiddle.html';
	});
}