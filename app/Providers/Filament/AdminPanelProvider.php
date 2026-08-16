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
            ->brandName('Camping')
            ->brandLogo(fn () => asset('images/logo_gunung_pancar.png'))
            ->brandLogoHeight('2.5rem')
            ->font('Plus Jakarta Sans', 'https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800&display=swap')
            ->sidebarCollapsibleOnDesktop()
            ->sidebarWidth('15rem')
            ->databaseNotifications()
            ->databaseNotificationsPolling('30s')
            ->colors([
                'primary' => \Filament\Support\Colors\Color::Emerald, // Premium Green
                'danger' => \Filament\Support\Colors\Color::Rose,
                'gray' => \Filament\Support\Colors\Color::Slate, 
                'info' => \Filament\Support\Colors\Color::Sky, 
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
                    margin-left: 0 !important;
                    justify-content: flex-start !important;
                    padding-left: 0.25rem !important;
                }
                .fi-sidebar-header .fi-logo img {
                    object-position: left center !important;
                }

                /* ── APEX-STYLE PREMIUM DARK MODE ── */
                .dark body, .dark .fi-main {
                    background-color: #06110a !important; /* Deep forest dark */
                }
                .dark .fi-sidebar {
                    background-color: #040d07 !important;
                    border-right: 1px solid rgba(16, 185, 129, 0.1) !important;
                }
                .dark .fi-topbar {
                    background-color: rgba(4, 13, 7, 0.8) !important;
                    border-bottom: 1px solid rgba(16, 185, 129, 0.1) !important;
                    backdrop-filter: blur(12px);
                }
                .dark .fi-ta-ctn, .dark .fi-wi-stats-overview-stat, .dark .fi-wi-chart, .dark .fi-section, .dark .fi-dropdown-panel {
                    background: linear-gradient(145deg, #091f15 0%, #06160e 100%) !important;
                    border: 1px solid rgba(16, 185, 129, 0.15) !important;
                    box-shadow: 0 10px 30px -10px rgba(0,0,0,0.8) !important;
                    border-radius: 1rem !important;
                }
                .dark .fi-ta-header-heading, .dark h1, .dark h2, .dark h3 {
                    color: #f8fafc !important;
                }

                /* Fix for Sidebar Footer Overlap */
                .fi-sidebar-nav {
                    padding-bottom: 6rem !important;
                }
                .fi-sidebar-nav-groups, .fi-sidebar-nav > ul {
                    padding-bottom: 6rem !important;
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
                            /* Stats Overview optimizations for 2x2 grid */
                            .fi-wi-stats-overview-stat {
                                padding: 0.85rem 0.75rem !important;
                                border-radius: 0.75rem !important;
                            }
                            .fi-wi-stats-overview-stat-label {
                                font-size: 0.75rem !important;
                                font-weight: 700 !important;
                                line-height: 1.2 !important;
                                color: #94a3b8 !important;
                            }
                            .fi-wi-stats-overview-stat-value {
                                font-size: 1.25rem !important;
                                font-weight: 800 !important;
                            }
                            .fi-wi-stats-overview-stat-description,
                            .fi-wi-stats-overview-stat-description > * {
                                font-size: 0.65rem !important;
                                line-height: 1.2 !important;
                                white-space: nowrap !important;
                            }
                            .fi-wi-stats-overview-stat-description svg {
                                height: 0.8rem !important;
                                width: 0.8rem !important;
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
            )
            ->renderHook(
                \Filament\View\PanelsRenderHook::SIDEBAR_FOOTER,
                fn (): string => Blade::render('
                    @auth
                    @php
                        $user = auth()->user();
                        $name = $user?->name ?? "User";
                        $initial = mb_strtoupper(mb_substr($name, 0, 1));
                        $role = $user?->roles->first()?->name ?? "panel_user";
                        $roleLabel = match($role) {
                            "super_admin" => "Superuser",
                            "sales"       => "Sales",
                            "ticketing"   => "Ticketing",
                            "kasir"       => "Kasir",
                            default       => ucfirst(str_replace("_", " ", $role)),
                        };
                        $editUrl = route("filament.admin.auth.profile");
                    @endphp
                    <style>
                        .sb-user-card {
                            display: flex;
                            align-items: center;
                            gap: 0.65rem;
                            padding: 0.85rem 1rem;
                            margin: 0.5rem 0.75rem 0.75rem;
                            background: rgba(4, 13, 7, 0.95);
                            backdrop-filter: blur(12px);
                            border: 1px solid rgba(16, 185, 129, 0.3);
                            border-radius: 0.85rem;
                            text-decoration: none;
                            transition: all 0.2s ease;
                            cursor: pointer;
                            overflow: hidden;
                            position: relative;
                            z-index: 50;
                            box-shadow: 0 -4px 20px rgba(0,0,0,0.5);
                        }
                        .sb-user-card:hover {
                            background: rgba(16, 185, 129, 0.14);
                            border-color: rgba(16, 185, 129, 0.3);
                        }
                        .sb-user-avatar {
                            width: 36px;
                            height: 36px;
                            border-radius: 50%;
                            background: linear-gradient(135deg, #059669, #10b981);
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            font-size: 0.9rem;
                            font-weight: 800;
                            color: #fff;
                            flex-shrink: 0;
                            box-shadow: 0 0 0 2px rgba(16,185,129,0.3);
                        }
                        .sb-user-info {
                            flex: 1;
                            min-width: 0;
                        }
                        .sb-user-name {
                            font-size: 0.8rem;
                            font-weight: 700;
                            color: #f0fdf4;
                            white-space: nowrap;
                            overflow: hidden;
                            text-overflow: ellipsis;
                            line-height: 1.2;
                        }
                        .sb-user-role {
                            font-size: 0.68rem;
                            color: #6ee7b7;
                            font-weight: 500;
                            margin-top: 1px;
                        }
                        .sb-user-icon {
                            color: rgba(110, 231, 183, 0.5);
                            flex-shrink: 0;
                        }
                        .sb-user-icon svg {
                            width: 14px;
                            height: 14px;
                        }
                    </style>
                    <a href="{{ $editUrl }}" class="sb-user-card" x-data="{}" x-bind:style="$store.sidebar.isOpen ? \'\' : \'padding: 0.5rem; justify-content: center; background: transparent; border-color: transparent;\'">
                        <div class="sb-user-avatar">{{ $initial }}</div>
                        <div class="sb-user-info" x-show="$store.sidebar.isOpen" style="display: none;" x-transition.opacity>
                            <div class="sb-user-name">{{ $name }}</div>
                            <div class="sb-user-role">{{ $roleLabel }}</div>
                        </div>
                        <span class="sb-user-icon" x-show="$store.sidebar.isOpen" style="display: none;" x-transition.opacity>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                            </svg>
                        </span>
                    </a>
                    @endauth
                ')
            );
    }
}
