(function (root) {
  "use strict";

  var WA_NUMBER = "6283116545674";

  function formatRupiah(n) {
    return "Rp " + new Intl.NumberFormat("id-ID").format(n);
  }

  function buildCartMessage(items) {
    var lines = ["Halo, aku mau beli akun pro dengan rincian sebagai berikut:", ""];
    var total = 0;
    items.forEach(function (it, i) {
      var subtotal = it.harga * it.qty;
      total += subtotal;
      lines.push((i + 1) + ". *" + it.nama + "*" + (it.qty > 1 ? " \u00d7" + it.qty : ""));
      lines.push("   Masa aktif " + it.masaAktif + " \u00b7 Garansi " + it.garansi);
      lines.push("   - " + (it.qty > 1 ? formatRupiah(it.harga) + " \u00d7 " + it.qty + " = " + formatRupiah(subtotal) : formatRupiah(it.harga)));
      lines.push("");
    });
    lines.push("*Total " + formatRupiah(total) + "*");
    return lines.join("\n");
  }

  function buildDirectMessage(prod) {
    return [
      "Halo, aku mau Beli akun pro",
      "*" + prod.nama + "*",
      "- Masa aktif " + prod.masaAktif,
      "- garansi " + prod.garansi,
      "- " + formatRupiah(prod.harga),
      "",
      "Apakah masih tersedia?",
    ].join("\n");
  }

  function waLink(text) {
    return "https://wa.me/" + WA_NUMBER + "?text=" + encodeURIComponent(text);
  }

  var api = { formatRupiah: formatRupiah, buildCartMessage: buildCartMessage, buildDirectMessage: buildDirectMessage, waLink: waLink };
  if (typeof module !== "undefined" && module.exports) module.exports = api;
  else root.WA = api;
})(typeof self !== "undefined" ? self : this);