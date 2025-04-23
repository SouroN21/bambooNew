<?php
$destinations_file = '../data/destinations.json';
$tours_file = '../data/tours.json';

$destinations = file_exists($destinations_file) ? json_decode(file_get_contents($destinations_file), true) : [];
$tours = file_exists($tours_file) ? json_decode(file_get_contents($tours_file), true) : [];

$des_id = isset($_GET['des_id']) ? (int)$_GET['des_id'] : 0;
$country = $_GET['country'] ?? '';

if ($des_id) {
    $destination = reset(array_filter($destinations, fn($d) => $d['des_id'] === $des_id)) ?: null;
} else {
    $destination = null;
}

if ($des_id) {
    $filtered_tours = array_filter($tours, fn($t) => $t['des_id'] === $des_id);
} elseif ($country) {
    $country_destinations = array_filter($destinations, fn($d) => str_ends_with($d['des_name'], $country));
    $des_ids = array_column($country_destinations, 'des_id');
    $filtered_tours = array_filter($tours, fn($t) => in_array($t['des_id'], $des_ids));
} else {
    $filtered_tours = $tours;
}

$page_title = $country ? "Tours in " . htmlspecialchars($country) :
             ($destination ? "Tours for " . htmlspecialchars($destination['des_name']) : "All Tours");
$css_path = "../css/tours.css";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $page_title; ?> - Bamboo Travel</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="<?php echo $css_path; ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
    <?php require_once '../components/header.php'; ?>

    <section class="tour-section">
        <div class="container">
            <header class="tour-header">
                <?php if ($destination && !empty($destination['des_image'])): ?>
                    <img src="<?php echo htmlspecialchars($destination['des_image']); ?>" alt="<?php echo htmlspecialchars($destination['des_name']); ?>" class="header-image">
                <?php endif; ?>
                <h1><i class="fas fa-map-marked-alt"></i> <?php echo $page_title; ?></h1>
            </header>

            <a href="../index.php" class="back-link"><i class="fas fa-arrow-left"></i> Back to Destinations</a>

            <div class="tour-content">
                <?php if (!empty($filtered_tours)): ?>
                    <div class="tour-list">
                        <?php foreach ($filtered_tours as $tour): ?>
                            <a href="tour_details.php?tour_id=<?php echo $tour['tour_id']; ?>" class="tour-link">
                                <article class="tour-card">
                                    <?php if (!empty($tour['tour_image'])): ?>
                                        <img src="<?php echo htmlspecialchars($tour['tour_image']); ?>" alt="<?php echo htmlspecialchars($tour['tour_name']); ?>" class="tour-image">
                                    <?php endif; ?>
                                    <h2><i class="fas fa-suitcase-rolling"></i> <?php echo htmlspecialchars($tour['tour_name']); ?></h2>
                                    <p><strong><i class="fas fa-map-pin"></i> Destination:</strong>
                                        <?php
                                            $dest = reset(array_filter($destinations, fn($d) => $d['des_id'] === $tour['des_id']));
                                            echo htmlspecialchars($dest['des_name'] ?? 'Unknown');
                                        ?>
                                    </p>
                                    <p><strong><i class="fas fa-dollar-sign"></i> Guide Price:</strong> $<?php echo number_format($tour['tour_guidPrice'], 2); ?></p>
                                    <p><strong><i class="fas fa-calendar-day"></i> No of Days:</strong> <?php echo count($tour['DaybyDay']); ?></p>
                                    <p><strong><i class="fas fa-star"></i> Highlights:</strong></p>
                                    <ul>
                                        <?php foreach ($tour['tour_highlights'] as $highlight): ?>
                                            <li><i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($highlight); ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </article>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="no-tours"><i class="fas fa-info-circle"></i> No tours available.</p>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <?php require_once '../components/footer.php'; ?>
</body>
</html>
