// Handling folder creation
const addFolderBtn = document.querySelector("#add-folder");
const modalBox = document.querySelector(".modal-box");

// Handling user upload
const uploadFileBox = document.querySelector("#upload-file-box");
const close = document.querySelector(".close");

// Handling folder creation errors
const createFolderErrorMsg = document.querySelector("#folder-create-error");
const requestedByErrorMsg = document.querySelector("#requested-by-field-error");
const uploadFileErrorMsg = document.querySelector("#file-upload-error");
const fileSensiErrorMsg = document.querySelector("#file-sensitivity-error");

// Confirming upload
const confirmationBox = document.querySelector(".confirmation-box");
const uploadBtn = document.querySelector("#upload-file");
const cancelBtn = document.querySelector("#cancel-btn");
const continueBtn = document.querySelector("#continue-btn");

if (uploadBtn) {
  uploadBtn.addEventListener("click", () => {
    modalBox.classList.remove("hide");
  });
}

if (cancelBtn) {
  cancelBtn.addEventListener("click", () => {
    closeModalBox();
  });
}

if (continueBtn) {
  continueBtn.addEventListener("click", () => {
    confirmationBox.classList.add("hide");
    uploadFileBox.classList.remove("hide");
  });
}

if (createFolderErrorMsg) {
  if (createFolderErrorMsg.innerHTML != "") {
    modalBox.classList.remove("hide");
  }
}

if (createFolderErrorMsg && requestedByErrorMsg) {
  if (
    createFolderErrorMsg.innerHTML != "" ||
    requestedByErrorMsg.innerHTML != ""
  ) {
    modalBox.classList.remove("hide");
  }
}

if (uploadFileErrorMsg) {
  if (uploadFileErrorMsg.innerHTML != "") {
    modalBox.classList.remove("hide");
    if (uploadFileBox) {
      confirmationBox.classList.add("hide");
      uploadFileBox.classList.remove("hide");
    }
  }
}

// if (fileSensiErrorMsg) {
//   if (fileSensiErrorMsg.innerHTML != "") {
//     confirmationBox.classList.add("hide");
//     modalBox.classList.remove("hide");
//   }
//   if (uploadFileBox) {
//     // uploadFileBox.classList.remove("hide");
//   }
// }

if (addFolderBtn) {
  addFolderBtn.addEventListener("click", () => {
    modalBox.classList.remove("hide");
  });
}

if (modalBox) {
  modalBox.addEventListener("click", (e) => {
    if (
      !modalBox.classList.contains("hide") &&
      e.target.classList.contains("modal-box")
    ) {
      closeModalBox();
    }
  });
}

if (close) {
  close.addEventListener("click", () => {
    closeModalBox();
  });
}

function closeModalBox() {
  modalBox.classList.add("hide");
  if (createFolderErrorMsg) {
    createFolderErrorMsg.innerHTML = "";
  }
}

// Showing success message
const doneBtn = document.querySelector("#done-btn");

// if (doneBtn) {
//   doneBtn.addEventListener("click", () => {
//     window.location.reload(true);
//   });
// }

if (modalBox) {
  modalBox.addEventListener("click", (e) => {
    const successMsgBox = document.querySelector(".successMsg-box");
    if (successMsgBox) {
      if (!successMsgBox.classList.contains("hide")) {
        console.log("Test");
        // location.reload();
      }
    }
  });
}

// function showSuccessMsg(currentUrl, currentPath) {
//   const uploadFileBox = document.querySelector(".add-folder");
//   uploadFileBox.classList.add("hide");
//   const successMsgBox = document.querySelector(".successMsg-box");
//   successMsgBox.classList.remove("hide");

//   const doneBtn = document.querySelector("#done-btn");

//   doneBtn.addEventListener("click", () => {
//     window.location.replace(`${currentUrl}?path=${currentPath}`);
//   });
// }

// Removing file
const fileTable = document.querySelector(".file-table tbody");

const removeModalBox = document.querySelector("#remove-modal-box");

if (fileTable) {
  fileTable.addEventListener("click", (e) => {
    if (e.target.classList.contains("btn-delete")) {
      let targetFileId = e.target.id;

      removeModalBox.classList.remove("hide");
      const confirmDelBtn = document.querySelector("#confirm-del-btn");
      const cancelDelBtn = document.querySelector("#cancel-del-btn");

      if (confirmDelBtn || cancelDelBtn) {
        confirmDelBtn.addEventListener("click", (e) => {
          let currentPath =
            document.querySelector("#current-path").childNodes[1].nodeValue;
          console.log(currentPath);
          window.location.href =
            "/Capstone_System/storeshare-u/remove_file.php?file_id=" +
            targetFileId +
            "&currentPath=" +
            currentPath;
        });

        cancelDelBtn.addEventListener("click", (e) => {
          removeModalBox.classList.add("hide");
        });
      }
    }
  });
}

// handle side nav in user panel
const userSideNav = document.querySelector("#user-side-nav");

if (userSideNav) {
  getCurrentLink();
}

function getCurrentLink() {
  let currentUrl = window.location.pathname;

  if (currentUrl.includes("root") || currentUrl.includes("folder")) {
    clearCurrentTab();
    document.querySelector("#file-system-nav").classList.add("current");
  } else if (currentUrl.includes("shared")) {
    clearCurrentTab();
    document.querySelector("#shared-with-nav").classList.add("current");
  } else if (currentUrl.includes("user")) {
    clearCurrentTab();
    document.querySelector("#user-tab").classList.add("current");
  } else if (currentUrl.includes("profile")) {
    clearCurrentTab();
    document.querySelector(".profile-link").classList.add("current");
  } else if (currentUrl.includes("logs")) {
    clearCurrentTab();
    document.querySelector("#folder-info-nav").classList.add("current");
  } else {
    // None
  }
}

function clearCurrentTab() {
  const navLinks = document.querySelectorAll(".side-bar .nav-link");

  navLinks.forEach((navLink) => {
    if (navLink.classList.contains("current")) {
      navLink.classList.remove("current");
    }
  });
}

// Handles change password UI
const changePassBtn = document.querySelector("#change-pass-btn");

if (changePassBtn) {
  changePassBtn.addEventListener("click", () => {
    modalBox.classList.remove("hide");
  });
}

// Change password error fields

const currPassFieldErr = document.querySelector("#curr-password-field-error");

if (currPassFieldErr) {
  if (currPassFieldErr.innerHTML != "") {
    modalBox.classList.remove("hide");
  }
}

const passFieldErr = document.querySelector("#password-field-error");

if (passFieldErr) {
  if (passFieldErr.innerHTML != "") {
    modalBox.classList.remove("hide");
  }
}

const confirmPassFieldErr = document.querySelector(
  "#conf-password-field-error"
);

if (confirmPassFieldErr) {
  if (confirmPassFieldErr.innerHTML != "") {
    modalBox.classList.remove("hide");
  }
}

const successMsg = document.querySelector("#change-password-form .success-msg");

if (successMsg) {
  if (successMsg.innerHTML != "") {
    modalBox.classList.remove("hide");
  }
}

// Folder logs handler (search)

let folderNameInput = document.querySelector("#folder-search-input");

if (folderNameInput) {
  folderNameInput.addEventListener("keyup", () => {
    resetFilter();
    handleFolderFilter();
  });
}

function handleFolderFilter() {
  let searchInput = document.querySelector("#folder-search-input").value;

  const records = document.querySelectorAll(".record");

  if (searchInput) {
    records.forEach((record) => {
      if (searchInput == "") {
        resetFilter();
      } else {
        if (
          searchInput != "" &&
          record.children[0].innerHTML
            .toLowerCase()
            .includes(searchInput.toLowerCase())
        ) {
          // display folder
          console.log("found!");
        } else {
          record.classList.add("hide");
        }
      }
    });
  }
}

function resetFilter() {
  const records = document.querySelectorAll(".record");
  records.forEach((record) => {
    record.classList.remove("hide");
  });
}
