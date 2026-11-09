<?php
declare(strict_types=1);

namespace App\Controllers\Pages\PublicSite;

use App\Core\View;

final class SitePagesController
{
    public static function home(): void
    {
        View::page('public/home', [
            'title' => 'Saliheen & Associates | Legal Certainty for Business',
            'description' => 'A remote legal governance office in Bangladesh, supporting boards and management teams with corporate governance, compliance and legal advisory services.',
            'bodyClass' => 'home-page',
            'canonical' => '/',
            'footerVariant' => 'home',
            'headerCta' => 'DISCUSS YOUR MATTER',
        ], 'public');
    }

    public static function about(): void
    {
        View::page('public/about', [
            'title' => 'About the Firm | Saliheen & Associates',
            'description' => 'Meet Saliheen & Associates, a Dhaka-based outsourced legal governance firm led by Barrister Nazmus Saliheen.',
            'bodyClass' => 'about-page',
            'canonical' => '/about',
            'footerVariant' => 'compact',
        ], 'public');
    }

    public static function services(): void
    {
        View::page('public/services', [
            'title' => 'Legal Services | Saliheen & Associates',
            'description' => 'Explore legal consultancy service areas offered by Saliheen & Associates.',
            'bodyClass' => 'services-page',
            'canonical' => '/services',
            'footerVariant' => 'compact',
        ], 'public');
    }

    public static function team(): void
    {
        View::page('public/team', [
            'title' => 'Our Team | Saliheen & Associates',
            'description' => 'Meet the professional team at Saliheen & Associates.',
            'bodyClass' => 'team-page',
            'canonical' => '/team',
            'footerVariant' => 'compact',
        ], 'public');
    }

    public static function contact(): void
    {
        View::page('public/contact', [
            'title' => 'Contact | Saliheen & Associates',
            'description' => 'Contact Saliheen & Associates to discuss an enquiry or arrange a consultation.',
            'bodyClass' => 'contact-page',
            'canonical' => '/contact',
            'footerVariant' => 'compact',
        ], 'public');
    }
}
