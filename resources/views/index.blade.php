@extends('layouts.app')

@section('content')
{{-- HERO --}}
<section id="beranda" class="relative overflow-hidden px-4 pb-14 pt-16 sm:pt-24">
    <div class="pointer-events-none absolute -right-24 -top-24 h-80 w-80 rounded-full bg-violet-200/40 blur-3xl"></div>
    <div class="pointer-events-none absolute -left-24 bottom-0 h-80 w-80 rounded-full bg-amber-200/50 blur-3xl"></div>
    <div class="relative mx-auto max-w-3xl text-center">
        <h1 class="text-3xl font-extrabold leading-tight tracking-tight text-slate-900 sm:text-5xl">
            Dapatkan Akun Pro dengan
            <span class="bg-gradient-to-r from-amber-500 to-violet-500 bg-clip-text text-transparent">Harga Terjangkau</span>
        </h1>
        <p class="mx-auto mt-4 max-w-xl text-sm text-slate-500 sm:text-base">
            Gemini Pro, CapCut Pro, Canva Pro, ChatGPT Plus, dan banyak lagi. Proses cepat via WhatsApp, bergaransi.
        </p>
        <div class="mx-auto mt-8 max-w-xl">
            <div class="flex items-center gap-2 rounded-full bg-white p-2 shadow-xl shadow-amber-500/15 ring-1 ring-amber-100">
                <span class="material-symbols-rounded ml-2 text-amber-400">search</span>
                <input id="searchInput" type="text" placeholder="Cari produk... mis. Gemini Pro"
                       class="w-full bg-transparent px-2 py-2 text-sm outline-none placeholder:text-slate-400" />
                <button id="searchClear" class="hidden text-slate-400 hover:text-slate-600" aria-label="Bersihkan pencarian">
                    <span class="material-symbols-rounded text-lg">close</span>
                </button>
                <button id="searchGo" class="rounded-full bg-gradient-to-r from-amber-400 to-amber-500 px-5 py-2.5 text-sm font-bold text-white shadow-lg shadow-amber-500/30 transition hover:brightness-105">
                    Cari
                </button>
            </div>
        </div>
        <div class="mt-7 flex flex-wrap items-center justify-center gap-2 text-xs font-semibold text-slate-600">
            <span class="inline-flex items-center gap-1.5 rounded-full bg-white px-3.5 py-1.5 ring-1 ring-amber-100"><span class="material-symbols-rounded text-base text-amber-500">bolt</span> Proses cepat</span>
            <span class="inline-flex items-center gap-1.5 rounded-full bg-white px-3.5 py-1.5 ring-1 ring-amber-100"><span class="material-symbols-rounded text-base text-emerald-500">verified_user</span> Bergaransi</span>
            <span class="inline-flex items-center gap-1.5 rounded-full bg-white px-3.5 py-1.5 ring-1 ring-amber-100">
                <svg class="h-3.5 w-3.5 text-emerald-500" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                Via WhatsApp
            </span>
        </div>
    </div>
</section>

{{-- KATALOG --}}
<section id="katalog" class="mx-auto max-w-6xl scroll-mt-20 px-4 py-10">
    <div>
        <p class="flex items-center gap-1.5 text-xs font-bold uppercase tracking-widest text-violet-600">
            <span class="material-symbols-rounded text-sm">apps</span> Katalog
        </p>
        <h2 class="mt-1 text-2xl font-extrabold tracking-tight text-slate-900 sm:text-3xl">Pilih Produk Favoritmu</h2>
        <p class="mt-1 text-sm text-slate-500">Tambah ke keranjang, atau langsung order via WhatsApp.</p>
    </div>

    <div id="categoryTabs" class="scrollbar-hide mt-6 flex gap-2 overflow-x-auto pb-1"></div>

    <div id="productGrid" class="mt-6 grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5"></div>
    <div id="pagination" class="mt-8 flex items-center justify-center gap-1.5"></div>
    <p id="emptyState" class="mt-10 hidden text-center text-sm text-slate-400">Produk tidak ditemukan.</p>
</section>

{{-- CARA BELANJA --}}
<section id="cara-belanja" class="bg-white py-12">
    <div class="mx-auto max-w-6xl px-4">
        <div class="text-center">
            <p class="flex items-center justify-center gap-1.5 text-xs font-bold uppercase tracking-widest text-violet-600">
                <span class="material-symbols-rounded text-sm">check_circle</span> Mudah
            </p>
            <h2 class="mt-1 text-2xl font-extrabold tracking-tight text-slate-900 sm:text-3xl">Cara Belanja</h2>
        </div>
        <div class="mt-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            @php
                $steps = [
                    ['icon' => 'shopping_bag', 'color' => 'from-amber-400 to-amber-500 shadow-amber-500/30', 'title' => 'Pilih Produk', 'desc' => 'Lihat katalog dan pilih akun pro yang kamu mau.'],
                    ['icon' => 'add_shopping_cart', 'color' => 'from-violet-400 to-violet-500 shadow-violet-500/30', 'title' => 'Keranjang / Order', 'desc' => 'Atur jumlah di keranjang lalu checkout, atau langsung beli.'],
                    ['icon' => 'chat', 'color' => 'from-emerald-400 to-emerald-500 shadow-emerald-500/30', 'title' => 'Chat WhatsApp', 'desc' => 'Rincian pesanan otomatis terisi di chat WhatsApp.'],
                    ['icon' => 'verified', 'color' => 'from-slate-700 to-slate-900 shadow-slate-900/30', 'title' => 'Aktif & Bergaransi', 'desc' => 'Akun dikirim setelah pembayaran, dengan garansi aktif.'],
                ];
            @endphp
            @foreach ($steps as $step)
                <div class="rounded-3xl bg-[#FFFBF2] p-6 ring-1 ring-amber-100 transition hover:-translate-y-1 hover:shadow-lg hover:shadow-amber-500/10">
                    <span class="grid h-12 w-12 place-items-center rounded-2xl bg-gradient-to-br text-white shadow-lg {{ $step['color'] }}">
                        <span class="material-symbols-rounded">{{ $step['icon'] }}</span>
                    </span>
                    <h3 class="mt-4 font-bold text-slate-900">{{ $step['title'] }}</h3>
                    <p class="mt-1 text-sm leading-relaxed text-slate-500">{{ $step['desc'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- TESTIMONI --}}
<section id="testimoni" class="mx-auto max-w-6xl px-4 py-12">
    <div class="text-center">
        <p class="flex items-center justify-center gap-1.5 text-xs font-bold uppercase tracking-widest text-violet-600">
            <span class="material-symbols-rounded text-sm">chat</span> Testimoni
        </p>
        <h2 class="mt-1 text-2xl font-extrabold tracking-tight text-slate-900 sm:text-3xl">Kata Mereka</h2>
    </div>
    <div id="testimonialRow" class="scrollbar-hide mt-8 flex gap-4 overflow-x-auto pb-2 snap-x snap-mandatory">
        @forelse ($testimonials as $t)
            <div class="snap-center w-[85%] shrink-0 rounded-[2rem] bg-white p-6 shadow-sm ring-1 ring-amber-900/5 transition hover:-translate-y-1 hover:shadow-lg hover:shadow-amber-500/10 sm:w-[400px]">
                <div class="flex items-center gap-0.5">
                    @for ($i = 1; $i <= 5; $i++)
                        <span class="material-symbols-rounded {{ $i <= $t->rating ? 'fill text-amber-400' : 'text-slate-200' }}" style="font-size:1rem">star</span>
                    @endfor
                </div>
                <p class="mt-3 text-sm leading-relaxed text-slate-600">"{{ $t->text }}"</p>
                <p class="mt-4 flex items-center gap-2.5 font-bold text-slate-900">
                    <span class="grid h-10 w-10 place-items-center rounded-full bg-gradient-to-br from-amber-400 to-violet-500 text-white">
                        <span class="material-symbols-rounded text-lg">person</span>
                    </span>
                    <span>{{ $t->name }}<span class="block text-xs font-medium text-slate-400">{{ $t->role }}</span></span>
                </p>
            </div>
        @empty
            <p class="w-full text-center text-sm text-slate-400">Belum ada testimoni.</p>
        @endforelse
    </div>
</section>

{{-- TENTANG --}}
<section id="tentang" class="bg-white py-12">
    <div class="mx-auto max-w-6xl px-4">
        <div class="grid items-center gap-10 sm:grid-cols-2">
            <div>
                <p class="flex items-center gap-1.5 text-xs font-bold uppercase tracking-widest text-violet-600">
                    <span class="material-symbols-rounded text-sm">storefront</span> Tentang Kami
                </p>
                <h2 class="mt-1 text-2xl font-extrabold tracking-tight text-slate-900 sm:text-3xl">Tentang {{ $storeName }}</h2>
                <p class="mt-4 text-sm leading-relaxed text-slate-600">
                    {{ $storeName }} menjual akun-akun pro premium dengan harga murah dan proses cepat.
                    Semua pesanan diproses melalui WhatsApp agar komunikasi langsung dan jelas.
                    Setiap produk memiliki masa aktif dan garansi, jadi kamu belanja dengan tenang.
                </p>
                <ul class="mt-5 space-y-2.5 text-sm text-slate-600">
                    <li class="flex items-center gap-2.5"><span class="material-symbols-rounded text-emerald-500">check_circle</span> Proses 24 jam, balas cepat</li>
                    <li class="flex items-center gap-2.5"><span class="material-symbols-rounded text-emerald-500">check_circle</span> Harga bersahabat & transparan</li>
                    <li class="flex items-center gap-2.5"><span class="material-symbols-rounded text-emerald-500">check_circle</span> Garansi setiap produk</li>
                </ul>
                <a href="https://wa.me/{{ $whatsappNumber }}" target="_blank" rel="noopener"
                   class="mt-7 inline-flex items-center gap-2 rounded-full bg-gradient-to-r from-amber-400 to-amber-500 px-7 py-3.5 font-bold text-white shadow-lg shadow-amber-500/30 transition hover:brightness-105">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                    Hubungi Kami di WhatsApp
                </a>
            </div>
            <div class="relative grid place-items-center">
                <div class="absolute h-56 w-56 rounded-full bg-amber-100 blur-2xl"></div>
                <div class="relative grid h-56 w-56 place-items-center rounded-[2.5rem] bg-gradient-to-br from-amber-400 to-violet-500 shadow-2xl shadow-amber-500/30">
                    <span class="material-symbols-rounded text-white" style="font-size:7rem">storefront</span>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    window.KASIRAKUN = {
        products: @json($products),
        categories: @json($categories),
    };
</script>
@endsection
