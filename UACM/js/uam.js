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
      //   categoryList.insertBefore(category, last);
      categoryList.appendChild(category);
    }
  };

  XHR.open("POST", "get_departments.php");
  XHR.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  XHR.send(data);
}

// Search handler
let searchInput = document.querySelector("#user-search-input");

searchInput.addEventListener("keyup", () => {
  resetFilter();
  handleFilter();
});

// Filter user by department
let department = document.querySelector("#department");

department.addEventListener("change", handleDepartmentFilter);

function handleDepartmentFilter() {
  resetFilter();
  handleFilter();
}

// filter user search results
function handleFilter() {
  let searchInput = document.querySelector("#user-search-input").value;
  let selectedDept = document.querySelector("#department").value;

  console.log(searchInput);
  console.log(selectedDept);
  const records = document.querySelectorAll(".record");

  records.forEach((record) => {
    if (searchInput == "" && selectedDept == "all") {
      resetFilter();
    } else {
      if (
        record.children[0].innerHTML
          .toLowerCase()
          .includes(searchInput.toLowerCase()) &&
        selectedDept == "all"
      ) {
        console.log(searchInput);
        console.log(selectedDept);
      } else if (
        searchInput != "" &&
        record.children[0].innerHTML
          .toLowerCase()
          .includes(searchInput.toLowerCase()) &&
        record.children[3].innerHTML == selectedDept
      ) {
        console.log(searchInput);
        console.log(selectedDept);
      } else if (
        searchInput == "" &&
        record.children[3].innerHTML == selectedDept
      ) {
        console.log(searchInput);
        console.log(selectedDept);
      } else {
        record.classList.add("hide");
      }
    }
  });
}

function resetFilter() {
  const records = document.querySelectorAll(".record");
  records.forEach((record) => {
    record.classList.remove("hide");
  });
}
