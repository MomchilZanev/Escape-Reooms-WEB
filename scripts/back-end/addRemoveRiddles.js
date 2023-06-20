async function getAllRiddles() {
  var riddles_id = new Set();
  var riddles = JSON.parse(sessionStorage.getItem("riddles"));

  createGetObjects(riddles, 'riddles', 'toDeleteObjectContainer');
  addDeleteButtons(riddles);

  for (let i = 0; i < riddles.length; i++) {
    riddles_id.add(riddles[i].id);
  }

  var allRiddles = await fetchGet("riddleController", "getAllRiddles", { language: "en", export: jsonCallback }, jsonCallback);
  var filterAllRiddles = allRiddles.filter(function(item){
    return !riddles_id.has(item.id);         
  });

  createGetObjects(filterAllRiddles, 'riddles', 'toAddObjectContainer');
  addAddButtons(allRiddles, riddles);
}

getAllRiddles();

function addDeleteButtons(riddles) {
  const listObjectBoxes = document.querySelectorAll('#toDeleteRiddlesContainer .objectContainer .objectBox');

  for (var i = 0; i < listObjectBoxes.length; i++) {
    var deleteButton = document.createElement('button');
    deleteButton.className = "addAndDelete localizedText deleteButton";
    deleteButton.textContent = "Delete";
    listObjectBoxes[i].appendChild(deleteButton);

    deleteButton.addEventListener('click', function() {
      var deleteId = this.parentNode.children[0].id;
      var getRiddle = riddles.filter(function(item){
        return deleteId != item.id;         
      });
      sessionStorage.setItem("riddles", JSON.stringify(getRiddle));
    });
  }
}

async function addAddButtons(allRiddles, riddles) {
  const listObjectBoxes = document.querySelectorAll('#toAddRiddlesContainer .objectContainer .objectBox');
  
  for (var i = 0; i < listObjectBoxes.length; i++) {
    var addButton = document.createElement('button');
    addButton.className = "addAndDelete localizedText addButton";
    addButton.textContent = "Add";
    listObjectBoxes[i].appendChild(addButton);

    addButton.addEventListener('click', function() {
      var addId = this.parentNode.children[0].id;
      var getRiddle = allRiddles.filter(function(item){
        return addId == item.id;         
      });

      riddles.push(getRiddle[0]);
      sessionStorage.setItem("riddles", JSON.stringify(riddles));
    });
  }
}



