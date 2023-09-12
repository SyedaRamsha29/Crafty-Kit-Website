<?php
// server connected
$con = mysqli_connect("localhost", "root", "", "craftykit");

$res = "craftykit";

session_start();

$Country = $_POST['Country'];
$fname = $_POST['First_Name'];
$lname = $_POST['Last_Name'];
$address = $_POST['Address'];
$City = $_POST['City'];
$Postal_code = $_POST['Postal_code'];
$phone_num = $_POST['phone_num'];

// Function to generate a random order ID
function generate_order_id($length = 10) {
    $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}

if (isset($_POST['product_ids'])) {
    $productIDs = $_POST['product_ids'];

    // Generate a random order ID for the entire order
    $orderID = generate_order_id(); // You can adjust the length as per your requirement.

    // Insert customer information and product IDs into the customer table
    foreach ($productIDs as $productID) {
        // Use the same $orderID for all products in the order
        $b = "INSERT INTO customer (Country, First_Name, Last_Name, Address, City, Postal_code, phone_num, product_id, order_id) 
              VALUES ('$Country', '$fname', '$lname', '$address', '$City', '$Postal_code', '$phone_num', '$productID', '$orderID')";

        $result = mysqli_query($con, $b);
        if (!$result) {
            echo "Error inserting data: " . mysqli_error($con);
            die();
        }
    }

    // Update the product sold after successful insertion
    foreach ($productIDs as $productID) {
        $update_query = "UPDATE product SET sold = sold - 1 WHERE product_id = '$productID'";
        $update_result = mysqli_query($con, $update_query);
        if (!$update_result) {
            echo "Error updating product sold: " . mysqli_error($con);
            die();
        }
    }

    // Display the order confirmation message with the order ID
    echo '<h2>Order Confirmation</h2>';
    echo '<p>Your order has been confirmed with Order ID: ' . $orderID . '</p>';

    // Clear the cart by unsetting the session variable
    unset($_SESSION['cart']);

} else {
    echo "No product IDs found.";
    die();
}

// Close the database connection
mysqli_close($con);
?>