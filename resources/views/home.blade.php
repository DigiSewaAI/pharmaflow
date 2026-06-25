<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>PharmaFlow — Smart Pharmacy Management for Modern Pharmacies</title>
    <meta name="description" content="Manage inventory, billing, POS, expiry tracking, analytics, and staff operations from one secure modern dashboard. Trusted by 500+ pharmacies in Nepal." />
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>💊</text></svg>" />

    <script src="https://cdn.tailwindcss.com">
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700;14..32,800;14..32,900&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        html {
            scroll-behavior: smooth;
        }
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background: #ffffff;
            color: #0F172A;
            overflow-x: hidden;
            transition: background 0.3s ease, color 0.3s ease;
        }
        .dark body {
            background: #0F172A;
            color: #f1f5f9;
        }

        ::-webkit-scrollbar {
            width: 6px;
        }
        ::-webkit-scrollbar-track {
            background: transparent;
        }
        ::-webkit-scrollbar-thumb {
            background: #2563EB;
            border-radius: 12px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #1d4ed8;
        }

        .glass-nav {
            background: rgba(255, 255, 255, 0.72);
            backdrop-filter: blur(20px) saturate(180%);
            -webkit-backdrop-filter: blur(20px) saturate(180%);
            border-bottom: 1px solid rgba(255, 255, 255, 0.25);
        }
        .dark .glass-nav {
            background: rgba(15, 23, 42, 0.78);
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.6);
            backdrop-filter: blur(12px) saturate(160%);
            -webkit-backdrop-filter: blur(12px) saturate(160%);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        .dark .glass-card {
            background: rgba(30, 41, 59, 0.5);
            border: 1px solid rgba(255, 255, 255, 0.06);
        }

        .glass-card-premium {
            background: rgba(255, 255, 255, 0.5);
            backdrop-filter: blur(16px) saturate(180%);
            -webkit-backdrop-filter: blur(16px) saturate(180%);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .dark .glass-card-premium {
            background: rgba(30, 41, 59, 0.4);
            border: 1px solid rgba(255, 255, 255, 0.06);
        }

        .gradient-text {
            background: linear-gradient(135deg, #2563EB 0%, #0EA5E9 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .gradient-bg {
            background: linear-gradient(135deg, #2563EB 0%, #0EA5E9 100%);
        }

        .gradient-bg-subtle {
            background: radial-gradient(ellipse at 20% 50%, rgba(37, 99, 235, 0.08) 0%, transparent 70%),
                radial-gradient(ellipse at 80% 50%, rgba(14, 165, 233, 0.06) 0%, transparent 70%);
        }
        .dark .gradient-bg-subtle {
            background: radial-gradient(ellipse at 20% 50%, rgba(37, 99, 235, 0.12) 0%, transparent 70%),
                radial-gradient(ellipse at 80% 50%, rgba(14, 165, 233, 0.08) 0%, transparent 70%);
        }

        .glow {
            box-shadow: 0 0 60px -12px rgba(37, 99, 235, 0.20);
        }
        .dark .glow {
            box-shadow: 0 0 80px -20px rgba(37, 99, 235, 0.15);
        }

        .hover-elevate {
            transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1), box-shadow 0.4s ease;
        }
        .hover-elevate:hover {
            transform: translateY(-8px);
            box-shadow: 0 24px 48px -12px rgba(0, 0, 0, 0.12);
        }
        .dark .hover-elevate:hover {
            box-shadow: 0 24px 48px -12px rgba(0, 0, 0, 0.4);
        }

        .hover-elevate-sm {
            transition: transform 0.25s ease, box-shadow 0.3s ease;
        }
        .hover-elevate-sm:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 28px -8px rgba(0, 0, 0, 0.08);
        }

        #navbar {
            transition: background 0.3s ease, box-shadow 0.3s ease, border-color 0.3s ease;
            z-index: 999;
        }
        #navbar.scrolled {
            background: rgba(255, 255, 255, 0.82);
            backdrop-filter: blur(20px) saturate(180%);
            -webkit-backdrop-filter: blur(20px) saturate(180%);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.04);
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }
        .dark #navbar.scrolled {
            background: rgba(15, 23, 42, 0.88);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.2);
            border-bottom: 1px solid rgba(255, 255, 255, 0.04);
        }

        .dashboard-mockup {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid rgba(255, 255, 255, 0.4);
            border-radius: 24px;
            box-shadow: 0 40px 80px -20px rgba(0, 0, 0, 0.20), 0 0 0 1px rgba(255, 255, 255, 0.1) inset;
        }
        .dark .dashboard-mockup {
            background: rgba(30, 41, 59, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.06);
            box-shadow: 0 40px 80px -20px rgba(0, 0, 0, 0.5);
        }

        .float-card {
            animation: floatY 5s ease-in-out infinite;
        }
        .float-card:nth-child(2) {
            animation-delay: 1.2s;
        }
        .float-card:nth-child(3) {
            animation-delay: 2.4s;
        }
        .float-card:nth-child(4) {
            animation-delay: 0.6s;
        }
        @keyframes floatY {
            0%,
            100% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-8px);
            }
        }

        .logo-strip {
            display: flex;
            gap: 3.5rem;
            animation: scrollLogos 30s linear infinite;
            width: max-content;
        }
        .logo-strip-wrapper {
            overflow: hidden;
            mask-image: linear-gradient(to right, transparent, black 10%, black 90%, transparent);
            -webkit-mask-image: linear-gradient(to right, transparent, black 10%, black 90%, transparent);
        }
        @keyframes scrollLogos {
            0% {
                transform: translateX(0);
            }
            100% {
                transform: translateX(-50%);
            }
        }

        .tab-btn {
            transition: all 0.2s ease;
            cursor: pointer;
            border-radius: 9999px;
            padding: 0.5rem 1.25rem;
            font-weight: 500;
            font-size: 0.875rem;
            background: transparent;
            color: #64748b;
            border: 1px solid transparent;
        }
        .tab-btn.active {
            background: #2563EB;
            color: #fff;
            box-shadow: 0 4px 14px rgba(37, 99, 235, 0.25);
        }
        .dark .tab-btn.active {
            box-shadow: 0 4px 14px rgba(37, 99, 235, 0.35);
        }
        .tab-btn:not(.active):hover {
            background: rgba(37, 99, 235, 0.06);
            color: #0F172A;
        }
        .dark .tab-btn:not(.active):hover {
            background: rgba(255, 255, 255, 0.04);
            color: #f1f5f9;
        }
        .tab-panel {
            display: none;
            animation: fadeIn 0.4s ease;
        }
        .tab-panel.active {
            display: block;
        }
        @keyframes fadeIn {
            0% {
                opacity: 0;
                transform: translateY(8px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .toggle-track {
            width: 56px;
            height: 30px;
            background: #e2e8f0;
            border-radius: 999px;
            position: relative;
            cursor: pointer;
            transition: background 0.25s ease;
            flex-shrink: 0;
        }
        .dark .toggle-track {
            background: #334155;
        }
        .toggle-track.active {
            background: #2563EB;
        }
        .toggle-thumb {
            width: 24px;
            height: 24px;
            background: white;
            border-radius: 50%;
            position: absolute;
            top: 3px;
            left: 3px;
            transition: transform 0.25s cubic-bezier(0.34, 1.56, 0.64, 1), background 0.2s ease;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.12);
        }
        .toggle-track.active .toggle-thumb {
            transform: translateX(26px);
            background: white;
        }

        .faq-question {
            cursor: pointer;
            user-select: none;
            transition: color 0.2s ease;
        }
        .faq-question:hover {
            color: #2563EB;
        }
        .faq-answer {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.35s ease, opacity 0.3s ease, padding 0.3s ease;
            opacity: 0;
            padding-top: 0;
        }
        .faq-answer.open {
            max-height: 240px;
            opacity: 1;
            padding-top: 0.75rem;
        }
        .faq-icon {
            transition: transform 0.3s ease;
        }
        .faq-icon.open {
            transform: rotate(180deg);
        }

        #scroll-progress {
            position: fixed;
            top: 0;
            left: 0;
            height: 3px;
            background: linear-gradient(90deg, #2563EB, #0EA5E9);
            z-index: 9999;
            width: 0%;
            transition: width 0.1s linear;
            border-radius: 0 2px 2px 0;
        }

        #cursor-glow {
            position: fixed;
            width: 360px;
            height: 360px;
            border-radius: 50%;
            pointer-events: none;
            z-index: 0;
            background: radial-gradient(circle, rgba(37, 99, 235, 0.06) 0%, transparent 70%);
            transform: translate(-50%, -50%);
            transition: opacity 0.3s ease;
            opacity: 0;
            will-change: transform, opacity;
        }
        @media (max-width: 768px) {
            #cursor-glow {
                display: none;
            }
        }

        #fab {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            z-index: 900;
            width: 52px;
            height: 52px;
            border-radius: 50%;
            background: #2563EB;
            color: #fff;
            border: none;
            box-shadow: 0 8px 24px rgba(37, 99, 235, 0.35);
            cursor: pointer;
            transition: transform 0.25s ease, box-shadow 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
        }
        #fab:hover {
            transform: scale(1.08);
            box-shadow: 0 12px 32px rgba(37, 99, 235, 0.45);
        }
        #fab.hidden {
            transform: scale(0);
            opacity: 0;
            pointer-events: none;
        }

        #dark-toggle {
            transition: background 0.25s ease, transform 0.2s ease;
            cursor: pointer;
            border: none;
            background: rgba(255, 255, 255, 0.08);
            color: #0F172A;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            backdrop-filter: blur(4px);
        }
        .dark #dark-toggle {
            color: #f1f5f9;
            background: rgba(255, 255, 255, 0.06);
        }
        #dark-toggle:hover {
            transform: scale(1.06);
            background: rgba(37, 99, 235, 0.10);
        }
        .dark #dark-toggle:hover {
            background: rgba(37, 99, 235, 0.20);
        }

        #cmd-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.35);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            z-index: 9999;
            display: none;
            align-items: flex-start;
            justify-content: center;
            padding-top: 12vh;
            animation: fadeIn 0.2s ease;
        }
        #cmd-overlay.open {
            display: flex;
        }
        #cmd-modal {
            background: #fff;
            border-radius: 20px;
            max-width: 600px;
            width: 92%;
            box-shadow: 0 40px 80px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            animation: slideUp 0.25s ease;
        }
        .dark #cmd-modal {
            background: #1e293b;
            box-shadow: 0 40px 80px rgba(0, 0, 0, 0.5);
        }
        @keyframes slideUp {
            0% {
                opacity: 0;
                transform: translateY(16px) scale(0.96);
            }
            100% {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }
        #cmd-input {
            width: 100%;
            padding: 1rem 1.5rem;
            font-size: 1rem;
            border: none;
            outline: none;
            background: transparent;
            color: #0F172A;
            font-family: 'Inter', sans-serif;
        }
        .dark #cmd-input {
            color: #f1f5f9;
        }
        #cmd-input::placeholder {
            color: #94a3b8;
        }
        #cmd-results {
            max-height: 320px;
            overflow-y: auto;
            padding: 0.5rem 0.75rem 0.75rem;
        }
        .cmd-item {
            padding: 0.6rem 1rem;
            border-radius: 12px;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            color: #0F172A;
            transition: background 0.15s ease;
            cursor: pointer;
        }
        .dark .cmd-item {
            color: #f1f5f9;
        }
        .cmd-item:hover,
        .cmd-item.active {
            background: rgba(37, 99, 235, 0.08);
        }
        .dark .cmd-item:hover,
        .dark .cmd-item.active {
            background: rgba(37, 99, 235, 0.18);
        }
        .cmd-item i {
            width: 20px;
            color: #64748b;
        }
        .dark .cmd-item i {
            color: #94a3b8;
        }
        .cmd-kbd {
            margin-left: auto;
            font-size: 0.7rem;
            background: rgba(0, 0, 0, 0.06);
            padding: 0.1rem 0.5rem;
            border-radius: 6px;
            color: #94a3b8;
        }
        .dark .cmd-kbd {
            background: rgba(255, 255, 255, 0.06);
        }

        #toast-container {
            position: fixed;
            bottom: 6rem;
            right: 2rem;
            z-index: 9998;
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            pointer-events: none;
        }
        .toast {
            background: #0F172A;
            color: #fff;
            padding: 0.75rem 1.25rem;
            border-radius: 14px;
            box-shadow: 0 12px 32px rgba(0, 0, 0, 0.2);
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 0.9rem;
            pointer-events: auto;
            animation: toastIn 0.35s ease;
            transition: opacity 0.3s ease, transform 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.06);
        }
        .toast.out {
            opacity: 0;
            transform: translateX(20px);
        }
        @keyframes toastIn {
            0% {
                opacity: 0;
                transform: translateX(20px) scale(0.95);
            }
            100% {
                opacity: 1;
                transform: translateX(0) scale(1);
            }
        }

        @media (max-width: 640px) {
            .hero-badge {
                font-size: 0.75rem;
            }
            .hero-headline {
                font-size: 2.1rem !important;
                line-height: 1.2 !important;
            }
            .hero-subheadline {
                font-size: 1rem !important;
            }
            .dashboard-mockup {
                border-radius: 16px;
            }
            #cmd-modal {
                width: 96%;
                margin-top: 2rem;
            }
            #toast-container {
                right: 1rem;
                bottom: 5.5rem;
                left: 1rem;
            }
            .toast {
                font-size: 0.8rem;
                padding: 0.6rem 1rem;
            }
        }

        .section-padding {
            padding-top: 5rem;
            padding-bottom: 5rem;
        }
        @media (min-width: 768px) {
            .section-padding {
                padding-top: 7rem;
                padding-bottom: 7rem;
            }
        }

        .border-subtle {
            border-color: rgba(0, 0, 0, 0.04);
        }
        .dark .border-subtle {
            border-color: rgba(255, 255, 255, 0.04);
        }

        .text-balance {
            text-wrap: balance;
        }

        .reveal {
            opacity: 0;
            transform: translateY(28px);
            transition: opacity 0.7s ease, transform 0.7s ease;
        }
        .reveal.visible {
            opacity: 1;
            transform: translateY(0);
        }

        .float-subtle {
            animation: floatSub 6s ease-in-out infinite;
        }
        @keyframes floatSub {
            0%,
            100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-6px);
            }
        }

        .pulse-ring {
            animation: pulseRing 2.5s ease-in-out infinite;
        }
        @keyframes pulseRing {
            0%,
            100% {
                box-shadow: 0 0 0 0 rgba(37, 99, 235, 0.2);
            }
            50% {
                box-shadow: 0 0 0 12px rgba(37, 99, 235, 0);
            }
        }

        .dropdown-menu {
            opacity: 0;
            transform: translateY(-8px) scale(0.96);
            pointer-events: none;
            transition: opacity 0.2s ease, transform 0.2s ease;
        }
        .dropdown-group:hover .dropdown-menu {
            opacity: 1;
            transform: translateY(0) scale(1);
            pointer-events: auto;
        }

        .feature-icon-wrap {
            position: relative;
        }
        .feature-icon-wrap::after {
            content: '';
            position: absolute;
            inset: -4px;
            border-radius: 14px;
            background: linear-gradient(135deg, #2563EB20, #0EA5E920);
            z-index: -1;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        .feature-card:hover .feature-icon-wrap::after {
            opacity: 1;
        }

        .badge-security {
            background: rgba(16, 185, 129, 0.12);
            border: 1px solid rgba(16, 185, 129, 0.15);
        }

        .animated-bg-shape {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.15;
            animation: floatBg 12s ease-in-out infinite alternate;
        }
        @keyframes floatBg {
            0% {
                transform: translate(0, 0) scale(1);
            }
            100% {
                transform: translate(40px, -30px) scale(1.2);
            }
        }
        .dark .animated-bg-shape {
            opacity: 0.08;
        }

        .dropdown-enter {
            animation: dropEnter 0.2s ease forwards;
        }
        @keyframes dropEnter {
            0% {
                opacity: 0;
                transform: translateY(-6px) scale(0.96);
            }
            100% {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .metric-icon {
            transition: transform 0.3s ease;
        }
        .metric-card:hover .metric-icon {
            transform: scale(1.12) rotate(-4deg);
        }

        .footer-link {
            transition: color 0.2s ease, transform 0.2s ease;
        }
        .footer-link:hover {
            color: #fff;
            transform: translateX(4px);
        }

        .pricing-card-highlight {
            border: 2px solid #2563EB;
            box-shadow: 0 0 0 1px rgba(37, 99, 235, 0.08), 0 20px 48px -16px rgba(37, 99, 235, 0.15);
        }
        .dark .pricing-card-highlight {
            box-shadow: 0 0 0 1px rgba(37, 99, 235, 0.12), 0 20px 48px -16px rgba(37, 99, 235, 0.08);
        }
    </style>
</head>
<body>

    <div id="scroll-progress" role="progressbar" aria-label="Page scroll progress"></div>
    <div id="cursor-glow"></div>

    <!-- ─── NAVBAR ─── -->
    <header id="navbar" class="fixed top-0 left-0 w-full glass-nav z-[999] transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 lg:h-20">
                <a href="#" class="flex items-center gap-2.5 text-lg font-bold tracking-tight text-[#0F172A] dark:text-white shrink-0">
                    <span class="w-8 h-8 rounded-xl gradient-bg flex items-center justify-center text-white text-sm shadow-lg shadow-blue-500/20">💊</span>
                    <span>PharmaFlow</span>
                </a>

                <nav class="hidden lg:flex items-center gap-6 text-sm font-medium text-[#334155] dark:text-[#cbd5e1]">
                    <a href="#features" class="hover:text-[#2563EB] dark:hover:text-[#60A5FA] transition-colors">Features</a>
                    <a href="#pricing" class="hover:text-[#2563EB] dark:hover:text-[#60A5FA] transition-colors">Pricing</a>
                    <a href="#testimonials" class="hover:text-[#2563EB] dark:hover:text-[#60A5FA] transition-colors">Customers</a>
                    <div class="relative dropdown-group">
                        <button class="flex items-center gap-1 hover:text-[#2563EB] dark:hover:text-[#60A5FA] transition-colors">
                            Resources <i class="fas fa-chevron-down text-[10px] ml-0.5"></i>
                        </button>
                        <div class="dropdown-menu absolute top-full left-0 mt-2 w-48 glass-card-premium rounded-2xl shadow-xl border border-white/20 dark:border-white/5 p-2">
                            <a href="#" class="block px-4 py-2 rounded-xl hover:bg-black/5 dark:hover:bg-white/5 transition-colors text-sm">Documentation</a>
                            <a href="#" class="block px-4 py-2 rounded-xl hover:bg-black/5 dark:hover:bg-white/5 transition-colors text-sm">Blog</a>
                            <a href="#" class="block px-4 py-2 rounded-xl hover:bg-black/5 dark:hover:bg-white/5 transition-colors text-sm">Community</a>
                            <a href="#" class="block px-4 py-2 rounded-xl hover:bg-black/5 dark:hover:bg-white/5 transition-colors text-sm">Support</a>
                        </div>
                    </div>
                </nav>

                <div class="hidden lg:flex items-center gap-3">
                    <button id="dark-toggle" aria-label="Toggle dark mode" class="rounded-full w-9 h-9 flex items-center justify-center text-[#0F172A] dark:text-white hover:bg-black/5 dark:hover:bg-white/5 transition-all">
                        <i class="fas fa-moon dark:hidden"></i>
                        <i class="fas fa-sun hidden dark:inline"></i>
                    </button>
                    <a href="{{ route('login') }}" class="text-sm font-medium text-[#334155] dark:text-[#cbd5e1] hover:text-[#2563EB] dark:hover:text-[#60A5FA] transition-colors px-3 py-2">Log in</a>
                    <a href="{{ route('register') }}" class="bg-[#2563EB] hover:bg-[#1d4ed8] text-white text-sm font-semibold px-5 py-2.5 rounded-xl transition-all shadow-lg shadow-blue-500/20 hover:shadow-blue-500/30 hover:scale-[1.02] hover:-translate-y-0.5 duration-300">Start Free Trial</a>
                </div>

                <button id="mobile-toggle" class="lg:hidden w-9 h-9 flex items-center justify-center rounded-lg hover:bg-black/5 dark:hover:bg-white/5 transition-colors text-[#0F172A] dark:text-white" aria-label="Toggle menu">
                    <i class="fas fa-bars text-xl"></i>
                </button>
            </div>

            <div id="mobile-menu" class="lg:hidden overflow-hidden transition-all duration-300 max-h-0 opacity-0">
                <div class="pt-2 pb-5 space-y-1 text-sm font-medium text-[#334155] dark:text-[#cbd5e1] border-t border-gray-200/50 dark:border-white/5">
                    <a href="#features" class="block px-3 py-2.5 rounded-xl hover:bg-black/5 dark:hover:bg-white/5 transition-colors">Features</a>
                    <a href="#pricing" class="block px-3 py-2.5 rounded-xl hover:bg-black/5 dark:hover:bg-white/5 transition-colors">Pricing</a>
                    <a href="#testimonials" class="block px-3 py-2.5 rounded-xl hover:bg-black/5 dark:hover:bg-white/5 transition-colors">Customers</a>
                    <div class="px-3 py-1 text-xs text-[#94a3b8] uppercase tracking-wider">Resources</div>
                    <a href="#" class="block px-3 py-2 pl-6 rounded-xl hover:bg-black/5 dark:hover:bg-white/5 transition-colors text-sm">Documentation</a>
                    <a href="#" class="block px-3 py-2 pl-6 rounded-xl hover:bg-black/5 dark:hover:bg-white/5 transition-colors text-sm">Blog</a>
                    <a href="#" class="block px-3 py-2 pl-6 rounded-xl hover:bg-black/5 dark:hover:bg-white/5 transition-colors text-sm">Community</a>
                    <a href="#" class="block px-3 py-2 pl-6 rounded-xl hover:bg-black/5 dark:hover:bg-white/5 transition-colors text-sm">Support</a>
                    <div class="pt-3 flex flex-col gap-2">
                        <a href="{{ route('login') }}" class="text-center text-sm font-medium text-[#334155] dark:text-[#cbd5e1] hover:text-[#2563EB] dark:hover:text-[#60A5FA] transition-colors py-2">Log in</a>
                        <a href="{{ route('register') }}" class="text-center bg-[#2563EB] hover:bg-[#1d4ed8] text-white text-sm font-semibold px-5 py-2.5 rounded-xl transition-all shadow-lg shadow-blue-500/20">Start Free Trial</a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- ─── HERO ─── -->
    <section class="relative pt-28 pb-16 md:pt-36 md:pb-24 overflow-hidden gradient-bg-subtle">
        <div class="absolute inset-0 pointer-events-none">
            <div class="animated-bg-shape top-10 left-10 w-72 h-72 bg-[#2563EB]"></div>
            <div class="animated-bg-shape bottom-10 right-10 w-96 h-96 bg-[#0EA5E9]" style="animation-delay:4s;"></div>
        </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-center">
                <!-- Left -->
                <div>
                    <div class="inline-flex items-center gap-2 bg-white/80 dark:bg-white/5 backdrop-blur-sm border border-gray-200/50 dark:border-white/5 rounded-full px-4 py-1.5 text-xs font-medium text-[#2563EB] dark:text-[#60A5FA] shadow-sm mb-5 hero-badge">
                        <span class="relative flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#2563EB] opacity-40"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-[#2563EB]"></span>
                        </span>
                        ✨ Trusted by 500+ pharmacies
                    </div>

                    <h1 class="hero-headline text-4xl sm:text-5xl md:text-6xl font-extrabold tracking-tight text-[#0F172A] dark:text-white leading-[1.08]">
                        Run Your Entire Pharmacy<br />
                        <span class="gradient-text">From One Smart Platform.</span>
                    </h1>

                    <p class="hero-subheadline mt-5 text-lg sm:text-xl text-[#475569] dark:text-[#94a3b8] max-w-lg leading-relaxed">
                        Manage inventory, billing, POS, expiry tracking, analytics, and staff operations — all from a secure modern dashboard.
                    </p>

                    <div class="mt-7 flex flex-wrap gap-3">
                        <a href="{{ route('register') }}" class="bg-[#2563EB] hover:bg-[#1d4ed8] text-white font-semibold px-7 py-3.5 rounded-2xl transition-all shadow-lg shadow-blue-500/25 hover:shadow-blue-500/35 hover:scale-[1.02] flex items-center gap-2">
                            Start Free Trial <i class="fas fa-arrow-right text-sm"></i>
                        </a>
                        <a href="#" class="bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 hover:bg-gray-50 dark:hover:bg-white/10 text-[#0F172A] dark:text-white font-medium px-7 py-3.5 rounded-2xl transition-all flex items-center gap-2">
                            <i class="fas fa-play-circle text-[#2563EB]"></i> Book Demo
                        </a>
                    </div>

                    <div class="mt-8 flex items-center gap-6 flex-wrap">
                        <div class="flex items-center gap-1 text-yellow-400 text-sm">
                            <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                            <span class="text-[#475569] dark:text-[#94a3b8] text-sm ml-2">500+ pharmacies</span>
                        </div>
                        <span class="w-px h-6 bg-gray-300 dark:bg-white/10"></span>
                        <span class="text-[#475569] dark:text-[#94a3b8] text-sm font-medium">98% <span class="font-normal">satisfaction</span></span>
                    </div>
                </div>

                <!-- Right: Dashboard Mockup -->
                <div class="relative">
                    <div class="dashboard-mockup p-4 md:p-5 glow relative z-10">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-2">
                                <span class="w-3 h-3 rounded-full bg-red-400"></span>
                                <span class="w-3 h-3 rounded-full bg-yellow-400"></span>
                                <span class="w-3 h-3 rounded-full bg-green-400"></span>
                                <span class="text-xs font-mono text-[#64748b] dark:text-[#94a3b8] ml-2">dashboard.pharmaflow.app</span>
                            </div>
                            <span class="text-xs font-medium text-[#2563EB] bg-blue-50 dark:bg-blue-500/10 px-3 py-1 rounded-full">Live</span>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div class="bg-white/80 dark:bg-white/5 rounded-xl p-3 border border-white/20 dark:border-white/5 float-card">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-medium text-[#64748b] dark:text-[#94a3b8]">Revenue</span>
                                    <span class="text-[10px] text-green-500 font-semibold bg-green-50 dark:bg-green-500/10 px-2 py-0.5 rounded-full">+12%</span>
                                </div>
                                <div class="text-lg font-bold text-[#0F172A] dark:text-white mt-0.5">Rs 48,290</div>
                                <div class="mt-2 h-8 flex items-end gap-0.5">
                                    <span class="w-2 bg-[#2563EB] h-4 rounded-sm"></span>
                                    <span class="w-2 bg-[#2563EB] h-6 rounded-sm"></span>
                                    <span class="w-2 bg-[#2563EB] h-3 rounded-sm"></span>
                                    <span class="w-2 bg-[#2563EB] h-7 rounded-sm"></span>
                                    <span class="w-2 bg-[#2563EB] h-5 rounded-sm"></span>
                                    <span class="w-2 bg-[#2563EB] h-8 rounded-sm"></span>
                                    <span class="w-2 bg-[#2563EB] h-4 rounded-sm"></span>
                                </div>
                            </div>

                            <div class="bg-white/80 dark:bg-white/5 rounded-xl p-3 border border-white/20 dark:border-white/5 float-card" style="animation-delay:1.2s">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-medium text-[#64748b] dark:text-[#94a3b8]">Inventory</span>
                                    <span class="text-[10px] text-amber-500 font-semibold bg-amber-50 dark:bg-amber-500/10 px-2 py-0.5 rounded-full">82%</span>
                                </div>
                                <div class="text-lg font-bold text-[#0F172A] dark:text-white mt-0.5">1,284 items</div>
                                <div class="mt-2 w-full h-1.5 bg-gray-200 dark:bg-white/10 rounded-full overflow-hidden">
                                    <div class="h-full w-[82%] rounded-full gradient-bg"></div>
                                </div>
                                <div class="flex justify-between text-[10px] text-[#64748b] dark:text-[#94a3b8] mt-1">
                                    <span>In stock</span>
                                    <span>82%</span>
                                </div>
                            </div>

                            <div class="bg-white/80 dark:bg-white/5 rounded-xl p-3 border border-white/20 dark:border-white/5 float-card" style="animation-delay:2.4s">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-medium text-[#64748b] dark:text-[#94a3b8]">Alerts</span>
                                    <span class="text-[10px] text-red-500 font-semibold bg-red-50 dark:bg-red-500/10 px-2 py-0.5 rounded-full">3</span>
                                </div>
                                <div class="text-xs text-[#475569] dark:text-[#cbd5e1] mt-1 leading-relaxed">
                                    <span class="block">⚠️ 2 meds expiring soon</span>
                                    <span class="block">⚠️ 1 low stock</span>
                                </div>
                            </div>

                            <div class="bg-white/80 dark:bg-white/5 rounded-xl p-3 border border-white/20 dark:border-white/5 float-card" style="animation-delay:0.6s">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-medium text-[#64748b] dark:text-[#94a3b8]">Live Status</span>
                                    <span class="relative flex h-2 w-2">
                                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-60"></span>
                                        <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                                    </span>
                                </div>
                                <div class="text-xs text-[#475569] dark:text-[#cbd5e1] mt-1">
                                    <span class="block">🟢 POS Online</span>
                                    <span class="block">🟢 Server OK</span>
                                </div>
                            </div>
                        </div>

                        <div class="mt-3 flex items-center justify-between text-[10px] text-[#64748b] dark:text-[#94a3b8] border-t border-gray-200/50 dark:border-white/5 pt-2">
                            <span>📊 Last 7 days: +18.4%</span>
                            <span>🔄 Updated now</span>
                        </div>
                    </div>

                    <div class="absolute -bottom-12 -right-12 w-64 h-64 rounded-full bg-[#2563EB] opacity-[0.08] blur-3xl pointer-events-none"></div>
                    <div class="absolute -top-12 -left-12 w-48 h-48 rounded-full bg-[#0EA5E9] opacity-[0.06] blur-3xl pointer-events-none"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- ─── SOCIAL PROOF ─── -->
    <section class="py-10 border-y border-gray-200/50 dark:border-white/5 bg-white/40 dark:bg-white/5 backdrop-blur-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <p class="text-center text-sm font-medium text-[#64748b] dark:text-[#94a3b8] tracking-wide uppercase mb-5">Trusted by independent pharmacies and growing healthcare teams</p>
            <div class="logo-strip-wrapper">
                <div class="logo-strip">
                    <span class="text-lg font-semibold text-[#1e293b] dark:text-[#cbd5e1] tracking-tight opacity-60 grayscale hover:opacity-100 hover:grayscale-0 transition-all">MediCare</span>
                    <span class="text-lg font-semibold text-[#1e293b] dark:text-[#cbd5e1] tracking-tight opacity-60 grayscale hover:opacity-100 hover:grayscale-0 transition-all">LifeCare</span>
                    <span class="text-lg font-semibold text-[#1e293b] dark:text-[#cbd5e1] tracking-tight opacity-60 grayscale hover:opacity-100 hover:grayscale-0 transition-all">HealthPlus</span>
                    <span class="text-lg font-semibold text-[#1e293b] dark:text-[#cbd5e1] tracking-tight opacity-60 grayscale hover:opacity-100 hover:grayscale-0 transition-all">NeoPharma</span>
                    <span class="text-lg font-semibold text-[#1e293b] dark:text-[#cbd5e1] tracking-tight opacity-60 grayscale hover:opacity-100 hover:grayscale-0 transition-all">City Pharmacy</span>
                    <span class="text-lg font-semibold text-[#1e293b] dark:text-[#cbd5e1] tracking-tight opacity-60 grayscale hover:opacity-100 hover:grayscale-0 transition-all">PrimeMed</span>
                    <span class="text-lg font-semibold text-[#1e293b] dark:text-[#cbd5e1] tracking-tight opacity-60 grayscale hover:opacity-100 hover:grayscale-0 transition-all">MediCare</span>
                    <span class="text-lg font-semibold text-[#1e293b] dark:text-[#cbd5e1] tracking-tight opacity-60 grayscale hover:opacity-100 hover:grayscale-0 transition-all">LifeCare</span>
                    <span class="text-lg font-semibold text-[#1e293b] dark:text-[#cbd5e1] tracking-tight opacity-60 grayscale hover:opacity-100 hover:grayscale-0 transition-all">HealthPlus</span>
                    <span class="text-lg font-semibold text-[#1e293b] dark:text-[#cbd5e1] tracking-tight opacity-60 grayscale hover:opacity-100 hover:grayscale-0 transition-all">NeoPharma</span>
                    <span class="text-lg font-semibold text-[#1e293b] dark:text-[#cbd5e1] tracking-tight opacity-60 grayscale hover:opacity-100 hover:grayscale-0 transition-all">City Pharmacy</span>
                    <span class="text-lg font-semibold text-[#1e293b] dark:text-[#cbd5e1] tracking-tight opacity-60 grayscale hover:opacity-100 hover:grayscale-0 transition-all">PrimeMed</span>
                </div>
            </div>
        </div>
    </section>

    <!-- ─── VALUE PROPOSITION ─── -->
    <section id="features" class="section-padding relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-14 reveal">
                <span class="inline-block text-xs font-semibold text-[#2563EB] bg-blue-50 dark:bg-blue-500/10 px-4 py-1.5 rounded-full mb-4">Features</span>
                <h2 class="text-3xl md:text-4xl font-extrabold tracking-tight text-[#0F172A] dark:text-white">Everything your pharmacy needs.</h2>
                <p class="mt-3 text-[#475569] dark:text-[#94a3b8] text-lg">One platform to manage inventory, sales, staff, and compliance — all in real time.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="glass-card-premium rounded-2xl p-6 hover-elevate reveal transition-all duration-300 border border-white/20 dark:border-white/5 group relative overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-[#2563EB]/5 to-[#0EA5E9]/5 opacity-0 group-hover:opacity-100 transition-opacity duration-500 rounded-2xl"></div>
                    <div class="relative z-10">
                        <div class="feature-icon-wrap w-12 h-12 rounded-xl gradient-bg flex items-center justify-center text-white text-xl shadow-lg shadow-blue-500/20 mb-4">
                            <i class="fas fa-cubes"></i>
                        </div>
                        <h3 class="text-lg font-bold text-[#0F172A] dark:text-white">Inventory Management</h3>
                        <p class="text-sm text-[#475569] dark:text-[#94a3b8] mt-1 leading-relaxed">Track stock levels, set reorder points, and manage suppliers in one place.</p>
                        <div class="mt-3 text-xs text-[#2563EB] font-medium opacity-0 group-hover:opacity-100 transition-opacity">Learn more →</div>
                    </div>
                </div>

                <div class="glass-card-premium rounded-2xl p-6 hover-elevate reveal transition-all duration-300 border border-white/20 dark:border-white/5 group relative overflow-hidden" style="transition-delay:0.05s">
                    <div class="absolute inset-0 bg-gradient-to-br from-[#2563EB]/5 to-[#0EA5E9]/5 opacity-0 group-hover:opacity-100 transition-opacity duration-500 rounded-2xl"></div>
                    <div class="relative z-10">
                        <div class="feature-icon-wrap w-12 h-12 rounded-xl gradient-bg flex items-center justify-center text-white text-xl shadow-lg shadow-blue-500/20 mb-4">
                            <i class="fas fa-cash-register"></i>
                        </div>
                        <h3 class="text-lg font-bold text-[#0F172A] dark:text-white">POS System</h3>
                        <p class="text-sm text-[#475569] dark:text-[#94a3b8] mt-1 leading-relaxed">Fast, intuitive point-of-sale with barcode scanning and receipt printing.</p>
                        <div class="mt-3 text-xs text-[#2563EB] font-medium opacity-0 group-hover:opacity-100 transition-opacity">Learn more →</div>
                    </div>
                </div>

                <div class="glass-card-premium rounded-2xl p-6 hover-elevate reveal transition-all duration-300 border border-white/20 dark:border-white/5 group relative overflow-hidden" style="transition-delay:0.1s">
                    <div class="absolute inset-0 bg-gradient-to-br from-[#2563EB]/5 to-[#0EA5E9]/5 opacity-0 group-hover:opacity-100 transition-opacity duration-500 rounded-2xl"></div>
                    <div class="relative z-10">
                        <div class="feature-icon-wrap w-12 h-12 rounded-xl gradient-bg flex items-center justify-center text-white text-xl shadow-lg shadow-blue-500/20 mb-4">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <h3 class="text-lg font-bold text-[#0F172A] dark:text-white">Analytics &amp; Reports</h3>
                        <p class="text-sm text-[#475569] dark:text-[#94a3b8] mt-1 leading-relaxed">Understand your business with real-time sales, profit, and trend reports.</p>
                        <div class="mt-3 text-xs text-[#2563EB] font-medium opacity-0 group-hover:opacity-100 transition-opacity">Learn more →</div>
                    </div>
                </div>

                <div class="glass-card-premium rounded-2xl p-6 hover-elevate reveal transition-all duration-300 border border-white/20 dark:border-white/5 group relative overflow-hidden" style="transition-delay:0.15s">
                    <div class="absolute inset-0 bg-gradient-to-br from-[#2563EB]/5 to-[#0EA5E9]/5 opacity-0 group-hover:opacity-100 transition-opacity duration-500 rounded-2xl"></div>
                    <div class="relative z-10">
                        <div class="feature-icon-wrap w-12 h-12 rounded-xl gradient-bg flex items-center justify-center text-white text-xl shadow-lg shadow-blue-500/20 mb-4">
                            <i class="fas fa-clock"></i>
                        </div>
                        <h3 class="text-lg font-bold text-[#0F172A] dark:text-white">Expiry Tracking</h3>
                        <p class="text-sm text-[#475569] dark:text-[#94a3b8] mt-1 leading-relaxed">Never waste stock again. Get automatic alerts for medicines nearing expiry.</p>
                        <div class="mt-3 text-xs text-[#2563EB] font-medium opacity-0 group-hover:opacity-100 transition-opacity">Learn more →</div>
                    </div>
                </div>

                <div class="glass-card-premium rounded-2xl p-6 hover-elevate reveal transition-all duration-300 border border-white/20 dark:border-white/5 group relative overflow-hidden" style="transition-delay:0.2s">
                    <div class="absolute inset-0 bg-gradient-to-br from-[#2563EB]/5 to-[#0EA5E9]/5 opacity-0 group-hover:opacity-100 transition-opacity duration-500 rounded-2xl"></div>
                    <div class="relative z-10">
                        <div class="feature-icon-wrap w-12 h-12 rounded-xl gradient-bg flex items-center justify-center text-white text-xl shadow-lg shadow-blue-500/20 mb-4">
                            <i class="fas fa-truck"></i>
                        </div>
                        <h3 class="text-lg font-bold text-[#0F172A] dark:text-white">Supplier Management</h3>
                        <p class="text-sm text-[#475569] dark:text-[#94a3b8] mt-1 leading-relaxed">Manage orders, track deliveries, and maintain supplier relationships.</p>
                        <div class="mt-3 text-xs text-[#2563EB] font-medium opacity-0 group-hover:opacity-100 transition-opacity">Learn more →</div>
                    </div>
                </div>

                <div class="glass-card-premium rounded-2xl p-6 hover-elevate reveal transition-all duration-300 border border-white/20 dark:border-white/5 group relative overflow-hidden" style="transition-delay:0.25s">
                    <div class="absolute inset-0 bg-gradient-to-br from-[#2563EB]/5 to-[#0EA5E9]/5 opacity-0 group-hover:opacity-100 transition-opacity duration-500 rounded-2xl"></div>
                    <div class="relative z-10">
                        <div class="feature-icon-wrap w-12 h-12 rounded-xl gradient-bg flex items-center justify-center text-white text-xl shadow-lg shadow-blue-500/20 mb-4">
                            <i class="fas fa-users"></i>
                        </div>
                        <h3 class="text-lg font-bold text-[#0F172A] dark:text-white">Multi-user Control</h3>
                        <p class="text-sm text-[#475569] dark:text-[#94a3b8] mt-1 leading-relaxed">Role-based access for staff, pharmacists, and managers with full audit trails.</p>
                        <div class="mt-3 text-xs text-[#2563EB] font-medium opacity-0 group-hover:opacity-100 transition-opacity">Learn more →</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ─── PRODUCT SHOWCASE ─── -->
    <section class="section-padding bg-white/40 dark:bg-white/5 border-y border-gray-200/50 dark:border-white/5">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-12 reveal">
                <span class="inline-block text-xs font-semibold text-[#2563EB] bg-blue-50 dark:bg-blue-500/10 px-4 py-1.5 rounded-full mb-4">Product</span>
                <h2 class="text-3xl md:text-4xl font-extrabold tracking-tight text-[#0F172A] dark:text-white">See it in action.</h2>
                <p class="mt-2 text-[#475569] dark:text-[#94a3b8]">Explore the modules that power your pharmacy.</p>
            </div>

            <div class="flex flex-wrap justify-center gap-2 mb-8 reveal">
                <button class="tab-btn active" data-tab="dashboard"><i class="fas fa-th-large mr-2"></i>Dashboard</button>
                <button class="tab-btn" data-tab="inventory"><i class="fas fa-cubes mr-2"></i>Inventory</button>
                <button class="tab-btn" data-tab="pos"><i class="fas fa-cash-register mr-2"></i>POS</button>
                <button class="tab-btn" data-tab="analytics"><i class="fas fa-chart-pie mr-2"></i>Analytics</button>
                <button class="tab-btn" data-tab="expiry"><i class="fas fa-clock mr-2"></i>Expiry Center</button>
            </div>

            <div class="relative">
                <div class="tab-panel active" id="panel-dashboard">
                    <div class="glass-card-premium rounded-2xl p-6 md:p-8 glow overflow-hidden border border-white/20 dark:border-white/5">
                        <div class="flex items-center gap-3 mb-5">
                            <span class="w-3 h-3 rounded-full bg-red-400"></span>
                            <span class="w-3 h-3 rounded-full bg-yellow-400"></span>
                            <span class="w-3 h-3 rounded-full bg-green-400"></span>
                            <span class="text-xs font-mono text-[#64748b] dark:text-[#94a3b8] ml-2">Dashboard</span>
                        </div>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-5">
                            <div class="bg-white/80 dark:bg-white/5 rounded-xl p-4 border border-white/20 dark:border-white/5">
                                <div class="text-xs text-[#64748b] dark:text-[#94a3b8]">Revenue</div>
                                <div class="text-xl font-bold text-[#0F172A] dark:text-white">Rs 48.2k</div>
                                <div class="text-[10px] text-green-500">↑ 12.4%</div>
                            </div>
                            <div class="bg-white/80 dark:bg-white/5 rounded-xl p-4 border border-white/20 dark:border-white/5">
                                <div class="text-xs text-[#64748b] dark:text-[#94a3b8]">Orders</div>
                                <div class="text-xl font-bold text-[#0F172A] dark:text-white">342</div>
                                <div class="text-[10px] text-green-500">↑ 8.1%</div>
                            </div>
                            <div class="bg-white/80 dark:bg-white/5 rounded-xl p-4 border border-white/20 dark:border-white/5">
                                <div class="text-xs text-[#64748b] dark:text-[#94a3b8]">Stock Value</div>
                                <div class="text-xl font-bold text-[#0F172A] dark:text-white">Rs 124k</div>
                                <div class="text-[10px] text-amber-500">↔ stable</div>
                            </div>
                            <div class="bg-white/80 dark:bg-white/5 rounded-xl p-4 border border-white/20 dark:border-white/5">
                                <div class="text-xs text-[#64748b] dark:text-[#94a3b8]">Staff</div>
                                <div class="text-xl font-bold text-[#0F172A] dark:text-white">14</div>
                                <div class="text-[10px] text-[#64748b]">active</div>
                            </div>
                        </div>
                        <div class="h-32 rounded-xl bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-500/10 dark:to-indigo-500/10 border border-white/20 dark:border-white/5 flex items-center justify-center text-[#64748b] dark:text-[#94a3b8] text-sm">
                            <span>📊 Sales trend chart (last 30 days)</span>
                        </div>
                        <div class="mt-3 flex gap-3 text-xs text-[#64748b] dark:text-[#94a3b8]">
                            <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-[#2563EB]"></span> Revenue</span>
                            <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-[#0EA5E9]"></span> Orders</span>
                        </div>
                    </div>
                </div>

                <div class="tab-panel" id="panel-inventory">
                    <div class="glass-card-premium rounded-2xl p-6 md:p-8 glow overflow-hidden border border-white/20 dark:border-white/5">
                        <div class="flex items-center gap-3 mb-5">
                            <span class="w-3 h-3 rounded-full bg-red-400"></span>
                            <span class="w-3 h-3 rounded-full bg-yellow-400"></span>
                            <span class="w-3 h-3 rounded-full bg-green-400"></span>
                            <span class="text-xs font-mono text-[#64748b] dark:text-[#94a3b8] ml-2">Inventory</span>
                        </div>
                        <div class="space-y-3">
                            <div class="flex items-center justify-between bg-white/60 dark:bg-white/5 px-4 py-3 rounded-xl border border-white/20 dark:border-white/5">
                                <div><span class="font-medium text-[#0F172A] dark:text-white">Paracetamol 500mg</span> <span class="text-xs text-[#64748b] ml-3">#1024</span></div>
                                <div class="flex items-center gap-4 text-sm"><span class="text-green-600 dark:text-green-400">342 in stock</span> <span class="text-[#64748b]">Exp: 12/2026</span></div>
                            </div>
                            <div class="flex items-center justify-between bg-white/60 dark:bg-white/5 px-4 py-3 rounded-xl border border-white/20 dark:border-white/5">
                                <div><span class="font-medium text-[#0F172A] dark:text-white">Amoxicillin 250mg</span> <span class="text-xs text-[#64748b] ml-3">#2048</span></div>
                                <div class="flex items-center gap-4 text-sm"><span class="text-amber-600 dark:text-amber-400">87 in stock</span> <span class="text-[#64748b]">Exp: 03/2026</span></div>
                            </div>
                            <div class="flex items-center justify-between bg-white/60 dark:bg-white/5 px-4 py-3 rounded-xl border border-white/20 dark:border-white/5">
                                <div><span class="font-medium text-[#0F172A] dark:text-white">Ibuprofen 400mg</span> <span class="text-xs text-[#64748b] ml-3">#3072</span></div>
                                <div class="flex items-center gap-4 text-sm"><span class="text-red-600 dark:text-red-400">23 in stock</span> <span class="text-[#64748b]">Exp: 09/2025</span></div>
                            </div>
                            <div class="flex items-center justify-between bg-white/60 dark:bg-white/5 px-4 py-3 rounded-xl border border-white/20 dark:border-white/5">
                                <div><span class="font-medium text-[#0F172A] dark:text-white">Metformin 500mg</span> <span class="text-xs text-[#64748b] ml-3">#4096</span></div>
                                <div class="flex items-center gap-4 text-sm"><span class="text-green-600 dark:text-green-400">156 in stock</span> <span class="text-[#64748b]">Exp: 07/2027</span></div>
                            </div>
                        </div>
                        <div class="mt-4 text-right text-xs text-[#64748b]">Showing 4 of 1,284 items</div>
                    </div>
                </div>

                <div class="tab-panel" id="panel-pos">
                    <div class="glass-card-premium rounded-2xl p-6 md:p-8 glow overflow-hidden border border-white/20 dark:border-white/5">
                        <div class="flex items-center gap-3 mb-5">
                            <span class="w-3 h-3 rounded-full bg-red-400"></span>
                            <span class="w-3 h-3 rounded-full bg-yellow-400"></span>
                            <span class="w-3 h-3 rounded-full bg-green-400"></span>
                            <span class="text-xs font-mono text-[#64748b] dark:text-[#94a3b8] ml-2">POS</span>
                        </div>
                        <div class="grid grid-cols-3 gap-4">
                            <div class="col-span-2 bg-white/80 dark:bg-white/5 rounded-xl p-4 border border-white/20 dark:border-white/5">
                                <div class="text-xs text-[#64748b] dark:text-[#94a3b8] mb-2">Current Sale</div>
                                <div class="flex justify-between items-center border-b border-gray-200/50 dark:border-white/5 py-2">
                                    <span>Paracetamol 500mg × 2</span> <span>Rs 1,250</span>
                                </div>
                                <div class="flex justify-between items-center border-b border-gray-200/50 dark:border-white/5 py-2">
                                    <span>Amoxicillin 250mg × 1</span> <span>Rs 875</span>
                                </div>
                                <div class="flex justify-between items-center border-b border-gray-200/50 dark:border-white/5 py-2">
                                    <span>Metformin 500mg × 3</span> <span>Rs 1,440</span>
                                </div>
                                <div class="flex justify-between items-center py-2 font-bold text-[#0F172A] dark:text-white">
                                    <span>Total</span> <span>Rs 3,565</span>
                                </div>
                            </div>
                            <div class="bg-white/80 dark:bg-white/5 rounded-xl p-4 border border-white/20 dark:border-white/5 flex flex-col items-center justify-center text-center">
                                <i class="fas fa-barcode text-3xl text-[#2563EB] mb-2"></i>
                                <span class="text-xs text-[#64748b]">Scan barcode</span>
                                <span class="text-[10px] text-[#64748b]">or search product</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-panel" id="panel-analytics">
                    <div class="glass-card-premium rounded-2xl p-6 md:p-8 glow overflow-hidden border border-white/20 dark:border-white/5">
                        <div class="flex items-center gap-3 mb-5">
                            <span class="w-3 h-3 rounded-full bg-red-400"></span>
                            <span class="w-3 h-3 rounded-full bg-yellow-400"></span>
                            <span class="w-3 h-3 rounded-full bg-green-400"></span>
                            <span class="text-xs font-mono text-[#64748b] dark:text-[#94a3b8] ml-2">Analytics</span>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-white/80 dark:bg-white/5 rounded-xl p-4 border border-white/20 dark:border-white/5">
                                <div class="text-xs text-[#64748b]">Revenue by Month</div>
                                <div class="h-16 flex items-end gap-1 mt-2">
                                    <span class="w-4 bg-[#2563EB] h-8 rounded-sm"></span>
                                    <span class="w-4 bg-[#2563EB] h-12 rounded-sm"></span>
                                    <span class="w-4 bg-[#2563EB] h-6 rounded-sm"></span>
                                    <span class="w-4 bg-[#2563EB] h-14 rounded-sm"></span>
                                    <span class="w-4 bg-[#2563EB] h-10 rounded-sm"></span>
                                    <span class="w-4 bg-[#2563EB] h-16 rounded-sm"></span>
                                </div>
                            </div>
                            <div class="bg-white/80 dark:bg-white/5 rounded-xl p-4 border border-white/20 dark:border-white/5">
                                <div class="text-xs text-[#64748b]">Top Selling</div>
                                <div class="mt-2 space-y-1 text-sm">
                                    <div class="flex justify-between"><span>Paracetamol</span> <span class="font-medium">342</span></div>
                                    <div class="flex justify-between"><span>Amoxicillin</span> <span class="font-medium">287</span></div>
                                    <div class="flex justify-between"><span>Ibuprofen</span> <span class="font-medium">214</span></div>
                                    <div class="flex justify-between"><span>Metformin</span> <span class="font-medium">198</span></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-panel" id="panel-expiry">
                    <div class="glass-card-premium rounded-2xl p-6 md:p-8 glow overflow-hidden border border-white/20 dark:border-white/5">
                        <div class="flex items-center gap-3 mb-5">
                            <span class="w-3 h-3 rounded-full bg-red-400"></span>
                            <span class="w-3 h-3 rounded-full bg-yellow-400"></span>
                            <span class="w-3 h-3 rounded-full bg-green-400"></span>
                            <span class="text-xs font-mono text-[#64748b] dark:text-[#94a3b8] ml-2">Expiry Center</span>
                        </div>
                        <div class="space-y-3">
                            <div class="flex items-center justify-between bg-white/60 dark:bg-white/5 px-4 py-3 rounded-xl border border-red-200/50 dark:border-red-500/20">
                                <div><span class="font-medium text-[#0F172A] dark:text-white">Ibuprofen 400mg</span> <span class="text-xs text-red-500 ml-3">⚠️ Expires in 7 days</span></div>
                                <div class="text-sm text-red-600 dark:text-red-400 font-medium">23 in stock</div>
                            </div>
                            <div class="flex items-center justify-between bg-white/60 dark:bg-white/5 px-4 py-3 rounded-xl border border-amber-200/50 dark:border-amber-500/20">
                                <div><span class="font-medium text-[#0F172A] dark:text-white">Amoxicillin 250mg</span> <span class="text-xs text-amber-500 ml-3">⚠️ Expires in 18 days</span></div>
                                <div class="text-sm text-amber-600 dark:text-amber-400 font-medium">87 in stock</div>
                            </div>
                            <div class="flex items-center justify-between bg-white/60 dark:bg-white/5 px-4 py-3 rounded-xl border border-amber-200/50 dark:border-amber-500/20">
                                <div><span class="font-medium text-[#0F172A] dark:text-white">Ciprofloxacin 500mg</span> <span class="text-xs text-amber-500 ml-3">⚠️ Expires in 22 days</span></div>
                                <div class="text-sm text-amber-600 dark:text-amber-400 font-medium">45 in stock</div>
                            </div>
                        </div>
                        <div class="mt-4 text-right text-xs text-[#64748b]">3 items nearing expiry</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ─── SMART FEATURES ─── -->
    <section class="section-padding relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-14 reveal">
                <span class="inline-block text-xs font-semibold text-[#2563EB] bg-blue-50 dark:bg-blue-500/10 px-4 py-1.5 rounded-full mb-4">Smart Features</span>
                <h2 class="text-3xl md:text-4xl font-extrabold tracking-tight text-[#0F172A] dark:text-white">Built for modern pharmacy.</h2>
            </div>

            <div class="space-y-12">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center reveal">
                    <div class="order-2 md:order-1">
                        <div class="glass-card-premium rounded-2xl p-6 md:p-8 border border-white/20 dark:border-white/5">
                            <div class="w-12 h-12 rounded-xl gradient-bg flex items-center justify-center text-white text-xl shadow-lg shadow-blue-500/20 mb-4">
                                <i class="fas fa-brain"></i>
                            </div>
                            <h3 class="text-xl font-bold text-[#0F172A] dark:text-white">AI Inventory Prediction</h3>
                            <p class="text-[#475569] dark:text-[#94a3b8] mt-2 leading-relaxed">Our AI analyzes sales patterns and seasonal trends to predict future inventory needs, helping improve inventory planning and reduce waste.</p>
                            <ul class="mt-3 space-y-1 text-sm text-[#475569] dark:text-[#94a3b8]">
                                <li>✓ Smart reorder recommendations</li>
                                <li>✓ Demand forecasting</li>
                                <li>✓ Seasonal trend analysis</li>
                            </ul>
                        </div>
                    </div>
                    <div class="order-1 md:order-2 flex justify-center">
                        <div class="w-full max-w-sm h-52 rounded-2xl gradient-bg-subtle border border-white/20 dark:border-white/5 flex items-center justify-center text-[#2563EB] text-6xl">
                            <i class="fas fa-robot"></i>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center reveal">
                    <div class="flex justify-center">
                        <div class="w-full max-w-sm h-52 rounded-2xl gradient-bg-subtle border border-white/20 dark:border-white/5 flex items-center justify-center text-[#0EA5E9] text-6xl">
                            <i class="fas fa-bell"></i>
                        </div>
                    </div>
                    <div>
                        <div class="glass-card-premium rounded-2xl p-6 md:p-8 border border-white/20 dark:border-white/5">
                            <div class="w-12 h-12 rounded-xl gradient-bg flex items-center justify-center text-white text-xl shadow-lg shadow-blue-500/20 mb-4">
                                <i class="fas fa-clock"></i>
                            </div>
                            <h3 class="text-xl font-bold text-[#0F172A] dark:text-white">Medicine Expiry Alerts</h3>
                            <p class="text-[#475569] dark:text-[#94a3b8] mt-2 leading-relaxed">Automated notifications for medicines approaching expiry, with suggested actions to minimize losses and ensure patient safety.</p>
                            <ul class="mt-3 space-y-1 text-sm text-[#475569] dark:text-[#94a3b8]">
                                <li>✓ 30/15/7 day warnings</li>
                                <li>✓ Batch-level tracking</li>
                                <li>✓ Disposal reporting</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center reveal">
                    <div class="order-2 md:order-1">
                        <div class="glass-card-premium rounded-2xl p-6 md:p-8 border border-white/20 dark:border-white/5">
                            <div class="w-12 h-12 rounded-xl gradient-bg flex items-center justify-center text-white text-xl shadow-lg shadow-blue-500/20 mb-4">
                                <i class="fas fa-chart-simple"></i>
                            </div>
                            <h3 class="text-xl font-bold text-[#0F172A] dark:text-white">Revenue Analytics</h3>
                            <p class="text-[#475569] dark:text-[#94a3b8] mt-2 leading-relaxed">Deep insights into your pharmacy's financial health — track revenue, profit margins, best-selling products, and staff performance.</p>
                            <ul class="mt-3 space-y-1 text-sm text-[#475569] dark:text-[#94a3b8]">
                                <li>✓ Real-time dashboards</li>
                                <li>✓ Profit &amp; loss reports</li>
                                <li>✓ Custom date ranges</li>
                            </ul>
                        </div>
                    </div>
                    <div class="order-1 md:order-2 flex justify-center">
                        <div class="w-full max-w-sm h-52 rounded-2xl gradient-bg-subtle border border-white/20 dark:border-white/5 flex items-center justify-center text-[#10B981] text-6xl">
                            <i class="fas fa-coins"></i>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center reveal">
                    <div class="flex justify-center">
                        <div class="w-full max-w-sm h-52 rounded-2xl gradient-bg-subtle border border-white/20 dark:border-white/5 flex items-center justify-center text-[#8B5CF6] text-6xl">
                            <i class="fas fa-barcode"></i>
                        </div>
                    </div>
                    <div>
                        <div class="glass-card-premium rounded-2xl p-6 md:p-8 border border-white/20 dark:border-white/5">
                            <div class="w-12 h-12 rounded-xl gradient-bg flex items-center justify-center text-white text-xl shadow-lg shadow-blue-500/20 mb-4">
                                <i class="fas fa-qrcode"></i>
                            </div>
                            <h3 class="text-xl font-bold text-[#0F172A] dark:text-white">Barcode Support</h3>
                            <p class="text-[#475569] dark:text-[#94a3b8] mt-2 leading-relaxed">Scan barcodes to add items, process sales, and track inventory. Works with standard pharmacy barcode formats.</p>
                            <ul class="mt-3 space-y-1 text-sm text-[#475569] dark:text-[#94a3b8]">
                                <li>✓ Universal barcode scanning</li>
                                <li>✓ Bulk scanning support</li>
                                <li>✓ Automatic product lookup</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center reveal">
                    <div class="order-2 md:order-1">
                        <div class="glass-card-premium rounded-2xl p-6 md:p-8 border border-white/20 dark:border-white/5">
                            <div class="w-12 h-12 rounded-xl gradient-bg flex items-center justify-center text-white text-xl shadow-lg shadow-blue-500/20 mb-4">
                                <i class="fas fa-file-alt"></i>
                            </div>
                            <h3 class="text-xl font-bold text-[#0F172A] dark:text-white">Real-Time Reports</h3>
                            <p class="text-[#475569] dark:text-[#94a3b8] mt-2 leading-relaxed">Generate comprehensive reports on sales, inventory, and staff performance — all available in real-time with export options.</p>
                            <ul class="mt-3 space-y-1 text-sm text-[#475569] dark:text-[#94a3b8]">
                                <li>✓ Custom report builder</li>
                                <li>✓ Export to PDF/Excel</li>
                                <li>✓ Scheduled reports</li>
                            </ul>
                        </div>
                    </div>
                    <div class="order-1 md:order-2 flex justify-center">
                        <div class="w-full max-w-sm h-52 rounded-2xl gradient-bg-subtle border border-white/20 dark:border-white/5 flex items-center justify-center text-[#F59E0B] text-6xl">
                            <i class="fas fa-file-pdf"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ─── WHY SWITCH ─── -->
    <section class="section-padding bg-white/40 dark:bg-white/5 border-y border-gray-200/50 dark:border-white/5">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-14 reveal">
                <span class="inline-block text-xs font-semibold text-[#2563EB] bg-blue-50 dark:bg-blue-500/10 px-4 py-1.5 rounded-full mb-4">Why Switch</span>
                <h2 class="text-3xl md:text-4xl font-extrabold tracking-tight text-[#0F172A] dark:text-white">Smarter operations, better outcomes.</h2>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <div class="glass-card-premium rounded-2xl p-6 text-center hover-elevate-sm reveal border border-white/20 dark:border-white/5 metric-card">
                    <div class="metric-icon text-3xl mb-3">📉</div>
                    <div class="text-2xl font-extrabold text-[#0F172A] dark:text-white"><span class="counter" data-target="42">0</span>%</div>
                    <div class="text-sm text-[#475569] dark:text-[#94a3b8]">Less manual work</div>
                </div>
                <div class="glass-card-premium rounded-2xl p-6 text-center hover-elevate-sm reveal border border-white/20 dark:border-white/5 metric-card" style="transition-delay:0.05s">
                    <div class="metric-icon text-3xl mb-3">⚡</div>
                    <div class="text-2xl font-extrabold text-[#0F172A] dark:text-white"><span class="counter" data-target="35">0</span>%</div>
                    <div class="text-sm text-[#475569] dark:text-[#94a3b8]">Faster billing</div>
                </div>
                <div class="glass-card-premium rounded-2xl p-6 text-center hover-elevate-sm reveal border border-white/20 dark:border-white/5 metric-card" style="transition-delay:0.1s">
                    <div class="metric-icon text-3xl mb-3">📊</div>
                    <div class="text-2xl font-extrabold text-[#0F172A] dark:text-white"><span class="counter" data-target="28">0</span>%</div>
                    <div class="text-sm text-[#475569] dark:text-[#94a3b8]">Better stock visibility</div>
                </div>
                <div class="glass-card-premium rounded-2xl p-6 text-center hover-elevate-sm reveal border border-white/20 dark:border-white/5 metric-card" style="transition-delay:0.15s">
                    <div class="metric-icon text-3xl mb-3">💊</div>
                    <div class="text-2xl font-extrabold text-[#0F172A] dark:text-white"><span class="counter" data-target="23">0</span>%</div>
                    <div class="text-sm text-[#475569] dark:text-[#94a3b8]">Reduced inventory loss</div>
                </div>
            </div>
        </div>
    </section>

    <!-- ─── TESTIMONIALS ─── -->
    <section id="testimonials" class="section-padding relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-14 reveal">
                <span class="inline-block text-xs font-semibold text-[#2563EB] bg-blue-50 dark:bg-blue-500/10 px-4 py-1.5 rounded-full mb-4">Testimonials</span>
                <h2 class="text-3xl md:text-4xl font-extrabold tracking-tight text-[#0F172A] dark:text-white">Loved by pharmacy teams.</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="glass-card-premium rounded-2xl p-6 hover-elevate-sm reveal transition-all duration-300 border border-white/20 dark:border-white/5">
                    <div class="flex text-yellow-400 text-sm mb-3">
                        <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                    </div>
                    <p class="text-[#0F172A] dark:text-white text-sm leading-relaxed">"PharmaFlow has completely transformed how we manage inventory. We've seen a significant reduction in waste and our staff loves the simplicity."</p>
                    <div class="flex items-center gap-3 mt-4 pt-4 border-t border-gray-200/50 dark:border-white/5">
                        <div class="w-10 h-10 rounded-full gradient-bg flex items-center justify-center text-white font-bold text-sm">SS</div>
                        <div><div class="font-semibold text-[#0F172A] dark:text-white text-sm">S. Sharma</div><div class="text-xs text-[#64748b] dark:text-[#94a3b8]">Owner • Community Pharmacy</div></div>
                    </div>
                </div>

                <div class="glass-card-premium rounded-2xl p-6 hover-elevate-sm reveal transition-all duration-300 border border-white/20 dark:border-white/5" style="transition-delay:0.1s">
                    <div class="flex text-yellow-400 text-sm mb-3">
                        <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                    </div>
                    <p class="text-[#0F172A] dark:text-white text-sm leading-relaxed">"The analytics module gives us clarity on our revenue streams. We've been able to make data-driven decisions that directly impact our bottom line."</p>
                    <div class="flex items-center gap-3 mt-4 pt-4 border-t border-gray-200/50 dark:border-white/5">
                        <div class="w-10 h-10 rounded-full gradient-bg flex items-center justify-center text-white font-bold text-sm">RA</div>
                        <div><div class="font-semibold text-[#0F172A] dark:text-white text-sm">R. Adhikari</div><div class="text-xs text-[#64748b] dark:text-[#94a3b8]">Operations Lead</div></div>
                    </div>
                </div>

                <div class="glass-card-premium rounded-2xl p-6 hover-elevate-sm reveal transition-all duration-300 border border-white/20 dark:border-white/5" style="transition-delay:0.2s">
                    <div class="flex text-yellow-400 text-sm mb-3">
                        <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                    </div>
                    <p class="text-[#0F172A] dark:text-white text-sm leading-relaxed">"Expiry alerts alone have saved us thousands. PharmaFlow is essential for any modern pharmacy. I recommend it to every colleague."</p>
                    <div class="flex items-center gap-3 mt-4 pt-4 border-t border-gray-200/50 dark:border-white/5">
                        <div class="w-10 h-10 rounded-full gradient-bg flex items-center justify-center text-white font-bold text-sm">NG</div>
                        <div><div class="font-semibold text-[#0F172A] dark:text-white text-sm">N. Gurung</div><div class="text-xs text-[#64748b] dark:text-[#94a3b8]">Retail Pharmacy Manager</div></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ─── PRICING ─── -->
    <section id="pricing" class="section-padding bg-white/40 dark:bg-white/5 border-y border-gray-200/50 dark:border-white/5">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-12 reveal">
                <span class="inline-block text-xs font-semibold text-[#2563EB] bg-blue-50 dark:bg-blue-500/10 px-4 py-1.5 rounded-full mb-4">Pricing</span>
                <h2 class="text-3xl md:text-4xl font-extrabold tracking-tight text-[#0F172A] dark:text-white">Choose your plan.</h2>
                <div class="flex items-center justify-center gap-4 mt-4">
                    <span class="text-sm font-medium text-[#475569] dark:text-[#94a3b8]">Monthly</span>
                    <div id="pricing-toggle" class="toggle-track">
                        <div class="toggle-thumb"></div>
                    </div>
                    <span class="text-sm font-medium text-[#475569] dark:text-[#94a3b8] flex items-center gap-1">Yearly <span class="text-xs text-[#2563EB] font-semibold">(save 20%)</span></span>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-5xl mx-auto">
                <div class="glass-card-premium rounded-2xl p-6 hover-elevate reveal transition-all duration-300 border border-white/20 dark:border-white/5">
                    <div class="text-sm font-semibold text-[#64748b] dark:text-[#94a3b8]">Starter</div>
                    <div class="mt-2 flex items-baseline gap-1">
                        <span class="text-3xl font-extrabold text-[#0F172A] dark:text-white">Rs <span class="price-amount" data-monthly="2999" data-yearly="2399">2,999</span></span>
                        <span class="text-sm text-[#64748b] dark:text-[#94a3b8]">/mo</span>
                    </div>
                    <p class="text-sm text-[#475569] dark:text-[#94a3b8] mt-1">For small pharmacies just getting started.</p>
                    <ul class="mt-4 space-y-2 text-sm text-[#475569] dark:text-[#94a3b8]">
                        <li>✓ Up to 3 users</li>
                        <li>✓ Inventory management</li>
                        <li>✓ Basic reports</li>
                        <li>✓ Email support</li>
                    </ul>
                    <a href="#cta" class="mt-6 block text-center border border-gray-300 dark:border-white/10 hover:bg-gray-50 dark:hover:bg-white/5 text-[#0F172A] dark:text-white font-semibold py-2.5 rounded-xl transition-all">Start Free Trial</a>
                </div>

                <div class="glass-card-premium rounded-2xl p-6 hover-elevate reveal transition-all duration-300 pricing-card-highlight relative" style="transition-delay:0.1s">
                    <span class="absolute -top-3 left-1/2 -translate-x-1/2 bg-[#2563EB] text-white text-[10px] font-bold px-4 py-1 rounded-full">Most Popular</span>
                    <div class="text-sm font-semibold text-[#2563EB]">Professional</div>
                    <div class="mt-2 flex items-baseline gap-1">
                        <span class="text-3xl font-extrabold text-[#0F172A] dark:text-white">Rs <span class="price-amount" data-monthly="5999" data-yearly="4799">5,999</span></span>
                        <span class="text-sm text-[#64748b] dark:text-[#94a3b8]">/mo</span>
                    </div>
                    <p class="text-sm text-[#475569] dark:text-[#94a3b8] mt-1">For growing pharmacies with multiple staff.</p>
                    <ul class="mt-4 space-y-2 text-sm text-[#475569] dark:text-[#94a3b8]">
                        <li>✓ Up to 15 users</li>
                        <li>✓ Advanced inventory + AI</li>
                        <li>✓ Expiry alerts &amp; analytics</li>
                        <li>✓ POS &amp; barcode support</li>
                        <li>✓ Priority support</li>
                        <li class="text-[#2563EB] font-medium">✓ 14-day free trial</li>
                    </ul>
                    <a href="#cta" class="mt-6 block text-center bg-[#2563EB] hover:bg-[#1d4ed8] text-white font-semibold py-2.5 rounded-xl transition-all shadow-lg shadow-blue-500/25">Start Free Trial</a>
                    <p class="text-[10px] text-[#64748b] dark:text-[#94a3b8] text-center mt-2">No setup fee. Cancel anytime.</p>
                </div>

                <div class="glass-card-premium rounded-2xl p-6 hover-elevate reveal transition-all duration-300 border border-white/20 dark:border-white/5" style="transition-delay:0.2s">
                    <div class="text-sm font-semibold text-[#64748b] dark:text-[#94a3b8]">Enterprise</div>
                    <div class="mt-2 flex items-baseline gap-1">
                        <span class="text-3xl font-extrabold text-[#0F172A] dark:text-white">Custom</span>
                        <span class="text-sm text-[#64748b] dark:text-[#94a3b8]">/mo</span>
                    </div>
                    <p class="text-sm text-[#475569] dark:text-[#94a3b8] mt-1">For large pharmacy chains and enterprises.</p>
                    <ul class="mt-4 space-y-2 text-sm text-[#475569] dark:text-[#94a3b8]">
                        <li>✓ Unlimited users</li>
                        <li>✓ All features + custom reports</li>
                        <li>✓ API access</li>
                        <li>✓ Dedicated account manager</li>
                        <li>✓ 24/7 support</li>
                    </ul>
                    <a href="#cta" class="mt-6 block text-center border border-gray-300 dark:border-white/10 hover:bg-gray-50 dark:hover:bg-white/5 text-[#0F172A] dark:text-white font-semibold py-2.5 rounded-xl transition-all">Contact Sales</a>
                </div>
            </div>
        </div>
    </section>

    <!-- ─── FAQ ─── -->
    <section class="section-padding">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12 reveal">
                <span class="inline-block text-xs font-semibold text-[#2563EB] bg-blue-50 dark:bg-blue-500/10 px-4 py-1.5 rounded-full mb-4">FAQ</span>
                <h2 class="text-3xl md:text-4xl font-extrabold tracking-tight text-[#0F172A] dark:text-white">Frequently Asked Questions</h2>
            </div>

            <div class="space-y-3">
                <div class="glass-card-premium rounded-2xl p-5 border border-white/20 dark:border-white/5 reveal">
                    <div class="faq-question flex items-center justify-between" data-target="faq1">
                        <span class="font-semibold text-[#0F172A] dark:text-white">Is my data secure with PharmaFlow?</span>
                        <i class="fas fa-chevron-down faq-icon text-[#64748b] dark:text-[#94a3b8] text-sm"></i>
                    </div>
                    <div id="faq1" class="faq-answer text-sm text-[#475569] dark:text-[#94a3b8] leading-relaxed">Yes. We use enterprise-grade encryption (AES-256) for all data at rest and in transit. Our systems are SOC 2 compliant and regularly audited. Your data is yours — always.</div>
                </div>

                <div class="glass-card-premium rounded-2xl p-5 border border-white/20 dark:border-white/5 reveal" style="transition-delay:0.05s">
                    <div class="faq-question flex items-center justify-between" data-target="faq2">
                        <span class="font-semibold text-[#0F172A] dark:text-white">Can I migrate from my current system?</span>
                        <i class="fas fa-chevron-down faq-icon text-[#64748b] dark:text-[#94a3b8] text-sm"></i>
                    </div>
                    <div id="faq2" class="faq-answer text-sm text-[#475569] dark:text-[#94a3b8] leading-relaxed">Absolutely. We provide free data migration support for all Professional and Enterprise plans. Our team will help you import your inventory, customer, and transaction data seamlessly.</div>
                </div>

                <div class="glass-card-premium rounded-2xl p-5 border border-white/20 dark:border-white/5 reveal" style="transition-delay:0.1s">
                    <div class="faq-question flex items-center justify-between" data-target="faq3">
                        <span class="font-semibold text-[#0F172A] dark:text-white">What kind of support do you offer?</span>
                        <i class="fas fa-chevron-down faq-icon text-[#64748b] dark:text-[#94a3b8] text-sm"></i>
                    </div>
                    <div id="faq3" class="faq-answer text-sm text-[#475569] dark:text-[#94a3b8] leading-relaxed">We offer email support for all plans, live chat for Professional, and 24/7 phone support with a dedicated account manager for Enterprise. We also have a comprehensive knowledge base and video tutorials.</div>
                </div>

                <div class="glass-card-premium rounded-2xl p-5 border border-white/20 dark:border-white/5 reveal" style="transition-delay:0.15s">
                    <div class="faq-question flex items-center justify-between" data-target="faq4">
                        <span class="font-semibold text-[#0F172A] dark:text-white">How does billing work?</span>
                        <i class="fas fa-chevron-down faq-icon text-[#64748b] dark:text-[#94a3b8] text-sm"></i>
                    </div>
                    <div id="faq4" class="faq-answer text-sm text-[#475569] dark:text-[#94a3b8] leading-relaxed">We bill monthly or yearly via credit card, bank transfer, or E-sewa. You can upgrade, downgrade, or cancel anytime. All plans include a 14-day free trial with no commitment.</div>
                </div>

                <div class="glass-card-premium rounded-2xl p-5 border border-white/20 dark:border-white/5 reveal" style="transition-delay:0.2s">
                    <div class="faq-question flex items-center justify-between" data-target="faq5">
                        <span class="font-semibold text-[#0F172A] dark:text-white">Can I access PharmaFlow on mobile?</span>
                        <i class="fas fa-chevron-down faq-icon text-[#64748b] dark:text-[#94a3b8] text-sm"></i>
                    </div>
                    <div id="faq5" class="faq-answer text-sm text-[#475569] dark:text-[#94a3b8] leading-relaxed">Yes. PharmaFlow is fully responsive and works on all modern browsers. We also offer native mobile apps for iOS and Android with offline mode support for pharmacists on the go.</div>
                </div>
            </div>
        </div>
    </section>

    <!-- ─── FINAL CTA ─── -->
    <section id="cta" class="relative overflow-hidden">
        <div class="absolute inset-0 gradient-bg opacity-90"></div>
        <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%23ffffff" fill-opacity="0.05"%3E%3Cpath d="M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-30"></div>
        <div class="relative z-10 section-padding">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-white">
                <div class="inline-flex items-center gap-2 bg-white/20 backdrop-blur-sm rounded-full px-5 py-1.5 text-sm font-medium mb-5 border border-white/10">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-white opacity-60"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-white"></span>
                    </span>
                    Start your 14-day free trial
                </div>
                <h2 class="text-3xl md:text-5xl font-extrabold tracking-tight leading-[1.1]">Ready to modernize your pharmacy?</h2>
                <p class="mt-4 text-lg text-white/80 max-w-lg mx-auto">Start free and experience smarter pharmacy operations. No credit card required.</p>
                <div class="mt-8 flex flex-wrap justify-center gap-3">
                    <a href="{{ route('register') }}" class="bg-white text-[#2563EB] hover:bg-gray-100 font-semibold px-8 py-3.5 rounded-2xl transition-all shadow-xl shadow-black/20 hover:shadow-black/30 hover:scale-[1.02] flex items-center gap-2">
                        ">Start Free Trial <i class="fas fa-arrow-right"></i></a>
                    <a href="#" class="bg-white/10 backdrop-blur-sm border border-white/20 hover:bg-white/20 text-white font-medium px-8 py-3.5 rounded-2xl transition-all flex items-center gap-2">
                        <i class="fas fa-play-circle"></i> Book Demo
                    </a>
                </div>
                <div class="mt-5 flex items-center justify-center gap-6 text-sm text-white/60">
                    <span class="flex items-center gap-1"><i class="fas fa-check-circle text-white/40"></i> 14-day trial</span>
                    <span class="flex items-center gap-1"><i class="fas fa-check-circle text-white/40"></i> No setup fee</span>
                    <span class="flex items-center gap-1"><i class="fas fa-check-circle text-white/40"></i> Cancel anytime</span>
                </div>
            </div>
        </div>
        <div class="absolute bottom-0 left-0 right-0 h-32 bg-gradient-to-t from-[#0F172A] to-transparent"></div>
    </section>

    <!-- ─── FOOTER ─── -->
    <footer class="bg-[#0F172A] text-white/70 border-t border-white/5">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="grid grid-cols-2 md:grid-cols-6 gap-8 pb-12 border-b border-white/5">
                <div class="col-span-2">
                    <a href="#" class="flex items-center gap-2.5 text-lg font-bold text-white mb-3">
                        <span class="w-8 h-8 rounded-xl gradient-bg flex items-center justify-center text-white text-sm">💊</span>
                        <span>PharmaFlow</span>
                    </a>
                    <p class="text-sm text-white/50 max-w-xs leading-relaxed">Smart Pharmacy Management for Modern Pharmacies. Built by pharmacists, for pharmacists.</p>
                    <div class="flex gap-3 mt-4">
                        <a href="#" class="w-9 h-9 rounded-lg bg-white/5 hover:bg-white/10 flex items-center justify-center transition-all text-white/60 hover:text-white"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="w-9 h-9 rounded-lg bg-white/5 hover:bg-white/10 flex items-center justify-center transition-all text-white/60 hover:text-white"><i class="fab fa-linkedin-in"></i></a>
                        <a href="#" class="w-9 h-9 rounded-lg bg-white/5 hover:bg-white/10 flex items-center justify-center transition-all text-white/60 hover:text-white"><i class="fab fa-github"></i></a>
                        <a href="#" class="w-9 h-9 rounded-lg bg-white/5 hover:bg-white/10 flex items-center justify-center transition-all text-white/60 hover:text-white"><i class="fab fa-youtube"></i></a>
                    </div>
                    <div class="mt-4 flex flex-col gap-1 text-xs text-white/40">
                        <span><i class="fas fa-envelope mr-2"></i> support@pharmaflow.com</span>
                        <span><i class="fas fa-map-marker-alt mr-2"></i> Kathmandu, Nepal</span>
                    </div>
                </div>
                <div>
                    <h4 class="text-white font-semibold text-sm mb-3">Product</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#features" class="footer-link hover:text-white transition-colors">Features</a></li>
                        <li><a href="#pricing" class="footer-link hover:text-white transition-colors">Pricing</a></li>
                        <li><a href="#" class="footer-link hover:text-white transition-colors">Integrations</a></li>
                        <li><a href="#" class="footer-link hover:text-white transition-colors">Changelog</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-semibold text-sm mb-3">Resources</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="footer-link hover:text-white transition-colors">Documentation</a></li>
                        <li><a href="#" class="footer-link hover:text-white transition-colors">Blog</a></li>
                        <li><a href="#" class="footer-link hover:text-white transition-colors">Community</a></li>
                        <li><a href="#" class="footer-link hover:text-white transition-colors">Support</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-semibold text-sm mb-3">Company</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="footer-link hover:text-white transition-colors">About</a></li>
                        <li><a href="#" class="footer-link hover:text-white transition-colors">Careers</a></li>
                        <li><a href="#" class="footer-link hover:text-white transition-colors">Press</a></li>
                        <li><a href="#" class="footer-link hover:text-white transition-colors">Contact</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-semibold text-sm mb-3">Legal</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="footer-link hover:text-white transition-colors">Privacy</a></li>
                        <li><a href="#" class="footer-link hover:text-white transition-colors">Terms</a></li>
                        <li><a href="#" class="footer-link hover:text-white transition-colors">Cookies</a></li>
                        <li><a href="#" class="footer-link hover:text-white transition-colors">Security</a></li>
                    </ul>
                </div>
            </div>
            <div class="pt-8 flex flex-col sm:flex-row justify-between items-center gap-4 text-sm text-white/40">
                <span>© 2026 PharmaFlow. All rights reserved.</span>
                <div class="flex items-center gap-4">
                    <span class="flex items-center gap-1 text-xs"><span class="w-2 h-2 rounded-full bg-green-400 inline-block"></span> SOC 2 Compliant</span>
                    <span class="flex items-center gap-1 text-xs"><span class="w-2 h-2 rounded-full bg-green-400 inline-block"></span> AES-256 Encryption</span>
                </div>
            </div>
        </div>
    </footer>

    <!-- ─── FAB ─── -->
    <button id="fab" aria-label="Back to top" class="hidden">
        <i class="fas fa-arrow-up"></i>
    </button>

    <!-- ─── TOAST ─── -->
    <div id="toast-container"></div>

    <!-- ─── COMMAND MENU ─── -->
    <div id="cmd-overlay" role="dialog" aria-modal="true" aria-label="Command menu">
        <div id="cmd-modal">
            <input id="cmd-input" type="text" placeholder="Search or jump to..." autofocus />
            <div id="cmd-results">
                <div class="cmd-item" data-action="features"><i class="fas fa-th-large"></i> Features <span class="cmd-kbd">→</span></div>
                <div class="cmd-item" data-action="pricing"><i class="fas fa-tag"></i> Pricing <span class="cmd-kbd">→</span></div>
                <div class="cmd-item" data-action="testimonials"><i class="fas fa-star"></i> Testimonials <span class="cmd-kbd">→</span></div>
                <div class="cmd-item" data-action="cta"><i class="fas fa-rocket"></i> Start Free Trial <span class="cmd-kbd">→</span></div>
                <div class="cmd-item" data-action="dark"><i class="fas fa-moon"></i> Toggle dark mode <span class="cmd-kbd">⌘D</span></div>
            </div>
        </div>
    </div>

    <!-- ─── JAVASCRIPT ─── -->
    <script>
        (function() {
            'use strict';

            const navbar = document.getElementById('navbar');
            const mobileToggle = document.getElementById('mobile-toggle');
            const mobileMenu = document.getElementById('mobile-menu');
            const darkToggle = document.getElementById('dark-toggle');
            const scrollProgress = document.getElementById('scroll-progress');
            const cursorGlow = document.getElementById('cursor-glow');
            const fab = document.getElementById('fab');
            const cmdOverlay = document.getElementById('cmd-overlay');
            const cmdInput = document.getElementById('cmd-input');
            const cmdResults = document.getElementById('cmd-results');
            const toastContainer = document.getElementById('toast-container');

            let darkMode = localStorage.getItem('pharmaflow-dark') === 'true';
            if (darkMode) document.documentElement.classList.add('dark');
            darkToggle.addEventListener('click', () => {
                darkMode = !darkMode;
                document.documentElement.classList.toggle('dark', darkMode);
                localStorage.setItem('pharmaflow-dark', String(darkMode));
                updateDarkToggleIcon();
                showToast(darkMode ? '🌙 Dark mode enabled' : '☀️ Light mode enabled');
            });

            function updateDarkToggleIcon() {
                const isDark = document.documentElement.classList.contains('dark');
                darkToggle.querySelector('.fa-moon').style.display = isDark ? 'none' : 'inline';
                darkToggle.querySelector('.fa-sun').style.display = isDark ? 'inline' : 'none';
            }
            updateDarkToggleIcon();

            let menuOpen = false;
            mobileToggle.addEventListener('click', () => {
                menuOpen = !menuOpen;
                mobileMenu.style.maxHeight = menuOpen ? '500px' : '0';
                mobileMenu.style.opacity = menuOpen ? '1' : '0';
                mobileToggle.querySelector('i').className = menuOpen ? 'fas fa-times text-xl' : 'fas fa-bars text-xl';
            });

            let lastScroll = 0;
            window.addEventListener('scroll', () => {
                const y = window.scrollY;
                navbar.classList.toggle('scrolled', y > 20);
                const docHeight = document.documentElement.scrollHeight - window.innerHeight;
                const progress = docHeight > 0 ? (y / docHeight) * 100 : 0;
                scrollProgress.style.width = progress + '%';
                fab.classList.toggle('hidden', y < 600);
                lastScroll = y;
            });

            if (window.innerWidth > 768) {
                document.addEventListener('mousemove', (e) => {
                    cursorGlow.style.left = e.clientX + 'px';
                    cursorGlow.style.top = e.clientY + 'px';
                    cursorGlow.style.opacity = '1';
                });
                document.addEventListener('mouseleave', () => {
                    cursorGlow.style.opacity = '0';
                });
            }

            fab.addEventListener('click', () => {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });

            document.addEventListener('keydown', (e) => {
                if ((e.metaKey || e.ctrlKey) && e.key === 'k') {
                    e.preventDefault();
                    toggleCmd();
                }
                if (e.key === 'Escape' && cmdOverlay.classList.contains('open')) {
                    toggleCmd();
                }
            });

            function toggleCmd() {
                const open = cmdOverlay.classList.toggle('open');
                if (open) {
                    cmdInput.value = '';
                    cmdInput.focus();
                    filterCmd('');
                }
            }

            cmdInput.addEventListener('input', (e) => filterCmd(e.target.value));

            function filterCmd(query) {
                const items = cmdResults.querySelectorAll('.cmd-item');
                const q = query.toLowerCase().trim();
                items.forEach(item => {
                    const text = item.textContent.toLowerCase();
                    item.style.display = (!q || text.includes(q)) ? 'flex' : 'none';
                });
                const visible = Array.from(items).filter(el => el.style.display !== 'none');
                visible.forEach((el, i) => el.classList.toggle('active', i === 0));
            }

            cmdResults.addEventListener('click', (e) => {
                const item = e.target.closest('.cmd-item');
                if (item) handleCmdAction(item.dataset.action);
            });

            cmdResults.addEventListener('keydown', (e) => {
                const items = Array.from(cmdResults.querySelectorAll('.cmd-item:not([style*="display: none"])'));
                const active = cmdResults.querySelector('.cmd-item.active');
                let idx = active ? items.indexOf(active) : -1;
                if (e.key === 'ArrowDown') {
                    e.preventDefault();
                    idx = Math.min(idx + 1, items.length - 1);
                } else if (e.key === 'ArrowUp') {
                    e.preventDefault();
                    idx = Math.max(idx - 1, 0);
                } else if (e.key === 'Enter' && active) {
                    e.preventDefault();
                    handleCmdAction(active.dataset.action);
                    return;
                }
                items.forEach((el, i) => el.classList.toggle('active', i === idx));
                if (idx >= 0) items[idx].scrollIntoView({ block: 'nearest' });
            });

            function handleCmdAction(action) {
                toggleCmd();
                if (action === 'features') document.getElementById('features').scrollIntoView({ behavior: 'smooth' });
                else if (action === 'pricing') document.getElementById('pricing').scrollIntoView({ behavior: 'smooth' });
                else if (action === 'testimonials') document.getElementById('testimonials').scrollIntoView({ behavior: 'smooth' });
                else if (action === 'cta') document.getElementById('cta').scrollIntoView({ behavior: 'smooth' });
                else if (action === 'dark') { darkToggle.click(); }
                showToast('✅ Navigated to ' + action);
            }

            function showToast(message, type) {
                const toast = document.createElement('div');
                toast.className = 'toast';
                toast.innerHTML = `<span>${message}</span>`;
                toastContainer.appendChild(toast);
                setTimeout(() => {
                    toast.classList.add('out');
                    setTimeout(() => toast.remove(), 350);
                }, 2800);
            }

            const toggleTrack = document.getElementById('pricing-toggle');
            let isYearly = false;
            toggleTrack.addEventListener('click', () => {
                isYearly = !isYearly;
                toggleTrack.classList.toggle('active', isYearly);
                document.querySelectorAll('.price-amount').forEach(el => {
                    const monthly = parseFloat(el.dataset.monthly);
                    const yearly = parseFloat(el.dataset.yearly);
                    el.textContent = isYearly ? yearly.toLocaleString() : monthly.toLocaleString();
                });
                showToast(isYearly ? '💰 Yearly pricing (save 20%)' : '💳 Monthly pricing');
            });

            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.addEventListener('click', () => {
                    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
                    btn.classList.add('active');
                    const target = btn.dataset.tab;
                    document.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));
                    document.getElementById('panel-' + target).classList.add('active');
                });
            });

            document.querySelectorAll('.faq-question').forEach(q => {
                q.addEventListener('click', () => {
                    const targetId = q.dataset.target;
                    const answer = document.getElementById(targetId);
                    const icon = q.querySelector('.faq-icon');
                    const isOpen = answer.classList.contains('open');
                    document.querySelectorAll('.faq-answer').forEach(a => {
                        if (a.id !== targetId) {
                            a.classList.remove('open');
                            a.previousElementSibling.querySelector('.faq-icon')?.classList.remove('open');
                        }
                    });
                    answer.classList.toggle('open', !isOpen);
                    icon.classList.toggle('open', !isOpen);
                });
            });

            const revealObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('visible');
                    }
                });
            }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });
            document.querySelectorAll('.reveal').forEach(el => revealObserver.observe(el));

            const counterObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const el = entry.target;
                        const target = parseInt(el.dataset.target);
                        let current = 0;
                        const increment = Math.ceil(target / 50);
                        const timer = setInterval(() => {
                            current += increment;
                            if (current >= target) {
                                current = target;
                                clearInterval(timer);
                            }
                            el.textContent = current;
                        }, 30);
                        counterObserver.unobserve(el);
                    }
                });
            }, { threshold: 0.4 });
            document.querySelectorAll('.counter').forEach(el => counterObserver.observe(el));

            document.querySelectorAll('a[href="#cta"]').forEach(link => {
                link.addEventListener('click', (e) => {
                    setTimeout(() => showToast('🚀 Start your free trial — no credit card needed'), 400);
                });
            });

            setTimeout(() => {
                showToast('⌘K · Quick navigation');
            }, 1200);

            document.querySelectorAll('#mobile-menu a').forEach(link => {
                link.addEventListener('click', () => {
                    menuOpen = false;
                    mobileMenu.style.maxHeight = '0';
                    mobileMenu.style.opacity = '0';
                    mobileToggle.querySelector('i').className = 'fas fa-bars text-xl';
                });
            });

            cmdOverlay.addEventListener('click', (e) => {
                if (e.target === cmdOverlay) toggleCmd();
            });

            console.log('💊 PharmaFlow · Premium Pharmacy Management');
        })();
    </script>

</body>
</html>