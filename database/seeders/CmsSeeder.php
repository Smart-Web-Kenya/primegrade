<?php

namespace Database\Seeders;

use App\Models\Cms\Announcement;
use App\Models\Cms\FacilityItem;
use App\Models\Cms\GalleryImage;
use App\Models\Cms\MenuItem;
use App\Models\Cms\Page;
use App\Models\Cms\Principle;
use App\Models\Cms\Program;
use App\Models\Cms\Setting;
use Illuminate\Database\Seeder;

class CmsSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedSettings();
        $this->seedPages();
        $this->seedMenu();
        $this->seedAnnouncements();
        $this->seedPrograms();
        $this->seedFacilities();
        $this->seedGallery();
        $this->seedPrinciples();
    }

    private function seedSettings(): void
    {
        $settings = [
            ['key' => 'site_name', 'value' => 'The Prime Grade School', 'group' => 'general'],
            ['key' => 'site_tagline', 'value' => 'Education with Character', 'group' => 'general'],
            ['key' => 'school_motto', 'value' => 'Education with Character', 'group' => 'identity'],
            ['key' => 'school_vision', 'value' => 'Shaping curious, confident leaders for tomorrow.', 'group' => 'identity'],
            ['key' => 'school_mission', 'value' => 'Delivering quality education, nurturing talents and shaping futures.', 'group' => 'identity'],
            ['key' => 'phone', 'value' => '0759 770 255', 'group' => 'contact'],
            ['key' => 'phone_link', 'value' => '+254759770255', 'group' => 'contact'],
            ['key' => 'email', 'value' => 'info@primegradeschool.org', 'group' => 'contact'],
            ['key' => 'website', 'value' => 'www.primegradeschool.org', 'group' => 'contact'],
            ['key' => 'postal_address', 'value' => '79438 - 00200 Nairobi', 'group' => 'contact'],
            ['key' => 'location', 'value' => 'Borabu, Nyamira County', 'group' => 'contact'],
            ['key' => 'location_detail', 'value' => 'Nyansiongo / Keroka area', 'group' => 'contact'],
            ['key' => 'enrolment_year', 'value' => '2027', 'group' => 'admissions'],
            ['key' => 'admission_notice', 'value' => 'New admission interviews start 3 November', 'group' => 'admissions'],
            ['key' => 'footer_description', 'value' => 'Education with Character. Serving families in Borabu, Nyamira County.', 'group' => 'general'],
            ['key' => 'logo', 'value' => 'uploads/cms/logos/logo.png', 'group' => 'general'],
            ['key' => 'copyright', 'value' => '© 2026 The Prime Grade School', 'group' => 'general'],
            ['key' => 'powered_by_text', 'value' => 'Smartweb Kenya', 'group' => 'general'],
            ['key' => 'powered_by_url', 'value' => 'https://www.smartwebkenya.com/', 'group' => 'general'],
        ];

        foreach ($settings as $setting) {
            Setting::query()->updateOrCreate(
                ['key' => $setting['key']],
                $setting + ['type' => 'text'],
            );
        }
    }

    private function seedPages(): void
    {
        $pages = [
            [
                'slug' => 'home',
                'title' => 'The Prime Grade School',
                'excerpt' => 'A place where every child grows in confidence and character.',
                'seo_title' => 'The Prime Grade School | Education with Character',
                'seo_description' => 'Enrolment is open for the 2027 academic year at The Prime Grade School, Borabu, Nyamira County. Pre-School, Primary and Junior School with boarding available.',
                'content' => [
                    'hero' => [
                        'eyebrow' => 'Borabu, Nyamira County · Enrolment open for 2027',
                        'heading' => 'The Prime Grade School',
                        'tagline' => 'Education with Character',
                        'text' => 'A place where every child grows in confidence and character.',
                        'image' => 'uploads/cms/general/building.jpg',
                    ],
                    'about' => [
                        'heading' => 'Education with Character',
                        'lead' => 'Our identity, values and approach to learner development.',
                    ],
                    'academics' => [
                        'heading' => 'Grades offered',
                        'lead' => 'Early Years, Primary and Junior School — with boarding for selected grades.',
                    ],
                    'facilities' => [
                        'heading' => 'Life at Prime Grade',
                        'lead' => 'Spaces and activities that support learning, character and talent.',
                    ],
                    'news' => [
                        'heading' => 'Latest updates',
                        'lead' => 'Important notices for parents and the school community.',
                    ],
                    'contact' => [
                        'heading' => 'Visit or call to enrol',
                        'lead' => 'Borabu, Nyamira County · Nyansiongo / Keroka area',
                    ],
                ],
            ],
            [
                'slug' => 'about',
                'title' => 'Education with Character',
                'excerpt' => 'Our identity, values and approach to learner development at The Prime Grade School, Borabu, Nyamira County.',
                'seo_title' => 'About the School | The Prime Grade School',
                'seo_description' => 'Learn about The Prime Grade School’s motto, vision, mission, teaching approach and the 10 Point Program — Education with Character.',
                'content' => [
                    'intro_heading' => 'A place where every child grows in confidence and character',
                    'intro' => 'The Prime Grade School is a school in Borabu, Nyamira County (Nyansiongo / Keroka area), serving families through Pre-School, Primary School and Junior School, with boarding available for selected grades.',
                    'quote' => 'The school’s motto is inspired by the proven belief that education without a solid foundation in character building is like a ship without a compass: equipped for a long journey, but lacking the moral direction to reach a worthy destination safely.',
                    'signature' => 'Prime Grade School: Shaping character, building knowledge and nurturing talent.',
                    'staff_heading' => 'Dedicated Teachers, Individual Attention',
                    'staff_body' => 'A dedicated and committed team of qualified teachers and instructors provides caring, engaging teaching and individual attention. Through clear guidance and encouragement, we help each learner build confidence, make steady academic progress and develop character and talents.',
                    'approach' => [
                        ['title' => 'Hands-on Learning', 'body' => 'Practical, age-appropriate projects bring lessons to life, building creativity, problem-solving and everyday life skills.'],
                        ['title' => 'Character and talent', 'body' => 'We recognise each learner’s strengths, support their learning and wellbeing, and develop talents in music, drama, art and sport.'],
                        ['title' => 'Partnership with parents', 'body' => 'We maintain regular communication between school and home to support each child’s learning, character and wellbeing.'],
                    ],
                ],
            ],
            [
                'slug' => 'academics',
                'title' => 'Grades offered',
                'excerpt' => 'Join a school with a new library, an IT room and an equipped laboratory, plus music, sport and hands-on projects.',
                'seo_title' => 'Academic Programs | The Prime Grade School',
                'seo_description' => 'Explore Pre-School, Primary and Junior School programs at The Prime Grade School, with boarding options and a strong foundation for learner development.',
                'content' => [
                    'pathways_heading' => 'Our educational structure',
                    'pathways_lead' => 'The Prime Grade School offers Early Years, Primary School and Junior School pathways, with boarding for selected grades.',
                    'boarding' => 'New facilities include a modern ablution block, a kitchen and dining hall, and dormitories, with structured study time and closer academic support.',
                    'environment_heading' => 'Learning environment and development',
                    'environment_lead' => 'Across all grades, learners benefit from dedicated teachers, practical projects and spaces that support academic progress and character growth.',
                ],
            ],
            [
                'slug' => 'facilities',
                'title' => 'Life at Prime Grade',
                'excerpt' => 'From dedicated teaching to libraries, labs, clubs and transport — every part of school life is built to support learning and character.',
                'seo_title' => 'Facilities & Co-curricular Activities | The Prime Grade School',
                'seo_description' => 'Discover classrooms, library, IT room, laboratory, boarding, transport, arts, sport and clubs at The Prime Grade School.',
                'content' => [
                    'learning_heading' => 'Classrooms, library and digital learning',
                    'staff_heading' => 'Dedicated Teachers, Individual Attention',
                    'staff_body' => 'A dedicated and committed team of qualified teachers and instructors provides caring, engaging teaching and individual attention. Through clear guidance and encouragement, we help each learner build confidence, make steady academic progress and develop character and talents.',
                    'boarding_heading' => 'Boarding facilities',
                    'boarding_body' => 'New facilities include a modern ablution block, a kitchen and dining hall, and dormitories, with structured study time and closer academic support.',
                    'activities_heading' => 'Arts, sport and clubs',
                    'activities_lead' => 'Music, singing, dance and drama nurture expression; soccer builds fitness and teamwork. Young Farmers and Environmental clubs encourage practical skills and care for nature.',
                ],
            ],
            [
                'slug' => 'news',
                'title' => 'School updates',
                'excerpt' => 'Admission notices and important information for parents and the school community.',
                'seo_title' => 'News, Events & Announcements | The Prime Grade School',
                'seo_description' => 'School announcements, admission notices and updates from The Prime Grade School, Borabu, Nyamira County.',
                'content' => [
                    'heading' => 'Announcements',
                    'parents_heading' => 'Stay in touch',
                    'parents_text' => 'For admission notices and general information, call or email the school office.',
                ],
            ],
            [
                'slug' => 'contact',
                'title' => 'Get in touch',
                'excerpt' => 'Call or visit the school office to enrol, ask a question or request a school visit.',
                'seo_title' => 'Contact & Location | The Prime Grade School',
                'seo_description' => 'Contact The Prime Grade School in Borabu, Nyamira County. Call 0759 770 255 or email info@primegradeschool.org to enrol or request a visit.',
                'content' => [
                    'office_heading' => 'Contact details',
                    'location_heading' => 'Location',
                    'location_text' => 'The Prime Grade School is in Borabu, Nyamira County, in the Nyansiongo / Keroka area.',
                ],
            ],
        ];

        foreach ($pages as $page) {
            Page::query()->updateOrCreate(
                ['slug' => $page['slug']],
                $page + ['is_published' => true],
            );
        }
    }

    private function seedMenu(): void
    {
        foreach ([
            ['label' => 'Home', 'url' => '/', 'sort_order' => 1],
            ['label' => 'About', 'url' => '/about', 'sort_order' => 2],
            ['label' => 'Academics', 'url' => '/academics', 'sort_order' => 3],
            ['label' => 'Facilities', 'url' => '/facilities', 'sort_order' => 4],
            ['label' => 'News', 'url' => '/news', 'sort_order' => 5],
            ['label' => 'Contact', 'url' => '/contact', 'sort_order' => 6],
        ] as $item) {
            MenuItem::query()->updateOrCreate(
                ['label' => $item['label'], 'parent_id' => null],
                $item + ['is_visible' => true],
            );
        }
    }

    private function seedAnnouncements(): void
    {
        $announcements = [
            [
                'title' => 'New admission interviews start 3 November',
                'slug' => 'new-admission-interviews-start-3-november',
                'category' => 'Admissions',
                'excerpt' => 'Call or visit the school office to enrol for the 2027 academic year.',
                'body' => 'Interviews for new admissions start 3 November. Call or visit the school office to enrol for the 2027 academic year.',
                'cta_text' => 'Call 0759 770 255',
                'cta_url' => 'tel:+254759770255',
                'published_at' => '2026-11-03 08:00:00',
                'sort_order' => 1,
            ],
            [
                'title' => 'Enrolment is open for 2027',
                'slug' => 'enrolment-is-open-for-2027',
                'category' => '2027 academic year',
                'excerpt' => 'Pre-School, Primary and Junior School, with boarding for selected grades.',
                'body' => 'Enrolment is open for the 2027 academic year — Pre-School, Primary and Junior School, with boarding available for selected grades.',
                'cta_text' => 'Contact admissions',
                'cta_url' => '/contact',
                'published_at' => '2026-11-03 08:00:00',
                'sort_order' => 2,
            ],
        ];

        foreach ($announcements as $announcement) {
            Announcement::query()->updateOrCreate(
                ['slug' => $announcement['slug']],
                $announcement + ['is_published' => true],
            );
        }
    }

    private function seedPrograms(): void
    {
        $programs = [
            [
                'title' => 'Early Years (Pre-School)',
                'slug' => 'early-years',
                'subtitle' => 'Pre-School',
                'grades_label' => 'Playgroup, PP1 and PP2',
                'summary' => 'Our Pre-School pathway gives young learners a caring start, building confidence, curiosity and readiness for Primary School through engaging teaching and individual attention.',
                'body' => 'A caring start that builds confidence and readiness for Primary School.',
                'bullets' => [
                    'Caring, age-appropriate teaching and guidance',
                    'Hands-on projects that bring early learning to life',
                    'Foundation for literacy, communication and social growth',
                ],
                'meta_tags' => ['Playgroup', 'PP1', 'PP2'],
                'sort_order' => 1,
                'is_featured' => true,
            ],
            [
                'title' => 'Primary School',
                'slug' => 'primary',
                'subtitle' => 'Primary School',
                'grades_label' => 'Grade 1 to Grade 6',
                'summary' => 'Primary School builds a strong academic foundation in literacy, numeracy, scientific understanding and critical thinking, alongside character, talent development and practical life skills.',
                'body' => 'Strong academic foundations. Boarding for Grades 4 to 6.',
                'bullets' => [
                    'Strong academic foundation across core learning areas',
                    'Access to library, IT room and equipped laboratory',
                    'Boarding available for Grades 4 to 6, with structured study time and closer academic support',
                    'Arts, sport, clubs and hands-on learning as part of school life',
                ],
                'boarding_note' => 'Boarding for Grades 4 to 6',
                'meta_tags' => ['Grades 1–6', 'Boarding for Grades 4–6'],
                'sort_order' => 2,
                'is_featured' => true,
            ],
            [
                'title' => 'Junior School',
                'slug' => 'junior',
                'subtitle' => 'Junior School',
                'grades_label' => 'Grade 7 to Grade 9',
                'summary' => 'Junior School continues academic progress with greater independence, digital literacy, confident communication and leadership through teamwork and service — preparing learners for the next stage of education.',
                'body' => 'Continued progress with digital literacy and boarding available.',
                'bullets' => [
                    'Continued academic growth with individual care',
                    'Digital literacy through a fully functional computer room',
                    'Hands-on science learning in an equipped laboratory',
                    'Boarding available with modern ablution block, kitchen, dining hall and dormitories',
                ],
                'boarding_note' => 'Boarding available',
                'meta_tags' => ['Grades 7–9', 'Boarding available'],
                'sort_order' => 3,
                'is_featured' => true,
            ],
        ];

        foreach ($programs as $program) {
            Program::query()->updateOrCreate(
                ['slug' => $program['slug']],
                $program + ['is_published' => true],
            );
        }
    }

    private function seedFacilities(): void
    {
        $facilities = [
            ['Classroom building', 'A modern three-storey classroom building supporting day-to-day learning across Pre-School, Primary and Junior School.', 'learning'],
            ['Library', 'Over 1,500 new books to feed young minds.', 'learning'],
            ['IT Room', 'A fully functional computer room supporting hands-on digital learning, creativity and confident, responsible use of technology.', 'learning'],
            ['Equipped Laboratory', 'A well-equipped laboratory for hands-on science learning.', 'learning'],
            ['Hands-on Learning', 'Practical, age-appropriate projects bring lessons to life, building creativity, problem-solving and everyday life skills.', 'learning'],
            ['Creative arts & music', 'Music, singing, dance and drama nurture expression and confidence.', 'arts'],
            ['Sport', 'Soccer builds fitness, teamwork and school spirit.', 'arts'],
            ['Young Farmers', 'Practical skills and care for nature through gardening and related activities.', 'arts'],
            ['Environmental Club', 'Encouraging care for our surroundings through gardening, tree planting and recycling.', 'arts'],
            ['Boarding facilities', 'New facilities include a modern ablution block, a kitchen and dining hall, and dormitories, with structured study time and closer academic support.', 'boarding'],
            ['School Transport', 'Safe school vans and buses to carry our children.', 'transport'],
        ];

        foreach ($facilities as $index => [$title, $description, $category]) {
            FacilityItem::query()->updateOrCreate(
                ['title' => $title],
                compact('description', 'category') + [
                    'sort_order' => $index + 1,
                    'is_published' => true,
                ],
            );
        }
    }

    private function seedGallery(): void
    {
        $images = [
            ['Learning together', 'Learning together', 'uploads/cms/gallery/pupils.jpg', 'Pupils in school uniform standing together'],
            ['Safe school transport', 'Safe school transport', 'uploads/cms/gallery/bus-side.jpg', 'The yellow school bus'],
            ['School van', 'Safe school transport', 'uploads/cms/gallery/bus-pupil.jpg', 'A pupil standing beside the school van'],
            ['The ride back home', 'The ride back home', 'uploads/cms/gallery/ride.jpg', 'Pupils waving from inside the school bus'],
            ['The classroom building', 'The classroom building', 'uploads/cms/gallery/classroom.jpg', 'The classroom building across the school grounds'],
        ];

        foreach ($images as $index => [$title, $caption, $imagePath, $altText]) {
            GalleryImage::query()->updateOrCreate(
                ['image_path' => $imagePath],
                [
                    'title' => $title,
                    'caption' => $caption,
                    'alt_text' => $altText,
                    'sort_order' => $index + 1,
                    'is_published' => true,
                ],
            );
        }
    }

    private function seedPrinciples(): void
    {
        $principles = [
            ['The Love and Fear of God', 'Our foremost guiding principle: placing God at the heart of school life and nurturing faith, humility, compassion and respect for others.'],
            ['Education with Character', 'Nurturing honesty, respect, responsibility and self-discipline alongside academic achievement.'],
            ['A Strong Academic Foundation', 'Building literacy, numeracy, scientific understanding and critical thinking.'],
            ['Hands-on Learning and Life Skills', 'Connecting classroom learning with practical experience to develop creativity, problem-solving, personal organisation and independence.'],
            ['Digital Literacy', 'Developing confident, creative and responsible use of computers and technology.'],
            ['Reading and Confident Communication', 'Fostering a love of books through an engaging library, while strengthening expression through storytelling, presentations and debate.'],
            ['Individual Care and Talent Development', 'Recognising each learner’s strengths, supporting their learning and wellbeing, and developing their talents in music, drama, art and sport.'],
            ['Leadership, Teamwork and Service', 'Building cooperation and leadership through group activities, age-appropriate responsibilities and service to others.'],
            ['Environmental Responsibility', 'Encouraging care for our surroundings through gardening, tree planting and recycling.'],
            ['Partnership with Parents', 'Maintaining regular communication between school and home to support each child’s learning, character and wellbeing.'],
        ];

        foreach ($principles as $index => [$title, $body]) {
            Principle::query()->updateOrCreate(
                ['title' => $title],
                ['body' => $body, 'sort_order' => $index + 1, 'is_published' => true],
            );
        }
    }
}
