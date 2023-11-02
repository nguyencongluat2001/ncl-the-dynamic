<html lang="en">

<head>
    <meta charset="utf-8" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;500&display=swap" rel="stylesheet" />
    <style>
        h5 {
            font-size: 20px;
        }

        .row {
            width: 100%;
            display: inline-block;
        }

        .text-center {
            text-align: center;
        }

        .title {
            line-height: 45%;
        }

        .title-left {
            width: 40%;
            float: left;
            text-align: center;
        }

        .title-right {
            width: 60%;
            float: right;
            text-align: center;
        }

        .decoration {
            text-decoration: underline;
        }
    </style>
</head>

<body style="font-family: 'Roboto', sans-serif">
    <div>
        <div class="row">
            <div class="info-conter">
                <p>Tài khoản đã được cấp lại mật khẩu!</p>
                <p>Mật khẩu hiện tại là: <b>{{ $data['password'] }}</b></p>
                <p>Quý khách có thể tiến hành đăng nhập với đường dẫn sau: https://thitructuyen.haiduong/dang-nhap</p>
                <p>Thông báo này được gửi tự động từ Hội thi trực truyến tìm hiểu về cải cách hành chính tỉnh Hải Dương, vui lòng không phản hồi lại email này.</p>
                <p>Trân trọng cảm ơn!</p>
            </div>
        </div>
    </div>
</body>

</html>
