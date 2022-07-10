<?php
/**
 * This file will listen the POST Requests to call the appropriate admin function (Search the booking).
 * Author: RuiyangXu 18017865
 */
date_default_timezone_set('PRC');

$bookingNumber = $_POST['search'];

require_once ('sql_Inform.php');
$conn = new mysqli($servername,$username,$password,$myDB,3306);

if ($conn->connect_error)
{
    die("Connect Failed".$conn->connect_error);
}else
{
    if (empty($bookingNumber))
    {
        //empty search sql
        $empty_search_2hours = mysqli_query($conn, "SELECT * FROM cabs WHERE TO_DAYS(Pick_Up_Date) = TO_DAYS(NOW()) AND Pick_up_Time <= NOW() + INTERVAL 2 HOUR AND Pick_up_Time >= NOW() AND Status = 'Unassigned'");

        if (mysqli_num_rows($empty_search_2hours) > 0)
        {
            if (mysqli_num_rows($empty_search_2hours) > 0)
            {
                while ($bookingDetails = mysqli_fetch_assoc($empty_search_2hours)) {
                    $pickup_date = date("d/m/Y", strtotime($bookingDetails['Pick_Up_Date']));
                    $pickup_time = date("H:i", strtotime($bookingDetails['Pick_up_Time']));
                    echo "<table>" .
                            "<tr>
                                <th style='color: dodgerblue'>Reference Number</th>
                                <th style='color: dodgerblue'>Customer Name</th>
                                <th style='color: dodgerblue'>Phone Number</th>
                                <th style='color: dodgerblue'>Unit Number</th>
                                <th style='color: dodgerblue'>Street Number</th>
                                <th style='color: dodgerblue'>Street Name</th>
                                <th style='color: dodgerblue'>Pickup Suburb</th>
                                <th style='color: dodgerblue'>Destination Suburb</th>
                                <th style='color: dodgerblue'>Pick Up Date and Time</th>
                                <th style='color: dodgerblue'>Status</th>
                            </tr>" .
                            "<tr>" .
                        "<td style='color: darkred'>" . $bookingDetails['Reference_Number'] . "</td>" .
                                "<td style='color: darkred'>" . $bookingDetails['Customer_Name'] . "</td>" .
                                "<td style='color: darkred'>" . $bookingDetails['Phone_Number'] . "</td>" .
                                "<td style='color: darkred'>" . $bookingDetails['Unit_Number'] . "</td>" .
                                "<td style='color: darkred'>" . $bookingDetails['Street_Number'] . "</td>" .
                                "<td style='color: darkred'>" . $bookingDetails['Street_Name'] . "</td>" .
                                "<td style='color: darkred'>" . $bookingDetails['Suburb'] . "</td>" .
                                "<td style='color: darkred'>" . $bookingDetails['Destination_Suburb'] . "</td>" .
                                "<td style='color: darkred'>" . $pickup_date . "  " . $pickup_time . "</td>" .
                                "<td style='color: darkred'>" . $bookingDetails['Status'] . "</td>" .
                            "</tr>" .
                        "</table><br>";
                }
            }
        }else
        {
            echo "Not Found";
        }
    }elseif (preg_match('/[^A-Za-z0-9]/', $bookingNumber))
    {
        //reference number格式错误的提示信息
        echo "<p>Please input correct Reference Number. The format is BRN with 5 digital number</p>";
    } else
    {
        //post获得的reference number查询数据库中对应的数据并打印表格
        $search = mysqli_query($conn, "SELECT * FROM cabs WHERE Reference_Number = '$bookingNumber'");
        if (mysqli_num_rows($search) > 0) {
            while ($bookingDetails = mysqli_fetch_assoc($search)) {
                $pickup_date = date("d/m/Y", strtotime($bookingDetails['Pick_Up_Date']));
                $pickup_time = date("H:i", strtotime($bookingDetails['Pick_up_Time']));
                echo "<table>" .
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
                            "<td >" . $bookingDetails['Reference_Number'] . "</td>" .
                            "<td>" . $bookingDetails['Customer_Name'] . "</td>" .
                            "<td>" . $bookingDetails['Phone_Number'] . "</td>" .
                            "<td>" . $bookingDetails['Unit_Number'] . "</td>" .
                            "<td>" . $bookingDetails['Street_Number'] . "</td>" .
                            "<td>" . $bookingDetails['Street_Name'] . "</td>" .
                            "<td>" . $bookingDetails['Suburb'] . "</td>" .
                            "<td>" . $bookingDetails['Destination_Suburb'] . "</td>" .
                            "<td>" . $pickup_date . "  " . $pickup_time . "</td>" .
                            "<td>" . $bookingDetails['Status'] . "</td>" .
                        "</tr>" .
                    "</table>";
            }
        } else {
            //未查询到对应数据，打印提示信息
            echo "<p>Not Found. Please make sure you input an existing Reference Number with correct format. The format is BRN with 5 digital number</p>";
        }
    }
}