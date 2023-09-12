<?php
session_start();

// Check if the cart session variable is not set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array(); // Initialize the cart as an empty array
}

// Retrieve the selected product ID and quantity from the "Add to Cart" form
if (isset($_POST['item_id']) && isset($_POST['quantity'])) {
    $productID = $_POST['item_id'];
    $quantity = $_POST['quantity'];

    // Add the product ID and quantity to the cart in the session
    for ($i = 0; $i < $quantity; $i++) {
        $_SESSION['cart'][] = $productID;
    }
}

// Check if the remove button is clicked
if (isset($_GET['remove'])) {
    $removeIndex = $_GET['remove'];

    // Check if the remove index is valid
    if (isset($_SESSION['cart'][$removeIndex])) {
        // Remove the item from the cart
        unset($_SESSION['cart'][$removeIndex]);
        $_SESSION['cart'] = array_values($_SESSION['cart']); // Reset the array keys
    }
}

// Database connection
$con = mysqli_connect("localhost", "root", "", "craftykit");
if (!$con) {
    echo "Error: Unable to connect to the database.";
    die();
}

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
    } else {
        echo "No product found with ID: " . $itemID . "<br>";
    }
}

// Close the database connection
mysqli_close($con);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500&display=swap');

       
        body{
            text-align: center;
            font-family:  "Montserrat";
        }

        table{
            row-gap: 5px;
            margin-top: 40px;
            font-family: "Montserrat";
        }

        td, th{
            padding: 10px;
        }
        
        .heading{
            margin-top: 70px;
        }

        button{
            margin-top: 20px;
            font-family: "Montserrat";
        }
    </style>
    <title >Cart Details</title>
</head>

<body class="cart-body">
    <div class="cart-container">
    <h2 class="heading">CART DETAILS</h2>
    </div>
    
    <table>
        <tr>
            <th>Product ID</th>
            <th>Product Name</th>
            <th>Price</th>
            <th>Action</th>
        </tr>
        <?php foreach ($cartProducts as $index => $product) : ?>
            <tr>
                <td><?php echo $product['product_id']; ?></td>
                <td><?php echo $product['product_name']; ?></td>
                <td><?php echo $product['price']; ?></td>
                <td><a href="add_to_cart.php?remove=<?php echo $index; ?>">Remove</a></td>
            </tr>
        <?php endforeach; ?>
    </table>
    <form action="http://localhost/craftykit/checkout1.php">
        <button class="checkout_button" type="submit" >CHECKOUT</button>
    </form>
</body>
</html>
