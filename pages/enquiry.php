<?php
// Load JSON data
$tours_file = '../data/tours.json';
$enquiries_file = '../data/enquiries.json';

$tours = file_exists($tours_file) ? json_decode(file_get_contents($tours_file), true) : [];
$enquiries = file_exists($enquiries_file) ? json_decode(file_get_contents($enquiries_file), true) : [];

// Get tour_id from URL
$tour_id = isset($_GET['tour_id']) ? (int)$_GET['tour_id'] : 0;

// Fetch tour details
$tour = array_filter($tours, fn($t) => $t['tour_id'] === $tour_id);
$tour = reset($tour) ?: null;
$tour_name = $tour ? $tour['tour_name'] : 'Unknown Tour';
$des_id = $tour ? $tour['des_id'] : 0;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $enq_modifications = $_POST['enq_modifications'] ?? '';
    $enq_dateOfDeparture = $_POST['enq_dateOfDeparture'] ?? '';
    $enq_tripDuration = $_POST['enq_tripDuration'] ?? '';
    $enq_noOfAdults = $_POST['enq_noOfAdults'] ?? '';
    $enq_noOfChild = $_POST['enq_noOfChild'] ?? '';
    $enq_bugetPerPerson = $_POST['enq_bugetPerPerson'] ?? '';
    $enq_standardHotel = $_POST['enq_standardHotel'] ?? '';
    $enq_noOfRooms = $_POST['enq_noOfRooms'] ?? '';
    $enq_departureAirport = $_POST['enq_departureAirport'] ?? '';
    $enq_flightClass = $_POST['enq_flightClass'] ?? '';
    $enq_interests = $_POST['enq_interests'] ?? '';

    $max_id = !empty($enquiries) ? max(array_column($enquiries, 'enq_id')) : 0;
    $new_enquiry = [
        'enq_id' => $max_id + 1,
        'tour_id' => $tour_id,
        'tourname' => $tour_name,
        'enq_modifications' => $enq_modifications,
        'enq_dateOfDeparture' => $enq_dateOfDeparture,
        'enq_tripDuration' => (int)$enq_tripDuration,
        'enq_noOfAdults' => (int)$enq_noOfAdults,
        'enq_noOfChild' => (int)$enq_noOfChild,
        'enq_bugetPerPerson' => (float)$enq_bugetPerPerson,
        'enq_standardHotel' => $enq_standardHotel,
        'enq_noOfRooms' => (int)$enq_noOfRooms,
        'enq_departureAirport' => $enq_departureAirport,
        'enq_flightClass' => $enq_flightClass,
        'enq_interests' => $enq_interests
    ];

    $enquiries[] = $new_enquiry;
    file_put_contents($enquiries_file, json_encode($enquiries, JSON_PRETTY_PRINT));
    $success = "Your enquiry has been submitted successfully!";
}

$page_title = "Request Enquiry - Bamboo Travel";
$css_path = "../css/enquiry.css";
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

    <section class="enquiry-section">
        <div class="container">
            <header class="tour-header">
                <h1>Request Enquiry for <?php echo htmlspecialchars($tour_name); ?></h1>
            </header>

            <a href="tour_details.php?tour_id=<?php echo $tour_id; ?>" class="back-link">Back to Tour Details</a>

            <div class="enquiry-content">
                <?php if (isset($success)): ?>
                    <p class="success-message"><?php echo $success; ?></p>
                <?php endif; ?>

                <form method="POST" class="enquiry-form">
                    <div class="form-group">
                        <label for="tour_name">Tour Name</label>
                        <input type="text" id="tour_name" value="<?php echo htmlspecialchars($tour_name); ?>" disabled>
                    </div>

                    <div class="form-group">
                        <label for="enq_modifications">Modifications</label>
                        <textarea id="enq_modifications" name="enq_modifications" placeholder="Any specific requests or modifications"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="enq_dateOfDeparture">Date of Departure</label>
                        <input type="date" id="enq_dateOfDeparture" name="enq_dateOfDeparture">
                    </div>

                    <div class="form-group">
                        <label for="enq_tripDuration">Trip Duration (days)</label>
                        <input type="number" id="enq_tripDuration" name="enq_tripDuration" min="1" placeholder="e.g., 7">
                    </div>

                    <div class="form-group">
                        <label for="enq_noOfAdults">Number of Adults</label>
                        <input type="number" id="enq_noOfAdults" name="enq_noOfAdults" min="1" placeholder="e.g., 2">
                    </div>

                    <div class="form-group">
                        <label for="enq_noOfChild">Number of Children</label>
                        <input type="number" id="enq_noOfChild" name="enq_noOfChild" min="0" placeholder="e.g., 1">
                    </div>

                    <div class="form-group">
                        <label for="enq_bugetPerPerson">Budget Per Person ($)</label>
                        <input type="number" id="enq_bugetPerPerson" name="enq_bugetPerPerson" step="0.01" min="0" placeholder="e.g., 1500">
                    </div>

                    <div class="form-group">
                        <label for="enq_standardHotel">Hotel Standard</label>
                        <input type="text" id="enq_standardHotel" name="enq_standardHotel" placeholder="e.g., 4-star">
                    </div>

                    <div class="form-group">
                        <label for="enq_noOfRooms">Number of Rooms</label>
                        <input type="number" id="enq_noOfRooms" name="enq_noOfRooms" min="1" placeholder="e.g., 1">
                    </div>

                    <div class="form-group">
                        <label for="enq_departureAirport">Departure Airport</label>
                        <input type="text" id="enq_departureAirport" name="enq_departureAirport" placeholder="e.g., London Heathrow">
                    </div>

                    <div class="form-group">
                        <label for="enq_flightClass">Flight Class</label>
                        <select id="enq_flightClass" name="enq_flightClass">
                            <option value="Economy">Economy</option>
                            <option value="Premium Economy">Premium Economy</option>
                            <option value="Business">Business</option>
                            <option value="First">First</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="enq_interests">Interests</label>
                        <textarea id="enq_interests" name="enq_interests" placeholder="e.g., Culture, Adventure, Relaxation"></textarea>
                    </div>

                    <button type="submit" class="submit-button">Submit Enquiry</button>
                </form>
            </div>
        </div>
    </section>

    <?php require_once '../components/footer.php'; ?>
</body>
</html>