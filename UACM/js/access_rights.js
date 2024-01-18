const accessMatrixTb = document.querySelector(".access-matrix-table tbody");
let folders = document.querySelectorAll(".folder-path-info");

// clicking folders with subfolders
accessMatrixTb.addEventListener("click", (e) => {
  if (e.target.classList.contains("clickable")) {
    let target = e.target;
    rotateChevron(target);
    let pathName = target.previousElementSibling.value;

    const XHR = new XMLHttpRequest();
    const data = "request=getSubFolders&pathInfo=" + pathName;

    XHR.onload = function () {
      const subFolders = JSON.parse(this.responseText);

      for (let x in subFolders) {
        let folderName = `${subFolders[x].folder_path_name}`;
        toggleSubfolders(folderName);
      }
    };

    XHR.open("POST", "get_subfolders.php");
    XHR.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    XHR.send(data);

    // console.log(pathName);
    // folders.forEach((folder) => {
    //   folderName = folder.value;
    //   if (folderName.includes(pathName)) {
    //     folder.parentElement.classList.remove("hide");
    //   }
    // });
  }
});

function toggleSubfolders(folderPathName) {
  folders.forEach((folder) => {
    folderName = folder.value;
    if (folderName == folderPathName) {
      folder.parentElement.classList.toggle("hide");
    }
  });
}

function rotateChevron(target) {
  let icon = target.firstChild;

  icon.classList.toggle("fa-chevron-right");
  icon.classList.toggle("fa-chevron-down");
}

// checkbox auto check
accessMatrixTb.addEventListener("click", (e) => {
  if (
    e.target.classList.contains("upload-check-box") ||
    e.target.classList.contains("view-check-box")
  ) {
    let targetCheckBox = e.target.value;
    let pathInfo = targetCheckBox.split("-");
    pathInfo = pathInfo[1] + "-" + pathInfo[2];

    pathInfo = document.querySelector(`#${pathInfo}`);
    pathInfo = pathInfo.firstElementChild;
    pathInfo = pathInfo.value;

    // check all parent folders
    const XHR = new XMLHttpRequest();
    const data = "request=getParentFolders&pathInfo=" + pathInfo;

    XHR.onload = function () {
      const parentFolders = JSON.parse(this.responseText);

      for (let x in parentFolders) {
        let folderName = `${parentFolders[x]}`;
        autoCheckFolder(folderName);
      }
    };

    XHR.open("POST", "get_subfolders.php");
    XHR.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    XHR.send(data);

    // if upload is checked, view must also be checked
    if (e.target.classList.contains("upload-check-box")) {
      let targetUploadCheckBox = e.target.parentElement;
      let targetViewCheckBox = targetUploadCheckBox.previousElementSibling;
      targetViewCheckBox = targetViewCheckBox.firstChild;
      targetViewCheckBox.checked = true;
    }
  }
});

function autoCheckFolder(folderPathInfo) {
  folders.forEach((folder) => {
    if (folder.value == folderPathInfo) {
      let checkBoxName = folder.name;
      let viewCheckBoxes = document.querySelectorAll(".view-check-box");
      viewCheckBoxes.forEach((viewCheckBox) => {
        if (viewCheckBox.value == "view-" + checkBoxName) {
          viewCheckBox.checked = true;
        }
      });
    }
  });
}

const updateUacBtn = document.querySelector("#update-uac-rights");
const modalBox = document.querySelector(".modal-box");

modalBox.addEventListener("click", (e) => {
  if (
    e.target.classList.contains("modal-box") &&
    !modalBox.classList.contains("hide")
  ) {
    closeModalBox();
  }
});

function closeModalBox() {
  const confirmationBox = document.querySelector(".confirmation-box");
  const successMsgBox = document.querySelector(".successMsg-box");

  modalBox.classList.add("hide");
  confirmationBox.classList.remove("hide");
  successMsgBox.classList.add("hide");
}

updateUacBtn.addEventListener("click", () => {
  modalBox.classList.remove("hide");
});

// Confirmed uac updated
const cancelBtn = document.querySelector("#cancel-btn");
const confirmUpdateBtn = document.querySelector("#confirm-uac-update");

cancelBtn.addEventListener("click", () => {
  closeModalBox();
});

confirmUpdateBtn.addEventListener("click", () => {
  let email = document.querySelector("#email");
  email = email.value;

  resetUacRights(email);
});

function getRightsInfo(email) {
  let viewCheckBoxes = document.querySelectorAll(".view-check-box");

  viewCheckBoxes.forEach((viewCheckBox) => {
    if (viewCheckBox.checked == true) {
      let checkBoxId = viewCheckBox.value;
      checkBoxId = checkBoxId.split("-");
      checkBoxId = checkBoxId[1] + "-" + checkBoxId[2];
      console.log(checkBoxId);

      let uploadCheckBoxSibling = document.querySelector(
        `[name=upload-${checkBoxId}]`
      );

      let pathInfo = document.querySelector(`[name=${checkBoxId}]`);
      pathInfo = pathInfo.value;

      let accessLevel = "";

      if (uploadCheckBoxSibling.checked == true) {
        accessLevel = 2;
        updateUacRights(email, accessLevel, pathInfo);
      } else {
        accessLevel = 1;
        updateUacRights(email, accessLevel, pathInfo);
      }
    }
  });

  showSuccessMsg();
}

function resetUacRights(email) {
  const XHR = new XMLHttpRequest();
  const data = "request=resetUac&pathInfo=&accessLevel=&email=" + email;

  XHR.onload = function () {
    let response = this.responseText;
    console.log("reset");
    getRightsInfo(email);
  };

  XHR.open("POST", "update_uac_rights.php");
  XHR.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  XHR.send(data);
}

function updateUacRights(email, accessLevel, pathName) {
  const XHR = new XMLHttpRequest();
  const data =
    "request=updateUac&pathInfo=" +
    pathName +
    "&accessLevel=" +
    accessLevel +
    "&email=" +
    email;

  XHR.onload = function () {
    let response = this.responseText;
    console.log("updated!");
  };

  XHR.open("POST", "update_uac_rights.php");
  XHR.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  XHR.send(data);
}

function showSuccessMsg() {
  const confirmationBox = document.querySelector(".confirmation-box");
  const successMsgBox = document.querySelector(".successMsg-box");

  confirmationBox.classList.add("hide");
  successMsgBox.classList.remove("hide");
}

const doneBtn = document.querySelector("#done-btn");

doneBtn.addEventListener("click", () => {
  closeModalBox();
});
