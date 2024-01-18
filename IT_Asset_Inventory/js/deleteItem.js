// Set hardware asset's status to inactive

const deleteBtn = document.querySelector("#delete-btn");
const modalBox = document.querySelector(".modal-box");
const confirmationMsgBox = document.querySelector(".confirmation-box");
const cancelBtn = document.querySelector("#cancel-btn");
const confirmDelBtn = document.querySelector(".confirmation-box .btn-warn");
const doneBtn = document.querySelector("#done-btn");

deleteBtn.addEventListener("click", showConfirmationMsg);

modalBox.addEventListener("click", checkTarget);

// Show confirmation message box
function showConfirmationMsg() {
  modalBox.classList.remove("hide");
  confirmationMsgBox.classList.remove("hide");
}

function checkTarget(e) {
  const successMsgBox = document.querySelector(".successMsg-box");

  if (
    e.target.classList.contains("modal-box") &&
    !successMsgBox.classList.contains("hide")
  ) {
    modalBox.classList.add("hide");
    successMsgBox.classList.add("hide");
    window.location.replace(
      "http://localhost/Capstone_System/IT_Asset_Inventory/hardware_assets.php"
    );
  }
  removeConfirmationBox();
}

cancelBtn.addEventListener("click", removeConfirmationBox);

confirmDelBtn.addEventListener("click", removeRecord);

// Update asset's status to inactive
function removeRecord(e) {
  const XHR = new XMLHttpRequest();
  const elementId = e.target.id;
  const targetId = "targetId=" + elementId.slice(12);
  console.log(targetId);

  XHR.onload = function () {
    const successMsgBox = document.querySelector(".successMsg-box");
    confirmationMsgBox.classList.add("hide");
    modalBox.classList.remove("hide");
    successMsgBox.classList.remove("hide");

    // Show success message
    const successMsg = document.querySelector(".successMsg-box .successMsg");
    const checkIcon = `<i class="fa-regular fa-circle-check">`;
    successMsg.innerHTML = this.responseText + " " + checkIcon;

    console.log(this.responseText);
  };

  XHR.open("POST", "delete_hardware_asset.php");
  XHR.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  XHR.send(targetId);
}

// Redirect back to main page
doneBtn.addEventListener("click", redirectToMain);

function redirectToMain(e) {
  if (e.target.id == "done-btn") {
    console.log(e.target);
    const successMsgBox = document.querySelector(".successMsg-box");
    successMsgBox.classList.add("hide");
    modalBox.classList.add("hide");
    window.location.replace(
      "http://localhost/Capstone_System/IT_Asset_Inventory/hardware_assets.php"
    );
  }
}

// Remove confirmation message box
function removeConfirmationBox() {
  modalBox.classList.add("hide");
}
