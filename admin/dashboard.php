<!DOCTYPE html>
<html lang="en">
<?php
include("../connection/connect.php");
session_start();

if (empty($_SESSION["adm_id"])) {
    header('location:index.php');
    exit();
}

function getCount($db, $table) {
    $sql = "SELECT COUNT(*) AS count FROM $table";
    $result = mysqli_query($db, $sql);
    if ($result) {
        $row = mysqli_fetch_assoc($result);
        return $row['count'];
    }
    return 0; // Return 0 if query fails
}

function getEarnings($db) {
    $result = mysqli_query($db, 'SELECT SUM(price) AS value_sum FROM users_orders WHERE status = "closed"'); 
    $row = mysqli_fetch_assoc($result); 
    return $row['value_sum'] ?: 0; // Default to 0 if null
}

?>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">    
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Admin Panel</title>
    <link href="css/lib/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link href="css/helper.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        canvas {
            max-width: 100%;
            height: 400px; /* Fixed height */
        }
    </style>
</head>
<body class="fix-header">
    <div class="preloader">
        <svg class="circular" viewBox="25 25 50 50">
            <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" /> 
        </svg>
    </div>
    <div id="main-wrapper">
        <div class="header">
            <nav class="navbar top-navbar navbar-expand-md navbar-light">
                <div class="navbar-header">
                    <a class="navbar-brand" href="dashboard.php">
                        <span><img src="images/icn.png" alt="homepage" class="dark-logo" /></span>
                    </a>
                </div>
                <div class="navbar-collapse">
                    <ul class="navbar-nav mr-auto mt-md-0"></ul>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-muted" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <img src="images/bookingSystem/user-icn.png" alt="user" class="profile-pic" />
                        </a>
                        <div class="dropdown-menu dropdown-menu-right animated zoomIn">
                            <ul class="dropdown-user">
                                <li><a href="logout.php"><i class="fa fa-power-off"></i> Logout</a></li>
                            </ul>
                        </div>
                    </li>
                </ul>
                </div>
            </nav>
        </div>

        <div class="left-sidebar">
            <div class="scroll-sidebar">
                <nav class="sidebar-nav">
                    <ul id="sidebarnav">
                        <li class="nav-devider"></li>
                        <li class="nav-label">Home</li>
                        <li><a href="dashboard.php"><i class="fa fa-tachometer"></i><span>Dashboard</span></a></li>
                        <li class="nav-label">Log</li>
                        <li><a href="all_users.php"><span><i class="fa fa-user f-s-20"></i></span><span>Users</span></a></li>
                        <li><a class="has-arrow" href="#" aria-expanded="false"><i class="fa fa-archive f-s-20 color-warning"></i><span class="hide-menu">Restaurant</span></a>
                            <ul aria-expanded="false" class="collapse">
                                <li><a href="all_restaurant.php">All Restaurant</a></li>
                                <li><a href="add_category.php">Add Category</a></li>
                                <li><a href="add_restaurant.php">Add Restaurant</a></li>                            
                            </ul>
                        </li>
                        <li><a class="has-arrow" href="#" aria-expanded="false"><i class="fa fa-cutlery" aria-hidden="true"></i><span class="hide-menu">Menu</span></a>
                            <ul aria-expanded="false" class="collapse">
                                <li><a href="all_menu.php">All Menus</a></li>
                                <li><a href="add_menu.php">Add Menu</a></li>
                            </ul>
                        </li>
                        <li><a href="all_orders.php"><i class="fa fa-shopping-cart" aria-hidden="true"></i><span>Orders</span></a></li>                        
                    </ul>
                </nav>
            </div>
        </div>

        <div class="page-wrapper">
            <div class="container-fluid">
                <div class="col-lg-12">
                    <div class="card card-outline-primary">
                        <div class="card-header">
                            <h4 class="m-b-0 text-white">Admin Dashboard</h4>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="card p-30">
                                    <div class="media">
                                        <div class="media-left meida media-middle">
                                            <span><i class="fa fa-home f-s-40"></i></span>
                                        </div>
                                        <div class="media-body media-text-right">
                                            <h2><?php echo getCount($db, 'restaurant'); ?></h2>
                                            <p class="m-b-0">Restaurants</p>
                                        </div>
                                    </div>
                                </div>
                            </div>					
                            <div class="col-md-3">
                                <div class="card p-30">
                                    <div class="media">
                                        <div class="media-left meida media-middle">
                                            <span><i class="fa fa-cutlery f-s-40" aria-hidden="true"></i></span>
                                        </div>
                                        <div class="media-body media-text-right">
                                            <h2><?php echo getCount($db, 'dishes'); ?></h2>
                                            <p class="m-b-0">Dishes</p>
                                        </div>
                                    </div>
                                </div>
                            </div>					
                            <div class="col-md-3">
                                <div class="card p-30">
                                    <div class="media">
                                        <div class="media-left meida media-middle">
                                            <span><i class="fa fa-users f-s-40"></i></span>
                                        </div>
                                        <div class="media-body media-text-right">
                                            <h2><?php echo getCount($db, 'users'); ?></h2>
                                            <p class="m-b-0">Users</p>
                                        </div>
                                    </div>
                                </div>
                            </div>					
                            <div class="col-md-3">
                                <div class="card p-30">
                                    <div class="media">
                                        <div class="media-left meida media-middle"> 
                                            <span><i class="fa fa-shopping-cart f-s-40" aria-hidden="true"></i></span>
                                        </div>
                                        <div class="media-body media-text-right">
                                            <h2><?php echo getCount($db, 'users_orders'); ?></h2>
                                            <p class="m-b-0">Total Orders</p>
                                        </div>
                                    </div>
                                </div>
                            </div>                       
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="card p-30">
                                    <div class="media">
                                        <div class="media-left meida media-middle"> 
                                            <span><i class="fa fa-th-large f-s-40" aria-hidden="true"></i></span>
                                        </div>
                                        <div class="media-body media-text-right">
                                            <h2><?php echo getCount($db, 'res_category'); ?></h2>
                                            <p class="m-b-0">Restro Categories</p>
                                        </div>
                                    </div>
                                </div>
                            </div>                   
                            <div class="col-md-4">
                                <div class="card p-30">
                                    <div class="media">
                                        <div class="media-left meida media-middle"> 
                                            <span><i class="fa fa-spinner f-s-40" aria-hidden="true"></i></span>
                                        </div>
                                        <div class="media-body media-text-right">
                                            <h2><?php echo getCount($db, 'users_orders WHERE status = "in process"'); ?></h2>
                                            <p class="m-b-0">Processing Orders</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card p-30">
                                    <div class="media">
                                        <div class="media-left meida media-middle"> 
                                            <span><i class="fa fa-check f-s-40" aria-hidden="true"></i></span>
                                        </div>
                                        <div class="media-body media-text-right">
                                            <h2><?php echo getCount($db, 'users_orders WHERE status = "closed"'); ?></h2>
                                            <p class="m-b-0">Delivered Orders</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="card p-30">
                                    <div class="media">
                                        <div class="media-left meida media-middle"> 
                                            <span><i class="fa fa-times f-s-40" aria-hidden="true"></i></span>
                                        </div>
                                        <div class="media-body media-text-right">
                                            <h2><?php echo getCount($db, 'users_orders WHERE status = "rejected"'); ?></h2>
                                            <p class="m-b-0">Cancelled Orders</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card p-30">
                                    <div class="media">
                                        <div class="media-left meida media-middle"> 
                                            <span><i class="fa fa-rupee f-s-40" aria-hidden="true"></i></span>
                                        </div>
                                        <div class="media-body media-text-right">
                                            <h2><?php echo getEarnings($db); ?></h2>
                                            <p class="m-b-0">Total Earnings</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Charts Section -->
                <div class="container-fluid mt-4">
                    <div class="row">
                        <div class="col-md-6">
                            <canvas id="barChart"></canvas>
                        </div>
                        <div class="col-md-6">
                            <canvas id="pieChart"></canvas>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <canvas id="lineChart"></canvas>
                        </div>
                    </div>
                </div>

                <script>
                // Fetching data for charts
                const totalRestaurants = <?php echo getCount($db, 'restaurant'); ?>;
                const totalDishes = <?php echo getCount($db, 'dishes'); ?>;
                const totalUsers = <?php echo getCount($db, 'users'); ?>;
                const totalOrders = <?php echo getCount($db, 'users_orders'); ?>;

                // Data for bar chart
                const barChartData = {
                    labels: ['Restaurants', 'Dishes', 'Users', 'Total Orders'],
                    datasets: [{
                        label: 'Count',
                        data: [totalRestaurants, totalDishes, totalUsers, totalOrders],
                        backgroundColor: [
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(54, 162, 235, 0.2)'
                        ],
                        borderColor: [
                            'rgba(75, 192, 192, 1)',
                            'rgba(255, 99, 132, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(54, 162, 235, 1)'
                        ],
                        borderWidth: 1
                    }]
                };

                // Bar Chart
                const ctxBar = document.getElementById('barChart').getContext('2d');
                new Chart(ctxBar, {
                    type: 'bar',
                    data: barChartData,
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });

                // Pie Chart data
                const pieChartData = {
                    labels: ['Restaurants', 'Dishes', 'Users'],
                    datasets: [{
                        label: 'Counts',
                        data: [totalRestaurants, totalDishes, totalUsers],
                        backgroundColor: [
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                        ],
                        borderColor: [
                            'rgba(75, 192, 192, 1)',
                            'rgba(255, 99, 132, 1)',
                            'rgba(255, 206, 86, 1)',
                        ],
                        borderWidth: 1
                    }]
                };
                // Pie Chart
                const ctxPie = document.getElementById('pieChart').getContext('2d');
                new Chart(ctxPie, {
                    type: 'pie',
                    data: pieChartData,
                });

                // Line Chart data (example)
                const lineChartData = {
                    labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October','November','December'],
                    datasets: [{
                        label: 'Monthly Orders',
                        data: [12, 19, 3, 5, 2, 3, 7], // Sample data, replace with actual data
                        fill: false,
                        borderColor: 'rgb(75, 192, 192)',
                        tension: 0.1
                    }]
                };
                // Line Chart
                const ctxLine = document.getElementById('lineChart').getContext('2d');
                new Chart(ctxLine, {
                    type: 'line',
                    data: lineChartData,
                });
                </script>
            </div>
        </div>     
    </div>
    
    <script src="js/lib/jquery/jquery.min.js"></script>
    <script src="js/lib/bootstrap/js/popper.min.js"></script>
    <script src="js/lib/bootstrap/js/bootstrap.min.js"></script>
    <script src="js/jquery.slimscroll.js"></script>
    <script src="js/sidebarmenu.js"></script>
    <script src="js/lib/sticky-kit-master/dist/sticky-kit.min.js"></script>
    <script src="js/custom.min.js"></script>
</body>
</html>
<?php
mysqli_close($db); // Close the database connection
?>
