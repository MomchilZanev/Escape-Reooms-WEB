var listRiddles = document.querySelectorAll('.objectBox a');
console.log(listRiddles.length);
for (var i = 0; i < listRiddles.length; i++) {
	listRiddles[i].addEventListener('click', function() {
		window.location = 'dataPageRiddle.html';
	});
}