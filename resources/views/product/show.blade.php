@extends('layouts.app')

@php
    $p = $product;
    $disc = $p->original_price && $p->original_price > $p->price
        ? (int) round((1 - $p->price / $p->original_price) * 100)
        : 0;
    $productArray = [
        'id' => $p->id,
        'name' => $p->name,
        'category' => $p->category->name,
        'price' => $p->price,
        'original_price' => $p->original_price,
        'duration' => $p->duration,
        'warranty' => $p->warranty,
        'icon' => $p->icon,
        'rating' => $p->rating,
        'badge' => $p->badge,
        'description' => strip_tags($p->description),
    ];
@endphp

@section('content')
<div class="mx-auto max-w-6xl px-4 py-8">
    <nav class="flex flex-wrap items-center gap-1.5 text-xs font-semibold text-slate-400">
        <a href="/" class="hover:text-amber-500">Beranda</a>
        <span class="material-symbols-rounded text-sm">chevron_right</span>
        <a href="/#katalog" class="hover:text-amber-500">Katalog</a>
        <span class="material-symbols-rounded text-sm">chevron_right</span>
        <span class="text-slate-600">{{ $p->name }}</span>
    </nav>

    <div class="mt-6 grid gap-8 lg:grid-cols-2">
        <div class="relative aspect-square overflow-hidden rounded-[2rem] bg-gradient-to-br from-amber-100 via-amber-50 to-violet-100 shadow-lg ring-1 ring-amber-100">
            @if ($p->getImageUrl())
                <img src="{{ $p->getImageUrl() }}" alt="{{ $p->name }}" class="h-full w-full object-cover" />
            @else
                <div class="grid h-full w-full place-items-center">
                    <span class="material-symbols-rounded text-amber-500" style="font-size:9rem">{{ $p->icon }}</span>
                </div>
            @endif
            @if ($disc > 0)
                <span class="absolute left-4 top-4 rounded-md bg-[#ee4d2d] px-2.5 py-1 text-sm font-bold text-white">-{{ $disc }}%</span>
            @endif
            @if ($p->badge)
                <span class="absolute right-4 top-4 rounded-md bg-slate-900/80 px-2.5 py-1 text-xs font-semibold text-white">{{ $p->badge }}</span>
            @endif
        </div>

        <div class="flex flex-col">
            <span class="inline-flex w-fit items-center gap-1.5 rounded-full bg-amber-100 px-3 py-1 text-xs font-bold text-amber-700">
                <span class="material-symbols-rounded text-sm">{{ $p->category->icon }}</span>
                {{ $p->category->name }}
            </span>
            <h1 class="mt-3 text-2xl font-extrabold tracking-tight text-slate-900 sm:text-3xl">{{ $p->name }}</h1>
            <div class="mt-2 flex items-center gap-2 text-sm">
                <span class="flex items-center gap-0.5">
                    @for ($i = 1; $i <= 5; $i++)
                        <span class="material-symbols-rounded {{ $i <= $p->rating ? 'fill text-amber-400' : 'text-slate-200' }}" style="font-size:1rem">star</span>
                    @endfor
                </span>
                <span class="text-slate-400">({{ $p->rating }})</span>
            </div>

            <div class="mt-4 flex items-baseline gap-2.5">
                <span class="text-3xl font-extrabold tracking-tight text-[#ee4d2d]">{{ number_format($p->price, 0, ',', '.') }}</span>
                @if ($p->original_price)
                    <span class="text-slate-400 line-through">Rp {{ number_format($p->original_price, 0, ',', '.') }}</span>
                @endif
            </div>
            <p class="mt-1 text-xs font-semibold text-slate-400">Harga dalam Rupiah (Rp)</p>

            <div class="mt-4 flex flex-wrap gap-2 text-sm font-semibold text-slate-600">
                <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-50 px-3.5 py-2 ring-1 ring-amber-100">
                    <span class="material-symbols-rounded text-amber-500">schedule</span> Masa aktif: {{ $p->duration }}
                </span>
                <span class="inline-flex items-center gap-1.5 rounded-full bg-green-50 px-3.5 py-2 ring-1 ring-green-100">
                    <span class="material-symbols-rounded text-emerald-500">verified_user</span> Garansi: {{ $p->warranty }}
                </span>
            </div>

            <div class="rich-content mt-4 text-sm leading-relaxed text-slate-600">{!! $p->renderRichContent('description') !!}</div>

            <div class="mt-4 flex flex-wrap gap-2 text-xs font-semibold text-slate-500">
                <span class="inline-flex items-center gap-1 rounded-full bg-white px-3 py-1.5 ring-1 ring-amber-100"><span class="material-symbols-rounded text-base text-amber-500">bolt</span> Proses cepat</span>
                <span class="inline-flex items-center gap-1 rounded-full bg-white px-3 py-1.5 ring-1 ring-amber-100"><span class="material-symbols-rounded text-base text-emerald-500">verified_user</span> Bergaransi</span>
                <span class="inline-flex items-center gap-1 rounded-full bg-white px-3 py-1.5 ring-1 ring-amber-100"><span class="material-symbols-rounded text-base text-violet-500">support_agent</span> Bantuan responsif</span>
            </div>

            <div class="mt-6 flex gap-3">
                <button data-cart="{{ $p->id }}"
                        class="flex flex-1 items-center justify-center gap-2 rounded-full bg-slate-900 py-3.5 text-sm font-bold text-white transition hover:bg-slate-700">
                    <span class="material-symbols-rounded text-lg">add_shopping_cart</span> Tambah ke Keranjang
                </button>
                <a href="{{ \App\Support\WhatsApp::directLink($p) }}" target="_blank" rel="noopener" data-analytics-event="whatsapp_click" data-product-id="{{ $p->id }}"
                   class="flex flex-1 items-center justify-center gap-2 rounded-full bg-emerald-500 py-3.5 text-sm font-bold text-white shadow-lg shadow-emerald-500/20 transition hover:bg-emerald-600">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                    Beli via WhatsApp
                </a>
            </div>
        </div>
    </div>

    @if ($related->isNotEmpty())
        <section class="mt-14">
            <h2 class="text-xl font-extrabold tracking-tight text-slate-900">Produk Serupa</h2>
            <p class="mt-1 text-sm text-slate-500">Kategori {{ $p->category->name }} yang mungkin kamu suka.</p>
            <div id="relatedGrid" class="mt-5 grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5"></div>
        </section>
    @endif
</div>

<script>
    window.KASIRAKUN = {
        products: @json([$productArray, ...$related]),
        cartProducts: @json($cartProducts),
        categories: @json([]),
        pageProductId: @json($p->id),
    };
</script>
@endsection
