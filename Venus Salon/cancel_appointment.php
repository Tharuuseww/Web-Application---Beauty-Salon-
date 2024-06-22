<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

// Check if the user is logged in
if (empty($_SESSION['bpmsuid'])) {
    header('location:logout.php');
    exit(); // Stop further execution
}

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['apt_number'])) {
    $aptNumber = $_POST['apt_number'];

    // Check if the appointment exists and is associated with the logged-in user
    $query = mysqli_query($con, "SELECT * FROM tblbook WHERE AptNumber = '$aptNumber' AND UserID = '{$_SESSION['bpmsuid']}'");
    if (mysqli_num_rows($query) > 0) {
        // Fetch the appointment details
        $appointmentData = mysqli_fetch_assoc($query);

        // Delete the appointment from the database
        $deleteQuery = mysqli_query($con, "DELETE FROM tblbook WHERE AptNumber = '$aptNumber'");
        if ($deleteQuery) {
            // Appointment cancelled successfully
            echo "<script>alert('Your appointment has been canceled successfully!');</script>";
        } else {
            // Error in cancelling appointment
            echo "<script>alert('Failed to cancel appointment. Please try again later.');</script>";
        }
    } else {
        // Appointment not found or not associated with the user
        echo "<script>alert('Appointment not found or not associated with your account.');</script>";
    }
} else {
    // Redirect to booking history page if appointment number is not set or request method is not POST
    echo "<script>alert('Invalid request.');</script>";
}

// Redirect to booking history page
echo "<script>window.location.href='booking-history.php';</script>";
exit(); // Stop further execution
?>
