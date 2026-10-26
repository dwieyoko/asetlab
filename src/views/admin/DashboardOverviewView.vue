<template>
  <div class="space-y-6">
    <div class="flex flex-col w-full p-space-md lg:p-space-lg gap-space-lg">
<!-- Top Section: Header & Top Stats -->
<div class="flex flex-col gap-space-md">
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-space-xs">
<div>
<h1 class="font-headline-md text-headline-md text-primary tracking-tight font-bold">Top Stats</h1>
<p class="font-body-sm text-body-sm text-outline">Ringkasan performa penjualan dan pipeline pengerjaan proyek terkini.</p>
</div>
<div class="flex items-center gap-space-xs self-start sm:self-auto">
<span class="font-label-sm text-label-sm text-outline uppercase tracking-wider">Pembaruan:</span>
<span class="font-label-sm text-label-sm text-secondary flex items-center gap-1 font-semibold">
<span class="w-1.5 h-1.5 rounded-full bg-secondary"></span> Live Real-time
        </span>
</div>
</div>
<!-- 4 Stats Cards Grid with Cyber Glowing Accent -->
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-space-md">
<!-- Card 1: Total Pendapatan -->
<div class="relative group overflow-hidden rounded-2xl bg-surface-container/70 backdrop-blur-md p-space-md shadow-xl transition-all duration-300 hover:bg-surface-container-high/80">
<div class="absolute -inset-0.5 bg-gradient-to-r from-primary-container/20 to-secondary/20 rounded-2xl opacity-40 group-hover:opacity-100 transition-opacity blur-[1px]"></div>
<div class="relative flex flex-col justify-between h-full">
<div class="flex items-center justify-between gap-space-xs mb-space-sm">
<div class="flex items-center gap-2">
<span class="flex items-center justify-center w-6 h-6 rounded-md bg-surface-container-highest/80 font-label-md text-label-md text-primary-container font-bold">1</span>
<span class="font-label-md text-label-md text-on-surface-variant font-medium">Total Pendapatan</span>
</div>
<span class="inline-flex items-center gap-0.5 px-2 py-0.5 rounded-full bg-secondary-container/20 text-secondary font-label-sm text-label-sm font-semibold tracking-wide">
<span class="material-symbols-outlined text-[14px]">trending_up</span>
              +12.4%
            </span>
</div>
<div>
<div class="font-headline-md text-headline-md font-bold text-tertiary tracking-tight">{{ formatRupiah(totalRevenue) }}</div>
<p class="font-body-sm text-body-sm text-outline mt-1">Akumulasi kontrak &amp; penjualan</p>
</div>
</div>
</div>
<!-- Card 2: Produk Terjual -->
<div class="relative group overflow-hidden rounded-2xl bg-surface-container/70 backdrop-blur-md p-space-md shadow-xl transition-all duration-300 hover:bg-surface-container-high/80">
<div class="absolute -inset-0.5 bg-gradient-to-r from-primary-container/20 to-secondary/20 rounded-2xl opacity-40 group-hover:opacity-100 transition-opacity blur-[1px]"></div>
<div class="relative flex flex-col justify-between h-full">
<div class="flex items-center justify-between gap-space-xs mb-space-sm">
<div class="flex items-center gap-2">
<span class="flex items-center justify-center w-6 h-6 rounded-md bg-surface-container-highest/80 font-label-md text-label-md text-primary-container font-bold">2</span>
<span class="font-label-md text-label-md text-on-surface-variant font-medium">Produk Terjual</span>
</div>
<span class="inline-flex items-center gap-0.5 px-2 py-0.5 rounded-full bg-secondary-container/20 text-secondary font-label-sm text-label-sm font-semibold tracking-wide">
<span class="material-symbols-outlined text-[14px]">trending_up</span>
              +8.2%
            </span>
</div>
<div>
<div class="font-headline-md text-headline-md font-bold text-tertiary tracking-tight">{{ totalDownloads }} <span class="font-headline-sm text-headline-sm font-normal text-on-surface-variant">item</span></div>
<p class="font-body-sm text-body-sm text-outline mt-1">Aset template &amp; source code</p>
</div>
</div>
</div>
<!-- Card 3: Proyek Jasa Aktif -->
<div class="relative group overflow-hidden rounded-2xl bg-surface-container/70 backdrop-blur-md p-space-md shadow-xl transition-all duration-300 hover:bg-surface-container-high/80">
<div class="absolute -inset-0.5 bg-gradient-to-r from-primary-container/20 to-secondary/20 rounded-2xl opacity-40 group-hover:opacity-100 transition-opacity blur-[1px]"></div>
<div class="relative flex flex-col justify-between h-full">
<div class="flex items-center justify-between gap-space-xs mb-space-sm">
<div class="flex items-center gap-2">
<span class="flex items-center justify-center w-6 h-6 rounded-md bg-surface-container-highest/80 font-label-md text-label-md text-primary-container font-bold">3</span>
<span class="font-label-md text-label-md text-on-surface-variant font-medium">Proyek Jasa Aktif</span>
</div>
<span class="w-2 h-2 rounded-full bg-secondary-container animate-pulse"></span>
</div>
<div>
<div class="font-headline-md text-headline-md font-bold text-tertiary tracking-tight">{{ activeProjectsCount }} <span class="font-headline-sm text-headline-sm font-normal text-on-surface-variant">proyek</span></div>
<p class="font-body-sm text-body-sm text-outline mt-1">Layanan web &amp; sosmed berjalan</p>
</div>
</div>
</div>
<!-- Card 4: Pesanan Baru -->
<div class="relative group overflow-hidden rounded-2xl bg-surface-container/70 backdrop-blur-md p-space-md shadow-xl transition-all duration-300 hover:bg-surface-container-high/80">
<div class="absolute -inset-0.5 bg-gradient-to-r from-primary-container/20 to-secondary/20 rounded-2xl opacity-40 group-hover:opacity-100 transition-opacity blur-[1px]"></div>
<div class="relative flex flex-col justify-between h-full">
<div class="flex items-center justify-between gap-space-xs mb-space-sm">
<div class="flex items-center gap-2">
<span class="flex items-center justify-center w-6 h-6 rounded-md bg-surface-container-highest/80 font-label-md text-label-md text-primary-container font-bold">4</span>
<span class="font-label-md text-label-md text-on-surface-variant font-medium">Pesanan Baru</span>
</div>
<span class="inline-flex items-center px-2 py-0.5 rounded-full bg-error-container/40 text-error font-label-sm text-label-sm font-semibold">
              Perlu Review
            </span>
</div>
<div>
<div class="font-headline-md text-headline-md font-bold text-tertiary tracking-tight">{{ pendingOrdersCount }} <span class="font-headline-sm text-headline-sm font-normal text-on-surface-variant">pesanan</span></div>
<p class="font-body-sm text-body-sm text-outline mt-1">Menunggu konfirmasi verifikasi</p>
</div>
</div>
</div>
</div>
</div>
<!-- Big Interactive Chart Container: Tren Pendapatan & Unduhan -->
<div class="relative rounded-2xl bg-surface-container-low/90 backdrop-blur-md p-space-md lg:p-space-lg shadow-2xl overflow-hidden">
<!-- Ambient subtle background glow behind graph -->
<div class="absolute -top-24 -right-24 w-96 h-96 bg-primary-container/10 rounded-full blur-3xl pointer-events-none"></div>
<div class="absolute -bottom-24 -left-24 w-96 h-96 bg-secondary/10 rounded-full blur-3xl pointer-events-none"></div>
<div class="relative flex flex-col sm:flex-row sm:items-center justify-between gap-space-md mb-space-md">
<div>
<h2 class="font-headline-sm text-headline-sm text-primary font-bold">Tren Pendapatan &amp; Unduhan</h2>
<p class="font-body-sm text-body-sm text-outline">Korelasi performa pendapatan berbanding total unduhan aset digital tahun 2026</p>
</div>
<div class="flex items-center gap-space-md flex-wrap">
<!-- Interactive Chart Legends -->
<div class="flex items-center gap-space-sm bg-surface-container-high px-3 py-1.5 rounded-full">
<div class="flex items-center gap-2 cursor-pointer transition-opacity hover:opacity-80">
<span class="w-3.5 h-1 rounded-full bg-primary-container shadow-[0_0_8px_#00f2fe]"></span>
<span class="font-label-sm text-label-sm text-on-surface">Line Chart (Pendapatan)</span>
</div>
<span class="text-outline-variant">|</span>
<div class="flex items-center gap-2 cursor-pointer transition-opacity hover:opacity-80">
<span class="w-3.5 h-1 rounded-full bg-secondary shadow-[0_0_8px_#4edea3]"></span>
<span class="font-label-sm text-label-sm text-on-surface">Unduhan</span>
</div>
</div>
<button aria-label="Menu Opsi Grafik" class="p-1.5 rounded-lg bg-surface-container-high text-on-surface-variant hover:text-primary hover:bg-surface-container-highest transition-colors" type="button">
<span class="material-symbols-outlined text-[20px]">more_horiz</span>
</button>
</div>
</div>
<!-- Responsive SVG Wave Graph Canvas -->
<div class="relative w-full h-72 sm:h-80 select-none">
<svg class="w-full h-full overflow-visible" preserveAspectRatio="none" viewBox="0 0 1000 320">
<defs>
<!-- Cyan Gradient Glow -->
<linearGradient id="cyanGradient" x1="0%" x2="0%" y1="0%" y2="100%">
<stop offset="0%" stop-color="#00f2fe" stop-opacity="0.32"></stop>
<stop offset="70%" stop-color="#00f2fe" stop-opacity="0.05"></stop>
<stop offset="100%" stop-color="#00f2fe" stop-opacity="0"></stop>
</linearGradient>
<!-- Green Gradient Glow -->
<linearGradient id="greenGradient" x1="0%" x2="0%" y1="0%" y2="100%">
<stop offset="0%" stop-color="#4edea3" stop-opacity="0.25"></stop>
<stop offset="80%" stop-color="#4edea3" stop-opacity="0.03"></stop>
<stop offset="100%" stop-color="#4edea3" stop-opacity="0"></stop>
</linearGradient>
<!-- Glow Filter -->
<filter height="140%" id="glowCyan" width="140%" x="-20%" y="-20%">
<feDropShadow dx="0" dy="0" flood-color="#00f2fe" flood-opacity="0.65" stdDeviation="3"></feDropShadow>
</filter>
<filter height="140%" id="glowGreen" width="140%" x="-20%" y="-20%">
<feDropShadow dx="0" dy="0" flood-color="#4edea3" flood-opacity="0.65" stdDeviation="3"></feDropShadow>
</filter>
</defs>
<!-- Horizontal Guide Lines & Y-Labels -->
<g class="text-outline-variant/30 text-label-sm opacity-60">
<line stroke="currentColor" stroke-dasharray="4 4" stroke-width="1" x1="50" x2="980" y1="20" y2="20"></line>
<text fill="#849495" font-family="Inter" font-size="11" text-anchor="end" x="35" y="24">400</text>
<line stroke="currentColor" stroke-dasharray="4 4" stroke-width="1" x1="50" x2="980" y1="85" y2="85"></line>
<text fill="#849495" font-family="Inter" font-size="11" text-anchor="end" x="35" y="89">300</text>
<line stroke="currentColor" stroke-dasharray="4 4" stroke-width="1" x1="50" x2="980" y1="150" y2="150"></line>
<text fill="#849495" font-family="Inter" font-size="11" text-anchor="end" x="35" y="154">200</text>
<line stroke="currentColor" stroke-dasharray="4 4" stroke-width="1" x1="50" x2="980" y1="215" y2="215"></line>
<text fill="#849495" font-family="Inter" font-size="11" text-anchor="end" x="35" y="219">100</text>
<line stroke="currentColor" stroke-width="1" x1="50" x2="980" y1="280" y2="280"></line>
<text fill="#849495" font-family="Inter" font-size="11" text-anchor="end" x="35" y="284">0</text>
</g>
<!-- AREA FILL: Cyan Line (Pendapatan) -->
<path d="M 60 280 
                 C 110 260, 130 170, 180 170 
                 C 230 170, 260 250, 310 240 
                 C 360 230, 380 200, 430 200 
                 C 480 200, 500 230, 550 170 
                 C 590 120, 610 60, 660 65 
                 C 710 70, 720 220, 770 180 
                 C 820 140, 850 150, 900 190 
                 C 940 220, 960 100, 980 90
                 L 980 280 L 60 280 Z" fill="url(#cyanGradient)"></path>
<!-- AREA FILL: Green Line (Unduhan) -->
<path d="M 60 280 
                 C 100 270, 120 240, 170 230 
                 C 220 220, 250 210, 300 215 
                 C 350 220, 380 260, 430 255 
                 C 480 250, 520 150, 570 140 
                 C 620 130, 640 235, 690 230 
                 C 740 225, 770 160, 820 165 
                 C 870 170, 900 230, 940 230 
                 C 960 230, 970 260, 980 270
                 L 980 280 L 60 280 Z" fill="url(#greenGradient)"></path>
<!-- STROKE: Cyan Line (Pendapatan) -->
<path d="M 60 280 
                 C 110 260, 130 170, 180 170 
                 C 230 170, 260 250, 310 240 
                 C 360 230, 380 200, 430 200 
                 C 480 200, 500 230, 550 170 
                 C 590 120, 610 60, 660 65 
                 C 710 70, 720 220, 770 180 
                 C 820 140, 850 150, 900 190 
                 C 940 220, 960 100, 980 90" fill="none" filter="url(#glowCyan)" stroke="#00f2fe" stroke-linecap="round" stroke-width="3.5"></path>
<!-- STROKE: Green Line (Unduhan) -->
<path d="M 60 280 
                 C 100 270, 120 240, 170 230 
                 C 220 220, 250 210, 300 215 
                 C 350 220, 380 260, 430 255 
                 C 480 250, 520 150, 570 140 
                 C 620 130, 640 235, 690 230 
                 C 740 225, 770 160, 820 165 
                 C 870 170, 900 230, 940 230 
                 C 960 230, 970 260, 980 270" fill="none" filter="url(#glowGreen)" stroke="#4edea3" stroke-linecap="round" stroke-width="3"></path>
<!-- Interactive Pulsing Points on Peak & Pivot Points -->
<circle class="animate-pulse" cx="660" cy="65" fill="#e0fdff" r="5" stroke="#00f2fe" stroke-width="3"></circle>
<circle cx="570" cy="140" fill="#e0fdff" r="5" stroke="#4edea3" stroke-width="3"></circle>
<circle cx="820" cy="165" fill="#003824" r="4" stroke="#4edea3" stroke-width="2.5"></circle>
<circle cx="980" cy="90" fill="#00373a" r="4.5" stroke="#00f2fe" stroke-width="2.5"></circle>
</svg>
</div>
<!-- X-Axis Months -->
<div class="grid grid-cols-10 pl-8 pr-2 pt-2 text-center">
<span class="font-label-sm text-label-sm text-outline">Jan</span>
<span class="font-label-sm text-label-sm text-outline">Feb</span>
<span class="font-label-sm text-label-sm text-outline">Mar</span>
<span class="font-label-sm text-label-sm text-outline">Apr</span>
<span class="font-label-sm text-label-sm text-outline">Mei</span>
<span class="font-label-sm text-label-sm text-primary font-bold">Jun</span>
<span class="font-label-sm text-label-sm text-outline">Jul</span>
<span class="font-label-sm text-label-sm text-outline">Agu</span>
<span class="font-label-sm text-label-sm text-outline">Okt</span>
<span class="font-label-sm text-label-sm text-outline">Des</span>
</div>
</div>
<!-- Bottom Bento Split: Pesanan Terbaru (65%) & Project Progress (35%) -->
<div class="grid grid-cols-1 lg:grid-cols-12 gap-space-lg">
<!-- Left Column: Pesanan Terbaru (Approx 65%) -->
<div class="lg:col-span-8 flex flex-col rounded-2xl bg-surface-container-low/90 backdrop-blur-md p-space-md lg:p-space-lg shadow-xl overflow-hidden">
<!-- Table Header & Filters -->
<div class="flex items-center justify-between mb-space-md gap-space-xs">
<div>
<h2 class="font-headline-sm text-headline-sm text-primary font-bold">Pesanan Terbaru</h2>
<p class="font-body-sm text-body-sm text-outline">Daftar transaksi masuk dan status komputasi pembayaran</p>
</div>
<div class="relative">
<select class="appearance-none bg-surface-container-high text-on-surface font-label-md text-label-md py-1.5 pl-3 pr-8 rounded-full border border-outline-variant/30 focus:outline-none focus:border-primary-container cursor-pointer">
<option>Semua Pesanan</option>
<option selected="">Selesai</option>
<option>Menunggu</option>
</select>
<span class="material-symbols-outlined absolute right-2.5 top-2 pointer-events-none text-outline text-[16px]">expand_more</span>
</div>
</div>
<!-- Table Wrapper -->
<div class="w-full overflow-x-auto">
<table class="w-full text-left border-collapse">
<thead>
<tr class="border-b border-outline-variant/20">
<th class="pb-3 pt-1 font-label-sm text-label-sm text-outline uppercase tracking-wider font-semibold">ID Order</th>
<th class="pb-3 pt-1 font-label-sm text-label-sm text-outline uppercase tracking-wider font-semibold">Nama Pelanggan</th>
<th class="pb-3 pt-1 font-label-sm text-label-sm text-outline uppercase tracking-wider font-semibold">Jenis Layanan</th>
<th class="pb-3 pt-1 font-label-sm text-label-sm text-outline uppercase tracking-wider font-semibold">Status Pembayaran</th>
<th class="pb-3 pt-1 font-label-sm text-label-sm text-outline uppercase tracking-wider font-semibold text-center">Aksi</th>
</tr>
</thead>
<tbody class="divide-y divide-outline-variant/10 font-body-sm text-body-sm">
<!-- Row 1 -->
<tr class="hover:bg-surface-container/60 transition-colors group">
<td class="py-3.5 pr-3 font-mono font-medium text-tertiary">ORD-20261018-001</td>
<td class="py-3.5 pr-3">
<div class="flex items-center gap-2">
<div class="w-6 h-6 rounded-full bg-surface-container-highest flex items-center justify-center font-bold text-label-sm text-primary">RP</div>
<span class="text-on-surface font-medium">Rizky Pratama</span>
</div>
</td>
<td class="py-3.5 pr-3">
<span class="inline-flex items-center px-2.5 py-0.5 rounded-full bg-surface-container-highest text-primary-fixed-dim border border-primary-container/20 font-label-sm text-label-sm">
                  Bisnis Layanan
                </span>
</td>
<td class="py-3.5 pr-3">
<span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-secondary/10 border border-secondary/30 text-secondary font-label-sm text-label-sm font-semibold">
<span class="w-1.5 h-1.5 rounded-full bg-secondary"></span>
                  Selesai
                </span>
</td>
<td class="py-3.5 text-center">
<button aria-label="Detail Pesanan ORD-001" class="p-1 rounded-md text-outline hover:text-primary transition-colors" type="button">
<span class="material-symbols-outlined text-[18px]">more_horiz</span>
</button>
</td>
</tr>
<!-- Row 2 -->
<tr class="hover:bg-surface-container/60 transition-colors group">
<td class="py-3.5 pr-3 font-mono font-medium text-tertiary">ORD-20261018-002</td>
<td class="py-3.5 pr-3">
<div class="flex items-center gap-2">
<div class="w-6 h-6 rounded-full bg-surface-container-highest flex items-center justify-center font-bold text-label-sm text-secondary">PN</div>
<span class="text-on-surface font-medium">PT Nusa Kreasi</span>
</div>
</td>
<td class="py-3.5 pr-3">
<span class="inline-flex items-center px-2.5 py-0.5 rounded-full bg-surface-container-highest text-primary-fixed-dim border border-primary-container/20 font-label-sm text-label-sm">
                  Web Custom
                </span>
</td>
<td class="py-3.5 pr-3">
<span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-amber-500/10 border border-amber-500/30 text-amber-400 font-label-sm text-label-sm font-semibold">
<span class="w-1.5 h-1.5 rounded-full bg-amber-400 animate-pulse"></span>
                  Menunggu
                </span>
</td>
<td class="py-3.5 text-center">
<button aria-label="Detail Pesanan ORD-002" class="p-1 rounded-md text-outline hover:text-primary transition-colors" type="button">
<span class="material-symbols-outlined text-[18px]">more_horiz</span>
</button>
</td>
</tr>
<!-- Row 3 -->
<tr class="hover:bg-surface-container/60 transition-colors group">
<td class="py-3.5 pr-3 font-mono font-medium text-tertiary">ORD-20261018-003</td>
<td class="py-3.5 pr-3">
<div class="flex items-center gap-2">
<div class="w-6 h-6 rounded-full bg-surface-container-highest flex items-center justify-center font-bold text-label-sm text-tertiary-fixed-dim">DM</div>
<span class="text-on-surface font-medium">Dimas Maulana</span>
</div>
</td>
<td class="py-3.5 pr-3">
<span class="inline-flex items-center px-2.5 py-0.5 rounded-full bg-surface-container-highest text-primary-fixed-dim border border-primary-container/20 font-label-sm text-label-sm">
                  Produk Digital
                </span>
</td>
<td class="py-3.5 pr-3">
<span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-amber-500/10 border border-amber-500/30 text-amber-400 font-label-sm text-label-sm font-semibold">
<span class="w-1.5 h-1.5 rounded-full bg-amber-400 animate-pulse"></span>
                  Menunggu
                </span>
</td>
<td class="py-3.5 text-center">
<button aria-label="Detail Pesanan ORD-003" class="p-1 rounded-md text-outline hover:text-primary transition-colors" type="button">
<span class="material-symbols-outlined text-[18px]">more_horiz</span>
</button>
</td>
</tr>
<!-- Row 4 -->
<tr class="hover:bg-surface-container/60 transition-colors group">
<td class="py-3.5 pr-3 font-mono font-medium text-tertiary">ORD-20261017-089</td>
<td class="py-3.5 pr-3">
<div class="flex items-center gap-2">
<div class="w-6 h-6 rounded-full bg-surface-container-highest flex items-center justify-center font-bold text-label-sm text-primary">SA</div>
<span class="text-on-surface font-medium">Siti Anggraini</span>
</div>
</td>
<td class="py-3.5 pr-3">
<span class="inline-flex items-center px-2.5 py-0.5 rounded-full bg-surface-container-highest text-primary-fixed-dim border border-primary-container/20 font-label-sm text-label-sm">
                  Bisnis Layanan
                </span>
</td>
<td class="py-3.5 pr-3">
<span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-secondary/10 border border-secondary/30 text-secondary font-label-sm text-label-sm font-semibold">
<span class="w-1.5 h-1.5 rounded-full bg-secondary"></span>
                  Selesai
                </span>
</td>
<td class="py-3.5 text-center">
<button aria-label="Detail Pesanan ORD-089" class="p-1 rounded-md text-outline hover:text-primary transition-colors" type="button">
<span class="material-symbols-outlined text-[18px]">more_horiz</span>
</button>
</td>
</tr>
</tbody>
</table>
</div>
<div class="mt-auto pt-space-sm flex items-center justify-between text-outline font-label-sm text-label-sm">
<span class="">Menampilkan 4 dari 124 pesanan</span>
<button class="text-primary-container hover:underline font-semibold flex items-center gap-1" type="button">
          Lihat Semua <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
</button>
</div>
</div>
<!-- Right Column: Project Progress (Approx 35%) -->
<div class="lg:col-span-4 flex flex-col rounded-2xl bg-surface-container-low/90 backdrop-blur-md p-space-md lg:p-space-lg shadow-xl justify-between">
<div>
<div class="flex items-center justify-between mb-space-xs">
<h2 class="font-headline-sm text-headline-sm text-primary font-bold">Project Progress</h2>
<span class="material-symbols-outlined text-outline text-[20px]">view_kanban</span>
</div>
<p class="font-body-sm text-body-sm text-outline mb-space-md">Papan Kanban mini / list status pengerjaan</p>
<!-- Kanban mini list cards -->
<div class="flex flex-col gap-space-sm">
<!-- Item 1: In Progress -->
<div class="p-space-sm rounded-xl bg-surface-container/70 border border-primary-container/20 flex flex-col gap-2 hover:bg-surface-container transition-all">
<div class="flex items-center justify-between">
<span class="font-label-md text-label-md text-on-surface font-semibold">Website Klien Fintech</span>
<span class="px-2 py-0.5 rounded-full bg-primary-container/10 border border-primary-container/40 text-primary-container font-label-sm text-label-sm font-bold shadow-[0_0_10px_rgba(0,242,254,0.2)]">
                In Progress
              </span>
</div>
<div class="w-full bg-surface-container-highest rounded-full h-1.5 overflow-hidden">
<div class="bg-primary-container h-1.5 rounded-full shadow-[0_0_8px_#00f2fe]" style="width: 68%"></div>
</div>
<div class="flex items-center justify-between text-outline font-label-sm text-label-sm">
<span class="">Status: Integrasi API Midtrans</span>
<span class="text-tertiary font-mono">68%</span>
</div>
</div>
<!-- Item 2: Review -->
<div class="p-space-sm rounded-xl bg-surface-container/70 border border-amber-500/20 flex flex-col gap-2 hover:bg-surface-container transition-all">
<div class="flex items-center justify-between">
<span class="font-label-md text-label-md text-on-surface font-semibold">Sosial Media Klien Nexa</span>
<span class="px-2 py-0.5 rounded-full bg-amber-500/10 border border-amber-500/40 text-amber-400 font-label-sm text-label-sm font-bold">
                Review
              </span>
</div>
<div class="w-full bg-surface-container-highest rounded-full h-1.5 overflow-hidden">
<div class="bg-amber-400 h-1.5 rounded-full" style="width: 85%"></div>
</div>
<div class="flex items-center justify-between text-outline font-label-sm text-label-sm">
<span class="">Status: Produksi Konten Reels Batch 2</span>
<span class="text-tertiary font-mono">85%</span>
</div>
</div>
<!-- Item 3: Selesai -->
<div class="p-space-sm rounded-xl bg-surface-container/70 border border-secondary/20 flex flex-col gap-2 hover:bg-surface-container transition-all">
<div class="flex items-center justify-between">
<span class="font-label-md text-label-md text-on-surface font-semibold">Desain UI/UX Mobile App</span>
<span class="px-2 py-0.5 rounded-full bg-secondary/10 border border-secondary/40 text-secondary font-label-sm text-label-sm font-bold shadow-[0_0_10px_rgba(78,222,163,0.2)]">
                Selesai
              </span>
</div>
<div class="w-full bg-surface-container-highest rounded-full h-1.5 overflow-hidden">
<div class="bg-secondary h-1.5 rounded-full shadow-[0_0_8px_#4edea3]" style="width: 100%"></div>
</div>
<div class="flex items-center justify-between text-outline font-label-sm text-label-sm">
<span class="">Status: Handover Design Tokens</span>
<span class="text-secondary font-mono font-bold">100%</span>
</div>
</div>
</div>
</div>
<!-- Quick Action Button in Kanban -->
<button class="mt-space-md w-full py-2.5 px-4 rounded-full bg-gradient-to-r from-primary-container to-secondary text-surface-container-lowest font-label-md text-label-md font-bold flex items-center justify-center gap-2 shadow-[0_4px_20px_rgba(0,242,254,0.35)] hover:brightness-110 active:scale-[0.99] transition-all" type="button">
<span class="material-symbols-outlined text-[18px]">add_task</span>
        Buat Penugasan Baru
      </button>
</div>
</div>
</div>
  </div>
</template>


<script setup>
import { computed } from 'vue';
import { products, orders, formatRupiah } from '@/data/mockData';

const totalProductRevenue = computed(() => products.value.reduce((acc, p) => acc + ((p.downloads || 0) * (p.price || 0)), 0));
const totalOrderRevenue = computed(() => orders.value.reduce((acc, o) => acc + (o.budget || 0), 0));
const totalRevenue = computed(() => totalProductRevenue.value + totalOrderRevenue.value);

const totalDownloads = computed(() => products.value.reduce((acc, p) => acc + (p.downloads || 0), 0));
const activeProjectsCount = computed(() => orders.value.filter(o => o.status === 'Sedang Dikerjakan' || o.status === 'Review').length);
const pendingOrdersCount = computed(() => orders.value.filter(o => o.status === 'Menunggu Konfirmasi').length);
</script>
