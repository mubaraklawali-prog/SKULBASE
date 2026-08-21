<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Skulbase — Run your school from one dashboard</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Lexend:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&family=IBM+Plex+Mono:wght@500;600&display=swap"
        rel="stylesheet">
    <style>
        :root {
            --ink: #1c1730;
            --slate: #68627f;
            --slate-soft: #a29bbf;
            --cream: #e9e1f7;
            --panel: #f5f0fc;
            --line: #d3c6ef;
            --purple-900: #150a3d;
            --purple-700: #3d1f8c;
            --purple-600: #6d28d9;
            --purple-400: #9f6bf0;
            --purple-100: #f0e9fd;
            --gold: #f0b429;
            --green: #1a9e6b;
            --red: #c0344a;
            --radius: 16px;
            --font-display: 'Lexend', sans-serif;
            --font-body: 'Inter', sans-serif;
            --font-mono: 'IBM Plex Mono', monospace;
        }

        * {
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        @media (prefers-reduced-motion: reduce) {
            html {
                scroll-behavior: auto;
            }

            * {
                animation-duration: .001ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: .001ms !important;
            }
        }

        body {
            margin: 0;
            background: var(--cream);
            color: var(--ink);
            font-family: var(--font-body);
            line-height: 1.55;
            -webkit-font-smoothing: antialiased;
            overflow-x: hidden;
            width: 100%;
            max-width: 100vw;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        img,
        svg {
            display: block;
            max-width: 100%;
        }

        .container {
            max-width: 1160px;
            margin: 0 auto;
            padding: 0 24px;
        }

        h1,
        h2,
        h3 {
            font-family: var(--font-display);
            color: var(--purple-900);
            margin: 0;
            letter-spacing: -0.01em;
        }

        :focus-visible {
            outline: 2.5px solid var(--purple-600);
            outline-offset: 2px;
            border-radius: 4px;
        }

        /* ===== UTILITY BAR ===== */
        .utilitybar {
            background: var(--purple-900);
            color: #d9cff5;
            font-size: 12.5px;
            font-weight: 500;
        }

        .utilitybar .container {
            display: flex;
            justify-content: flex-end;
            gap: 20px;
            padding: 8px 24px;
        }

        .utilitybar a {
            opacity: .85;
            transition: opacity .15s;
        }

        .utilitybar a:hover {
            opacity: 1;
            color: #fff;
        }

        /* ===== NAV ===== */
        .nav {
            background: var(--cream);
            position: sticky;
            top: 0;
            z-index: 50;
            border-bottom: 1px solid var(--line);
            transition: box-shadow .25s ease, padding .25s ease, background .25s ease;
        }

        .nav.scrolled {
            box-shadow: 0 8px 24px -12px rgba(21, 10, 61, .18);
            background: rgba(233, 225, 247, .9);
            backdrop-filter: blur(10px);
        }

        .nav .container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 24px;
            gap: 16px;
            flex-wrap: wrap;
            transition: padding .25s ease;
        }

        .nav.scrolled .container {
            padding: 11px 24px;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 9px;
            font-family: var(--font-display);
            font-weight: 800;
            font-size: 19px;
            background: var(--purple-900);
            color: #fff;
            padding: 6px 16px 6px 6px;
            border-radius: 14px;
        }

        .brand-skul {
            color: #fff;
        }

        .brand-base {
            color: var(--purple-400);
        }

        .brand-mark {
            width: 32px;
            height: 32px;
            border-radius: 9px;
            background: linear-gradient(135deg, var(--purple-400), var(--purple-700));
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 17px;
            font-weight: 800;
            transition: transform .3s cubic-bezier(.34, 1.56, .64, 1);
        }

        .brand:hover .brand-mark {
            transform: rotate(-8deg) scale(1.08);
        }

        .navlinks {
            display: flex;
            gap: 28px;
            font-size: 14.5px;
            font-weight: 500;
            color: var(--slate);
        }

        .navlinks a {
            position: relative;
            padding-bottom: 3px;
            transition: color .2s;
        }

        .navlinks a::after {
            content: "";
            position: absolute;
            left: 0;
            bottom: 0;
            width: 100%;
            height: 2px;
            background: var(--purple-600);
            border-radius: 2px;
            transform: scaleX(0);
            transform-origin: right;
            transition: transform .25s ease;
        }

        .navlinks a:hover {
            color: var(--purple-600);
        }

        .navlinks a:hover::after {
            transform: scaleX(1);
            transform-origin: left;
        }

        .navcta {
            display: flex;
            gap: 10px;
            align-items: center;
            flex-shrink: 0;
        }

        .btn {
            font-family: var(--font-body);
            font-weight: 700;
            font-size: 14px;
            padding: 10px 20px;
            border-radius: 9px;
            border: 1.5px solid transparent;
            display: inline-flex;
            align-items: center;
            gap: 7px;
            cursor: pointer;
            transition: background .2s, border-color .2s, transform .2s, box-shadow .2s;
        }

        .btn-primary {
            background: var(--purple-600);
            color: #fff;
        }

        .btn-primary:hover {
            background: var(--purple-700);
            transform: translateY(-2px);
            box-shadow: 0 10px 20px -8px rgba(109, 40, 217, .5);
        }

        .btn-ghost {
            color: var(--purple-900);
            border-color: var(--line);
            background: #fff;
        }

        .btn-ghost:hover {
            border-color: var(--purple-400);
            transform: translateY(-2px);
        }

        .btn:active {
            transform: translateY(0);
        }

        /* mobile menu toggle */
        .nav-toggle {
            display: none;
            width: 38px;
            height: 38px;
            border-radius: 8px;
            border: 1.5px solid var(--line);
            background: #fff;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            cursor: pointer;
        }

        .nav-toggle span {
            display: block;
            width: 16px;
            height: 2px;
            background: var(--purple-900);
            position: relative;
            transition: background .2s .1s;
        }

        .nav-toggle span::before,
        .nav-toggle span::after {
            content: "";
            position: absolute;
            left: 0;
            width: 16px;
            height: 2px;
            background: var(--purple-900);
            transition: transform .25s ease, top .25s ease;
        }

        .nav-toggle span::before {
            top: -5px;
        }

        .nav-toggle span::after {
            top: 5px;
        }

        .nav-toggle.open span {
            background: transparent;
        }

        .nav-toggle.open span::before {
            top: 0;
            transform: rotate(45deg);
        }

        .nav-toggle.open span::after {
            top: 0;
            transform: rotate(-45deg);
        }

        .mobile-menu {
            display: none;
            flex-direction: column;
            gap: 2px;
            max-height: 0;
            overflow: hidden;
            transition: max-height .3s ease;
            border-top: 1px solid var(--line);
        }

        .mobile-menu.open {
            max-height: 260px;
        }

        .mobile-menu a {
            padding: 14px 24px;
            font-size: 14.5px;
            font-weight: 600;
            color: var(--purple-900);
            border-bottom: 1px solid var(--line);
        }

        .mobile-menu a:hover {
            background: var(--purple-100);
        }

        @media(max-width:760px) {
            .navlinks {
                display: none;
            }

            .nav-toggle {
                display: flex;
            }

            .mobile-menu {
                display: flex;
            }
        }

        /* ===== HERO ===== */
        .hero {
            padding: 64px 0 40px;
            position: relative;
            overflow: hidden;
        }

        .hero::before,
        .hero::after {
            content: "";
            position: absolute;
            border-radius: 50%;
            filter: blur(70px);
            z-index: 0;
            pointer-events: none;
            animation: blobDrift 14s ease-in-out infinite;
        }

        .hero::before {
            width: 360px;
            height: 360px;
            top: -140px;
            left: -110px;
            opacity: .4;
            background: radial-gradient(circle, var(--purple-400), transparent 70%);
        }

        .hero::after {
            width: 300px;
            height: 300px;
            bottom: -150px;
            right: -90px;
            opacity: .22;
            animation-delay: -7s;
            background: radial-gradient(circle, var(--gold), transparent 70%);
        }

        @keyframes blobDrift {

            0%,
            100% {
                transform: translate(0, 0) scale(1);
            }

            50% {
                transform: translate(26px, -18px) scale(1.08);
            }
        }

        .hero .container {
            position: relative;
            z-index: 1;
        }

        .hero-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 56px;
            align-items: center;
        }

        .eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            font-family: var(--font-mono);
            font-size: 12px;
            font-weight: 600;
            letter-spacing: .03em;
            color: var(--purple-600);
            background: var(--purple-100);
            padding: 6px 12px;
            border-radius: 20px;
            margin-bottom: 18px;
        }

        .eyebrow .dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: var(--green);
        }

        .h1 {
            font-size: clamp(30px, 4.2vw, 44px);
            font-weight: 800;
            line-height: 1.12;
            margin-bottom: 18px;
            max-width: 420px;
            text-wrap: balance;
        }

        /* --- Hero entrance choreography --- */
        @keyframes heroRise {
            from {
                opacity: 0;
                transform: translateY(16px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .eyebrow,
        .h1,
        .hero-sub,
        .hero-actions,
        .hero-microcopy {
            opacity: 0;
            animation: heroRise .8s cubic-bezier(.16, 1, .3, 1) forwards;
        }

        .eyebrow {
            animation-delay: .05s;
        }

        .h1 {
            animation-delay: .18s;
        }

        .hero-sub {
            animation-delay: .38s;
        }

        .hero-actions {
            animation-delay: .56s;
        }

        .hero-microcopy {
            animation-delay: .72s;
        }

        /* --- Shimmering headline highlight --- */
        .h1 em {
            font-style: normal;
            position: relative;
            display: inline-block;
            background: linear-gradient(100deg, var(--purple-700) 15%, var(--purple-400) 35%, #ffffff 50%, var(--purple-400) 65%, var(--purple-700) 85%);
            background-size: 250% auto;
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            color: var(--purple-600);
            animation: shineText 3.2s linear infinite;
            animation-delay: 1.3s;
        }

        @keyframes shineText {
            0% {
                background-position: 200% center;
            }

            100% {
                background-position: -50% center;
            }
        }

        .h1 em::after {
            content: "";
            position: absolute;
            left: 1px;
            right: 1px;
            bottom: -2px;
            height: 3px;
            border-radius: 3px;
            background: linear-gradient(90deg, transparent, var(--gold), transparent);
            background-size: 200% auto;
            opacity: 0;
            animation: underlineSheen 3.2s linear infinite, underlineFade .8s ease forwards;
            animation-delay: 1.3s, 1.3s;
        }

        @keyframes underlineSheen {
            0% {
                background-position: 200% center;
            }

            100% {
                background-position: -50% center;
            }
        }

        @keyframes underlineFade {
            to {
                opacity: 1;
            }
        }

        .h1 .run-word {
            display: inline-block;
            color: var(--purple-600);
            animation: runPop .6s cubic-bezier(.34, 1.56, .64, 1) both;
            animation-delay: .55s;
        }

        @keyframes runPop {
            0% {
                opacity: 0;
                transform: translateY(6px) scale(.85);
            }

            60% {
                opacity: 1;
                transform: translateY(-2px) scale(1.06);
            }

            100% {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .h1 .sparkle {
            display: inline-block;
            font-size: 0.55em;
            line-height: 0;
            vertical-align: super;
            margin-left: 2px;
            color: var(--gold);
            opacity: 0;
            transform: scale(.4) rotate(-15deg);
            animation: sparklePop 3.2s ease-in-out infinite;
            animation-delay: 1.6s;
        }

        @keyframes sparklePop {

            0%,
            100% {
                opacity: 0;
                transform: scale(.3) rotate(-15deg);
            }

            8% {
                opacity: 1;
                transform: scale(1.1) rotate(0deg);
            }

            16% {
                opacity: .85;
                transform: scale(.9) rotate(8deg);
            }

            24% {
                opacity: 0;
                transform: scale(.4) rotate(15deg);
            }
        }

        .hero-sub {
            font-size: 17px;
            color: var(--slate);
            max-width: 440px;
            margin-bottom: 30px;
        }

        .hero-sub .pop {
            color: var(--purple-700);
            font-weight: 600;
            background: linear-gradient(180deg, transparent 62%, var(--purple-100) 62%);
            padding: 0 1px;
            border-radius: 3px;
        }

        .hero-actions {
            display: flex;
            gap: 14px;
            flex-wrap: wrap;
            margin-bottom: 16px;
        }

        .hero-actions .btn-primary {
            position: relative;
            overflow: hidden;
        }

        .hero-actions .btn-primary::after {
            content: "";
            position: absolute;
            top: 0;
            left: -60%;
            width: 45%;
            height: 100%;
            background: linear-gradient(120deg, transparent, rgba(255, 255, 255, .65), transparent);
            transform: skewX(-20deg);
            animation: btnShine 1.3s ease 1.9s 1;
        }

        @keyframes btnShine {
            from {
                left: -60%;
            }

            to {
                left: 140%;
            }
        }

        .btn-lg {
            padding: 14px 26px;
            font-size: 15px;
            border-radius: 11px;
        }

        .hero-microcopy {
            font-size: 12.5px;
            color: var(--slate-soft);
        }

        /* hero visual: dashboard mock + emerging report card */
        .hero-visual {
            position: relative;
            opacity: 0;
            animation: heroRise .8s cubic-bezier(.16, 1, .3, 1) forwards;
            animation-delay: .3s;
        }

        .dash-mock {
            background: var(--purple-900);
            border-radius: 18px;
            padding: 16px;
            box-shadow: 0 30px 60px -20px rgba(21, 10, 61, .35);
            animation: dashFloat 4.6s ease-in-out infinite;
            animation-delay: 1.1s;
        }

        @keyframes dashFloat {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-9px);
            }
        }

        .dash-mock-top {
            display: flex;
            gap: 6px;
            margin-bottom: 14px;
        }

        .dash-mock-top span {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .25);
        }

        .dash-mock-body {
            background: #1e1450;
            border-radius: 12px;
            padding: 16px;
            display: grid;
            gap: 10px;
        }

        .dash-stat-row {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 8px;
        }

        .dash-stat {
            background: rgba(255, 255, 255, .06);
            border-radius: 9px;
            padding: 10px;
        }

        .dash-stat .n {
            font-family: var(--font-display);
            font-weight: 800;
            color: #fff;
            font-size: 17px;
        }

        .dash-stat .l {
            font-size: 9.5px;
            color: #b3a7e0;
            text-transform: uppercase;
            letter-spacing: .04em;
            margin-top: 2px;
        }

        .dash-chart {
            background: rgba(255, 255, 255, .06);
            border-radius: 9px;
            padding: 12px;
            height: 70px;
        }

        .dash-chart svg {
            width: 100%;
            height: 100%;
        }

        .report-card-float {
            position: absolute;
            right: -18px;
            bottom: -30px;
            width: 230px;
            background: #fff;
            border-radius: 12px;
            padding: 16px;
            box-shadow: 0 20px 45px -12px rgba(21, 10, 61, .3);
            border: 1px solid var(--line);
            animation: cardFloat 5.2s ease-in-out infinite;
            animation-delay: 1.1s;
        }

        @keyframes cardFloat {

            0%,
            100% {
                transform: translateY(0) rotate(0deg);
            }

            50% {
                transform: translateY(7px) rotate(-.6deg);
            }
        }

        .rc-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .rc-title {
            font-family: var(--font-display);
            font-weight: 700;
            font-size: 12.5px;
            color: var(--purple-900);
        }

        .rc-badge {
            font-family: var(--font-mono);
            font-size: 9.5px;
            font-weight: 600;
            color: var(--green);
            background: #e6f7ef;
            padding: 2px 7px;
            border-radius: 10px;
        }

        .rc-row {
            display: flex;
            justify-content: space-between;
            font-size: 11px;
            color: var(--slate);
            padding: 4px 0;
            border-bottom: 1px dashed var(--line);
        }

        .rc-row b {
            color: var(--ink);
            font-weight: 600;
        }

        .rc-foot {
            margin-top: 8px;
            font-family: var(--font-mono);
            font-size: 10px;
            color: var(--purple-600);
            font-weight: 600;
        }

        @media(max-width:900px) {
            .hero-grid {
                grid-template-columns: 1fr;
            }

            .h1 {
                font-size: 33px;
            }

            .report-card-float {
                position: static;
                width: auto;
                margin-top: 22px;
            }
        }

        /* ===== SECTION SHARED ===== */
        section {
            padding: 68px 0;
        }

        .section-head {
            max-width: 560px;
            margin: 0 auto 40px;
            text-align: center;
        }

        .section-eyebrow {
            font-family: var(--font-mono);
            font-size: 12px;
            font-weight: 600;
            letter-spacing: .06em;
            text-transform: uppercase;
            color: var(--purple-600);
            margin-bottom: 10px;
        }

        .h2 {
            font-size: 29px;
            font-weight: 800;
            margin-bottom: 10px;
        }

        .section-sub {
            color: var(--slate);
            font-size: 15.5px;
        }

        /* ===== PROBLEM ===== */
        .problem {
            background: var(--panel);
            border-top: 1px solid var(--line);
            border-bottom: 1px solid var(--line);
        }

        .problem-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }

        .problem-card {
            background: var(--cream);
            border: 1px solid var(--line);
            border-radius: 14px;
            padding: 22px;
        }

        .problem-icon {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: #fdeceb;
            color: var(--red);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 17px;
            margin-bottom: 14px;
        }

        .problem-card h3 {
            font-size: 15.5px;
            font-weight: 700;
            margin-bottom: 6px;
        }

        .problem-card p {
            font-size: 13.5px;
            color: var(--slate);
            margin: 0;
        }

        @media(max-width:760px) {
            .problem-grid {
                grid-template-columns: 1fr;
            }
        }

        /* ===== FEATURES ===== */
        .feat-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 18px;
            margin-bottom: 20px;
        }

        .feat-card {
            background: var(--panel);
            border: 1px solid var(--line);
            border-radius: 14px;
            padding: 22px;
        }

        .feat-icon {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: var(--purple-100);
            color: var(--purple-600);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 17px;
            margin-bottom: 14px;
        }

        .feat-card h3 {
            font-size: 15.5px;
            font-weight: 700;
            margin-bottom: 6px;
        }

        .feat-card p {
            font-size: 13.5px;
            color: var(--slate);
            margin: 0;
        }

        @media(max-width:760px) {
            .feat-grid {
                grid-template-columns: 1fr;
            }
        }

        /* ===== FEATURED: REPORT CARDS ===== */
        .featured {
            position: relative;
            background: linear-gradient(135deg, var(--purple-900), var(--purple-700));
            border-radius: 20px;
            padding: 40px;
            color: #fff;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 36px;
            align-items: center;
            overflow: hidden;
        }

        .featured::before {
            content: "";
            position: absolute;
            top: -60px;
            right: -60px;
            width: 220px;
            height: 220px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(159, 107, 240, .35), transparent 70%);
        }

        .featured-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-family: var(--font-mono);
            font-size: 11px;
            font-weight: 600;
            letter-spacing: .04em;
            background: rgba(255, 255, 255, .12);
            border: 1px solid rgba(255, 255, 255, .25);
            padding: 5px 12px;
            border-radius: 20px;
            margin-bottom: 16px;
        }

        .featured h3 {
            color: #fff;
            font-size: 24px;
            font-weight: 800;
            margin-bottom: 12px;
            line-height: 1.2;
        }

        .featured .before {
            font-size: 13.5px;
            color: #c9b8ef;
            text-decoration: line-through;
            opacity: .75;
            margin: 0 0 6px;
        }

        .featured .after-text {
            font-size: 14.5px;
            color: #e8ddfb;
            margin: 0 0 20px;
            line-height: 1.6;
        }

        .featured-stat {
            display: inline-flex;
            align-items: baseline;
            gap: 8px;
            background: rgba(255, 255, 255, .1);
            border-radius: 10px;
            padding: 10px 16px;
        }

        .featured-stat .num {
            font-family: var(--font-display);
            font-size: 24px;
            font-weight: 800;
        }

        .featured-stat .lbl {
            font-size: 11.5px;
            color: #c9b8ef;
        }

        .featured-visual {
            background: #fff;
            border-radius: 14px;
            padding: 20px;
            color: var(--ink);
            position: relative;
            z-index: 1;
            box-shadow: 0 25px 50px -15px rgba(0, 0, 0, .4);
        }

        .fv-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 14px;
            padding-bottom: 12px;
            border-bottom: 1px solid var(--line);
        }

        .fv-head .school {
            font-family: var(--font-display);
            font-weight: 700;
            font-size: 13px;
            color: var(--purple-900);
        }

        .fv-head .term {
            font-size: 11px;
            color: var(--slate);
        }

        .fv-row {
            display: flex;
            justify-content: space-between;
            font-size: 12.5px;
            padding: 6px 0;
            color: var(--slate);
        }

        .fv-row b {
            color: var(--ink);
        }

        .fv-grade {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 12px;
            padding-top: 12px;
            border-top: 1px dashed var(--line);
        }

        .fv-grade .avg {
            font-family: var(--font-display);
            font-size: 20px;
            font-weight: 800;
            color: var(--purple-600);
        }

        .fv-grade .pos {
            font-family: var(--font-mono);
            font-size: 11px;
            color: var(--green);
            font-weight: 600;
        }

        @media(max-width:860px) {
            .featured {
                grid-template-columns: 1fr;
                padding: 28px;
            }
        }

        /* ===== DEMO ===== */
        .demo-frame {
            background: var(--purple-900);
            border-radius: 18px;
            padding: 10px;
            box-shadow: 0 30px 60px -25px rgba(21, 10, 61, .4);
        }

        .demo-inner {
            background: linear-gradient(135deg, var(--purple-700), var(--purple-900));
            border-radius: 12px;
            aspect-ratio: 16/9;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            gap: 14px;
            color: #fff;
        }

        .play-btn {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            background: #fff;
            color: var(--purple-700);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            box-shadow: 0 12px 30px rgba(0, 0, 0, .3);
        }

        .demo-inner p {
            font-size: 13px;
            color: #d9cff5;
            margin: 0;
        }

        /* ===== PRICING ===== */
        .pricing-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
        }

        .price-card {
            background: var(--panel);
            border: 1.5px solid var(--line);
            border-radius: 16px;
            padding: 24px 20px;
            position: relative;
        }

        .price-card.rec {
            border-color: var(--purple-600);
            box-shadow: 0 12px 30px -14px rgba(109, 40, 217, .35);
        }

        .rec-tag {
            position: absolute;
            top: -11px;
            left: 20px;
            background: var(--purple-600);
            color: #fff;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: .04em;
            text-transform: uppercase;
            padding: 4px 10px;
            border-radius: 10px;
        }

        .plan-name {
            font-family: var(--font-display);
            font-weight: 700;
            font-size: 15.5px;
            margin-bottom: 4px;
        }

        .plan-price {
            font-family: var(--font-display);
            font-size: 26px;
            font-weight: 800;
            color: var(--purple-900);
        }

        .plan-price span {
            font-size: 12px;
            font-weight: 500;
            color: var(--slate);
        }

        .plan-yearly {
            font-size: 11.5px;
            color: var(--slate-soft);
            margin: 2px 0 14px;
        }

        .plan-feat {
            font-size: 12.5px;
            color: var(--slate);
            padding: 5px 0;
            display: flex;
            gap: 6px;
            align-items: flex-start;
        }

        .plan-feat b {
            color: var(--ink);
        }

        .plan-cta {
            display: block;
            text-align: center;
            margin-top: 16px;
            padding: 10px;
            border-radius: 9px;
            font-size: 13px;
            font-weight: 700;
            border: 1.5px solid var(--purple-600);
            color: var(--purple-600);
        }

        .price-card.rec .plan-cta {
            background: var(--purple-600);
            color: #fff;
        }

        @media(max-width:900px) {
            .pricing-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media(max-width:560px) {
            .pricing-grid {
                grid-template-columns: 1fr;
            }
        }

        /* ===== FINAL CTA ===== */
        .final {
            background: linear-gradient(135deg, var(--purple-900), var(--purple-700));
            border-radius: 22px;
            padding: 56px 40px;
            text-align: center;
            color: #fff;
            position: relative;
            overflow: hidden;
        }

        .final::before {
            content: "";
            position: absolute;
            bottom: -80px;
            left: 50%;
            transform: translateX(-50%);
            width: 320px;
            height: 320px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(159, 107, 240, .3), transparent 70%);
        }

        .final h2 {
            color: #fff;
            font-size: 28px;
            margin-bottom: 10px;
            position: relative;
        }

        .final p {
            color: #d9cff5;
            font-size: 15px;
            margin-bottom: 26px;
            position: relative;
        }

        .final .hero-actions {
            justify-content: center;
            position: relative;
        }

        /* ===== FOOTER ===== */
        footer {
            border-top: 1px solid var(--line);
            padding: 28px 0;
        }

        footer .container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
        }

        footer p {
            font-size: 12.5px;
            color: var(--purple-600);
            font-weight: 500;
            margin: 0;
        }

        footer .flinks {
            display: flex;
            gap: 18px;
            font-size: 12.5px;
            color: var(--slate);
        }
    </style>
</head>

<body>

    <div class="utilitybar">
        <div class="container">
            <a href="#demo">Tutorial</a>
            <a href="{{ route('login') }}">Login</a>
            <a href="{{ route('school.register') }}">Register</a>
        </div>
    </div>

    <nav class="nav" id="mainNav">
        <div class="container">
            <div class="brand"><span class="brand-mark">🎓</span><span><span class="brand-skul">Skul</span><span
                        class="brand-base">base</span></span></div>
            <div class="navlinks">
                <a href="#features">Features</a>
                <a href="#pricing">Pricing</a>
                <a href="#demo">Tutorial</a>
                <a href="{{ route('affiliates.register') }}">Affiliate</a>
            </div>
            <div class="navcta">
                <a class="btn btn-ghost" href="{{ route('login') }}">Log in</a>
                <a class="btn btn-primary" href="{{ route('school.register') }}">Start Free Trial</a>
                <button class="nav-toggle" id="navToggle" aria-label="Toggle menu"
                    aria-expanded="false"><span></span></button>
            </div>
        </div>
        <div class="mobile-menu" id="mobileMenu">
            <a href="#features">Features</a>
            <a href="#pricing">Pricing</a>
            <a href="#demo">Tutorial</a>
            <a href="{{ route('affiliates.register') }}">Affiliate</a>
        </div>
    </nav>

    <header class="hero">
        <div class="container hero-grid">
            <div>
                <div class="eyebrow"><span class="dot"></span> School management, simplified</div>
                <h1 class="h1"><span class="run-word">Run</span> your entire school from <em>one dashboard</em><span
                        class="sparkle" aria-hidden="true">✦</span></h1>
                <p class="hero-sub"><span class="pop">Students</span>, <span class="pop">attendance</span>, <span
                        class="pop">fees</span>, <span class="pop">report cards</span> and <span
                        class="pop">parent communication</span> — no more spreadsheets, registers, or manual grading.
                    Skulbase brings your school's daily operations into one place.</p>
                <div class="hero-actions">
                    <a class="btn btn-primary btn-lg" href="{{ route('school.register') }}">Start Free Trial</a>
                    <a class="btn btn-ghost btn-lg" href="#demo">▶ Watch Demo</a>
                </div>
                <p class="hero-microcopy">30-day free trial · no card required</p>
            </div>

            <div class="hero-visual">
                <div class="dash-mock">
                    <div class="dash-mock-top"><span></span><span></span><span></span></div>
                    <div class="dash-mock-body">
                        <div class="dash-stat-row">
                            <div class="dash-stat">
                                <div class="n">612</div>
                                <div class="l">Students</div>
                            </div>
                            <div class="dash-stat">
                                <div class="n">94%</div>
                                <div class="l">Attendance</div>
                            </div>
                            <div class="dash-stat">
                                <div class="n">₦8.6M</div>
                                <div class="l">Fees</div>
                            </div>
                        </div>
                        <div class="dash-chart">
                            <svg viewBox="0 0 300 70" preserveAspectRatio="none">
                                <polyline points="0,55 40,50 80,40 120,45 160,25 200,30 240,15 300,10" fill="none"
                                    stroke="#9f6bf0" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="report-card-float">
                    <div class="rc-head">
                        <span class="rc-title">Report Card</span>
                        <span class="rc-badge">Generated</span>
                    </div>
                    <div class="rc-row"><span>Mathematics</span><b>A</b></div>
                    <div class="rc-row"><span>English</span><b>A</b></div>
                    <div class="rc-row"><span>Basic Science</span><b>B+</b></div>
                    <div class="rc-foot">✓ Automatically generated</div>
                </div>
            </div>
        </div>
    </header>

    <section class="problem">
        <div class="container">
            <div class="section-head">
                <div class="section-eyebrow">The problem</div>
                <h2 class="h2">Running a school shouldn't mean fighting your own paperwork</h2>
            </div>
            <div class="problem-grid">
                <div class="problem-card">
                    <div class="problem-icon">📋</div>
                    <h3>Manual attendance registers</h3>
                    <p>Paper registers get lost, miscounted, and take hours to compile at the end of term.</p>
                </div>
                <div class="problem-card">
                    <div class="problem-icon">📊</div>
                    <h3>Spreadsheet fee tracking</h3>
                    <p>Chasing balances across scattered spreadsheets makes it easy to lose track of who's paid.</p>
                </div>
                <div class="problem-card">
                    <div class="problem-icon">📝</div>
                    <h3>Slow, error-prone report cards</h3>
                    <p>Calculating grades and typing report cards by hand, one student at a time, every single term.</p>
                </div>
            </div>
        </div>
    </section>

    <section id="features">
        <div class="container">
            <div class="section-head">
                <div class="section-eyebrow">Core features</div>
                <h2 class="h2">Everything your school needs, in one place</h2>
                <p class="section-sub">Built for how Nigerian schools actually run — day to day, term to term.</p>
            </div>

            <div class="feat-grid">
                <div class="feat-card">
                    <div class="feat-icon">🧑‍🎓</div>
                    <h3>Student management</h3>
                    <p>Add students, assign classes, and keep every record organized in one searchable system.</p>
                </div>
                <div class="feat-card">
                    <div class="feat-icon">📅</div>
                    <h3>Digital attendance</h3>
                    <p>Teachers mark attendance in seconds. Admins see school-wide trends instantly.</p>
                </div>
                <div class="feat-card">
                    <div class="feat-icon">💳</div>
                    <h3>Fee tracking</h3>
                    <p>See who's paid, who owes, and generate receipts — no spreadsheet needed.</p>
                </div>
            </div>

            <div class="featured">
                <div>
                    <div class="featured-badge">⭐ Key feature</div>
                    <h3>Report cards, generated automatically.</h3>
                    <p class="before">Manually calculating grades and typing report cards one by one</p>
                    <p class="after-text">Teachers enter scores once — Skulbase calculates the results and generates
                        each student's report card automatically, ready to review and print.</p>
                    <div class="featured-stat">
                        <span class="num">Automatic</span>
                        <span class="lbl">report card<br>generation</span>
                    </div>
                </div>
                <div class="featured-visual">
                    <div class="fv-head">
                        <span class="school">Bright Future Academy</span>
                        <span class="term">Term 1 · 2026</span>
                    </div>
                    <div class="fv-row"><span>Mathematics</span><b>92 — A</b></div>
                    <div class="fv-row"><span>English Language</span><b>88 — A</b></div>
                    <div class="fv-row"><span>Basic Science</span><b>79 — B+</b></div>
                    <div class="fv-row"><span>Social Studies</span><b>85 — A</b></div>
                    <div class="fv-grade">
                        <span class="avg">86.0%</span>
                        <span class="pos">Position 3 of 42</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="demo">
        <div class="container">
            <div class="section-head">
                <div class="section-eyebrow">Product demo</div>
                <h2 class="h2">See Skulbase in action</h2>
                <p class="section-sub">Discover how schools can manage students, fees, attendance and results in one
                    place.</p>
            </div>
            <div class="demo-frame">
                <div class="demo-inner">
                    <div class="play-btn">▶</div>
                    <p>Watch the 90-second demo · captions on</p>
                </div>
            </div>
        </div>
    </section>

    <section id="pricing">
        <div class="container">
            <div class="section-head">
                <div class="section-eyebrow">Pricing</div>
                <h2 class="h2">Start free. Upgrade when you're ready.</h2>
                <p class="section-sub">Every plan includes a 30-day free trial — no card required to start.</p>
            </div>
            <div class="pricing-grid">
                <div class="price-card rec">
                    <span class="rec-tag">Recommended</span>
                    <div class="plan-name">Free Trial</div>
                    <div class="plan-price">₦0</div>
                    <div class="plan-yearly">30 days, no payment required</div>
                    <div class="plan-feat">✓ Full access to every feature</div>
                    <div class="plan-feat">✓ No card required</div>
                    <div class="plan-feat">✓ Choose a plan after your trial</div>
                    <a class="plan-cta" href="{{ route('school.register') }}">Start Free Trial</a>
                </div>
                <div class="price-card">
                    <div class="plan-name">Starter</div>
                    <div class="plan-price">₦5,000<span>/mo</span></div>
                    <div class="plan-yearly">Yearly: ₦50,000</div>
                    <div class="plan-feat">✓ Up to <b>300 students</b></div>
                    <div class="plan-feat">✓ 30-day free trial included</div>
                    <a class="plan-cta" href="{{ route('school.register') }}">Get Started</a>
                </div>
                <div class="price-card">
                    <div class="plan-name">Standard</div>
                    <div class="plan-price">₦10,000<span>/mo</span></div>
                    <div class="plan-yearly">Yearly: ₦100,000</div>
                    <div class="plan-feat">✓ Up to <b>1,000 students</b></div>
                    <div class="plan-feat">✓ 30-day free trial included</div>
                    <a class="plan-cta" href="{{ route('school.register') }}">Get Started</a>
                </div>
                <div class="price-card">
                    <div class="plan-name">Premium</div>
                    <div class="plan-price">₦20,000<span>/mo</span></div>
                    <div class="plan-yearly">Yearly: ₦200,000</div>
                    <div class="plan-feat">✓ <b>Unlimited</b> students</div>
                    <div class="plan-feat">✓ 30-day free trial included</div>
                    <a class="plan-cta" href="{{ route('school.register') }}">Get Started</a>
                </div>
            </div>
        </div>
    </section>

    <section>
        <div class="container">
            <div class="final">
                <h2>Ready to manage your school smarter?</h2>
                <p>Start your 30-day free trial — no card required.</p>
                <div class="hero-actions">
                    <a class="btn btn-primary btn-lg" href="{{ route('school.register') }}"
                        style="background:#fff;color:var(--purple-700);">Start Free Trial</a>
                    <a class="btn btn-lg" href="{{ route('login') }}"
                        style="border:1.5px solid rgba(255,255,255,.4);color:#fff;">Log in</a>
                </div>
            </div>
        </div>
    </section>

    <footer>
        <div class="container">
            <p>© 2026 Skulbase. All Rights Reserved. | Designed &amp; Developed by Mubarak Lawal</p>
            <div class="flinks">
                <a href="{{ route('login') }}">Login</a>
                <a href="{{ route('school.register') }}">Register</a>
                <a href="#demo">Tutorial</a>
            </div>
        </div>
    </footer>

    <script>
        const nav = document.getElementById('mainNav');
        const toggle = document.getElementById('navToggle');
        const mobileMenu = document.getElementById('mobileMenu');

        window.addEventListener('scroll', () => {
            nav.classList.toggle('scrolled', window.scrollY > 8);
        }, {
            passive: true
        });

        toggle.addEventListener('click', () => {
            const isOpen = mobileMenu.classList.toggle('open');
            toggle.classList.toggle('open', isOpen);
            toggle.setAttribute('aria-expanded', isOpen);
        });

        mobileMenu.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', () => {
                mobileMenu.classList.remove('open');
                toggle.classList.remove('open');
                toggle.setAttribute('aria-expanded', 'false');
            });
        });
    </script>
</body>

</html>
