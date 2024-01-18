const months = [
  "Jan",
  "Feb",
  "Mar",
  "Apr",
  "May",
  "Jun",
  "Jul",
  "Aug",
  "Sept",
  "Oct",
  "Nov",
  "Dec",
];

function fetchIncidentInfo() {
  const incidentData = "";
  let xhr = new XMLHttpRequest();
  xhr.open("GET", "get-incident-count.php", true);
  xhr.send();
  xhr.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      incidentData = JSON.parse(this.responseText);
      console.log(incidentData);
    }
  };
  return incidentData;
}

function getCurrentYear() {
  const d = new Date();
  return d.getFullYear();
}

// Get all data incidents per month in the current year
function getDataByMonth() {
  let incidentData = "";
  let xhr = new XMLHttpRequest();
  xhr.open("GET", "get-incident-count.php", true);
  xhr.send();
  xhr.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      incidentData = JSON.parse(this.responseText);

      const currentYear = getCurrentYear();

      console.log(incidentData);

      let currYearIncidents = incidentData.filter((data) => {
        let year = new Date(data.incident_date);
        year = year.getFullYear();
        console.log(year);
        console.log(currentYear);
        if (year === currentYear) {
          return data;
        }
      });

      console.log(currYearIncidents);

      let monthsInfoArr = currYearIncidents.map((data) => {
        const date = new Date(data.incident_date);
        return months[date.getMonth()];
      });
      console.log(monthsInfoArr);

      let perMonthInfoData = getInfoPerMonth(monthsInfoArr);

      console.log(perMonthInfoData);

      const ctx = document.getElementById("myChart");

      const bgColor = {
        id: "bgColor",
        beforeDraw: (chart, options) => {
          const { ctx, width, height } = chart;
          ctx.fillStyle = "#fff";
          ctx.fillRect(0, 0, width, height);
          ctx.restore();
        },
      };

      new Chart(ctx, {
        type: "line",
        data: {
          labels: months,
          datasets: [
            {
              label: "Incident Count for Year 2023",
              data: perMonthInfoData,
              borderWidth: 1,
            },
          ],
        },
        options: {
          scales: {
            y: {
              beginAtZero: true,
            },
          },
        },
        plugins: [bgColor],
      });
    }
  };
}

function getInfoPerMonth(monthsInfoArr) {
  const perMonthsArr = [];

  for (let i = 0; i < 12; i++) {
    perMonthsArr[i] = getCountsOfMonth(monthsInfoArr, i);
  }

  return perMonthsArr;
}

function getCountsOfMonth(monthsData, monthToCount) {
  let count = 0;
  monthsData.forEach((month) => {
    if (month == months[monthToCount]) {
      count += 1;
    }
  });

  return count;
}

getDataByMonth();

function generateReport() {
  const canvas = document.querySelector("#myChart");
  // Create image

  const canvasImage = canvas.toDataURL("image/jpeg", 1.0);

  console.log(canvasImage);
  // convert image to pdf

  let pdf = new jsPDF("landscape");
  pdf.setFontSize(20);
  pdf.addImage(canvasImage, "JPEG", 20, 15);
  pdf.save("Security-Incident-Report.pdf");
}
