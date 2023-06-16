var listRooms = document.querySelectorAll('.objectBox a');
for (var i = 0; i < listRooms.length; i++) {
	listRooms[i].addEventListener('click', function() {
		window.location = 'dataPageRoom.html';
	});
}