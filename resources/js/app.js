import "./bootstrap";
import { formatRupiah, buildCartMessage, buildDirectMessage, waLink } from "./wa-message.mjs";

const $ = (id) => document.getElementById(id);

const storeName = document.body.dataset.store;
const whatsappNumber = document.body.dataset.wa;
const { products, categories, cartProducts = products } = window.KASIRAKUN;

const cartKey = `kasirakun-cart-${whatsappNumber}`;
let cart = JSON.parse(localStorage.getItem(cartKey) || "[]").map((item) => ({
    ...item,
    id: String(item.id),
}));
let activeCat = "Semua";
let search = "";
let page = 1;
const perPage = 10;

const productById = {};
cartProducts.forEach((p) => (productById[String(p.id)] = p));

function escapeHtml(value) {
    return String(value ?? "")
        .replaceAll("&", "&amp;")
        .replaceAll("<", "&lt;")
        .replaceAll(">", "&gt;")
        .replaceAll('"', "&quot;")
        .replaceAll("'", "&#039;");
}

const icon = (name, cls = "") =>
    `<span class="material-symbols-rounded ${escapeHtml(cls)}">${escapeHtml(name)}</span>`;

function categoryIcon(cat) {
    const found = categories.find((c) => c.name === cat);
    return icon(found ? found.icon : "label", "text-base");
}

function stars(r, size = "1rem") {
    let s = "";
    for (let i = 0; i < 5; i++) {
        s +=
            i < r
                ? `<span class="material-symbols-rounded fill text-amber-400" style="font-size:${size}">star</span>`
                : `<span class="material-symbols-rounded text-slate-200" style="font-size:${size}">star</span>`;
    }
    return s;
}

function waIcon(cls = "h-4 w-4") {
    return `<svg class="${cls}" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>`;
}

function discount(p) {
    if (!p.original_price || p.original_price <= p.price) return 0;
    return Math.round((1 - p.price / p.original_price) * 100);
}

function cardHTML(p) {
    const disc = discount(p);
    const id = escapeHtml(p.id);
    const name = escapeHtml(p.name);
    const duration = escapeHtml(p.duration);
    const warranty = escapeHtml(p.warranty);
    const badge = escapeHtml(p.badge);
    const image = escapeHtml(p.image);
    const directUrl = escapeHtml(waLink(whatsappNumber, buildDirectMessage(p)));
    return (
        '<article class="group flex flex-col overflow-hidden rounded-lg bg-white shadow-sm ring-1 ring-amber-900/5 transition hover:-translate-y-1 hover:shadow-lg hover:ring-amber-300">' +
        `<div class="relative aspect-square cursor-pointer overflow-hidden bg-gradient-to-br from-amber-100 via-amber-50 to-violet-100" data-open="${id}">` +
        (p.image
            ? `<img src="${image}" alt="${name}" loading="lazy" class="h-full w-full object-cover transition duration-300 group-hover:scale-110" />`
            : `<div class="grid h-full w-full place-items-center transition duration-300 group-hover:scale-110">` + icon(p.icon, "text-5xl text-amber-500") + "</div>") +
        (disc ? `<span class="absolute left-1.5 top-1.5 rounded-md bg-[#ee4d2d] px-1.5 py-0.5 text-[10px] font-bold text-white">-${disc}%</span>` : "") +
        (p.badge ? `<span class="absolute right-1.5 top-1.5 rounded-md bg-slate-900/80 px-1.5 py-0.5 text-[9px] font-semibold text-white">${badge}</span>` : "") +
        "</div>" +
        '<div class="flex flex-1 flex-col p-2.5">' +
        '<div class="flex items-center gap-1">' +
        stars(p.rating, "0.85rem") +
        `<span class="text-[11px] text-slate-400">(${p.rating})</span>` +
        "</div>" +
        `<h3 class="line-clamp-2 mt-1 cursor-pointer text-xs font-medium leading-snug text-slate-700 transition hover:text-[#ee4d2d]" data-open="${id}">${name}</h3>` +
        '<div class="mt-1 flex flex-col gap-1 text-[10px] leading-tight text-slate-400">' +
        '<span class="flex min-w-0 items-start gap-1">' + icon("schedule", "shrink-0 text-[11px] text-slate-400") + `<span class="min-w-0 break-words">Masa aktif ${duration}</span></span>` +
        '<span class="flex min-w-0 items-start gap-1">' + icon("verified_user", "shrink-0 text-[11px] text-slate-400") + `<span class="min-w-0 break-words">Garansi ${warranty}</span></span>` +
        "</div>" +
        '<div class="mt-auto pt-2">' +
        '<div class="flex items-baseline gap-1">' +
        `<span class="text-base font-extrabold tracking-tight text-[#ee4d2d]">${formatRupiah(p.price)}</span>` +
        (p.original_price ? `<span class="text-[10px] text-slate-400 line-through">${formatRupiah(p.original_price)}</span>` : "") +
        "</div>" +
        '<div class="mt-1.5 flex gap-1.5">' +
        `<button aria-label="Tambah ${name} ke keranjang" class="grid h-8 w-8 place-items-center rounded-full bg-slate-900 text-white transition hover:bg-slate-700 font-bold sm:flex sm:flex-1 sm:w-auto sm:h-auto sm:justify-center sm:items-center sm:gap-1 sm:py-1.5 sm:text-[11px]" data-cart="${id}">` +
        icon("add_shopping_cart", "text-[8px]") + '<span class="hidden sm:inline">Keranjang</span></button>' +
        `<a aria-label="Beli ${name} via WhatsApp" class="flex flex-1 items-center justify-center gap-1 rounded-full bg-emerald-500 py-1.5 text-[11px] font-bold text-white shadow transition hover:bg-emerald-600" href="${directUrl}" target="_blank" rel="noopener">` +
        waIcon("h-3 w-3") + " WhatsApp</a>" +
        "</div>" +
        "</div>" +
        "</div>" +
        "</article>"
    );
}

function renderTabs() {
    if (!$("categoryTabs")) return;
    const names = ["Semua", ...categories.map((c) => c.name)];
    $("categoryTabs").innerHTML = names
        .map((c) => {
            const active = c === activeCat
                ? "bg-gradient-to-r from-amber-400 to-amber-500 text-white shadow-md shadow-amber-500/30"
                : "bg-white text-slate-600 hover:bg-amber-100";
            return `<button class="flex items-center gap-1.5 whitespace-nowrap rounded-full px-5 py-2 text-sm font-bold ring-1 ring-amber-100 transition ${active}" data-cat="${escapeHtml(c)}">${categoryIcon(c)}${escapeHtml(c)}</button>`;
        })
        .join("");
}

function filtered() {
    const q = search.toLowerCase();
    return products.filter((p) => {
        const inCat = activeCat === "Semua" || p.category === activeCat;
        const match = (p.name + " " + p.category + " " + p.description).toLowerCase().includes(q);
        return inCat && match;
    });
}

function renderProducts() {
    if (!$("productGrid")) return;
    const list = filtered();
    const totalPages = Math.max(1, Math.ceil(list.length / perPage));
    if (page > totalPages) page = totalPages;
    const start = (page - 1) * perPage;
    $("productGrid").innerHTML = list.slice(start, start + perPage).map(cardHTML).join("");
    $("emptyState").classList.toggle("hidden", list.length > 0);
    renderPagination(totalPages, list.length);
}

function renderRelated() {
    if (!$("relatedGrid")) return;
    const id = window.KASIRAKUN.pageProductId;
    const related = id ? products.filter((p) => p.id !== id) : products;
    $("relatedGrid").innerHTML = related.map(cardHTML).join("");
}

function renderPagination(totalPages, total) {
    const el = $("pagination");
    if (!el) return;
    if (total === 0 || totalPages <= 1) {
        el.innerHTML = "";
        return;
    }
    const btn = (label, p, extra = "", disabled = false) =>
        `<button class="grid h-9 min-w-9 place-items-center rounded-full px-2 text-sm font-bold transition ${disabled ? "cursor-not-allowed text-slate-300" : "text-slate-600 hover:bg-amber-100"} ${extra}" ${disabled ? "disabled" : `data-page="${p}"`}>${label}</button>`;
    const pages = [];
    pages.push(btn('<span class="material-symbols-rounded text-base">chevron_left</span>', page - 1, "", page === 1));
    for (let i = 1; i <= totalPages; i++) {
        if (totalPages > 7 && i > 2 && i < totalPages - 1 && Math.abs(i - page) > 1) {
            if (i === 3 || i === totalPages - 2) pages.push('<span class="px-1 text-slate-300">…</span>');
            continue;
        }
        pages.push(
            i === page
                ? `<button class="grid h-9 min-w-9 place-items-center rounded-full bg-gradient-to-r from-amber-400 to-amber-500 px-2 text-sm font-bold text-white shadow-md shadow-amber-500/30" disabled>${i}</button>`
                : btn(i, i)
        );
    }
    pages.push(btn('<span class="material-symbols-rounded text-base">chevron_right</span>', page + 1, "", page === totalPages));
    el.innerHTML = pages.join("");
    el.dataset.total = total;
}

function saveCart() {
    localStorage.setItem(cartKey, JSON.stringify(cart));
}

function cartQty() {
    return cart.reduce((t, it) => t + it.qty, 0);
}

function badge() {
    const q = cartQty();
    const b = $("cartBadge");
    b.textContent = q;
    b.classList.toggle("hidden", q === 0);
    b.classList.toggle("grid", q > 0);
}

function renderCart() {
    const validCart = cart.filter((item) => productById[item.id]);
    if (validCart.length !== cart.length) {
        cart = validCart;
        saveCart();
    }
    badge();
    const wrap = $("cartItems");
    const footer = $("cartFooter");
    if (cart.length === 0) {
        wrap.innerHTML =
            '<div class="grid place-items-center gap-2 py-16 text-slate-400">' +
            icon("shopping_cart", "text-6xl") +
            '<p class="text-sm font-bold text-slate-500">Keranjang masih kosong</p>' +
            '<p class="text-xs">Yuk pilih produk dulu!</p></div>';
        footer.classList.add("hidden");
        return;
    }
    footer.classList.remove("hidden");
    let total = 0;
    wrap.innerHTML = cart
        .map((it) => {
            const p = productById[it.id];
            if (!p) return "";
            const subtotal = p.price * it.qty;
            total += subtotal;
            return (
                '<div class="mb-3 flex items-center gap-3 rounded-3xl bg-white p-3.5 shadow-sm ring-1 ring-amber-100">' +
                `<span class="grid h-12 w-12 shrink-0 place-items-center rounded-2xl bg-gradient-to-br from-amber-100 to-violet-100 text-amber-500">${icon(p.icon, "text-2xl")}</span>` +
                '<div class="min-w-0 flex-1">' +
                `<p class="truncate text-sm font-extrabold text-slate-900">${escapeHtml(p.name)}</p>` +
                '<p class="mt-0.5 flex items-center gap-1 truncate text-xs text-slate-500">' +
                icon("schedule", "text-xs text-amber-500") + escapeHtml(p.duration) +
                '<span class="mx-0.5 text-slate-300">·</span>' +
                icon("verified_user", "text-xs text-emerald-500") + escapeHtml(p.warranty) +
                `</p><p class="mt-0.5 text-xs text-slate-400">${formatRupiah(p.price)} / unit</p>` +
                "</div>" +
                '<div class="flex shrink-0 flex-col items-end gap-1.5">' +
                '<div class="flex items-center gap-1 rounded-full bg-amber-50 ring-1 ring-amber-100">' +
                `<button class="grid h-7 w-7 place-items-center rounded-full text-amber-600 transition hover:bg-amber-200" data-qty="-1" data-id="${it.id}" aria-label="Kurangi">${icon("remove", "text-sm")}</button>` +
                `<span class="w-5 text-center text-sm font-extrabold text-slate-900">${it.qty}</span>` +
                `<button class="grid h-7 w-7 place-items-center rounded-full text-amber-600 transition hover:bg-amber-200" data-qty="1" data-id="${it.id}" aria-label="Tambah">${icon("add", "text-sm")}</button>` +
                "</div>" +
                '<div class="flex items-center gap-1.5">' +
                `<span class="text-sm font-extrabold text-amber-500">${formatRupiah(subtotal)}</span>` +
                `<button class="grid h-7 w-7 place-items-center rounded-full text-red-300 transition hover:bg-red-50 hover:text-red-500" data-remove="${it.id}" aria-label="Hapus">${icon("delete", "text-base")}</button>` +
                "</div></div></div>"
            );
        })
        .join("");
    $("cartTotal").textContent = formatRupiah(total);
}

function addToCart(id) {
    id = String(id);
    const existing = cart.find((it) => it.id === id);
    if (existing) existing.qty += 1;
    else cart.push({ id, qty: 1 });
    saveCart();
    renderCart();
    toast(`${productById[id].name} ditambahkan ke keranjang`);
}

function changeQty(id, delta) {
    id = String(id);
    const item = cart.find((it) => it.id === id);
    if (!item) return;
    item.qty += delta;
    if (item.qty <= 0) cart = cart.filter((it) => it.id !== id);
    saveCart();
    renderCart();
}

function removeFromCart(id) {
    id = String(id);
    cart = cart.filter((it) => it.id !== id);
    saveCart();
    renderCart();
}

function toast(msg) {
    const t = $("toast");
    $("toastMsg").textContent = msg;
    t.classList.remove("hidden");
    t.classList.add("flex");
    clearTimeout(t._timer);
    t._timer = setTimeout(() => {
        t.classList.add("hidden");
        t.classList.remove("flex");
    }, 2200);
}

function openCart() {
    $("cartDrawer").classList.remove("translate-x-full");
    $("cartOverlay").classList.remove("hidden");
    document.body.style.overflow = "hidden";
}

function closeCart() {
    $("cartDrawer").classList.add("translate-x-full");
    $("cartOverlay").classList.add("hidden");
    document.body.style.overflow = "";
}

function initTestimonialCarousel() {
    const row = $("testimonialRow");
    if (!row) return;

    let isDown = false;
    let startX = 0;
    let startLeft = 0;

    row.addEventListener("pointerdown", (e) => {
        if (e.pointerType !== "mouse") return;
        isDown = true;
        startX = e.clientX;
        startLeft = row.scrollLeft;
        row.classList.add("cursor-grabbing");
        row.classList.remove("cursor-grab");
    });
    row.addEventListener("pointermove", (e) => {
        if (!isDown) return;
        e.preventDefault();
        row.scrollLeft = startLeft - (e.clientX - startX);
    });
    const end = () => {
        isDown = false;
        row.classList.remove("cursor-grabbing");
        row.classList.add("cursor-grab");
    };
    row.addEventListener("pointerup", end);
    row.addEventListener("pointerleave", end);
    row.classList.add("cursor-grab");
}

function bindEvents() {
    document.addEventListener("click", (e) => {
        const openBtn = e.target.closest("[data-open]");
        if (openBtn) {
            window.location.href = `/produk/${openBtn.getAttribute("data-open")}`;
            return;
        }
        const cartBtn = e.target.closest("[data-cart]");
        if (cartBtn) {
            addToCart(cartBtn.getAttribute("data-cart"));
            return;
        }
    });

    if ($("searchInput")) {
        $("searchInput").addEventListener("input", (e) => {
            search = e.target.value.trim();
            page = 1;
            $("searchClear").classList.toggle("hidden", search === "");
            renderProducts();
        });
        $("searchClear").addEventListener("click", () => {
            $("searchInput").value = "";
            $("searchInput").dispatchEvent(new Event("input"));
            $("searchInput").focus();
        });
        $("searchGo").addEventListener("click", () => {
            document.getElementById("katalog").scrollIntoView({ behavior: "smooth" });
        });
        $("searchInput").addEventListener("keydown", (e) => {
            if (e.key === "Enter") $("searchGo").click();
        });
    }

    if ($("menuBtn") && $("mobileMenu")) {
        const menuButton = $("menuBtn");
        const mobileMenu = $("mobileMenu");
        const menuIcon = menuButton.querySelector(".material-symbols-rounded");

        const setMenuOpen = (isOpen) => {
            mobileMenu.classList.toggle("is-open", isOpen);
            menuButton.setAttribute("aria-expanded", String(isOpen));
            menuButton.setAttribute("aria-label", isOpen ? "Tutup menu" : "Buka menu");
            mobileMenu.setAttribute("aria-hidden", String(!isOpen));
            if (menuIcon) menuIcon.textContent = isOpen ? "close" : "menu";
        };

        menuButton.addEventListener("click", () =>
            setMenuOpen(!mobileMenu.classList.contains("is-open"))
        );
        document.addEventListener("click", (event) => {
            if (!mobileMenu.classList.contains("is-open")) return;
            if (mobileMenu.contains(event.target) || menuButton.contains(event.target)) return;
            setMenuOpen(false);
        });
        document.addEventListener("keydown", (event) => {
            if (event.key === "Escape" && mobileMenu.classList.contains("is-open")) {
                setMenuOpen(false);
                menuButton.focus();
            }
        });
        mobileMenu.querySelectorAll("a").forEach((a) =>
            a.addEventListener("click", () => setMenuOpen(false))
        );
    }

    if ($("categoryTabs")) {
        $("categoryTabs").addEventListener("click", (e) => {
            const btn = e.target.closest("[data-cat]");
            if (!btn) return;
            activeCat = btn.getAttribute("data-cat");
            page = 1;
            renderTabs();
            renderProducts();
        });
    }

    if ($("pagination")) {
        $("pagination").addEventListener("click", (e) => {
            const btn = e.target.closest("[data-page]");
            if (!btn) return;
            page = parseInt(btn.getAttribute("data-page"), 10);
            renderProducts();
            document.getElementById("katalog").scrollIntoView({ behavior: "smooth", block: "start" });
        });
    }

    $("cartBtn").addEventListener("click", openCart);
    $("cartClose").addEventListener("click", closeCart);
    $("cartOverlay").addEventListener("click", closeCart);

    $("cartItems").addEventListener("click", (e) => {
        const qtyBtn = e.target.closest("[data-qty]");
        if (qtyBtn) return changeQty(qtyBtn.getAttribute("data-id"), parseInt(qtyBtn.getAttribute("data-qty"), 10));
        const rmBtn = e.target.closest("[data-remove]");
        if (rmBtn) return removeFromCart(rmBtn.getAttribute("data-remove"));
    });

    $("checkoutBtn").addEventListener("click", () => {
        const items = cart.map((it) => {
            const p = productById[it.id];
            return { name: p.name, duration: p.duration, warranty: p.warranty, price: p.price, qty: it.qty };
        });
        window.open(waLink(whatsappNumber, buildCartMessage(items)), "_blank");
        cart = [];
        saveCart();
        renderCart();
        closeCart();
    });

    document.addEventListener("keydown", (e) => {
        if (e.key === "Escape") closeCart();
    });
}

$("year").textContent = new Date().getFullYear();
renderTabs();
renderProducts();
renderRelated();
renderCart();
initTestimonialCarousel();
bindEvents();
