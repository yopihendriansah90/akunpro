<x-dynamic-component :component="'filament-panels::page'">
    <style>
        .analytics-page { display: grid; gap: 1.5rem; }
        .analytics-toolbar { display: flex; align-items: end; justify-content: space-between; gap: 1.5rem; padding: 1.25rem; border: 1px solid rgb(228 228 231); border-radius: .75rem; background: white; box-shadow: 0 1px 2px rgb(0 0 0 / .04); }
        .analytics-toolbar h2, .analytics-panel h3 { margin: 0; color: rgb(24 24 27); font-size: .95rem; font-weight: 650; }
        .analytics-toolbar p, .analytics-panel p { margin: .3rem 0 0; color: rgb(113 113 122); font-size: .875rem; }
        .analytics-filter { display: flex; align-items: end; gap: .75rem; }
        .analytics-filter label { color: rgb(63 63 70); font-size: .8rem; font-weight: 600; }
        .analytics-filter input { display: block; width: 10rem; margin-top: .3rem; padding: .55rem .7rem; border: 1px solid rgb(212 212 216); border-radius: .5rem; background: white; color: rgb(39 39 42); font-size: .875rem; }
        .analytics-button { height: 2.35rem; border: 0; border-radius: .5rem; padding: 0 1rem; background: rgb(245 158 11); color: white; font-size: .8rem; font-weight: 650; cursor: pointer; }
        .analytics-button:hover { background: rgb(217 119 6); }
        .analytics-presets { display: flex; flex-wrap: wrap; gap: .5rem; }
        .analytics-preset { border: 1px solid rgb(228 228 231); border-radius: .5rem; padding: .5rem .8rem; background: white; color: rgb(82 82 91); font-size: .8rem; font-weight: 600; cursor: pointer; }
        .analytics-preset:hover { border-color: rgb(245 158 11); color: rgb(180 83 9); }
        .analytics-metrics { display: grid; grid-template-columns: repeat(5, minmax(0, 1fr)); gap: 1rem; }
        .analytics-metric { min-width: 0; padding: 1rem; border: 1px solid rgb(228 228 231); border-radius: .75rem; background: white; box-shadow: 0 1px 2px rgb(0 0 0 / .04); }
        .analytics-metric-top { display: flex; align-items: center; justify-content: space-between; gap: .5rem; color: rgb(113 113 122); font-size: .8rem; }
        .analytics-metric-value { margin-top: .65rem; color: rgb(24 24 27); font-size: 1.55rem; font-weight: 750; }
        .analytics-panel { min-width: 0; overflow: hidden; border: 1px solid rgb(228 228 231); border-radius: .75rem; background: white; box-shadow: 0 1px 2px rgb(0 0 0 / .04); }
        .analytics-panel-head { display: flex; align-items: flex-start; justify-content: space-between; gap: .75rem; padding: 1.25rem 1.25rem 0; }
        .analytics-chart-grid, .analytics-bottom-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 1.5rem; }
        .analytics-bars { display: flex; align-items: end; gap: .25rem; height: 15rem; margin: 1.25rem 1.25rem 0; padding: 0 .25rem; border-bottom: 1px solid rgb(228 228 231); overflow-x: auto; }
        .analytics-bar-wrap { display: flex; min-width: 1.1rem; height: 100%; flex: 1 0 0; flex-direction: column; align-items: center; justify-content: end; gap: .25rem; }
        .analytics-bar-wrap span { color: rgb(113 113 122); font-size: .62rem; opacity: 0; }
        .analytics-bar-wrap:hover span { opacity: 1; }
        .analytics-bar { width: 100%; min-height: 3px; border-radius: .3rem .3rem 0 0; background: rgb(245 158 11); }
        .analytics-bar.daily { background: rgb(217 119 6); }
        .analytics-axis { display: flex; justify-content: space-between; padding: .5rem 1.25rem 1rem; color: rgb(161 161 170); font-size: .65rem; }
        .analytics-list { margin-top: .75rem; }
        .analytics-list-row { display: flex; align-items: center; justify-content: space-between; gap: 1rem; padding: .75rem 1.25rem; border-top: 1px solid rgb(244 244 245); color: rgb(63 63 70); font-size: .85rem; }
        .analytics-list-row strong { color: rgb(180 83 9); white-space: nowrap; }
        .analytics-empty { padding: 2rem 1.25rem; text-align: center; color: rgb(113 113 122); font-size: .85rem; }
        .analytics-interactions { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: .75rem; padding: 1.25rem; }
        .analytics-interaction { padding: 1rem; border-radius: .6rem; background: rgb(236 253 245); color: rgb(6 95 70); }
        .analytics-interaction.amber { background: rgb(255 251 235); color: rgb(146 64 14); }
        .analytics-interaction p { color: inherit; margin: 0; }
        .analytics-interaction strong { display: block; margin-top: .35rem; font-size: 1.3rem; }
        html.fi.dark {
            color-scheme: dark;
        }
        html.fi.dark .analytics-toolbar, html.fi.dark .analytics-metric, html.fi.dark .analytics-panel, html.fi.dark .analytics-preset { border-color: rgb(255 255 255 / .1); background: rgb(24 24 27); }
        html.fi.dark .analytics-toolbar h2, html.fi.dark .analytics-panel h3, html.fi.dark .analytics-metric-value { color: white; }
        html.fi.dark .analytics-toolbar p, html.fi.dark .analytics-panel p, html.fi.dark .analytics-metric-top { color: rgb(161 161 170); }
        html.fi.dark .analytics-filter label, html.fi.dark .analytics-list-row { color: rgb(212 212 216); }
        html.fi.dark .analytics-filter input { border-color: rgb(255 255 255 / .12); background: rgb(39 39 42); color: white; }
        html.fi.dark .analytics-preset { color: rgb(212 212 216); }
        html.fi.dark .analytics-list-row { border-color: rgb(255 255 255 / .06); }
        html.fi.dark .analytics-bars, html.fi.dark .analytics-axis { border-color: rgb(255 255 255 / .1); }
        html.fi.dark .analytics-interaction { background: rgb(6 78 59 / .35); color: rgb(167 243 208); }
        html.fi.dark .analytics-interaction.amber { background: rgb(120 53 15 / .35); color: rgb(253 230 138); }
        @media (max-width: 1100px) { .analytics-metrics { grid-template-columns: repeat(3, minmax(0, 1fr)); } }
        @media (max-width: 760px) {
            .analytics-toolbar, .analytics-filter { align-items: stretch; flex-direction: column; }
            .analytics-filter input { width: 100%; }
            .analytics-metrics, .analytics-chart-grid, .analytics-bottom-grid { grid-template-columns: 1fr; }
        }
    </style>

    <div class="analytics-page">
        <div class="analytics-toolbar">
            <div>
                <h2>Ringkasan performa</h2>
                <p>Pantau kunjungan dan interaksi calon pelanggan.</p>
            </div>
            <form wire:submit="applyFilters" class="analytics-filter">
                <label>Dari<input type="date" wire:model="startDate" /></label>
                <label>Sampai<input type="date" wire:model="endDate" /></label>
                <button type="submit" class="analytics-button">Terapkan</button>
            </form>
        </div>

        <div class="analytics-presets">
            @foreach ([['today', 'Hari ini'], ['yesterday', 'Kemarin'], ['7days', '7 hari'], ['30days', '30 hari'], ['month', 'Bulan ini'], ['lastMonth', 'Bulan lalu']] as [$preset, $label])
                <button type="button" wire:click="setPreset('{{ $preset }}')" class="analytics-preset">{{ $label }}</button>
            @endforeach
        </div>

        <div class="analytics-metrics">
            @foreach ([
                ['label' => 'Kunjungan', 'value' => number_format($statistics['summary']['views'] ?? 0), 'icon' => 'heroicon-o-eye', 'tone' => 'primary'],
                ['label' => 'Pengunjung unik', 'value' => number_format($statistics['summary']['unique_visitors'] ?? 0), 'icon' => 'heroicon-o-users', 'tone' => 'success'],
                ['label' => 'View produk', 'value' => number_format($statistics['summary']['product_views'] ?? 0), 'icon' => 'heroicon-o-shopping-bag', 'tone' => 'warning'],
                ['label' => 'Klik WhatsApp', 'value' => number_format($statistics['summary']['whatsapp_clicks'] ?? 0), 'icon' => 'heroicon-o-chat-bubble-left-right', 'tone' => 'success'],
                ['label' => 'Sedang online', 'value' => number_format($statistics['summary']['online'] ?? 0), 'icon' => 'heroicon-o-bolt', 'tone' => 'danger'],
            ] as $stat)
                <div class="analytics-metric">
                    <div class="analytics-metric-top"><span>{{ $stat['label'] }}</span><x-filament::icon :icon="$stat['icon']" style="width:1.2rem;height:1.2rem" /></div>
                    <div class="analytics-metric-value">{{ $stat['value'] }}</div>
                </div>
            @endforeach
        </div>

        <div class="analytics-chart-grid">
            @foreach ([['title' => 'Tren kunjungan harian', 'description' => "Periode {$startDate} sampai {$endDate}.", 'key' => 'daily', 'class' => 'daily'], ['title' => 'Tren kunjungan bulanan', 'description' => 'Rolling 12 bulan terakhir.', 'key' => 'monthly', 'class' => '']] as $chart)
                <section class="analytics-panel">
                    <div class="analytics-panel-head"><div><h3>{{ $chart['title'] }}</h3><p>{{ $chart['description'] }}</p></div><x-filament::icon icon="heroicon-o-chart-bar" style="width:1.25rem;height:1.25rem" /></div>
                    @php $points = $statistics[$chart['key']] ?? []; $max = max(1, collect($points)->max('total')); @endphp
                    <div class="analytics-bars">
                        @foreach ($points as $point)
                            <div class="analytics-bar-wrap" title="{{ $point['label'] }}: {{ $point['total'] }} view"><span>{{ $point['total'] }}</span><div class="analytics-bar {{ $chart['class'] }}" style="height: {{ max(3, ($point['total'] / $max) * 78) }}%"></div></div>
                        @endforeach
                    </div>
                    <div class="analytics-axis"><span>{{ $points[0]['label'] ?? '-' }}</span><span>{{ $points[count($points) - 1]['label'] ?? '-' }}</span></div>
                </section>
            @endforeach
        </div>

        <div class="analytics-bottom-grid">
            <section class="analytics-panel"><div class="analytics-panel-head"><div><h3>Produk paling sering dilihat</h3><p>Produk dengan view terbanyak pada periode ini.</p></div></div><div class="analytics-list">
                @forelse ($statistics['top_products'] ?? [] as $product)<div class="analytics-list-row"><span>{{ $product['name'] }}</span><strong>{{ number_format($product['total']) }} view</strong></div>@empty<div class="analytics-empty">Belum ada view produk pada periode ini.</div>@endforelse
            </div></section>
            <section class="analytics-panel"><div class="analytics-panel-head"><div><h3>Interaksi pelanggan</h3><p>Aktivitas yang mengarah ke proses pemesanan.</p></div></div><div class="analytics-interactions"><div class="analytics-interaction"><p>Klik WhatsApp</p><strong>{{ number_format($statistics['events']['whatsapp_click'] ?? 0) }}</strong></div><div class="analytics-interaction amber"><p>Checkout keranjang</p><strong>{{ number_format($statistics['events']['cart_checkout'] ?? 0) }}</strong></div></div></section>
        </div>
    </div>
</x-dynamic-component>
