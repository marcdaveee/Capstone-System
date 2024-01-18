// Load incident type categories
loadIncidentTypeOptions();

function loadIncidentTypeOptions() {
  const XHR = new XMLHttpRequest();
  const data = "request=incidentType";

  XHR.onload = function () {
    const incidentTypes = JSON.parse(this.responseText);
    const incidentTypeList = document.querySelector("#incident-type");
    const last = document.querySelector(".last");
    console.log(incidentTypes);

    if (incidentTypeList.children.length > 2) {
      console.log("More than two options!");
      reloadCategories();
    }

    let incidentTypeOption = "";

    for (let x in incidentTypes) {
      incidentTypeOption = document.createElement("option");
      incidentTypeOption.id = `${incidentTypes[x].property_value}`;
      incidentTypeOption.className = "incident-type-option";
      incidentTypeOption.value = `${incidentTypes[x].property_value}`;
      incidentTypeOption.innerHTML = `${incidentTypes[x].property_value}`;
      incidentTypeList.insertBefore(incidentTypeOption, last);
    }

    let selectedIncidentType = document.querySelector(
      "#selected-incident-type"
    ).value;

    console.log("Selected option id: " + selectedIncidentType.value);
    let options = document.querySelectorAll(".incident-type-option");
    options.forEach((option) => {
      if (option.value == selectedIncidentType) {
        console.log("Selected:" + option.value);
        option.selected = true;
      }
    });
  };

  XHR.open("POST", "get-ticket-properties.php");
  XHR.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  XHR.send(data);
}

function reloadCategories() {
  let options = document.querySelectorAll(".incident-type-option");
  options.forEach((option) => {
    option.remove();
  });
}

const incidentTypeInput = document.querySelector("#incident-type");

// Check if incident type field value is empty
if (incidentTypeInput) {
  incidentTypeInput.addEventListener("change", function () {
    if (this.value == "Define New") {
      createNewTypeCategory();
    }
  });
}

// Display for creating new incident type category
function createNewTypeCategory() {
  document.querySelector(".modal-box").classList.remove("hide");
}

// Handling new incident type input
let newIncidentTypeCategory = document.querySelector("#new-incident-type");

//Validate input for new incident type category (is empty, if exists already, if valid)
if (newIncidentTypeCategory) {
  checkIfExist(
    newIncidentTypeCategory.value,
    "incidentProperty",
    "incident-type-create-error"
  );
}

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

  XHR.open("POST", "validate-ticket-props.php");
  XHR.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  XHR.send(data);
}

// Submitting new incident type category
const addIncidentTypeForm = document.querySelector("#add-incident-type");

if (addIncidentTypeForm) {
  addIncidentTypeForm.addEventListener("submit", (e) => {
    e.preventDefault();
    const XHR = new XMLHttpRequest();
    const data =
      "request=addIncidentType&data=" + newIncidentTypeCategory.value;

    XHR.onload = function () {
      let response = this.responseText;
      console.log("Response: " + response);

      if (response.trim() == "Good!") {
        // Show success message
        document.querySelector("#incident-type-create-error").innerHTML = "";
        loadIncidentTypeOptions();
        showSuccessMsgBox();
      } else {
        document.querySelector("#incident-type-create-error").innerHTML =
          this.responseText;
      }
    };

    XHR.open("POST", "update-ticket-props.php");
    XHR.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    XHR.send(data);
  });
}

function showSuccessMsgBox() {
  console.log("success!");
  document.querySelector(".add-category").classList.add("hide");
  const successMsgBox = document.querySelector(".successMsg-box");
  successMsgBox.classList.remove("hide");
}

const doneBtn = document.querySelector("#done-btn");

if (doneBtn) {
  doneBtn.addEventListener("click", () => {
    closeModal();
  });
}

let modalBox = document.querySelector(".modal-box");

if (modalBox) {
  modalBox.addEventListener("click", (e) => {
    if (e.target.className == "modal-box") {
      closeModal();
    }
  });
}

let closeBtn = document.querySelector(".add-category .close");
if (closeBtn) {
  closeBtn.addEventListener("click", (e) => {
    closeModal();
  });
}

function closeModal() {
  let options = document.querySelectorAll(".incident-type-option");
  options.forEach((option) => {
    if (option.value == newIncidentTypeCategory.value) {
      console.log("Selected:" + option.value);
      option.selected = true;
    }
  });
  newIncidentTypeCategory.value = "";
  document.querySelector(".add-category").classList.remove("hide");
  const successMsgBox = document.querySelector(".successMsg-box");
  successMsgBox.classList.add("hide");
  modalBox.classList.add("hide");
}

// Actions Taken Form Input pops up when ticket status is "Resolved"

const ticketStatusInput = document.querySelector("#ticket-status");

if (ticketStatusInput) {
  ticketStatusInput.addEventListener("change", function () {
    if (this.value == "Resolved") {
      showActionsTakenForm();
    } else {
      if (this.value == "Pending") {
        // actions taken input field
        const actionsTakenFormInput = document.querySelector("#actions-taken");
        actionsTakenFormInput.value = "";
      }
    }
  });
}

// actions taken form will appear when ticket is resolved
const actionsTakenForm = document.querySelector(".actions-taken-form");

function showActionsTakenForm() {
  actionsTakenForm.classList.remove("hide");
}

// close button for actions taken form
const closeActionsTakenForm = document.querySelector(".close-actions-taken");

if (closeActionsTakenForm) {
  closeActionsTakenForm.addEventListener("click", (e) => {
    if (actionsTakenForm) {
      actionsTakenForm.classList.add("hide");
      ticketStatusInput.value = "";
    }
  });
}

// Done button for actions taken form
const doneActionsTakenForm = document.querySelector(".done-actions-taken");

if (doneActionsTakenForm) {
  doneActionsTakenForm.addEventListener("click", (e) => {
    if (actionsTakenForm) {
      actionsTakenForm.classList.add("hide");
    }
  });
}

// Execute when there's an error message in the actions taken form
const actionsTakenErrorField = document.querySelector("#actions-taken-error");

if (actionsTakenErrorField) {
  if (actionsTakenErrorField.innerHTML != "") {
    actionsTakenForm.classList.remove("hide");
  }
}
