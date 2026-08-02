<?php
session_start();

if(!isset($_SESSION['phone'])){
    header("location:index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="hi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP | Life Replay AI</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@600;800&family=Plus+Jakarta+Sans:wght@500;700&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #060713;
            color: #FFF;
            font-family: 'Plus Jakarta Sans', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            padding: 20px;
        }
        .card {
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 24px;
            padding: 32px 24px;
            text-align: center;
            width: 100%;
            max-width: 360px;
            backdrop-filter: blur(20px);
        }
        h2 { font-family: 'Outfit', sans-serif; margin-bottom: 8px; font-size: 24px; }
        p { color: #94A3B8; font-size: 14px; margin-bottom: 20px; }
        input[type="number"] {
            width: 100%;
            padding: 14px;
            background: rgba(255,255,255,0.06);
            border: 1px solid rgba(255,255,255,0.15);
            border-radius: 12px;
            color: #FFF;
            font-size: 22px;
            text-align: center;
            letter-spacing: 8px;
            outline: none;
            box-sizing: border-box;
            margin-bottom: 16px;
        }
        button {
            width: 100%;
            padding: 14px;
            border: none;
            border-radius: 12px;
            background: linear-gradient(135deg, #6366F1, #A855F7, #EC4899);
            color: #FFF;
            font-weight: 700;
            font-size: 16px;
            cursor: pointer;
        }
        .msg { color: #FF4D4D; margin-bottom: 12px; font-size: 13px; font-weight: 600; }
        a { color: #00E5FF; text-decoration: none; font-size: 12px; display: inline-block; margin-top: 14px; }
    </style>
</head>
<body>
    <div class="card">
        <h2>Enter OTP</h2>
        <p>Sent via SMS to: <strong>+91 <?php echo htmlspecialchars($_SESSION['phone']); ?></strong></p>

        <?php if(isset($_GET['msg'])){ echo "<div class='msg'>".htmlspecialchars($_GET['msg'])."</div>"; } ?>

        <form action="Verify-otp.php" method="POST">
            <input type="number" name="otp" placeholder="XXXX" required oninput="if(this.value.length > 4) this.value = this.value.slice(0, 4);">
            <button type="submit">Verify & Login</button>
        </form>
        
        <a href="index.php">Change Phone Number?</a>
    </div>
</body>
</html>