<?php
// Load JSON data
$destinations_file = '../data/destinations.json';
$tours_file = '../data/tours.json';

if (!file_exists($destinations_file)) {
    die("Error: destinations.json not found at $destinations_file");
}

$destinations = json_decode(file_get_contents($destinations_file), true);
if (json_last_error() !== JSON_ERROR_NONE) {
    die("Error: Invalid JSON in destinations.json - " . json_last_error_msg());
}
$destinations = $destinations ?: [];

$tours = file_exists($tours_file) ? json_decode(file_get_contents($tours_file), true) : [];

// Extract country from des_name (e.g., "Kyoto, Japan" -> "Japan")
foreach ($destinations as &$dest) {
    $parts = explode(', ', $dest['des_name']);
    $dest['country'] = trim(end($parts)); // Get the last part as country
}

// Extract unique countries
$countries = array_unique(array_column($destinations, 'country'));

$page_title = "All Countries - Bamboo Travel";
$css_path = "../css/countries.css";
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

    <section class="countries-section">
        <div class="container">
            <header class="countries-header">
                <h1>All Countries</h1>
            </header>
            <a href="../index.php" class="back-link">Back to Home</a>

            <div class="countries-content">
                <?php if (!empty($countries)): ?>
                    <div class="countries-list">
                        <?php foreach ($countries as $country): ?>
                            <article class="country-card">
                                <h2><?php echo htmlspecialchars($country); ?></h2>
                                <p><strong>Destinations:</strong>
                                    <?php
                                        $country_destinations = array_filter($destinations, fn($d) => $d['country'] === $country);
                                        $dest_names = array_column($country_destinations, 'des_name');
                                        echo htmlspecialchars(implode(', ', $dest_names));
                                    ?>
                                </p>
                                <p><strong>Number of Tours:</strong>
                                    <?php
                                        $country_tours = array_filter($tours, fn($t) => in_array($t['des_id'], array_column($country_destinations, 'des_id')));
                                        echo count($country_tours);
                                    ?>
                                </p>
                                <a href="tours.php?country=<?php echo urlencode($country); ?>" class="view-tours-button">View Tours</a>
                            </article>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="no-countries">No countries found.</p>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <?php require_once '../components/footer.php'; ?>
</body>
</html>