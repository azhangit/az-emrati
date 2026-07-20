<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <meta name="description" content="{{ get_setting('website_name') ?? 'Emirati Coffee' }} — Under Maintenance">

    <title>{{ get_setting('website_name') ?? 'Emirati Coffee' }} | Under Maintenance</title>

    @php
        $site_icon = uploaded_asset(get_setting('site_icon'));
        // Black footer-style mark (Arabic + Emirati Coffee Co)
        $logo = asset('assets/img/home-page/f-logo-1.png');
        $heroImg = static_asset('assets/img/home-page/emrati-main.png');
        $ig = get_setting('instagram_link') ?: 'https://www.instagram.com/emiraticoffee.ae/';
        $brand = get_setting('website_name') ?: 'Emirati Coffee & Co';
    @endphp

    <link rel="icon" href="{{ $site_icon }}">
    <link rel="apple-touch-icon" href="{{ $site_icon }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --brand-blue: #0077ED;
            --brand-blue-deep: #0069F7;
            --ink: #181818;
            --muted: #6e6e73;
            --mist: #EFEEF0;
            --panel: #F6F5F8;
            --white: #ffffff;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        html, body {
            height: 100%;
            font-family: "Public Sans", sans-serif;
            color: var(--ink);
            background: var(--mist);
            -webkit-font-smoothing: antialiased;
        }

        .cs {
            min-height: 100vh;
            min-height: 100dvh;
            position: relative;
            overflow: hidden;
            display: grid;
            place-items: center;
            background:
                radial-gradient(ellipse 80% 60% at 70% 40%, rgba(0, 119, 237, 0.08), transparent 55%),
                radial-gradient(ellipse 50% 40% at 15% 80%, rgba(0, 105, 247, 0.06), transparent 50%),
                linear-gradient(165deg, #f8f7f9 0%, #EFEEF0 45%, #e8e7eb 100%);
        }

        .cs__grain {
            pointer-events: none;
            position: absolute;
            inset: 0;
            opacity: 0.35;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.85' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)'/%3E%3C/svg%3E");
            mix-blend-mode: soft-light;
        }

        .cs__orb {
            position: absolute;
            width: min(70vw, 640px);
            height: min(70vw, 640px);
            border-radius: 50%;
            background: radial-gradient(circle, rgba(0, 119, 237, 0.12), transparent 68%);
            filter: blur(40px);
            animation: drift 14s ease-in-out infinite alternate;
            right: -8%;
            top: 10%;
        }

        .cs__stage {
            position: relative;
            z-index: 1;
            width: min(1120px, 100%);
            padding: clamp(2rem, 5vw, 4rem) clamp(1.25rem, 4vw, 3rem);
            display: grid;
            grid-template-columns: 1.05fr 0.95fr;
            gap: clamp(2rem, 5vw, 4.5rem);
            align-items: center;
        }

        .cs__brand {
            display: inline-flex;
            align-items: center;
            gap: 0.9rem;
            margin-bottom: clamp(1.5rem, 3vw, 2.25rem);
            opacity: 0;
            animation: rise 0.9s ease forwards 0.1s;
        }

        .cs__brand img {
            height: clamp(48px, 7vw, 64px);
            width: auto;
            display: block;
            object-fit: contain;
        }

        .cs__brand-text {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            gap: 0.35rem;
        }

        .cs__brand-name {
            font-size: clamp(1.05rem, 2vw, 1.25rem);
            font-weight: 600;
            letter-spacing: -0.02em;
            color: var(--ink);
            line-height: 1.2;
        }

        .cs__kicker {
            display: block;
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            color: var(--brand-blue);
            margin: 0;
        }

        .cs__title {
            font-size: clamp(2.75rem, 7vw, 5rem);
            font-weight: 700;
            line-height: 0.98;
            letter-spacing: -0.045em;
            color: var(--ink);
            margin-bottom: 1.15rem;
            opacity: 0;
            animation: rise 0.95s ease forwards 0.35s;
        }

        .cs__lede {
            max-width: 34ch;
            font-size: clamp(1rem, 2.1vw, 1.2rem);
            font-weight: 400;
            line-height: 1.55;
            color: var(--muted);
            margin-bottom: clamp(1.75rem, 3vw, 2.5rem);
            opacity: 0;
            animation: rise 0.95s ease forwards 0.5s;
        }

        .cs__actions {
            display: flex;
            flex-wrap: wrap;
            gap: 0.85rem;
            opacity: 0;
            animation: rise 0.95s ease forwards 0.65s;
        }

        .cs__btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.55rem;
            padding: 0.9rem 1.55rem;
            border-radius: 999px;
            font-size: 0.95rem;
            font-weight: 600;
            text-decoration: none;
            transition: transform 0.25s ease, background 0.25s ease, color 0.25s ease, box-shadow 0.25s ease;
        }

        .cs__btn--primary {
            background: var(--brand-blue);
            color: var(--white);
            box-shadow: 0 10px 28px rgba(0, 119, 237, 0.28);
        }

        .cs__btn--primary:hover {
            background: var(--brand-blue-deep);
            transform: translateY(-2px);
        }

        .cs__btn--ghost {
            background: transparent;
            color: var(--ink);
            border: 1.5px solid rgba(24, 24, 24, 0.18);
        }

        .cs__btn--ghost:hover {
            border-color: var(--brand-blue);
            color: var(--brand-blue);
            transform: translateY(-2px);
        }

        .cs__btn svg {
            width: 18px;
            height: 18px;
            fill: currentColor;
        }

        .cs__meta {
            margin-top: 2rem;
            font-size: 0.85rem;
            color: var(--muted);
            opacity: 0;
            animation: rise 0.95s ease forwards 0.8s;
        }

        .cs__visual {
            position: relative;
            display: grid;
            place-items: center;
            opacity: 0;
            animation: fadeIn 1.1s ease forwards 0.45s;
        }

        .cs__visual-glow {
            position: absolute;
            width: 78%;
            height: 78%;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(0, 119, 237, 0.18), transparent 70%);
            filter: blur(24px);
            animation: pulse 5.5s ease-in-out infinite;
        }

        .cs__visual img {
            position: relative;
            z-index: 1;
            width: min(100%, 480px);
            height: auto;
            object-fit: contain;
            filter: drop-shadow(0 28px 50px rgba(17, 23, 35, 0.18));
            animation: float 6s ease-in-out infinite;
        }

        .cs__footer {
            position: absolute;
            bottom: 1.25rem;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 0.75rem;
            color: rgba(110, 110, 115, 0.9);
            z-index: 2;
            opacity: 0;
            animation: rise 0.9s ease forwards 1s;
        }

        @keyframes rise {
            from { opacity: 0; transform: translateY(18px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-14px); }
        }

        @keyframes pulse {
            0%, 100% { opacity: 0.7; transform: scale(1); }
            50% { opacity: 1; transform: scale(1.06); }
        }

        @keyframes drift {
            from { transform: translate(0, 0); }
            to { transform: translate(-4%, 6%); }
        }

        @media (max-width: 860px) {
            .cs__stage {
                grid-template-columns: 1fr;
                text-align: center;
                padding-top: 3rem;
                padding-bottom: 4.5rem;
            }

            .cs__brand {
                justify-content: center;
            }

            .cs__brand-text {
                align-items: center;
            }

            .cs__lede {
                margin-left: auto;
                margin-right: auto;
            }

            .cs__actions {
                justify-content: center;
            }

            .cs__visual {
                order: -1;
            }

            .cs__visual img {
                width: min(72vw, 320px);
            }

            .cs__orb {
                top: -5%;
                right: -20%;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .cs__brand,
            .cs__kicker,
            .cs__title,
            .cs__lede,
            .cs__actions,
            .cs__meta,
            .cs__visual,
            .cs__footer,
            .cs__visual img,
            .cs__visual-glow,
            .cs__orb {
                animation: none !important;
                opacity: 1 !important;
            }
        }
    </style>
</head>
<body>
    <main class="cs">
        <div class="cs__grain" aria-hidden="true"></div>
        <div class="cs__orb" aria-hidden="true"></div>

        <div class="cs__stage">
            <div class="cs__copy">
                <div class="cs__brand">
                    <img src="{{ $logo }}" alt="{{ $brand }}">
                    <div class="cs__brand-text">
                        <span class="cs__brand-name">{{ $brand }}</span>
                        <p class="cs__kicker">Please wait</p>
                    </div>
                </div>

                <h1 class="cs__title">Site is Under Maintenance</h1>
                <p class="cs__lede">
                    We’re making things better behind the scenes. We’ll be back soon with a fresh pour.
                </p>

                <div class="cs__actions">
                    <a class="cs__btn cs__btn--primary" href="{{ $ig }}" target="_blank" rel="noopener noreferrer">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M7 2h10a5 5 0 0 1 5 5v10a5 5 0 0 1-5 5H7a5 5 0 0 1-5-5V7a5 5 0 0 1 5-5zm5 5.2A4.8 4.8 0 1 0 16.8 12 4.8 4.8 0 0 0 12 7.2zm0 7.9A3.1 3.1 0 1 1 15.1 12 3.1 3.1 0 0 1 12 15.1zm5.35-8.85a1.15 1.15 0 1 0 1.15 1.15 1.15 1.15 0 0 0-1.15-1.15z"/></svg>
                        Follow on Instagram
                    </a>
                    <a class="cs__btn cs__btn--ghost" href="mailto:hello@emiraticoffee.ae">
                        Get in touch
                    </a>
                </div>

                <p class="cs__meta">Thank you for your patience — we’ll be back shortly.</p>
            </div>

            <div class="cs__visual" aria-hidden="true">
                <div class="cs__visual-glow"></div>
                <img src="{{ $heroImg }}" alt="">
            </div>
        </div>

        <p class="cs__footer">&copy; {{ date('Y') }} Emirati Coffee Co.</p>
    </main>
</body>
</html>
