<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.12.0/css/all.css">
    <link href="https://fonts.googleapis.com/css?family=PT+Sans:400,700|PT+Sans+Caption:400,700" rel="stylesheet"
          type="text/css">
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@300&display=swap" rel="stylesheet">
    <style type="text/css">
        @import url('https://fonts.googleapis.com/css2?family=Manrope:wght@300;400;500&display=swap');
        /* Reset Styles */
        #outlook a {
            padding: 0;
        }

        body {
            width: 100% !important;
            -webkit-text-size-adjust: none;
            -ms-text-size-adjust: none;
            margin: 0;
            padding: 0;
        }

        .ReadMsgBody {
            width: 100%;
        }

        .ExternalClass {
            width: 100%;
        }

        .backgroundTable {
            margin: 0 auto;
            padding: 0;
            width: 100% !important;
        }

        table td {
            border-collapse: collapse;
        }

        .ExternalClass * {
            line-height: 115%;
        }

        .inbox-overlay {
            background-image: url('https://image.freepik.com/free-photo/abstract-polygonal-space-low-poly-dark-background-3d-rendering_7247-234.jpg') !important;

        }

        /* Link Fixes */
        a[x-apple-data-detectors] {
            color: inherit !important;
            text-decoration: none !important;
            font-size: inherit !important;
            font-family: inherit !important;
            font-weight: inherit !important;
            line-height: inherit !important;
        }

        .btn-download a:hover {
            font-size: 18px !important;
        }

        .btn-see a:hover {
            font-size: 15px !important;
        }

        .capTitle tr {
            width: 50%;
        }

        .displayNone {
            display: block;
        }

        /* All Your Font Jazz */
        /* Google Fonts */
        @media screen and (-webkit-min-device-pixel-ratio: 0) {
            /* Webkit styles */
            .PTSans {
                font-family: 'PT Sans', sans-serif !important;
            }

            .PTSans strong {
                font-weight: 700 !important;
            }

            .PTSans.regular strong {
                font-weight: 400 !important;
            }
        }

        @-moz-document url-prefix() {
            /* Firefox styles */
            .PTSans {
                font-family: 'PT Sans', sans-serif !important;
            }
            .PTSans strong {
                font-weight: 700 !important;
            }
            .PTSans.regular strong {
                font-weight: 400 !important;
            }
        }

        @media screen and (-ms-high-contrast: none),
        (-ms-high-contrast: active) {
            /* I.E. styles */
            .PTSans {
                font-family: 'PT Sans Caption', sans-serif !important;
            }

            .PTSans strong {
                font-weight: 700 !important;
            }

            .PTSans.regular strong {
                font-weight: 400 !important;
            }
        }

        /* Caption */
        @media screen and (-webkit-min-device-pixel-ratio: 0) {
            /* Webkit styles */
            .PTSansCaption {
                font-family: 'PT Sans Caption', sans-serif !important;
            }

            .PTSansCaption strong {
                font-weight: 700 !important;
            }

            .PTSansCaption.regular strong {
                font-weight: 400 !important;
            }
        }

        @-moz-document url-prefix() {
            /* Firefox styles */
            .PTSansCaption {
                font-family: 'PT Sans Caption', sans-serif !important;
            }
            .PTSansCaption strong {
                font-weight: 700 !important;
            }
            .PTSansCaption.regular strong {
                font-weight: 400 !important;
            }
        }

        @media screen and (-ms-high-contrast: none),
        (-ms-high-contrast: active) {
            /* I.E. styles */
            .PTSansCaption {
                font-family: 'PT Sans Caption', sans-serif !important;
            }

            .PTSansCaption strong {
                font-weight: 700 !important;
            }

            .PTSansCaption.regular strong {
                font-weight: 400 !important;
            }
        }

        /* Mobile Styles */
        @media only screen and (max-width: 480px) {
            /********** CUSTOM STYLES ***********/
            .capTitle {
                display: flex !important;
                flex-direction: column;
            }

            .capTitle tr {
                width: 100% !important;
            }

            .captionTitle {
                text-align: center !important;
                padding: 0 !important;
            }

            .btn-see {
                text-align: center;
            }

            .displayNone {
                display: none;
            }

            .body-width {
                width: 250px !important;
            }

            .triangle-mobile {
                background-size: cover !important;
                background-repeat: no-repeat;
                background-position: 50% 0 !important
            }

            .mobile-bg {
                height: 764px !important;
            }

            .triangle-height {
                height: 410px !important
            }

            .hero-bg {
                background-size: cover !important;
                background-position: -40px 10px !important
            }

            /****** Add Custom Styles Here ******/
            /* General Styles */
            .container {
                width: 100% !important;
                overflow: hidden;
            }

            .mobileHide {
                display: none !important;
            }

            .displayInlineBlock {
                display: inline-block;
            }

            .fullWidth {
                width: 100% !important;
                height: auto;
            }

            .halfWidth {
                width: 50% !important;
                height: auto;
            }

            .floatLeft {
                float: left;
            }

            .floatRight {
                float: right;
            }

            .heightAuto {
                height: auto;
            }

            th {
                width: 100% !important;
                display: block !important;
            }

            .mobileStack th {
                width: 100% !important;
                display: block !important;
            }

            /* Images */
            .fullWidthImg img {
                width: 100% !important;
                height: auto;
            }

            .fullImg {
                width: 100% !important;
            }

            /* Buttons */
            .fullWidthBtn {
                width: 100% !important;
                height: auto;
                border-left: 0 none !important;
                border-right: 0 none !important;
                padding-left: 0 !important;
                padding-right: 0 !important;
            }

            /* Text */
            .mobileAlignCenter {
                text-align: center !important;
                margin: auto;
                float: none;
            }

            .mobileAlignLeft {
                text-align: left !important;
            }

            .copy13 {
                font-size: 13px !important;
                line-height: 1.5 !important;
            }

            .copy14 {
                font-size: 14px !important;
                line-height: 1.5 !important;
            }

            .copy16 {
                font-size: 16px !important;
                line-height: 1.5 !important;
            }

            .copy18 {
                font-size: 18px !important;
                line-height: 1.5 !important;
            }

            .copy21 {
                font-size: 21px !important;
                line-height: 1.5 !important;
            }

            .copy24 {
                font-size: 24px !important;
                line-height: 1.5 !important;
            }

            .copy30 {
                font-size: 30px !important;
                line-height: 1.5 !important;
            }

            .copy52 {
                font-size: 52px !important;
            }

            .copy73 {
                font-size: 73px !important;
            }

            /* Borders */
            .borderTop {
                border-top: 1px solid #e5e5e5;
            }

            .borderTopBold {
                border-top: 2px solid #559EBF;
            }

            .borderBottom {
                border-bottom: 1px solid #e5e5e5;
            }

            .borderBottomBold {
                border-bottom: 2px solid #559EBF;
            }

            .borderNone {
                border: 0 none !important;
            }

            .borderTopNone {
                border-top: 0 none !important;
            }

            .borderRightNone {
                border-right: 0 none !important;
            }

            .borderBottomNone {
                border-bottom: 0 none !important;
            }

            .borderLeftNone {
                border-left: 0 none !important;
            }

            /* Padding */
            .contentPadding {
                padding-left: 10px !important;
                padding-right: 10px !important;
            }

            .contentPadding20 {
                padding-left: 20px !important;
                padding-right: 20px !important;
            }

            .paddingNone {
                padding: 0 !important;
            }

            .paddingLeft0 {
                padding-left: 0 !important;
            }

            .paddingRight0 {
                padding-right: 0 !important;
            }

            .paddingTop0 {
                padding-top: 0 !important;
            }

            .paddingTop2 {
                padding-top: 2px !important;
            }

            .paddingTop5 {
                padding-top: 5px !important;
            }

            .paddingTop10 {
                padding-top: 10px !important;
            }

            .paddingTop15 {
                padding-top: 15px !important;
            }

            .paddingTop20 {
                padding-top: 20px !important;
            }

            .paddingTop25 {
                padding-top: 25px !important;
            }

            .paddingTop35 {
                padding-top: 35px !important;
            }

            .paddingBottom0 {
                padding-bottom: 0 !important;
            }

            .paddingBottom2 {
                padding-bottom: 2px !important;
            }

            .paddingBottom5 {
                padding-bottom: 5px !important;
            }

            .paddingBottom10 {
                padding-bottom: 10px !important;
            }

            .paddingBottom15 {
                padding-bottom: 15px !important;
            }

            .paddingBottom20 {
                padding-bottom: 20px !important;
            }

            .paddingBottom25 {
                padding-bottom: 25px !important;
            }

            .paddingBottom30 {
                padding-bottom: 30px !important;
            }

            /* Footer Nav */
            .footer-nav a {
                display: block !important;
                width: 100%;
                padding: 15px 0 !important;
                border-top: 1px solid #ecece8 !important;
                border-right: 0 !important;
                border-left: 0 !important;
                font-size: 13px !important;
            }

            .footer-nav,
            .footer-nav tbody,
            .footer-nav tr,
            .footer-nav td {
                width: 100% !important;
                max-height: inherit !important;
                overflow: visible !important;
                display: block !important;
                float: none !important;
            }

            .footer-nav table {
                display: table !important;
                width: 100% !important;
            }

            .footer-nav tbody {
                display: table !important;
            }

            .footer-nav tr {
                display: table-row !important;
            }

            .footer-nav td {
                display: table-cell !important;
            }

            /* Mobile Show */
            .mobileShow,
            .mobileShow tbody,
            .mobileShow tr,
            .mobileShow td {
                width: 100% !important;
                max-height: inherit !important;
                overflow: visible !important;
                display: block !important;
                float: none !important;
            }

            .mobileShow table {
                display: table !important;
                width: 100% !important;
            }

            .mobileShow tbody {
                display: table !important;
            }

            .mobileShow tr {
                display: table-row !important;
            }

            .mobileShow td {
                display: table-cell !important;
            }

            /* Reset Styles */
            body {
                margin: 0 !important;
            }

            div[style*="margin: 16px 0"] {
                margin: 0 !important;
            }
        }
    </style>
</head>
<body>
<table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0">
    <tbody>
    <tr>
        <td class="paddingLeft0 paddingRight0" align="center" style="padding:0 10px;">
            <table class="container" width="640" align="center" cellpadding="0" cellspacing="0" border="0"
                   style="width:640px;">
                <tbody>
                <tr>
                    <td align="center">
                        <!-- Background Image Container -->
                        <table class="heightAuto fullWidth" width="640" cellpadding="0" cellspacing="0" border="0"
                               style="width:640px;">
                            <tbody>
                            <tr>
                                <td valign="top" height="300"
                                    background="http://media.lt02.net/8062/Shared/Welcome/hero-bg.jpg"
                                    style="height:993px; background:#eeeeee url('https://image.freepik.com/free-photo/abstract-polygonal-space-low-poly-dark-background-3d-rendering_7247-234.jpg'); cover no-repeat center top / 100% auto;">
                                    <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                        <tbody>
                                        <tr>
                                            <td class="" align="center" valign="top" height="993" style="height:993px">
                                                <!-- Place Your Nonsense Here -->
                                                <!-- Background Image Container -->
                                                <table class="heightAuto fullWidth" width="640" cellpadding="0"
                                                       cellspacing="0" border="0" style="width:640px;">
                                                    <tbody>
                                                    <tr>
                                                        <td valign="top" height="514"
                                                            background="http://media.lt02.net/8062/Shared/Welcome/triangle.png"
                                                            style="height:514px; background:#eeeeee url('http://media.lt02.net/8062/Shared/Welcome/triangle.png') cover no-repeat center top / 100% auto;">
                                                            <!-- Containing Table -->
                                                            <table width="100%" cellpadding="0" cellspacing="0"
                                                                   border="0">
                                                                <tbody>
                                                                <tr>
                                                                    <td class="" align="center" valign="middle"
                                                                        height="300" style="height:300px">
                                                                        <!-- Place Your Nonsense Here -->
                                                                        <!-- Containing Table -->
                                                                        <table width="100%" cellpadding="0"
                                                                               cellspacing="0" border="0">
                                                                            <tbody>
                                                                            <tr>
                                                                                <td class="" align="center" valign="top"
                                                                                    height="80" style="height:80px;">
                                                                                    <!-- MSO Header END -->
                                                                                    <table width="100%" align="center"
                                                                                           cellpadding="0"
                                                                                           cellspacing="0" border="0">
                                                                                        <tbody>
                                                                                        <tr>
                                                                                            <td height="80"
                                                                                                class="copy21 inbox-overlay"
                                                                                                style="font-family:Arial, Helvetica, sans-serif; font-size:18px; color:#fff; text-align:center; font-weight:normal;line-height:18px;text-transform:uppercase;background-color: #fff;height:80px;">
                                                                                                <!-- Nav Content -->
                                                                                                <table width="100%"
                                                                                                       cellpadding="0"
                                                                                                       cellspacing="0"
                                                                                                       border="0">
                                                                                                    <tbody>
                                                                                                    <tr>
                                                                                                        <th width="20%"
                                                                                                            style="font-size:0px;">
                                                                                                            <table
                                                                                                                class="one-width"
                                                                                                                width="100%"
                                                                                                                align="left"
                                                                                                                cellpadding="0"
                                                                                                                cellspacing="0"
                                                                                                                border="0"
                                                                                                                style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;">
                                                                                                                <tbody>
                                                                                                                <tr>
                                                                                                                    <td class="contentPadding"
                                                                                                                        align="right"
                                                                                                                        style="padding:0 0 0 20px">
                                                                                                                        <!-- Logo -->
                                                                                                                        <table
                                                                                                                            width="100%"
                                                                                                                            cellpadding="0"
                                                                                                                            cellspacing="0"
                                                                                                                            border="0">
                                                                                                                            <tbody>
                                                                                                                            <tr>
                                                                                                                                <td align="center"
                                                                                                                                    style="padding:10px;">
                                                                                                                                    <a href=""
                                                                                                                                       title="Dzus"
                                                                                                                                       target="_blank"><img
                                                                                                                                            src="{{ asset('images/logo.png') }}"
                                                                                                                                            alt="Dzus"
                                                                                                                                            width="120"
                                                                                                                                            border="0"
                                                                                                                                            style="font-family:Arial, Helvetica, sans-serif;font-size:30px;color:#fff;display:block"></a>
                                                                                                                                </td>
                                                                                                                            </tr>
                                                                                                                            </tbody>
                                                                                                                        </table>
                                                                                                                        <!-- Logo END -->
                                                                                                                    </td>
                                                                                                                </tr>
                                                                                                                </tbody>
                                                                                                            </table>
                                                                                                        </th>
                                                                                                        <th width="80%"
                                                                                                            style="font-size:0px;">
                                                                                                            <table
                                                                                                                class="two-width"
                                                                                                                width="100%"
                                                                                                                align="right"
                                                                                                                cellpadding="0"
                                                                                                                cellspacing="0"
                                                                                                                border="0"
                                                                                                                style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;">
                                                                                                                <tbody>
                                                                                                                <tr>
                                                                                                                    <td class="fullWidthImg">
                                                                                                                        <!-- Navigation -->
                                                                                                                        <table
                                                                                                                            class="mobileHide"
                                                                                                                            width="100%"
                                                                                                                            cellpadding="0"
                                                                                                                            cellspacing="0"
                                                                                                                            border="0">
                                                                                                                            <tbody>
                                                                                                                            <tr>
                                                                                                                                <td align="center">
                                                                                                                                    <table
                                                                                                                                        width="100%"
                                                                                                                                        cellpadding="0"
                                                                                                                                        cellspacing="0"
                                                                                                                                        border="0">
                                                                                                                                        <tbody>
                                                                                                                                        <tr>
                                                                                                                                            <td class="PTSans"
                                                                                                                                                align="center"
                                                                                                                                                style="font-family:Arial, Helvetica, sans-serif;font-size:0px;text-align:right; padding-right:30px;">
                                                                                                                                                <a href=""
                                                                                                                                                   title="Facebook"
                                                                                                                                                   target="_blank"
                                                                                                                                                   style="font-weight: 500;font-size: 20px; color:#fff; text-decoration:none; display:inline-block; white-space:nowrap; padding:15px 10px; border:1px solid transparent;box-sizing: border-box; -moz-box-sizing:border-box; -webkit-box-sizing:border-box;">
                                                                                                                                                    <i class="fab fa-facebook-f"></i>
                                                                                                                                                    <!--[if mso]>&nbsp;&nbsp;&nbsp;&nbsp;<![endif]-->
                                                                                                                                                </a>
                                                                                                                                                <a href=""
                                                                                                                                                   title="Youtube"
                                                                                                                                                   target="_blank"
                                                                                                                                                   style="font-weight: 500;font-size: 20px; color:#fff; text-decoration:none; display:inline-block; white-space:nowrap; padding:15px 10px; border:1px solid transparent;box-sizing: border-box; -moz-box-sizing:border-box; -webkit-box-sizing:border-box;">
                                                                                                                                                    <i class="fab fa-youtube"></i>
                                                                                                                                                    <!--[if mso]>&nbsp;&nbsp;&nbsp;&nbsp;<![endif]-->
                                                                                                                                                </a>
                                                                                                                                                <a href=""
                                                                                                                                                   title="Instagram"
                                                                                                                                                   target="_blank"
                                                                                                                                                   style="font-weight: 500;font-size: 20px; color:#fff; text-decoration:none; display:inline-block; white-space:nowrap; padding:15px 10px; border:1px solid transparent;box-sizing: border-box; -moz-box-sizing:border-box; -webkit-box-sizing:border-box;">
                                                                                                                                                    <i class="fab fa-instagram"></i>
                                                                                                                                                    <!--[if mso]>&nbsp;&nbsp;&nbsp;&nbsp;<![endif]-->
                                                                                                                                                </a>
                                                                                                                                            </td>
                                                                                                                                        </tr>
                                                                                                                                        </tbody>
                                                                                                                                    </table>
                                                                                                                                </td>
                                                                                                                            </tr>
                                                                                                                            </tbody>
                                                                                                                        </table>
                                                                                                                        <!-- Navigation END -->
                                                                                                                    </td>
                                                                                                                </tr>
                                                                                                                </tbody>
                                                                                                            </table>
                                                                                                        </th>
                                                                                                    </tr>
                                                                                                    </tbody>
                                                                                                </table>
                                                                                                <!-- Nav Content END -->
                                                                                            </td>
                                                                                        </tr>
                                                                                        </tbody>
                                                                                    </table>
                                                                                    <!-- MSO Header End -->
                                                                                </td>
                                                                            </tr>
                                                                            </tbody>
                                                                        </table>
                                                                        <!-- Containing Table END-->
                                                                        <!-- Headline -->
                                                                        <table width="100%" align="center"
                                                                               cellpadding="0" cellspacing="0"
                                                                               border="0">
                                                                            <tbody>
                                                                            <tr>
                                                                                <td class="contentPadding20 copy52 PTSans paddingTop35"
                                                                                    style="font-family:Arial, Helvetica, sans-serif; font-size:71px; color:#242424; letter-spacing:.1em; text-align:center; font-weight:bold; padding:60px 0 0;text-transform:uppercase;">
                                                                                    thank you
                                                                                </td>
                                                                            </tr>
                                                                            </tbody>
                                                                        </table>
                                                                        <!-- Headline END -->
                                                                        <!-- Headline -->
                                                                        <table width="100%" align="center"
                                                                               cellpadding="0" cellspacing="0"
                                                                               border="0">
                                                                            <tbody>
                                                                            <tr>
                                                                                <td class="contentPadding20 copy16 PTSans"
                                                                                    style="font-family:Arial, Helvetica, sans-serif; font-size:22px;letter-spacing:.4em; color:#363636; text-align:center; font-weight:normal; padding:0;">
                                                                                    for choosing us
                                                                                </td>
                                                                            </tr>
                                                                            </tbody>
                                                                        </table>
                                                                        <!-- Headline END -->
                                                                        <!-- Proceed to End Placing Nonsense -->
                                                                    </td>
                                                                </tr>
                                                                </tbody>
                                                            </table>
                                                            <!-- Containing Table END-->
                                                            <!--[if gte mso 9]></div></v:shape><![endif]-->
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                                <!-- Background Image Container END -->
                                                <!-- Headline -->
                                                <table width="100%" align="center" cellpadding="0" cellspacing="0"
                                                       border="0">
                                                    <tbody>
                                                    <tr>
                                                        <td class="copy13 PTSansCaption paddingTop20"
                                                            style="font-family:Arial, Helvetica, sans-serif; font-size:22px; color:#ffffff; text-align:center; font-weight:bold; padding:74px 0 15px;text-transform:uppercase;letter-spacing:.1em;">
                                                            Mua 1 lần tải trọn đời!
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                                <!-- Headline END -->
                                                <!-- Offer -->
                                                <table width="100%" align="center" cellpadding="0" cellspacing="0"
                                                       border="0">
                                                    <tbody>
                                                    <tr>
                                                        <td class="copy73 PTSans"
                                                            style="font-family:Arial, Helvetica, sans-serif; font-size:80px; color:#ffffff; text-align:center; font-weight:bold; padding:0 0 0;text-transform:uppercase;">
                                                            FREE 100%
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                                <!-- Offer END -->
                                                <!-- Coupon Code -->
                                                <table width="100%" align="center" cellpadding="0" cellspacing="0"
                                                       border="0">
                                                    <tbody class="capTitle"
                                                           style="display: flex; justify-content: space-around">
                                                    <tr>
                                                        <td class="captionTitle copy16 PTSansCaption paddingBottom20"
                                                            style=" display:block; width:100%; font-family:Arial, Helvetica, sans-serif; font-size:17px; color:#ffffff; text-align:right; font-weight:bold; padding:15px 20px 35px 0;text-transform:uppercase;letter-spacing:.05em;">
                                                            Giá trị: <span style="color:#f18a21">{{ number_format($order->pay_money) }}Đ</span>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td class="captionTitle copy16 PTSansCaption paddingBottom20"
                                                            style="display:block; width:100%; font-family:Arial, Helvetica, sans-serif; font-size:17px; color:#ffffff; text-align:left; font-weight:bold; padding:15px 0 35px 20px;text-transform:uppercase;letter-spacing:.05em;">
                                                            Số sản phẩm: <span
                                                                style="color:#f18a21">{{ $order->products->count() }}</span>
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                                <!-- Coupon Code END -->
                                                <!-- Containing Table -->
                                                <table style="line-height: 60px" width="100%" cellpadding="0"
                                                       cellspacing="0" border="0">
                                                    <tbody>
                                                    <tr>
                                                        <td class="btn-download" align="center">
                                                            <a style="border: 2px solid #fff; background-color: rgba(0, 0, 0, 0); color: #fff; padding: 15px 40px; font-size: 17px; text-transform: uppercase; text-decoration: none; font-family:Arial, Helvetica, sans-serif; font-weight: 600;"
                                                               href="{{ route('user.order_histories', ['order' => $order->id]) }}"
                                                               target="_blank">
                                                                Tải về
                                                            </a>
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                                <!-- Containing Table END-->
                                                <!-- Proceed to End Placing Nonsense -->
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                    <!-- Containing Table END-->
                                    <!--[if gte mso 9]></div></v:shape><![endif]-->
                                </td>
                            </tr>
                            </tbody>
                        </table>

                    </td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
    </tbody>
</table>
</body>
</body>
</html>
