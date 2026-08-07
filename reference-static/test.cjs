const assert = require("assert");
const fs = require("fs");
const vm = require("vm");

const ctx = { module: { exports: {} } };
vm.runInNewContext(fs.readFileSync("wa.js", "utf8"), ctx);
const { buildCartMessage, buildDirectMessage, formatRupiah } = ctx.module.exports;

const items = [
  { nama: "Gemini Pro", masaAktif: "3 bulan", garansi: "1 bulan", harga: 30000, qty: 2 },
  { nama: "Capcut Pro", masaAktif: "1 bulan", garansi: "1 bulan", harga: 25000, qty: 1 },
];

const cartMsg = buildCartMessage(items);
assert(cartMsg.includes("Halo, aku mau beli akun pro dengan rincian sebagai berikut:"));
assert(cartMsg.includes("1. *Gemini Pro* ×2"));
assert(cartMsg.includes("   Masa aktif 3 bulan · Garansi 1 bulan"));
assert(cartMsg.includes("   - Rp 30.000 × 2 = Rp 60.000"));
assert(cartMsg.includes("2. *Capcut Pro*"));
assert(cartMsg.includes("   - Rp 25.000"));
assert(cartMsg.includes("*Total Rp 85.000*"));
assert(!cartMsg.includes("Bagaimana cara beli nya?"));

const direct = buildDirectMessage({ nama: "Gemini Pro", masaAktif: "3 bulan", garansi: "1 bulan", harga: 30000 });
assert(direct.includes("Halo, aku mau Beli akun pro"));
assert(direct.includes("*Gemini Pro*\n- Masa aktif 3 bulan\n- garansi 1 bulan\n- Rp 30.000"));
assert(direct.includes("Apakah masih tersedia?"));

assert.strictEqual(formatRupiah(55000), "Rp 55.000");

console.log("OK: semua test pass");
