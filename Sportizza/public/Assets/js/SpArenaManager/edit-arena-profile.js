const edit_category = document.getElementById("category");
const other_facilities = document.getElementById("other-facilities");
const edit_location = document.getElementById("location");
const map_link = document.getElementById("map-link");

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

  const arena_edit_form = document.getElementById('arena-edit-form');

  arena_edit_form.addEventListener('submit',function (event) {
    event.preventDefault();

    // validateSpArenaName() &&
    // validateContact() &&
    // validateSparenaCategory() &&
    // validateSparenaLocation() &&
    // validateMapLink() &&
    // validateDescription() &&
    // validateOtherFacilities() &&
    // validatePayment()

    validateSpArenaName() &&
    validateContact() &&
    validateSparenaCategory() &&
    validateSparenaLocation() &&
    validateMapLink() &&
    validateDescription() &&
    validateOtherFacilities() &&
    validatePayment()

    if (
      validateSpArenaName() &&
      validateContact() &&
      validateSparenaCategory() &&
      validateSparenaLocation() &&
      validateMapLink() &&
      validateDescription() &&
      validateOtherFacilities() &&
      validatePayment()
    ) {
      arena_edit_form.submit();
    }
  });

  function validateEditForm() {
      validateSpArenaName();
      validateContact();
      validateSparenaCategory();
      validateSparenaLocation();
      validateMapLink();
      validateDescription();
      validateOtherFacilities();
      validatePayment(); 
  };

  function ChangeImage(imageNumber) {
    const image_id = "#photo" + imageNumber;
    const file_id = "#file" + imageNumber;
    const arena_img = document.querySelector(image_id);
    const img_file = document.querySelector(file_id);
    
    // js for edit profile picture

    //work form image showing function
    img_file.addEventListener("change", function () {
      //this refers to file upload
      const choosedFile = this.files[0];
      if (choosedFile) {
        const reader = new FileReader();
        //file reader function
        reader.addEventListener("load", function () {
          arena_img.setAttribute("src", reader.result);
        });
        reader.readAsDataURL(choosedFile);
        ImageSubmit(imageNumber);
      }
    });
  }

  function ImageSubmit(imageNumber) {
    const form_id = "#image_upload_" + imageNumber;
    const image_upload = document.querySelector(form_id);
    if(image_upload.submit()){
      // alert("Image Uploader");
    }
  }