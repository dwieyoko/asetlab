<template>
  <div>
    <div class="flex flex-col w-full overflow-hidden">
<!-- Ambient Atmospheric Blooms -->
<div class="relative w-full">
<div class="absolute top-10 right-1/4 w-[500px] h-[500px] bg-primary-container/10 rounded-full blur-[140px] pointer-events-none -z-10"></div>
<div class="absolute top-48 left-10 w-[350px] h-[350px] bg-secondary/10 rounded-full blur-[120px] pointer-events-none -z-10"></div>
<!-- HERO SECTION -->
<section class="relative w-full px-margin-mobile md:px-margin pt-space-lg md:pt-space-xl pb-space-xl">
<div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-12 gap-gutter items-center">
<!-- Left Content Column -->
<div class="lg:col-span-7 flex flex-col items-start z-10">
<!-- Tag Badge -->
<div class="inline-flex items-center gap-space-xs px-space-md py-1 rounded-full bg-surface-container-high/70 shadow-sm mb-space-md">
<span class="w-2 h-2 rounded-full bg-secondary animate-pulse"></span>
<span class="font-label-sm text-label-sm uppercase tracking-wider text-secondary">Tech Accelerator &amp; Creative Studio</span>
</div>
<!-- Main Hero Headline -->
<h1 class="font-display-hero-mobile md:font-display-hero text-display-hero-mobile md:text-display-hero text-tertiary tracking-tight font-extrabold max-w-2xl leading-[1.15]">
            Laboratorium 
            <span class="bg-gradient-to-r from-primary-container via-surface-tint to-secondary bg-clip-text text-transparent">
              Solusi Digital
            </span> 
            untuk 
            <span class="bg-gradient-to-r from-surface-tint to-secondary bg-clip-text text-transparent">
              Akselerasi Bisnis
            </span> 
            Anda
          </h1>
<!-- Subheadline -->
<p class="font-body-lg text-body-lg text-on-surface mt-space-md max-w-xl leading-relaxed">
            Kami membantu mentransformasi dan melejitkan potensi bisnis Anda melalui produk digital, pembuatan website modern, dan pengelolaan media sosial yang inovatif.
          </p>
<!-- Action Buttons -->
<div class="flex flex-wrap items-center gap-space-md mt-space-xl">
<a class="inline-flex items-center justify-center px-8 py-3.5 rounded-full bg-gradient-to-r from-primary-container via-surface-tint to-secondary text-on-primary font-label-lg text-label-lg font-bold shadow-[0_4px_25px_rgba(0,242,254,0.38)] hover:shadow-[0_6px_32px_rgba(0,242,254,0.55)] hover:scale-[1.02] active:scale-[0.98] transition-all duration-300" href="/layanan">
              Eksplorasi Layanan
              <span class="material-symbols-outlined text-[18px] ml-1.5">arrow_forward</span>
</a>
<a class="inline-flex items-center justify-center px-7 py-3.5 rounded-full bg-surface-container-high/60 hover:bg-surface-container-highest text-tertiary font-label-lg text-label-lg font-semibold shadow-sm transition-all duration-200" @click.prevent="openConsultModal">
              Konsultasi Gratis
            </a>
</div>
<!-- Trust Badges Under Buttons -->
<div class="flex items-center gap-space-lg mt-space-xl pt-space-md">
<div class="flex -space-x-2.5 overflow-hidden">
<img class="inline-block h-8 w-8 rounded-full object-cover" data-alt="Close up modern tech developer portrait glowing cyan rim light dark cybernetic aesthetic" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBsG24yZr8lOo19UiP2mmk12yggLnokqBw6-04KyDJaffjOp9oKTRlJa5S1ZI1IApk53Fas6zWx9HftiX21eVE12D94355GNT8vURJYe2EwLz0zxV7aa4Rx4ZhulwLOVCzV3y4QzgpFooXBpzJf5lNJeS0EKR78vXVapOMtTfJFuOiSU-AS-0nF5d2DSLK1gnLJ2S-8vfvSI2DkzEEEkJIEyqizIdlUTT0cxOLEgS2qO7LHKRdtgSnY4A">
<img class="inline-block h-8 w-8 rounded-full object-cover" data-alt="Asian professional female UI designer avatar portrait sleek studio lighting dark slate background" src="https://lh3.googleusercontent.com/aida-public/AB6AXuB5PTQIeTVaAT6R-XDADGuRAXzRdY5qLwkE-w4zgfmQhUNvA24iCkA7Aq7jxZUBu9d0QM54WTYmfgOQEeSAoEFx3fUtLK7voMIsF5ZwGua8la5IsqJKtcUe5bWrUe6pRJ7U1zUGVpfeaLXw0hCKvqpguYqqbcY5S9VRGyyB9yJZmoerCmYi_18vjd0whHp2PHDKO6O4CSDRHft0YciAY441DQx1Gc411N_fXlFml-s2HmR-coZUxCUc8Q">
<img class="inline-block h-8 w-8 rounded-full object-cover" data-alt="Tech founder creative director headshot portrait neon emerald luminescence deep navy background" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCLl94JZ4kGRP67QKvRvoSNxeX1RqtMC9aajgU-lA4ehu8jbKWEBnSmai24xVujV-aJ_voffRYeoXRH8TaUJ0DgUFn7nXCh7rw_xD5RsVa8AWHal2ElGVpZCztupCXtJ9ilugvJmcX7_c6AVizYNYCG4z17mcAYN2zTqZaUXLALBSbV4mPlJGS_bwt4KsAamomqzPRc5cEGBEUoR0Ss2In8OKcn8TtQQ5Xsb5I5X_Tvp-49qDI4WZVa6A">
<div class="flex items-center justify-center h-8 w-8 rounded-full bg-surface-container-highest font-label-sm text-label-sm text-primary font-bold">
                +4k
              </div>
</div>
<div class="flex flex-col">
<div class="flex items-center gap-1 text-secondary">
<span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="font-label-sm text-label-sm text-on-surface font-semibold ml-1">4.9 / 5.0</span>
</div>
<span class="font-body-sm text-body-sm text-on-surface">Dipercaya 150+ bisnis digital nasional</span>
</div>
</div>
</div>
<!-- Right 3D Cybernetic Visual Canvas -->
<div class="lg:col-span-5 relative mt-space-lg lg:mt-0 flex items-center justify-center">
<!-- Cyber Holographic Mockup Illustration Container -->
<div class="relative w-full max-w-lg aspect-square flex items-center justify-center">
<!-- Perspective Matrix Grid SVG -->
<svg class="absolute inset-x-0 bottom-0 w-full h-48 opacity-40 pointer-events-none" fill="none" viewBox="0 0 500 200" xmlns="http://www.w3.org/2000/svg">
<defs>
<linearGradient gradientUnits="userSpaceOnUse" id="gridFade" x1="250" x2="250" y1="0" y2="200">
<stop stop-color="#00F2FE" stop-opacity="0"></stop>
<stop offset="0.6" stop-color="#00F2FE" stop-opacity="0.3"></stop>
<stop offset="1" stop-color="#4EDEA3" stop-opacity="0.6"></stop>
</linearGradient>
</defs>
<g stroke="url(#gridFade)" stroke-width="0.75">
<path d="M0 190 H500 M0 160 H500 M0 130 H500 M0 100 H500 M0 70 H500"></path>
<path d="M250 40 L50 200 M250 40 L110 200 M250 40 L180 200 M250 40 L250 200 M250 40 L320 200 M250 40 L390 200 M250 40 L450 200"></path>
</g>
</svg>
<!-- Radial Halo Underneath Hardware -->
<div class="absolute w-72 h-72 rounded-full bg-gradient-to-tr from-primary-container/20 to-secondary/25 blur-3xl pointer-events-none"></div>
<!-- Central Graphic / Holographic Scene -->
<div class="relative z-10 w-full flex items-center justify-center">
<img class="w-full h-auto max-h-[460px] object-contain drop-shadow-[0_20px_45px_rgba(0,242,254,0.25)] rounded-2xl" data-alt="Futuristic cybernetic workstation mockup featuring a floating dark obsidian laptop with vibrant glowing cyan and emerald analytics charts on the screen, a suspended smartphone displaying digital crypto assets with luminous coin symbol, glowing cyan circuit board traces, futuristic sci-fi holographic hud rings, dark navy background with subtle grid floor reflections." src="https://lh3.googleusercontent.com/aida-public/AB6AXuBg9T4DTRaBkFvrg4pFnhR2FgqglJ4EpxLOjsVpsi1Fq54VWbRkdS-DNQBw27OBFPqcGKygWzveoIyWU-pyyvPRgVroCHVK_EzXkPn-9Thj7ZksV_eQTkNivC5Y6_65ZhjVQBlquaRBZoJGHxXw4YCx98VAmpdZlpJcpAd9-E9-mobxP1EEp0meRavidw2lJICGSZYNFZE7_fQMC8hfRgkDnWTf6cOy0t0wWxQt0ebR7xqGMgdGOAGopg">
<!-- Floating Micro Glass Card 1 (Top Right KPI) -->
<div class="absolute -top-3 -right-2 bg-surface-container-high/85 backdrop-blur-md px- space-sm py-2 px-3 rounded-2xl shadow-xl flex items-center gap-2">
<div class="w-7 h-7 rounded-lg bg-secondary/15 flex items-center justify-center text-secondary">
<span class="material-symbols-outlined text-[16px]">trending_up</span>
</div>
<div>
<div class="font-label-sm text-label-sm text-secondary font-bold">+284.6%</div>
<div class="font-label-sm text-[10px] text-on-surface-variant">Growth Velocity</div>
</div>
</div>
<!-- Floating Micro Glass Card 2 (Bottom Left Security Node) -->
<div class="absolute -bottom-2 -left-2 bg-surface-container-high/85 backdrop-blur-md px-3 py-2 rounded-2xl shadow-xl flex items-center gap-2">
<div class="w-7 h-7 rounded-lg bg-primary-container/15 flex items-center justify-center text-primary-container">
<span class="material-symbols-outlined text-[16px]">verified_user</span>
</div>
<div>
<div class="font-label-sm text-label-sm text-primary font-bold">100% Secure</div>
<div class="font-label-sm text-[10px] text-on-surface-variant">Cloud Node Active</div>
</div>
</div>
</div>
</div>
</div>
</div>
</section>
<!-- SECTION 2: STATS / METRICS BANNER -->
<section class="w-full px-margin-mobile md:px-margin py-space-md">
<div class="max-w-7xl mx-auto bg-surface-container-low/80 backdrop-blur-md rounded-2xl p-space-lg shadow-sm">
<div class="grid grid-cols-2 md:grid-cols-4 gap-gutter text-center md:text-left">
<div class="flex flex-col items-center md:items-start p-space-sm">
<span class="font-headline-lg text-headline-lg font-bold text-tertiary">150<span class="text-secondary">+</span></span>
<span class="font-label-md text-label-md text-on-surface font-semibold mt-1">Proyek Selesai</span>
<span class="font-body-sm text-body-sm text-on-surface-variant mt-0.5">Website, web app, &amp; ekosistem brand</span>
</div>
<div class="flex flex-col items-center md:items-start p-space-sm">
<span class="font-headline-lg text-headline-lg font-bold text-tertiary">98.4<span class="text-primary-container">%</span></span>
<span class="font-label-md text-label-md text-on-surface font-semibold mt-1">Kepuasan Klien</span>
<span class="font-body-sm text-body-sm text-on-surface-variant mt-0.5">Berdasarkan ulasan terverifikasi CSAT</span>
</div>
<div class="flex flex-col items-center md:items-start p-space-sm">
<span class="font-headline-lg text-headline-lg font-bold text-tertiary">50<span class="text-secondary">+</span></span>
<span class="font-label-md text-label-md text-on-surface font-semibold mt-1">Produk Digital</span>
<span class="font-body-sm text-body-sm text-on-surface-variant mt-0.5">Ready-to-use template &amp; toolkit</span>
</div>
<div class="flex flex-col items-center md:items-start p-space-sm">
<span class="font-headline-lg text-headline-lg font-bold text-tertiary">24<span class="text-primary-container">/7</span></span>
<span class="font-label-md text-label-md text-on-surface font-semibold mt-1">Dedicated Support</span>
<span class="font-body-sm text-body-sm text-on-surface-variant mt-0.5">Pendampingan teknis &amp; konsultasi</span>
</div>
</div>
</div>
</section>
<!-- SECTION 3: LAYANAN KAMI (CORE SERVICES GRID) -->
<section class="w-full px-margin-mobile md:px-margin pt-space-xl pb-space-xl" id="layanan">
<div class="max-w-7xl mx-auto flex flex-col">
<!-- Section Header -->
<div class="flex flex-col md:flex-row md:items-end justify-between mb-space-xl">
<div>
<div class="font-label-sm text-label-sm uppercase tracking-widest text-primary-container font-bold mb-space-xs">
              Katalog Solusi
            </div>
<h2 class="font-headline-lg text-headline-lg text-tertiary font-bold tracking-tight">
              Layanan Kami
            </h2>
</div>
<p class="font-body-md text-body-md text-on-surface max-w-md mt-space-sm md:mt-0">
            Arsitektur digital komprehensif dirancang khusus untuk mempercepat pertumbuhan dan ekspansi market bisnis modern.
          </p>
</div>
<!-- 3 Interactive Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-gutter"><div class="group relative rounded-2xl bg-surface-container/70 backdrop-blur-md p-space-lg flex flex-col justify-between transition-all duration-300 hover:bg-surface-container-high/80 hover:-translate-y-1.5 shadow-lg shadow-black/40">
<div class="absolute top-0 left-8 right-8 h-px bg-gradient-to-r from-transparent via-primary-container to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
<div>
<div class="w-14 h-14 rounded-2xl bg-surface-container-highest flex items-center justify-center text-primary-container shadow-inner mb-space-lg group-hover:bg-primary-container/20 transition-colors">
<span class="material-symbols-outlined text-[28px]">code_blocks</span>
</div>
<div class="flex items-center justify-between mb-space-xs">
<h3 class="font-headline-sm text-headline-sm text-tertiary font-bold group-hover:text-primary-container transition-colors">
                  Web Development
                </h3>
<span class="font-label-sm text-label-sm text-outline-variant font-mono">01</span>
</div>
<p class="font-body-md text-body-md text-on-surface mt-space-sm leading-relaxed">
                Pembuatan website berkecepatan tinggi, responsif di seluruh perangkat, berorientasi konversi, dan SEO-friendly untuk daya saing bisnis maksimal.
              </p>
<div class="flex flex-wrap gap-1.5 mt-space-md">
<span class="px-2.5 py-1 rounded-full bg-surface-container-highest/80 text-primary font-label-sm text-label-sm">React &amp; Next.js</span>
<span class="px-2.5 py-1 rounded-full bg-surface-container-highest/80 text-primary font-label-sm text-label-sm">Custom CMS</span>
<span class="px-2.5 py-1 rounded-full bg-surface-container-highest/80 text-primary font-label-sm text-label-sm">API Integration</span>
</div>
</div>
<div class="pt-space-lg mt-space-lg flex items-center justify-between">
<span class="font-label-sm text-label-sm text-secondary font-semibold">Mulai Proyek Web</span>
<div class="w-8 h-8 rounded-full bg-surface-container-highest flex items-center justify-center text-on-surface group-hover:bg-primary-container group-hover:text-on-primary transition-colors">
<span class="material-symbols-outlined text-[16px]">arrow_forward</span>
</div>
</div>
</div>
<div class="group relative rounded-2xl bg-surface-container/70 backdrop-blur-md p-space-lg flex flex-col justify-between transition-all duration-300 hover:bg-surface-container-high/80 hover:-translate-y-1.5 shadow-lg shadow-black/40">
<div class="absolute top-0 left-8 right-8 h-px bg-gradient-to-r from-transparent via-secondary to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
<div>
<div class="w-14 h-14 rounded-2xl bg-surface-container-highest flex items-center justify-center text-secondary shadow-inner mb-space-lg group-hover:bg-secondary/20 transition-colors">
<span class="material-symbols-outlined text-[28px]">token</span>
</div>
<div class="flex items-center justify-between mb-space-xs">
<h3 class="font-headline-sm text-headline-sm text-tertiary font-bold group-hover:text-secondary transition-colors">
                  Digital Assets
                </h3>
<span class="font-label-sm text-label-sm text-outline-variant font-mono">02</span>
</div>
<p class="font-body-md text-body-md text-on-surface mt-space-sm leading-relaxed">
                Akses kurasi aset digital siap pakai: template desain UI/UX, workflow otomatisasi, e-book bisnis, dan kit grafis berskala profesional.
              </p>
<div class="flex flex-wrap gap-1.5 mt-space-md">
<span class="px-2.5 py-1 rounded-full bg-surface-container-highest/80 text-secondary font-label-sm text-label-sm">Figma Design Kits</span>
<span class="px-2.5 py-1 rounded-full bg-surface-container-highest/80 text-secondary font-label-sm text-label-sm">Workflow Stacks</span>
<span class="px-2.5 py-1 rounded-full bg-surface-container-highest/80 text-secondary font-label-sm text-label-sm">E-Books</span>
</div>
</div>
<div class="pt-space-lg mt-space-lg flex items-center justify-between">
<span class="font-label-sm text-label-sm text-secondary font-semibold">Jelajahi Aset Toko</span>
<div class="w-8 h-8 rounded-full bg-surface-container-highest flex items-center justify-center text-on-surface group-hover:bg-secondary group-hover:text-on-secondary transition-colors">
<span class="material-symbols-outlined text-[16px]">arrow_forward</span>
</div>
</div>
</div>
<div class="group relative rounded-2xl bg-surface-container/70 backdrop-blur-md p-space-lg flex flex-col justify-between transition-all duration-300 hover:bg-surface-container-high/80 hover:-translate-y-1.5 shadow-lg shadow-black/40">
<div class="absolute top-0 left-8 right-8 h-px bg-gradient-to-r from-transparent via-primary-container to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
<div>
<div class="w-14 h-14 rounded-2xl bg-surface-container-highest flex items-center justify-center text-primary-container shadow-inner mb-space-lg group-hover:bg-primary-container/20 transition-colors">
<span class="material-symbols-outlined text-[28px]">cell_tower</span>
</div>
<div class="flex items-center justify-between mb-space-xs">
<h3 class="font-headline-sm text-headline-sm text-tertiary font-bold group-hover:text-primary-container transition-colors">
                  Social Media Management
                </h3>
<span class="font-label-sm text-label-sm text-outline-variant font-mono">03</span>
</div>
<p class="font-body-md text-body-md text-on-surface mt-space-sm leading-relaxed">
                Manajemen akun media sosial menyeluruh mulai dari riset audiens, produksi konten visual &amp; copywriting, hingga reporting engagement data bulanan.
              </p>
<div class="flex flex-wrap gap-1.5 mt-space-md">
<span class="px-2.5 py-1 rounded-full bg-surface-container-highest/80 text-primary font-label-sm text-label-sm">Creative Copy</span>
<span class="px-2.5 py-1 rounded-full bg-surface-container-highest/80 text-primary font-label-sm text-label-sm">Video Reels / Shorts</span>
<span class="px-2.5 py-1 rounded-full bg-surface-container-highest/80 text-primary font-label-sm text-label-sm">Growth Analytics</span>
</div>
</div>
<div class="pt-space-lg mt-space-lg flex items-center justify-between">
<span class="font-label-sm text-label-sm text-secondary font-semibold">Tingkatkan Engagement</span>
<div class="w-8 h-8 rounded-full bg-surface-container-highest flex items-center justify-center text-on-surface group-hover:bg-primary-container group-hover:text-on-primary transition-colors">
<span class="material-symbols-outlined text-[16px]">arrow_forward</span>
</div>
</div>
</div></div>
</div>
</section>
<!-- SECTION 4: SHOWCASE HIGHLIGHT (BENTO PREVIEW) -->
<section class="w-full px-margin-mobile md:px-margin py-space-xl">
<div class="max-w-7xl mx-auto rounded-3xl bg-surface-container-low/90 backdrop-blur-xl p-space-lg md:p-space-xl shadow-2xl relative overflow-hidden">
<!-- Inner glow blob -->
<div class="absolute -bottom-24 -right-24 w-80 h-80 bg-primary-container/10 rounded-full blur-3xl pointer-events-none"></div>
<div class="grid grid-cols-1 lg:grid-cols-12 gap-gutter items-center">
<div class="lg:col-span-6 flex flex-col">
<span class="font-label-sm text-label-sm uppercase tracking-wider text-secondary font-bold">Produk Digital Terpopuler</span>
<h3 class="font-headline-lg text-headline-lg text-tertiary font-bold mt-space-xs">
              SaaS Starter Kit &amp; Design System UI v2.4
            </h3>
<p class="font-body-md text-body-md text-on-surface mt-space-sm leading-relaxed">
              Kembangkan aplikasi web enterprise 5x lebih cepat dengan komponen Figma yang telah terintegrasi langsung dengan Next.js App Router dan Tailwind CSS tokens.
            </p>
<div class="space-y-space-sm mt-space-md">
<div class="flex items-center gap-space-sm">
<span class="material-symbols-outlined text-secondary text-[20px]">check_circle</span>
<span class="font-body-md text-body-md text-on-surface">300+ Komponen UI responsif berstandar accessibility (WCAG AA)</span>
</div>
<div class="flex items-center gap-space-sm">
<span class="material-symbols-outlined text-secondary text-[20px]">check_circle</span>
<span class="font-body-md text-body-md text-on-surface">Dark mode default dengan token cyber-luminescence presisi</span>
</div>
<div class="flex items-center gap-space-sm">
<span class="material-symbols-outlined text-secondary text-[20px]">check_circle</span>
<span class="font-body-md text-body-md text-on-surface">Free update berkala selamanya dan dokumentasi implementasi</span>
</div>
</div>
<div class="flex items-center gap-space-md mt-space-lg">
<a class="inline-flex items-center justify-center px-6 py-3 rounded-full bg-secondary text-on-secondary font-label-md text-label-md font-bold shadow-[0_4px_20px_rgba(78,222,163,0.3)] hover:brightness-110 transition-all" href="#">
                Dapatkan Toolkit Ini
                <span class="material-symbols-outlined text-[16px] ml-1">download</span>
</a>
<span class="font-label-md text-label-md text-on-surface-variant">Rp 349.000 <span class="line-through text-outline text-xs">Rp 890.000</span></span>
</div>
</div>
<div class="lg:col-span-6 relative flex justify-center">
<div class="w-full max-w-md rounded-2xl overflow-hidden shadow-2xl bg-surface-container">
<img class="w-full h-auto object-cover hover:scale-105 transition-transform duration-500" data-alt="Modern dark UI SaaS dashboard screenshot showing intricate dark navy components, glowing cyan metrics, active status pill badges, financial conversion graphs, and crisp modular widgets, professional UI design presentation on obsidian background" src="https://lh3.googleusercontent.com/aida-public/AB6AXuD3rSCUU2yj_9DuXG6j0xlADefPGsLQfIESW0cH3w4dAngSMbCibeiQrFwji5RH5Tsh9-GSxRQBDd7YEPw0j4EFmsiALcid1F7b2uza1kQU5SnmK8SZ4qVglMe5A1AUYbzFiceZsxl-RoSRVXmnvewuld5DQF86BZvkDTPhREUs54Jh8D-PYGMZ0c_svMXOBUUTYDm56aa8Zpli7CUaZVASsoJws0T6PLEfynkTzt3MDiwbSMf1-91s7w">
</div>
</div>
</div>
</div>
</section>
<!-- SECTION 5: CLIENT TESTIMONIALS -->
<section class="w-full px-margin-mobile md:px-margin py-space-xl">
<div class="max-w-7xl mx-auto flex flex-col items-center">
<div class="text-center max-w-xl mb-space-xl">
<span class="font-label-sm text-label-sm uppercase tracking-wider text-primary-container font-bold">Kisah Sukses Klien</span>
<h2 class="font-headline-lg text-headline-lg text-tertiary font-bold tracking-tight mt-space-xs">
            Apa Kata Mitra yang Berakselerasi Bersama Kami
          </h2>
</div>
<div class="grid grid-cols-1 md:grid-cols-3 gap-gutter w-full"><div class="rounded-2xl bg-surface-container/60 p-space-lg flex flex-col justify-between shadow-md">
<div>
<div class="flex items-center gap-1 text-secondary mb-space-sm">
<span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">star</span>
</div>
<p class="font-body-md text-body-md text-on-surface italic leading-relaxed">
                “Website baru dari asetlab.id berhasil meningkatkan conversion rate layanan fintech kami hingga 140% dalam 2 bulan pertama. Kualitas visualnya luar biasa futuristik!”
              </p>
</div>
<div class="flex items-center gap-space-sm mt-space-md pt-space-sm">
<img class="w-10 h-10 rounded-full object-cover" data-alt="Portrait photo of a young Indonesian male fintech startup CEO dressed in modern casual black shirt smiling confidently" src="https://lh3.googleusercontent.com/aida-public/AB6AXuB7FSffPVngsNLuk3be8R8--4r1Lada7oUnw9Q7vKkRWk7Ayorjb2kCL2pYQ9B2vhwaOyUa2359kCMWSYUA8hAwjFa-BrDRzOLYLBSOV-mGG5eL4tZ1cQNWUul5xuTme8_0wjHFFwgAEFEs_h-2ncMSCuyYEpLBlIziR9bTQhCKT8cIQzINxDeiygXw1pGrBuip1GwwGC_xMnFEopsumtNn9CK1fm0cNOeyFrJ4rTzpb9-N4IBUMhW0bA">
<div>
<div class="font-label-md text-label-md text-tertiary font-bold">Rizky Pratama</div>
<div class="font-body-sm text-body-sm text-on-surface">Founder, PayNusa Digital</div>
</div>
</div>
</div>
<div class="rounded-2xl bg-surface-container/60 p-space-lg flex flex-col justify-between shadow-md">
<div>
<div class="flex items-center gap-1 text-secondary mb-space-sm">
<span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">star</span>
</div>
<p class="font-body-md text-body-md text-on-surface italic leading-relaxed">
                “Strategi social media management yang dijalankan tim asetlab benar-benar tepat sasaran. Kontennya tidak hanya estetik tapi memicu engagement organik nyata.”
              </p>
</div>
<div class="flex items-center gap-space-sm mt-space-md pt-space-sm">
<img class="w-10 h-10 rounded-full object-cover" data-alt="Portrait of an elegant Indonesian businesswoman creative director wearing blazer in minimalist workspace" src="https://lh3.googleusercontent.com/aida-public/AB6AXuC3LzP4-H7RhpHYZkFNLD1wGiUimG5OKwKm5A9ioMQ5pfVaBC5_myZS5ffYq9r5ttbA8TkTS5ygo1v-rn55ZkvaKnjX57K9PVRQIuuLZaqnhb6r9azsyunPfo4XJPaFlDdllauIBOvGGqse0SVm2cyX3W5mmiXR097dUxlHS-UVmpjtOvmc1vuKXmxFcPCJfYHG5bflF3I3MQ1Dys_-S2Yre4gqIHxd6t2UfjK6gSVtcbmP9QY7WKMrIQ">
<div>
<div class="font-label-md text-label-md text-tertiary font-bold">Siti Andini</div>
<div class="font-body-sm text-body-sm text-on-surface">Marketing Head, Nexa Brand Studio</div>
</div>
</div>
</div>
<div class="rounded-2xl bg-surface-container/60 p-space-lg flex flex-col justify-between shadow-md">
<div>
<div class="flex items-center gap-1 text-secondary mb-space-sm">
<span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">star</span>
</div>
<p class="font-body-md text-body-md text-on-surface italic leading-relaxed">
                “Aset UI kit yang kami unduh langsung mempercepat workflow sprint developer kami. Clean code, dokumentasi rapi, dan dark theme token-nya siap pakai.”
              </p>
</div>
<div class="flex items-center gap-space-sm mt-space-md pt-space-sm">
<img class="w-10 h-10 rounded-full object-cover" data-alt="Modern Indonesian male lead front-end engineer portrait in tech studio ambient teal luminescence" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDtOZwVf0Ni8WXP6p6oad4fdo2TcWklxZViT4_eggWtrCCo_cfWe-3Wv2wdpU1PsEeMlINBwnesZijA6ONbLPXhdA9ic-3T4RXiuXoFapaeOarKBwTV4W7tjrlwWBGvpM8VG2ruHOwLe3kKULjNCPCEq35-034XQRk0wq3RhYQSJHfEe6ierA3QqPZqxvuOvWQba1qcSz1SgjtHu-NqgfqvREGNu6OFSL_t5fL8QwyZhCzInyhmslMljw">
<div>
<div class="font-label-md text-label-md text-tertiary font-bold">Bramantyo Wicaksono</div>
<div class="font-body-sm text-body-sm text-on-surface">Tech Lead, Visi Kreasi Solusindo</div>
</div>
</div>
</div></div>
</div>
</section>
<!-- SECTION 6: CALL TO ACTION FOOTER BANNER -->
<section class="w-full px-margin-mobile md:px-margin pt-space-md pb-space-xl" id="konsultasi">
<div class="max-w-7xl mx-auto rounded-3xl bg-gradient-to-r from-surface-container-high via-surface-container to-surface-container-high p-space-xl text-center relative overflow-hidden shadow-2xl">
<div class="absolute inset-0 bg-gradient-to-r from-primary-container/10 via-transparent to-secondary/10 pointer-events-none"></div>
<div class="relative z-10 max-w-2xl mx-auto flex flex-col items-center">
<div class="w-12 h-12 rounded-full bg-primary-container/20 flex items-center justify-center text-primary-container mb-space-md shadow-lg shadow-primary-container/20">
<span class="material-symbols-outlined text-[24px]">rocket_launch</span>
</div>
<h2 class="font-headline-lg text-headline-lg text-tertiary font-extrabold tracking-tight">
            Siap Melejitkan Potensi Digital Bisnis Anda?
          </h2>
<p class="font-body-lg text-body-lg text-on-surface mt-space-sm">
            Diskusikan kebutuhan website, aset digital, atau media sosial Anda bersama lead architect kami sekarang. Gratis sesi konsultasi 30 menit.
          </p>
<div class="flex flex-wrap items-center justify-center gap-space-md mt-space-lg">
<a class="inline-flex items-center justify-center px-8 py-3.5 rounded-full bg-gradient-to-r from-primary-container to-secondary text-on-primary font-label-lg text-label-lg font-bold shadow-[0_4px_25px_rgba(0,242,254,0.4)] hover:brightness-110 transition-all" href="mailto:halo@asetlab.id">
              Jadwalkan Konsultasi Gratis
              <span class="material-symbols-outlined text-[18px] ml-2">calendar_month</span>
</a>
<a class="inline-flex items-center justify-center px-6 py-3.5 rounded-full bg-surface-container-highest text-primary font-label-lg text-label-lg font-medium hover:bg-surface-bright transition-all" href="/layanan">
              Lihat Detail Paket
            </a>
</div>
</div>
</div>
</section>
</div>
</div>

    <!-- Interactive Consultation Modal -->
    <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/75 backdrop-blur-md">
      <div class="relative w-full max-w-lg rounded-3xl bg-surface-container-high border border-outline-variant/40 p-6 md:p-8 shadow-2xl">
        <button @click="showModal = false" class="absolute top-5 right-5 text-outline hover:text-on-surface">
          <span class="material-symbols-outlined">close</span>
        </button>
        <div class="flex items-center gap-3 mb-4">
          <div class="w-10 h-10 rounded-full bg-primary-container/20 flex items-center justify-center text-primary-container">
            <span class="material-symbols-outlined text-[20px]">calendar_month</span>
          </div>
          <div>
            <h3 class="font-headline-sm text-headline-sm text-tertiary font-bold">Jadwalkan Konsultasi Gratis</h3>
            <p class="text-xs text-outline">Sesi eksplorasi teknis & arsitektur 30 menit bersama Tech Lead kami.</p>
          </div>
        </div>

        <form @submit.prevent="submitConsultation" class="space-y-4">
          <div>
            <label class="block text-xs font-semibold text-on-surface-variant mb-1">Nama Lengkap</label>
            <input v-model="form.name" required type="text" placeholder="e.g. Raditya Pratama" class="w-full bg-surface-container-low px-4 py-2.5 rounded-xl border border-outline-variant/40 text-sm text-on-surface focus:outline-none focus:border-primary-container" />
          </div>
          <div>
            <label class="block text-xs font-semibold text-on-surface-variant mb-1">Email Perusahaan / Bisnis</label>
            <input v-model="form.email" required type="email" placeholder="nama@perusahaan.com" class="w-full bg-surface-container-low px-4 py-2.5 rounded-xl border border-outline-variant/40 text-sm text-on-surface focus:outline-none focus:border-primary-container" />
          </div>
          <div>
            <label class="block text-xs font-semibold text-on-surface-variant mb-1">Kategori Kebutuhan Proyek</label>
            <select v-model="form.service" class="w-full bg-surface-container-low px-4 py-2.5 rounded-xl border border-outline-variant/40 text-sm text-on-surface focus:outline-none focus:border-primary-container">
              <option value="Web & SaaS Platform">Full-Stack Web & SaaS Platform</option>
              <option value="Design System & UI Kit">Design System & UI/UX Architecture</option>
              <option value="E-Commerce & High-Conversion">E-Commerce & High-Conversion Landing Page</option>
              <option value="Social Media Strategy">Social Media Management & Growth</option>
            </select>
          </div>
          <div>
            <label class="block text-xs font-semibold text-on-surface-variant mb-1">Catatan Singkat / Objektif</label>
            <textarea v-model="form.notes" rows="3" placeholder="Ceritakan gambaran singkat sistem atau platform yang ingin dibangun..." class="w-full bg-surface-container-low px-4 py-2 rounded-xl border border-outline-variant/40 text-sm text-on-surface focus:outline-none focus:border-primary-container"></textarea>
          </div>

          <div class="pt-2 flex justify-end gap-3">
            <button @click="showModal = false" type="button" class="px-5 py-2.5 rounded-full text-sm text-on-surface-variant hover:bg-surface-container">Batal</button>
            <button type="submit" class="px-6 py-2.5 rounded-full bg-gradient-to-r from-primary-container to-secondary text-on-primary font-bold text-sm hover:brightness-110 shadow-lg">Kirim Pengajuan</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>


<script setup>
import { ref } from 'vue';

const showModal = ref(false);
const form = ref({
  name: '',
  email: '',
  service: 'Web & SaaS Platform',
  notes: ''
});

function openConsultModal() {
  showModal.value = true;
}

function submitConsultation() {
  alert(`Terima kasih ${form.value.name}! Pengajuan konsultasi Anda untuk ${form.value.service} telah diterima. Lead architect kami akan menghubungi ${form.value.email} dalam 1x24 jam.`);
  showModal.value = false;
  form.value = { name: '', email: '', service: 'Web & SaaS Platform', notes: '' };
}
</script>
