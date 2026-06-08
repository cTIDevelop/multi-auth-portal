<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Provider;
use App\Models\Service;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ProviderSeeder extends Seeder
{
    public function run(): void
    {
        $providers = [
            [
                'name'           => 'Ana Martínez',
                'email'          => 'provider1@example.com',
                'password'       => Hash::make('password'),
                'company_name'   => 'TechSolutions MX',
                'contact_person' => 'Ana Martínez',
                'phone'          => '+52 55 1234 5678',
                'website'        => 'https://techsolutionsmx.com',
                'tax_id'         => 'MAMA850101XYZ',
                'description'    => 'Leading software development and cloud services provider in Mexico.',
                'address'        => 'Av. Insurgentes Sur 1234',
                'city'           => 'Ciudad de México',
                'state'          => 'CDMX',
                'country'        => 'México',
                'postal_code'    => '03810',
                'is_active'      => true,
                'is_verified'    => true,
                'verified_at'    => now()->subDays(10),
                'services'       => [
                    ['category_slug' => 'software-development', 'name' => 'Custom Web App Development', 'price' => 50000, 'price_type' => 'fixed', 'is_featured' => true],
                    ['category_slug' => 'cloud-services',       'name' => 'AWS Infrastructure Setup',   'price' => 800,   'price_type' => 'hourly'],
                    ['category_slug' => 'it-support',           'name' => 'Monthly IT Support Plan',    'price' => 5000,  'price_type' => 'fixed'],
                ],
                'products'       => [
                    ['category_slug' => 'software-tools', 'name' => 'Project Management License', 'price' => 299,  'stock' => 999, 'sku' => 'PML-001'],
                    ['category_slug' => 'software-tools', 'name' => 'API Monitoring Tool',        'price' => 149,  'stock' => 500, 'sku' => 'AMT-001'],
                ],
            ],
            [
                'name'           => 'Carlos Rodríguez',
                'email'          => 'provider2@example.com',
                'password'       => Hash::make('password'),
                'company_name'   => 'Creativa Digital',
                'contact_person' => 'Carlos Rodríguez',
                'phone'          => '+52 33 9876 5432',
                'website'        => 'https://creativadigital.mx',
                'tax_id'         => 'ROCC900215ABC',
                'description'    => 'Full-service digital marketing and design agency based in Guadalajara.',
                'address'        => 'Blvd. Puerta de Hierro 4965',
                'city'           => 'Guadalajara',
                'state'          => 'Jalisco',
                'country'        => 'México',
                'postal_code'    => '45116',
                'is_active'      => true,
                'is_verified'    => true,
                'verified_at'    => now()->subDays(5),
                'services'       => [
                    ['category_slug' => 'seo-sem',         'name' => 'SEO Audit & Strategy',    'price' => 8000,  'price_type' => 'fixed', 'is_featured' => true],
                    ['category_slug' => 'social-media',    'name' => 'Social Media Management', 'price' => 12000, 'price_type' => 'fixed'],
                    ['category_slug' => 'content-creation','name' => 'Blog Content Package',    'price' => 4500,  'price_type' => 'fixed'],
                    ['category_slug' => 'ui-ux-design',    'name' => 'UX Research & Wireframes','price' => null,  'price_type' => 'quote'],
                ],
                'products'       => [
                    ['category_slug' => 'accessories', 'name' => 'Branded Merch Bundle',   'price' => 850,  'compare_price' => 1200, 'stock' => 50, 'sku' => 'BMB-001'],
                    ['category_slug' => 'accessories', 'name' => 'Corporate Stationery Kit','price' => 450,  'stock' => 30,  'sku' => 'CSK-001'],
                ],
            ],
            [
                'name'           => 'Sofía López',
                'email'          => 'provider3@example.com',
                'password'       => Hash::make('password'),
                'company_name'   => 'Capacita Pro',
                'contact_person' => 'Sofía López',
                'phone'          => '+52 81 5555 7890',
                'website'        => 'https://capacitapro.mx',
                'tax_id'         => 'LOLS880320DEF',
                'description'    => 'Corporate training and professional development specialists.',
                'address'        => 'Av. Constitución 300',
                'city'           => 'Monterrey',
                'state'          => 'Nuevo León',
                'country'        => 'México',
                'postal_code'    => '64000',
                'is_active'      => true,
                'is_verified'    => false,
                'services'       => [
                    ['category_slug' => 'corporate-training', 'name' => 'Leadership Workshop (2 days)', 'price' => 15000, 'price_type' => 'fixed', 'duration_minutes' => 960, 'is_featured' => true],
                    ['category_slug' => 'online-courses',     'name' => 'Project Management Online',   'price' => 1800,  'price_type' => 'fixed', 'duration_minutes' => 600],
                    ['category_slug' => 'certifications',     'name' => 'PMP Exam Preparation',        'price' => 7500,  'price_type' => 'fixed'],
                ],
                'products'       => [
                    ['category_slug' => 'software-tools', 'name' => 'Training Materials PDF Bundle', 'price' => 350, 'stock' => 999, 'sku' => 'TMB-001'],
                    ['category_slug' => 'accessories',    'name' => 'Workshop Participant Kit',       'price' => 280, 'stock' => 100, 'sku' => 'WPK-001'],
                ],
            ],
            [
                'name'           => 'Miguel Torres',
                'email'          => 'provider4@example.com',
                'password'       => Hash::make('password'),
                'company_name'   => 'SecureNet Consulting',
                'contact_person' => 'Miguel Torres',
                'phone'          => '+52 55 9000 1111',
                'website'        => 'https://securenet.mx',
                'tax_id'         => 'TOMM750830GHI',
                'description'    => 'Cybersecurity consulting and managed security services.',
                'address'        => 'Paseo de la Reforma 505',
                'city'           => 'Ciudad de México',
                'state'          => 'CDMX',
                'country'        => 'México',
                'postal_code'    => '06600',
                'is_active'      => false, // Inactive provider example
                'is_verified'    => true,
                'verified_at'    => now()->subDays(30),
                'services'       => [
                    ['category_slug' => 'cybersecurity', 'name' => 'Penetration Testing',    'price' => null,  'price_type' => 'quote'],
                    ['category_slug' => 'cybersecurity', 'name' => 'Security Audit',         'price' => 25000, 'price_type' => 'fixed'],
                    ['category_slug' => 'it-support',    'name' => '24/7 SOC Monitoring',    'price' => 18000, 'price_type' => 'fixed'],
                ],
                'products'       => [
                    ['category_slug' => 'hardware', 'name' => 'Hardware Security Key (FIDO2)', 'price' => 650, 'compare_price' => 800, 'stock' => 0, 'sku' => 'HSK-001'],
                    ['category_slug' => 'hardware', 'name' => 'Enterprise Firewall Appliance', 'price' => 15000, 'stock' => 5, 'sku' => 'EFA-001'],
                ],
            ],
        ];

        foreach ($providers as $data) {
            $services  = $data['services'] ?? [];
            $products  = $data['products'] ?? [];
            $verifiedAt = $data['verified_at'] ?? null;
            unset($data['services'], $data['products'], $data['verified_at']);

            $provider = Provider::firstOrCreate(
                ['email' => $data['email']],
                array_merge($data, ['verified_at' => $verifiedAt])
            );

            // Assign role
            if (! $provider->hasRole('provider')) {
                $provider->assignRole('provider');
            }

            // Create services
            foreach ($services as $serviceData) {
                $categorySlug = $serviceData['category_slug'];
                unset($serviceData['category_slug']);

                $category = Category::where('slug', $categorySlug)->first();

                Service::firstOrCreate(
                    ['provider_id' => $provider->id, 'name' => $serviceData['name']],
                    array_merge($serviceData, [
                        'provider_id'       => $provider->id,
                        'category_id'       => $category?->id,
                        'slug'              => Str::slug($serviceData['name']) . '-' . $provider->id,
                        'short_description' => 'Professional ' . strtolower($serviceData['name']) . ' by ' . $provider->company_name,
                        'description'       => '<p>High-quality ' . strtolower($serviceData['name']) . ' tailored to your business needs. Contact us for details.</p>',
                        'is_active'         => true,
                        'is_featured'       => $serviceData['is_featured'] ?? false,
                        'duration_minutes'  => $serviceData['duration_minutes'] ?? null,
                    ])
                );
            }

            // Create products
            foreach ($products as $productData) {
                $categorySlug = $productData['category_slug'];
                unset($productData['category_slug']);

                $category = Category::where('slug', $categorySlug)->first();
                $stock = $productData['stock'] ?? 0;
                unset($productData['stock']);

                Product::firstOrCreate(
                    ['provider_id' => $provider->id, 'name' => $productData['name']],
                    array_merge($productData, [
                        'provider_id'       => $provider->id,
                        'category_id'       => $category?->id,
                        'slug'              => Str::slug($productData['name']) . '-' . $provider->id,
                        'short_description' => $productData['name'] . ' from ' . $provider->company_name,
                        'stock_quantity'    => $stock,
                        'is_active'         => true,
                        'compare_price'     => $productData['compare_price'] ?? null,
                    ])
                );
            }
        }

        $this->command->info('✅ Providers seeded (4 providers, with services and products).');
        $this->command->line('   provider1@example.com / password');
        $this->command->line('   provider2@example.com / password');
        $this->command->line('   provider3@example.com / password');
        $this->command->line('   provider4@example.com / password (inactive)');
    }
}
