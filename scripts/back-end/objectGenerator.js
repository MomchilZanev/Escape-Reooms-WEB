var objectBox = document.createElement('div');
objectBox.className = 'objectBox';

var anchorTag = document.createElement('a');
anchorTag.id = '1';

var imageTag = document.createElement('img');
imageTag.src = '../images/example.jpg';

anchorTag.appendChild(imageTag);

var paragraphTag = document.createElement('p');
paragraphTag.className = 'objectTitle';
paragraphTag.textContent = 'Object Title';

objectBox.appendChild(anchorTag);
objectBox.appendChild(paragraphTag);

document.getElementsByClassName('objectContainer')[0].appendChild(objectBox);