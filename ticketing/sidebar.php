   <!-- Side Bar Navigation -->
   <?php 
    

    if($_SESSION["role"] != "admin"){
        header("Location: /Capstone_System/404.php");
        exit();
    }

    $username = $_SESSION["username"];        
   
?>
   
   <section class="side-bar" id="ticket-side-nav">
      <!-- <h2 class="header txt-md my-3">Ticket</h2> -->
      <ul class="flex-column flex-align-left flex-just-sb">

        <div class="flex-column flex-align-left flex-just-left mt-4">
            <li class="my-1 flex flex-just-left nav-link" id="dashboard-nav">               
                <!-- <div class="block"> -->
                    <a href="/Capstone_System/ticketing/dashboard.php" id="folders-tab" class="block txt-fw-2 flex py-1"> <i class="fa-solid fa-chart-line mx-1 ml-2"></i>Dashboard</a>
                <!-- </div>                 -->
            </li>

            <li class="my-1 flex flex-just-left nav-link" id="all-ticket-nav"> 
                <!-- <div class="flex nav-link" id="shared-with-nav"> -->
                    <a href="/Capstone_System/ticketing/tickets.php" id="" class="block txt-fw-2 flex py-1"> <i class="fa-solid fa-ticket mx-1 ml-2"></i>All Tickets</a>
                <!-- </div>                 -->
            </li>
                   

            <li class="my-1 flex flex-just-left nav-link"  id="knowledge-base-nav">
                <!-- <div class="block"> -->
                    <a href="/Capstone_System/ticketing/knowledge-base.php" id="" class="block txt-fw-2 flex py-1"> <i class="fa-solid fa-book-open-reader mx-1 ml-2"></i>Knowledge Base</a>
                <!-- </div>                 -->
            </li>     

            <li class="my-1 flex flex-just-left nav-link mt-4"  id="knowledge-base-nav">
                <!-- <div class="block"> -->
                    <a href="/Capstone_System/main-menu.php" id="" class="block txt-fw-2 flex py-1"> <i class="fas fa-left-long mx-1 ml-2"></i>Back to Menu</a>
                <!-- </div>                 -->
            </li>     
                        
        </div>

      

        <li class=" flex">            
            <a href="/Capstone_System/logout.php" id="" class="btn btn-secondary  txt-fw-2"> <i class="fa-solid fa-right-from-bracket mx-1 invert-left"></i>Logout</a>            
        </li>
        
      </ul>

      <script>
        // handle side nav in user panel
        const ticketSideNav = document.querySelector("#ticket-side-nav");

        if (ticketSideNav) {
            getCurrentLink();
        }

        function getCurrentLink() {
            let currentUrl = window.location.pathname;

            if (currentUrl.includes("dashboard")) {
                clearCurrentTab();
                document.querySelector("#dashboard-nav").classList.add("current-secondary");
            } else if (currentUrl.includes("tickets") || currentUrl.includes("edit") || currentUrl.includes("resolve") || currentUrl.includes("create")) {
                clearCurrentTab();
                document.querySelector("#all-ticket-nav").classList.add("current-secondary");
            } else if (currentUrl.includes("knowledge") || currentUrl.includes("view-res")) {
                clearCurrentTab();
                document.querySelector("#knowledge-base-nav").classList.add("current-secondary");
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
      </script>

    </section>