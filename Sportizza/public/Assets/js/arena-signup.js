//popup delete message
function open_popup_map() {
  const form = document.getElementById("popup_delete");
  form.style.display = "block";
}
function close_popup_map() {
  var form = document.getElementById("popup_delete");
  form.style.display = "none";
}
  let map;
  let markers = [];

  function initMap() {
    const myLatlng = { lat: 6.919312201418947, lng: 79.94772455867054 };
    map = new google.maps.Map(document.getElementById("map"), {
      zoom: 10,
      center: myLatlng,
    });

    // Configure the click listener.
    map.addListener("click", (mapsMouseEvent) => {
      addMarker(mapsMouseEvent.latLng);
      console.log(mapsMouseEvent.latLng.lat());
      console.log(mapsMouseEvent.latLng.lng());
      setLink(mapsMouseEvent.latLng.lat(), mapsMouseEvent.latLng.lng());
    });
    addMarker(myLatlng);
  }

  function addMarker(position) {
    deleteMarkers();
    const marker = new google.maps.Marker({
      position,
      map,
    });
    markers.push(marker);
  }

  // Sets the map on all markers in the array.
  function setMapOnAll(map) {
    for (let i = 0; i < markers.length; i++) {
      markers[i].setMap(map);
    }
  } // Removes the markers from the map, but keeps them in the array.
  function hideMarkers() {
    setMapOnAll(null);
  } // Shows any markers currently in the array.
  function showMarkers() {
    setMapOnAll(map);
  } // Deletes all markers in the array by removing references to them.
  function deleteMarkers() {
    hideMarkers();
    markers = [];
  }

  function setLink(lat, lng) {
    let Link =
      "https://www.google.com/maps/search/?api=1&query=" + lat + "%2C" + lng;
    document.getElementById("map-link").value = Link;
  }