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
  } else if (currentUrl.includes("user")) {
    clearCurrentTab();
    document.querySelector("#user-tab").classList.add("current");
  } else {
    // None
  }
}

function clearCurrentTab() {
  let menus = document.querySelectorAll(".menu-item");

  menus.forEach((menuItem) => {
    if (menuItem.classList.contains("current")) {
      menuItem.classList.remove("current");
    }
  });
}
