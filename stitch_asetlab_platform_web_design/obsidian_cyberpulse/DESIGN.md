---
name: Obsidian Cyberpulse
colors:
  surface: '#0f131c'
  surface-dim: '#0f131c'
  surface-bright: '#353943'
  surface-container-lowest: '#0a0e17'
  surface-container-low: '#181b25'
  surface-container: '#1c1f29'
  surface-container-high: '#262a34'
  surface-container-highest: '#31353f'
  on-surface: '#dfe2ef'
  on-surface-variant: '#b9cacb'
  inverse-surface: '#dfe2ef'
  inverse-on-surface: '#2c303a'
  outline: '#849495'
  outline-variant: '#3a494b'
  surface-tint: '#00dce6'
  primary: '#e0fdff'
  on-primary: '#00373a'
  primary-container: '#00f2fe'
  on-primary-container: '#006a70'
  inverse-primary: '#00696f'
  secondary: '#4edea3'
  on-secondary: '#003824'
  secondary-container: '#00a572'
  on-secondary-container: '#00311f'
  tertiary: '#f1f8ff'
  on-tertiary: '#00354a'
  tertiary-container: '#b2e1ff'
  on-tertiary-container: '#00678c'
  error: '#ffb4ab'
  on-error: '#690005'
  error-container: '#93000a'
  on-error-container: '#ffdad6'
  primary-fixed: '#6ff6ff'
  primary-fixed-dim: '#00dce6'
  on-primary-fixed: '#002022'
  on-primary-fixed-variant: '#004f53'
  secondary-fixed: '#6ffbbe'
  secondary-fixed-dim: '#4edea3'
  on-secondary-fixed: '#002113'
  on-secondary-fixed-variant: '#005236'
  tertiary-fixed: '#c4e7ff'
  tertiary-fixed-dim: '#7bd0ff'
  on-tertiary-fixed: '#001e2c'
  on-tertiary-fixed-variant: '#004c69'
  background: '#0f131c'
  on-background: '#dfe2ef'
  surface-variant: '#31353f'
typography:
  display-hero:
    fontFamily: Plus Jakarta Sans
    fontSize: 56px
    fontWeight: '800'
    lineHeight: 68px
    letterSpacing: -0.02em
  display-hero-mobile:
    fontFamily: Plus Jakarta Sans
    fontSize: 36px
    fontWeight: '800'
    lineHeight: 44px
    letterSpacing: -0.02em
  headline-lg:
    fontFamily: Plus Jakarta Sans
    fontSize: 32px
    fontWeight: '700'
    lineHeight: 40px
    letterSpacing: -0.015em
  headline-lg-mobile:
    fontFamily: Plus Jakarta Sans
    fontSize: 26px
    fontWeight: '700'
    lineHeight: 34px
    letterSpacing: -0.01em
  headline-md:
    fontFamily: Plus Jakarta Sans
    fontSize: 24px
    fontWeight: '600'
    lineHeight: 32px
    letterSpacing: -0.01em
  headline-sm:
    fontFamily: Plus Jakarta Sans
    fontSize: 18px
    fontWeight: '600'
    lineHeight: 26px
  body-lg:
    fontFamily: Inter
    fontSize: 16px
    fontWeight: '400'
    lineHeight: 26px
  body-md:
    fontFamily: Inter
    fontSize: 14px
    fontWeight: '400'
    lineHeight: 22px
  body-sm:
    fontFamily: Inter
    fontSize: 12px
    fontWeight: '400'
    lineHeight: 18px
  label-lg:
    fontFamily: Inter
    fontSize: 14px
    fontWeight: '600'
    lineHeight: 20px
    letterSpacing: 0.01em
  label-md:
    fontFamily: Inter
    fontSize: 12px
    fontWeight: '600'
    lineHeight: 16px
    letterSpacing: 0.02em
  label-sm:
    fontFamily: Inter
    fontSize: 10px
    fontWeight: '600'
    lineHeight: 14px
    letterSpacing: 0.04em
rounded:
  sm: 0.5rem
  DEFAULT: 1rem
  md: 1.5rem
  lg: 2rem
  xl: 3rem
  full: 9999px
spacing:
  gutter: 1.5rem
  gutter-mobile: 1rem
  margin: 2rem
  margin-mobile: 1rem
  space-xs: 0.25rem
  space-sm: 0.5rem
  space-md: 1rem
  space-lg: 1.5rem
  space-xl: 2.5rem
---

## Brand & Style

The design system establishes a futuristic, high-velocity digital atmosphere tailored for tech accelerators, digital studios, and modern data platforms. It balances technical precision with high-impact luminescence, evoking confidence, digital mastery, and effortless scalability. 

The aesthetic is rooted in dark futuristic glassmorphism and cyber-luminescence. Deep obsidian surfaces (#090D16, #0E1424) act as an infinite void upon which vibrant bioluminescent cyan (#00F2FE) and electric emerald (#10B981) emerge. The emotional tone avoids chaotic sci-fi tropes, maintaining structured executive balance, clean legibility, and refined pill-shaped contours. It delivers the sensation of piloting an elite digital command deck.

## Colors

The palette revolves around deep atmospheric navy-obsidian grounds and high-energy luminescent accents.

- **Primary (`#00F2FE` - Electric Cyan)**: Drives focal CTA elements, primary active tabs, and luminescent gradients. Represents forward momentum and high-tech clarity.
- **Secondary (`#10B981` - Emerald Spring)**: Utilized in synergy with cyan for energetic dual-stop gradients, success status chips, and positive financial trajectory indicators.
- **Tertiary (`#38BDF8` - Atmosphere Sky)**: Softens harsh cyan glare; provides delicate border trims, sub-indicators, and secondary chart telemetry lines.
- **Neutral (`#090D16` - Deep Obsidian)**: The bedrock background canvas, paired with `#0E1424` for card panels and `#161F36` for hovered interactive states.
- **Semantic Accents**: Warning states utilize radiant amber (`#F59E0B`), destructive alerts leverage vivid crimson (`#EF4444`), and neutral text relies on slate hierarchies (`#F8FAFC`, `#94A3B8`, `#475569`).

## Typography

The type system blends the energetic geometry of Plus Jakarta Sans for structural titles with the ultra-clean clarity of Inter for dense analytics and body copy. 

Headings feature tight negative letter-spacing to emphasize authority and technological precision. Gradient text treatments are applied sparingly to hero titles, blending seamlessly from primary cyan (`#00F2FE`) to vibrant emerald (`#4ADE80`). Monospace styling is avoided in favor of tabular numeric figures enabled inside Inter, ensuring data visualization metrics, financial figures (IDR/Rupiah formats), and transaction rows maintain rigorous horizontal alignment.

## Layout & Spacing

A 12-column responsive fluid grid governs the page architecture, anchored by an outer layout margin of `2rem` (32px) on desktop, collapsing to `1rem` (16px) on mobile breakpoints. Gutters maintain `1.5rem` (24px) for analytical dashboards to balance density and breathing room.

The spatial scale follows a strict 4px/8px modular cadence. Internal card paddings default to `space-lg` (24px) on data panels and `space-xl` (40px) on promotional hero segments. For ultra-dense dashboard grids, tabular rows and KPI cards step down to `space-md` (16px) internal padding, preventing dashboard vertical stretching.

## Elevation & Depth

Depth is established through multi-layered translucent glass surfaces coupled with atmospheric glowing edges rather than traditional muddy dropshadows.

1. **Base Layer (Canvas)**: Solid deep obsidian (`#090D16`), occasionally overlaid with faint ambient radial blooms of cyan (`rgba(0, 242, 254, 0.04)`) to establish visual origin points.
2. **Surface Level 1 (Panels & Cards)**: Translucent glass containers styled with `rgba(14, 20, 36, 0.75)` and a backdrop filter blur of `16px`. Outlines are razor-thin `1px solid rgba(56, 189, 248, 0.15)`.
3. **Surface Level 2 (Hover & Active Modals)**: Subtle inner radiance (`inset 0 1px 1px 0 rgba(255, 255, 255, 0.1)`) accompanied by an amplified perimeter border of `rgba(0, 242, 254, 0.4)`.
4. **Interactive Glow (CTA Buttons & Badges)**: Active elements cast an ethereal ambient aura: `0 8px 24px -4px rgba(0, 242, 254, 0.35), 0 0 12px 0 rgba(16, 185, 129, 0.25)`.

## Shapes

The design system employs a refined pill-dominant shape profile (Level 3). 

Interactive triggers, input bars, badges, status chips, and primary buttons utilize full pill radii (`9999px` / `rounded-full`) to deliver tactile ergonomics and human-centric approachability against the dark futuristic aesthetic. Enclosing structural dashboard containers, data cards, and modal sheets are calibrated to a softer roundedness of `1.25rem` (20px) to maximize internal screen space efficiency without feeling rigid.

## Components

### Buttons
- **Primary Action (Pill Gradient)**: Linear gradient background `90deg, #00F2FE 0%, #4ADE80 100%`, pure dark text (`#050811`) with bold weight (`fontWeight: 700`). Features an ambient glow `box-shadow: 0 4px 20px rgba(0, 242, 254, 0.4)`. Transitions slightly brighter upon hover.
- **Secondary Ghost**: Dark translucent fill (`rgba(14, 20, 36, 0.6)`), border `1px solid rgba(56, 189, 248, 0.3)`, text `#F8FAFC`. On hover, the border shifts to `#00F2FE` with a soft inset glow.

### Cards & Data Panels
- Built with a glassmorphic finish (`rgba(14, 20, 36, 0.7)`), 1px border `rgba(56, 189, 248, 0.18)`, and corner radius `1.25rem`. 
- **Stats Card**: Features an indexed step badge (e.g., "1", "2"), an absolute indicator chip (e.g., `+12.4%`), crisp primary metric typography in pure white, and an optional bottom sparkline chart.

### Badges & Status Chips
- Fully rounded pill geometry with horizontal padding `0.75rem` and vertical padding `0.25rem`.
- **Active / Success**: Background `rgba(16, 185, 129, 0.12)`, border `1px solid rgba(16, 185, 129, 0.3)`, text `#34D399` with a pulsing live green circular dot.
- **Pending / Warning**: Background `rgba(245, 158, 11, 0.12)`, border `1px solid rgba(245, 158, 11, 0.3)`, text `#FBBF24`.
- **Category Badge**: Background `rgba(56, 189, 248, 0.1)`, text `#38BDF8`.

### Input Fields
- Pill-shaped or heavily rounded (`1rem`), background `rgba(9, 13, 22, 0.8)`, border `1px solid rgba(255, 255, 255, 0.08)`. Focus states activate a radiant cyan border `rgba(0, 242, 254, 0.6)` with subtle outer glow ring (`0 0 0 3px rgba(0, 242, 254, 0.15)`).

### Tables & Data Visualizations
- **Data Table**: Alternating transparent and subtle translucent row states (`rgba(255, 255, 255, 0.015)`). Subtle border dividers (`rgba(255, 255, 255, 0.05)`). Column headers use `label-sm` in slate gray uppercase with wide tracking.
- **Charts**: Dual-tone neon line strokes (`#00F2FE` and `#4ADE80`) over soft vertical fill gradients fading to total opacity `0` at the baseline.