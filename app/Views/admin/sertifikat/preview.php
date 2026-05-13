<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Certificate Preview</title>
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <style>
        .cert-container {
            width: 1123px; /* A4 Landscape at 96dpi */
            height: 794px;
            padding: 50px;
            box-sizing: border-box;
            background: #fdfdfd;
            border: 20px solid #0369A1;
            position: relative;
            text-align: center;
            font-family: 'Lato', sans-serif;
            margin: 20px auto;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        .cert-header {
            font-family: 'Playfair Display', serif;
            font-size: 80px;
            color: #0369A1;
            margin-top: 50px;
            text-transform: uppercase;
        }
        .cert-sub {
            font-size: 24px;
            letter-spacing: 5px;
            margin-top: 10px;
            opacity: 0.8;
        }
        .cert-recipient {
            font-family: 'Playfair Display', serif;
            font-size: 56px;
            color: #1e293b;
            margin: 40px 0;
            border-bottom: 2px solid #cbd5e1;
            display: inline-block;
            padding: 0 50px;
        }
        .cert-text {
            font-size: 20px;
            line-height: 1.6;
            max-width: 800px;
            margin: 0 auto;
        }
        .cert-footer {
            position: absolute;
            bottom: 80px;
            width: 100%;
            left: 0;
            display: flex;
            justify-content: space-around;
            padding: 0 100px;
            box-sizing: border-box;
        }
        .sign-box {
            width: 250px;
            border-top: 1px solid #1e293b;
            padding-top: 10px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="cert-container">
        <div class="cert-header">CERTIFICATE</div>
        <div class="cert-sub">OF APPRECIATION</div>
        
        <p class="cert-text" style="margin-top: 60px;">This is to certify that</p>
        <div class="cert-recipient"><?= $name ?></div>
        
        <p class="cert-text">has successfully completed the training course</p>
        <p class="cert-text" style="font-weight: bold; font-size: 24px; margin-top: 10px;"><?= $course ?></p>
        
        <p class="cert-text" style="margin-top: 30px;">on <?= $date ?></p>
        
        <div class="cert-footer">
            <div class="sign-box">
                <p>Titan Mulyanto</p>
                <p style="font-size: 14px; font-weight: normal; opacity: 0.6;">Founder ZevedU Academy</p>
            </div>
            <div style="width: 150px;">
                <!-- Space for Stamp -->
                <div style="width: 100px; height: 100px; border: 2px dashed #cbd5e1; border-radius: 50%; margin: 0 auto; display: flex; items-center; justify-center; font-size: 10px; color: #cbd5e1;">STAMP</div>
            </div>
            <div class="sign-box">
                <p>Course Instructor</p>
                <p style="font-size: 14px; font-weight: normal; opacity: 0.6;">Senior Mentor</p>
            </div>
        </div>
    </div>
</body>
</html>
