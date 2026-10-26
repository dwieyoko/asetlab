<template>
  <div class="space-y-6">
    <div class="flex flex-col w-full">
<div class="p-space-lg lg:p-margin flex flex-col gap-space-lg w-full max-w-[1600px] mx-auto">
<!-- Top Action Bar & Page Title -->
<div class="flex flex-col xl:flex-row xl:items-center justify-between gap-space-md">
<div class="flex flex-col gap-space-xs">
<div class="flex items-center gap-space-xs">
<span class="font-label-sm text-label-sm text-secondary uppercase tracking-widest bg-secondary/10 px-2 py-0.5 rounded-full">Financial Audit</span>
<span class="text-outline text-xs">•</span>
<span class="font-label-sm text-label-sm text-outline">asetlab.id Ledger v2.4</span>
</div>
<h1 class="font-headline-lg text-headline-lg text-on-surface tracking-tight font-extrabold flex items-center gap-3">
          Laporan Transaksi &amp; Keuangan
          <span class="h-2.5 w-2.5 rounded-full bg-secondary shadow-[0_0_12px_#4edea3]"></span>
</h1>
<p class="font-body-md text-body-md text-primary font-normal opacity-90">Arsip mutasi real-time, rekonsiliasi gateway pembayaran, serta kalkulasi margin bersih.</p>
</div>
<div class="flex flex-wrap items-center gap-space-sm">
<!-- Date Selector Pill -->
<div class="flex items-center gap-space-xs bg-surface-container px-space-md py-space-xs rounded-full shadow-inner">
<span class="material-symbols-outlined text-[18px] text-primary-container">calendar_month</span>
<span class="font-label-md text-label-md text-on-surface">Bulan Ini: Oktober 2026</span>
<span class="material-symbols-outlined text-[16px] text-outline cursor-pointer hover:text-on-surface transition-colors">expand_more</span>
</div>
<!-- Automated Reconciliation CTA -->
<button  class="flex items-center gap-space-xs bg-surface-container-high hover:bg-surface-variant text-on-surface px-space-md py-space-xs rounded-full font-label-md text-label-md shadow-sm transition-all duration-200" id="reconBtn" @click="reconcileLedger" :disabled="isReconciling">
<span class="material-symbols-outlined text-[18px] text-secondary">sync</span>
<span class="">Rekonsiliasi Otomatis</span>
</button>
<!-- Download Financial Report Primary Pill Button -->
<button class="flex items-center gap-space-xs bg-gradient-to-r from-primary-container to-secondary text-surface-container-lowest px-5 py-space-xs rounded-full font-label-md text-label-md font-bold shadow-[0_4px_20px_rgba(0,242,254,0.35)] hover:shadow-[0_4px_28px_rgba(78,222,163,0.5)] transition-all transform hover:-translate-y-0.5 active:translate-y-0">
<span class="material-symbols-outlined text-[18px]">download</span>
<span class="">Unduh Laporan (PDF/CSV)</span>
</button>
</div>
</div>
<!-- 4 Financial KPI Panels -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-space-md">
<!-- KPI 1: Pemasukan Kotor -->
<div class="relative overflow-hidden bg-surface-container-low rounded-xl p-space-md shadow-md hover:bg-surface-container/70 transition-all flex flex-col justify-between">
<div class="absolute -right-8 -top-8 w-24 h-24 bg-primary-container/10 rounded-full blur-2xl pointer-events-none"></div>
<div class="flex items-center justify-between mb-space-xs">
<div class="flex items-center gap-2">
<span class="w-5 h-5 rounded-md bg-surface-container-highest flex items-center justify-center font-label-sm text-label-sm text-primary font-bold">1</span>
<span class="font-label-md text-label-md text-on-surface-variant">Pemasukan Kotor</span>
</div>
<span class="font-label-sm text-label-sm text-secondary bg-secondary/10 px-2 py-0.5 rounded-full font-semibold">+15.8% MoM</span>
</div>
<div class="mt-2 flex flex-col">
<span class="font-headline-md text-headline-md text-primary tracking-tight font-extrabold">Rp 74.270.000</span>
<span class="font-body-sm text-body-sm text-outline mt-1 flex items-center gap-1">
<span class="material-symbols-outlined text-[14px] text-secondary">trending_up</span>
            Target bulanan tercapai 106%
          </span>
</div>
<!-- Micro sparkline -->
<div class="mt-3 pt-2">
<svg class="w-full h-7 overflow-visible" preserveAspectRatio="none" viewBox="0 0 100 24">
<defs>
<linearGradient id="grad1" x1="0" x2="0" y1="0" y2="1">
<stop offset="0%" stop-color="#00f2fe" stop-opacity="0.3"></stop>
<stop offset="100%" stop-color="#00f2fe" stop-opacity="0"></stop>
</linearGradient>
</defs>
<path d="M0,20 Q20,15 35,18 T70,8 T100,3 L100,24 L0,24 Z" fill="url(#grad1)"></path>
<path d="M0,20 Q20,15 35,18 T70,8 T100,3" fill="none" stroke="#00f2fe" stroke-width="2"></path>
</svg>
</div>
</div>
<!-- KPI 2: Pendapatan Bersih -->
<div class="relative overflow-hidden bg-surface-container-low rounded-xl p-space-md shadow-md hover:bg-surface-container/70 transition-all flex flex-col justify-between">
<div class="absolute -right-8 -top-8 w-24 h-24 bg-secondary/10 rounded-full blur-2xl pointer-events-none"></div>
<div class="flex items-center justify-between mb-space-xs">
<div class="flex items-center gap-2">
<span class="w-5 h-5 rounded-md bg-surface-container-highest flex items-center justify-center font-label-sm text-label-sm text-secondary font-bold">2</span>
<span class="font-label-md text-label-md text-on-surface-variant">Pendapatan Bersih</span>
</div>
<span class="font-label-sm text-label-sm text-primary-container bg-primary-container/10 px-2 py-0.5 rounded-full font-semibold">Margin 83.2%</span>
</div>
<div class="mt-2 flex flex-col">
<span class="font-headline-md text-headline-md text-secondary tracking-tight font-extrabold">Rp 61.850.000</span>
<span class="font-body-sm text-body-sm text-outline mt-1 flex items-center gap-1">
<span class="material-symbols-outlined text-[14px] text-outline">account_balance_wallet</span>
            Setelah potongan PPN &amp; Gateway
          </span>
</div>
<div class="mt-3 pt-2">
<svg class="w-full h-7 overflow-visible" preserveAspectRatio="none" viewBox="0 0 100 24">
<defs>
<linearGradient id="grad2" x1="0" x2="0" y1="0" y2="1">
<stop offset="0%" stop-color="#4edea3" stop-opacity="0.3"></stop>
<stop offset="100%" stop-color="#4edea3" stop-opacity="0"></stop>
</linearGradient>
</defs>
<path d="M0,22 Q25,12 50,16 T80,5 T100,2 L100,24 L0,24 Z" fill="url(#grad2)"></path>
<path d="M0,22 Q25,12 50,16 T80,5 T100,2" fill="none" stroke="#4edea3" stroke-width="2"></path>
</svg>
</div>
</div>
<!-- KPI 3: Menunggu Pembayaran / Piutang -->
<div class="relative overflow-hidden bg-surface-container-low rounded-xl p-space-md shadow-md hover:bg-surface-container/70 transition-all flex flex-col justify-between">
<div class="flex items-center justify-between mb-space-xs">
<div class="flex items-center gap-2">
<span class="w-5 h-5 rounded-md bg-surface-container-highest flex items-center justify-center font-label-sm text-label-sm text-outline font-bold">3</span>
<span class="font-label-md text-label-md text-on-surface-variant">Menunggu / Piutang</span>
</div>
<span class="font-label-sm text-label-sm text-tertiary-fixed-dim bg-tertiary-fixed-dim/10 px-2 py-0.5 rounded-full font-semibold">2 invoice pending</span>
</div>
<div class="mt-2 flex flex-col">
<span class="font-headline-md text-headline-md text-on-surface tracking-tight font-extrabold">Rp 8.400.000</span>
<span class="font-body-sm text-body-sm text-outline mt-1 flex items-center gap-1">
<span class="material-symbols-outlined text-[14px] text-tertiary-fixed-dim">schedule</span>
            Jatuh tempo 48 jam ke depan
          </span>
</div>
<div class="mt-3 pt-2">
<div class="w-full bg-surface-container-highest rounded-full h-1.5 overflow-hidden">
<div class="bg-gradient-to-r from-tertiary-fixed-dim to-primary-container h-full rounded-full" style="width: 14%"></div>
</div>
<div class="flex justify-between items-center text-label-sm font-label-sm text-outline mt-1">
<span class="">Rasio Piutang</span>
<span class="">11.3% Pemasukan</span>
</div>
</div>
</div>
<!-- KPI 4: Success Rate -->
<div class="relative overflow-hidden bg-surface-container-low rounded-xl p-space-md shadow-md hover:bg-surface-container/70 transition-all flex flex-col justify-between">
<div class="flex items-center justify-between mb-space-xs">
<div class="flex items-center gap-2">
<span class="w-5 h-5 rounded-md bg-surface-container-highest flex items-center justify-center font-label-sm text-label-sm text-secondary font-bold">4</span>
<span class="font-label-md text-label-md text-on-surface-variant">Transaksi Berhasil</span>
</div>
<span class="font-label-sm text-label-sm text-secondary bg-secondary/10 px-2 py-0.5 rounded-full font-semibold">Optimal</span>
</div>
<div class="mt-2 flex flex-col">
<span class="font-headline-md text-headline-md text-on-surface tracking-tight font-extrabold">98.6%</span>
<span class="font-body-sm text-body-sm text-outline mt-1 flex items-center gap-1">
<span class="material-symbols-outlined text-[14px] text-secondary">verified</span>
            142 sukses / 2 kedaluwarsa
          </span>
</div>
<div class="mt-3 pt-2">
<div class="w-full bg-surface-container-highest rounded-full h-1.5 overflow-hidden">
<div class="bg-secondary h-full rounded-full" style="width: 98.6%"></div>
</div>
<div class="flex justify-between items-center text-label-sm font-label-sm text-outline mt-1">
<span class="">Midtrans &amp; Xendit uptime</span>
<span class="text-secondary font-bold">99.98%</span>
</div>
</div>
</div>
</div>
<!-- Quick Analysis Visuals Grid -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-space-md">
<!-- Mini Chart Card: Volume Transaksi & Revenue Flow -->
<div class="lg:col-span-2 bg-surface-container-low rounded-xl p-space-lg flex flex-col justify-between shadow-lg relative overflow-hidden">
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-space-sm mb-space-md">
<div class="flex flex-col">
<span class="font-label-sm text-label-sm text-outline uppercase tracking-wider">Arus Kas Masuk vs Keluar</span>
<span class="font-headline-sm text-headline-sm text-on-surface font-bold">Tren Transaksi Finansial (Harian)</span>
</div>
<div class="flex items-center gap-space-md">
<div class="flex items-center gap-2">
<span class="w-2.5 h-2.5 rounded-full bg-primary-container"></span>
<span class="font-label-sm text-label-sm text-on-surface-variant">Pemasukan</span>
</div>
<div class="flex items-center gap-2">
<span class="w-2.5 h-2.5 rounded-full bg-secondary"></span>
<span class="font-label-sm text-label-sm text-on-surface-variant">Biaya Gateway</span>
</div>
<button class="text-outline hover:text-on-surface transition-colors">
<span class="material-symbols-outlined text-[20px]">more_vert</span>
</button>
</div>
</div>
<!-- Analytical Wave SVG Chart -->
<div class="w-full h-44 relative flex items-end">
<svg class="w-full h-full" preserveAspectRatio="none" viewBox="0 0 600 160">
<defs>
<linearGradient id="areaCyan" x1="0" x2="0" y1="0" y2="1">
<stop offset="0%" stop-color="#00f2fe" stop-opacity="0.35"></stop>
<stop offset="100%" stop-color="#00f2fe" stop-opacity="0.0"></stop>
</linearGradient>
<linearGradient id="areaEmerald" x1="0" x2="0" y1="0" y2="1">
<stop offset="0%" stop-color="#4edea3" stop-opacity="0.25"></stop>
<stop offset="100%" stop-color="#4edea3" stop-opacity="0.0"></stop>
</linearGradient>
</defs>
<!-- Grid Lines -->
<line opacity="0.4" stroke="#31353f" stroke-dasharray="3 3" x1="0" x2="600" y1="40" y2="40"></line>
<line opacity="0.4" stroke="#31353f" stroke-dasharray="3 3" x1="0" x2="600" y1="80" y2="80"></line>
<line opacity="0.4" stroke="#31353f" stroke-dasharray="3 3" x1="0" x2="600" y1="120" y2="120"></line>
<!-- Cyan Curve (Pemasukan) -->
<path d="M0,130 C60,110 110,60 180,85 C240,110 300,20 370,40 C440,60 490,15 540,50 C570,70 600,45 600,45 L600,160 L0,160 Z" fill="url(#areaCyan)"></path>
<path d="M0,130 C60,110 110,60 180,85 C240,110 300,20 370,40 C440,60 490,15 540,50 C570,70 600,45 600,45" fill="none" stroke="#00f2fe" stroke-width="2.5"></path>
<!-- Emerald Curve (Gateway & Operasional) -->
<path d="M0,150 C80,145 150,130 220,135 C300,140 380,105 450,115 C520,125 560,95 600,105 L600,160 L0,160 Z" fill="url(#areaEmerald)"></path>
<path d="M0,150 C80,145 150,130 220,135 C300,140 380,105 450,115 C520,125 560,95 600,105" fill="none" stroke="#4edea3" stroke-width="2"></path>
</svg>
</div>
<!-- Chart X-Axis Labels -->
<div class="flex justify-between items-center pt-2 font-label-sm text-label-sm text-outline border-t border-outline-variant/20 mt-1">
<span class="">01 Okt</span>
<span class="">06 Okt</span>
<span class="">12 Okt</span>
<span class="">18 Okt</span>
<span class="">24 Okt</span>
<span class="">31 Okt (Hari Ini)</span>
</div>
</div>
<!-- Payment Channel Distribution / Settlement breakdown -->
<div class="bg-surface-container-low rounded-xl p-space-lg flex flex-col justify-between shadow-lg">
<div class="flex items-center justify-between">
<div class="flex flex-col">
<span class="font-label-sm text-label-sm text-outline uppercase tracking-wider">Metode Settlement</span>
<span class="font-headline-sm text-headline-sm text-on-surface font-bold">Kanal Pembayaran</span>
</div>
<span class="material-symbols-outlined text-primary-container text-[20px]">donut_small</span>
</div>
<div class="my-space-md flex flex-col gap-space-sm">
<!-- Item 1: Midtrans QRIS -->
<div class="flex flex-col gap-1">
<div class="flex justify-between items-center font-label-md text-label-md">
<span class="text-on-surface flex items-center gap-1.5">
<span class="w-2 h-2 rounded-full bg-primary-container"></span> Midtrans QRIS (Instant)
              </span>
<span class="text-primary font-bold">54.2%</span>
</div>
<div class="w-full bg-surface-container-highest rounded-full h-1.5 overflow-hidden">
<div class="bg-primary-container h-full rounded-full" style="width: 54.2%"></div>
</div>
</div>
<!-- Item 2: Transfer Bank BCA -->
<div class="flex flex-col gap-1">
<div class="flex justify-between items-center font-label-md text-label-md">
<span class="text-on-surface flex items-center gap-1.5">
<span class="w-2 h-2 rounded-full bg-secondary"></span> BCA Virtual Account
              </span>
<span class="text-secondary font-bold">28.5%</span>
</div>
<div class="w-full bg-surface-container-highest rounded-full h-1.5 overflow-hidden">
<div class="bg-secondary h-full rounded-full" style="width: 28.5%"></div>
</div>
</div>
<!-- Item 3: Kartu Kredit / Visa-MC -->
<div class="flex flex-col gap-1">
<div class="flex justify-between items-center font-label-md text-label-md">
<span class="text-on-surface flex items-center gap-1.5">
<span class="w-2 h-2 rounded-full bg-tertiary-fixed-dim"></span> Kartu Kredit Internasional
              </span>
<span class="text-tertiary-fixed-dim font-bold">12.1%</span>
</div>
<div class="w-full bg-surface-container-highest rounded-full h-1.5 overflow-hidden">
<div class="bg-tertiary-fixed-dim h-full rounded-full" style="width: 12.1%"></div>
</div>
</div>
<!-- Item 4: Xendit VA -->
<div class="flex flex-col gap-1">
<div class="flex justify-between items-center font-label-md text-label-md">
<span class="text-on-surface flex items-center gap-1.5">
<span class="w-2 h-2 rounded-full bg-outline"></span> Xendit Multi-Bank
              </span>
<span class="text-outline font-bold">5.2%</span>
</div>
<div class="w-full bg-surface-container-highest rounded-full h-1.5 overflow-hidden">
<div class="bg-outline h-full rounded-full" style="width: 5.2%"></div>
</div>
</div>
</div>
<div class="bg-surface-container rounded-lg p-space-xs px-space-sm flex items-center justify-between">
<span class="font-label-sm text-label-sm text-on-surface-variant">Settlement Cycle Otomatis:</span>
<span class="font-label-sm text-label-sm text-secondary font-semibold">T+1 Setiap 09:00 WIB</span>
</div>
</div>
</div>
<!-- Detailed Ledger & Mutations Table Section -->
<div class="bg-surface-container-low rounded-xl shadow-xl flex flex-col overflow-hidden">
<!-- Table Header & Controls Filter Bar -->
<div class="p-space-md sm:p-space-lg flex flex-col lg:flex-row lg:items-center justify-between gap-space-md bg-surface-container/40">
<div class="flex items-center gap-space-sm">
<div class="flex flex-col">
<div class="flex items-center gap-2">
<h2 class="font-headline-sm text-headline-sm text-on-surface font-bold">Riwayat Transaksi &amp; Mutasi Terperinci</h2>
<span class="bg-surface-container-highest text-primary-fixed text-label-sm font-label-sm px-2.5 py-0.5 rounded-full font-semibold">Live Feed</span>
</div>
<span class="font-body-sm text-body-sm text-primary opacity-80">Menampilkan 6 transaksi terbaru dari total 144 entri pada periode ini</span>
</div>
</div>
<div class="flex flex-wrap items-center gap-space-xs sm:gap-space-sm">
<!-- Filter: Status -->
<div class="flex items-center bg-surface-container px-3 py-1.5 rounded-full text-on-surface font-label-sm text-label-sm">
<span class="text-outline mr-1.5">Status:</span>
<span class="font-semibold text-secondary">Semua</span>
<span class="material-symbols-outlined text-[14px] text-outline ml-1">arrow_drop_down</span>
</div>
<!-- Filter: Metode -->
<div class="flex items-center bg-surface-container px-3 py-1.5 rounded-full text-on-surface font-label-sm text-label-sm">
<span class="text-outline mr-1.5">Gateway:</span>
<span class="font-semibold text-primary">Semua Channel</span>
<span class="material-symbols-outlined text-[14px] text-outline ml-1">arrow_drop_down</span>
</div>
<!-- Quick Search in Table -->
<div class="relative flex items-center">
<span class="material-symbols-outlined absolute left-3 text-outline text-[16px]">search</span>
<input class="bg-surface-container pl-8 pr-3 py-1.5 rounded-full font-body-sm text-body-sm text-on-surface placeholder:text-outline/70 focus:outline-none focus:ring-1 focus:ring-primary-container w-44 sm:w-56" placeholder="Cari invoice, klien..." type="text">
</div>
<!-- Export Mini Button -->
<button class="w-8 h-8 rounded-full bg-surface-container flex items-center justify-center text-outline hover:text-on-surface transition-colors" title="Print Rekapitulasi">
<span class="material-symbols-outlined text-[18px]">print</span>
</button>
</div>
</div>
<!-- Responsive Table Container -->
<div class="overflow-x-auto w-full">
<table class="w-full text-left border-collapse">
<thead>
<tr class="bg-surface-container/70 text-outline font-label-sm text-label-sm uppercase tracking-wider"><th class="py-space-sm px-space-md text-primary font-semibold tracking-wider">Invoice &amp; ID</th><th class="py-space-sm px-space-md text-primary font-semibold tracking-wider">Waktu &amp; Tanggal</th><th class="py-space-sm px-space-md text-primary font-semibold tracking-wider">Pelanggan / Entitas</th><th class="py-space-sm px-space-md text-primary font-semibold tracking-wider">Metode Pembayaran</th><th class="py-space-sm px-space-md text-primary font-semibold tracking-wider">Rincian Pembelian</th><th class="py-space-sm px-space-md text-right text-primary font-semibold tracking-wider">Nominal Transaksi</th><th class="py-space-sm px-space-md text-right text-primary font-semibold tracking-wider">Fee &amp; Pajak</th><th class="py-space-sm px-space-md text-center text-primary font-semibold tracking-wider">Status</th><th class="py-space-sm px-space-md text-center text-primary font-semibold tracking-wider">Aksi</th></tr>
</thead>
<tbody class="divide-y divide-surface-container-highest/30 font-body-sm text-body-sm">
<!-- Row 1: Lunas Web Dev -->
<tr class="hover:bg-surface-container/50 transition-colors group">
<td class="py-space-md px-space-md">
<div class="flex flex-col">
<span class="font-label-md text-label-md text-primary font-mono font-bold tracking-tight">INV-2026/X/084</span>
<span class="font-label-sm text-label-sm text-outline">TX-99201482</span>
</div>
</td>
<td class="py-space-md px-space-md whitespace-nowrap">
<div class="flex flex-col">
<span class="text-on-surface font-medium">24 Okt 2026</span>
<span class="font-label-sm text-label-sm text-outline">14:32 WIB</span>
</div>
</td>
<td class="py-space-md px-space-md">
<div class="flex items-center gap-space-xs">
<div class="w-7 h-7 rounded-full bg-primary/10 text-primary flex items-center justify-center font-bold text-xs uppercase">
                    NS
                  </div>
<div class="flex flex-col">
<span class="text-on-surface font-semibold">PT Nusantara Solusi</span>
<span class="font-label-sm text-label-sm text-outline">finance@nusantara.id</span>
</div>
</div>
</td>
<td class="py-space-md px-space-md whitespace-nowrap">
<div class="flex items-center gap-1.5 bg-surface-container px-2.5 py-1 rounded-full w-fit">
<span class="material-symbols-outlined text-[16px] text-primary-container">qr_code_2</span>
<span class="font-label-sm text-label-sm text-on-surface">Midtrans QRIS</span>
</div>
</td>
<td class="py-space-md px-space-md">
<div class="flex flex-col max-w-[200px]">
<span class="text-on-surface font-medium truncate">Layanan Web Dev Termin 1</span>
<span class="font-label-sm text-label-sm text-outline truncate">Enterprise Portal asetlab custom</span>
</div>
</td>
<td class="py-space-md px-space-md text-right whitespace-nowrap">
<span class="font-label-lg text-label-lg text-on-surface font-bold">Rp 25.000.000</span>
</td>
<td class="py-space-md px-space-md text-right whitespace-nowrap">
<div class="flex flex-col items-end">
<span class="text-error font-medium">- Rp 175.000</span>
<span class="font-label-sm text-label-sm text-outline">PPN + 0.7% MDR</span>
</div>
</td>
<td class="py-space-md px-space-md text-center whitespace-nowrap">
<span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-secondary/10 text-secondary font-label-sm text-label-sm font-semibold border-0">
<span class="w-1.5 h-1.5 rounded-full bg-secondary animate-pulse"></span>
                  Lunas / Berhasil
                </span>
</td>
<td class="py-space-md px-space-md text-center whitespace-nowrap">
<div class="flex items-center justify-center gap-1">
<button class="p-1 rounded-md hover:bg-surface-container-highest text-primary-container transition-colors" title="Unduh Invoice PDF">
<span class="material-symbols-outlined text-[18px]">download_for_offline</span>
</button>
<button class="p-1 rounded-md hover:bg-surface-container-highest text-outline hover:text-on-surface transition-colors" title="Lihat Webhook Log">
<span class="material-symbols-outlined text-[18px]">data_object</span>
</button>
</div>
</td>
</tr>
<!-- Row 2: Menunggu BCA Transfer -->
<tr class="hover:bg-surface-container/50 transition-colors group">
<td class="py-space-md px-space-md">
<div class="flex flex-col">
<span class="font-label-md text-label-md text-primary font-mono font-bold tracking-tight">INV-2026/X/083</span>
<span class="font-label-sm text-label-sm text-outline">TX-99201479</span>
</div>
</td>
<td class="py-space-md px-space-md whitespace-nowrap">
<div class="flex flex-col">
<span class="text-on-surface font-medium">24 Okt 2026</span>
<span class="font-label-sm text-label-sm text-outline">11:15 WIB</span>
</div>
</td>
<td class="py-space-md px-space-md">
<div class="flex items-center gap-space-xs">
<div class="w-7 h-7 rounded-full bg-tertiary-fixed-dim/20 text-tertiary-fixed-dim flex items-center justify-center font-bold text-xs uppercase">
                    KB
                  </div>
<div class="flex flex-col">
<span class="text-on-surface font-semibold">Kreatif Brand Studio</span>
<span class="font-label-sm text-label-sm text-outline">billing@kreatifbrand.co</span>
</div>
</div>
</td>
<td class="py-space-md px-space-md whitespace-nowrap">
<div class="flex items-center gap-1.5 bg-surface-container px-2.5 py-1 rounded-full w-fit">
<span class="material-symbols-outlined text-[16px] text-tertiary-fixed-dim">account_balance</span>
<span class="font-label-sm text-label-sm text-on-surface">Transfer BCA VA</span>
</div>
</td>
<td class="py-space-md px-space-md">
<div class="flex flex-col max-w-[200px]">
<span class="text-on-surface font-medium truncate">Lisensi Multi-App SaaS</span>
<span class="font-label-sm text-label-sm text-outline truncate">asetlab Admin Console Suite</span>
</div>
</td>
<td class="py-space-md px-space-md text-right whitespace-nowrap">
<span class="font-label-lg text-label-lg text-on-surface font-bold">Rp 6.400.000</span>
</td>
<td class="py-space-md px-space-md text-right whitespace-nowrap">
<div class="flex flex-col items-end">
<span class="text-outline font-medium">Rp 4.000</span>
<span class="font-label-sm text-label-sm text-outline">Flat VA Fee</span>
</div>
</td>
<td class="py-space-md px-space-md text-center whitespace-nowrap">
<span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-amber-500/10 text-amber-300 font-label-sm text-label-sm font-semibold">
<span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>
                  Menunggu Bayar
                </span>
</td>
<td class="py-space-md px-space-md text-center whitespace-nowrap">
<div class="flex items-center justify-center gap-1">
<button class="p-1 rounded-md hover:bg-surface-container-highest text-tertiary-fixed-dim transition-colors" title="Kirim Ulang Reminder Invoice">
<span class="material-symbols-outlined text-[18px]">forward_to_inbox</span>
</button>
<button class="p-1 rounded-md hover:bg-surface-container-highest text-outline hover:text-on-surface transition-colors" title="Lihat Webhook Log">
<span class="material-symbols-outlined text-[18px]">data_object</span>
</button>
</div>
</td>
</tr>
<!-- Row 3: Lunas Source Code License -->
<tr class="hover:bg-surface-container/50 transition-colors group">
<td class="py-space-md px-space-md">
<div class="flex flex-col">
<span class="font-label-md text-label-md text-primary font-mono font-bold tracking-tight">INV-2026/X/082</span>
<span class="font-label-sm text-label-sm text-outline">TX-99201460</span>
</div>
</td>
<td class="py-space-md px-space-md whitespace-nowrap">
<div class="flex flex-col">
<span class="text-on-surface font-medium">23 Okt 2026</span>
<span class="font-label-sm text-label-sm text-outline">21:04 WIB</span>
</div>
</td>
<td class="py-space-md px-space-md">
<div class="flex items-center gap-space-xs">
<div class="w-7 h-7 rounded-full bg-secondary/10 text-secondary flex items-center justify-center font-bold text-xs uppercase">
                    DA
                  </div>
<div class="flex flex-col">
<span class="text-on-surface font-semibold">Dimas Anggara</span>
<span class="font-label-sm text-label-sm text-outline">dimas.dev@gmail.com</span>
</div>
</div>
</td>
<td class="py-space-md px-space-md whitespace-nowrap">
<div class="flex items-center gap-1.5 bg-surface-container px-2.5 py-1 rounded-full w-fit">
<span class="material-symbols-outlined text-[16px] text-primary-container">qr_code_2</span>
<span class="font-label-sm text-label-sm text-on-surface">Midtrans QRIS</span>
</div>
</td>
<td class="py-space-md px-space-md">
<div class="flex flex-col max-w-[200px]">
<span class="text-on-surface font-medium truncate">Full-Stack Next.js Boilerplate</span>
<span class="font-label-sm text-label-sm text-outline truncate">Lisensi Komersial Developer</span>
</div>
</td>
<td class="py-space-md px-space-md text-right whitespace-nowrap">
<span class="font-label-lg text-label-lg text-on-surface font-bold">Rp 1.450.000</span>
</td>
<td class="py-space-md px-space-md text-right whitespace-nowrap">
<div class="flex flex-col items-end">
<span class="text-error font-medium">- Rp 10.150</span>
<span class="font-label-sm text-label-sm text-outline">0.7% MDR QRIS</span>
</div>
</td>
<td class="py-space-md px-space-md text-center whitespace-nowrap">
<span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-secondary/10 text-secondary font-label-sm text-label-sm font-semibold">
<span class="w-1.5 h-1.5 rounded-full bg-secondary"></span>
                  Lunas / Berhasil
                </span>
</td>
<td class="py-space-md px-space-md text-center whitespace-nowrap">
<div class="flex items-center justify-center gap-1">
<button class="p-1 rounded-md hover:bg-surface-container-highest text-primary-container transition-colors" title="Unduh Invoice PDF">
<span class="material-symbols-outlined text-[18px]">download_for_offline</span>
</button>
<button class="p-1 rounded-md hover:bg-surface-container-highest text-outline hover:text-on-surface transition-colors" title="Lihat Webhook Log">
<span class="material-symbols-outlined text-[18px]">data_object</span>
</button>
</div>
</td>
</tr>
<!-- Row 4: Kartu Kredit Internasional -->
<tr class="hover:bg-surface-container/50 transition-colors group">
<td class="py-space-md px-space-md">
<div class="flex flex-col">
<span class="font-label-md text-label-md text-primary font-mono font-bold tracking-tight">INV-2026/X/081</span>
<span class="font-label-sm text-label-sm text-outline">TX-99201433</span>
</div>
</td>
<td class="py-space-md px-space-md whitespace-nowrap">
<div class="flex flex-col">
<span class="text-on-surface font-medium">23 Okt 2026</span>
<span class="font-label-sm text-label-sm text-outline">18:22 WIB</span>
</div>
</td>
<td class="py-space-md px-space-md">
<div class="flex items-center gap-space-xs">
<div class="w-7 h-7 rounded-full bg-primary-container/20 text-primary-container flex items-center justify-center font-bold text-xs uppercase">
                    OA
                  </div>
<div class="flex flex-col">
<span class="text-on-surface font-semibold">Orbit Apex Pte Ltd</span>
<span class="font-label-sm text-label-sm text-outline">accounts@orbitapex.sg</span>
</div>
</div>
</td>
<td class="py-space-md px-space-md whitespace-nowrap">
<div class="flex items-center gap-1.5 bg-surface-container px-2.5 py-1 rounded-full w-fit">
<span class="material-symbols-outlined text-[16px] text-tertiary-fixed-dim">credit_card</span>
<span class="font-label-sm text-label-sm text-on-surface">Kartu Kredit Visa</span>
</div>
</td>
<td class="py-space-md px-space-md">
<div class="flex flex-col max-w-[200px]">
<span class="text-on-surface font-medium truncate">Sosmed Growth Engine Package</span>
<span class="font-label-sm text-label-sm text-outline truncate">Retainer Layanan Bulanan</span>
</div>
</td>
<td class="py-space-md px-space-md text-right whitespace-nowrap">
<span class="font-label-lg text-label-lg text-on-surface font-bold">Rp 18.500.000</span>
</td>
<td class="py-space-md px-space-md text-right whitespace-nowrap">
<div class="flex flex-col items-end">
<span class="text-error font-medium">- Rp 536.500</span>
<span class="font-label-sm text-label-sm text-outline">2.9% Visa Processing</span>
</div>
</td>
<td class="py-space-md px-space-md text-center whitespace-nowrap">
<span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-secondary/10 text-secondary font-label-sm text-label-sm font-semibold">
<span class="w-1.5 h-1.5 rounded-full bg-secondary"></span>
                  Lunas / Berhasil
                </span>
</td>
<td class="py-space-md px-space-md text-center whitespace-nowrap">
<div class="flex items-center justify-center gap-1">
<button class="p-1 rounded-md hover:bg-surface-container-highest text-primary-container transition-colors" title="Unduh Invoice PDF">
<span class="material-symbols-outlined text-[18px]">download_for_offline</span>
</button>
<button class="p-1 rounded-md hover:bg-surface-container-highest text-outline hover:text-on-surface transition-colors" title="Lihat Webhook Log">
<span class="material-symbols-outlined text-[18px]">data_object</span>
</button>
</div>
</td>
</tr>
<!-- Row 5: Menunggu Xendit VA -->
<tr class="hover:bg-surface-container/50 transition-colors group">
<td class="py-space-md px-space-md">
<div class="flex flex-col">
<span class="font-label-md text-label-md text-primary font-mono font-bold tracking-tight">INV-2026/X/080</span>
<span class="font-label-sm text-label-sm text-outline">TX-99201410</span>
</div>
</td>
<td class="py-space-md px-space-md whitespace-nowrap">
<div class="flex flex-col">
<span class="text-on-surface font-medium">22 Okt 2026</span>
<span class="font-label-sm text-label-sm text-outline">16:45 WIB</span>
</div>
</td>
<td class="py-space-md px-space-md">
<div class="flex items-center gap-space-xs">
<div class="w-7 h-7 rounded-full bg-surface-container-highest text-on-surface flex items-center justify-center font-bold text-xs uppercase">
                    RM
                  </div>
<div class="flex flex-col">
<span class="text-on-surface font-semibold">Raditya Mandiri</span>
<span class="font-label-sm text-label-sm text-outline">raditya@tokoonline.id</span>
</div>
</div>
</td>
<td class="py-space-md px-space-md whitespace-nowrap">
<div class="flex items-center gap-1.5 bg-surface-container px-2.5 py-1 rounded-full w-fit">
<span class="material-symbols-outlined text-[16px] text-primary">account_balance</span>
<span class="font-label-sm text-label-sm text-on-surface">Xendit Mandiri VA</span>
</div>
</td>
<td class="py-space-md px-space-md">
<div class="flex flex-col max-w-[200px]">
<span class="text-on-surface font-medium truncate">Setup Cloud Server &amp; DB</span>
<span class="font-label-sm text-label-sm text-outline truncate">DevOps Fast Deployment</span>
</div>
</td>
<td class="py-space-md px-space-md text-right whitespace-nowrap">
<span class="font-label-lg text-label-lg text-on-surface font-bold">Rp 2.000.000</span>
</td>
<td class="py-space-md px-space-md text-right whitespace-nowrap">
<div class="flex flex-col items-end">
<span class="text-outline font-medium">Rp 4.500</span>
<span class="font-label-sm text-label-sm text-outline">Xendit VA Flat</span>
</div>
</td>
<td class="py-space-md px-space-md text-center whitespace-nowrap">
<span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-amber-500/10 text-amber-300 font-label-sm text-label-sm font-semibold">
<span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>
                  Menunggu Bayar
                </span>
</td>
<td class="py-space-md px-space-md text-center whitespace-nowrap">
<div class="flex items-center justify-center gap-1">
<button class="p-1 rounded-md hover:bg-surface-container-highest text-tertiary-fixed-dim transition-colors" title="Kirim Ulang Reminder Invoice">
<span class="material-symbols-outlined text-[18px]">forward_to_inbox</span>
</button>
<button class="p-1 rounded-md hover:bg-surface-container-highest text-outline hover:text-on-surface transition-colors" title="Lihat Webhook Log">
<span class="material-symbols-outlined text-[18px]">data_object</span>
</button>
</div>
</td>
</tr>
<!-- Row 6: Kedaluwarsa QRIS -->
<tr class="hover:bg-surface-container/50 transition-colors group hover:opacity-100">
<td class="py-space-md px-space-md">
<div class="flex flex-col">
<span class="font-label-md text-label-md text-outline font-mono font-bold tracking-tight">INV-2026/X/079</span>
<span class="font-label-sm text-label-sm text-outline">TX-99201398</span>
</div>
</td>
<td class="py-space-md px-space-md whitespace-nowrap">
<div class="flex flex-col">
<span class="text-on-surface font-medium">21 Okt 2026</span>
<span class="font-label-sm text-label-sm text-outline">09:12 WIB</span>
</div>
</td>
<td class="py-space-md px-space-md">
<div class="flex items-center gap-space-xs">
<div class="w-7 h-7 rounded-full bg-error-container/30 text-error flex items-center justify-center font-bold text-xs uppercase">
                    SL
                  </div>
<div class="flex flex-col">
<span class="text-on-surface font-semibold">Studio Lentera</span>
<span class="font-label-sm text-label-sm text-outline">info@lentera.media</span>
</div>
</div>
</td>
<td class="py-space-md px-space-md whitespace-nowrap">
<div class="flex items-center gap-1.5 bg-surface-container px-2.5 py-1 rounded-full w-fit">
<span class="material-symbols-outlined text-[16px] text-outline">qr_code_2</span>
<span class="font-label-sm text-label-sm text-on-surface-variant">Midtrans QRIS</span>
</div>
</td>
<td class="py-space-md px-space-md">
<div class="flex flex-col max-w-[200px]">
<span class="text-on-surface font-medium truncate">Template UI Figma asetlab</span>
<span class="font-label-sm text-label-sm text-outline truncate">Personal UI Kit Asset</span>
</div>
</td>
<td class="py-space-md px-space-md text-right whitespace-nowrap">
<span class="font-label-lg text-label-lg text-outline font-bold line-through">Rp 420.000</span>
</td>
<td class="py-space-md px-space-md text-right whitespace-nowrap">
<span class="text-outline">Rp 0</span>
</td>
<td class="py-space-md px-space-md text-center whitespace-nowrap">
<span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-error-container/20 text-error font-label-sm text-label-sm font-semibold">
<span class="w-1.5 h-1.5 rounded-full bg-error"></span>
                  Kedaluwarsa
                </span>
</td>
<td class="py-space-md px-space-md text-center whitespace-nowrap">
<div class="flex items-center justify-center gap-1">
<button class="p-1 rounded-md hover:bg-surface-container-highest text-outline hover:text-on-surface transition-colors" title="Lihat Webhook Log">
<span class="material-symbols-outlined text-[18px]">data_object</span>
</button>
</div>
</td>
</tr>
</tbody>
</table>
</div>
<!-- Pagination & Ledger Summary Footer -->
<div class="p-space-md flex flex-col sm:flex-row items-center justify-between gap-space-sm bg-surface-container/60 border-t border-outline-variant/20">
<div class="flex items-center gap-2">
<span class="font-body-sm text-body-sm text-outline">Tampilkan:</span>
<select class="bg-surface-container-low text-on-surface font-label-sm text-label-sm rounded-full px-2 py-1 border-0 focus:ring-1 focus:ring-primary-container">
<option>10 Baris</option>
<option>25 Baris</option>
<option>50 Baris</option>
</select>
<span class="font-body-sm text-body-sm text-outline ml-2">Total 144 Transaksi Tercatat</span>
</div>
<div class="flex items-center gap-1">
<button class="w-8 h-8 rounded-full bg-surface-container flex items-center justify-center text-outline hover:text-on-surface transition-colors disabled:opacity-40" disabled="">
<span class="material-symbols-outlined text-[16px]">chevron_left</span>
</button>
<button class="w-8 h-8 rounded-full bg-primary-container text-surface-container-lowest font-label-md text-label-md font-bold">1</button>
<button class="w-8 h-8 rounded-full hover:bg-surface-container text-outline hover:text-on-surface font-label-md text-label-md transition-colors">2</button>
<button class="w-8 h-8 rounded-full hover:bg-surface-container text-outline hover:text-on-surface font-label-md text-label-md transition-colors">3</button>
<span class="text-outline px-1">...</span>
<button class="w-8 h-8 rounded-full hover:bg-surface-container text-outline hover:text-on-surface font-label-md text-label-md transition-colors">15</button>
<button class="w-8 h-8 rounded-full bg-surface-container flex items-center justify-center text-outline hover:text-on-surface transition-colors">
<span class="material-symbols-outlined text-[16px]">chevron_right</span>
</button>
</div>
</div>
</div>
<!-- Webhook Health & Sync Status Bottom Strip -->
<div class="bg-surface-container-low rounded-xl p-space-md flex flex-col md:flex-row items-center justify-between gap-space-md shadow-sm">
<div class="flex items-center gap-space-sm">
<div class="w-10 h-10 rounded-full bg-secondary/10 flex items-center justify-center text-secondary">
<span class="material-symbols-outlined text-[22px]">bolt</span>
</div>
<div class="flex flex-col">
<div class="flex items-center gap-2">
<span class="font-label-md text-label-md text-on-surface font-bold">Sinkronisasi Endpoint Webhook Aktif</span>
<span class="w-2 h-2 rounded-full bg-secondary"></span>
</div>
<span class="font-body-sm text-body-sm text-outline">Callback Midtrans (https://api.asetlab.id/v1/payments/midtrans) latensi: 42ms</span>
</div>
</div>
<div class="flex items-center gap-space-md">
<div class="flex flex-col items-end">
<span class="font-label-sm text-label-sm text-outline">Terakhir sinkronisasi</span>
<span class="font-label-md text-label-md text-on-surface font-mono font-bold">14:32:04 WIB (Baru saja)</span>
</div>
<button  class="bg-surface-container hover:bg-surface-container-high text-primary-container px-4 py-2 rounded-full font-label-md text-label-md transition-all flex items-center gap-1.5" id="webhookTestBtn" @click="testWebhook" :disabled="isPingingWebhook">
<span class="material-symbols-outlined text-[16px]">wifi_tethering</span>
<span class="">Test Ping Endpoint</span>
</button>
</div>
</div>
</div>
<!-- Interactive Toast Notification -->
<div class="fixed bottom-6 right-6 bg-surface-container-highest text-on-surface px-space-md py-space-sm rounded-full shadow-[0_8px_30px_rgba(0,0,0,0.6)] flex items-center gap-space-sm opacity-0 pointer-events-none transition-all duration-300 z-50" id="reconToast" :class="showToast ? 'opacity-100' : 'opacity-0 pointer-events-none'">
<span class="material-symbols-outlined text-secondary text-[20px]">check_circle</span>
<span class="font-label-md text-label-md">Rekonsiliasi otomatis 144 mutasi selesai: SINKRON 100%.</span>
</div>

</div>
  </div>
</template>


<script setup>
import { ref } from 'vue';
import { transactions, formatRupiah } from '@/data/mockData';

const isReconciling = ref(false);
const showToast = ref(false);
const isPingingWebhook = ref(false);

function reconcileLedger() {
  isReconciling.value = true;
  setTimeout(() => {
    isReconciling.value = false;
    showToast.value = true;
    setTimeout(() => {
      showToast.value = false;
    }, 3500);
  }, 1200);
}

function testWebhook() {
  isPingingWebhook.value = true;
  setTimeout(() => {
    isPingingWebhook.value = false;
    alert('Webhook Gateway Ping: Midtrans & BCA VA Status 200 OK (Latency: 34ms). Endpoint tersinkron.');
  }, 700);
}

function downloadCsvReport() {
  let csv = 'ID,Invoice,Tanggal,Klien,Kategori,Metode,Nominal,Status\n';
  transactions.value.forEach(t => {
    csv += `"${t.id}","${t.refInvoice}","${t.date}","${t.client}","${t.category}","${t.method}",${t.amount},"${t.status}"\n`;
  });

  const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
  const url = URL.createObjectURL(blob);
  const link = document.createElement('a');
  link.setAttribute('href', url);
  link.setAttribute('download', `laporan-transaksi-asetlab-${new Date().toISOString().slice(0, 10)}.csv`);
  document.body.appendChild(link);
  link.click();
  document.body.removeChild(link);
}
</script>
