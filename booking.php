<?php
/**
 * This script listens for standard POST Requests to call the appropriate booking functionality.
 * Author: RuiyangXu 18017865
 */
date_default_timezone_set('PRC');

//receive data via post method
$customer_name = $_POST['cname'];
$phone_number = $_POST['phone'];
$unit_number = $_POST['unumber'];
$street_number = $_POST['snumber'];
$street_name = $_POST['stname'];
$suburb = $_POST['sbname'];
$destination_suburb = $_POST['dsbname'];
$date = $_POST['date'];
$pickup_time = $_POST['time'];

//change format for date and receive the current time and date to compare
$currentDate = date("Y/m/d");
$currentTime = date("H:i", time());

//safe connect
require_once ('sql_Inform.php');
$conn = new mysqli($servername,$username,$password,$myDB,3306);

//connect to database
if ($conn->connect_error)
{
    die("Connect failed" .$conn->connect_error);
}else
{
    /*
     * 对Mysql中not null约束的元素 判断是否有空值
     */
    if (empty($customer_name))
    {
        echo "Customer name is Empty";
    }elseif(empty($phone_number))
    {
        echo "Phone number can not be empty";
    }elseif (empty($street_number))
    {
        echo "Street number can not be empty";
    }elseif (empty($street_name))
    {
        echo "Street name can not be empty";
    }elseif (empty($date))
    {
        echo "Date can not be empty";
    }elseif (empty($pickup_time))
    {
        echo "Time can not be empty";
    }elseif (preg_match('/[^A-Za-z\s]/', $customer_name)) //正则表达式判断user输入是否包含非字符串元素
    {
        echo "Customer name must be string";
    }elseif (preg_match('/[^0-9]/', $phone_number) || strlen($phone_number) < 10 || strlen($phone_number) > 12) //判断phone number是否有非数字类型输入，判断输入字符长度是否在10-12
    {
        echo "Phone number must be number and length between 10-12";
    }elseif (strtotime($date) < strtotime($currentDate))  //check date is a future date
    {
        echo "Please select a future Date.";
    }elseif (strtotime($pickup_time) < strtotime($currentTime) && strtotime($date) <= strtotime($currentDate)) //check time is a future time
    {
        echo "Please select a future Time.";
    }else
    {
        //call function randomNumber创建一个随机数作为reference number
        $randomNumber = randomNumber();
        //根据格式要求需要在5位digital number前加BRN，5位数中，不满足5位数字的，使用str_pad()方法补全填充
        //str_pad() 函数把字符串填充为新的长度
        //str_pad(string,length,pad_string,pad_type)
        /*
         *  string 	必需。规定要填充的字符串。
            length 	必需。规定新字符串的长度。如果该值小于原始字符串的长度，则不进行任何操作。
            pad_string 	可选。规定供填充使用的字符串。默认是空白。
            pad_type 	可选。规定填充字符串的哪边。
            可能的值：
                STR_PAD_BOTH - 填充字符串的两侧。如果不是偶数，则右侧获得额外的填充。
                STR_PAD_LEFT - 填充字符串的左侧。
                STR_PAD_RIGHT - 填充字符串的右侧。这是默认的
         */
        //在这里选择新字符串长度为5，填充“0”,填充于字符串左边（BRN右边）
        $reference_number = "BRN".str_pad($randomNumber, 5, "0", STR_PAD_LEFT);
        //使用strtotime()函数，将user选择的日期转换为unix时间戳，再使用date()函数将时间戳转换为其他格式的时间戳格式
        $pickup_date = date("d/m/Y",strtotime($date));
        //执行sql语句，将获取的所有数据插入到数据库中
        $sql_insert = "INSERT INTO cabs(Reference_Number, Customer_Name, Phone_Number, Unit_Number, Street_Number, Street_Name, Suburb, Destination_Suburb, Pick_Up_Date, Pick_up_Time, Status) 
                       VALUES ('$reference_number', '$customer_name', '$phone_number', '$unit_number', '$street_number', '$street_name', '$suburb', '$destination_suburb', '$date', '$pickup_time', 'Unassigned')";
        if (mysqli_query($conn, $sql_insert))
        {
            //打印确认语句，确认插入成功，打印提示语句
            echo "<h1>Thanks for your booking!</h1><br>"."<p>Booking Reference Number: ".$reference_number."<br>"."Pickup Time: ".$pickup_time."<br>"."Pick up Date: ".$pickup_date."</p>";
        }else
        {
            echo "Error";
        }
    }
}

//创建一个随机数做unique number
function randomNumber()
{
    //使用随机数作为unique number 因为需要有5为digital number, 因此最大值为99999, 最小值为0, 因此, 可能会出现相同数，可以考虑使用uniqid()或md5()生成唯一id
    return (mt_rand(00000,99999));
}