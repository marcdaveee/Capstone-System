// for side-nav navigation
const menuItems = document.querySelectorAll(".menu-item");
const sideBar = document.querySelector(".side-bar");

getCurrentCategory();

function getCurrentCategory() {
  let currentUrl = window.location.pathname;
  // current == "/Capstone_System/hardware_assets.php"
  if (currentUrl.includes("hardware")) {
    console.log("exist!");
    clearCurrentTab();
    document
      .querySelector(".side-nav .sub-menu #hardware-asset-tab")
      .classList.add("current");
  } else if (currentUrl.includes("software")) {
    clearCurrentTab();
    document
      .querySelector(".side-nav .sub-menu #software-asset-tab")
      .classList.add("current");
  } else if (currentUrl.includes("folder")) {
    clearCurrentTab();
    document
      .querySelector(".side-nav .sub-menu #software-asset-tab")
      .classList.add("current");
  } else {
    // None
  }
}

function clearCurrentTab() {
  let menuItems = document.querySelectorAll(".menu-item");

  menuItems.forEach((menuItem) => {
    if (menuItem.classList.contains("current")) {
      menuItem.classList.remove("current");
    }
  });
}

// // Load location options
// loadLocationOptions();

// function loadLocationOptions() {
//   const XHR = new XMLHttpRequest();
//   const data = "request=locationOptions";

//   XHR.onload = function () {
//     const locations = JSON.parse(this.responseText);
//     const locationList = document.querySelector("#department-select");
//     console.log(locations);

//     let location = "";
//     for (let x in locations) {
//       location = document.createElement("option");
//       location.id = `${locations[x].location_option}`;
//       location.className = "location-option";
//       location.value = `${locations[x].location_option}`;
//       location.innerHTML = `${locations[x].location_option}`;
//       // locationList.insertBefore(location, last);
//       locationList.appendChild(location);
//     }
//   };

//   XHR.open("POST", "get_categories.php");
//   XHR.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
//   XHR.send(data);
// }

// Filter hardware assets by location

// document
//   .querySelector("#department-select")
//   .addEventListener("change", handleLocationFilter);

// function handleLocationFilter(e) {
//   resetFilter();

//   let location = this.value;
//   let selectedItemType = document.querySelector("#item-type-select").value;

//   // handleFilter(location, selectedItemType);
//   handleFilter();
// }

// Load location options
loadItemTypeOptions();

function loadItemTypeOptions() {
  const XHR = new XMLHttpRequest();
  const data = "request=softwareTypeOptions";

  XHR.onload = function () {
    const softwareTypeOptions = JSON.parse(this.responseText);
    const softwareTypeList = document.querySelector("#software-type-select");
    console.log(softwareTypeOptions);

    let softwareTypeOption = "";
    for (let x in softwareTypeOptions) {
      softwareTypeOption = document.createElement("option");
      softwareTypeOption.id = `${softwareTypeOptions[x].software_type_option}`;
      softwareTypeOption.className = "software-type-option";
      softwareTypeOption.value = `${softwareTypeOptions[x].software_type_option}`;
      softwareTypeOption.innerHTML = `${softwareTypeOptions[x].software_type_option}`;
      // locationList.insertBefore(location, last);
      softwareTypeList.appendChild(softwareTypeOption);
    }
  };

  XHR.open("POST", "get_categories.php");
  XHR.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  XHR.send(data);
}

// Filter software assets by type
document
  .querySelector("#software-type-select")
  .addEventListener("change", handleSoftwareTypeFilter);

function handleSoftwareTypeFilter(e) {
  resetFilter();
  //   let location = document.querySelector("#software-type-select").value;
  //   let selectedItemType = this.value;

  //   // handleFilter(location, selectedItemType);
  handleFilter();
}

const softwareSearch = document.querySelector("#asset-filter-input");

softwareSearch.addEventListener("keyup", () => {
  resetFilter();
  handleFilter();
});

// filter software assets results
function handleFilter() {
  //

  let searchInput = document.querySelector("#asset-filter-input").value;
  let selectedSoftwareType = document.querySelector(
    "#software-type-select"
  ).value;

  const records = document.querySelectorAll(".record");

  records.forEach((record) => {
    if (searchInput == "" && selectedSoftwareType == "all") {
      resetFilter();
    } else {
      if (
        searchInput == "" &&
        record.children[2].innerHTML == selectedSoftwareType
      ) {
        //skip
      } else if (
        searchInput != "" &&
        record.children[0].innerHTML
          .toLowerCase()
          .includes(searchInput.toLowerCase()) &&
        selectedSoftwareType == "all"
      ) {
        //skip
      } else if (
        searchInput != "" &&
        record.children[0].innerHTML
          .toLowerCase()
          .includes(searchInput.toLowerCase()) &&
        record.children[2].innerHTML == selectedSoftwareType
      ) {
        //skip
      } else {
        record.classList.add("hide");
      }
    }
  });
}

// Reset filter
function resetFilter() {
  const records = document.querySelectorAll(".record");
  records.forEach((record) => {
    record.classList.remove("hide");
  });
}

// Action button listener
document
  .querySelector(".asset-records tbody")
  .addEventListener("click", function (e) {
    if (e.target.classList.contains("ellipse")) {
      hideActions();
      let actionList = e.target.nextElementSibling;
      actionList.classList.remove("hide");
    } else {
      console.log("not detected");
      hideActions();
    }
  });

function hideActions() {
  let actionLists = document.querySelectorAll(".actions-list");
  actionLists.forEach((item) => {
    item.classList.add("hide");
  });
}
