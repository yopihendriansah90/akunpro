export function formatRupiah(n) {
    return "Rp " + new Intl.NumberFormat("id-ID").format(n);
}

export function buildCartMessage(items) {
    const lines = ["Halo, aku mau beli akun pro dengan rincian sebagai berikut:", ""];
    let total = 0;
    items.forEach((it, i) => {
        const subtotal = it.price * it.qty;
        total += subtotal;
        lines.push(`${i + 1}. *${it.name}*${it.qty > 1 ? " ×" + it.qty : ""}`);
        lines.push(`   Masa aktif ${it.duration} · Garansi ${it.warranty}`);
        lines.push(`   - ${it.qty > 1 ? `${formatRupiah(it.price)} × ${it.qty} = ${formatRupiah(subtotal)}` : formatRupiah(it.price)}`);
        lines.push("");
    });
    lines.push(`*Total ${formatRupiah(total)}*`);
    return lines.join("\n");
}

export function buildDirectMessage(product) {
    return [
        "Halo, aku mau Beli akun pro",
        `*${product.name}*`,
        `- Masa aktif ${product.duration}`,
        `- garansi ${product.warranty}`,
        `- ${formatRupiah(product.price)}`,
        "",
        "Apakah masih tersedia?",
    ].join("\n");
}

export function waLink(number, text) {
    return `https://wa.me/${number}?text=${encodeURIComponent(text)}`;
}
