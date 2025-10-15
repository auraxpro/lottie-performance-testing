# Performance Audit Report – Tipalti Finance AI Page

Date: 2025-10-15
URL Analyzed: https://tipalti.com/accounts-payable-software/finance-ai/

## Executive Summary

- Core Web Vitals (field data): Passed. LCP ~2.0s, CLS ~0.05.
- Lab (Lighthouse Desktop): Performance 64 (example run), TBT 1,240 ms, total payload ~7.4 MB.
- Primary bottlenecks are not animations but heavy third‑party JavaScript (marketing/analytics/AB testing), a large Vimeo embed loading on first paint, render‑blocking CSS, and unused/legacy JS.
- Lottie is not inherently the cause of slowdowns. Poor Lottie integration can hurt scores (sync player load, eager JSON loads, no lazy loading). Using async + lazy + conditional loading keeps ≥95%.

## Key Findings (Ranked by Impact)

1) Excessive Main‑Thread JS (High TBT)
- Symptoms: TBT 1,240 ms; long tasks from GTM tags, VWO, Segment, Cookiebot, Facebook/LinkedIn/Bing pixels, ZoomInfo, New Relic.
- Impact: Delays interactivity; depresses Performance score.
- Evidence: “Minimize main‑thread work” (7.1s), “Reduce JS execution time (4.5s)”, many third‑party sources flagged.

2) Heavy Vimeo Embed on Initial Load
- Symptoms: Multi‑MB video/player/vendor modules fetched during initial render.
- Impact: Large network payload; CPU for player scripts; increases long tasks and LCP risk.
- Evidence: “Some third‑party resources can be lazy loaded with a facade – Vimeo Player” with multi‑MB assets.

3) Render‑Blocking CSS (Multiple Stylesheets)
- Symptoms: CSS from theme (flying‑press/*) blocking FCP/LCP.
- Impact: Delays first render and LCP.
- Evidence: “Render blocking requests” list; no inline critical CSS.

4) Unused/Legacy/Duplicated JavaScript
- Symptoms: “Reduce unused JavaScript”, “Avoid serving legacy JS”, duplicates flagged.
- Impact: Extra parse/eval/transfer cost; increases TBT and payload.

5) Suboptimal Caching of Third‑Party Assets
- Symptoms: Short TTLs (20m–2m) or none on pixels/analytics; repeat visits pay repeatedly.
- Impact: Higher bandwidth, slower return visits.

6) Forced Reflows (Layout Thrash)
- Symptoms: Scripts triggering reflow after DOM/style mutations.
- Impact: Jank and extra main‑thread time.

7) Images Not Fully Optimized
- Symptoms: PNGs that could be compressed or served responsively/WebP.
- Impact: Minor but easy LCP win.

## Remediation Plan (90‑Day Rollout)

Phase 1 – Quick Wins (1–2 weeks)
- Defer/idle‑load non‑critical third‑party tags
  - Gate GTM‑injected tags (VWO, ZoomInfo, pixels, Segment) behind user interaction, requestIdleCallback, or consent.
  - In GTM, set triggers to fire after DOMContentLoaded/Window Loaded for non‑critical tags.
- Vimeo facade
  - Replace inline embed with a lightweight poster + play button; lazy‑load real player on click.
- Critical CSS
  - Inline above‑the‑fold critical CSS; load the rest asynchronously.
- Preconnect
  - Add up to 4 rel=preconnect for top origins you keep (e.g., your CDN, www.googletagmanager.com, f.vimeocdn.com if still used).
- Image optimizations
  - Convert target PNGs to WebP/AVIF; use correct sizes; set width/height to reduce CLS.

Phase 2 – Structural Optimizations (2–4 weeks)
- JS delivery
  - Ship modern (module) bundles to modern browsers; drop legacy transforms/polyfills where safe.
  - Code‑split route‑critical JS; lazy‑load admin/widgets not needed at first paint.
- Consent‑driven loading
  - Ensure pixels/AB tools load only after consent; configure Cookiebot to block until consent state.
- Tag governance
  - Remove duplicate/unused tags; consolidate analytics; prefer server‑side tagging for heavy pixels.

Phase 3 – Hardening and Monitoring (ongoing)
- RUM and budgets
  - Set performance budgets (TBT, JS transfer) and monitor via CI/RUM.
- A/B guardrails
  - Ensure experiments don’t auto‑load on all pages; scope experiments; use server‑side where possible.

## Concrete Fixes (Code Patterns)

1) Vimeo “lite” facade (HTML)
```html
<!-- Poster (LCP candidate) -->
<div class="video-facade" data-src="https://player.vimeo.com/video/ID">
  <img src="/path/poster.webp" alt="Video" width="1280" height="720" loading="lazy" decoding="async">
  <button class="play">Play</button>
</div>
<script>
document.addEventListener('click', function(e){
  const wrap = e.target.closest('.video-facade');
  if(!wrap) return;
  const src = wrap.dataset.src + '?autoplay=1&muted=1';
  const iframe = document.createElement('iframe');
  iframe.src = src;
  iframe.loading = 'lazy';
  iframe.allow = 'autoplay; fullscreen; picture-in-picture';
  iframe.setAttribute('allowfullscreen','');
  wrap.replaceWith(iframe);
});
</script>
```

2) Async/idle third‑party loaders
```html
<script>
// Fire after first interaction or idle
(function(){
  let loaded = false;
  function load(){ if(loaded) return; loaded = true;
    var s = document.createElement('script');
    s.src = 'https://www.googletagmanager.com/gtm.js?id=GTM-XXXX';
    s.async = true; document.head.appendChild(s);
  }
  window.addEventListener('scroll', load, {once:true, passive:true});
  window.addEventListener('pointerdown', load, {once:true});
  window.requestIdleCallback ? requestIdleCallback(load, {timeout:3000}) : setTimeout(load, 3000);
})();
</script>
```

3) Critical CSS + async CSS
```html
<style>
/* critical-above-the-fold.css (inline subset) */
</style>
<link rel="preload" href="/assets/bundle.css" as="style" onload="this.rel='stylesheet'">
<noscript><link rel="stylesheet" href="/assets/bundle.css"></noscript>
```

4) Preconnect (keep ≤4)
```html
<link rel="preconnect" href="https://www.googletagmanager.com" crossorigin>
<link rel="preconnect" href="https://f.vimeocdn.com" crossorigin>
<link rel="preconnect" href="https://player.vimeo.com" crossorigin>
<link rel="preconnect" href="https://cdn.segment.com" crossorigin>
```

## WordPress‑Specific Guidance

- Gate third‑party enqueues
  - Enqueue marketing/AB scripts conditionally (only on pages that need them) and with `wp_enqueue_script(..., [], null, true)` plus add `defer`.
- Inline critical CSS via theme hooks; move non‑critical styles to async load.
- Use server‑side or tag manager governance to limit auto‑loading third parties site‑wide.

## Lottie Migration Guidance (to keep ≥95%)

- Load player locally and deferred
  - Enqueue only on pages with Lottie (conditional); add `defer`; footer loading.
- Lazy load animations by default
  - Intersection Observer; only above‑the‑fold uses `immediate`.
- Cache and compress JSON
  - 1‑year Cache‑Control + Gzip/Brotli; host locally.
- Renderer choice
  - Default `svg`; use `canvas` for particularly complex animations or on mobile where needed.
- Keep files lean
  - Optimize Lottie JSONs; target hero ≤200KB, icons ≤50KB.

Reference implementation: `wordpress-integration/functions-lottie-optimized.php` (conditional enqueue, defer, lazy init, shortcode/helpers).

## Success Criteria & Expected Gains

- TBT: ↓ 400–800 ms (deferring third‑parties + Vimeo facade + smaller JS)
- Payload: ↓ multiple MB on initial view (Vimeo + non‑critical tags deferred)
- LCP: stays good (~0.9–1.2 s) and more consistent
- Return visits: faster with improved caching
- Lighthouse score: should climb from ~64 → 90+ desktop on pages without heavy third‑parties; 80–90 achievable on pages that still need some, if deferred/facaded.

## Rollout Checklist

- [ ] Replace Vimeo embeds with facade where possible
- [ ] Defer/gate non‑critical GTM tags (VWO, pixels, Segment, ZoomInfo, etc.)
- [ ] Inline critical CSS; async load remaining stylesheets
- [ ] Preconnect top origins (≤4) that remain
- [ ] Modern JS builds; code‑split; drop legacy/polyfills where safe
- [ ] Optimize images to WebP/AVIF + responsive sizes
- [ ] Conditional Lottie enqueue + lazy loading per our implementation
- [ ] Verify cache headers for JSON/JS (1‑year for local assets)
- [ ] Re‑test with PageSpeed (mobile & desktop) and GTmetrix

---

If you want, I can apply the Vimeo facade and third‑party deferral patterns to one high‑traffic page as a pilot, then we validate gains in PSI/GTmetrix before rolling out site‑wide.
