<?php

require_once('../report.php');

$town = $response['lead_details'][0]['lead_form_value'];
$flat_type = $response['lead_details'][2]['lead_form_value'];

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Landed Valuation</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="../css/report.css">
</head>

<body>
    <div class="cursor-follower"></div>

    <div class="main-container">
        <div class="background-image"></div>
        <div class="background-overlay"></div>

        <div class="container">
            <div class="table-main-container">
                <div class="table-box">
                    <h1>Resale Flate Price</h1>
                    <div class="table">
                        <table>
                            <tr>
                                <th colspan="2">Search Results</th>
                            </tr>
                            <tr>
                                <td>HDB Town</td>
                                <td><?= $town ?? '' ?></td>
                            </tr>
                            <tr>
                                <td>Flat Type</td>
                                <td><?= $flat_type ?? '' ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="table-box1">
                    <div class="filter-wrapper">
                        <div class="filter-buttons-box">
                            <button type="button">Your Block</button>
                            <button type="button">Your Custer</button>
                        </div>
                        <div class="filter-search-box">
                            <div class="select-box">
                                <span>Show</span>
                                <select name="project-name" id="project-name">
                                    <option value="Choose Town" selected>0</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                </select>
                                <span>Entire</span>
                            </div>
                            <div class="search-box">
                                <input class="search-field" type="search" placeholder=" ⌕ search here" name="search"
                                    id="search">
                            </div>
                        </div>
                    </div>
                    <div class="table">
                        <table>
                            <tr>
                                <th>Sold Price</th>
                                <th>Sold Month</th>
                                <th>Address</th>
                                <th>Area</th>
                                <th>Level</th>
                                <th>Remaining Lease</th>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.3/js/standalone/selectize.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script>
        // Cursor follower effect
        function initCursorFollower() {
            const follower = document.querySelector('.cursor-follower');
            let mouseX = 0, mouseY = 0;
            let followerX = 0, followerY = 0;

            document.addEventListener('mousemove', (e) => {
                mouseX = e.clientX;
                mouseY = e.clientY;
                follower.style.opacity = '1';
            });

            function animate() {
                // Smooth following with easing
                followerX += (mouseX - followerX) * 0.1;
                followerY += (mouseY - followerY) * 0.1;

                if (follower) {
                    follower.style.left = `${followerX}px`;
                    follower.style.top = `${followerY}px`;
                }

                requestAnimationFrame(animate);
            }

            animate();

            // Hide follower when mouse leaves window
            document.addEventListener('mouseleave', () => {
                follower.style.opacity = '0';
            });

            document.addEventListener('mouseenter', () => {
                follower.style.opacity = '1';
            });
        }

        $(document).ready(function() {
            pageLoader('show');
            let first_request_url = '<?= $first_request_url ?>';
            let second_request_url = '<?= $second_request_url ?>'; 

            sendRequest(first_request_url)
                .then(function (records) {
                    // Handle records
                    // console.log('first',records) 
                    setDataIntoTable(records);
                    pageLoader('hide');
                })
                .catch(function (error) {
                    // Handle errors
                    if (error == 'No records found') {
                        sendRequest(second_request_url).then(function (records) {
                            // Handle records
                            // console.log('second',records)

                            setDataIntoTable(records);
                            pageLoader('hide');
                        })
                        .catch(function (error) {
                            // Handle errors
                            console.error(error);
                            pageLoader('hide');
                        });
                    }
                    // console.error(error);
                });

            function sendRequest(url) {
                return new Promise(function (resolve, reject) {
                    $.ajax({
                        url: url,
                        method: 'GET',
                        dataType: 'json',
                        success: function (data) {
                            if (data && data.result && data.result.records && data.result.records.length > 0) {
                                // Resolve the promise with the records
                                resolve(data.result.records);
                            } else {
                                // Reject the promise with a message
                                reject('No records found');
                            }
                        },
                        error: function () {
                            // Reject the promise with an error message
                            reject('Error fetching data');
                        }
                    });
                });
            }

            function setDataIntoTable(records){
                var filteredBlocks = [];
                var selectedBlock = '<?=$_POST['blk'] ?? "" ?>';

                var yourBlock = '';
                var yourCluster = '';
                
                if (records.length > 0) {
                    for (let index = 0; index < records.length; index++) {
                        const entry = records[index];
                        if (entry.block === selectedBlock) {
                            yourBlock += `<tr>
                                            <td>${formatCurrency(entry.resale_price)}</td>
                                            <td>${formatDate(entry.month)}</td>
                                            <td>${entry.block + ', ' + entry.street_name}</td>
                                            <td>${entry.floor_area_sqm } sqm</td>
                                            <td>${entry.storey_range}</td>
                                            <td>${entry.remaining_lease}</td>
                                        </tr>`;
                            yourCluster += `<tr>
                                    <td>${formatCurrency(entry.resale_price)}</td>
                                    <td>${formatDate(entry.month)}</td>
                                    <td>${entry.block + ', ' + entry.street_name}</td>
                                    <td>${entry.floor_area_sqm } sqm</td>
                                    <td>${entry.storey_range}</td>
                                    <td>${entry.remaining_lease}</td>
                                </tr>`;
                        }else{
                            yourCluster += `<tr>
                                    <td>${formatCurrency(entry.resale_price)}</td>
                                    <td>${formatDate(entry.month)}</td>
                                    <td>${entry.block + ', ' + entry.street_name}</td>
                                    <td>${entry.floor_area_sqm } sqm</td>
                                    <td>${entry.storey_range}</td>
                                    <td>${entry.remaining_lease}</td>
                                </tr>`;
                        }
                    }

                    let nav1 = $("#nav-block-tab");
                    let nav2 = $("#nav-cluster-tab");

                    let tab1 = $("#nav-block");
                    let tab2 = $("#nav-cluster");
                    
                    if (yourBlock) {
                        tab2.removeClass('show active');
                        nav2.removeClass('active');

                        tab1.addClass('show active');
                        nav1.addClass('active');
                        $("#example2 tbody").html(yourBlock);
                        new DataTable('#example2');
                    }

                    if (yourCluster) { 
                        $("#example tbody").html(yourCluster);
                        new DataTable('#example');
                    }

                    $('#show_total_records').html(`${records.length +' ('+formatDate2()+')'}`)

                    var myDiv = document.getElementById('table-guider');
                    if (window.innerWidth <= 767) {
                        // Show the div
                        myDiv.style.display = 'block';

                        // Hide the div after 5 seconds
                        setTimeout(function () {
                            myDiv.style.display = 'none';
                        }, 5000); // 5000 milliseconds = 5 seconds
                    }
                }
            }

            // Function to format currency
            function formatCurrency(amount) {
                return '$' + Number(amount).toFixed(0).replace(/\d(?=(\d{3})+$)/g, '$&,');
            }

            // Function to format date
            function formatDate(dateString) {
                var date = new Date(dateString);
                return date.toLocaleString('en-US', { month: 'short', year: 'numeric' });
            }

            function formatDate2() {
                var currentDate = new Date();
                var day = currentDate.getDate();
                var month = currentDate.toLocaleString('en-US', { month: 'short' });
                var year = currentDate.getFullYear();

                // Add leading zero to day if necessary
                day = (day < 10) ? '0' + day : day;

                return day + ' ' + month + ' ' + year;
            }

            function pageLoader(status){
                if (status=="show") {
                    $('.loader-wrapper').removeClass('d-none');
                }else{
                    $('.loader-wrapper').addClass('d-none');
                }
            }
        }); 
    </script>
</body>

</html>