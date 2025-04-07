<?php
$page_title = "Bamboo Travel - Tailor-Made Asian Tours";
$css_path = "css/style.css";
$destinations_file = 'data/destinations.json';
$destinations = file_exists($destinations_file) ? json_decode(file_get_contents($destinations_file), true) : [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo $page_title; ?></title>
    <link rel="stylesheet" href="css/header.css" />
    <link rel="stylesheet" href="<?php echo $css_path; ?>" />
</head>
<body>

    <!-- Header -->
    <header class="header" id="main-header">
        <div class="header-content">
            <nav class="nav">
                <ul>
                    <li><a href="index.php">Destinations</a></li>
                    <li><a href="pages/all_tours.php">Tours</a></li>
                    <li><a href="#about">Experiences</a></li>
                    <li><a href="pages/how_to_book.php">How to Book</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <div class="slideshow">
            <div class="slide" style="background-image: url('https://images.unsplash.com/photo-1545569341-9eb8b30979d9?q=80&w=2070&auto=format&fit=crop');"></div>
            <div class="slide" style="background-image: url('https://images.unsplash.com/photo-1490077476659-095159692ab5?q=80&w=2011&auto=format&fit=crop');"></div>
            <div class="slide" style="background-image: url('https://images.unsplash.com/photo-1518546305927-5a555bb7020d?q=80&w=2069&auto=format&fit=crop');"></div>
        </div>
        <div class="hero-content">
            <h2>Tailor-Made Tours to Asia</h2>
            <p>Explore the wonders of Asia with bespoke travel experiences crafted just for you.</p>
            <a href="#destinations" class="cta-button">Discover Destinations</a>
        </div>
    </section>

    <!-- Destinations Section -->
    <section id="destinations" class="destinations-section">
        <div class="container">
            <h2>Our Destinations</h2>
            <?php if (!empty($destinations)): ?>
                <div class="destination-list">
                    <?php foreach ($destinations as $dest): ?>
                        <div class="destination-card">
                            <?php if (!empty($dest['des_image'])): ?>
                                <img src="<?php echo htmlspecialchars($dest['des_image']); ?>" alt="<?php echo htmlspecialchars($dest['des_name']); ?>" />
                            <?php endif; ?>
                            <div class="destination-info">
                                <h3>
                                    <a href="pages/tours.php?des_id=<?php echo $dest['des_id']; ?>">
                                        <?php echo htmlspecialchars($dest['des_name']); ?>
                                    </a>
                                </h3>
                                <p><?php echo htmlspecialchars($dest['des_description']); ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="no-destinations">No destinations available at this time.</p>
            <?php endif; ?>
        </div>
    </section>

    <!-- About Us Section -->
    <section id="about" class="about-section">
        <div class="container">
            <h2>About Us</h2>
            <p>At Bamboo Travel, we specialize in tailor-made holidays across Asia. Our local expertise and passion for travel allow us to create unforgettable experiences just for you.</p>
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
</html>
