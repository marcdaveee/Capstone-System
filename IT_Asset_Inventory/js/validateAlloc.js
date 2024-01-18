// handle if input is empty
let user = document.querySelector("#user");
let currLocation = document.querySelector("#curr-location");

checkIfAlpha(user.value, "", "user-field-error");

user.addEventListener("keyup", function () {
  checkIfAlpha(this.value, "", "user-field-error");
});

checkIfAlphaNumeric(currLocation.value, "curr-location-error");
currLocation.addEventListener("change", function () {
  if (this.value == "Define New") {
    createNewTypeCategory();
  }
  checkIfAlphaNumeric(this.value, "curr-location-error");
});

function checkIfAlphaNumeric(value, errorField) {
  const XHR = new XMLHttpRequest();
  console.log(value);
  const data = "input=" + value;

  XHR.onload = function () {
    const response = this.responseText;
    console.log(response);

    if (response.trim() != "Good") {
      document.querySelector(`#${errorField}`).innerHTML = this.responseText;
    } else {
      document.querySelector(`#${errorField}`).innerHTML = "";
    }
  };

  XHR.open("POST", "validate_alphanum_field.php");
  XHR.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  XHR.send(data);
}

function checkIfAlpha(value, type, errorField) {
  const XHR = new XMLHttpRequest();
  console.log(value);
  const data = "type=" + type + "&input=" + value;

  XHR.onload = function () {
    const response = this.responseText;
    console.log(response);

    if (response.trim() != "Good") {
      document.querySelector(`#${errorField}`).innerHTML = this.responseText;
    } else {
      document.querySelector(`#${errorField}`).innerHTML = "";
    }
  };

  XHR.open("POST", "validate_alpha_field.php");
  XHR.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  XHR.send(data);
}

// check if value exist in the database
function checkIfExist(value, type, errorField) {
  const XHR = new XMLHttpRequest();
  const data = "type=" + type + "&input=" + value;
  console.log(data);

  XHR.onload = function () {
    const response = this.responseText;

    if (response != "Good") {
      document.querySelector(`#${errorField}`).innerHTML = this.responseText;
    } else {
      document.querySelector(`#${errorField}`).innerHTML = "";
    }
  };

  XHR.open("POST", "check_duplication.php");
  XHR.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  XHR.send(data);
}

// Load location options
loadCategories();

function loadCategories() {
  const XHR = new XMLHttpRequest();
  const data = "request=locationOptions";

  XHR.onload = function () {
    const locations = JSON.parse(this.responseText);
    const locationList = document.querySelector("#curr-location");
    console.log(locations);
    if (locationList.children.length > 2) {
      console.log("More than two options!");
      reloadCategories();
    }

    const last = document.querySelector("#curr-location .last");
    let location = "";
    for (let x in locations) {
      location = document.createElement("option");
      location.id = `${locations[x].location_option}`;
      location.className = "location-option";
      location.value = `${locations[x].location_option}`;
      location.innerHTML = `${locations[x].location_option}`;
      locationList.insertBefore(location, last);
    }

    let selectedLocation = document.querySelector(
      "#selected-location-option"
    ).value;
    console.log("Selected option id: " + selectedLocation);
    let options = document.querySelectorAll(".location-option");
    options.forEach((option) => {
      if (option.value == selectedLocation) {
        console.log("Selected:" + option.value);
        option.selected = true;
      }
    });
    checkIfAlphaNumeric(currLocation.value, "curr-location-error");
  };

  XHR.open("POST", "get_categories.php");
  XHR.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  XHR.send(data);
}

function reloadCategories() {
  let options = document.querySelectorAll(".location-option");
  options.forEach((option) => {
    option.remove();
  });
}

// Display for creating new item type category
function createNewTypeCategory() {
  document.querySelector(".modal-box").classList.remove("hide");
}

// Adding new location
let newLocationInput = document.querySelector("#new-location-option");

// Validating input in creating new location
checkIfAlphaNumeric(newLocationInput.value, "location-create-error");
newLocationInput.addEventListener("keyup", function () {
  checkIfAlphaNumeric(newLocationInput.value, "location-create-error");
  checkIfExist(newLocationInput.value, "location", "location-create-error");
});

// Submitting new location
const newLocationOptionForm = document.querySelector("#add-location-option");
newLocationOptionForm.addEventListener("submit", (e) => {
  e.preventDefault();
  const XHR = new XMLHttpRequest();
  const data = "request=addNewLocation&data=" + newLocationInput.value;

  XHR.onload = function () {
    let response = this.responseText;
    console.log("Response: " + response);

    if (response.trim() == "Good!") {
      // Show success message
      document.querySelector("#location-create-error").innerHTML = "";
      loadCategories();
      showSuccessMsgBox();
    } else {
      document.querySelector("#location-create-error").innerHTML =
        this.responseText;
    }
  };

  XHR.open("POST", "set_categories.php");
  XHR.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  XHR.send(data);
});

// Display a success message box upon successfully defining a new location
function showSuccessMsgBox() {
  console.log("success!");
  document.querySelector(".add-category").classList.add("hide");
  const successMsgBox = document.querySelector(".successMsg-box");
  successMsgBox.classList.remove("hide");
}

const doneBtn = document.querySelector("#done-btn");
doneBtn.addEventListener("click", () => {
  closeModal();
});

let modalBox = document.querySelector(".modal-box");

modalBox.addEventListener("click", (e) => {
  if (e.target.className == "modal-box") {
    closeModal();
  }
});

let closeBtn = document.querySelector(".add-category .close");
closeBtn.addEventListener("click", (e) => {
  closeModal();
});

function closeModal() {
  currLocation.value = "";
  let options = document.querySelectorAll(".location-option");
  options.forEach((option) => {
    if (option.value == newLocationInput.value) {
      console.log("Selected:" + option.value);
      option.selected = true;
    }
  });

  newLocationInput.value = "";
  document.querySelector(".add-category").classList.remove("hide");
  const successMsgBox = document.querySelector(".successMsg-box");
  successMsgBox.classList.add("hide");
  checkIfAlphaNumeric(currLocation.value, "location-create-error");
  modalBox.classList.add("hide");
}
