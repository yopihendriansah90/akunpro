<?php

namespace Database\Seeders;

use App\Models\Testimonial;
use Illuminate\Database\Seeder;

class TestimonialSeeder extends Seeder
{
    public function run(): void
    {
        $testimonials = [
            [
                'name' => 'Rizky',
                'role' => 'Mahasiswa',
                'rating' => 5,
                'text' => 'Proses cepat banget, chat WhatsApp langsung dibalas. Gemini Pro-nya aman sampai sekarang, recommended!',
                'available' => true,
                'sort' => 1,
            ],
            [
                'name' => 'Sinta',
                'role' => 'Kreator Konten',
                'rating' => 5,
                'text' => 'CapCut Pro tanpa watermark. Ini langganan bulanan saya, gak pernah kapok.',
                'available' => true,
                'sort' => 2,
            ],
            [
                'name' => 'Andi',
                'role' => 'Freelance Desainer',
                'rating' => 4,
                'text' => 'Canva Pro-nya murah dan masa aktif sesuai janji. Semoga makin banyak promo.',
                'available' => true,
                'sort' => 3,
            ],
        ];

        foreach ($testimonials as $testimonial) {
            Testimonial::updateOrCreate(['name' => $testimonial['name']], $testimonial);
        }
    }
}
