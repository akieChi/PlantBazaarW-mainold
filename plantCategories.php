<?php
include 'conn.php';
session_start();

// Check if a user is logged in
$isLoggedIn = isset($_SESSION['email']) && !empty($_SESSION['email']);
$profilePic = ''; // Placeholder for the profile picture
$email = null;
$isSeller = false; // Flag to check if the user is a seller

if ($isLoggedIn) {
    $email = $_SESSION['email'];

    // Query to get the profile picture from the database
    $query = "SELECT id, proflePicture, firstname, lastname FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        $profilePic = $user['proflePicture'];  // Assuming you store the path to the profile picture
        $userId = $user['id'];
        $firstname = $user['firstname'];
        $lastname = $user['lastname'];
    }

    // If no profile picture is available, use a default image
    if (empty($profilePic)) {
        $profilePic = 'ProfilePictures/Default-Profile-Picture.png';  // Path to a default profile picture
    }

    // Query to check if the user is a seller
    $sellerQuery = "SELECT seller_id FROM sellers WHERE user_id = '$userId'";
    $sellerResult = mysqli_query($conn, $sellerQuery);

    if ($sellerResult && mysqli_num_rows($sellerResult) > 0) {
        $isSeller = true; // User is a seller
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="plantcategories.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer"/>
    <script src="jquery.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@splidejs/splide@4/dist/css/splide.min.css">
    <script src="https://cdn.jsdelivr.net/npm/@splidejs/splide@4/dist/js/splide.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <script src="node_modules/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
    <script src="https://www.google.com/recaptcha/enterprise.js?render=6Lcv5mUqAAAAABNZ9eLdrYxpn8OWSacrmhefh9I3"></script>
    <script src="notif.js"></script>
    <title>Document</title>
</head>
<body>
    <?php include 'nav.php'; ?>
<div class="container">
    <!-- Categories Container -->
    <button id="openCategoriesModal" class="categories-modal-btn">Filter Categories</button>
    <!-- Categories Container for Desktop -->
    <div class="categories-container">
        <!-- Plant Type -->
        <div style="height:210px; overflow: auto;" class="plant-type" >
            <h3>Plant Type</h3>
            <button class="clear-all">Clear All</button> <!-- Clear All Button -->
            <div class="plant-type-items">
                <label><input type="checkbox" class="category-checkbox" value="Outdoor"> Outdoor Plant</label>
                <label><input type="checkbox" class="category-checkbox" value="Indoor"> Indoor Plant</label>
                <label><input type="checkbox" class="category-checkbox" value="Flowers"> Flowers</label>
                <label><input type="checkbox" class="category-checkbox" value="Leaves"> Leaves</label>
                <label><input type="checkbox" class="category-checkbox" value="Bushes"> Bushes</label>
                <label><input type="checkbox" class="category-checkbox" value="Trees"> Trees</label>
                <label><input type="checkbox" class="category-checkbox" value="Climbers"> Climbers</label>
                <label><input type="checkbox" class="category-checkbox" value="Grasses"> Grasses</label>
                <label><input type="checkbox" class="category-checkbox" value="Succulent"> Succulent</label>
                <label><input type="checkbox" class="category-checkbox" value="Cacti"> Cacti</label>
                <label><input type="checkbox" class="category-checkbox" value="Aquatic"> Aquatic</label>
            </div>
        </div>

     <!-- Plant Size -->
        <div style="height:210px; overflow: auto;" class="plant-size">
            <h3>Filter by Size</h3>
            <button class="clear-all">Clear All</button> <!-- Clear All Button -->
            <div class="plant-size-items">
                <label><input type="checkbox" class="size-checkbox" value="Seedling"> Seedlings</label>
                <label><input type="checkbox" class="size-checkbox" value="Juvenile"> Juvenile</label>
                <label><input type="checkbox" class="size-checkbox" value="Adult"> Adult</label>
            </div>
        </div>

     <!-- Plant Location -->
     <div  style="height:210px; overflow: auto;" class="plant-location">
            <h3>Filter by Location</h3>
            <button class="clear-all">Clear All</button> <!-- Clear All Button -->
            <div id="locationCheckboxes"></div> <!-- Dynamic Location Checkboxes -->
        </div>
    </div>

    <!-- Modal for Categories (visible on mobile view only) -->
    <div id="categoriesModal" class="categories-modal">
        <div class="categories-modal-content">
            <button id="closeCategoriesModal" class="close-modal-btn">&times;</button>

            <!-- Copy of categories for the mobile view modal -->
            <div class="plant-type">
                <h3>Plant Type</h3>
                <button class="clear-all">Clear All</button>
                <div class="plant-type-items">
                    <label><input type="checkbox" class="category-checkbox" value="Outdoor"> Outdoor Plant</label>
                    <label><input type="checkbox" class="category-checkbox" value="Indoor"> Indoor Plant</label>
                    <label><input type="checkbox" class="category-checkbox" value="Flowers"> Flowers</label>
                    <label><input type="checkbox" class="category-checkbox" value="Leaves"> Leaves</label>
                    <label><input type="checkbox" class="category-checkbox" value="Bushes"> Bushes</label>
                    <label><input type="checkbox" class="category-checkbox" value="Trees"> Trees</label>
                    <label><input type="checkbox" class="category-checkbox" value="Climbers"> Climbers</label>
                    <label><input type="checkbox" class="category-checkbox" value="Grasses"> Grasses</label>
                    <label><input type="checkbox" class="category-checkbox" value="Succulent"> Succulent</label>
                    <label><input type="checkbox" class="category-checkbox" value="Cacti"> Cacti</label>
                    <label><input type="checkbox" class="category-checkbox" value="Aquatic"> Aquatic</label>
                </div>
            </div>
            <!-- Plant Size -->
        <div class="plant-size">
            <h3>Filter by Size</h3>
            <button class="clear-all">Clear All</button> <!-- Clear All Button -->
            <div class="plant-size-items">
                <label><input type="checkbox" class="size-checkbox" value="Seedlings"> Seedlings</label>
                <label><input type="checkbox" class="size-checkbox" value="Juvenile"> Juvenile</label>
                <label><input type="checkbox" class="size-checkbox" value="Adult"> Adult</label>
            </div>
        </div>

        <!-- Plant Location -->
     <div class="plant-location">
            <h3>Filter by Location</h3>
            <button class="clear-all">Clear All</button> <!-- Clear All Button -->
            <div id="locationCheckboxesMobile"></div> <!-- Dynamic Location Checkboxes -->
        </div>

</div>
</div>
        


<div class="listed-plants">
    <div class="sort-container">
        <h1>Listed Plants</h1>
        <select id="sortPrice" class="sort-price-dropdown">
            <option value="">Sort by Price</option>
            <option value="low">Lowest to Highest</option>
            <option value="high">Highest to Lowest</option>
        </select>
    </div>
    <div class="search-bar-container">
    <input type="text" id="searchBar" placeholder="Search...">
    <!-- <span id="searchIcon" class="icon-search">&#128269;</span> Unicode for search icon -->
    <button id="clearSearch" class="clear-search-btn" style="display: none;">&times;</button>
</div>



    <div class="newly-contents" id="newly-contents">
        <!-- Products will be loaded dynamically -->
    </div>
    <div id="pagination-container"></div> <!-- Pagination -->
</div>
</div>
<style>
    /* Scrollbar customization for Webkit browsers */
.plant-type::-webkit-scrollbar,
.plant-size::-webkit-scrollbar,
.plant-location::-webkit-scrollbar {
    width: 10px; /* Width of the scrollbar */
}

.plant-type::-webkit-scrollbar-thumb,
.plant-size::-webkit-scrollbar-thumb,
.plant-location::-webkit-scrollbar-thumb {
    background-color: #ccc; /* Thumb color */
    border-radius: 10px; /* Rounded edges for the thumb */
    width: 8px; /* Width of the thumb */
}

.plant-type::-webkit-scrollbar-thumb:hover,
.plant-size::-webkit-scrollbar-thumb:hover,
.plant-location::-webkit-scrollbar-thumb:hover {
    background-color: #888; /* Thumb color on hover */
}

.plant-type::-webkit-scrollbar-track,
.plant-size::-webkit-scrollbar-track,
.plant-location::-webkit-scrollbar-track {
    background-color: #f1f1f1; /* Track color */
    border-radius: 10px; /* Rounded edges for the track */
}
</style>

<?php include 'footer.php';?>
<script src="script.js"></script>
<script>
$(document).ready(function () {
    const plantsPerPage = 6;
    let currentPage = 1;
    let allPlants = [];

    // Global filter state object
    const filterState = {
        locations: [],
        sizes: [],
        types: [],
        searchTerm: '',
        sortOrder: ''
    };

    // Variables for logged-in state and user email
    const isLoggedIn = <?php echo json_encode($isLoggedIn); ?>;
    const userEmail = <?php echo json_encode($email); ?>;

    function fetchPlants() {
        $.ajax({
            url: 'Ajax/fetch_categories.php',
            type: 'GET',
            dataType: 'json',
            success: function (response) {
                allPlants = response;
                if (!allPlants.length) {
                    $('#newly-contents').html("<p>No plants available at the moment.</p>");
                    return;
                }
                loadPlants(currentPage);
                setupCheckboxes(allPlants);
            },
            error: function (xhr, status, error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to load plants. Please try again.'
                });
            }
        });
    }

    function setupCheckboxes(plants) {
        let locations = [...new Set(plants.map(p => p.city))];
        let sizes = [...new Set(plants.map(p => p.plantSize))];
        let types = [...new Set(plants.map(p => p.plantcategories))];

        let locationCheckboxesHtml = locations.map(location =>
            `<label>
                <input type="checkbox" class="location-checkbox" value="${location}">
                ${location}
            </label><br>`).join('');

        $('#locationCheckboxes, #locationCheckboxesMobile').html(locationCheckboxesHtml);

        // Event listeners for filters
        $('.location-checkbox, .size-checkbox, .category-checkbox').on('change', filterPlants);
    }

    function loadPlants(page = 1) {
        // Apply filters first
        let filteredPlants = allPlants.filter(function (plant) {
            let plantLocation = plant.city;
            let plantSize = plant.plantSize;
            let plantType = plant.plantcategories;
            let plantName = plant.plantname.toLowerCase();

            let matchesType = filterState.types.length === 0 || filterState.types.includes(plantType);
            let matchesLocation = filterState.locations.length === 0 || filterState.locations.includes(plantLocation);
            let matchesSize = filterState.sizes.length === 0 || filterState.sizes.includes(plantSize);
            let matchesSearchTerm = !filterState.searchTerm || plantName.includes(filterState.searchTerm);

            return matchesType && matchesLocation && matchesSize && matchesSearchTerm;
        });

        // Apply sorting
        if (filterState.sortOrder === 'low') {
            filteredPlants.sort((a, b) => parseFloat(a.price) - parseFloat(b.price));
        } else if (filterState.sortOrder === 'high') {
            filteredPlants.sort((a, b) => parseFloat(b.price) - parseFloat(a.price));
        }

        let totalPages = Math.ceil(filteredPlants.length / plantsPerPage);
        let paginatedPlants = filteredPlants.slice((page - 1) * plantsPerPage, page * plantsPerPage);
        
        currentPage = page;
        displayPlants(paginatedPlants);
        renderPagination(totalPages, page);
    }

    function displayPlants(plantsToDisplay) {
        let contentHtml = plantsToDisplay.map(product => {
            let chatButtonHtml = '';
            if (isLoggedIn && userEmail !== product.seller_email) {
                chatButtonHtml = `<button class="chat-seller" data-email="${product.seller_email}" data-id="${product.plantid}" >Chat Seller</button>`;
            }

            return `<div class="plant-item" data-location="${product.city}" data-category="${product.plantcategories}" data-size="${product.plantSize}" data-price="${product.price}">
                <div style="width: 280px; height: 200px; overflow: hidden;" class="plant-image">
                    <img style="width: 100%; height: 100%; object-fit: cover;" src="Products/${product.seller_email}/${product.img1}" alt="${product.plantname}" onerror="this.onerror=null; this.src='placeholder.jpg';">
                </div>
                <p>${product.plantname}</p>
                <p>Price: ₱${product.price}</p>
                <p>Category: ${product.plantcategories}</p>
                <p>Size: ${product.plantSize}</p>
                <div class="plant-item-buttons">
                    <button class="view-details" data-id="${product.plantid}" data-email="${product.seller_email}">View more details</button>
                    ${chatButtonHtml}
                </div>
            </div>`;
        }).join('');
        $('#newly-contents').html(contentHtml);
    }

    function filterPlants() {
        // Update filter state
        filterState.searchTerm = $('#searchBar').val().toLowerCase();
        filterState.locations = $('.location-checkbox:checked').map(function () {
            return $(this).val();
        }).get();
        filterState.sizes = $('.size-checkbox:checked').map(function () {
            return $(this).val();
        }).get();
        filterState.types = $('.category-checkbox:checked').map(function () {
            return $(this).val();
        }).get();

        // Reset to first page and load filtered results
        currentPage = 1;
        loadPlants(currentPage);
    }

    function renderPagination(totalPages, current) {
        if (totalPages <= 1) {
            $('#pagination-container').html('');
            return;
        }

        let paginationHtml = `
            <button class="page-link prev-btn" ${current === 1 ? 'disabled' : ''} data-page="${current - 1}">
                &laquo; Prev
            </button>`;

        // Always show first page
        paginationHtml += `<button class="page-link ${current === 1 ? 'active' : ''}" data-page="1">1</button>`;

        // Calculate range to show
        let startPage = Math.max(2, current - 1);
        let endPage = Math.min(totalPages - 1, current + 1);

        // Add first ellipsis if needed
        if (current > 3) {
            paginationHtml += '<span class="ellipsis">...</span>';
        }

        // Add middle pages
        for (let i = startPage; i <= endPage; i++) {
            if (i <= totalPages - 1 && i > 1) {
                paginationHtml += `
                    <button class="page-link ${i === current ? 'active' : ''}" data-page="${i}">
                        ${i}
                    </button>`;
            }
        }

        // Add last ellipsis if needed
        if (current < totalPages - 2) {
            paginationHtml += '<span class="ellipsis">...</span>';
        }

        // Always show last page if there's more than one page
        if (totalPages > 1) {
            paginationHtml += `
                <button class="page-link ${current === totalPages ? 'active' : ''}" data-page="${totalPages}">
                    ${totalPages}
                </button>`;
        }

        paginationHtml += `
            <button class="page-link next-btn" ${current === totalPages ? 'disabled' : ''} data-page="${current + 1}">
                Next &raquo;
            </button>`;

        $('#pagination-container').html(paginationHtml);

        // Pagination click handlers
        $('.page-link').on('click', function() {
            if ($(this).is('[disabled]') || $(this).hasClass('active')) {
                return;
            }

            const newPage = $(this).data('page');
            if (newPage >= 1 && newPage <= totalPages) {
                scrollToTop();
                loadPlants(newPage);
            }
        });
    }

    function scrollToTop() {
        if ('scrollBehavior' in document.documentElement.style) {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        } else {
            $('html, body').animate({ scrollTop: 0 }, 300);
        }
    }

    // Event Handlers
    $('.clear-all').on('click', function () {
        $(this).closest('.plant-type, .plant-size, .plant-location')
            .find('input[type="checkbox"]')
            .prop('checked', false);
        filterPlants();
    });

    $('#sortPrice').on('change', function() {
        filterState.sortOrder = $(this).val();
        filterPlants();
    });

    $('#searchBar').on('input', function () {
        filterPlants();
        toggleSearchIcon();
    });

    $('#clearSearch').on('click', function () {
        $('#searchBar').val('');
        filterState.searchTerm = '';
        toggleSearchIcon();
        filterPlants();
    });

    function toggleSearchIcon() {
        if ($('#searchBar').val().trim()) {
            $('#clearSearch').show().addClass('active');
        } else {
            $('#clearSearch').hide().removeClass('active');
        }
    }

    // Mobile modal handlers
    $('#openCategoriesModal').on('click', function() {
        $('#categoriesModal').addClass('show');
    });

    $('#closeCategoriesModal').on('click', function() {
        $('#categoriesModal').removeClass('show');
    });

    // View details handler
    $(document).on('click', '.view-details', function () {
        let plantId = $(this).data('id');
        let sellerEmail = $(this).data('email');
        window.location.href = `viewdetails.php?plantId=${plantId}`;
    });

    $(document).on('click', '.chat-seller', function () {
        let sellerEmail = $(this).data('email');
        let plantId = $(this).data('id');
        window.location.href = `chat_upgrade/chat.php?seller_email=${encodeURIComponent(sellerEmail)}&plantid=${encodeURIComponent(plantId)}`;
    });

    // Initialize
    fetchPlants();
    toggleSearchIcon();
});
</script>
 
</body>
</html>