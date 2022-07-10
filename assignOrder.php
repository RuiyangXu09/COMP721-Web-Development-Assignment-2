<?php
/**
 * This file will listen the POST Requests to call the appropriate admin function (Assign the booking).
 * Author: RuiyangXu 18017865
 */
$assignButton = $_POST['assign'];
$bookingNumber = $_POST['search'];

//safe connect
require_once ('sql_Inform.php');
$conn = new mysqli($servername,$username,$password,$myDB,3306);

// connect to database
if ($conn ->connect_error)
{
    die("Connect failed".$conn->connect_error);
}else
{
    //sql语句，update Status to Assigned
    $sql = mysqli_query($conn, "UPDATE cabs SET Status = 'Assigned' WHERE Reference_Number = '$bookingNumber'");
    //sql assign语句, 根据post获得的bookingNumber的值在数据库中查找相对应的数据, 找到后打印表格和相应的confirmation
    $assign = mysqli_query($conn, "SELECT * FROM cabs WHERE Reference_Number = '$bookingNumber'");
    if (mysqli_num_rows($assign) >0)
    {
        while ($assignOrderDetails = mysqli_fetch_assoc($assign))
        {
            $pickup_date = date("d/m/Y",strtotime($assignOrderDetails['Pick_Up_Date']));
            $pickup_time = date("H:i",strtotime($assignOrderDetails['Pick_up_Time']));
            echo "<a style='color: red; font-size: 20px'>Congratulations! Your Booking "."$bookingNumber"." has been Assigned!</a>".
                "<table>" .
                    "<tr>
                        <th>Reference Number</th>
                        <th>Customer Name</th>
                        <th>Phone Number</th>
                        <th>Unit Number</th>
                        <th>Street Number</th>
                        <th>Street Name</th>
                        <th>Pickup Suburb</th>
                        <th>Destination Suburb</th>
                        <th>Pick Up Date and Time</th>
                        <th>Status</th>
                    </tr>" .
                    "<tr>" .
                        "<td >" . $assignOrderDetails['Reference_Number'] . "</td>" .
                        "<td>" . $assignOrderDetails['Customer_Name'] . "</td>" .
                        "<td>" . $assignOrderDetails['Phone_Number'] . "</td>" .
                        "<td>" . $assignOrderDetails['Unit_Number'] . "</td>" .
                        "<td>" . $assignOrderDetails['Street_Number'] . "</td>" .
                        "<td>" . $assignOrderDetails['Street_Name'] . "</td>" .
                        "<td>" . $assignOrderDetails['Suburb'] . "</td>" .
                        "<td>" . $assignOrderDetails['Destination_Suburb'] . "</td>" .
                        "<td>" . $pickup_date. "  " . $pickup_time. "</td>" .
                        "<td>" . $assignOrderDetails['Status'] . "</td>" .
                    "</tr>" .
                "</table>";
        }
    }
}