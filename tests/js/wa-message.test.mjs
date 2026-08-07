import assert from "node:assert/strict";
import { buildCartMessage, buildDirectMessage, formatRupiah, waLink } from "../../resources/js/wa-message.mjs";

const items = [
    { name: "Gemini Pro", duration: "3 bulan", warranty: "1 bulan", price: 30000, qty: 2 },
    { name: "Capcut Pro", duration: "1 bulan", warranty: "1 bulan", price: 25000, qty: 1 },
];

const cart = buildCartMessage(items);
assert(cart.includes("Halo, aku mau beli akun pro dengan rincian sebagai berikut:"));
assert(cart.includes("1. *Gemini Pro* ×2"));
assert(cart.includes("   Masa aktif 3 bulan · Garansi 1 bulan"));
assert(cart.includes("   - Rp 30.000 × 2 = Rp 60.000"));
assert(cart.includes("2. *Capcut Pro*"));
assert(cart.includes("   - Rp 25.000"));
assert(cart.includes("*Total Rp 85.000*"));
assert(!cart.includes("Bagaimana cara beli nya?"));

const direct = buildDirectMessage({ name: "Gemini Pro", duration: "3 bulan", warranty: "1 bulan", price: 30000 });
assert(direct.includes("*Gemini Pro*\n- Masa aktif 3 bulan\n- garansi 1 bulan\n- Rp 30.000"));
assert(direct.includes("Apakah masih tersedia?"));

assert.equal(formatRupiah(55000), "Rp 55.000");
assert(waLink("6283116545674", "halo").startsWith("https://wa.me/6283116545674?text="));

console.log("OK: js wa-message test pass");
