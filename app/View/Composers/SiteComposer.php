<?php

namespace App\View\Composers;

use App\Models\Cms\MenuItem;
use App\Models\Cms\Setting;
use App\Support\CmsMediaUploader;
use Illuminate\View\View;

class SiteComposer
{
    public function __construct(private CmsMediaUploader $media)
    {
    }

    public function compose(View $view): void
    {
        $settings = [
            'site_name' => Setting::get('site_name', 'The Prime Grade School'),
            'site_tagline' => Setting::get('site_tagline', 'Education with Character'),
            'school_motto' => Setting::get('school_motto', 'Education with Character'),
            'school_vision' => Setting::get('school_vision', 'Shaping curious, confident leaders for tomorrow.'),
            'school_mission' => Setting::get('school_mission', 'Delivering quality education, nurturing talents and shaping futures.'),
            'phone' => Setting::get('phone', '0759 770 255'),
            'phone_link' => Setting::get('phone_link', '+254759770255'),
            'email' => Setting::get('email', 'info@primegradeschool.org'),
            'website' => Setting::get('website', 'www.primegradeschool.org'),
            'postal_address' => Setting::get('postal_address', '79438 - 00200 Nairobi'),
            'location' => Setting::get('location', 'Borabu, Nyamira County'),
            'location_detail' => Setting::get('location_detail', 'Nyansiongo / Keroka area'),
            'enrolment_year' => Setting::get('enrolment_year', '2027'),
            'admission_notice' => Setting::get('admission_notice', 'New admission interviews start 3 November'),
            'footer_description' => Setting::get('footer_description', 'Education with Character. Serving families in Borabu, Nyamira County.'),
            'logo' => Setting::get('logo', 'assets/logo.png'),
            'copyright' => Setting::get('copyright', '© '.date('Y').' The Prime Grade School'),
            'powered_by_text' => Setting::get('powered_by_text', 'Smartweb Kenya'),
            'powered_by_url' => Setting::get('powered_by_url', 'https://www.smartwebkenya.com/'),
        ];

        $menuItems = MenuItem::query()
            ->whereNull('parent_id')
            ->where('is_visible', true)
            ->orderBy('sort_order')
            ->with(['children' => fn ($q) => $q->where('is_visible', true)->orderBy('sort_order')])
            ->get();

        $view->with([
            'settings' => $settings,
            'menuItems' => $menuItems,
            'media' => $this->media,
        ]);
    }
}
