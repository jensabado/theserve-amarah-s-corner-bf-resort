<?php
session_start();
require_once '../includes/database_conn.php';

date_default_timezone_set('Asia/Manila');
$user_id = $_POST['user_id'];
$billing_name = $_POST['billing_name'];
$billing_phone = $_POST['billing_phone'];
$email = $_POST['billing_email'];
$address = $_POST['address'];
$province = $_POST['province'];
$city = $_POST['city'];
$barangay = $_POST['barangay'];
$payment = $_POST['payment'];
$delivery = $_POST['deliver'];
$screenshot = $_FILES['screenshot']['name'];
$screenshottmp = $_FILES['screenshot']['tmp_name'];
$reference = $_POST['reference'];
$shipping_value = $_POST['shipping_value'];
$order_total = $_POST['order_total_val'];
$date = date('F j, Y h:i A');

if ($payment == 2) {
    $imgExt = explode('.', $screenshot);
    $imgExt = strtolower(end($imgExt));

    $newImageName = uniqid() . '.' . $imgExt;
    move_uploaded_file($screenshottmp, '../assets/images/' . $newImageName);

    $insert_orders = mysqli_query($conn, "INSERT INTO orders (user_id, payment_method, delivery_method, shipping_fee, screenshot_payment, reference, order_total, order_date, order_status) VALUES ('$user_id', '$payment', '$delivery', '$shipping_value', '$newImageName', '$reference', '$order_total', '$date', '1')");

    if ($insert_orders) {
        $order_id = mysqli_insert_id($conn);

        $insert_order_address = mysqli_query($conn, "INSERT INTO order_address (order_id, block_street_building, province, city_municipality, barangay) VALUES ('$order_id', '$address', '$province', '$city', '$barangay')");

        if ($insert_order_address) {
            $get_cart = mysqli_query($conn, "SELECT * FROM cart WHERE user_id = $user_id");

            foreach ($get_cart as $row) {
                $product_id = $row['product_id'];
                $cart_id = $row['cart_id'];
                $subcategory_id = $row['subcategory_id'];
                $product_qty = $row['product_qty'];
                $product_total = $row['product_total'];

                $insert_order_list = mysqli_query($conn, "INSERT INTO order_items (order_id, product_id, subcategory_id, qty, product_total) VALUES ('$order_id', '$product_id', '$subcategory_id', '$product_qty', '$product_total')");

                if ($insert_order_list) {
                    $delete_cart_item = mysqli_query($conn, "DELETE FROM cart WHERE cart_id = $cart_id");
                }
            }
            if ($delete_cart_item) {
                $body = [
                    'Messages' => [
                        [
                            'From' => [
                                'Email' => "capstemp00@gmail.com",
                                'Name' => "Amarah's Pizza Corner",
                            ],
                            'To' => [
                                [
                                    'Email' => "$email",
                                    'Name' => "$billing_name",
                                ],
                            ],
                            'Subject' => "Here is the status your order",
                            'HTMLPart' => '<style type="text/css">#outlook a { padding:0; }
                            body { margin:0;padding:0;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%; }
                            table, td { border-collapse:collapse;mso-table-lspace:0pt;mso-table-rspace:0pt; }
                            img { border:0;height:auto;line-height:100%; outline:none;text-decoration:none;-ms-interpolation-mode:bicubic; }
                            p { display:block;margin:13px 0; }</style><!--[if mso]>
                          <noscript>
                          <xml>
                          <o:OfficeDocumentSettings>
                            <o:AllowPNG/>
                            <o:PixelsPerInch>96</o:PixelsPerInch>
                          </o:OfficeDocumentSettings>
                          </xml>
                          </noscript>
                          <![endif]--><!--[if lte mso 11]>
                          <style type="text/css">
                            .mj-outlook-group-fix { width:100% !important; }
                          </style>
                          <![endif]--><style type="text/css">@media only screen and (min-width:480px) {
                          .mj-column-per-100 { width:100% !important; max-width: 100%; }
                        }</style><style media="screen and (min-width:480px)">.moz-text-html .mj-column-per-100 { width:100% !important; max-width: 100%; }</style><style type="text/css">[owa] .mj-column-per-100 { width:100% !important; max-width: 100%; }</style><style type="text/css">@media only screen and (max-width:480px) {
                        table.mj-full-width-mobile { width: 100% !important; }
                        td.mj-full-width-mobile { width: auto !important; }
                      }</style> <body style="word-spacing:normal;background-color:#F4F4F4;"><div style="display:none;font-size:1px;color:#ffffff;line-height:1px;max-height:0px;max-width:0px;opacity:0;overflow:hidden;">Here is your order update status</div><div style="background-color:#F4F4F4;"><!--[if mso | IE]><table align="center" border="0" cellpadding="0" cellspacing="0" class="" role="presentation" style="width:600px;" width="600" ><tr><td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;"><![endif]--><div style="margin:0px auto;max-width:600px;"><table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="width:100%;"><tbody><tr><td style="direction:ltr;font-size:0px;padding:20px 0;padding-bottom:0px;padding-top:0px;text-align:center;"><!--[if mso | IE]><table role="presentation" border="0" cellpadding="0" cellspacing="0"><tr><td class="" style="vertical-align:top;width:600px;" ><![endif]--><div class="mj-column-per-100 mj-outlook-group-fix" style="font-size:0px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;"><table border="0" cellpadding="0" cellspacing="0" role="presentation" style="vertical-align:top;" width="100%"><tbody><tr><td align="center" style="font-size:0px;padding:0px 0px 0px 0px;padding-top:0px;padding-right:0px;padding-bottom:0px;padding-left:0px;word-break:break-word;"><table border="0" cellpadding="0" cellspacing="0" role="presentation" style="border-collapse:collapse;border-spacing:0px;"><tbody><tr><td style="width:600px;"><img alt="" height="auto" src="https://0owzv.mjt.lu/tplimg/0owzv/b/1y0lu/mz3o.png" style="border:none;border-radius:px;display:block;outline:none;text-decoration:none;height:auto;width:100%;font-size:13px;" width="600"></td></tr></tbody></table></td></tr></tbody></table></div><!--[if mso | IE]></td></tr></table><![endif]--></td></tr></tbody></table></div><!--[if mso | IE]></td></tr></table><table align="center" border="0" cellpadding="0" cellspacing="0" class="" role`="presentation" style="width:600px;" width="600" bgcolor="#ffffff" ><tr><td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;"><![endif]--><div style="background:#ffffff;background-color:#ffffff;margin:0px auto;max-width:600px;"><table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="background:#ffffff;background-color:#ffffff;width:100%;"><tbody><tr><td style="direction:ltr;font-size:0px;padding:20px 0px 20px 0px;text-align:center;"><!--[if mso | IE]><table role="presentation" border="0" cellpadding="0" cellspacing="0"><tr><td class="" style="vertical-align:top;width:600px;" ><![endif]--><div class="mj-column-per-100 mj-outlook-group-fix" style="font-size:0px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;"><table border="0" cellpadding="0" cellspacing="0" role="presentation" style="vertical-align:top;" width="100%"><tbody><tr><td align="left" style="font-size:0px;padding:0px 25px 0px 25px;padding-top:0px;padding-bottom:0px;word-break:break-word;"><div style="font-family:Arial, sans-serif;font-size:13px;letter-spacing:normal;line-height:1;text-align:left;color:#000000;"><h1 style="text-align:left; margin-top: 10px; margin-bottom: 10px; font-weight: normal;"><span style="font-size:20px;letter-spacing:normal;text-align:left;color:#55575d;font-family:Arial;"><b>Hi ' . $billing_name . '!</b></span></h1></div></td></tr><tr><td align="left" style="font-size:0px;padding:0px 25px 0px 25px;padding-top:0px;padding-bottom:0px;word-break:break-word;"><div style="font-family:Arial, sans-serif;font-size:13px;letter-spacing:normal;line-height:1;text-align:left;color:#000000;"><p style="text-align: left; margin: 10px 0; margin-top: 10px; margin-bottom: 10px;"><span style="font-size:13px;letter-spacing:normal;text-align:left;color:#55575d;font-family:Arial;">Here is the status of your order, as of now it is pending and awaiting the confirmation by one of our staffs. please sit back and relax as we take care of your order.</span></p></div></td></tr></tbody></table></div><!--[if mso | IE]></td></tr></table><![endif]--></td></tr></tbody></table></div><!--[if mso | IE]></td></tr></table><table align="center" border="0" cellpadding="0" cellspacing="0" class="" role="presentation" style="width:600px;" width="600" bgcolor="#ffffff" ><tr><td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;"><![endif]--><div style="background:#ffffff;background-color:#ffffff;margin:0px auto;max-width:600px;"><table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="background:#ffffff;background-color:#ffffff;width:100%;"><tbody><tr><td style="direction:ltr;font-size:0px;padding:20px 0;padding-bottom:0px;padding-top:0px;text-align:center;"><!--[if mso | IE]><table role="presentation" border="0" cellpadding="0" cellspacing="0"><tr><td class="" style="vertical-align:top;width:600px;" ><![endif]--><div class="mj-column-per-100 mj-outlook-group-fix" style="font-size:0px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;"><table border="0" cellpadding="0" cellspacing="0" role="presentation" style="vertical-align:top;" width="100%"><tbody><tr><td align="center" style="font-size:0px;padding:0px 25px 0px 25px;padding-top:0px;padding-right:25px;padding-bottom:0px;padding-left:25px;word-break:break-word;"><table border="0" cellpadding="0" cellspacing="0" role="presentation" style="border-collapse:collapse;border-spacing:0px;"><tbody><tr><td style="width:550px;"><img alt="" height="auto" src="https://0owzv.mjt.lu/tplimg/0owzv/b/1y0lu/mzgm.png" style="border:none;border-radius:px;display:block;outline:none;text-decoration:none;height:auto;width:100%;font-size:13px;" width="550"></td></tr></tbody></table></td></tr></tbody></table></div><!--[if mso | IE]></td></tr></table><![endif]--></td></tr></tbody></table></div><!--[if mso | IE]></td></tr></table><table align="center" border="0" cellpadding="0" cellspacing="0" class="" role="presentation" style="width:600px;" width="600" bgcolor="#ffffff" ><tr><td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;"><![endif]--><div style="background:#ffffff;background-color:#ffffff;margin:0px auto;max-width:600px;"><table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="background:#ffffff;background-color:#ffffff;width:100%;"><tbody><tr><td style="direction:ltr;font-size:0px;padding:20px 0;text-align:center;"><!--[if mso | IE]><table role="presentation" border="0" cellpadding="0" cellspacing="0"><tr><td class="" style="vertical-align:top;width:600px;" ><![endif]--><div class="mj-column-per-100 mj-outlook-group-fix" style="font-size:0px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;"><table border="0" cellpadding="0" cellspacing="0" role="presentation" style="vertical-align:top;" width="100%"><tbody><tr><td align="left" style="font-size:0px;padding:10px 25px;padding-top:0px;padding-bottom:0px;word-break:break-word;"><div style="font-family:Arial, sans-serif;font-size:13px;letter-spacing:normal;line-height:1;text-align:left;color:#000000;"><p style="text-align: left; margin: 10px 0; margin-top: 10px; margin-bottom: 10px;"><span style="font-size:13px;letter-spacing:normal;text-align:left;color:#55575d;font-family:Arial;">If you have any concerns, </span><span style="font-size:13px;letter-spacing:normal;text-align:left;color:#55575d;font-family:Arial;"><br></span><span style="font-size:13px;letter-spacing:normal;text-align:left;color:#55575d;font-family:Arial;"><br></span><span style="font-size:13px;letter-spacing:normal;text-align:left;color:#55575d;font-family:Arial;">you may contact us at "bfresortamarahscorner@gmail.com"</span></p></div></td></tr></tbody></table></div><!--[if mso | IE]></td></tr></table><![endif]--></td></tr></tbody></table></div><!--[if mso | IE]></td></tr></table><table align="center" border="0" cellpadding="0" cellspacing="0" class="" role="presentation" style="width:600px;" width="600" ><tr><td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;"><![endif]--><div style="margin:0px auto;max-width:600px;"><table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="width:100%;"><tbody><tr><td style="direction:ltr;font-size:0px;padding:20px 0px 20px 0px;text-align:center;"><!--[if mso | IE]><table role="presentation" border="0" cellpadding="0" cellspacing="0"><tr><td class="" style="vertical-align:top;width:600px;" ><![endif]--><div class="mj-column-per-100 mj-outlook-group-fix" style="font-size:0px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;"><table border="0" cellpadding="0" cellspacing="0" role="presentation" style="vertical-align:top;" width="100%"><tbody></tbody></table></div><!--[if mso | IE]></td></tr></table><![endif]--></td></tr></tbody></table></div><!--[if mso | IE]></td></tr></table><table align="center" border="0" cellpadding="0" cellspacing="0" class="" role="presentation" style="width:600px;" width="600" ><tr><td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;"><![endif]--><div style="margin:0px auto;max-width:600px;"><table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="width:100%;"><tbody><tr><td style="direction:ltr;font-size:0px;padding:20px 0px 20px 0px;text-align:center;"><!--[if mso | IE]><table role="presentation" border="0" cellpadding="0" cellspacing="0"><tr><td class="" style="vertical-align:top;width:600px;" ><![endif]--><div class="mj-column-per-100 mj-outlook-group-fix" style="font-size:0px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;"><table border="0" cellpadding="0" cellspacing="0" role="presentation" width="100%"><tbody><tr><td style="vertical-align:top;padding:0;"><table border="0" cellpadding="0" cellspacing="0" role="presentation" width="100%"><tbody></tbody></table></td></tr></tbody></table></div><!--[if mso | IE]></td></tr></table><![endif]--></td></tr></tbody></table></div><!--[if mso | IE]></td></tr></table><table align="center" border="0" cellpadding="0" cellspacing="0" class="" role="presentation" style="width:600px;" width="600" ><tr><td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;"><![endif]--><div style="margin:0px auto;max-width:600px;"><table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="width:100%;"><tbody><tr><td style="direction:ltr;font-size:0px;padding:20px 0px 20px 0px;text-align:center;"><!--[if mso | IE]><table role="presentation" border="0" cellpadding="0" cellspacing="0"><tr><td class="" style="vertical-align:top;width:600px;" ><![endif]--><div class="mj-column-per-100 mj-outlook-group-fix" style="font-size:0px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;"><table border="0" cellpadding="0" cellspacing="0" role="presentation" width="100%"><tbody><tr><td style="vertical-align:top;padding:0;"><table border="0" cellpadding="0" cellspacing="0" role="presentation" width="100%"><tbody><tr><td align="left" style="font-size:0px;padding:10px 25px;padding-top:0px;padding-bottom:0px;word-break:break-word;"><div style="font-family:Arial, sans-serif;font-size:13px;letter-spacing:normal;line-height:1;text-align:left;color:#000000;"><p style="text-align: center; margin: 10px 0; margin-top: 10px; margin-bottom: 10px;"><span style="font-size:13px;letter-spacing:normal;text-align:center;color:#55575d;font-family:Arial;line-height:22px;">This e-mail has been sent to [[EMAIL_TO]], <a href="[[UNSUB_LINK_EN]]" style="color:inherit;text-decoration:none;" target="_blank">click here to unsubscribe</a>.</span></p></div></td></tr><tr><td align="left" style="font-size:0px;padding:10px 25px;padding-top:0px;padding-bottom:0px;word-break:break-word;"><div style="font-family:Arial, sans-serif;font-size:13px;letter-spacing:normal;line-height:1;text-align:left;color:#000000;"><p style="text-align: center; margin: 10px 0; margin-top: 10px; margin-bottom: 10px;"><span style="font-size:13px;letter-spacing:normal;text-align:center;color:#55575d;font-family:Arial;line-height:22px;">   PH</span></p></div></td></tr></tbody></table></td></tr></tbody></table></div><!--[if mso | IE]></td></tr></table><![endif]--></td></tr></tbody></table></div><!--[if mso | IE]></td></tr></table><![endif]--></div></body>',
                        ],
                    ],
                ];
            
                $ch = curl_init();
            
                curl_setopt($ch, CURLOPT_URL, "https://api.mailjet.com/v3.1/send");
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/json')
                );
                curl_setopt($ch, CURLOPT_USERPWD, "a42bfdf767ddb807f6aaf82282a24f7a:c58f5c7f72f66fdea695a4a6ceb4e219");
                $server_output = curl_exec($ch);
                curl_close($ch);
            
                $response = json_decode($server_output);
                if ($response->Messages[0]->Status == 'success') {
                    $_SESSION['order_id'] = $order_id;
                    $_SESSION['checkout'] = 'success';
                    echo 'success';
                }
            }
        }
    }
} else {
    $insert_orders = mysqli_query($conn, "INSERT INTO orders (user_id, payment_method, delivery_method, shipping_fee, order_total, order_date, order_status) VALUES ('$user_id', '$payment', '$delivery', '$shipping_value', '$order_total', '$date', '1')");

    if ($insert_orders) {
        $order_id = mysqli_insert_id($conn);

        $insert_order_address = mysqli_query($conn, "INSERT INTO order_address (order_id, billing_name, billing_number, block_street_building, province, city_municipality, barangay) VALUES ('$order_id', '$billing_name', '$billing_phone', '$address', '$province', '$city', '$barangay')");

        if ($insert_order_address) {
            $get_cart = mysqli_query($conn, "SELECT * FROM cart WHERE user_id = $user_id");

            foreach ($get_cart as $row) {
                $product_id = $row['product_id'];
                $cart_id = $row['cart_id'];
                $subcategory_id = $row['subcategory_id'];
                $product_qty = $row['product_qty'];
                $product_total = $row['product_total'];

                $insert_order_list = mysqli_query($conn, "INSERT INTO order_items (order_id, product_id, subcategory_id, qty, product_total) VALUES ('$order_id', '$product_id', '$subcategory_id', '$product_qty', '$product_total')");

                if ($insert_order_list) {
                    $delete_cart_item = mysqli_query($conn, "DELETE FROM cart WHERE cart_id = $cart_id");
                }
            }
            if ($delete_cart_item) {
                $body = [
                    'Messages' => [
                        [
                            'From' => [
                                'Email' => "capstemp00@gmail.com",
                                'Name' => "Amarah's Pizza Corner",
                            ],
                            'To' => [
                                [
                                    'Email' => "$email",
                                    'Name' => "$billing_name",
                                ],
                            ],
                            'Subject' => "Here is the status your order",
                            'HTMLPart' => '<style type="text/css">#outlook a { padding:0; }
                            body { margin:0;padding:0;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%; }
                            table, td { border-collapse:collapse;mso-table-lspace:0pt;mso-table-rspace:0pt; }
                            img { border:0;height:auto;line-height:100%; outline:none;text-decoration:none;-ms-interpolation-mode:bicubic; }
                            p { display:block;margin:13px 0; }</style><!--[if mso]>
                          <noscript>
                          <xml>
                          <o:OfficeDocumentSettings>
                            <o:AllowPNG/>
                            <o:PixelsPerInch>96</o:PixelsPerInch>
                          </o:OfficeDocumentSettings>
                          </xml>
                          </noscript>
                          <![endif]--><!--[if lte mso 11]>
                          <style type="text/css">
                            .mj-outlook-group-fix { width:100% !important; }
                          </style>
                          <![endif]--><style type="text/css">@media only screen and (min-width:480px) {
                          .mj-column-per-100 { width:100% !important; max-width: 100%; }
                        }</style><style media="screen and (min-width:480px)">.moz-text-html .mj-column-per-100 { width:100% !important; max-width: 100%; }</style><style type="text/css">[owa] .mj-column-per-100 { width:100% !important; max-width: 100%; }</style><style type="text/css">@media only screen and (max-width:480px) {
                        table.mj-full-width-mobile { width: 100% !important; }
                        td.mj-full-width-mobile { width: auto !important; }
                      }</style> <body style="word-spacing:normal;background-color:#F4F4F4;"><div style="display:none;font-size:1px;color:#ffffff;line-height:1px;max-height:0px;max-width:0px;opacity:0;overflow:hidden;">Here is your order update status</div><div style="background-color:#F4F4F4;"><!--[if mso | IE]><table align="center" border="0" cellpadding="0" cellspacing="0" class="" role="presentation" style="width:600px;" width="600" ><tr><td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;"><![endif]--><div style="margin:0px auto;max-width:600px;"><table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="width:100%;"><tbody><tr><td style="direction:ltr;font-size:0px;padding:20px 0;padding-bottom:0px;padding-top:0px;text-align:center;"><!--[if mso | IE]><table role="presentation" border="0" cellpadding="0" cellspacing="0"><tr><td class="" style="vertical-align:top;width:600px;" ><![endif]--><div class="mj-column-per-100 mj-outlook-group-fix" style="font-size:0px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;"><table border="0" cellpadding="0" cellspacing="0" role="presentation" style="vertical-align:top;" width="100%"><tbody><tr><td align="center" style="font-size:0px;padding:0px 0px 0px 0px;padding-top:0px;padding-right:0px;padding-bottom:0px;padding-left:0px;word-break:break-word;"><table border="0" cellpadding="0" cellspacing="0" role="presentation" style="border-collapse:collapse;border-spacing:0px;"><tbody><tr><td style="width:600px;"><img alt="" height="auto" src="https://0owzv.mjt.lu/tplimg/0owzv/b/1y0lu/mz3o.png" style="border:none;border-radius:px;display:block;outline:none;text-decoration:none;height:auto;width:100%;font-size:13px;" width="600"></td></tr></tbody></table></td></tr></tbody></table></div><!--[if mso | IE]></td></tr></table><![endif]--></td></tr></tbody></table></div><!--[if mso | IE]></td></tr></table><table align="center" border="0" cellpadding="0" cellspacing="0" class="" role`="presentation" style="width:600px;" width="600" bgcolor="#ffffff" ><tr><td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;"><![endif]--><div style="background:#ffffff;background-color:#ffffff;margin:0px auto;max-width:600px;"><table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="background:#ffffff;background-color:#ffffff;width:100%;"><tbody><tr><td style="direction:ltr;font-size:0px;padding:20px 0px 20px 0px;text-align:center;"><!--[if mso | IE]><table role="presentation" border="0" cellpadding="0" cellspacing="0"><tr><td class="" style="vertical-align:top;width:600px;" ><![endif]--><div class="mj-column-per-100 mj-outlook-group-fix" style="font-size:0px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;"><table border="0" cellpadding="0" cellspacing="0" role="presentation" style="vertical-align:top;" width="100%"><tbody><tr><td align="left" style="font-size:0px;padding:0px 25px 0px 25px;padding-top:0px;padding-bottom:0px;word-break:break-word;"><div style="font-family:Arial, sans-serif;font-size:13px;letter-spacing:normal;line-height:1;text-align:left;color:#000000;"><h1 style="text-align:left; margin-top: 10px; margin-bottom: 10px; font-weight: normal;"><span style="font-size:20px;letter-spacing:normal;text-align:left;color:#55575d;font-family:Arial;"><b>Hi ' . $billing_name . '!</b></span></h1></div></td></tr><tr><td align="left" style="font-size:0px;padding:0px 25px 0px 25px;padding-top:0px;padding-bottom:0px;word-break:break-word;"><div style="font-family:Arial, sans-serif;font-size:13px;letter-spacing:normal;line-height:1;text-align:left;color:#000000;"><p style="text-align: left; margin: 10px 0; margin-top: 10px; margin-bottom: 10px;"><span style="font-size:13px;letter-spacing:normal;text-align:left;color:#55575d;font-family:Arial;">Here is the status of your order, as of now it is pending and awaiting the confirmation by one of our staffs. please sit back and relax as we take care of your order.</span></p></div></td></tr></tbody></table></div><!--[if mso | IE]></td></tr></table><![endif]--></td></tr></tbody></table></div><!--[if mso | IE]></td></tr></table><table align="center" border="0" cellpadding="0" cellspacing="0" class="" role="presentation" style="width:600px;" width="600" bgcolor="#ffffff" ><tr><td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;"><![endif]--><div style="background:#ffffff;background-color:#ffffff;margin:0px auto;max-width:600px;"><table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="background:#ffffff;background-color:#ffffff;width:100%;"><tbody><tr><td style="direction:ltr;font-size:0px;padding:20px 0;padding-bottom:0px;padding-top:0px;text-align:center;"><!--[if mso | IE]><table role="presentation" border="0" cellpadding="0" cellspacing="0"><tr><td class="" style="vertical-align:top;width:600px;" ><![endif]--><div class="mj-column-per-100 mj-outlook-group-fix" style="font-size:0px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;"><table border="0" cellpadding="0" cellspacing="0" role="presentation" style="vertical-align:top;" width="100%"><tbody><tr><td align="center" style="font-size:0px;padding:0px 25px 0px 25px;padding-top:0px;padding-right:25px;padding-bottom:0px;padding-left:25px;word-break:break-word;"><table border="0" cellpadding="0" cellspacing="0" role="presentation" style="border-collapse:collapse;border-spacing:0px;"><tbody><tr><td style="width:550px;"><img alt="" height="auto" src="https://0owzv.mjt.lu/tplimg/0owzv/b/1y0lu/mzgm.png" style="border:none;border-radius:px;display:block;outline:none;text-decoration:none;height:auto;width:100%;font-size:13px;" width="550"></td></tr></tbody></table></td></tr></tbody></table></div><!--[if mso | IE]></td></tr></table><![endif]--></td></tr></tbody></table></div><!--[if mso | IE]></td></tr></table><table align="center" border="0" cellpadding="0" cellspacing="0" class="" role="presentation" style="width:600px;" width="600" bgcolor="#ffffff" ><tr><td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;"><![endif]--><div style="background:#ffffff;background-color:#ffffff;margin:0px auto;max-width:600px;"><table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="background:#ffffff;background-color:#ffffff;width:100%;"><tbody><tr><td style="direction:ltr;font-size:0px;padding:20px 0;text-align:center;"><!--[if mso | IE]><table role="presentation" border="0" cellpadding="0" cellspacing="0"><tr><td class="" style="vertical-align:top;width:600px;" ><![endif]--><div class="mj-column-per-100 mj-outlook-group-fix" style="font-size:0px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;"><table border="0" cellpadding="0" cellspacing="0" role="presentation" style="vertical-align:top;" width="100%"><tbody><tr><td align="left" style="font-size:0px;padding:10px 25px;padding-top:0px;padding-bottom:0px;word-break:break-word;"><div style="font-family:Arial, sans-serif;font-size:13px;letter-spacing:normal;line-height:1;text-align:left;color:#000000;"><p style="text-align: left; margin: 10px 0; margin-top: 10px; margin-bottom: 10px;"><span style="font-size:13px;letter-spacing:normal;text-align:left;color:#55575d;font-family:Arial;">If you have any concerns, </span><span style="font-size:13px;letter-spacing:normal;text-align:left;color:#55575d;font-family:Arial;"><br></span><span style="font-size:13px;letter-spacing:normal;text-align:left;color:#55575d;font-family:Arial;"><br></span><span style="font-size:13px;letter-spacing:normal;text-align:left;color:#55575d;font-family:Arial;">you may contact us at "bfresortamarahscorner@gmail.com"</span></p></div></td></tr></tbody></table></div><!--[if mso | IE]></td></tr></table><![endif]--></td></tr></tbody></table></div><!--[if mso | IE]></td></tr></table><table align="center" border="0" cellpadding="0" cellspacing="0" class="" role="presentation" style="width:600px;" width="600" ><tr><td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;"><![endif]--><div style="margin:0px auto;max-width:600px;"><table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="width:100%;"><tbody><tr><td style="direction:ltr;font-size:0px;padding:20px 0px 20px 0px;text-align:center;"><!--[if mso | IE]><table role="presentation" border="0" cellpadding="0" cellspacing="0"><tr><td class="" style="vertical-align:top;width:600px;" ><![endif]--><div class="mj-column-per-100 mj-outlook-group-fix" style="font-size:0px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;"><table border="0" cellpadding="0" cellspacing="0" role="presentation" style="vertical-align:top;" width="100%"><tbody></tbody></table></div><!--[if mso | IE]></td></tr></table><![endif]--></td></tr></tbody></table></div><!--[if mso | IE]></td></tr></table><table align="center" border="0" cellpadding="0" cellspacing="0" class="" role="presentation" style="width:600px;" width="600" ><tr><td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;"><![endif]--><div style="margin:0px auto;max-width:600px;"><table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="width:100%;"><tbody><tr><td style="direction:ltr;font-size:0px;padding:20px 0px 20px 0px;text-align:center;"><!--[if mso | IE]><table role="presentation" border="0" cellpadding="0" cellspacing="0"><tr><td class="" style="vertical-align:top;width:600px;" ><![endif]--><div class="mj-column-per-100 mj-outlook-group-fix" style="font-size:0px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;"><table border="0" cellpadding="0" cellspacing="0" role="presentation" width="100%"><tbody><tr><td style="vertical-align:top;padding:0;"><table border="0" cellpadding="0" cellspacing="0" role="presentation" width="100%"><tbody></tbody></table></td></tr></tbody></table></div><!--[if mso | IE]></td></tr></table><![endif]--></td></tr></tbody></table></div><!--[if mso | IE]></td></tr></table><table align="center" border="0" cellpadding="0" cellspacing="0" class="" role="presentation" style="width:600px;" width="600" ><tr><td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;"><![endif]--><div style="margin:0px auto;max-width:600px;"><table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="width:100%;"><tbody><tr><td style="direction:ltr;font-size:0px;padding:20px 0px 20px 0px;text-align:center;"><!--[if mso | IE]><table role="presentation" border="0" cellpadding="0" cellspacing="0"><tr><td class="" style="vertical-align:top;width:600px;" ><![endif]--><div class="mj-column-per-100 mj-outlook-group-fix" style="font-size:0px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;"><table border="0" cellpadding="0" cellspacing="0" role="presentation" width="100%"><tbody><tr><td style="vertical-align:top;padding:0;"><table border="0" cellpadding="0" cellspacing="0" role="presentation" width="100%"><tbody><tr><td align="left" style="font-size:0px;padding:10px 25px;padding-top:0px;padding-bottom:0px;word-break:break-word;"><div style="font-family:Arial, sans-serif;font-size:13px;letter-spacing:normal;line-height:1;text-align:left;color:#000000;"><p style="text-align: center; margin: 10px 0; margin-top: 10px; margin-bottom: 10px;"><span style="font-size:13px;letter-spacing:normal;text-align:center;color:#55575d;font-family:Arial;line-height:22px;">This e-mail has been sent to [[EMAIL_TO]], <a href="[[UNSUB_LINK_EN]]" style="color:inherit;text-decoration:none;" target="_blank">click here to unsubscribe</a>.</span></p></div></td></tr><tr><td align="left" style="font-size:0px;padding:10px 25px;padding-top:0px;padding-bottom:0px;word-break:break-word;"><div style="font-family:Arial, sans-serif;font-size:13px;letter-spacing:normal;line-height:1;text-align:left;color:#000000;"><p style="text-align: center; margin: 10px 0; margin-top: 10px; margin-bottom: 10px;"><span style="font-size:13px;letter-spacing:normal;text-align:center;color:#55575d;font-family:Arial;line-height:22px;">   PH</span></p></div></td></tr></tbody></table></td></tr></tbody></table></div><!--[if mso | IE]></td></tr></table><![endif]--></td></tr></tbody></table></div><!--[if mso | IE]></td></tr></table><![endif]--></div></body>',
                        ],
                    ],
                ];
            
                $ch = curl_init();
            
                curl_setopt($ch, CURLOPT_URL, "https://api.mailjet.com/v3.1/send");
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/json')
                );
                curl_setopt($ch, CURLOPT_USERPWD, "a42bfdf767ddb807f6aaf82282a24f7a:c58f5c7f72f66fdea695a4a6ceb4e219");
                $server_output = curl_exec($ch);
                curl_close($ch);
            
                $response = json_decode($server_output);
                if ($response->Messages[0]->Status == 'success') {
                    $_SESSION['order_id'] = $order_id;
                    $_SESSION['checkout'] = 'success';
                    echo 'success';
                }
            }
        }
    }
}
