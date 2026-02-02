<?php require_once 'includes/config.php'; ?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITE_NAME; ?> | <?php echo __('official_portal'); ?></title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

    <!-- Official Government Top Bar -->
    <div class="gov-top-bar bg-light py-1 border-bottom d-none d-lg-block">
        <div class="container d-flex justify-content-between align-items-center">
            <div class="small text-muted">
                <i class="fas fa-landmark me-2 text-primary"></i>
                <span class="fw-bold"><?php echo __('gov_nepal'); ?></span> | <?php echo __('official_portal'); ?>
            </div>
            <div class="small text-muted">
                <i class="far fa-calendar-alt me-1"></i> <?php echo __('date_today'); ?> <?php echo date('Y-m-d'); ?>
                <span class="mx-2">|</span>
                <a href="?lang=en" class="text-decoration-none text-muted <?php echo $lang == 'en' ? 'fw-bold text-primary' : ''; ?>">English</a>
                <span class="mx-1">/</span>
                <a href="?lang=ne" class="text-decoration-none text-muted <?php echo $lang == 'ne' ? 'fw-bold text-primary' : ''; ?>">नेपाली</a>
            </div>
        </div>
    </div>

    <!-- Breaking News Ticker -->
    <div class="news-ticker bg-primary text-white py-2 overflow-hidden position-relative" style="z-index: 1021;">
        <div class="container d-flex">
            <span class="fw-bold text-uppercase me-3 border-end pe-3" style="white-space: nowrap;">
                <i class="fas fa-bullhorn me-2"></i><?php echo __('latest_update'); ?>:
            </span>
            <marquee behavior="scroll" direction="left" onmouseover="this.stop();" onmouseout="this.start();" class="small pt-1">
                <?php echo __('hero_badge'); ?> • Voter list verification is now active for upcoming Phase 2 elections • Please update your citizenship details before the deadline.
            </marquee>
        </div>
    </div>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg sticky-top bg-white shadow-sm py-3">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="index.php">
                <div class="logo-box bg-primary text-white rounded-3 p-2 me-3 shadow-sm">
                    <i class="fas fa-fingerprint fs-3"></i>
                </div>
                <div>
                    <span class="fw-bolder fs-4 text-primary d-block" style="line-height: 1.2;"><?php echo __('site_name'); ?></span>
                    <small class="text-muted d-block" style="font-size: 0.75rem; margin-top: -2px; letter-spacing: 1px;"><?php echo __('official_portal'); ?></small>
                </div>
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <i class="fas fa-bars"></i>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item"><a class="nav-link fw-bold" href="#home"><?php echo __('home'); ?></a></li>
                    <li class="nav-item dropdown px-2">
                        <a class="nav-link dropdown-toggle fw-bold" href="#" data-bs-toggle="dropdown"><?php echo __('official_resources'); ?></a>
                        <ul class="dropdown-menu border-0 shadow-sm rounded-3">
                            <li><a class="dropdown-item py-2" href="#"><?php echo __('voter_education'); ?></a></li>
                            <li><a class="dropdown-item py-2" href="#"><?php echo __('polling_stations'); ?></a></li>
                            <li><a class="dropdown-item py-2" href="#"><?php echo __('downloads'); ?></a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item py-2 text-primary fw-bold" href="https://election.gov.np" target="_blank"><?php echo __('election_commission'); ?></a></li>
                        </ul>
                    </li>
                    <li class="nav-item"><a class="nav-link fw-bold" href="#how-it-works"><?php echo __('how_it_works'); ?></a></li>
                    <li class="nav-item"><a class="nav-link fw-bold" href="#legal"><?php echo __('legal_title'); ?></a></li>
                    <li class="nav-item ms-lg-3">
                        <a class="btn btn-outline-primary px-4 rounded-pill fw-bold small" href="login.php"><?php echo __('login'); ?></a>
                    </li>
                    <li class="nav-item dropdown ms-lg-2">
                        <a class="btn btn-primary px-4 rounded-pill fw-bold shadow-sm small dropdown-toggle" href="#" data-bs-toggle="dropdown"><?php echo __('register'); ?></a>
                        <ul class="dropdown-menu border-0 shadow-sm rounded-3 mt-2">
                            <li><a class="dropdown-item py-2" href="voter_signup.php"><i class="fas fa-user me-2 theme-text"></i> <?php echo __('voter'); ?></a></li>
                            <li><a class="dropdown-item py-2" href="candidate_signup.php"><i class="fas fa-user-tie me-2 theme-text"></i> <?php echo __('candidate'); ?></a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Area -->
    <header id="home" class="hero py-5" style="background: linear-gradient(135deg, #fff 0%, #f1f3f5 100%);">
        <div class="container py-4">
            <div class="row align-items-center">
                <div class="col-lg-6 hero-content text-center text-lg-start">
                    <span class="official-badge mb-3 d-inline-block"><?php echo __('gov_nepal'); ?></span>
                    <h1 class="display-3 fw-bold text-dark mb-4"><?php echo __('hero_title'); ?></h1>
                    <p class="lead text-muted mb-5 fs-5"><?php echo __('hero_desc'); ?></p>
                    <div class="d-flex flex-wrap gap-3 justify-content-center justify-content-lg-start">
                        <a href="voter_signup.php" class="btn btn-primary btn-lg px-5 py-3 rounded-pill fw-bold shadow">
                            <i class="fas fa-check-circle me-2"></i><?php echo __('start_voting'); ?>
                        </a>
                        <a href="candidate_signup.php" class="btn btn-outline-dark btn-lg px-5 py-3 rounded-pill fw-bold shadow-sm">
                            <i class="fas fa-bullhorn me-2"></i><?php echo __('candidate'); ?>
                        </a>
                    </div>
                </div>
                <div class="col-lg-6 mt-5 mt-lg-0">
                    <div class="position-relative">
                        <img src="assets/nepal_hero.png" alt="Nepali Voting" class="img-fluid rounded-4 shadow-lg animate-fade">
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Election Statistics (Snapshot) -->
    <section class="py-5 bg-light border-bottom">
        <div class="container py-4">
            <div class="row g-4 text-center">
                <div class="col-md-4">
                    <div class="stat-card">
                        <div class="stat-icon"><i class="fas fa-users"></i></div>
                        <h2 class="fw-bold text-primary">18.5M+</h2>
                        <p class="text-muted mb-0 fw-bold text-uppercase small"><?php echo __('stats_voters'); ?></p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card">
                        <div class="stat-icon"><i class="fas fa-chair"></i></div>
                        <h2 class="fw-bold text-primary">275</h2>
                        <p class="text-muted mb-0 fw-bold text-uppercase small"><?php echo __('stats_seats'); ?></p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card">
                        <div class="stat-icon"><i class="fas fa-map-marked-alt"></i></div>
                        <h2 class="fw-bold text-primary">165</h2>
                        <p class="text-muted mb-0 fw-bold text-uppercase small"><?php echo __('stats_constituencies'); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Notice & Press Release Section -->
    <section class="py-5" style="z-index: 1;">
        <div class="container py-4">
            <div class="row g-5">
                <!-- Notice Board -->
                <div class="col-lg-7">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h3 class="fw-bold mb-0 text-dark border-start border-primary border-4 ps-3"><?php echo __('notice_board'); ?></h3>
                        <a href="#" class="text-primary fw-bold text-decoration-none small"><?php echo __('view_all'); ?> <i class="fas fa-arrow-right ms-1"></i></a>
                    </div>
                    <div class="notice-board">
                        <div class="notice-item d-flex align-items-center">
                            <div class="date-box bg-light p-2 rounded text-center me-3" style="min-width: 65px;">
                                <span class="d-block fw-bold text-primary fs-5">05</span>
                                <span class="small text-muted">FEB</span>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-1">Voter Registration Deadline Extension Notice</h6>
                                <p class="small text-muted mb-0">Information regarding the extension of voter list updating.</p>
                            </div>
                            <a href="#" class="ms-auto btn btn-light btn-sm rounded-circle"><i class="fas fa-download"></i></a>
                        </div>
                        <div class="notice-item d-flex align-items-center">
                            <div class="date-box bg-light p-2 rounded text-center me-3" style="min-width: 65px;">
                                <span class="d-block fw-bold text-primary fs-5">01</span>
                                <span class="small text-muted">FEB</span>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-1">Guidelines for International Observers</h6>
                                <p class="small text-muted mb-0">Official guidelines released for upcoming elections.</p>
                            </div>
                            <a href="#" class="ms-auto btn btn-light btn-sm rounded-circle"><i class="fas fa-download"></i></a>
                        </div>
                    </div>
                </div>
                <!-- Press Releases -->
                <div class="col-lg-5">
                    <h3 class="fw-bold mb-4 text-dark border-start border-primary border-4 ps-3"><?php echo __('press_releases'); ?></h3>
                    <div class="press-section">
                        <div class="press-card mb-3">
                            <h6 class="fw-bold mb-2">ECN holds interactive session with civil society</h6>
                            <p class="small text-muted">A collaborative meeting was held at the headquarters to discuss transparency.</p>
                        </div>
                        <div class="press-card">
                            <h6 class="fw-bold mb-2">Completion of first phase technical audit</h6>
                            <p class="small text-muted">The central technical committee has successfully audited digital ballot systems.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Voter Services Section (Final ECN Feature) -->
    <section class="py-5 bg-light">
        <div class="container py-4">
            <div class="row g-4 text-center">
                <div class="col-lg-3 col-md-6">
                    <div class="service-card p-4 bg-white rounded-4 shadow-sm h-100 border-top border-primary border-4">
                        <div class="fs-1 text-primary mb-3"><i class="fas fa-id-card"></i></div>
                        <h5 class="fw-bold"><?php echo __('online_registration'); ?></h5>
                        <p class="small text-muted mb-3">Apply for new voter registration from anywhere.</p>
                        <a href="voter_signup.php" class="btn btn-outline-primary btn-sm rounded-pill px-4"><?php echo __('register'); ?></a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="service-card p-4 bg-white rounded-4 shadow-sm h-100 border-top border-primary border-4">
                        <div class="fs-1 text-primary mb-3"><i class="fas fa-search-location"></i></div>
                        <h5 class="fw-bold"><?php echo __('voter_search'); ?></h5>
                        <p class="small text-muted mb-3">Check your name and polling booth in the official list.</p>
                        <button class="btn btn-outline-primary btn-sm rounded-pill px-4"><?php echo __('view_all'); ?></button>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="service-card p-4 bg-white rounded-4 shadow-sm h-100 border-top border-primary border-4">
                        <div class="fs-1 text-primary mb-3"><i class="fas fa-chart-pie"></i></div>
                        <h5 class="fw-bold"><?php echo __('result_portal'); ?></h5>
                        <p class="small text-muted mb-3">Live updates and historical election data.</p>
                        <button class="btn btn-outline-primary btn-sm rounded-pill px-4"><?php echo __('view_all'); ?></button>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="service-card p-4 bg-white rounded-4 shadow-sm h-100 border-top border-primary border-4">
                        <div class="fs-1 text-primary mb-3"><i class="fas fa-file-invoice"></i></div>
                        <h5 class="fw-bold"><?php echo __('form_download'); ?></h5>
                        <p class="small text-muted mb-3">Download all electoral forms and documentation.</p>
                        <button class="btn btn-outline-primary btn-sm rounded-pill px-4"><?php echo __('downloads'); ?></button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Multimedia & Acts (Final Layout) -->
    <section class="py-5 bg-white clearfix">
        <div class="container py-4">
            <div class="row g-5">
                <!-- Multimedia Gallery -->
                <div class="col-lg-6">
                    <h3 class="fw-bold mb-4 text-dark"><?php echo __('multimedia_title'); ?></h3>
                    <div class="video-placeholder shadow">
                        <img src="https://images.unsplash.com/photo-1516280440614-37939bbacd81?q=80&w=1000&auto=format&fit=crop" class="w-100 h-100 object-fit-cover rounded-4" alt="Video">
                        <div class="play-btn">
                            <i class="fas fa-play"></i>
                        </div>
                    </div>
                </div>
                <!-- Acts & Legal -->
                <div id="legal" class="col-lg-6">
                    <h3 class="fw-bold mb-4 text-dark"><?php echo __('legal_title'); ?></h3>
                    <p class="text-muted small mb-4"><?php echo __('legal_desc'); ?></p>
                    <div class="legal-item">
                        <span class="fw-bold small">Election (Offence and Punishment) Act, 2073</span>
                        <a href="#" class="text-primary"><i class="fas fa-file-pdf fs-4"></i></a>
                    </div>
                    <div class="legal-item">
                        <span class="fw-bold small">Election Commission Act, 2073</span>
                        <a href="#" class="text-primary"><i class="fas fa-file-pdf fs-4"></i></a>
                    </div>
                    <div class="legal-item">
                        <span class="fw-bold small">Voter List Act, 2073</span>
                        <a href="#" class="text-primary"><i class="fas fa-file-pdf fs-4"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white pt-5 pb-4">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <div class="d-flex align-items-center mb-4">
                        <div class="bg-primary p-2 rounded text-white me-3">
                            <i class="fas fa-fingerprint fs-4"></i>
                        </div>
                        <h4 class="fw-bold mb-0"><?php echo __('site_name'); ?></h4>
                    </div>
                    <p class="text-secondary small">VOTEPORT is a secure, digital-first voting platform designed to empower citizens through transparent, accessible, and inclusive election technologies.</p>
                </div>
                <div class="col-lg-4 col-md-6">
                    <h6 class="fw-bold text-white mb-4"><?php echo __('official_resources'); ?></h6>
                    <ul class="list-unstyled small text-secondary">
                        <li class="mb-2"><a href="https://election.gov.np" target="_blank" class="text-secondary text-decoration-none">ECN Official Portal</a></li>
                        <li class="mb-2"><?php echo __('voter_reg_link'); ?></li>
                        <li class="mb-2"><?php echo __('privacy_policy'); ?></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h6 class="fw-bold text-white mb-4"><?php echo __('contact_us'); ?></h6>
                    <p class="text-secondary small mb-2"><i class="fas fa-map-marker-alt me-2 text-primary"></i> <?php echo __('office_address'); ?></p>
                    <p class="text-secondary small mb-2"><i class="fas fa-phone me-2 text-primary"></i> <?php echo __('phone_number'); ?></p>
                    <p class="text-secondary small mb-2"><i class="fas fa-envelope me-2 text-primary"></i> <?php echo __('email_address'); ?></p>
                    <p class="text-primary small fw-bold mt-3"><i class="fas fa-clock me-2"></i> <?php echo __('available_247'); ?></p>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h6 class="fw-bold text-white mb-4"><?php echo __('newsletter_title'); ?></h6>
                    <p class="text-secondary small mb-3"><?php echo __('newsletter_desc'); ?></p>
                    <div class="input-group mb-3">
                        <input type="email" class="form-control bg-secondary bg-opacity-10 border-0 text-white" placeholder="<?php echo __('email'); ?>">
                        <button class="btn btn-primary" type="button"><?php echo __('subscribe'); ?></button>
                    </div>
                </div>
            </div>
            <hr class="mt-5 border-secondary opacity-25">
            <div class="text-center">
                <p class="small text-secondary mb-0">&copy; 2026 <?php echo __('site_name'); ?>. Powered by Digital Election Commission.</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/main.js"></script>
</body>
</html>
