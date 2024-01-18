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
  };

  XHR.open("POST", "get-ticket-properties.php");
  XHR.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  XHR.send(data);
}

// handles filtering of tickets table

let incidentType = document.querySelector("#incident-type");

incidentType.addEventListener("change", () => {
  resetFilter();
  handleFilter();
});

// filter hardware assets results
function handleFilter() {
  let incidentTypeValue = document.querySelector("#incident-type").value;

  const records = document.querySelectorAll(".card");

  records.forEach((record) => {
    if (incidentTypeValue == "all") {
      resetFilter();
    } else {
      if (incidentTypeValue == "all") {
        // Don't filter
      } else {
        if (
          record.children[1].children[0].children[0].innerHTML.includes(
            incidentTypeValue
          )
        ) {
          // don't skip
        } else {
          record.classList.add("hide");
        }
      }
    }
  });
}

// Reset filter
function resetFilter() {
  const records = document.querySelectorAll(".card");
  records.forEach((record) => {
    record.classList.remove("hide");
  });
}
