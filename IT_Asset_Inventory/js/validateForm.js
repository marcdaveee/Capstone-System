// handle if input is empty
let serialNo = document.querySelector("#item-serial-no");
let itemName = document.querySelector("#item-name");
let itemType = document.querySelector("#item-type");
let itemBrand = document.querySelector("#item-brand");
let manufacturer = document.querySelector("#item-manufacturer");

// Check if Serial no. field value is empty and Alphanumeric
// Check if Serial no. field value exists
if (serialNo) {
  checkIfExist(serialNo.value, "serialNo", "item-serialno-error");
  serialNo.addEventListener("keyup", function () {
    // checkIfAlphaNumeric(this.value, "item-serialno-error");
    checkIfExist(serialNo.value, "serialNo", "item-serialno-error");
  });
}

// Check if item name field value is empty and alphanumeric
checkIfAlphaNumeric(itemName.value, "item-name-error");
itemName.addEventListener("keyup", function () {
  // checkIfEmpty(this.value, "item-name-error");
  checkIfAlphaNumeric(this.value, "item-name-error");
});

// Check if item type field value is empty

console.log("Current Value:" + itemType.value);
itemType.addEventListener("change", function () {
  if (this.value == "Define New") {
    createNewTypeCategory();
  }

  checkIfAlphaNumeric(this.value, "item-type-error");
});

// Check if item brand field value is empty and Alphanumeric
checkIfAlphaNumeric(itemBrand.value, "item-brand-error");
itemBrand.addEventListener("keyup", function () {
  checkIfAlphaNumeric(this.value, "item-brand-error");
});

// Check if manufacturer field value is empty and Alphanumeric
checkIfAlphaNumeric(manufacturer.value, "item-manufacturer-error");
manufacturer.addEventListener("keyup", function () {
  checkIfAlphaNumeric(this.value, "item-manufacturer-error");
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
function checkIfAlpha(value, errorField) {
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

  XHR.open("POST", "validate_alpha_field.php");
  XHR.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  XHR.send(data);
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
  checkIfExist(newItemTypeCategory.value, "itemType", "item-type-create-error");
});

// Submitting new item type category
const addItemTypeForm = document.querySelector("#add-item-type");
addItemTypeForm.addEventListener("submit", (e) => {
  e.preventDefault();
  const XHR = new XMLHttpRequest();
  const data = "request=addNewCategory&data=" + newItemTypeCategory.value;

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
  itemType.value = "";
  let options = document.querySelectorAll(".item-type-option");
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
  checkIfAlphaNumeric(itemType.value, "item-type-error");
  modalBox.classList.add("hide");
}

loadCategories();

function loadCategories() {
  const XHR = new XMLHttpRequest();
  const data = "request=itemTypeCategories";

  XHR.onload = function () {
    const categories = JSON.parse(this.responseText);
    const categoryList = document.querySelector("#item-type");
    if (categoryList.children.length > 2) {
      console.log("More than two options!");
      reloadCategories();
    }

    const last = document.querySelector("#item-type .last");
    let category = "";
    for (let x in categories) {
      category = document.createElement("option");
      category.id = `${categories[x].item_type_category}`;
      category.className = "item-type-option";
      category.value = `${categories[x].item_type_category}`;
      category.innerHTML = `${categories[x].item_type_category}`;
      categoryList.insertBefore(category, last);
    }

    let selectedItemType = document.querySelector("#selected-item-type").value;
    console.log("Selected option id: " + selectedItemType.value);
    let options = document.querySelectorAll(".item-type-option");
    options.forEach((option) => {
      if (option.value == selectedItemType) {
        console.log("Selected:" + option.value);
        option.selected = true;
      }
    });
    checkIfAlphaNumeric(itemType.value, "item-type-error");
  };

  XHR.open("POST", "get_categories.php");
  XHR.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  XHR.send(data);
}

function reloadCategories() {
  let options = document.querySelectorAll(".item-type-option");
  options.forEach((option) => {
    option.remove();
  });
}
