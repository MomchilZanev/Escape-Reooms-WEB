var btnMenu = document.querySelector('.btnMenu');
var sidebar = document.querySelector('.sidebar');
var listItems = document.querySelectorAll('nav ul li');

btnMenu.addEventListener('click', function () {
	btnMenu.classList.toggle('click');
	sidebar.classList.toggle('show');
	image = btnMenu.children[0];
	const pattern = /.*menu\.svg$/;
	if (pattern.test(image.src)) {
		image.setAttribute("src", "../images/left-arrow.svg");
	} else {
		image.setAttribute("src", "../images/menu.svg");
	}
});

// Add the active class and remove it from siblings on list item click
for (var i = 0; i < listItems.length; i++) {
	listItems[i].addEventListener('click', function () {
		this.classList.add('active');
		var siblings = this.parentNode.children;
		for (var j = 0; j < siblings.length; j++) {
			if (siblings[j] !== this) {
				siblings[j].classList.remove('active');
			}
		}

		sessionStorage.clear(); // Sanitizing the session storage when navigating between the major pages
		sessionStorage.setItem('tempRoom', JSON.stringify(setTempRoomValues()));

		openContent(this.children[0].id + '.html');
	});
}

function openContent(filename) {
	iframe = top.document.getElementById('innerPage');
	iframe.width = iframe.contentWindow.document.body.scrollWidth;
	iframe.height = iframe.contentWindow.document.body.scrollHeight;
	iframe.setAttribute("src", filename);
	iframe.addEventListener("load", function () {
		var language = getCookie("language") ?? "en";
		setLanguage(language);
	});
}

document.addEventListener("DOMContentLoaded", function () {
	openContent('homepage.html');
});

function setTempRoomValues() {
	var tempItems = {};
	tempItems.language = '';
	tempItems.timeLimit = '';
	tempItems.minPlayers = '';
	tempItems.maxPlayers = '';
	tempItems.name = '';
	tempItems.image = '';
	tempItems.difficulty = '';
	tempItems.id = '';
	tempItems.riddles = [];
	return tempItems;
  }