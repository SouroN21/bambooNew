<?php
// Load JSON data
$destinations_file = '../data/destinations.json';
$tours_file = '../data/tours.json';

$destinations = file_exists($destinations_file) ? json_decode(file_get_contents($destinations_file), true) : [];
$tours = file_exists($tours_file) ? json_decode(file_get_contents($tours_file), true) : [];

// Get des_id from URL
$des_id = isset($_GET['des_id']) ? (int)$_GET['des_id'] : 0;

// Fetch destination details
$destination = array_filter($destinations, fn($d) => $d['des_id'] === $des_id);
$destination = reset($destination) ?: null;

// Fetch tours for this destination
$filtered_tours = array_filter($tours, fn($t) => $t['des_id'] === $des_id);

$page_title = "Tours for " . ($destination ? htmlspecialchars($destination['des_name']) : 'Unknown') . " - Bamboo Travel";
$css_path = "../css/tours.css";  // Switch to tours.css for consistency
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
            <!-- Header with destination image -->
            <header class="tour-header">
                <?php if ($destination && !empty($destination['des_image'])): ?>
                    <img src="../<?php echo htmlspecialchars($destination['des_image']); ?>" alt="<?php echo htmlspecialchars($destination['des_name']); ?>" class="header-image">
                <?php endif; ?>
                <h1>Tours for <?php echo $destination ? htmlspecialchars($destination['des_name']) : 'Unknown Destination'; ?></h1>
            </header>

            <!-- Back link -->
            <a href="../index.php" class="back-link">Back to Destinations</a>

            <!-- Main content -->
            <div class="tour-content">
                <?php if ($destination): ?>
                    <?php if (!empty($filtered_tours)): ?>
                        <div class="tour-list">
                            <?php foreach ($filtered_tours as $tour): ?>
                                <a href="tour_details.php?tour_id=<?php echo $tour['tour_id']; ?>" class="tour-link">
                                    <article class="tour-card">
                                        <?php if (!empty($tour['tour_image'])): ?>
                                            <img src="<?php echo htmlspecialchars($tour['tour_image']); ?>" alt="<?php echo htmlspecialchars($tour['tour_name']); ?>" class="tour-image">
                                        <?php endif; ?>
                                        <h2><?php echo htmlspecialchars($tour['tour_name']); ?></h2>
                                        <p><strong>Destination:</strong> <?php echo $destination ? htmlspecialchars($destination['des_name']) : 'Unknown'; ?></p>
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
                        <p class="no-tours">No tours available for this destination.</p>
                    <?php endif; ?>
                <?php else: ?>
                    <p class="no-tours">Destination not found.</p>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <?php require_once '../components/footer.php'; ?>
</body>
</html>