(function () {
  "use strict";

  var WA = window.WA;
  var formatRupiah = WA.formatRupiah;
  var buildCartMessage = WA.buildCartMessage;
  var buildDirectMessage = WA.buildDirectMessage;
  var waLink = WA.waLink;

  var products = window.PRODUCTS;
  var testimonials = window.TESTIMONIALS;
  var cart = JSON.parse(localStorage.getItem("kasirakun-cart") || "[]");
  var activeCat = "Semua";
  var search = "";

  var $ = function (id) { return document.getElementById(id); };
  var productById = {};
  products.forEach(function (p) { productById[p.id] = p; });

  function icon(name, cls) {
    return '<span class="material-symbols-rounded ' + (cls || "") + '">' + name + "</span>";
  }

  function waIcon(cls) {
    return '<svg class="' + (cls || "h-4 w-4") + '" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">' +
      '<path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>' +
      "</svg>";
  }

  function categories() {
    var cats = ["Semua"];
    products.forEach(function (p) {
      if (cats.indexOf(p.kategori) === -1) cats.push(p.kategori);
    });
    return cats;
  }

  function filtered() {
    var q = search.toLowerCase();
    return products.filter(function (p) {
      var inCat = activeCat === "Semua" || p.kategori === activeCat;
      var match = (p.nama + " " + p.kategori + " " + p.deskripsi).toLowerCase().indexOf(q) !== -1;
      return inCat && match;
    });
  }

  function stars(r) {
    var s = "";
    for (var i = 0; i < 5; i++) {
      s += i < r
        ? '<span class="material-symbols-rounded fill text-amber-400" style="font-size:1rem">star</span>'
        : '<span class="material-symbols-rounded text-slate-200" style="font-size:1rem">star</span>';
    }
    return s;
  }

  function discount(p) {
    return Math.round((1 - p.harga / p.hargaCoret) * 100);
  }

  function cardHTML(p) {
    return (
      '<article class="group flex flex-col rounded-[2rem] bg-white p-5 shadow-sm ring-1 ring-amber-900/5 transition hover:-translate-y-1.5 hover:shadow-xl hover:shadow-amber-500/10 hover:ring-amber-300">' +
        '<div class="relative grid cursor-pointer place-items-center overflow-hidden rounded-3xl bg-gradient-to-br from-amber-100 via-amber-50 to-violet-100 py-9 transition group-hover:from-amber-200" data-open="' + p.id + '">' +
          icon(p.ikon, 'text-6xl text-amber-500 transition group-hover:scale-110 group-hover:text-amber-600') +
          '<span class="absolute right-3 top-3 rounded-full bg-white/85 px-2.5 py-1 text-[11px] font-extrabold text-violet-600 shadow-sm">-' + discount(p) + '%</span>' +
        "</div>" +
        '<div class="mt-4 flex items-center justify-between">' +
          '<span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-bold text-amber-700">' + p.kategori + "</span>" +
          '<span class="flex items-center gap-0.5">' + stars(p.rating) + "</span>" +
        "</div>" +
        '<h3 class="mt-2.5 cursor-pointer text-lg font-extrabold tracking-tight text-slate-900 transition hover:text-amber-500" data-open="' + p.id + '">' + p.nama + "</h3>" +
        '<p class="line-clamp-2 mt-1 text-sm text-slate-500">' + p.deskripsi + "</p>" +
        '<div class="mt-3 flex items-center gap-1.5 text-xs font-semibold text-slate-500">' +
          icon('schedule', 'text-sm text-amber-500') + p.masaAktif +
          '<span class="mx-0.5 text-slate-300">·</span>' +
          icon('verified_user', 'text-sm text-emerald-500') + p.garansi +
        "</div>" +
        '<div class="mt-3 flex items-baseline gap-2">' +
          '<span class="text-xl font-extrabold tracking-tight text-amber-500">' + formatRupiah(p.harga) + "</span>" +
          '<span class="text-sm text-slate-400 line-through">' + formatRupiah(p.hargaCoret) + "</span>" +
        "</div>" +
        '<div class="mt-4 flex gap-2">' +
          '<button class="flex flex-1 items-center justify-center gap-1.5 rounded-full bg-slate-900 py-2.5 text-sm font-bold text-white transition hover:bg-slate-700" data-cart="' + p.id + '">' +
            icon('add_shopping_cart', 'text-base') + " Keranjang</button>" +
          '<a class="flex flex-1 items-center justify-center gap-1.5 rounded-full bg-emerald-500 py-2.5 text-sm font-bold text-white shadow-lg shadow-emerald-500/20 transition hover:bg-emerald-600" href="' + waLink(buildDirectMessage(p)) + '" target="_blank" rel="noopener">' +
            waIcon("h-4 w-4") + " WhatsApp</a>" +
        "</div>" +
      "</article>"
    );
  }

  function categoryIcon(cat) {
    var map = { "Semua": "apps", "AI Chat": "chat", "Video Editor": "video_settings", "Desain": "palette", "Musik": "music_note", "Streaming": "tv" };
    return icon(map[cat] || "label", "text-base");
  }

  function renderTabs() {
    $("categoryTabs").innerHTML = categories().map(function (c) {
      var active = c === activeCat
        ? "bg-gradient-to-r from-amber-400 to-amber-500 text-white shadow-md shadow-amber-500/30"
        : "bg-white text-slate-600 hover:bg-amber-100";
      return '<button class="flex items-center gap-1.5 whitespace-nowrap rounded-full px-5 py-2 text-sm font-bold ring-1 ring-amber-100 transition ' + active + '" data-cat="' + c + '">' +
        categoryIcon(c) + c + "</button>";
    }).join("");
  }

  function renderProducts() {
    var list = filtered();
    $("productGrid").innerHTML = list.map(cardHTML).join("");
    $("emptyState").classList.toggle("hidden", list.length > 0);
  }

  function saveCart() {
    localStorage.setItem("kasirakun-cart", JSON.stringify(cart));
  }

  function cartQty() {
    return cart.reduce(function (t, it) { return t + it.qty; }, 0);
  }

  function badge() {
    var q = cartQty();
    var b = $("cartBadge");
    b.textContent = q;
    b.classList.toggle("hidden", q === 0);
    b.classList.toggle("grid", q > 0);
  }

  function renderCart() {
    badge();
    var wrap = $("cartItems");
    var footer = $("cartFooter");
    if (cart.length === 0) {
      wrap.innerHTML =
        '<div class="grid place-items-center gap-2 py-16 text-slate-400">' +
          icon('shopping_cart', 'text-6xl') +
          '<p class="text-sm font-bold text-slate-500">Keranjang masih kosong</p>' +
          '<p class="text-xs">Yuk pilih produk dulu!</p>' +
        "</div>";
      footer.classList.add("hidden");
      return;
    }
    footer.classList.remove("hidden");
    var total = 0;
    wrap.innerHTML = cart.map(function (it) {
      var p = productById[it.id];
      var subtotal = p.harga * it.qty;
      total += subtotal;
      return (
        '<div class="mb-3 flex items-center gap-3 rounded-3xl bg-white p-3.5 shadow-sm ring-1 ring-amber-100">' +
          '<span class="grid h-12 w-12 shrink-0 place-items-center rounded-2xl bg-gradient-to-br from-amber-100 to-violet-100 text-amber-500">' + icon(p.ikon, "text-2xl") + "</span>" +
          '<div class="min-w-0 flex-1">' +
            '<p class="truncate text-sm font-extrabold text-slate-900">' + p.nama + "</p>" +
            '<p class="mt-0.5 flex items-center gap-1 truncate text-xs text-slate-500">' +
              icon('schedule', 'text-xs text-amber-500') + p.masaAktif +
              '<span class="mx-0.5 text-slate-300">·</span>' +
              icon('verified_user', 'text-xs text-emerald-500') + p.garansi +
            "</p>" +
            '<p class="mt-0.5 text-xs text-slate-400">' + formatRupiah(p.harga) + " / unit</p>" +
          "</div>" +
          '<div class="flex shrink-0 flex-col items-end gap-1.5">' +
            '<div class="flex items-center gap-1 rounded-full bg-amber-50 ring-1 ring-amber-100">' +
              '<button class="grid h-7 w-7 place-items-center rounded-full text-amber-600 transition hover:bg-amber-200" data-qty="-1" data-id="' + it.id + '" aria-label="Kurangi">' + icon('remove', 'text-sm') + "</button>" +
              '<span class="w-5 text-center text-sm font-extrabold text-slate-900">' + it.qty + "</span>" +
              '<button class="grid h-7 w-7 place-items-center rounded-full text-amber-600 transition hover:bg-amber-200" data-qty="1" data-id="' + it.id + '" aria-label="Tambah">' + icon('add', 'text-sm') + "</button>" +
            "</div>" +
            '<div class="flex items-center gap-1.5">' +
              '<span class="text-sm font-extrabold text-amber-500">' + formatRupiah(subtotal) + "</span>" +
              '<button class="grid h-7 w-7 place-items-center rounded-full text-red-300 transition hover:bg-red-50 hover:text-red-500" data-remove="' + it.id + '" aria-label="Hapus">' + icon('delete', 'text-base') + "</button>" +
            "</div>" +
          "</div>" +
        "</div>"
      );
    }).join("");
    $("cartTotal").textContent = formatRupiah(total);
  }

  function addToCart(id) {
    var existing = cart.filter(function (it) { return it.id === id; })[0];
    if (existing) existing.qty += 1;
    else cart.push({ id: id, qty: 1 });
    saveCart();
    renderCart();
    toast(productById[id].nama + " ditambahkan ke keranjang");
  }

  function changeQty(id, delta) {
    var item = cart.filter(function (it) { return it.id === id; })[0];
    if (!item) return;
    item.qty += delta;
    if (item.qty <= 0) cart = cart.filter(function (it) { return it.id !== id; });
    saveCart();
    renderCart();
  }

  function removeFromCart(id) {
    cart = cart.filter(function (it) { return it.id !== id; });
    saveCart();
    renderCart();
  }

  function toast(msg) {
    var t = $("toast");
    $("toastMsg").textContent = msg;
    t.classList.remove("hidden");
    t.classList.add("flex");
    clearTimeout(t._timer);
    t._timer = setTimeout(function () { t.classList.add("hidden"); t.classList.remove("flex"); }, 2200);
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

  function modalHTML(p) {
    return (
      '<div class="flex items-center justify-between">' +
        '<h3 class="text-2xl font-extrabold tracking-tight text-slate-900">' + p.nama + "</h3>" +
        '<button id="modalClose" class="grid h-9 w-9 place-items-center rounded-full bg-amber-100 text-amber-600 transition hover:bg-amber-200" aria-label="Tutup">' + icon('close') + "</button>" +
      "</div>" +
      '<div class="relative mt-4 grid place-items-center overflow-hidden rounded-3xl bg-gradient-to-br from-amber-100 via-amber-50 to-violet-100 py-12">' +
        icon(p.ikon, 'text-7xl text-amber-500') +
        '<span class="absolute right-3 top-3 rounded-full bg-white/85 px-2.5 py-1 text-[11px] font-extrabold text-violet-600 shadow-sm">-' + discount(p) + '%</span>' +
      "</div>" +
      '<div class="mt-4 flex items-center justify-between">' +
        '<span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-bold text-amber-700">' + p.kategori + "</span>" +
        '<span class="flex items-center gap-0.5">' + stars(p.rating) + "</span>" +
      "</div>" +
      '<div class="mt-3 flex items-baseline gap-2">' +
        '<span class="text-3xl font-extrabold tracking-tight text-amber-500">' + formatRupiah(p.harga) + "</span>" +
        '<span class="text-slate-400 line-through">' + formatRupiah(p.hargaCoret) + "</span>" +
      "</div>" +
      '<div class="mt-3 flex flex-wrap gap-x-5 gap-y-1.5 text-sm font-semibold text-slate-600">' +
        '<span class="flex items-center gap-1.5">' + icon('schedule', 'text-amber-500') + "Masa aktif: " + p.masaAktif + "</span>" +
        '<span class="flex items-center gap-1.5">' + icon('verified_user', 'text-emerald-500') + "Garansi: " + p.garansi + "</span>" +
      "</div>" +
      '<p class="mt-4 text-sm leading-relaxed text-slate-600">' + p.deskripsi + "</p>" +
      '<div class="mt-6 flex gap-2">' +
        '<button class="flex flex-1 items-center justify-center gap-1.5 rounded-full bg-slate-900 py-3 text-sm font-bold text-white transition hover:bg-slate-700" data-cart="' + p.id + '">' +
          icon('add_shopping_cart', 'text-base') + " Keranjang</button>" +
        '<a class="flex flex-1 items-center justify-center gap-1.5 rounded-full bg-emerald-500 py-3 text-sm font-bold text-white shadow-lg shadow-emerald-500/20 transition hover:bg-emerald-600" href="' + waLink(buildDirectMessage(p)) + '" target="_blank" rel="noopener">' +
          waIcon("h-4 w-4") + " WhatsApp</a>" +
      "</div>"
    );
  }

  function openModal(id) {
    $("modal").innerHTML = modalHTML(productById[id]);
    $("modalOverlay").classList.remove("hidden");
    $("modalOverlay").classList.add("flex");
    document.body.style.overflow = "hidden";
  }

  function closeModal() {
    $("modalOverlay").classList.add("hidden");
    $("modalOverlay").classList.remove("flex");
    document.body.style.overflow = "";
  }

  function renderTestimonials() {
    $("testimonialGrid").innerHTML = testimonials.map(function (t) {
      return (
        '<div class="rounded-[2rem] bg-white p-6 shadow-sm ring-1 ring-amber-900/5 transition hover:-translate-y-1 hover:shadow-lg hover:shadow-amber-500/10">' +
          '<div class="flex items-center gap-0.5">' + stars(t.rating) + "</div>" +
          '<p class="mt-3 text-sm leading-relaxed text-slate-600">"' + t.text + '"</p>' +
          '<p class="mt-4 flex items-center gap-2.5 font-bold text-slate-900">' +
            '<span class="grid h-10 w-10 place-items-center rounded-full bg-gradient-to-br from-amber-400 to-violet-500 text-white">' + icon('person', 'text-lg') + "</span>" +
            '<span>' + t.nama + '<span class="block text-xs font-medium text-slate-400">' + t.role + "</span></span>" +
          "</p>" +
        "</div>"
      );
    }).join("");
  }

  function bindEvents() {
    $("searchInput").addEventListener("input", function (e) {
      search = e.target.value.trim();
      $("searchClear").classList.toggle("hidden", search === "");
      renderProducts();
    });
    $("searchClear").addEventListener("click", function () {
      $("searchInput").value = "";
      $("searchInput").dispatchEvent(new Event("input"));
      $("searchInput").focus();
    });
    $("searchGo").addEventListener("click", function () {
      document.getElementById("katalog").scrollIntoView({ behavior: "smooth" });
    });
    $("searchInput").addEventListener("keydown", function (e) {
      if (e.key === "Enter") $("searchGo").click();
    });

    $("menuBtn").addEventListener("click", function () {
      $("mobileMenu").classList.toggle("hidden");
    });
    $("mobileMenu").querySelectorAll("a[href^='#']").forEach(function (a) {
      a.addEventListener("click", function () { $("mobileMenu").classList.add("hidden"); });
    });

    $("categoryTabs").addEventListener("click", function (e) {
      var btn = e.target.closest("[data-cat]");
      if (!btn) return;
      activeCat = btn.getAttribute("data-cat");
      renderTabs();
      renderProducts();
    });

    $("productGrid").addEventListener("click", function (e) {
      var openBtn = e.target.closest("[data-open]");
      if (openBtn) return openModal(openBtn.getAttribute("data-open"));
      var cartBtn = e.target.closest("[data-cart]");
      if (cartBtn) return addToCart(cartBtn.getAttribute("data-cart"));
    });

    $("cartBtn").addEventListener("click", openCart);
    $("cartClose").addEventListener("click", closeCart);
    $("cartOverlay").addEventListener("click", closeCart);

    $("cartItems").addEventListener("click", function (e) {
      var qtyBtn = e.target.closest("[data-qty]");
      if (qtyBtn) return changeQty(qtyBtn.getAttribute("data-id"), parseInt(qtyBtn.getAttribute("data-qty"), 10));
      var rmBtn = e.target.closest("[data-remove]");
      if (rmBtn) return removeFromCart(rmBtn.getAttribute("data-remove"));
    });

    $("checkoutBtn").addEventListener("click", function () {
      var items = cart.map(function (it) {
        var p = productById[it.id];
        return { nama: p.nama, masaAktif: p.masaAktif, garansi: p.garansi, harga: p.harga, qty: it.qty };
      });
      window.open(waLink(buildCartMessage(items)), "_blank");
      cart = [];
      saveCart();
      renderCart();
      closeCart();
    });

    $("modalOverlay").addEventListener("click", function (e) {
      if (e.target === $("modalOverlay") || e.target.closest("#modalClose")) closeModal();
    });
    $("modal").addEventListener("click", function (e) {
      var cartBtn = e.target.closest("[data-cart]");
      if (cartBtn) {
        addToCart(cartBtn.getAttribute("data-cart"));
        closeModal();
      }
    });

    document.addEventListener("keydown", function (e) {
      if (e.key === "Escape") { closeModal(); closeCart(); }
    });
  }

  $("year").textContent = new Date().getFullYear();
  renderTabs();
  renderProducts();
  renderCart();
  renderTestimonials();
  bindEvents();
})();
