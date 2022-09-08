<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
$error = '';
function mailer($conn){
    //Import PHPMailer classes into the global namespace
    //These must be at the top of your script, not inside a function
    // include_once './includes/dbh.inc.php';

    //Load Composer's autoloader
    require __DIR__. '/vendor/autoload.php';

    //Create an instance; passing `true` enables exceptions

    try {
        if(!isset($_GET['id'])){return;}
        $order_id = $_GET['id'];
        $user_id = '';
        $subtotal = 0.00;
        $shipping_fee = 0.00;
        $total = 0.00;
        $discounted = '0';
        $vat_exempt_subtotal = 0.00;
        $discount = 0.00;

        $receipt_sql = "SELECT * FROM orders_products WHERE order_id='$order_id'";
        $receipt_stmt = mysqli_query($conn, $receipt_sql);
        if(mysqli_num_rows($receipt_stmt)<0){ return;}
        $order = $receipt_stmt;
        
        foreach($receipt_stmt as $row){ 
            $user_id = $row['user_id'];
            $order_id = $row['order_id'];
            $created_at = $row['created_at'];
        }

        $orderdatetime_sql = "SELECT * FROM orders WHERE order_id='$order_id' LIMIT 1";
        $orderdatetime_stmt = mysqli_query($conn, $orderdatetime_sql);
        if(mysqli_num_rows($orderdatetime_stmt)<0){ return;}
        $datetime = [];
        foreach($orderdatetime_stmt as $row){ 
            $subtotal = $row['subtotal'];
            $shipping_fee = $row['shipping_fee'];
            $total = $row['total'];
            $discounted = $row['discounted'];
            if($row['subtotal']){
                $vat_exempt_subtotal = $row['vat_exempt_subtotal'];
                $discount = $row['discount'];
            }

            $created_at = $row['created_at'];
            $datetime = explode(" ", $created_at);

        }
        $datetime = explode(" ", $created_at);

        $receipt_sql = "SELECT u.*, o.*, a.*  FROM users as u,orders as o, address as a WHERE u.user_id='$user_id' AND u.user_id=a.user_id AND o.order_id='$order_id' AND u.user_id=a.user_id LIMIT 1";
        $receipt_stmt = mysqli_query($conn, $receipt_sql);
        if(mysqli_num_rows($receipt_stmt)<0){ return;}
        $user = [];
        foreach($receipt_stmt as $row){ 
            $user = $row;
            
        }
        
        $template = "
        <html>
        <head>
            <style>
                *{
                    font-family: sans-serif;
                    margin: 0;
                    padding: 0;
                }
                .container {
                    margin: 10px;
                }
                .company {
                    margin: 20px 0;
                }
                .color-1 {
                    color: #3F72AF;
                }
                .color-2 {
                    color: #D6E6F2;
                }
                .color-3 {
                    color: #F7FBFC;
                }
                .styled-table {
                    border-collapse: collapse;
                    margin: 25px 0;
                    font-size: 0.9em;
                    font-family: sans-serif;
                    min-width: 400px;
                    box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);
                }
                .styled-table thead tr {
                    background-color: #3F72AF;
                    color: #ffffff;
                    text-align: left;
                }
                .styled-table th,
                .styled-table td {
                    padding: 12px 15px;
                }
                .styled-table tbody tr {
                    border-bottom: 1px solid #dddddd;
                }
                
                .styled-table tbody tr:nth-of-type(even) {
                    background-color: #f3f3f3;
                }
                
                .styled-table tbody tr:last-of-type {
                    border-bottom: 2px solid #3F72AF;
                }
                .styled-table tbody tr.active-row {
                    font-weight: bold;
                    color: #009879;
                }
                .align-right {
                    text-align: left;
                }
                
                h3{
                    margin-bottom: 10px;
                }
            </style>
        </head>
        <body>
            <div class=\"container\">
            <h2 class=\"color-1 company\" >Benj Pharmacy Online</h2>
            <a href=\"mailto:official@benjpharmacy.online\">official@benjpharmacy.online</a>
            <h3>RECEIPT</h3>
            <p><b>Name:&nbsp;</b>".ucfirst($user['first_name'])." ". ucfirst($user['last_name'])."</p>
            <p><b>Email:&nbsp;</b>".$user['email']."</p>
            <p><b>Phone:&nbsp;</b> ".$user['contact']."</p>
            <p><b>Shipping Address:&nbsp;</b> ".$user['address'].", ".ucfirst($user['barangay']).", ".ucfirst($user['city'])."</p>
            <p><b>Payment Method:&nbsp;</b>".strtoupper($user['payment_method'])." </p>
            <p><b>Delivery Option:&nbsp;</b>".strtoupper($user['delivery_option'])." </p>

            <p><b>Date:&nbsp;</b>".$datetime[0]." </p>
            <p><b>Time:&nbsp;</b>".$datetime[1]." </p>
            <div class=\"order\">
            <table class=\"styled-table\">
                <thead>
                    <tr>
                        <th>Description</th>
                        <th>Quantity</th>
                        <th>Item Price</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                ";
                foreach($order as $row){
                    $template .= "
                    <tr>
                        <td>".$row['name']."</td>
                        <td>".$row['quantity']."</td>
                        <td>".$row['price']."</td>
                        <td>".number_format($row['quantity']*$row['price'],2)."</td>
                    </tr>
                    ";
                }
                    
                    
            $template .= "
                </tbody>
            </table>
            ";
                
            $template .= "
            <div class=\"align-right\">
            ";
            if($discounted === '1'){
                $template .= "
                <span style=\"color:white;background:#3F72AF;border-radius:10px;margin:0 10px;padding:3px 5px;text-align:center\">Discount Applied &#10004;</span>
                ";
            }

            
            if($discounted === '1'){
                $template .= "
                    <p><b>Subtotal:</b><span>"." "."&#8369;"." ".number_format($subtotal,2)."</span></p>
                    <p><b>VAT Exempt Subtotal:</b><span>"." "."&#8369;"." ".$vat_exempt_subtotal."</span></p>
                    <p><b>Shipping Fee:</b><span>"." "."&#8369;"." ".$shipping_fee."</span></p>
                    <p><b>Discount:</b><span>"." -"."&#8369;"." ".$discount."</span></p>
                    <p><b>Total:</b><span>"." "."&#8369;"." ".$total."</span></p>
                </div>
                </div>
                ";
                
            }else{
                $template .= "
                    <p><b>Subtotal:</b><span>"." "."&#8369;"." ".number_format($subtotal,2)."</span></p>
                    <p><b>Shipping Fee:</b><span>"." "."&#8369;"." ".$shipping_fee."</span></p>
                    <p><b>Total:</b><span>"." "."&#8369;"." ".$total."</span></p>
                </div>
                </div>
                ";

            }


            $template .= "
                </table>
                </div>
                </body>
                </html>
                ";
            
        $altbodyTemplate = "
        Benj Pharmacy Online
        Email us: official@benjpharmacy.online

        RECEIPT
        Name: ".ucfirst($user['first_name'])." ". ucfirst($user['last_name'])."
        Email: ".$user['email']."
        Phone:  ".$user['contact']."
        Shipping Address:  ".$user['address'].", ".ucfirst($user['barangay']).", ".ucfirst($user['city'])."
        Payment Method: ".strtoupper($user['payment_method'])." 
        Delivery Option: ".strtoupper($user['delivery_option'])."

        Date: ".$datetime[0]."
        Time: ".$datetime[1]."

            Description | Quantity | Item | Price | Total
        ";
        foreach($order as $row){
            $altbodyTemplate .= "
            ".$row['name']." | ".$row['quantity']." | ".$row['price']." | ".number_format($row['quantity']*$row['price'],2)."
            ";
        }
        
        if ( $discounted === '1' ){

            $altbodyTemplate .= "
                Discount Applied
    
                Subtotal: "." "."₱"." ".number_format($subtotal,2)."
                VAT Exempt Subtotal: "." "."₱"." ".number_format($vat_exempt_subtotal,2)."
                Shipping Fee: "." "."₱"." ".$shipping_fee."
                Discount: "." "."-₱"." ".$discount."
                Total: "." "."₱"." ".$total."
            ";
        }else{
            $altbodyTemplate .= "
                Subtotal: "." "."₱"." ".number_format($subtotal,2)."
                Shipping Fee: "." "."₱"." ".$shipping_fee."
                Total: "." "."₱"." ".$total."
            ";
        }



        $mail = new PHPMailer(true);
        //Server settings
        $mail->SMTPDebug = 0;                      //Enable verbose debug output | to enable = SMTP::DEBUG_SERVER
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'smtp.hostinger.com';                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = 'receipt@benjpharmacy.online';                     //SMTP username
        $mail->Password   = '!FJyg!b6D3H6g63';                               //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
        $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        //Recipients
        $mail->setFrom('receipt@benjpharmacy.online', 'benjpharmacy.online');
        $mail->addAddress($user['email']);          //Name is optional
        $mail->addAddress('copyreceipt@benjpharmacy.online');          //Name is optional
        // $mail->addReplyTo('info@example.com', 'Information');
        // $mail->addCC('cc@example.com');
        // $mail->addBCC('bcc@example.com');

        //Attachments
        // $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
        // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = 'Benj Pharmacy Online Receipt - order: '.$order_id;
        $mail->Body    = $template;
        $mail->AltBody = $altbodyTemplate;

        $mail->send();
        return true;
    } catch (Exception $e) {
        $error .=  "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        return false;

    }
}
$mailNotSent = true;
while($mailNotSent){
    if(mailer($conn)) $mailNotSent = false;
}
?>