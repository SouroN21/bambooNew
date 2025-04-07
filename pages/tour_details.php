<?php
// Load JSON data
$destinations_file = '../data/destinations.json';
$tours_file = '../data/tours.json';

$destinations = file_exists($destinations_file) ? json_decode(file_get_contents($destinations_file), true) : [];
$tours = file_exists($tours_file) ? json_decode(file_get_contents($tours_file), true) : [];

// Get tour_id from URL
$tour_id = isset($_GET['tour_id']) ? (int)$_GET['tour_id'] : 0;

// Fetch tour details
$tour = array_filter($tours, fn($t) => $t['tour_id'] === $tour_id);
$tour = reset($tour) ?: null;

// Fetch destination details
$destination = $tour ? array_filter($destinations, fn($d) => $d['des_id'] === $tour['des_id']) : [];
$destination = $destination ? reset($destination) : null;

$page_title = $tour ? htmlspecialchars($tour['tour_name']) . " - Bamboo Travel" : "Tour Not Found - Bamboo Travel";
$css_path = "../css/tour_details.css";
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

<section class="tour-details-section">
    <div class="container">
        <?php if ($tour): ?>
            <!-- Tour Header -->
            <header class="tour-header">
                <?php if (!empty($tour['tour_image'])): ?>
                    <img src="<?php echo htmlspecialchars($tour['tour_image']); ?>" alt="<?php echo htmlspecialchars($tour['tour_name']); ?>" class="tour-image">
                <?php endif; ?>
                <h1><?php echo htmlspecialchars($tour['tour_name']); ?></h1>
            </header>

            <!-- Back Link -->
            <a href="tours.php?des_id=<?php echo $tour['des_id']; ?>" class="back-link">&larr; Back to Tours</a>

            <!-- Tour Details -->
            <div class="tour-details">
                <p><strong>Destination:</strong> <?php echo $destination ? htmlspecialchars($destination['des_name']) : 'Unknown'; ?></p>
                <p><strong>Description:</strong> <?php echo htmlspecialchars($tour['tour_description']); ?></p>
                <p><strong>Guide Price:</strong> $<?php echo number_format($tour['tour_guidPrice'], 2); ?></p>
                <p><strong>No of Days:</strong> <?php echo count($tour['DaybyDay']); ?></p>
                <p><strong>Airline:</strong> <?php echo htmlspecialchars($tour['tour_airLine']); ?></p>
                <p><strong>Highlights:</strong></p>
                <ul>
                    <?php foreach ($tour['tour_highlights'] as $highlight): ?>
                        <li><?php echo htmlspecialchars($highlight); ?></li>
                    <?php endforeach; ?>
                </ul>
                <p><strong>Suitable For:</strong></p>
                <ul>
                    <?php foreach ($tour['tour_suitablefor'] as $suitable): ?>
                        <li><?php echo htmlspecialchars($suitable); ?></li>
                    <?php endforeach; ?>
                </ul>

                <h2 class="itinerary-heading">Day by Day Itinerary</h2>
                <div class="itinerary-container">
                    <?php foreach ($tour['DaybyDay'] as $index => $day): ?>
                        <div class="itinerary-day">
                            <button class="itinerary-toggle" onclick="toggleItinerary(<?php echo $index; ?>)">
                                <span class="day-title">Day <?php echo $index + 1; ?>: <?php echo htmlspecialchars($day['title']); ?></span>
                                <span class="toggle-icon" id="icon-<?php echo $index; ?>">&#x25BC;</span>
                            </button>
                            <div class="itinerary-details" id="details-<?php echo $index; ?>">
                                <p><strong>Accommodation:</strong> <?php echo htmlspecialchars($day['accommodation']); ?></p>
                                <p><strong>Meals:</strong> <?php echo htmlspecialchars($day['meals']); ?></p>
                                <p><strong>Transport:</strong> <?php echo htmlspecialchars($day['transport']); ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <a href="enquiry.php?tour_id=<?php echo $tour['tour_id']; ?>" class="enquiry-button">Request Enquiry</a>
            </div>
        <?php else: ?>
            <h1>Tour Not Found</h1>
            <p class="no-tours">The requested tour could not be found.</p>
            <a href="../index.php" class="back-link">Back to Home</a>
        <?php endif; ?>
    </div>
</section>

<?php require_once '../components/footer.php'; ?>

<script>
function toggleItinerary(index) {
    const details = document.getElementById(`details-${index}`);
    const icon = document.getElementById(`icon-${index}`);
    details.classList.toggle('show');
    icon.textContent = details.classList.contains('show') ? '\u25B2' : '\u25BC';
}
</script>
</body>
</html>
