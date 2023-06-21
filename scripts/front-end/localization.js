var englishBtn = document.getElementById("en");
var bulgarianBtn = document.getElementById("bg");
var innerPage = document.getElementById("innerPage");

englishBtn.addEventListener("click", function () {
	bulgarianBtn.style.backgroundColor = "#d9d9d9";
	englishBtn.style.backgroundColor = "#FFDB58";
	setLanguage("en");
	sessionStorage.setItem("language", "en");
});

bulgarianBtn.addEventListener("click", function () {
	bulgarianBtn.style.backgroundColor = "#FFDB58";
	englishBtn.style.backgroundColor = "#d9d9d9";
	setLanguage("bg");
	sessionStorage.setItem("language", "bg");
});

function setLanguage(language = "en") {
	document.getElementById(language).style.backgroundColor = "#FFDB58";

	const localizationResources = document.getElementById("localizationResources");
	if (localizationResources) {
		localizationResources.remove();
	}

	const newLocalizationResources = document.createElement("script");
	newLocalizationResources.src = window.origin + "/escaperooms/scripts/resources/" + language + ".js";
	newLocalizationResources.id = "localizationResources";
	document.head.appendChild(newLocalizationResources);

	newLocalizationResources.addEventListener("load", function () {
		setLocalizedText(document.getElementsByClassName("localizedText"));
		setLocalizedText(innerPage.contentWindow.document.getElementsByClassName("localizedText"));
		setLocalizedValue(document.getElementsByClassName("localizedValue"));
		setLocalizedValue(innerPage.contentWindow.document.getElementsByClassName("localizedValue"));
		setLocalizedPlaceholders(document.getElementsByClassName("localizedPlaceholder"));
		setLocalizedPlaceholders(innerPage.contentWindow.document.getElementsByClassName("localizedPlaceholder"));
	});

	setCookie("language", language, 7);
}

// The localized message class should always be directly preceded by the "localizedText" class
function setLocalizedText(localizedElements) {
	for (var i = 0; i < localizedElements.length; ++i) {
		var classes = localizedElements[i].className.split(" ");
		var j = classes.indexOf("localizedText");
		if (j >= 0) {
			var message = classes[j+1];
			localizedElements[i].textContent = window.localizationResources[message];
		}
	}
}

function setLocalizedValue(localizedElements) {
	for (var i = 0; i < localizedElements.length; ++i) {
		var classes = localizedElements[i].className.split(" ");
		var j = classes.indexOf("localizedValue");
		if (j >= 0) {
			var message = classes[j+1];
			localizedElements[i].value = window.localizationResources[message];
		}
	}
}

function setLocalizedPlaceholders(localizedElements) {
	for (var i = 0; i < localizedElements.length; ++i) {
		var classes = localizedElements[i].className.split(" ");
		var j = classes.indexOf("localizedPlaceholder");
		if (j >= 0) {
			const message = classes[j+1];
			const placeholderText = window.localizationResources[message];
			if ( placeholderText !== null && placeholderText !== undefined ) {
				localizedElements[i].placeholder = window.localizationResources[message];
			}
		}
	}
}