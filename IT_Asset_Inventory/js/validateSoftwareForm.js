// handle if input is empty
let productId = document.querySelector("#product-id");
let softwareName = document.querySelector("#software-name");
let softwareType = document.querySelector("#software-type");
let vendor = document.querySelector("#manufacturer");
let dateOfPurchase = document.querySelector("#date-of-purchase");
let noOfInstallation = document.querySelector("#no-of-installation");

// Check if Product ID field value exists
if (productId) {
  checkIfExist(productId.value, "productId", "product-id-error");
  productId.addEventListener("keyup", function () {
    // checkIfAlphaNumeric(this.value, "item-serialno-error");
    checkIfExist(productId.value, "productId", "product-id-error");
  });
}

// Check if software name field value is empty and alphanumeric
checkIfAlphaNumeric(softwareName.value, "software-name-error");
softwareName.addEventListener("keyup", function () {
  // checkIfEmpty(this.value, "item-name-error");
  checkIfAlphaNumeric(this.value, "software-name-error");
});

// Check if software type field value is empty
checkIfAlphaNumeric(softwareType.value, "software-type-error");
softwareType.addEventListener("change", function () {
  if (this.value == "Define New") {
    createNewTypeCategory();
  }
  checkIfAlphaNumeric(this.value, "software-type-error");
});

// Check if vendor field value is empty and Alphanumeric
checkIfAlphaNumeric(vendor.value, "manufacturer-field-error");
vendor.addEventListener("keyup", function () {
  checkIfAlphaNumeric(this.value, "manufacturer-field-error");
});

// Check if date of purchase field value is empty
checkIfAlpha(dateOfPurchase.value, "date", "date-purchase-error");
dateOfPurchase.addEventListener("blur", function () {
  checkIfAlpha(this.value, "date", "date-purchase-error");
});

// Check if no of installation field value is empty
checkIfAlphaNumeric(dateOfPurchase.value, "no-of-installation-error");
dateOfPurchase.addEventListener("keyup", function () {
  checkIfAlphaNumeric(this.value, "no-of-installation-error");
});

// check if value exist in the database
function checkIfExist(value, type, errorField) {
  const XHR = new XMLHttpRequest();
  const data = "type=" + type + "&input=" + value;
  console.log(data);

  XHR.onload = function () {
    const response = this.responseText;

    if (response.trim() != "Good") {
      document.querySelector(`#${errorField}`).innerHTML = this.responseText;
    } else {
      document.querySelector(`#${errorField}`).innerHTML = "";
    }
  };

  XHR.open("POST", "check_duplication.php");
  XHR.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  XHR.send(data);
}

// check if value contains alphanumeric characters
function checkIfAlphaNumeric(value, errorField) {
  const XHR = new XMLHttpRequest();
  const data = "input=" + value;

  XHR.onload = function () {
    const response = this.responseText;

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

// check if value contains letters only
function checkIfAlpha(value, type, errorField) {
  const XHR = new XMLHttpRequest();
  const data = "type=" + type + "&input=" + value;

  XHR.onload = function () {
    const response = this.responseText;

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

// Load software type categories
loadCategories();

function loadCategories() {
  const XHR = new XMLHttpRequest();
  const data = "request=softwareTypeOptions";

  XHR.onload = function () {
    const categories = JSON.parse(this.responseText);
    const categoryList = document.querySelector("#software-type");
    if (categoryList.children.length > 2) {
      console.log("More than two options!");
      reloadCategories();
    }

    const last = document.querySelector("#software-type .last");
    let category = "";
    for (let x in categories) {
      category = document.createElement("option");
      category.id = `${categories[x].software_type_option}`;
      category.className = "software-type-option";
      category.value = `${categories[x].software_type_option}`;
      category.innerHTML = `${categories[x].software_type_option}`;
      categoryList.insertBefore(category, last);
    }

    let selectedSoftwareType = document.querySelector(
      "#selected-software-type"
    ).value;
    console.log("Selected option id: " + selectedSoftwareType);
    let options = document.querySelectorAll(".software-type-option");
    options.forEach((option) => {
      if (option.value == selectedSoftwareType) {
        console.log("Selected:" + option.value);
        option.selected = true;
      }
    });
    checkIfAlphaNumeric(softwareType.value, "software-type-error");
  };

  XHR.open("POST", "get_categories.php");
  XHR.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  XHR.send(data);
}

function reloadCategories() {
  let options = document.querySelectorAll(".software-type-option");
  options.forEach((option) => {
    option.remove();
  });
}

// Display for creating new item type category
function createNewTypeCategory() {
  document.querySelector(".modal-box").classList.remove("hide");
}

let newItemTypeCategory = document.querySelector("#new-item-type");

//Validating new item type category name input
checkIfAlphaNumeric(newItemTypeCategory.value, "item-type-create-error");
newItemTypeCategory.addEventListener("keyup", function () {
  checkIfAlphaNumeric(newItemTypeCategory.value, "item-type-create-error");
  checkIfExist(
    newItemTypeCategory.value,
    "softwareType",
    "item-type-create-error"
  );
});

// Submitting new item type category
const addItemTypeForm = document.querySelector("#add-item-type");
addItemTypeForm.addEventListener("submit", (e) => {
  e.preventDefault();
  const XHR = new XMLHttpRequest();
  const data = "request=addSoftwareCategory&data=" + newItemTypeCategory.value;

  XHR.onload = function () {
    let response = this.responseText;
    console.log("Response: " + response);

    if (response.trim() == "Good!") {
      // Show success message
      document.querySelector("#item-type-create-error").innerHTML = "";
      loadCategories();
      showSuccessMsgBox();
    } else {
      document.querySelector("#item-type-create-error").innerHTML =
        this.responseText;
    }
  };

  XHR.open("POST", "set_categories.php");
  XHR.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  XHR.send(data);
});

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
  softwareType.value = "";
  let options = document.querySelectorAll(".software-type-option");
  options.forEach((option) => {
    if (option.value == newItemTypeCategory.value) {
      console.log("Selected:" + option.value);
      option.selected = true;
    }
  });

  newItemTypeCategory.value = "";
  document.querySelector(".add-category").classList.remove("hide");

  const successMsgBox = document.querySelector(".successMsg-box");
  successMsgBox.classList.add("hide");

  checkIfAlphaNumeric(softwareType.value, "software-type-error");
  modalBox.classList.add("hide");
}
