var homepage = document.getElementById("homepage");
var showRiddles = document.getElementById('showRiddles');
var importRooms = document.getElementById('importRoom');
var exportRooms = document.getElementById('exportRooms');
var documentation = document.getElementById('documentation');

var EnglishBtn = document.getElementById("EN");
var BulgarianBtn = document.getElementById("BG");

EnglishBtn.addEventListener('click', function () {
  BulgarianBtn.style.backgroundColor = '#d9d9d9';
  EnglishBtn.style.backgroundColor = '#FFDB58';
	homepage.textContent = "Homepage";
	showRiddles.textContent = "Show riddles";
	importRooms.textContent = "Import a room";
	exportRooms.textContent = "Export rooms";
	documentation.textContent = "Documentation";
});

BulgarianBtn.addEventListener('click', function () {
  BulgarianBtn.style.backgroundColor = '#FFDB58';
  EnglishBtn.style.backgroundColor = '#d9d9d9';
	homepage.textContent = "Начална страница";
	showRiddles.textContent = "Покажи загадките";
	importRooms.textContent = "Добави стая";
	exportRooms.textContent = "Извлечи стаите";
	documentation.textContent = "Документация";
});

