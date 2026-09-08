/* ============================================================
   NestFinder — Main Stylesheet
   File: assets/css/style.css
   ============================================================ */

/* ── RESET & BASE ─────────────────────────────────────────── */
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

:root {
  --primary:       #1a6fc4;
  --primary-dark:  #145fa8;
  --primary-light: #e8f1fb;
  --secondary:     #f4a226;
  --secondary-dark:#d4881a;
  --success:       #27ae60;
  --danger:        #e74c3c;
  --warning:       #f39c12;
  --info:          #2980b9;
  --dark:          #1a202c;
  --gray-900:      #2d3748;
  --gray-700:      #4a5568;
  --gray-500:      #718096;
  --gray-300:      #cbd5e0;
  --gray-100:      #f7fafc;
  --white:         #ffffff;
  --border:        #e2e8f0;
  --shadow-sm:     0 1px 3px rgba(0,0,0,.08);
  --shadow:        0 4px 16px rgba(0,0,0,.10);
  --shadow-lg:     0 8px 30px rgba(0,0,0,.14);
  --radius:        10px;
  --radius-lg:     16px;
  --transition:    .25s ease;
  --font:          'Segoe UI', system-ui, -apple-system, sans-serif;
  --nav-height:    70px;
}

html { scroll-behavior: smooth; font-size: 16px; }
body { font-family: var(--font); color: var(--gray-900); background: var(--gray-100); line-height: 1.65; }
a { color: var(--primary); text-decoration: none; transition: color var(--transition); }
a:hover { color: var(--primary-dark); }
img { max-width: 100%; display: block; }
ul { list-style: none; }
h1,h2,h3,h4,h5,h6 { line-height: 1.3; font-weight: 700; color: var(--dark); }
h1 { font-size: clamp(1.8rem, 4vw, 2.6rem); }
h2 { font-size: clamp(1.4rem, 3vw, 2rem); }
h3 { font-size: clamp(1.1rem, 2.5vw, 1.4rem); }
p  { margin-bottom: 1rem; color: var(--gray-700); }

/* ── UTILITIES ────────────────────────────────────────────── */
.container       { max-width: 1200px; margin: 0 auto; padding: 0 20px; }
.text-center     { text-align: center; }
.text-right      { text-align: right; }
.mt-1{margin-top:8px}   .mt-2{margin-top:16px}
.mt-3{margin-top:24px}  .mt-4{margin-top:32px}
.mb-1{margin-bottom:8px}.mb-2{margin-bottom:16px}
.mb-3{margin-bottom:24px}.mb-4{margin-bottom:32px}
.py-2{padding:16px 0}   .py-3{padding:24px 0}
.py-4{padding:32px 0}   .py-5{padding:60px 0}
.flex        { display: flex; }
.flex-center { display: flex; align-items: center; justify-content: center; }
.flex-between{ display: flex; align-items: center; justify-content: space-between; }
.gap-1{gap:8px} .gap-2{gap:16px} .gap-3{gap:24px}
.w-100{width:100%} .d-none{display:none}
.page-padding{ padding: 40px 0 60px; }

/* ── TYPOGRAPHY ───────────────────────────────────────────── */
.section-title    { text-align:center; margin-bottom:12px; font-size:clamp(1.5rem,3vw,2rem); }
.section-subtitle { text-align:center; color:var(--gray-500); margin-bottom:48px; font-size:1.05rem; }
.section-divider  { width:60px; height:4px; background:var(--primary); margin:12px auto 16px; border-radius:2px; }

/* ── BUTTONS ──────────────────────────────────────────────── */
.btn {
  display: inline-flex; align-items: center; gap: 6px;
  padding: 10px 22px; border-radius: var(--radius);
  border: 2px solid transparent; font-size: .95rem;
  font-weight: 600; cursor: pointer;
  transition: all var(--transition);
  text-align: center; white-space: nowrap;
  font-family: var(--font);
}
.btn-primary     { background:var(--primary);    color:#fff; border-color:var(--primary); }
.btn-primary:hover    { background:var(--primary-dark); border-color:var(--primary-dark); color:#fff; }
.btn-secondary   { background:var(--secondary);  color:#fff; border-color:var(--secondary); }
.btn-secondary:hover  { background:var(--secondary-dark); border-color:var(--secondary-dark); color:#fff; }
.btn-success     { background:var(--success);    color:#fff; border-color:var(--success); }
.btn-success:hover    { background:#219d54; color:#fff; }
.btn-danger      { background:var(--danger);     color:#fff; border-color:var(--danger); }
.btn-danger:hover     { background:#c0392b; color:#fff; }
.btn-warning     { background:var(--warning);    color:#fff; border-color:var(--warning); }
.btn-warning:hover    { background:#d68910; color:#fff; }
.btn-outline     { background:transparent; color:var(--primary); border-color:var(--primary); }
.btn-outline:hover    { background:var(--primary); color:#fff; }
.btn-outline-white { background:transparent; color:#fff; border-color:#fff; }
.btn-outline-white:hover { background:#fff; color:var(--primary); }
.btn-sm   { padding:6px 14px; font-size:.85rem; }
.btn-lg   { padding:14px 32px; font-size:1.05rem; }
.btn-full { width:100%; justify-content:center; }
.btn:disabled { opacity:.6; cursor:not-allowed; }

/* ── NAVBAR ───────────────────────────────────────────────── */
.navbar {
  position: sticky; top: 0; z-index: 1000;
  background: var(--white); box-shadow: var(--shadow-sm);
  height: var(--nav-height);
}
.nav-inner {
  display: flex; align-items: center;
  justify-content: space-between; height: 100%;
}
.nav-logo {
  display: flex; align-items: center; gap: 10px;
  font-size: 1.4rem; font-weight: 800; color: var(--primary);
}
.nav-logo span { color: var(--secondary); }
.nav-logo-icon {
  width: 38px; height: 38px; background: var(--primary);
  border-radius: 8px; display: flex; align-items: center;
  justify-content: center; color: #fff; font-size: 1.2rem;
}
.nav-links { display: flex; align-items: center; gap: 4px; }
.nav-links a {
  padding: 8px 14px; border-radius: 8px;
  color: var(--gray-700); font-weight: 500; font-size: .95rem;
  transition: all var(--transition);
}
.nav-links a:hover,
.nav-links a.active { color: var(--primary); background: var(--primary-light); }
.nav-actions { display: flex; align-items: center; gap: 10px; }

/* Dropdown */
.nav-user-menu { position: relative; }
.nav-user-btn {
  display: flex; align-items: center; gap: 8px;
  padding: 8px 14px; background: var(--primary-light);
  border: none; border-radius: 8px; cursor: pointer;
  font-family: var(--font); font-size: .92rem;
  font-weight: 600; color: var(--primary);
  transition: all var(--transition);
}
.nav-user-btn:hover { background: var(--primary); color: #fff; }
.nav-dropdown {
  position: absolute; right: 0; top: calc(100% + 8px);
  background: #fff; border-radius: var(--radius);
  box-shadow: var(--shadow-lg); min-width: 200px;
  overflow: hidden; opacity: 0; visibility: hidden;
  transform: translateY(-8px);
  transition: all var(--transition); z-index: 999;
}
.nav-user-menu:hover .nav-dropdown {
  opacity: 1; visibility: visible; transform: translateY(0);
}
.nav-dropdown a {
  display: flex; align-items: center; gap: 10px;
  padding: 12px 18px; color: var(--gray-700);
  font-size: .92rem; font-weight: 500;
  border-bottom: 1px solid var(--border);
}
.nav-dropdown a:last-child { border-bottom: none; }
.nav-dropdown a:hover { background: var(--primary-light); color: var(--primary); }

/* Hamburger */
.hamburger {
  display: none; flex-direction: column; gap: 5px;
  background: none; border: none; cursor: pointer; padding: 6px;
}
.hamburger span {
  display: block; width: 24px; height: 2px;
  background: var(--gray-700); border-radius: 2px;
  transition: all var(--transition);
}
.hamburger.active span:nth-child(1) { transform: rotate(45deg) translate(5px,5px); }
.hamburger.active span:nth-child(2) { opacity: 0; }
.hamburger.active span:nth-child(3) { transform: rotate(-45deg) translate(5px,-5px); }

@media(max-width:900px) {
  .hamburger { display: flex; }
  .nav-links {
    position: fixed; top: var(--nav-height); left: 0; right: 0;
    background: #fff; flex-direction: column; align-items: flex-start;
    padding: 16px; box-shadow: var(--shadow); gap: 4px;
    transform: translateY(-100%); opacity: 0;
    transition: all var(--transition); z-index: 999;
  }
  .nav-links.open { transform: translateY(0); opacity: 1; }
  .nav-links a { width: 100%; }
  .nav-actions { gap: 6px; }
}

/* ── PAGE HERO (inner pages) ──────────────────────────────── */
.page-hero {
  background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
  padding: 48px 0 44px; color: #fff;
}
.page-hero h1 { color: #fff; margin-bottom: 8px; }
.page-hero p  { color: rgba(255,255,255,.8); margin: 0; }
.breadcrumb {
  display: flex; align-items: center; gap: 8px;
  font-size: .85rem; color: rgba(255,255,255,.7);
  margin-bottom: 16px; flex-wrap: wrap;
}
.breadcrumb a { color: rgba(255,255,255,.7); }
.breadcrumb a:hover { color: #fff; }
.breadcrumb-sep { color: rgba(255,255,255,.4); }

/* ── HERO SECTION ─────────────────────────────────────────── */
.hero {
  position: relative; min-height: 580px;
  background:
    linear-gradient(135deg, rgba(26,111,196,.88) 0%, rgba(20,50,90,.82) 100%),
    url('../images/hero-bg.jpg') center/cover no-repeat;
  display: flex; align-items: center; overflow: hidden;
}
.hero-content {
  position: relative; z-index: 2; color: #fff;
  max-width: 800px; margin: 0 auto;
  text-align: center; padding: 60px 20px;
}
.hero-badge {
  display: inline-block; background: rgba(255,255,255,.15);
  backdrop-filter: blur(6px); border: 1px solid rgba(255,255,255,.3);
  padding: 6px 18px; border-radius: 50px;
  font-size: .85rem; font-weight: 600; color: #fff;
  margin-bottom: 20px; letter-spacing: .5px;
}
.hero h1 {
  color: #fff; font-size: clamp(2rem,5vw,3.2rem);
  margin-bottom: 16px; text-shadow: 0 2px 4px rgba(0,0,0,.2);
}
.hero p { color: rgba(255,255,255,.85); font-size: 1.1rem; margin-bottom: 36px; }
.hero-stats {
  display: flex; justify-content: center;
  gap: 40px; margin-top: 32px; flex-wrap: wrap;
}
.hero-stat strong { display: block; font-size: 1.8rem; color: var(--secondary); }
.hero-stat span   { font-size: .85rem; color: rgba(255,255,255,.75); }

/* Hero Search */
.hero-search {
  background: rgba(255,255,255,.97); border-radius: var(--radius-lg);
  padding: 28px 28px 24px; box-shadow: var(--shadow-lg); margin-top: 24px;
}
.hero-search-tabs { display: flex; gap: 8px; margin-bottom: 20px; }
.search-tab {
  padding: 8px 24px; border-radius: 50px;
  border: 2px solid var(--border); background: transparent;
  font-weight: 600; font-size: .9rem; cursor: pointer;
  transition: all var(--transition); font-family: var(--font);
  color: var(--gray-700);
}
.search-tab.active { background: var(--primary); border-color: var(--primary); color: #fff; }
.hero-search-grid {
  display: grid; grid-template-columns: repeat(auto-fit, minmax(160px,1fr));
  gap: 12px; align-items: end;
}
.search-field { display: flex; flex-direction: column; gap: 6px; }
.search-field label {
  font-size: .8rem; font-weight: 700; color: var(--gray-500);
  text-transform: uppercase; letter-spacing: .5px;
}
.search-field input,
.search-field select {
  height: 46px; padding: 0 14px; border: 1.5px solid var(--border);
  border-radius: 8px; font-size: .95rem; font-family: var(--font);
  color: var(--gray-900); transition: border-color var(--transition); background: #fff;
}
.search-field input:focus,
.search-field select:focus {
  outline: none; border-color: var(--primary);
  box-shadow: 0 0 0 3px rgba(26,111,196,.12);
}
.search-btn-wrap { display: flex; align-items: flex-end; }
.search-btn-wrap .btn { height: 46px; width: 100%; justify-content: center; }

/* ── PROPERTY CARDS ───────────────────────────────────────── */
.property-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(300px,1fr));
  gap: 28px;
}
.property-card {
  background: #fff; border-radius: var(--radius-lg);
  overflow: hidden; box-shadow: var(--shadow-sm);
  transition: all var(--transition); border: 1px solid var(--border);
  display: flex; flex-direction: column;
}
.property-card:hover { transform: translateY(-6px); box-shadow: var(--shadow-lg); }
.card-image { position: relative; height: 220px; overflow: hidden; }
.card-image img {
  width: 100%; height: 100%; object-fit: cover;
  transition: transform .4s ease;
}
.property-card:hover .card-image img { transform: scale(1.06); }
.card-badge {
  position: absolute; top: 14px; left: 14px;
  padding: 5px 14px; border-radius: 50px;
  font-size: .78rem; font-weight: 700; text-transform: uppercase;
  letter-spacing: .5px;
}
.badge-sale { background: var(--primary); color: #fff; }
.badge-rent { background: var(--secondary); color: #fff; }
.badge-type {
  position: absolute; top: 14px; right: 14px;
  background: rgba(0,0,0,.55); backdrop-filter: blur(4px);
  color: #fff; padding: 5px 12px; border-radius: 50px;
  font-size: .75rem; font-weight: 600;
}
.card-body { padding: 18px 20px; flex: 1; }
.card-title {
  font-size: 1.05rem; font-weight: 700; color: var(--dark);
  margin-bottom: 8px; display: -webkit-box;
  -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;
  line-height: 1.4;
}
.card-location {
  display: flex; align-items: center; gap: 6px;
  color: var(--gray-500); font-size: .88rem; margin-bottom: 12px;
}
.card-price { font-size: 1.35rem; font-weight: 800; color: var(--primary); margin-bottom: 14px; }
.card-features { display: flex; gap: 16px; flex-wrap: wrap; margin-bottom: 16px; }
.card-feature {
  display: flex; align-items: center; gap: 5px;
  font-size: .82rem; color: var(--gray-500); font-weight: 500;
}
.card-footer {
  padding: 14px 20px; border-top: 1px solid var(--border);
  display: flex; align-items: center; justify-content: space-between;
}
.card-area { font-size: .85rem; color: var(--gray-500); font-weight: 500; }
.card-footer .btn { padding: 8px 18px; font-size: .85rem; }

/* ── SECTIONS ─────────────────────────────────────────────── */
.section     { padding: 80px 0; }
.section-bg  { background: var(--white); }

/* Featured */
.featured-section   { padding: 80px 0; background: var(--gray-100); }

/* Categories */
.categories-section { padding: 80px 0; background: var(--white); }
.categories-grid {
  display: grid; grid-template-columns: repeat(auto-fill, minmax(180px,1fr)); gap: 20px;
}
.category-card {
  background: #fff; border: 1.5px solid var(--border);
  border-radius: var(--radius-lg); padding: 30px 20px;
  text-align: center; cursor: pointer;
  transition: all var(--transition); text-decoration: none; display: block;
}
.category-card:hover {
  border-color: var(--primary); background: var(--primary-light);
  transform: translateY(-4px); box-shadow: var(--shadow);
}
.category-icon  { font-size: 2.5rem; margin-bottom: 14px; display: block; }
.category-name  { font-weight: 700; color: var(--dark); font-size: 1rem; margin-bottom: 6px; display: block; }
.category-count { font-size: .82rem; color: var(--gray-500); }

/* Locations */
.locations-section { padding: 80px 0; background: var(--gray-100); }
.locations-grid {
  display: grid; grid-template-columns: repeat(auto-fill, minmax(200px,1fr)); gap: 20px;
}
.location-card {
  position: relative; height: 160px; border-radius: var(--radius-lg);
  overflow: hidden; cursor: pointer; text-decoration: none; display: block;
}
.location-card img { width: 100%; height: 100%; object-fit: cover; transition: transform .4s ease; }
.location-card:hover img { transform: scale(1.08); }
.location-overlay {
  position: absolute; inset: 0;
  background: linear-gradient(to top, rgba(0,0,0,.7) 0%, transparent 60%);
  display: flex; flex-direction: column;
  align-items: flex-start; justify-content: flex-end; padding: 16px;
}
.location-name  { color: #fff; font-weight: 700; font-size: 1rem; }
.location-count { color: rgba(255,255,255,.8); font-size: .82rem; }

/* Why Choose Us */
.why-section { padding: 80px 0; background: var(--white); }
.why-grid {
  display: grid; grid-template-columns: repeat(auto-fill, minmax(240px,1fr)); gap: 32px;
}
.why-card {
  text-align: center; padding: 36px 24px;
  border-radius: var(--radius-lg); border: 1px solid var(--border);
  transition: all var(--transition);
}
.why-card:hover { border-color: var(--primary); box-shadow: var(--shadow); transform: translateY(-4px); }
.why-icon {
  font-size: 2.8rem; margin-bottom: 18px; display: inline-block;
  background: var(--primary-light); width: 72px; height: 72px;
  border-radius: 50%; line-height: 72px; text-align: center;
}
.why-card h3 { font-size: 1.1rem; margin-bottom: 10px; }
.why-card p  { font-size: .92rem; color: var(--gray-500); margin: 0; }

/* CTA */
.cta-section {
  padding: 80px 0;
  background: linear-gradient(135deg, var(--primary) 0%, #0f4880 100%);
  text-align: center;
}
.cta-section h2 { color: #fff; margin-bottom: 14px; }
.cta-section p  { color: rgba(255,255,255,.8); margin-bottom: 32px; font-size: 1.05rem; }
.cta-buttons    { display: flex; gap: 16px; justify-content: center; flex-wrap: wrap; }

/* ── LISTING PAGE ─────────────────────────────────────────── */
.listing-layout {
  display: grid; grid-template-columns: 280px 1fr;
  gap: 32px; align-items: start; padding: 40px 0;
}
@media(max-width:900px) { .listing-layout { grid-template-columns: 1fr; } }

/* Filter Sidebar */
.filter-sidebar {
  background: #fff; border-radius: var(--radius-lg);
  padding: 24px; box-shadow: var(--shadow-sm);
  border: 1px solid var(--border);
  position: sticky; top: calc(var(--nav-height) + 16px);
}
.filter-title {
  font-size: 1.1rem; font-weight: 700; margin-bottom: 20px;
  padding-bottom: 14px; border-bottom: 1px solid var(--border);
  display: flex; align-items: center; justify-content: space-between;
}
.filter-group { margin-bottom: 22px; }
.filter-group label {
  display: block; font-size: .82rem; font-weight: 700;
  color: var(--gray-500); text-transform: uppercase;
  letter-spacing: .5px; margin-bottom: 8px;
}
.filter-group input,
.filter-group select {
  width: 100%; height: 42px; padding: 0 12px;
  border: 1.5px solid var(--border); border-radius: 8px;
  font-size: .92rem; font-family: var(--font);
  color: var(--gray-900); background: var(--gray-100);
  transition: border-color var(--transition);
}
.filter-group input:focus,
.filter-group select:focus {
  outline: none; border-color: var(--primary); background: #fff;
  box-shadow: 0 0 0 3px rgba(26,111,196,.1);
}
.price-range { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }

/* Results */
.results-header {
  display: flex; align-items: center; justify-content: space-between;
  margin-bottom: 24px; flex-wrap: wrap; gap: 12px;
}
.results-count { font-size: .95rem; color: var(--gray-500); }
.results-count strong { color: var(--dark); }
.sort-select {
  height: 40px; padding: 0 12px; border: 1.5px solid var(--border);
  border-radius: 8px; font-size: .9rem; font-family: var(--font);
  color: var(--gray-700); background: #fff; cursor: pointer;
}

/* Empty State */
.empty-state {
  text-align: center; padding: 60px 20px; background: #fff;
  border-radius: var(--radius-lg); border: 1px dashed var(--border);
}
.empty-icon     { font-size: 4rem; margin-bottom: 20px; }
.empty-state h3 { font-size: 1.3rem; margin-bottom: 10px; }
.empty-state p  { color: var(--gray-500); margin-bottom: 24px; }

/* Pagination */
.pagination { display: flex; justify-content: center; gap: 8px; margin-top: 40px; flex-wrap: wrap; }
.page-btn {
  width: 40px; height: 40px; display: flex;
  align-items: center; justify-content: center;
  border-radius: 8px; border: 1.5px solid var(--border);
  background: #fff; color: var(--gray-700); font-weight: 600;
  font-size: .9rem; cursor: pointer;
  transition: all var(--transition); text-decoration: none;
}
.page-btn:hover,
.page-btn.active { background: var(--primary); border-color: var(--primary); color: #fff; }

/* ── PROPERTY DETAILS PAGE ────────────────────────────────── */
.details-layout {
  display: grid; grid-template-columns: 1fr 360px;
  gap: 32px; padding: 40px 0; align-items: start;
}
@media(max-width:1024px) { .details-layout { grid-template-columns: 1fr; } }
.details-gallery {
  border-radius: var(--radius-lg); overflow: hidden;
  margin-bottom: 28px; height: 420px; box-shadow: var(--shadow);
}
.details-gallery img { width: 100%; height: 100%; object-fit: cover; }
.details-card {
  background: #fff; border-radius: var(--radius-lg);
  padding: 28px; box-shadow: var(--shadow-sm);
  border: 1px solid var(--border); margin-bottom: 24px;
}
.details-title { font-size: clamp(1.2rem,2.5vw,1.7rem); margin-bottom: 8px; }
.details-price { font-size: 1.9rem; font-weight: 800; color: var(--primary); }
.details-badges { display: flex; gap: 10px; flex-wrap: wrap; margin-bottom: 14px; }
.badge {
  padding: 5px 14px; border-radius: 50px;
  font-size: .8rem; font-weight: 700; text-transform: uppercase; letter-spacing: .5px;
}
.badge-primary   { background: var(--primary-light); color: var(--primary); }
.badge-success   { background: #e8f8f0; color: var(--success); }
.badge-warning   { background: #fef9e7; color: var(--warning); }
.badge-secondary { background: #fef5e0; color: var(--secondary-dark); }
.details-info-grid {
  display: grid; grid-template-columns: repeat(auto-fill,minmax(160px,1fr));
  gap: 20px; margin-top: 20px;
}
.info-item {
  display: flex; flex-direction: column; gap: 6px;
  padding: 16px; background: var(--gray-100);
  border-radius: var(--radius); text-align: center;
}
.info-label { font-size: .78rem; font-weight: 700; color: var(--gray-500); text-transform: uppercase; letter-spacing: .5px; }
.info-value { font-size: 1.05rem; font-weight: 700; color: var(--dark); }
.info-icon  { font-size: 1.3rem; margin-bottom: 4px; }
.features-list { display: flex; flex-wrap: wrap; gap: 10px; margin-top: 14px; }
.feature-tag {
  padding: 7px 16px; background: var(--primary-light);
  color: var(--primary); border-radius: 50px;
  font-size: .85rem; font-weight: 600;
}

/* Sidebar Cards */
.sidebar-card {
  background: #fff; border-radius: var(--radius-lg);
  padding: 24px; box-shadow: var(--shadow-sm);
  border: 1px solid var(--border); margin-bottom: 24px;
}
.sidebar-card h3 {
  font-size: 1.05rem; margin-bottom: 18px;
  padding-bottom: 12px; border-bottom: 1px solid var(--border);
}
.agent-info { display: flex; align-items: center; gap: 14px; margin-bottom: 16px; }
.agent-avatar {
  width: 54px; height: 54px; border-radius: 50%;
  background: var(--primary); color: #fff;
  display: flex; align-items: center; justify-content: center;
  font-size: 1.4rem; font-weight: 700; flex-shrink: 0;
}
.agent-name { font-weight: 700; color: var(--dark); }
.agent-role { font-size: .82rem; color: var(--gray-500); }
.agent-contact { display: flex; flex-direction: column; gap: 8px; margin-bottom: 16px; }
.agent-contact-item { display: flex; align-items: center; gap: 8px; font-size: .88rem; color: var(--gray-700); }

/* ── FORMS ────────────────────────────────────────────────── */
.form-card {
  background: #fff; border-radius: var(--radius-lg);
  padding: 36px; box-shadow: var(--shadow);
  border: 1px solid var(--border);
  max-width: 680px; margin: 0 auto;
}
.form-card-wide { max-width: 900px; }
.form-title    { font-size: 1.5rem; margin-bottom: 6px; }
.form-subtitle { color: var(--gray-500); margin-bottom: 28px; }
.form-group    { margin-bottom: 20px; }
.form-group label {
  display: block; font-size: .88rem; font-weight: 700;
  color: var(--gray-700); margin-bottom: 8px;
}
.form-group .required::after { content: ' *'; color: var(--danger); }
.form-control {
  width: 100%; padding: 11px 14px;
  border: 1.5px solid var(--border); border-radius: 8px;
  font-size: .95rem; font-family: var(--font);
  color: var(--gray-900); background: var(--gray-100);
  transition: all var(--transition);
}
.form-control:focus {
  outline: none; border-color: var(--primary);
  background: #fff; box-shadow: 0 0 0 3px rgba(26,111,196,.1);
}
.form-control.is-invalid { border-color: var(--danger); }
.form-control.is-valid   { border-color: var(--success); }
textarea.form-control { resize: vertical; min-height: 120px; }
.form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
@media(max-width:600px) { .form-row { grid-template-columns: 1fr; } }
.form-hint  { font-size: .8rem; color: var(--gray-500); margin-top: 5px; }
.form-error { font-size: .82rem; color: var(--danger); margin-top: 5px; display: none; }
.form-error.show { display: block; }

/* Image Preview */
.image-preview {
  width: 100%; height: 200px;
  border: 2px dashed var(--border); border-radius: var(--radius);
  display: flex; align-items: center; justify-content: center;
  margin-top: 10px; overflow: hidden; background: var(--gray-100);
  cursor: pointer; transition: border-color var(--transition);
}
.image-preview:hover { border-color: var(--primary); }
.image-preview img { width: 100%; height: 100%; object-fit: cover; }
.image-preview-placeholder { text-align: center; color: var(--gray-500); font-size: .9rem; }
.image-preview-placeholder span { font-size: 2rem; display: block; margin-bottom: 8px; }

/* Price Display */
#priceDisplay {
  font-size: .9rem; color: var(--primary);
  font-weight: 700; margin-top: 6px;
}

/* ── ALERTS ───────────────────────────────────────────────── */
.alert {
  padding: 14px 18px; border-radius: var(--radius);
  margin-bottom: 20px; display: flex; align-items: flex-start;
  gap: 12px; font-size: .92rem; font-weight: 500;
  border-left: 4px solid transparent;
}
.alert-success { background: #eafaf1; color: #1e8449; border-color: var(--success); }
.alert-danger  { background: #fdecea; color: #c0392b; border-color: var(--danger); }
.alert-warning { background: #fef9e7; color: #b7770d; border-color: var(--warning); }
.alert-info    { background: #e8f4fb; color: #1a6f9c; border-color: var(--info); }
.alert-icon    { font-size: 1.1rem; flex-shrink: 0; margin-top: 1px; }

/* ── DASHBOARD ────────────────────────────────────────────── */
.dashboard-layout {
  display: grid; grid-template-columns: 240px 1fr;
  gap: 32px; padding: 40px 0; align-items: start;
}
@media(max-width:900px) { .dashboard-layout { grid-template-columns: 1fr; } }
.dashboard-sidebar {
  background: #fff; border-radius: var(--radius-lg);
  overflow: hidden; box-shadow: var(--shadow-sm);
  border: 1px solid var(--border);
  position: sticky; top: calc(var(--nav-height) + 16px);
}
.dash-user-header {
  padding: 24px;
  background: linear-gradient(135deg, var(--primary), var(--primary-dark));
  text-align: center; color: #fff;
}
.dash-avatar {
  width: 64px; height: 64px; background: rgba(255,255,255,.2);
  border-radius: 50%; display: flex; align-items: center;
  justify-content: center; font-size: 1.8rem; font-weight: 700;
  margin: 0 auto 12px; border: 2px solid rgba(255,255,255,.4);
}
.dash-username { font-weight: 700; font-size: 1rem; }
.dash-role     { font-size: .8rem; color: rgba(255,255,255,.7); text-transform: capitalize; }
.dash-nav      { padding: 12px; }
.dash-nav-item {
  display: flex; align-items: center; gap: 10px;
  padding: 11px 14px; border-radius: 8px;
  color: var(--gray-700); font-weight: 500; font-size: .92rem;
  transition: all var(--transition); margin-bottom: 2px;
}
.dash-nav-item:hover,
.dash-nav-item.active { background: var(--primary-light); color: var(--primary); }
.dash-nav-item .nav-icon { font-size: 1.1rem; }

/* Stats */
.stats-grid {
  display: grid; grid-template-columns: repeat(auto-fill,minmax(180px,1fr));
  gap: 20px; margin-bottom: 32px;
}
.stat-card {
  background: #fff; border-radius: var(--radius-lg);
  padding: 24px; text-align: center;
  box-shadow: var(--shadow-sm); border: 1px solid var(--border);
  transition: all var(--transition);
}
.stat-card:hover { transform: translateY(-3px); box-shadow: var(--shadow); }
.stat-icon   { font-size: 2rem; margin-bottom: 12px; display: inline-block; }
.stat-number { font-size: 2rem; font-weight: 800; color: var(--primary); }
.stat-label  { font-size: .85rem; color: var(--gray-500); margin-top: 4px; }

/* Content Cards */
.content-card {
  background: #fff; border-radius: var(--radius-lg);
  padding: 28px; box-shadow: var(--shadow-sm);
  border: 1px solid var(--border); margin-bottom: 24px;
}
.content-card-header {
  display: flex; align-items: center; justify-content: space-between;
  margin-bottom: 20px; padding-bottom: 16px; border-bottom: 1px solid var(--border);
}
.content-card-title { font-size: 1.1rem; font-weight: 700; }

/* ── TABLES ───────────────────────────────────────────────── */
.table-responsive { overflow-x: auto; border-radius: var(--radius); }
.table { width: 100%; border-collapse: collapse; font-size: .9rem; }
.table th {
  background: var(--gray-100); padding: 12px 16px; text-align: left;
  font-size: .78rem; font-weight: 700; text-transform: uppercase;
  letter-spacing: .5px; color: var(--gray-500);
  border-bottom: 1px solid var(--border); white-space: nowrap;
}
.table td {
  padding: 14px 16px; border-bottom: 1px solid var(--border);
  color: var(--gray-700); vertical-align: middle;
}
.table tr:last-child td { border-bottom: none; }
.table tr:hover td { background: var(--gray-100); }
.table-img { width: 50px; height: 40px; border-radius: 6px; object-fit: cover; }

/* ── AUTH PAGES ───────────────────────────────────────────── */
.auth-wrapper {
  min-height: calc(100vh - var(--nav-height));
  display: flex; align-items: center; justify-content: center;
  padding: 40px 20px; background: var(--gray-100);
}
.auth-card {
  background: #fff; border-radius: var(--radius-lg);
  padding: 44px; box-shadow: var(--shadow-lg);
  width: 100%; max-width: 480px; border: 1px solid var(--border);
}
@media(max-width:520px) { .auth-card { padding: 28px 20px; } }
.auth-logo { text-align: center; margin-bottom: 28px; }
.auth-logo a { font-size: 1.6rem; font-weight: 800; color: var(--primary); }
.auth-logo a span { color: var(--secondary); }
.auth-title    { font-size: 1.5rem; text-align: center; margin-bottom: 6px; }
.auth-subtitle { text-align: center; color: var(--gray-500); margin-bottom: 28px; }
.auth-footer   { text-align: center; margin-top: 24px; color: var(--gray-500); font-size: .9rem; }
.auth-footer a { font-weight: 700; color: var(--primary); }

/* Password Toggle */
.pass-wrap { position: relative; }
.pass-toggle {
  position: absolute; right: 12px; top: 50%;
  transform: translateY(-50%); background: none;
  border: none; cursor: pointer; color: var(--gray-500); font-size: 1rem;
}

/* ── BADGES ───────────────────────────────────────────────── */
.role-badge { padding: 4px 12px; border-radius: 50px; font-size: .75rem; font-weight: 700; text-transform: uppercase; }
.role-admin  { background: #f8e0e0; color: #c0392b; }
.role-seller { background: #e0f0e0; color: #1e8449; }
.role-buyer  { background: #e0eaf8; color: #1a6fc4; }
.status-badge { padding: 4px 12px; border-radius: 50px; font-size: .75rem; font-weight: 700; text-transform: uppercase; }
.status-active   { background: #e8f8f0; color: var(--success); }
.status-inactive { background: #fdecea; color: var(--danger); }

/* ── FOOTER ───────────────────────────────────────────────── */
.footer { background: var(--dark); color: rgba(255,255,255,.75); padding: 60px 0 0; }
.footer-grid {
  display: grid; grid-template-columns: 2fr 1fr 1fr 1fr;
  gap: 40px; margin-bottom: 48px;
}
@media(max-width:900px) { .footer-grid { grid-template-columns: 1fr 1fr; } }
@media(max-width:580px) { .footer-grid { grid-template-columns: 1fr; } }
.footer-logo { font-size: 1.5rem; font-weight: 800; color: #fff; margin-bottom: 14px; display: flex; align-items: center; gap: 8px; }
.footer-logo span { color: var(--secondary); }
.footer-desc { font-size: .9rem; line-height: 1.7; margin-bottom: 20px; }
.footer-social { display: flex; gap: 10px; }
.social-btn {
  width: 36px; height: 36px; border-radius: 8px;
  background: rgba(255,255,255,.08); display: flex;
  align-items: center; justify-content: center;
  color: rgba(255,255,255,.7); font-size: .9rem;
  transition: all var(--transition); text-decoration: none;
}
.social-btn:hover { background: var(--primary); color: #fff; }
.footer-heading { font-size: .9rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #fff; margin-bottom: 18px; }
.footer-links { display: flex; flex-direction: column; gap: 10px; }
.footer-links a { color: rgba(255,255,255,.65); font-size: .9rem; transition: color var(--transition); }
.footer-links a:hover { color: var(--secondary); }
.footer-contact-item { display: flex; align-items: flex-start; gap: 10px; font-size: .9rem; margin-bottom: 12px; }
.footer-contact-icon { font-size: 1rem; margin-top: 2px; }
.footer-bottom { border-top: 1px solid rgba(255,255,255,.08); padding: 20px 0; text-align: center; font-size: .85rem; color: rgba(255,255,255,.4); }
.footer-bottom a { color: rgba(255,255,255,.5); }
.footer-bottom a:hover { color: var(--secondary); }

/* ── MISC ─────────────────────────────────────────────────── */
.back-link {
  display: inline-flex; align-items: center; gap: 6px;
  color: var(--gray-500); font-size: .9rem;
  margin-bottom: 20px; transition: color var(--transition);
}
.back-link:hover { color: var(--primary); }
.scroll-top {
  position: fixed; bottom: 24px; right: 24px;
  width: 44px; height: 44px; background: var(--primary);
  color: #fff; border: none; border-radius: 50%;
  display: flex; align-items: center; justify-content: center;
  cursor: pointer; box-shadow: var(--shadow);
  font-size: 1.1rem; opacity: 0; visibility: hidden;
  transition: all var(--transition); z-index: 999;
}
.scroll-top.visible { opacity: 1; visibility: visible; }
.scroll-top:hover { background: var(--primary-dark); transform: translateY(-3px); }

/* Fade In Animation */
.fade-in { opacity: 0; transform: translateY(24px); transition: opacity .6s ease, transform .6s ease; }
.fade-in.visible { opacity: 1; transform: translateY(0); }

/* ── RESPONSIVE ───────────────────────────────────────────── */
@media(max-width:768px) {
  .property-grid { grid-template-columns: 1fr; }
  .categories-grid { grid-template-columns: repeat(2,1fr); }
  .locations-grid  { grid-template-columns: repeat(2,1fr); }
  .why-grid        { grid-template-columns: 1fr 1fr; }
  .hero            { min-height: 460px; }
  .hero-stats      { gap: 24px; }
  .details-layout  { grid-template-columns: 1fr; }
  .details-gallery { height: 280px; }
  .footer-grid     { grid-template-columns: 1fr; }
}
@media(max-width:480px) {
  .why-grid         { grid-template-columns: 1fr; }
  .hero-search-grid { grid-template-columns: 1fr; }
  .form-row         { grid-template-columns: 1fr; }
  .stats-grid       { grid-template-columns: 1fr 1fr; }
  .auth-card        { padding: 24px 16px; }
}