<?php

namespace Tests\Unit;

use App\Models\Product;
use App\Support\WhatsApp;
use PHPUnit\Framework\TestCase;

class WhatsAppTest extends TestCase
{
    public function test_format_rupiah(): void
    {
        $this->assertSame('Rp 55.000', WhatsApp::formatRupiah(55000));
        $this->assertSame('Rp 1.500.000', WhatsApp::formatRupiah(1500000));
    }

    public function test_direct_message_format(): void
    {
        $product = new Product([
            'name' => 'Gemini Pro',
            'duration' => '3 bulan',
            'warranty' => '1 bulan',
            'price' => 30000,
        ]);

        $message = WhatsApp::directMessage($product);

        $this->assertStringContainsString("Halo, aku mau Beli akun pro", $message);
        $this->assertStringContainsString("*Gemini Pro*\n- Masa aktif 3 bulan\n- garansi 1 bulan\n- Rp 30.000", $message);
        $this->assertStringContainsString("Apakah masih tersedia?", $message);
    }
}
