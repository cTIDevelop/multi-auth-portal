<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name'        => 'Technology',
                'slug'        => 'technology',
                'description' => 'Software, hardware, and digital services',
                'icon'        => 'heroicon-o-computer-desktop',
                'color'       => '#6366f1',
                'sort_order'  => 1,
                'children'    => [
                    ['name' => 'Software Development', 'slug' => 'software-development', 'sort_order' => 1],
                    ['name' => 'Cloud Services',        'slug' => 'cloud-services',        'sort_order' => 2],
                    ['name' => 'Cybersecurity',         'slug' => 'cybersecurity',          'sort_order' => 3],
                    ['name' => 'IT Support',            'slug' => 'it-support',             'sort_order' => 4],
                ],
            ],
            [
                'name'        => 'Marketing',
                'slug'        => 'marketing',
                'description' => 'Digital marketing, branding, and advertising',
                'icon'        => 'heroicon-o-megaphone',
                'color'       => '#f59e0b',
                'sort_order'  => 2,
                'children'    => [
                    ['name' => 'SEO & SEM',        'slug' => 'seo-sem',        'sort_order' => 1],
                    ['name' => 'Social Media',     'slug' => 'social-media',   'sort_order' => 2],
                    ['name' => 'Content Creation', 'slug' => 'content-creation','sort_order' => 3],
                    ['name' => 'Email Marketing',  'slug' => 'email-marketing', 'sort_order' => 4],
                ],
            ],
            [
                'name'        => 'Design',
                'slug'        => 'design',
                'description' => 'Graphic design, UI/UX, and creative services',
                'icon'        => 'heroicon-o-paint-brush',
                'color'       => '#ec4899',
                'sort_order'  => 3,
                'children'    => [
                    ['name' => 'UI/UX Design',    'slug' => 'ui-ux-design',   'sort_order' => 1],
                    ['name' => 'Graphic Design',  'slug' => 'graphic-design', 'sort_order' => 2],
                    ['name' => 'Branding',        'slug' => 'branding',       'sort_order' => 3],
                    ['name' => 'Video Production','slug' => 'video-production','sort_order' => 4],
                ],
            ],
            [
                'name'        => 'Consulting',
                'slug'        => 'consulting',
                'description' => 'Business and professional consulting services',
                'icon'        => 'heroicon-o-briefcase',
                'color'       => '#10b981',
                'sort_order'  => 4,
                'children'    => [
                    ['name' => 'Business Strategy', 'slug' => 'business-strategy', 'sort_order' => 1],
                    ['name' => 'Financial Advisory', 'slug' => 'financial-advisory', 'sort_order' => 2],
                    ['name' => 'HR & Recruitment',  'slug' => 'hr-recruitment',    'sort_order' => 3],
                    ['name' => 'Legal Services',    'slug' => 'legal-services',    'sort_order' => 4],
                ],
            ],
            [
                'name'        => 'Education',
                'slug'        => 'education',
                'description' => 'Training, courses, and educational services',
                'icon'        => 'heroicon-o-academic-cap',
                'color'       => '#3b82f6',
                'sort_order'  => 5,
                'children'    => [
                    ['name' => 'Online Courses',   'slug' => 'online-courses',  'sort_order' => 1],
                    ['name' => 'Corporate Training','slug' => 'corporate-training','sort_order' => 2],
                    ['name' => 'Certifications',   'slug' => 'certifications',  'sort_order' => 3],
                ],
            ],
            [
                'name'        => 'Products',
                'slug'        => 'products',
                'description' => 'Physical and digital products',
                'icon'        => 'heroicon-o-cube',
                'color'       => '#8b5cf6',
                'sort_order'  => 6,
                'children'    => [
                    ['name' => 'Hardware',       'slug' => 'hardware',       'sort_order' => 1],
                    ['name' => 'Software Tools', 'slug' => 'software-tools', 'sort_order' => 2],
                    ['name' => 'Accessories',    'slug' => 'accessories',    'sort_order' => 3],
                ],
            ],
        ];

        foreach ($categories as $categoryData) {
            $children = $categoryData['children'] ?? [];
            unset($categoryData['children']);

            $parent = Category::firstOrCreate(
                ['slug' => $categoryData['slug']],
                array_merge($categoryData, ['is_active' => true])
            );

            foreach ($children as $child) {
                Category::firstOrCreate(
                    ['slug' => $child['slug']],
                    array_merge($child, [
                        'parent_id'  => $parent->id,
                        'is_active'  => true,
                        'color'      => $categoryData['color'] ?? null,
                    ])
                );
            }
        }

        $this->command->info('✅ Categories seeded (6 parents, 24 subcategories).');
    }
}
