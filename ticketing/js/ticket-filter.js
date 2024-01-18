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

let prioritylevel = document.querySelector("#priority-level");

prioritylevel.addEventListener("change", () => {
  resetFilter();
  handleFilter();
});

let statusType = document.querySelector("#status-type");

statusType.addEventListener("change", () => {
  resetFilter();
  handleFilter();
});

// filter hardware assets results
function handleFilter() {
  let incidentTypeValue = document.querySelector("#incident-type").value;
  let priorityLevelValue = document.querySelector("#priority-level").value;
  let statusValue = document.querySelector("#status-type").value;

  console.log("Incident Type Value: ", incidentTypeValue);
  console.log("Priority Level: ", priorityLevelValue);
  console.log("Status: ", statusValue);

  const records = document.querySelectorAll(".ticket-row");

  records.forEach((record) => {
    console.log("Record Incident Type Value: ", record.children[2].innerHTML);

    if (
      incidentTypeValue == "all" &&
      priorityLevelValue == "all" &&
      statusValue == "all"
    ) {
      resetFilter();
    } else {
      if (
        incidentTypeValue == "all" &&
        record.children[4].innerHTML.includes(priorityLevelValue) &&
        record.children[7].innerHTML.includes(statusValue)
      ) {
        // Don't filter
      } else if (
        record.children[2].innerHTML.includes(incidentTypeValue) &&
        priorityLevelValue == "all" &&
        record.children[7].innerHTML.includes(statusValue)
      ) {
        // Don't filter
      } else if (
        record.children[2].innerHTML.includes(incidentTypeValue) &&
        record.children[4].innerHTML.includes(priorityLevelValue) &&
        statusValue == "all"
      ) {
        // Don't filter
      } else if (
        record.children[2].innerHTML.includes(incidentTypeValue) &&
        priorityLevelValue == "all" &&
        statusValue == "all"
      ) {
        console.log(record);
        // Don't filter
      } else if (
        incidentTypeValue == "all" &&
        record.children[4].innerHTML.includes(priorityLevelValue) &&
        statusValue == "all"
      ) {
        // Don't filter
      } else if (
        incidentTypeValue == "all" &&
        priorityLevelValue == "all" &&
        record.children[7].innerHTML.includes(statusValue)
      ) {
        // skip
      } else {
        if (
          record.children[2].innerHTML.includes(incidentTypeValue) &&
          record.children[4].innerHTML.includes(priorityLevelValue) &&
          record.children[7].innerHTML.includes(statusValue)
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
  const records = document.querySelectorAll(".ticket-row");
  records.forEach((record) => {
    record.classList.remove("hide");
  });
}

// Set corresponding colors based on the status of the ticket

let tickets = document.querySelectorAll(".ticket-row");

tickets.forEach((ticket) => {
  const priorityLevel = ticket.children[4].innerHTML;
  if (priorityLevel == "Low") {
    ticket.children[4].classList.add("low-state");
  } else if (priorityLevel == "Medium") {
    ticket.children[4].classList.add("inactive-state");
  } else {
    ticket.children[4].classList.add("critical-state");
  }

  const status = ticket.children[7].innerHTML;
  if (status == "Resolved") {
    ticket.children[7].classList.add("resolve-state");
  } else {
    ticket.children[7].classList.add("inactive-state");
  }
});
