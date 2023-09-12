<?php
// Start a session to access cart data
session_start();

// Check if the form has been submitted (after placing the order)
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Process the form data and handle the order
    // (You can add the order handling logic here)
    // For example, save the order details to a database, send an email confirmation, etc.
    // Once the order is successfully processed, you can clear the cart by unsetting the session variable:
    // unset($_SESSION['cart']);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="style.css">
    <title>Crafty Kit</title>
    <style>
        .ck{
            font-family: 'Playfair Display', serif;
	        font-weight:540;
        }
        button{
            font-family: "Montserrat";
        }

        form{
            font-family:  "Montserrat";
        }

        .checkout-cart h3,.checkout-cart p{
            font-family: "Montserrat";
            text-align: center;
            margin-top: 45px;
        }

        .checkout-cart table{
            margin-top: 30px;
        }

    </style>
</head>
<body>
    <section class="checkout">
        <div class="checkout-form">
            <h1 class="ck" id="checkout-ck">Crafty Kit</h1>
            <form action="checkout.php" method="POST">

                <h3>SHIPPING ADDRESS</h3>
                <div class="grid">
                    <!-- Add form inputs for shipping address -->
                    <div class="checkout_input" id="grid1">
                        <select id="full_line" name="Country">
                            <option value="United States">United States</option>
                        </select>
                        <span>Country/Region</span>
                    </div>
                    <div class="checkout_input" id="grid2">
                        <input type="text" placeholder=" " class="input" name="First_Name" autocomplete="off" />
                        <span>First Name</span>
                    </div>
                    <div class="checkout_input" id="grid3">
                        <input type="text" placeholder=" " class="input" name="Last_Name" autocomplete="off"/>
                        <span>Last Name</span>
                    </div>
                    <div class="checkout_input" id="grid4">
                        <input type="text" placeholder=" " id="full_line" name="Address" autocomplete="off"/>
                        <span>Address</span>
                    </div>
                    <div class="checkout_input" id="grid5">
                        <input type="text" placeholder=" " name="City" autocomplete="off"/>
                        <span>City</span>
                    </div>
                    <div class="checkout_input" id="grid6">
                        <input type="text" placeholder=" " name="Postal_code" autocomplete="off"/>
                        <span>Postal Code</span>
                    </div>
                    <div class="checkout_input" id="grid7">
                        <input type="text" placeholder=" " id="full_line" name="phone_num" autocomplete="off"/>
                        <span>Phone Number</span>
                    </div>
                </div>

                
                <!-- Add a hidden input field to store the product IDs -->
                <?php
                if (isset($_SESSION['cart'])) {
                    foreach ($_SESSION['cart'] as $productID) {
                        echo '<input type="hidden" name="product_ids[]" value="' . $productID . '">';
                    }
                }
                ?>

                <div class="placeorder_button_box">
                    <button type="submit" value="submit" class="placeorder_button">
                        PLACE ORDER
                    </button>
                </div>
            </form>
        </div>
        <div class="checkout-cart">
            <?php
            // Display the cart details
            if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
                echo '<h3>CART DETAILS</h3>';
                echo '<table>';
                echo '<tr><th>Product ID</th><th>Product Name</th><th>Price</th></tr>';

                // Database connection
                $con = mysqli_connect("localhost", "root", "", "craftykit");
                if (!$con) {
                    echo "Error: Unable to connect to the database.";
                    die();
                }

                // Calculate the total price of the products
                $totalPrice = 0;

                // Retrieve the product details for the cart items
                $cartProducts = array();
                foreach ($_SESSION['cart'] as $itemID) {
                    // Check if the product ID is numeric and greater than 0
                    if (!is_numeric($itemID) || $itemID <= 0) {
                        echo "Invalid product ID: " . $itemID . "<br>";
                        continue;
                    }

                    $query = "SELECT product_id, product_name, price FROM product WHERE product_id = '$itemID'";
                    $result = mysqli_query($con, $query);

                    if (!$result) {
                        echo "Error executing query: " . mysqli_error($con) . "<br>";
                        continue;
                    }

                    if (mysqli_num_rows($result) > 0) {
                        $product = mysqli_fetch_assoc($result);
                        $cartProducts[] = $product;

                        // Add the price of the product to the total price
                        $totalPrice += $product['price'];
                    } else {
                        echo "No product found with ID: " . $itemID . "<br>";
                    }
                }

                // Close the database connection
                mysqli_close($con);

                // Display the cart items in the table
                foreach ($cartProducts as $product) {
                    echo '<tr>';
                    echo '<td>' . $product['product_id'] . '</td>';
                    echo '<td>' . $product['product_name'] . '</td>';
                    echo '<td>' . $product['price'] . '</td>';
                    echo '</tr>';
                }

                // Display the total price
                echo '<tr><td colspan="2"><strong>Total Price</strong></td><td><strong>' . $totalPrice . '</strong></td></tr>';

                echo '</table>';
            } else {
                echo '<p>Your cart is empty.</p>';
            }
            ?>
        </div>
    </section>
</body>
</html>