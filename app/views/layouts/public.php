<?php
declare(strict_types=1);

function public_head(string $title, string $description, string $bodyClass, string $canonicalPath): void
{
    $canonical = absolute_url($canonicalPath);
    echo '<!doctype html><html lang="en"><head>';
    echo '<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">';
    echo '<meta name="description" content="' . e($description) . '">';
    echo '<meta name="theme-color" content="#702632">';
    echo '<title>' . e($title) . '</title>';
    echo '<link rel="canonical" href="' . e($canonical) . '">';
    echo '<meta property="og:type" content="website">';
    echo '<meta property="og:site_name" content="Saliheen &amp; Associates">';
    echo '<meta property="og:title" content="' . e($title) . '">';
    echo '<meta property="og:description" content="' . e($description) . '">';
    echo '<meta property="og:url" content="' . e($canonical) . '">';
    echo '<link rel="icon" type="image/svg+xml" href="' . url('/images/saliheen-favicon.svg') . '">';
    echo '<link rel="icon" type="image/png" sizes="64x64" href="' . url('/images/saliheen-favicon.png') . '">';
    echo '<link rel="stylesheet" href="' . url('/css/styles.css') . '">';
    echo '<link rel="stylesheet" href="' . url('/portal/services.css') . '">';
    echo '</head><body class="' . e($bodyClass) . '">';
}

function public_header(string $active = ''): void
{
    $links = [
        'home' => ['label' => 'Home', 'href' => url('/')],
        'about' => ['label' => 'About', 'href' => url('/about/')],
        'services' => ['label' => 'Services', 'href' => url('/services/')],
        'explore' => ['label' => 'Explore Services', 'href' => url('/explore-services/')],
        'team' => ['label' => 'Our Team', 'href' => url('/team/')],
        'contact' => ['label' => 'Contact', 'href' => url('/contact/')],
    ];
    echo '<div class="topbar"><div class="container"><span>Independent Legal Guidance. Professional Service.</span><span>Appointments By Prior Arrangement</span></div></div>';
    echo '<header class="navbar"><div class="container navinner">';
    echo '<a class="brand" href="' . url('/') . '" aria-label="Saliheen &amp; Associates home">';
    echo '<img src="' . url('/images/saliheen-brand.svg') . '" alt="Saliheen &amp; Associates logo"></a>';
    echo '<button class="menu-toggle" id="menuToggle" aria-label="Open navigation" aria-expanded="false" aria-controls="navLinks">&#9776;</button>';
    echo '<nav class="navlinks" id="navLinks" aria-label="Main navigation">';
    foreach ($links as $key => $link) {
        $class = $key === $active ? ' class="active"' : '';
        echo '<a href="' . e($link['href']) . '"' . $class . '>' . e($link['label']) . '</a>';
    }
    echo '<a class="portal-link" href="' . url('/login/') . '">Client Portal</a>';
    echo '<a class="admin-link" href="' . url('/admin-login/') . '">Admin Login</a>';
    echo '</nav>';
    echo '<a class="navcta" href="' . url('/contact/#contact') . '">DISCUSS YOUR MATTER &#8599;</a>';
    echo '</div></header>';
}

function public_footer(): void
{
    echo '<footer class="footer"><div class="container"><div class="footer-grid">';
    echo '<div><img class="footer-logo" src="' . url('/images/saliheen-brand-light.svg') . '" alt="Saliheen &amp; Associates logo">';
    echo '<p>Professional legal consultancy with a commitment to clarity, discretion, and client-focused service.</p>';
    echo '<p>B176, Lane 23, DOHS, Mohakhali, Dhaka, Bangladesh</p></div>';
    echo '<div><h4>Explore</h4><div class="footer-links">';
    echo '<a href="' . url('/about/') . '">About the Firm</a>';
    echo '<a href="' . url('/services/') . '">Practice Areas</a>';
    echo '<a href="' . url('/explore-services/') . '">Explore Legal Services</a>';
    echo '<a href="' . url('/team/') . '">Our Team</a>';
    echo '<a href="' . url('/contact/#contact') . '">Contact</a></div></div>';
    echo '<div><h4>Contact</h4><p><a href="mailto:info@nsaliheen.com">info@nsaliheen.com</a><br>';
    echo '<a href="mailto:nazmus.nazlee@gmail.com">nazmus.nazlee@gmail.com</a><br>';
    echo '<a href="tel:+8801713079046">+880 1713-079046</a></p>';
    echo '<a class="footer-enquiry" href="' . url('/contact/#contact') . '">Enquire with the firm &#8599;</a></div>';
    echo '</div></div><div class="copyright-band"><div class="container"><div class="copyright">';
    echo '<span>&copy; ' . date('Y') . ' Saliheen &amp; Associates.</span><span>General info only; not legal advice.</span>';
    echo '</div></div></div></footer>';
    echo '<button class="back-top" id="backTop" aria-label="Scroll to top">&#8593;</button>';
    echo '<script src="' . url('/js/site.js') . '" defer></script>';
}
