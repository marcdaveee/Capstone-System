// handle if input is empty
let username = document.querySelector("#user-name");
let email = document.querySelector("#email");
let password = document.querySelector("#password");
let userRole = document.querySelector("#role-type");
let department = document.querySelector("#department");

// Check if user name field value exists
if (username) {
  validateCredentials(username.value, "username", "username-error");
  username.addEventListener("keyup", function () {
    // checkIfAlphaNumeric(this.value, "item-serialno-error");
    validateCredentials(username.value, "username", "username-error");
  });
}

// Check if email field value exists
if (email) {
  validateCredentials(email.value, "email", "email-error");
  email.addEventListener("keyup", function () {
    // checkIfAlphaNumeric(this.value, "item-serialno-error");
    validateCredentials(email.value, "email", "email-error");
  });
}

if (password) {
  validateCredentials(password.value, "password", "password-field-error");
  password.addEventListener("keyup", function () {
    // checkIfAlphaNumeric(this.value, "item-serialno-error");
    validateCredentials(password.value, "password", "password-field-error");
  });
}

// Load role type
loadRoleCategories();

function loadRoleCategories() {
  const XHR = new XMLHttpRequest();
  const data = "request=roleTypeCategories";

  XHR.onload = function () {
    const categories = JSON.parse(this.responseText);
    const categoryList = document.querySelector("#role-type");
    if (categoryList.children.length > 2) {
      console.log("More than two options!");
      reloadCategories();
    }

    const last = document.querySelector("#role-type .last");
    let category = "";
    for (let x in categories) {
      category = document.createElement("option");
      //   category.id = `${categories[x].role_type}`;
      category.className = "role-type-option";
      category.value = `${categories[x].role_type}`;
      category.innerHTML = `${categories[x].role_type}`;
      categoryList.insertBefore(category, last);
    }

    let selectedRoleType = document.querySelector("#selected-role-type").value;
    console.log(selectedRoleType);
    let options = document.querySelectorAll(".role-type-option");
    options.forEach((option) => {
      if (option.value == selectedRoleType) {
        console.log("Selected:" + option.value);
        option.selected = true;
      }
    });
    validateCredentials(userRole.value, "", "role-type-error");
  };

  XHR.open("POST", "get_role_type_options.php");
  XHR.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  XHR.send(data);
}

function reloadCategories() {
  let options = document.querySelectorAll(".role-type-option");
  options.forEach((option) => {
    option.remove();
  });
}

userRole.addEventListener("change", function () {
  if (this.value == "Define New") {
    createNewTypeCategory();
  }
  validateCredentials(userRole.value, "", "role-type-error");
});

// Load departments
loadDeptCategories();

function loadDeptCategories() {
  const XHR = new XMLHttpRequest();
  const data = "request=getDepartments";

  XHR.onload = function () {
    const categories = JSON.parse(this.responseText);
    const categoryList = document.querySelector("#department");
    if (categoryList.children.length > 2) {
      console.log("More than two options!");
      reloadDepartments();
    }

    const last = document.querySelector("#department .last");
    let category = "";
    for (let x in categories) {
      category = document.createElement("option");
      category.className = "department-option";
      category.value = `${categories[x].dept_name}`;
      category.innerHTML = `${categories[x].dept_name}`;
      categoryList.insertBefore(category, last);
    }

    let selectedDept = document.querySelector("#selected-department").value;
    console.log(selectedDept);
    let options = document.querySelectorAll(".department-option");
    options.forEach((option) => {
      if (option.value == selectedDept) {
        console.log("Selected:" + option.value);
        option.selected = true;
      }
    });

    validateCredentials(department.value, "", "department-error");
  };

  XHR.open("POST", "get_departments.php");
  XHR.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  XHR.send(data);
}

function reloadDepartments() {
  let options = document.querySelectorAll(".department-option");
  options.forEach((option) => {
    option.remove();
  });
}

department.addEventListener("change", function () {
  if (this.value == "Define New") {
    createNewDept();
  }
  validateCredentials(department.value, "", "department-error");
});

function createNewDept() {
  document.querySelector(".modal-box").classList.remove("hide");
  document.querySelector("#new-department-box").classList.remove("hide");
}

let newDepartment = document.querySelector("#new-department");

//Validating new department name input
validateCredentials(
  newDepartment.value,
  "department",
  "department-create-error"
);

newDepartment.addEventListener("keyup", function () {
  validateCredentials(
    newDepartment.value,
    "department",
    "department-create-error"
  );
});

// Submitting new department name category
const addDeptForm = document.querySelector("#add-department");

addDeptForm.addEventListener("submit", (e) => {
  e.preventDefault();
  const XHR = new XMLHttpRequest();
  const data = "request=addNewDept&data=" + newDepartment.value;

  XHR.onload = function () {
    let response = this.responseText;
    console.log("Response: " + response);

    if (response.trim() == "Good!") {
      // Show success message
      document.querySelector("#department-create-error").innerHTML = "";
      document.querySelector("#selected-department").value = "";
      loadDeptCategories();
      showSuccessMsgBox();
    } else {
      document.querySelector("#department-create-error").innerHTML =
        this.responseText;
    }
  };

  XHR.open("POST", "add_dept.php");
  XHR.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  XHR.send(data);
});

// validate user account creation credentials
function validateCredentials(value, type, errorField) {
  const XHR = new XMLHttpRequest();

  const data = "type=" + type + "&input=" + value;

  XHR.onload = function () {
    const response = this.responseText;

    if (response.trim() != "Good!") {
      document.querySelector(`#${errorField}`).innerHTML = this.responseText;
    } else {
      document.querySelector(`#${errorField}`).innerHTML = "";
    }
  };

  XHR.open("POST", "validate_acc_credentials.php");
  XHR.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  XHR.send(data);
}

function createNewTypeCategory() {
  document.querySelector(".modal-box").classList.remove("hide");
  document.querySelector("#new-role-box").classList.remove("hide");
}

let newRoleTypeCategory = document.querySelector("#new-role-type");

//Validating new role type category name input
validateCredentials(
  newRoleTypeCategory.value,
  "userRole",
  "role-type-create-error"
);

newRoleTypeCategory.addEventListener("keyup", function () {
  validateCredentials(
    newRoleTypeCategory.value,
    "userRole",
    "role-type-create-error"
  );
});

// Submitting new role type category
const addRoleTypeForm = document.querySelector("#add-role-type");
addRoleTypeForm.addEventListener("submit", (e) => {
  e.preventDefault();
  const XHR = new XMLHttpRequest();
  const data = "request=addRoleTypeCategory&data=" + newRoleTypeCategory.value;

  XHR.onload = function () {
    let response = this.responseText;
    console.log("Response: " + response);

    if (response.trim() == "Good!") {
      // Show success message
      document.querySelector("#role-type-create-error").innerHTML = "";
      document.querySelector("#selected-role-type").value = "";
      loadRoleCategories();
      showSuccessMsgBox();
    } else {
      document.querySelector("#role-type-create-error").innerHTML =
        this.responseText;
    }
  };

  XHR.open("POST", "add_role_type.php");
  XHR.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  XHR.send(data);
});

function showSuccessMsgBox() {
  console.log("success!");
  // document.querySelector(".add-category").classList.add("hide");
  document.querySelector("#new-role-box").classList.add("hide");
  document.querySelector("#new-department-box").classList.add("hide");
  const successMsgBox = document.querySelector(".successMsg-box");
  successMsgBox.classList.remove("hide");
}

const doneBtn = document.querySelector("#done-btn");

doneBtn.addEventListener("click", () => {
  console.log("triggered");
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
  let options = document.querySelectorAll(".role-type-option");
  options.forEach((option) => {
    if (option.value == newRoleTypeCategory.value) {
      console.log("Selected:" + option.value);
      option.selected = true;
    }
  });

  options = document.querySelectorAll(".department-option");

  options.forEach((option) => {
    if (option.value == newDepartment.value) {
      console.log("Selected:" + option.value);
      option.selected = true;
    }
  });

  newRoleTypeCategory.value = "";

  newDepartment.value = "";
  // document.querySelector(".add-category").classList.remove("hide");
  document.querySelector("#new-role-box").classList.add("hide");
  document.querySelector("#new-department-box").classList.add("hide");

  const successMsgBox = document.querySelector(".successMsg-box");
  successMsgBox.classList.add("hide");
  validateCredentials(userRole.value, "", "role-type-error");
  modalBox.classList.add("hide");
}
