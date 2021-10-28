// /Edit Profile picture
const imgDiv = document.querySelector(".editProPic");
const img = document.querySelector("#arena-picture1");
const file = document.querySelector("#image1_upload");
const uploadBtn = document.querySelector("#image1_uploadBtn");

//if user hover image div
imgDiv.addEventListener("mouseenter", function () {
    uploadBtn.style.display = "block"
})

//if user out from img div
imgDiv.addEventListener("mouseleave", function () {
    uploadBtn.style.display = "none"
})

//work form image showing function
file.addEventListener("change", function () {
    //this refers to file upload
    const choosedFile = this.files[0];
    if (choosedFile) {
        const reader = new FileReader();
        //file reader function
        reader.addEventListener("load", function () {
            img.setAttribute("src", reader.result);
        });
        reader.readAsDataURL(choosedFile);

    }
})