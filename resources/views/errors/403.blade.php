@php
    $dashboardUrl = null;
    if (Auth::guard('admin')->check()) {
        $dashboardUrl = route('admin.home');
    } elseif (Auth::guard('web')->check()) {
        $dashboardUrl = route('user.home');
    }
    $message = isset($exception) && $exception->getMessage() ? $exception->getMessage() : null;
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>403 - Forbidden</title>
<style>
  * { margin: 0; padding: 0; box-sizing: border-box; }

  :root {
    --bg: #06121a;
    --accent: #1880aa;
    --accent2: #4fc3e8;
    --fail: #ff4d5e;
    --text: #e4f1f6;
    --muted: #7d97a2;
  }

  body {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
    background: radial-gradient(circle at 50% 40%, #0d2432 0%, var(--bg) 68%);
    color: var(--text);
    overflow: hidden;
    text-align: center;
    padding: 2rem;
  }

  main { position: relative; z-index: 3; }

  /* ---------- SCANNER ---------- */
  .scanner {
    position: relative;
    width: 210px;
    height: 210px;
    margin: 0 auto 1.5rem;
  }

  .finger-svg {
    width: 100%;
    height: 100%;
    overflow: visible;
    filter: drop-shadow(0 0 14px rgba(24, 128, 170, 0.5));
  }

  .ring-frame {
    position: absolute;
    inset: -8px;
    border-radius: 50%;
    border: 1px dashed rgba(24, 128, 170, 0.45);
    animation: spin 8s linear infinite;
  }

  @keyframes spin { to { transform: rotate(360deg); } }

  .pulse {
    position: absolute;
    inset: 0;
    border-radius: 50%;
    border: 2px solid rgba(79, 195, 232, 0.5);
    animation: ping 2.6s ease-out infinite;
  }
  .pulse:nth-child(2) { animation-delay: 1.3s; }

  @keyframes ping {
    from { transform: scale(0.7); opacity: 0.8; }
    to   { transform: scale(1.35); opacity: 0; }
  }

  /* ---------- FAILURE OVERLAY ---------- */
  .fail-overlay {
    position: absolute;
    inset: 0;
    border-radius: 50%;
    background: radial-gradient(circle at center, rgba(255, 77, 94, 0.28), rgba(255, 77, 94, 0.05));
    opacity: 0;
    animation: failFlash 4s ease-in-out infinite;
    pointer-events: none;
  }

  @keyframes failFlash {
    0%, 64%   { opacity: 0; }
    70%, 88%  { opacity: 1; }
    96%, 100% { opacity: 0; }
  }

  .fail-mark {
    position: absolute;
    top: 50%;
    left: 50%;
    font-size: 74px;
    font-weight: 900;
    line-height: 1;
    color: var(--fail);
    transform: translate(-50%, -50%) scale(0.4);
    text-shadow: 0 0 24px rgba(255, 77, 94, 0.9);
    opacity: 0;
    animation: failMark 4s ease-in-out infinite;
    pointer-events: none;
  }

  @keyframes failMark {
    0%, 64%   { opacity: 0; transform: translate(-50%, -50%) scale(0.4) rotate(-20deg); }
    71%       { opacity: 1; transform: translate(-50%, -50%) scale(1.15) rotate(0deg); }
    85%       { opacity: 1; transform: translate(-50%, -50%) scale(1) rotate(0deg); }
    95%, 100% { opacity: 0; transform: translate(-50%, -50%) scale(1) rotate(0deg); }
  }

  .fail-label {
    position: absolute;
    left: 50%;
    bottom: -6px;
    transform: translateX(-50%);
    font-size: 0.72rem;
    letter-spacing: 0.3em;
    font-weight: 700;
    color: var(--fail);
    opacity: 0;
    animation: failFlash 4s ease-in-out infinite;
  }

  /* ---------- TEXT ---------- */
  .code {
    font-size: clamp(3.4rem, 14vw, 6.5rem);
    font-weight: 800;
    letter-spacing: 0.1em;
    line-height: 1;
    background: linear-gradient(90deg, var(--accent), var(--accent2), var(--accent));
    background-size: 200% auto;
    -webkit-background-clip: text;
    background-clip: text;
    -webkit-text-fill-color: transparent;
    animation: shine 4s linear infinite;
  }

  @keyframes shine { to { background-position: 200% center; } }

  h1 {
    font-size: clamp(1.3rem, 4vw, 1.9rem);
    margin: 0.5rem 0 0.75rem;
    letter-spacing: 0.03em;
  }

  p {
    color: var(--muted);
    max-width: 460px;
    margin: 0 auto 2rem;
    line-height: 1.6;
    font-size: 1.15rem;
  }

  .actions {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 1rem;
  }

  .btn {
    display: inline-block;
    padding: 0.85rem 2.2rem;
    border-radius: 999px;
    text-decoration: none;
    color: #04141c;
    font-weight: 700;
    background: linear-gradient(90deg, var(--accent), var(--accent2));
    background-size: 200% auto;
    transition: background-position 0.4s ease, transform 0.2s ease, box-shadow 0.3s ease;
  }

  .btn:hover {
    background-position: right center;
    transform: translateY(-3px);
    box-shadow: 0 10px 30px rgba(24, 128, 170, 0.6);
  }

  .btn:active { transform: translateY(0); }

  .ghost {
    color: var(--muted);
    text-decoration: none;
    font-size: 0.9rem;
    transition: color 0.2s ease;
  }

  .ghost:hover { color: var(--text); }
</style>
</head>
<body>
  <main>
    <div class="scanner">
      <div class="pulse"></div>
      <div class="pulse"></div>
      <div class="ring-frame"></div>

      <svg class="finger-svg" viewBox="0 0 200 210" xmlns="http://www.w3.org/2000/svg">
        <defs>
          <clipPath id="fingerClip">
            <path d="M100 8 C 55 8 22 45 22 96 L 22 150 C 22 190 55 205 100 205
                     C 145 205 178 190 178 150 L 178 96 C 178 45 145 8 100 8 Z"/>
          </clipPath>

          <filter id="glow" x="-40%" y="-40%" width="180%" height="180%">
            <feGaussianBlur stdDeviation="3.2" result="b"/>
            <feMerge>
              <feMergeNode in="b"/>
              <feMergeNode in="SourceGraphic"/>
            </feMerge>
          </filter>

          <mask id="reveal">
            <rect x="0" y="0" width="200" height="210" fill="black"/>
            <rect x="0" y="0" width="200" height="0" fill="white">
              <animate attributeName="height" dur="4s" repeatCount="indefinite"
                       values="0;210;210;210;0" keyTimes="0;0.62;0.68;0.9;1"/>
            </rect>
          </mask>

          <g id="fp" fill="none" stroke-width="4" stroke-linecap="round" clip-path="url(#fingerClip)">
            <ellipse cx="100" cy="118" rx="72" ry="80" stroke-dasharray="150 24 200 30"/>
            <ellipse cx="100" cy="118" rx="62" ry="70" stroke-dasharray="60 18 160 22 90 20"/>
            <ellipse cx="100" cy="118" rx="52" ry="59" stroke-dasharray="130 20 120 24"/>
            <ellipse cx="100" cy="118" rx="42" ry="48" stroke-dasharray="70 16 120 20 40 18"/>
            <ellipse cx="100" cy="118" rx="32" ry="37" stroke-dasharray="100 18 60 16"/>
            <ellipse cx="100" cy="118" rx="22" ry="26" stroke-dasharray="70 14 30 16"/>
            <ellipse cx="100" cy="118" rx="12" ry="15" stroke-dasharray="40 12 20 12"/>
            <path d="M100 106 L100 130"/>
          </g>
        </defs>

        <!-- dim base -->
        <use href="#fp" stroke="rgba(24,128,170,0.3)"/>

        <!-- bright revealed ridges -->
        <use href="#fp" stroke="#4fc3e8" filter="url(#glow)" mask="url(#reveal)"/>

        <!-- scan line -->
        <rect x="10" width="180" height="14" rx="7" fill="rgba(79,195,232,0.18)">
          <animate attributeName="y" dur="4s" repeatCount="indefinite"
                   values="0;196;196;196;0" keyTimes="0;0.62;0.68;0.9;1"/>
          <animate attributeName="opacity" dur="4s" repeatCount="indefinite"
                   values="1;1;0;0;1" keyTimes="0;0.62;0.68;0.9;1"/>
        </rect>
        <rect x="10" width="180" height="3" rx="1.5" fill="#4fc3e8">
          <animate attributeName="y" dur="4s" repeatCount="indefinite"
                   values="7;203;203;203;7" keyTimes="0;0.62;0.68;0.9;1"/>
          <animate attributeName="opacity" dur="4s" repeatCount="indefinite"
                   values="1;1;0;0;1" keyTimes="0;0.62;0.68;0.9;1"/>
        </rect>
      </svg>

      <div class="fail-overlay"></div>
      <div class="fail-mark">&times;</div>
      <div class="fail-label">ACCESS DENIED</div>
    </div>

    <div class="code">403</div>
    <h1>Access Forbidden</h1>
    <p>{{ $message ?: "You don't have permission to access this resource." }}</p>

    <div class="actions">
      <a href="{{ $dashboardUrl ?: '/' }}" class="btn">{{ $dashboardUrl ? 'Back to Dashboard' : 'Return Home' }}</a>
      <a href="javascript:history.back()" class="ghost">Go Back</a>
    </div>
  </main>
</body>
</html>
