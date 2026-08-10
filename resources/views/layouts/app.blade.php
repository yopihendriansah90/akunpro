<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>{{ $storeName }} — Katalog Akun Pro Premium</title>
    <meta name="description" content="{{ $storeName }} menjual akun pro premium dengan harga terjangkau. Proses cepat via WhatsApp, bergaransi." />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#FFFBF2] font-sans text-slate-800"
      data-store="{{ $storeName }}" data-wa="{{ $whatsappNumber }}">

    @php $home = request()->routeIs('home') ? '' : '/'; @endphp

    <header class="sticky top-0 z-40 bg-white/85 shadow-sm backdrop-blur-lg">
        <div class="mx-auto flex max-w-6xl items-center justify-between gap-3 px-4 py-3">
            <a href="{{ $home }}#beranda" class="flex items-center gap-2.5 text-xl font-extrabold tracking-tight text-slate-900">
                <span class="grid h-10 w-10 place-items-center rounded-2xl bg-gradient-to-br from-amber-400 to-amber-500 text-white shadow-lg shadow-amber-500/30">
                    <span class="material-symbols-rounded">storefront</span>
                </span>
                <span>{{ $storeName }}</span>
            </a>
            <nav class="hidden items-center gap-7 text-sm font-semibold text-slate-500 md:flex">
                <a href="{{ $home }}#beranda" class="hover:text-amber-500">Beranda</a>
                <a href="{{ $home }}#katalog" class="hover:text-amber-500">Katalog</a>
                <a href="{{ $home }}#cara-belanja" class="hover:text-amber-500">Cara Belanja</a>
                <a href="{{ $home }}#tentang" class="hover:text-amber-500">Tentang</a>
            </nav>
            <div class="flex items-center gap-2">
                <a href="https://wa.me/{{ $whatsappNumber }}" target="_blank" rel="noopener" data-analytics-event="whatsapp_click"
                   class="hidden items-center gap-1.5 rounded-full bg-emerald-500 px-4 py-2.5 text-sm font-bold text-white shadow-lg shadow-emerald-500/25 transition hover:bg-emerald-600 sm:flex">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                    Chat Admin
                </a>
                <button id="cartBtn" class="relative grid h-10 w-10 place-items-center rounded-full bg-amber-100 text-amber-600 transition hover:bg-amber-200" aria-label="Buka keranjang">
                    <span class="material-symbols-rounded">shopping_cart</span>
                    <span id="cartBadge" class="absolute -right-1 -top-1 hidden h-5 min-w-5 place-items-center rounded-full bg-slate-900 px-1 text-[11px] font-bold text-white"></span>
                </button>
                <button id="menuBtn" class="grid h-10 w-10 place-items-center rounded-full bg-amber-100 text-amber-600 transition hover:bg-amber-200 md:hidden" aria-label="Buka menu" aria-expanded="false" aria-controls="mobileMenu">
                    <span class="material-symbols-rounded">menu</span>
                </button>
            </div>
        </div>
        <div id="mobileMenu" class="mobile-menu border-t border-amber-100 bg-white px-6 md:hidden" aria-hidden="true">
            <nav class="flex flex-col gap-3 text-sm font-semibold text-slate-600">
                <a href="{{ $home }}#beranda" class="flex items-center gap-2"><span class="material-symbols-rounded text-amber-500">home</span> Beranda</a>
                <a href="{{ $home }}#katalog" class="flex items-center gap-2"><span class="material-symbols-rounded text-amber-500">apps</span> Katalog</a>
                <a href="{{ $home }}#cara-belanja" class="flex items-center gap-2"><span class="material-symbols-rounded text-amber-500">shopping_bag</span> Cara Belanja</a>
                <a href="{{ $home }}#tentang" class="flex items-center gap-2"><span class="material-symbols-rounded text-amber-500">info</span> Tentang</a>
                <a href="https://wa.me/{{ $whatsappNumber }}" target="_blank" rel="noopener" data-analytics-event="whatsapp_click" class="mt-2 flex items-center justify-center gap-2 rounded-full bg-emerald-500 px-4 py-3 font-bold text-white">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                    Chat Admin
                </a>
            </nav>
        </div>
    </header>

    <main>
        @yield('content')
    </main>

    <footer class="bg-slate-900 py-10 text-center text-sm text-slate-400">
        <p class="inline-flex items-center gap-2 text-lg font-extrabold text-white">
            <span class="grid h-8 w-8 place-items-center rounded-xl bg-gradient-to-br from-amber-400 to-amber-500 text-white">
                <span class="material-symbols-rounded text-base">storefront</span>
            </span>
            {{ $storeName }}
        </p>
        <p class="mt-3 inline-flex items-center gap-1.5">
            <span class="material-symbols-rounded text-base text-emerald-400">call</span>
            WhatsApp: <a class="text-amber-400 hover:underline" href="https://wa.me/{{ $whatsappNumber }}">{{ $whatsappNumber }}</a>
        </p>
        <p class="mt-3">© <span id="year"></span> {{ $storeName }}. Semua hak dilindungi.</p>
    </footer>

    <a href="https://wa.me/{{ $whatsappNumber }}" target="_blank" rel="noopener" data-analytics-event="whatsapp_click"
       class="fixed bottom-5 right-5 z-30 grid h-14 w-14 place-items-center rounded-full bg-emerald-500 text-white shadow-xl shadow-emerald-500/30 transition hover:scale-105 hover:bg-emerald-600" aria-label="Chat WhatsApp">
        <svg class="h-7 w-7" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
    </a>

    <div id="cartOverlay" class="fixed inset-0 z-40 hidden bg-black/50 backdrop-blur-sm"></div>
    <aside id="cartDrawer" class="fixed right-0 top-0 z-50 flex h-full w-full max-w-md translate-x-full flex-col bg-[#FFFBF2] shadow-2xl transition-transform duration-300">
        <div class="flex items-center justify-between border-b border-amber-100 bg-white px-5 py-4">
            <h3 class="flex items-center gap-2 text-lg font-extrabold text-slate-900">
                <span class="material-symbols-rounded text-amber-500">shopping_cart</span> Keranjang Belanja
            </h3>
            <button id="cartClose" class="grid h-9 w-9 place-items-center rounded-full bg-amber-100 text-amber-600 transition hover:bg-amber-200" aria-label="Tutup keranjang">
                <span class="material-symbols-rounded">close</span>
            </button>
        </div>
        <div id="cartItems" class="flex-1 overflow-y-auto px-4 py-4"></div>
        <div id="cartFooter" class="hidden border-t border-amber-100 bg-white px-5 py-4">
            <div class="flex items-center justify-between text-lg font-extrabold">
                <span class="text-slate-500">Total</span>
                <span id="cartTotal" class="text-amber-500">Rp 0</span>
            </div>
            <button id="checkoutBtn" data-analytics-event="cart_checkout" class="mt-3 flex w-full items-center justify-center gap-2 rounded-full bg-emerald-500 py-3.5 font-bold text-white shadow-lg shadow-emerald-500/25 transition hover:bg-emerald-600">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                Checkout via WhatsApp
            </button>
        </div>
    </aside>

    <div id="toast" class="fixed bottom-24 left-1/2 z-50 hidden max-w-[88vw] -translate-x-1/2 items-center gap-1.5 whitespace-nowrap rounded-full bg-slate-900 px-4 py-2.5 text-xs font-semibold text-white shadow-xl">
        <span class="material-symbols-rounded text-sm text-emerald-400">check_circle</span>
        <span id="toastMsg"></span>
    </div>
</body>
</html>
