<html lang="en">
<head>
    <title>uc2 stage</title>
    <link rel="stylesheet" href="../../css/app.css">
</head>

<body style="font-size: 0.95rem;">
<div class="container">
    <div class="title" style="display: flex;gap: 20px;align-items: center;font-weight: 600;color: #272937;">
        <img src="/public/assets/logo.svg" alt="Logo" height="40" width="40"/>
        <h2>Supervisor account</h2>
    </div>
    <div class="content">
        <p style="color: #616373;">Your {{$type}} account on uc2.stage.dz was created successfully</p>
        <p style="color: #616373;">
            Here are the login information you can use to access you account on
            our website
        </p>
        <p style="color: #616373;">
            Email address :
            <strong class="address" style="color: #272937; font-size: 1.3rem">{{$address}}</strong>
        </p>
        <p style="color: #616373;">Password : <strong class="password" style="color: #383ebe; font-size: 1.3rem">{{$password}}</strong></p>
    </div>
</div>
</body>
</html>
