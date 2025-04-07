<?php
$page_title = "How to Book - Bamboo Travel";
$css_path = "../css/how_to_book.css";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo $page_title; ?></title>
    <link rel="stylesheet" href="../css/header.css" />
    <link rel="stylesheet" href="<?php echo $css_path; ?>" />
</head>
<body>
<?php require_once '../components/header.php'; ?>


    <!-- How to Book Section -->
    <section class="how-to-book-section">
        <div class="container">
            <h1>How to Book Your Tour</h1>
            <p class="intro-text">Follow these simple steps to plan your perfect Asian adventure with Bamboo Travel.</p>
            
            <div class="steps-grid">
                <!-- Step 1 -->
                <div class="step-block">
                <img src="https://images.unsplash.com/photo-1556741533-6e6a62bd8b49?q=80&w=175&auto=format&fit=crop" alt="Step 2" class="step-image">
                <div class="step-content">
                        <h2>Step 1</h2>
                        <p>Please complete the <a href="all_tours.php">enquiry form</a> and press 'send enquiry'. Alternatively, if you would prefer to speak with one of our Asia experts, please call <strong>020 7720 9285</strong> and we'll take your details by phone.</p>
                    </div>
                </div>

                <!-- Step 2 -->
                <div class="step-block">
                    <img src="https://images.unsplash.com/photo-1556741533-6e6a62bd8b49?q=80&w=175&auto=format&fit=crop" alt="Step 2" class="step-image">
                    <div class="step-content">
                        <h2>Step 2</h2>
                        <p>We will then send you a quotation for your chosen day of departure, along with our booking form and guidance on the payment requirements.</p>
                    </div>
                </div>

                <!-- Step 3 -->
                <div class="step-block">
                    <img src="https://images.unsplash.com/photo-1506748686214-e9df14d4d9d0?q=80&w=175&auto=format&fit=crop" alt="Step 3" class="step-image">
                    <div class="step-content">
                        <h2>Step 3</h2>
                        <p>Upon receipt of your payment, we will request your holiday, and once confirmed, send you a detailed invoice. Your holiday is then booked.</p>
                    </div>
                </div>
            </div>

            <a href="../index.php" class="back-link">Back to Home</a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <p>© <?php echo date('Y'); ?> Bamboo Travel. All rights reserved.</p>
            <p>Email: <a href="mailto:info@bambootravel.co.uk">info@bambootravel.co.uk</a> | Phone: 020 7720 9285</p>
        </div>
    </footer>

</body>
<script>
    document.getElementById('menu-toggle').addEventListener('click', function () {
        const navMenu = document.getElementById('nav-menu');
        navMenu.classList.toggle('active');
    });
</script>

</html>
