var btnMenu = document.querySelector('.btnMenu');
var sidebar = document.querySelector('.sidebar');
var listItems = document.querySelectorAll('nav ul li');

var LanguageIconBtn = document.getElementById("LanguageIconBtn");

LanguageIconBtn.addEventListener('click', function () {
	window.location = 'index.html';
});

// Toggle the click class and show class on button click
btnMenu.addEventListener('click', function() {
	btnMenu.classList.toggle('click');
	sidebar.classList.toggle('show');
});

// Add the active class and remove it from siblings on list item click
for (var i = 0; i < listItems.length; i++) {
	listItems[i].addEventListener('click', function() {
	this.classList.add('active');
	var siblings = this.parentNode.children;
	for (var j = 0; j < siblings.length; j++) {
		if (siblings[j] !== this) {
		siblings[j].classList.remove('active');
		}
	}
	});
}