var btnMenu = document.querySelector('.btnMenu');
var sidebar = document.querySelector('.sidebar');
var listItems = document.querySelectorAll('nav ul li');

var LanguageIconBtn = document.getElementById("LanguageIconBtn");
var LanguageForm = document.getElementById('LanguageForm');
var langTextBox = document.getElementById("langTextBox");

var submitLangButton = document.getElementById("submitLangButton");

LanguageForm.style.display = 'none';

LanguageIconBtn.addEventListener('click', function () {
	if (LanguageForm.style.display == 'none') {
		LanguageForm.style.display = 'block';
	} else {
		LanguageForm.style.display = 'none';
	}
});

submitLangButton.addEventListener('click', function () {

	var text = langTextBox.value;
	langTextBox.value = '';
	LanguageForm.style.display = 'none';
	LanguageIconBtn.textContent = text;

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