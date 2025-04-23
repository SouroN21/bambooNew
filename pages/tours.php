<?php
// Load JSON data
$destinations_file = '../data/destinations.json';
$tours_file = '../data/tours.json';

$destinations = file_exists($destinations_file) ? json_decode(file_get_contents($destinations_file), true) : [];
$tours = file_exists($tours_file) ? json_decode(file_get_contents($tours_file), true) : [];

// Get des_id or country from URL
$des_id = isset($_GET['des_id']) ? (int)$_GET['des_id'] : 0;
$country = isset($_GET['country']) ? $_GET['country'] : '';

// Fetch destination details
if ($des_id) {
    $destination = array_filter($destinations, fn($d) => $d['des_id'] === $des_id);
    $destination = reset($destination) ?: null;
} elseif ($country) {
    $destination = null; // Country filter doesn't focus on a single destination
} else {
    $destination = null;
}

// Fetch tours for this destination or country
if ($des_id) {
    $filtered_tours = array_filter($tours, fn($t) => $t['des_id'] === $des_id);
} elseif ($country) {
    $country_destinations = array_filter($destinations, fn($d) => str_ends_with($d['des_name'], $country));
    $country_des_ids = array_column($country_destinations, 'des_id');
    $filtered_tours = array_filter($tours, fn($t) => in_array($t['des_id'], $country_des_ids));
} else {
    $filtered_tours = $tours;
}

$page_title = $country ? "Tours in " . htmlspecialchars($country) . " - Bamboo Travel" :
             ($destination ? "Tours for " . htmlspecialchars($destination['des_name']) . " - Bamboo Travel" : "All Tours - Bamboo Travel");
$css_path = "../css/tours.css";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="<?php echo $css_path; ?>">
</head>
<body>
    <?php require_once '../components/header.php'; ?>

    <section class="tour-section">
        <div class="container">
            <!-- Header with destination or country -->
            <header class="tour-header">
                <?php if ($destination && !empty($destination['des_image'])): ?>
                    <img src="<?php echo htmlspecialchars($destination['des_image']); ?>" alt="<?php echo htmlspecialchars($destination['des_name']); ?>" class="header-image">
                <?php endif; ?>
                <h1><?php echo $country ? "Tours in " . htmlspecialchars($country) : ($destination ? "Tours for " . htmlspecialchars($destination['des_name']) : "All Tours"); ?></h1>
            </header>

            <!-- Back link -->
            <a href="../index.php" class="back-link">Back to Destinations</a>

            <!-- Main content -->
            <div class="tour-content">
                <?php if (!empty($filtered_tours)): ?>
                    <div class="tour-list">
                        <?php foreach ($filtered_tours as $tour): ?>
                            <a href="tour_details.php?tour_id=<?php echo $tour['tour_id']; ?>" class="tour-link">
                                <article class="tour-card">
                                    <?php if (!empty($tour['tour_image'])): ?>
                                        <img src="<?php echo htmlspecialchars($tour['tour_image']); ?>" alt="<?php echo htmlspecialchars($tour['tour_name']); ?>" class="tour-image">
                                    <?php endif; ?>
                                    <h2><?php echo htmlspecialchars($tour['tour_name']); ?></h2>
                                    <p><strong>Destination:</strong>
                                        <?php
                                            $dest = array_filter($destinations, fn($d) => $d['des_id'] === $tour['des_id']);
                                            echo htmlspecialchars(reset($dest)['des_name'] ?? 'Unknown');
                                        ?>
                                    </p>
                                    <p><strong>Guide Price:</strong> $<?php echo number_format($tour['tour_guidPrice'], 2); ?></p>
                                    <p><strong>No of Days:</strong> <?php echo count($tour['DaybyDay']); ?></p>
                                    <p><strong>Highlights:</strong></p>
                                    <ul>
                                        <?php foreach ($tour['tour_highlights'] as $highlight): ?>
                                            <li><?php echo htmlspecialchars($highlight); ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </article>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="no-tours">No tours available.</p>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <?php require_once '../components/footer.php'; ?>
</body>
</html>