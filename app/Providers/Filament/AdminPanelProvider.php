<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use App\Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\View\PanelsRenderHook;
use Illuminate\Support\Facades\Blade;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('dasbord')
            ->login()
            ->profile(\App\Filament\Pages\EditProfile::class)
            ->brandName('TicketBrain')
            ->brandLogo(fn () => asset('images/logo.jpg'))
            ->brandLogoHeight('2.5rem')
            ->font('Plus Jakarta Sans', 'https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800&display=swap')
            ->sidebarCollapsibleOnDesktop()
            ->sidebarWidth('15rem')
            ->databaseNotifications()
            ->databaseNotificationsPolling('30s')
            ->colors([
                'primary' => \Filament\Support\Colors\Color::hex('#4f46e5'), // Royal Indigo
                'danger' => \Filament\Support\Colors\Color::Rose,
                'gray' => \Filament\Support\Colors\Color::Zinc, // Changed from Slate to Zinc for pure black/gray dark mode
                'info' => \Filament\Support\Colors\Color::hex('#06b6d4'), // Vibrant Cyan
                'success' => \Filament\Support\Colors\Color::Emerald,
                'warning' => \Filament\Support\Colors\Color::Amber,
            ])
            ->discoverClusters(in: app_path('Filament/Clusters'), for: 'App\Filament\Clusters')
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                PreventRequestForgery::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->plugins([
                \BezhanSalleh\FilamentShield\FilamentShieldPlugin::make()
            ])
            ->navigationItems([
                \Filament\Navigation\NavigationItem::make('Scanner Gate')
                    ->icon('heroicon-o-qr-code')
                    ->url(fn (): string => route('scanner'))
                    ->openUrlInNewTab()
                    ->visible(fn (): bool => auth()->user()->hasAnyRole(['super_admin', 'ticketing']))
                    ->sort(1),
            ])
            ->renderHook(
                \Filament\View\PanelsRenderHook::HEAD_END,
                fn (): string => Blade::render('
            <style>
                /* ── FORCE LOGO SIZE ONLY ON LOGIN PAGE ── */
                .fi-simple-layout .fi-logo {
                    display: flex !important;
                    align-items: center !important;
                    height: 4.5rem !important;
                    justify-content: center !important;
                    margin-bottom: 0.5rem;
                }
                .fi-simple-layout .fi-logo img {
                    height: 100% !important;
                    max-height: 4.5rem !important;
                    width: auto !important;
                    max-width: 100% !important;
                    object-fit: contain !important;
                    background: transparent;
                }

                /* ── PREMIUM LOGIN PAGE (GUNUNG PANCAR) ── */
                .fi-simple-layout {
                    background-image: url("https://images.unsplash.com/photo-1542273917363-3b1817f69a2d?q=80&w=2074&auto=format&fit=crop");
                    background-size: cover !important;
                    background-position: center center !important;
                    background-attachment: fixed !important;
                    position: relative;
                }
                
                .fi-simple-layout::before {
                    content: "";
                    position: fixed; /* Fix the overlay cutoff */
                    inset: 0;
                    width: 100vw;
                    height: 100vh;
                    background: linear-gradient(135deg, rgba(15, 23, 42, 0.85) 0%, rgba(6, 78, 59, 0.75) 100%);
                    backdrop-filter: blur(4px);
                    z-index: 0;
                }

                .fi-simple-main {
                    position: relative;
                    z-index: 10;
                }

                /* Hide Native Sign-in Title */
                .fi-simple-main h1 {
                    display: none !important;
                }

                .fi-simple-main .fi-card {
                    background: rgba(255, 255, 255, 0.45) !important; /* Make it more transparent */
                    backdrop-filter: blur(24px) !important;
                    -webkit-backdrop-filter: blur(24px) !important;
                    border: 1px solid rgba(255, 255, 255, 0.4) !important;
                    border-radius: 1.5rem !important;
                    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5), inset 0 0 0 1px rgba(255, 255, 255, 0.2) !important;
                    padding: 2.5rem !important;
                }
                
                /* Ensure form labels and text inside the card are readable */
                .fi-simple-main .fi-card label,
                .fi-simple-main .fi-card .fi-fo-field-wrp-label {
                    color: #111827 !important;
                    font-weight: 700 !important;
                }

                /* ── MAGIC DARK MODE LOGO ── */
                /* Removes the white background and perfectly adapts the logo for dark mode */
                .dark .fi-logo img, .dark img[src*="logo"] {
                    filter: invert(1) hue-rotate(180deg) brightness(1.5) !important;
                    mix-blend-mode: screen !important;
                }
                
                /* Pull Sidebar Logo to the left so it aligns perfectly with the menu text */
                .fi-sidebar-header .fi-logo {
                    margin-left: -0.75rem !important;
                }

                /* ── APEX-STYLE PREMIUM DARK MODE ── */
                .dark body, .dark .fi-main {
                    background-color: #09090b !important; /* Pitch black / ultra dark gray */
                }
                .dark .fi-sidebar {
                    background-color: #09090b !important;
                    border-right: 1px solid rgba(255, 255, 255, 0.05) !important;
                }
                .dark .fi-topbar {
                    background-color: rgba(9, 9, 11, 0.8) !important;
                    border-bottom: 1px solid rgba(255, 255, 255, 0.05) !important;
                    backdrop-filter: blur(12px);
                }
                .dark .fi-ta-ctn, .dark .fi-wi-stats-overview-stat, .dark .fi-wi-chart, .dark .fi-section, .dark .fi-dropdown-panel {
                    background: linear-gradient(145deg, #18181b 0%, #111113 100%) !important;
                    border: 1px solid rgba(255, 255, 255, 0.05) !important;
                    box-shadow: 0 10px 30px -10px rgba(0,0,0,0.8) !important;
                    border-radius: 1rem !important;
                }
                .dark .fi-ta-header-heading, .dark h1, .dark h2, .dark h3 {
                    color: #f8fafc !important;
                }
                
                /* ── DROPDOWN & SELECT SIZING FIX ── */
                .fi-dropdown-panel {
                    max-width: 90vw !important; /* Prevent it from being too wide on mobile */
                }
                .fi-dropdown-list-item-label, 
                .choices__list--dropdown .choices__item,
                .fi-dropdown-panel .text-sm,
                .fi-dropdown-panel button,
                .fi-dropdown-panel a {
                    font-size: 0.85rem !important; /* Smaller, neat font size */
                    font-weight: 500 !important;
                }
                .fi-dropdown-list-item {
                    padding-top: 0.35rem !important;
                    padding-bottom: 0.35rem !important;
                }
                
                /* ── GLOBAL TYPOGRAPHY ENHANCEMENT ── */
                body, .fi-body {
                    -webkit-font-smoothing: antialiased;
                    -moz-osx-font-smoothing: grayscale;
                }
                h1, h2, h3, h4, h5, h6, .fi-header-heading, .fi-ta-header-heading {
                    font-weight: 800 !important;
                    letter-spacing: -0.02em;
                }
                th, .fi-ta-header-cell {
                    font-weight: 700 !important;
                    text-transform: uppercase;
                    letter-spacing: 0.05em;
                }
                .fi-btn-label {
                    font-weight: 700 !important;
                }

                /* ── MINIMALIST NPROGRESS LOADING BAR ── */
                #nprogress .bar {
                    background: #10b981 !important;
                    height: 2px !important;
                }
                #nprogress .peg {
                    box-shadow: 0 0 10px #10b981, 0 0 5px #10b981 !important;
                }
                #nprogress .spinner-icon {
                    display: none !important;
                }
            </style>
                ')
            )
            ->renderHook(
                \Filament\View\PanelsRenderHook::AUTH_LOGIN_FORM_BEFORE,
                fn (): string => Blade::render('
                    <div style="text-align:center; margin-bottom: 0.5rem; margin-top: -1.5rem;">
                        <h2 style="font-size: 1.25rem; font-weight: 800; color: #064e3b; letter-spacing: -0.01em;">Portal Karyawan Gunung Pancar</h2>
                    </div>
                    <div style="width: 40px; height: 3px; background: #10b981; margin: 1rem auto; border-radius: 99px;"></div>
                ')
            )
            ->renderHook(
                \Filament\View\PanelsRenderHook::AUTH_LOGIN_FORM_AFTER,
                fn (): string => Blade::render('
                    <div style="text-align:center; margin-top: 1.5rem;">
                        <p style="font-size: 0.75rem; color: #6b7280; font-weight: 500;">
                            Powered by <span style="color: #10b981; font-weight: 700;">PT. Info Tech Support</span>
                        </p>
                    </div>
                ')
            )
            ->renderHook(
                \Filament\View\PanelsRenderHook::BODY_START,
                fn (): string => Blade::render('
                    <!-- ── 3-DOT PAGE PRELOADER ── -->
                    <div id="dot-preloader" style="position: fixed; inset: 0; z-index: 999999; display: flex; align-items: center; justify-content: center; background-color: #09090b; transition: opacity 0.4s ease;">
                        <div style="display: flex; gap: 0.75rem; align-items: center;">
                            <div class="loading-dot" style="animation-delay: -0.32s;"></div>
                            <div class="loading-dot" style="animation-delay: -0.16s;"></div>
                            <div class="loading-dot" style="animation-delay: 0s;"></div>
                        </div>
                    </div>

                    <style>
                        /* ── PRELOADER ── */
                        .loading-dot {
                            width: 1.25rem;
                            height: 1.25rem;
                            background-color: #10b981;
                            border-radius: 9999px;
                            animation: bounce-dot 1.4s infinite ease-in-out both;
                            box-shadow: 0 0 10px rgba(16,185,129,0.5);
                        }
                        @keyframes bounce-dot {
                            0%, 80%, 100% { transform: scale(0); opacity: 0.3; }
                            40% { transform: scale(1); opacity: 1; }
                        }

                        /* ── DENSE MOBILE GRID OPTIMIZATION (1x3 & 1x2) ── */
                        @media (max-width: 768px) {
                            /* Stats Overview (1x3) optimizations */
                            .fi-wi-stats-overview-stat {
                                padding: 0.75rem 0.25rem !important;
                                border-radius: 0.75rem !important;
                                gap: 0.25rem !important;
                                display: flex;
                                flex-direction: column;
                                align-items: center;
                                text-align: center;
                            }
                            .fi-wi-stats-overview-stat-label {
                                font-size: 0.7rem !important;
                                font-weight: 700 !important;
                                line-height: 1 !important;
                                color: #94a3b8 !important;
                                width: 100%;
                            }
                            .fi-wi-stats-overview-stat-value {
                                font-size: 1.15rem !important;
                                font-weight: 800 !important;
                                line-height: 1.1 !important;
                                margin-top: 0.25rem;
                            }
                            .fi-wi-stats-overview-stat-description {
                                font-size: 0.6rem !important;
                                margin-top: 0.15rem;
                                justify-content: center !important;
                                width: 100%;
                            }
                            .fi-wi-stats-overview-stat-description svg {
                                height: 0.75rem !important;
                                width: 0.75rem !important;
                            }
                            .fi-wi-stats-overview-stat-chart {
                                height: 25px !important;
                            }

                            /* Charts optimizations */
                            .fi-wi-chart {
                                padding: 0.75rem !important;
                                border-radius: 0.75rem !important;
                            }
                            .fi-wi-chart .fi-ta-header-heading,
                            .fi-wi-chart h2,
                            .fi-wi-chart .fi-card-header h2 {
                                font-size: 0.9rem !important;
                                font-weight: 700 !important;
                                line-height: 1.2 !important;
                            }
                        }
                    </style>

                    <script>
                        window.addEventListener("load", function() {
                            const pre = document.getElementById("dot-preloader");
                            if (pre) {
                                pre.style.opacity = "0";
                                setTimeout(() => pre.remove(), 400);
                            }
                        });
                        
                        setTimeout(() => {
                            const pre = document.getElementById("dot-preloader");
                            if (pre && pre.style.opacity !== "0") {
                                pre.style.opacity = "0";
                                setTimeout(() => pre.remove(), 400);
                            }
                        }, 5000);
                    </script>
                ')
            )
            ->renderHook(
                \Filament\View\PanelsRenderHook::BODY_END,
                fn (): string => Blade::render('
                    <!-- Subtle Mountain Theme Background -->
                    <div style="position: fixed; bottom: 0; left: 0; width: 100%; height: 30vh; pointer-events: none; z-index: -1; opacity: 0.03; mix-blend-mode: overlay; background-image: url(\'data:image/svg+xml;utf8,<svg viewBox=\"0 0 1200 400\" xmlns=\"http://www.w3.org/2000/svg\" preserveAspectRatio=\"none\"><path fill=\"%23ffffff\" fill-opacity=\"0.3\" d=\"M0 400 L200 150 L350 250 L600 50 L850 300 L1050 200 L1200 350 L1200 400 Z\" /><path fill=\"%23ffffff\" fill-opacity=\"0.6\" d=\"M-100 400 L150 250 L400 350 L650 150 L900 300 L1150 100 L1300 400 Z\" /></svg>\'); background-size: 100% 100%; background-repeat: no-repeat; background-position: bottom;"></div>
                ')
            );
    }
}
