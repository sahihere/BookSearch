<?php
if (isset($_GET['book_name'])) {
    $bookName = urlencode($_GET['book_name']);
    $apiUrl = "https://www.googleapis.com/books/v1/volumes?q={$bookName}&langRestrict=en"; // Fetch only English results

    // Send the API request
    $response = file_get_contents($apiUrl);
    $books = json_decode($response, true);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Search</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="bg">
    <div class="container">
        <!-- Homepage Live Theme -->
        <div class="live-theme">
            <h1>Explore the World of Books</h1>
            <p>Find your favorite books with just a few clicks.</p>
        </div>

        <form method="GET" action="">
            <input type="text" name="book_name" placeholder="Enter book name" required>
            <button type="submit" class="search-button">Search</button>
        </form>

        <?php if (isset($books['items'])): ?>
            <h2>Search Results</h2>
            <div class="results">
                <?php foreach ($books['items'] as $book): 
                    $volumeInfo = $book['volumeInfo'];
                    $title = $volumeInfo['title'] ?? 'No title available';
                    $authors = implode(', ', $volumeInfo['authors'] ?? ['No authors available']);
                    $description = $volumeInfo['description'] ?? 'No description available';
                    $averageRating = $volumeInfo['averageRating'] ?? 'No rating available';
                    $thumbnail = $volumeInfo['imageLinks']['thumbnail'] ?? 'no_image_available.png'; // Book thumbnail image
                    $shortDescription = (strlen($description) > 350) ? substr($description, 0, 350) : $description;
                    $showReadMore = strlen($description) > 350;
                    $amazonLink = "https://www.amazon.com/s?k=" . urlencode($title) . " book"; // Amazon search URL
                ?>
                    <div class="book fade-in">
                        <img src="<?php echo $thumbnail; ?>" alt="Book cover">
                        <div class="book-info">
                            <h3><?php echo $title; ?></h3>
                            <p><strong>Author:</strong> <?php echo $authors; ?></p>
                            <p><strong>Description:</strong> 
                                <span class="short-description"><?php echo $shortDescription; ?></span>
                                <?php if ($showReadMore): ?>
                                    <span class="full-description" style="display: none;"><?php echo $description; ?></span>
                                    <a href="#" class="read-more" onclick="toggleDescription(event, this)">Read More</a>
                                <?php endif; ?>
                            </p>
                            <p><strong>Rating:</strong> <?php echo $averageRating; ?>/5</p>
                            <p><a href="<?php echo $amazonLink; ?>" class="buy-link" target="_blank">Buy on Amazon</a></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <script>
        // Read More / Read Less toggle
        function toggleDescription(event, element) {
            event.preventDefault();
            var shortDescription = element.previousElementSibling.previousElementSibling;
            var fullDescription = element.previousElementSibling;
            
            if (fullDescription.style.display === "none") {
                fullDescription.style.display = "inline";
                shortDescription.style.display = "none";
                element.innerText = "Read Less";
            } else {
                fullDescription.style.display = "none";
                shortDescription.style.display = "inline";
                element.innerText = "Read More";
            }
        }
    </script>
</div>
</body>
</html>
