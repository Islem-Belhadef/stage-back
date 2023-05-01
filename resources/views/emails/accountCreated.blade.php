<html lang="en">
<head>
    <title>uc2 stage</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&family=Nunito:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;0,1000;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900;1,1000&display=swap"
        rel="stylesheet">
</head>

<body style="font-family: 'Inter', sans-serif;font-size: 0.95rem;">
<div class="container">
    <div class="title" style="display: flex;gap: 20px;align-items: center;font-family: 'Nunito',sans-serif;font-weight: 600;color: #272937;">
        <img src="{{ URL::to('logo.svg') }}" alt="Logo" height="40" width="40"/>
        <h2>Supervisor account</h2>
    </div>
    <div class="content">
        <p style="color: #616373;">Your supervisor account on uc2.stage.dz was created successfully</p>
        <p style="color: #616373;">
            Here are the login information you can use to access you account on
            our website
        </p>
        <p style="color: #616373;">
            Email address :
            <strong class="address" style="color: #272937;">{{$address}}</strong>
        </p>
        <p style="color: #616373;">Password : <strong class="password" style="color: #383ebe;">{{$password}}</strong></p>
    </div>
</div>
</body>
</html>
