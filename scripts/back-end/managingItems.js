async function testGetRoomDetails(exportParam = false) {
  var callback = exportParam ? blobCallback : jsonCallback;
  var data = await fetchGet("escapeRoomController", "getRoomDetails", { id: 1, language: "en", export: exportParam }, callback);

  if (exportParam) {
      downloadFile(data, "escape-room-details", "json");
  }
  else {
  }
}

async function testAddRoom() {
  var roomToAdd = {
      language: "en",
      name: "Very new escape room",
      difficulty: 3,
      timeLimit: 45,
      minPlayers: 1,
      maxPlayers: 1,
      image: "https://escapetheroom.com/wp-content/uploads/2018/11/escape-the-room-head.jpg"
  };

  await fetchPost("escapeRoomController", "addRoom", { roomJson: roomToAdd });
}

async function testUpdateRoom() {
  var roomToUpdate = {
      id: 1,
      language: "en",
      name: "Sample escape room",
      difficulty: 4,
      timeLimit: 60,
      minPlayers: 1,
      maxPlayers: 2,
      image: "https://escapetheroom.com/wp-content/uploads/2018/11/escape-the-room-head.jpg"
  };

  await fetchPost("escapeRoomController", "updateRoom", { roomJson: roomToUpdate });
}

async function testTranslateRoom() {
  var roomToTranslate = {
      id: 1,
      language: "fr",
      name: "Exemple de salle d'évasion"
  };

  await fetchPost("escapeRoomController", "translateRoom", { roomJson: roomToTranslate });
}

async function testDeleteRoom() {
  var roomToDeleteId = 3;
  await fetchPost("escapeRoomController", "deleteRoom", { id: roomToDeleteId });
}