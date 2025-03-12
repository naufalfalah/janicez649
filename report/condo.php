<?php

require_once('../report.php');

$town = $response['lead_details'][0]['lead_form_value'];
$block = $response['lead_details'][1]['lead_form_value'];
$unit = $response['lead_details'][4]['lead_form_value'];

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
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
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
                                <td>Block Number</td>
                                <td><?= $block ?? '' ?></td>
                            </tr>
                            <tr>
                                <td>Unit</td>
                                <td><?= $unit ?? '' ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
                
                <!-- Filter and Results Table Section -->
                <div class="table-box1">
                    <!-- <div class="filter-wrapper">
                        <div class="filter-search-box">
                            <div class="select-box">
                                <span>Show</span>
                                <select id="custom-length">
                                    <option value="5">5</option>
                                    <option value="10" selected>10</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                </select>
                            </div>
                            <div class="search-box">
                                <input class="search-field" type="search" placeholder=" ⌕ search here" name="search" id="search-field" />
                            </div>
                        </div>
                    </div> -->

                    <div class="table">
                        <table id="empTable" class="table table-striped" style="width:100%">
                            <tr>
                                <th>Date of Sales</th>
                                <th>Project Name</th>
                                <th>Street Name</th>
                                <th>Discrict</th>
                                <th>Market Segment</th>
                                <th>Tenure</th>
                                <th>Type of Sale</th>
                                <th>Floor Level</th>
                                <th>Area (Sqft)</th>
                                <th>Sale Price (S$)</th>
                            </tr>
                        </table>
                        <tbody></tbody>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.3/js/standalone/selectize.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
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
        
        $(document).ready(function () {
            $('.dataTables_filter label input').attr('placeholder', ' ⌕ search here');
            $('.basic').select2();
            let dataTable = initDataTable();

            function initDataTable(pageLength = 10) {
                $('#empTable').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: '../util_project_data.php',
                        data: function (d) {
                            d.project = $('#project').val();
                        }
                    },
                    columns: [
                        { data: 'contractDate' },
                        { data: 'project' },
                        { data: 'street' },
                        { data: 'district' },
                        { data: 'marketSegment' },
                        { data: 'tenure' },
                        { data: 'typeOfSale' },
                        { data: 'floorRange' },
                        { data: 'area' },
                        { data: 'price' }
                    ],
                    order: [[0, "desc"]]
                });
            }
        });
    </script>
</body>

</html>