<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Travel Planner Application</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f5e6f8; /* Light lilac background */
        }
        .navbar {
            background-color: #9b59b6; /* Dark lilac for navbar */
        }
        .jumbotron {
            background-image: url('travel.jpg');
          background-size:cover;
            background-position: center;
            color: #7d3c98;
            height: 350px;
            background-color: #bfa2db; /* Light lilac overlay */
            background-blend-mode: overlay;
        }
        .jumbotron .btn-primary {
            background-color: #7d3c98; /* Darker lilac for buttons */
            border-color: #7d3c98;
        }
        .btn-primary {
            background-color: #7d3c98; /* Darker lilac for buttons */
            border-color: #7d3c98;
        }
        .jumbotron .btn-primary:hover {
            background-color: #6c3483; /* Even darker on hover */
        }
        .btn-primary:hover {
            background-color: #6c3483; /* Even darker on hover */
        }
        .section {
            padding: 50px 0;
        }
        .card {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }
        .card:hover {
            transform: translateY(-5px);
        }
        .icon-section {
            font-size: 40px;
            color: #7d3c98; /* Dark lilac for icons */
        }
        .bg-light-lilac {
            background-color: #e6ccf2; /* Light lilac for sections */
        }
        .btn-lilac-outline {
            color: #7d3c98;
            border-color: #7d3c98;
        }
        .btn-lilac-outline:hover {
            background-color: #7d3c98;
            color: white;
        }
        footer {
            background-color: #9b59b6;
            color: white;
        }
         .feature-img {
        width: 100px;
        height: 100px;
        object-fit: cover; /* Ensures the image fits within the dimensions without distortion */
        border-radius: 50%;
    }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark">
  <a class="navbar-brand" href="index.php">Travel Planner Application</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNav">
    <ul class="navbar-nav ml-auto">
      <li class="nav-item">
        <a class="nav-link" href="login.php">Login</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="signup.php">Sign Up</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="community_gallery.php">Community Gallery</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="feedback.php">Feedback</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="contact.php">Contact Us</a>
      </li>
    </ul>
  </div>
</nav>

<!-- Hero Section -->
<div class="jumbotron text-center d-flex align-items-center justify-content-center">
    <div>
        <h1 class="display-4">Explore the World with Ease</h1>
        <p class="lead">Create, manage, and share your travel experiences with the community.</p>
        <br/><br/><br/>
        <a href="login.php" class="btn btn-primary btn-lg">Get Started</a>
        <a href="signup.php" class="btn btn-lilac-outline btn-lg">Sign Up</a>
    </div>
</div>

<!-- Features Section -->
<div class="section text-center bg-light-lilac py-5">
    <div class="container">
        <h2 class="mb-4" style="color: #7d3c98;">Why Choose Travel Planner?</h2>
        <div class="row">
            <!-- Plan Your Trips Feature -->
            <div class="col-md-4">
                <div class="card p-4" style="border: none; background-color: #bfa2db;">
                    <div class="icon-section mb-3">
                        <!-- Image for Planning Trips -->
                        <img src="img4.jpg" alt="Plan Trips Image" class="img-fluid mb-3" style="border-radius: 30%;">
                        <i class="fas fa-map-marked-alt" style="font-size: 40px; color: #7d3c98;"></i>
                    </div>
                    <h4 style="color: #7d3c98;">Plan Your Trips</h4>
                    <p style="color: #555;">Easily plan your trips with a detailed itinerary & stay on track during all your exciting adventures.</p>
                </div>
            </div>

            <!-- Share Your Experiences Feature -->
            <div class="col-md-4">
                <div class="card p-4" style="border: none; background-color: #bfa2db;">
                    <div class="icon-section mb-3">
                        <!-- Image for Sharing Experiences -->
                        <img src="img5.webp" alt="Share Experiences Image" class="img-fluid mb-3" style="border-radius: 30%;">
                        <i class="fas fa-camera-retro" style="font-size: 40px; color: #7d3c98;"></i>
                    </div>
                    <h4 style="color: #7d3c98;">Share Your Experiences</h4>
                    <p style="color: #555;">Upload your favorite travel photos and share your stories with our community.</p>
                    <br/>
                    
                </div>
            </div>

            <!-- Join Our Community Feature -->
            <div class="col-md-4">
                <div class="card p-4" style="border: none; background-color: #bfa2db;">
                    <div class="icon-section mb-3">
                        <!-- Image for Community -->
                        <img src="img6.jpg" alt="Join Community Image" class="img-fluid mb-3" style="border-radius: 30%;">
                        <i class="fas fa-users" style="font-size: 40px; color: #7d3c98;"></i>
                    </div>
                    <h4 style="color: #7d3c98;">Join Our Community</h4>
                    <p style="color: #555;">Join a vibrant community of travelers and get inspired by their real-life experiences.</p>
                </div>
            </div>
        </div>
    </div>
</div>



<!-- Gallery Section -->
<div class="section bg-light-lilac">
    <div class="container text-center">
        <h2 class="mb-4">Community Gallery</h2>
        <div class="row">
            <div class="col-md-4 mb-4">
                <img src="img2.jpg" class="img-fluid rounded" alt="Beach">
            </div>
            <div class="col-md-4 mb-4">
                <img src="img1.jpg" class="img-fluid rounded" alt="Mountains">
            </div>
            <div class="col-md-4 mb-4">
                <img src="img3.jpg" class="img-fluid rounded" alt="City">
            </div>
        </div>
       
        <a href="community_gallery.php" class="btn btn-primary btn-lg">Explore the Gallery</a>
    </div>
</div>

<!-- Call to Action Section -->
<div class="section text-center">
    <div class="container">
        <h2 class="mb-4">Ready to Start Your Adventure?</h2>
        <p>Sign up now and begin your next journey with Travel Planner!</p>
        <a href="signup.php" class="btn btn-lilac-outline btn-lg">Sign Up</a>
    </div>
</div>

<!-- Footer -->
<footer class="text-center py-4">
    <p>&copy; 2024 Travel Planner. All Rights Reserved.</p>
</footer>

<script src="https://kit.fontawesome.com/a076d05399.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
