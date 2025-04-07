<?php
// Load JSON data
$destinations_file = '../data/destinations.json';
$tours_file = '../data/tours.json';

$destinations = file_exists($destinations_file) ? json_decode(file_get_contents($destinations_file), true) : [];
$tours = file_exists($tours_file) ? json_decode(file_get_contents($tours_file), true) : [];

// Filters
$filter_des_id = isset($_GET['des_id']) ? (int)$_GET['des_id'] : '';
$filter_min_price = isset($_GET['min_price']) ? (float)$_GET['min_price'] : '';
$filter_max_price = isset($_GET['max_price']) ? (float)$_GET['max_price'] : '';
$filter_suitable = isset($_GET['suitable']) ? $_GET['suitable'] : '';

// Apply filters
$filtered_tours = array_filter($tours, function ($t) use ($filter_des_id, $filter_min_price, $filter_max_price, $filter_suitable) {
    return (!$filter_des_id || $t['des_id'] === $filter_des_id) &&
           ($filter_min_price === '' || $t['tour_guidPrice'] >= $filter_min_price) &&
           ($filter_max_price === '' || $t['tour_guidPrice'] <= $filter_max_price) &&
           (!$filter_suitable || in_array($filter_suitable, $t['tour_suitablefor'], true));
});

$page_title = "All Tours - Bamboo Travel";
$css_path = "../css/all_tours.css";
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

    <!-- Filter Section -->
    <section class="filter-section">
        <div class="container">
            <h2>Filter Tours</h2>
            <form method="GET" class="filter-form">
                <div class="form-group">
                    <label for="des_id">Destination</label>
                    <select id="des_id" name="des_id">
                        <option value="">All Destinations</option>
                        <?php foreach ($destinations as $dest): ?>
                            <option value="<?php echo $dest['des_id']; ?>" <?php echo ($filter_des_id === $dest['des_id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($dest['des_name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="min_price">Min Price ($)</label>
                    <input type="number" id="min_price" name="min_price" step="0.01" value="<?php echo $filter_min_price; ?>" placeholder="e.g. 500">
                </div>
                <div class="form-group">
                    <label for="max_price">Max Price ($)</label>
                    <input type="number" id="max_price" name="max_price" step="0.01" value="<?php echo $filter_max_price; ?>" placeholder="e.g. 2000">
                </div>
                <div class="form-group">
                    <label for="suitable">Suitable For</label>
                    <input type="text" id="suitable" name="suitable" value="<?php echo htmlspecialchars($filter_suitable); ?>" placeholder="e.g. Families">
                </div>
                <button type="submit" class="submit-button">Apply Filters</button>
            </form>
        </div>
    </section>

    <!-- Tours Section -->
    <section class="tour-content">
        <div class="container">
            <h1>All Tours</h1>
            <a href="../index.php" class="back-link">← Back to Destinations</a>
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
                                <p><strong>Description:</strong> <?php echo htmlspecialchars($tour['tour_description']); ?></p>
                                <p><strong>Highlights:</strong> <?php echo htmlspecialchars(implode(', ', $tour['tour_highlights'])); ?></p>
                                <p><strong>Suitable For:</strong> <?php echo htmlspecialchars(implode(', ', $tour['tour_suitablefor'])); ?></p>
                                <p><strong>Guide Price:</strong> $<?php echo number_format($tour['tour_guidPrice'], 2); ?></p>
                                <p><strong>No of Days:</strong> <?php echo count($tour['DaybyDay']); ?></p>
                                <p><strong>Airline:</strong> <?php echo htmlspecialchars($tour['tour_airLine']); ?></p>
                            </article>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="no-tours">No tours match your filters.</p>
            <?php endif; ?>
        </div>
    </section>

    <?php require_once '../components/footer.php'; ?>
</body>
</html>