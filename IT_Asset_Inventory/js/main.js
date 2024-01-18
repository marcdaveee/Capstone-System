// for side-nav navigation

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

// Load location options
loadLocationOptions();

function loadLocationOptions() {
  const XHR = new XMLHttpRequest();
  const data = "request=locationOptions";

  XHR.onload = function () {
    const locations = JSON.parse(this.responseText);
    const locationList = document.querySelector("#department-select");
    console.log(locations);

    let location = "";
    for (let x in locations) {
      location = document.createElement("option");
      location.id = `${locations[x].location_option}`;
      location.className = "location-option";
      location.value = `${locations[x].location_option}`;
      location.innerHTML = `${locations[x].location_option}`;
      // locationList.insertBefore(location, last);
      locationList.appendChild(location);
    }
  };

  XHR.open("POST", "/Capstone_System/IT_Asset_Inventory/get_categories.php");
  XHR.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  XHR.send(data);
}

// Filter hardware assets by location

document
  .querySelector("#department-select")
  .addEventListener("change", handleLocationFilter);

function handleLocationFilter(e) {
  resetFilter();

  let location = this.value;
  let selectedItemType = document.querySelector("#item-type-select").value;

  // handleFilter(location, selectedItemType);
  handleFilter();
}

// Load location options
loadItemTypeOptions();

function loadItemTypeOptions() {
  const XHR = new XMLHttpRequest();
  const data = "request=itemTypeCategories";

  XHR.onload = function () {
    const itemTypeOptions = JSON.parse(this.responseText);
    const itemTypeList = document.querySelector("#item-type-select");
    console.log(itemTypeOptions);

    let itemTypeOption = "";
    for (let x in itemTypeOptions) {
      itemTypeOption = document.createElement("option");
      itemTypeOption.id = `${itemTypeOptions[x].item_type_category}`;
      itemTypeOption.className = "item-type-option";
      itemTypeOption.value = `${itemTypeOptions[x].item_type_category}`;
      itemTypeOption.innerHTML = `${itemTypeOptions[x].item_type_category}`;
      // locationList.insertBefore(location, last);
      itemTypeList.appendChild(itemTypeOption);
    }
  };

  XHR.open("POST", "/Capstone_System/IT_Asset_Inventory/get_categories.php");
  XHR.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  XHR.send(data);
}

// Filter hardware assets by type
document
  .querySelector("#item-type-select")
  .addEventListener("change", handleItemTypeFilter);

function handleItemTypeFilter(e) {
  resetFilter();
  let location = document.querySelector("#department-select").value;
  let selectedItemType = this.value;

  // handleFilter(location, selectedItemType);
  handleFilter();
}

// Filter hardware assets by status
document
  .querySelector("#status-select")
  .addEventListener("change", handleItemStatusFilter);

function handleItemStatusFilter(e) {
  resetFilter();
  handleFilter();
}

// Filter hardware assets by search input

const hardwareSearch = document.querySelector("#asset-filter-input");

hardwareSearch.addEventListener("keyup", () => {
  resetFilter();
  handleFilter();
});

// filter hardware assets results
function handleFilter() {
  let searchInput = document.querySelector("#asset-filter-input").value;
  let location = document.querySelector("#department-select").value;
  let selectedItemType = document.querySelector("#item-type-select").value;
  let status = document.querySelector("#status-select").value;

  const records = document.querySelectorAll(".record");

  records.forEach((record) => {
    if (
      searchInput == "" &&
      location == "all" &&
      selectedItemType == "all" &&
      status == "all"
    ) {
      resetFilter();
    } else {
      if (
        location == "all" &&
        record.children[2].innerHTML == selectedItemType &&
        record.children[6].firstChild.innerHTML == status &&
        searchInput == ""
      ) {
        console.log(location);
        console.log(selectedItemType);
      } else if (
        selectedItemType == "all" &&
        record.children[5].innerHTML == location &&
        record.children[6].firstChild.innerHTML == status &&
        searchInput == ""
      ) {
        console.log(location);
        console.log(selectedItemType);
      } else if (
        status == "all" &&
        record.children[5].innerHTML == location &&
        record.children[2].innerHTML == selectedItemType &&
        searchInput == ""
      ) {
        console.log(location);
        console.log(selectedItemType);
      } else if (
        location == "all" &&
        selectedItemType == "all" &&
        record.children[6].firstChild.innerHTML == status &&
        searchInput == ""
      ) {
        console.log(record.children[6].innerHTML);
        // Skip
      } else if (
        record.children[5].innerHTML == location &&
        selectedItemType == "all" &&
        status == "all" &&
        searchInput == ""
      ) {
        // skip
      } else if (
        location == "all" &&
        record.children[2].innerHTML == selectedItemType &&
        status == "all" &&
        searchInput == ""
      ) {
        // skip
      } else if (
        searchInput != "" &&
        record.children[0].innerHTML
          .toLowerCase()
          .includes(searchInput.toLowerCase()) &&
        location == "all" &&
        selectedItemType == "all" &&
        status == "all"
      ) {
        // skip
      } else if (
        searchInput != "" &&
        record.children[0].innerHTML
          .toLowerCase()
          .includes(searchInput.toLowerCase()) &&
        record.children[5].innerHTML == location &&
        selectedItemType == "all" &&
        status == "all"
      ) {
        // skip
      } else if (
        searchInput != "" &&
        record.children[0].innerHTML
          .toLowerCase()
          .includes(searchInput.toLowerCase()) &&
        location == "all" &&
        selectedItemType == "all" &&
        record.children[6].firstChild.innerHTML == status
      ) {
        // skip
      } else if (
        searchInput != "" &&
        record.children[0].innerHTML
          .toLowerCase()
          .includes(searchInput.toLowerCase()) &&
        location == "all" &&
        record.children[2].innerHTML == selectedItemType &&
        status == "all"
      ) {
        // Skip
      } else if (
        searchInput != "" &&
        record.children[0].innerHTML
          .toLowerCase()
          .includes(searchInput.toLowerCase()) &&
        record.children[5].innerHTML == location &&
        record.children[2].innerHTML == selectedItemType &&
        status == "all"
      ) {
        // skip
      } else if (
        searchInput != "" &&
        record.children[0].innerHTML
          .toLowerCase()
          .includes(searchInput.toLowerCase()) &&
        record.children[5].innerHTML == location &&
        selectedItemType == "all" &&
        record.children[6].firstChild.innerHTML == status
      ) {
        // skip
      } else if (
        searchInput != "" &&
        record.children[0].innerHTML
          .toLowerCase()
          .includes(searchInput.toLowerCase()) &&
        location == "all" &&
        record.children[2].innerHTML == selectedItemType &&
        record.children[6].firstChild.innerHTML == status
      ) {
        // skip
      } else {
        if (
          searchInput != "" &&
          record.children[0].innerHTML
            .toLowerCase()
            .includes(searchInput.toLowerCase()) &&
          record.children[5].innerHTML == location &&
          record.children[2].innerHTML == selectedItemType &&
          record.children[6].firstChild.innerHTML == status
        ) {
          console.log(location);
          console.log(selectedItemType);
        } else {
          record.classList.add("hide");
        }
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
