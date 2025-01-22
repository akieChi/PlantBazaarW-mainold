<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Products History</title>
    <link rel="stylesheet" href="style.css"> <!-- Include your CSS here -->
    <!-- Include SheetJS library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.1/xlsx.full.min.js"></script>
</head>
<?php
include '../conn.php';
include 'nav.php';

$query = "SELECT count(*) AS total_count FROM product WHERE listing_status = 2";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);
$total_count = $row['total_count'];
?>
<body>
   
    <a href="javascript:history.back()" class="back-button">Back</a>
    <br>
    <br>

    <br><h1>Your Products History</h1>  
    <!-- Total Profit -->
    <div id="totalProfit">
        <strong>Total Profit: ₱<span id="profitAmount">0.00</span></strong>
    </div>
    <div id="totalProfit">
        <strong>Total Item Sold: <span id="profitAmount"><?php echo $total_count; ?></span></strong>
    </div>

    <!-- Products Table -->
    <table id="productsTable">
        <thead>
            <tr>
                <th>Image</th>
                <th>Plant Name</th>
                <th>Price</th>
                <th>Status</th>
                <th>Sold To</th>
                <th>Date Sold</th>
            </tr>
        </thead>
        <tbody>
            <!-- Product rows will be dynamically inserted here -->
        </tbody>
    </table>

    <div id="imageModal" class="modal">
        <span class="close" onclick="closeModal()">&times;</span>
        <img class="modal-content" id="modalImage">
        <div id="caption"></div>
    </div>

    <!-- Print Button -->
    <button class="print-btn" onclick="window.print()">Print Report</button>
    
    <!-- Export to Excel Button -->
    <button class="export-btn" onclick="exportTableToExcel('productsTable', 'products_history.xlsx')">Export to Excel</button>

    <script>
        // Function to load products using AJAX
        function loadProducts() {
            fetch(`fetch_listed_plants.php?listing_status=2`) // Use your actual PHP file name here
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        alert(data.error);
                    } else {
                        displayProducts(data.products);
                    }
                })
                .catch(error => console.error('Error fetching data:', error));
        }

        // Function to display products in a table and calculate total profit
        function displayProducts(products) {
            const tableBody = document.querySelector('#productsTable tbody');
            const profitAmount = document.getElementById('profitAmount');
            let totalProfit = 0;

            tableBody.innerHTML = ''; // Clear previous content

            products.forEach(product => {
                const row = document.createElement('tr');
                let imgPath = '../Products/' + product.seller_email + '/' + product.img1;
                console.log(imgPath);  // Check the constructed image path

                row.innerHTML = ` 
                    <td><img src="${imgPath}" alt="${product.plantname}" class="product-image" onclick="openModal('${imgPath}')"></td>
                    <td>${product.plantname}</td>
                    <td>₱${product.price}</td>
                    <td>${product.listing_status == 1 ? 'Available' : 'Sold'}</td>
                    <td>${product.sold_to}</td>
                    <td>${product.updatedAt}</td>
                `;
                tableBody.appendChild(row);

                // Add the price to the total profit
                totalProfit += parseFloat(product.price);
            });

            // Update the total profit display
            profitAmount.textContent = totalProfit.toFixed(2);
        }

        function openModal(imgSrc) {
            const modal = document.getElementById("imageModal");
            const modalImage = document.getElementById("modalImage");
            modal.style.display = "block";
            modalImage.src = imgSrc;
        }

        // Close the modal
        function closeModal() {
            const modal = document.getElementById("imageModal");
            modal.style.display = "none";
        }

        // Initial load
        loadProducts();

        // Function to export table to Excel
        function exportTableToExcel(tableId, filename) {
            var table = document.getElementById(tableId);
            var wb = XLSX.utils.table_to_book(table, {sheet: "Sheet1"});
            XLSX.writeFile(wb, filename);
        }
    </script>

    <style>
        body {
            font-family: Verdana, Geneva, Tahoma, sans-serif;
        }
        
        /* CSS for table layout */
        #productsTable {
            width: 100%;
            border-collapse: collapse;
        }

        #productsTable th, #productsTable td {
            padding: 12px;
            text-align: center;
            border: 1px solid #ddd;
        }

        #productsTable th {
            background-color: #f4f4f4;
            font-weight: bold;
        }

        .product-image {
            max-width: 100px;
            height: auto;
            cursor: pointer;
        }

        /* Styling for the images */
        .product-image {
            width: 100px;
            height: auto;
            cursor: pointer;
        }

        /* Modal styling */
        .modal {
            display: none; /* Hidden by default */
            position: fixed;
            z-index: 1; /* Sit on top */
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0,0,0); /* Black with opacity */
            background-color: rgba(0, 0, 0, 0.9); /* Black with opacity */
        }

        .modal-content {
            margin: auto;
            display: block;
            width: 80%; /* Adjust the width as per requirement */
            max-width: 700px;
        }

        #caption {
            text-align: center;
            color: #fff;
            padding: 10px;
            font-size: 20px;
        }

        .close {
            position: absolute;
            top: 10px;
            right: 25px;
            color: #f1f1f1;
            font-size: 35px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover,
        .close:focus {
            color: #bbb;
            text-decoration: none;
            cursor: pointer;
        }

        /* Total Profit Styling */
        #totalProfit {
            margin-bottom: 20px;
            font-size: 20px;
            font-weight: bold;
        }
        
        .back-button {
            position: absolute; /* Fixed position within the layout */
            top: 30px; /* Adjusted so it's under the logo */
            left: 220px;
            background-color: darkgreen;
            color: white;
            padding: 8px 12px; /* Reduced padding for a smaller button */
            font-size: 12px;
            border: none;
            border-radius: 10px;
            text-decoration: none;
            cursor: pointer;
            z-index: 1000;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            margin-top: 55px; /* Adjust margin to fit the design */
            transition: background-color 0.3s; /* Transition for hover effect */
            margin-left: -210px;
        }

        .back-button:hover {
            background-color: #4CAF50;
        }

        /* Print Button Styling */
        .print-btn {
            padding: 10px 20px;
            font-size: 16px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 20px;
        }

        .print-btn:hover {
            background-color: #45a049;
        }

        /* Export Button Styling */
        .export-btn {
            padding: 10px 20px;
            font-size: 16px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 20px;
        }

        .export-btn:hover {
            background-color: #0056b3;
        }

        /* Print-specific CSS */
        @media print {
            body {
                font-size: 14px;
                background-color: white;
            }

            .back-button, .print-btn, .export-btn {
                display: none; /* Hide buttons on print */
            }

            #totalProfit {
                margin-top: 30px;
                font-size: 16px;
            }

            #productsTable th, #productsTable td {
                padding: 8px;
            }

            #productsTable {
                width: 100%;
                margin-top: 20px;
                border: 1px solid #ddd;
            }

            .product-image {
                max-width: 50px;
                height: auto;
            }

            .modal {
                display: none;
            }
        }
    </style>
</body>
</html>
