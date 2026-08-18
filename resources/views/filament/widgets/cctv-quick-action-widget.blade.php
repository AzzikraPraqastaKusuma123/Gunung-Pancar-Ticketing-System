<x-filament-widgets::widget>
    <style>
        .qa-confirm-overlay {
            display: none;
            position: fixed; inset: 0;
            background: rgba(0,0,0,0.7);
            backdrop-filter: blur(4px);
            z-index: 9999;
            align-items: center;
            justify-content: center;
        }
        .qa-confirm-overlay.active { display: flex; }

        .qa-confirm-box {
            background: #ffffff;
            border: 1px solid rgba(0,0,0,0.1);
            border-radius: 1rem;
            padding: 2rem;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 25px 50px rgba(0,0,0,0.2);
            animation: qa-fadein 0.15s ease;
        }
        .dark .qa-confirm-box {
            background: #111827;
            border: 1px solid rgba(255,255,255,0.1);
            box-shadow: 0 25px 50px rgba(0,0,0,0.6);
        }
            border-radius: 1rem;
            padding: 2rem;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 25px 50px rgba(0,0,0,0.6);
            animation: qa-fadein 0.15s ease;
        }
        @keyframes qa-fadein {
            from { opacity:0; transform: scale(0.97); }
            to   { opacity:1; transform: scale(1); }
        }

        .qa-btn {
            background: #f3f4f6;
            border: 1px solid rgba(0,0,0,0.05);
            border-radius: 0.75rem;
            padding: 1rem;
            text-align: left;
            cursor: pointer;
            display: flex;
            flex-direction: column;
            gap: 0.625rem;
            width: 100%;
            transition: border-color 0.2s, background 0.2s, transform 0.1s;
            position: relative;
            overflow: hidden;
        }
        .dark .qa-btn {
            background: rgba(17,24,39,0.7);
            border-color: rgba(255,255,255,0.08);
        }
        .qa-btn:active { transform: scale(0.98); }
        .qa-btn-red:hover   { border-color: rgba(239,68,68,0.5); background: rgba(127,29,29,0.25); }
        .qa-btn-blue:hover  { border-color: rgba(59,130,246,0.5); background: rgba(30,58,138,0.25); }
        .qa-btn-amber:hover { border-color: rgba(245,158,11,0.5); background: rgba(120,53,15,0.25); }
        .qa-btn-green:hover { border-color: rgba(16,185,129,0.5); background: rgba(6,78,59,0.25); }

        .qa-icon-wrap {
            width: 2.75rem; height: 2.75rem;
            border-radius: 0.625rem;
            display: flex; align-items: center; justify-content: center;
        }
        .qa-log-bar {
            background: #f3f4f6;
            border: 1px solid rgba(0,0,0,0.05);
            border-radius: 0.625rem;
            padding: 0.875rem 1rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 1rem;
        }
        .dark .qa-log-bar {
            background: rgba(17,24,39,0.7);
            border-color: rgba(255,255,255,0.06);
        }
    </style>

    <!-- Confirm Overlay -->
    <div class="qa-confirm-overlay" id="qa-overlay">
        <div class="qa-confirm-box">
            <div style="display:flex; align-items:center; gap:0.75rem; margin-bottom:1.25rem;">
                <div id="qa-overlay-icon" style="width:3rem; height:3rem; border-radius:0.75rem; display:flex; align-items:center; justify-content:center;"></div>
                <div>
                    <p style="font-size:0.7rem; color:#6b7280; text-transform:uppercase; letter-spacing:0.08em; margin:0;">Konfirmasi Tindakan</p>
                    <h3 id="qa-overlay-title" class="text-gray-900 dark:text-gray-100" style="font-size:1.1rem; font-weight:700; margin:0.2rem 0 0;"></h3>
                </div>
            </div>
            <p id="qa-overlay-desc" style="font-size:0.875rem; color:#9ca3af; margin:0 0 1.5rem; line-height:1.6;"></p>
            <div style="display:flex; gap:0.75rem;">
                <button onclick="qaCancel()" style="flex:1; padding:0.75rem; background:transparent; border:1px solid rgba(255,255,255,0.1); border-radius:0.625rem; color:#9ca3af; font-size:0.875rem; font-weight:500; cursor:pointer;">Batal</button>
                <button id="qa-confirm-btn" onclick="qaExecute()" style="flex:1; padding:0.75rem; border-radius:0.625rem; font-size:0.875rem; font-weight:600; cursor:pointer; color:#fff; border:none;"></button>
            </div>
        </div>
    </div>

    <x-filament::section>
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem; flex-wrap:wrap; gap:0.5rem;">
            <div>
                <p style="font-size:0.7rem; font-weight:600; color:#4b5563; text-transform:uppercase; letter-spacing:0.08em; margin:0;">EMERGENCY CONTROLS</p>
                <h2 class="text-gray-900 dark:text-gray-100" style="font-size:1.125rem; font-weight:700; margin:0.2rem 0 0;">Kendali Darurat</h2>
            </div>
            <div style="display:flex; align-items:center; gap:0.4rem; padding:0.3rem 0.75rem; background:rgba(16,185,129,0.1); border:1px solid rgba(16,185,129,0.2); border-radius:9999px;">
                <span style="width:5px; height:5px; background:#10b981; border-radius:50%;"></span>
                <span style="font-size:0.7rem; font-weight:600; color:#10b981;">Sistem Siap</span>
            </div>
        </div>

        <div style="display:grid; grid-template-columns:repeat(2, 1fr); gap:0.625rem;">

            <button class="qa-btn qa-btn-red"
                onclick="qaConfirm('Aktifkan Sirine', 'Tindakan ini akan membunyikan sirine di seluruh zona camping dan mengirim notifikasi ke semua petugas. Pastikan ada ancaman nyata sebelum melanjutkan.', '#ef4444', 'sirine')">
                <div class="qa-icon-wrap" style="background:rgba(239,68,68,0.12);">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#f87171" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M19.114 5.636a9 9 0 0 1 0 12.728M16.463 8.288a5.25 5.25 0 0 1 0 7.424M6.75 8.25l4.72-4.72a.75.75 0 0 1 1.28.53v15.88a.75.75 0 0 1-1.28.53l-4.72-4.72H4.51c-.88 0-1.704-.507-1.938-1.354A9.009 9.009 0 0 1 2.25 12c0-.83.112-1.633.322-2.396C2.806 8.756 3.63 8.25 4.51 8.25H6.75Z"/></svg>
                </div>
                <div>
                    <p class="text-gray-900 dark:text-gray-100" style="font-weight:700; font-size:0.9rem; margin:0;">Aktifkan Sirine</p>
                    <p style="color:#6b7280; font-size:0.75rem; margin:0.2rem 0 0;">Semua zona camping</p>
                </div>
            </button>

            <button class="qa-btn qa-btn-blue"
                onclick="qaConfirm('PA Broadcast', 'Pengumuman suara akan disiarkan ke seluruh pengeras suara di area camping. Pastikan pesan sudah disiapkan.', '#3b82f6', 'pa')">
                <div class="qa-icon-wrap" style="background:rgba(59,130,246,0.12);">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#60a5fa" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M12 18.75a6 6 0 0 0 6-6v-1.5m-6 7.5a6 6 0 0 1-6-6v-1.5m6 7.5v3.75m-3.75 0h7.5M12 15.75a3 3 0 0 1-3-3V4.5a3 3 0 1 1 6 0v8.25a3 3 0 0 1-3 3Z"/></svg>
                </div>
                <div>
                    <p class="text-gray-900 dark:text-gray-100" style="font-weight:700; font-size:0.9rem; margin:0;">PA Broadcast</p>
                    <p style="color:#6b7280; font-size:0.75rem; margin:0.2rem 0 0;">Pengumuman publik</p>
                </div>
            </button>

            <button class="qa-btn qa-btn-amber"
                onclick="qaConfirm('Lockdown Gerbang', 'Gerbang utama akan dikunci secara elektronik. Tidak ada kendaraan yang dapat masuk atau keluar sampai lockdown dicabut.', '#f59e0b', 'gate')">
                <div class="qa-icon-wrap" style="background:rgba(245,158,11,0.12);">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#fbbf24" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z"/></svg>
                </div>
                <div>
                    <p class="text-gray-900 dark:text-gray-100" style="font-weight:700; font-size:0.9rem; margin:0;">Lockdown Gerbang</p>
                    <p style="color:#6b7280; font-size:0.75rem; margin:0.2rem 0 0;">Kunci akses portal</p>
                </div>
            </button>

            <button class="qa-btn qa-btn-green"
                onclick="qaConfirm('Nyalakan Spotlight', 'Lampu sorot area akan dinyalakan untuk menerangi zona yang terdeteksi aktivitas mencurigakan.', '#10b981', 'light')">
                <div class="qa-icon-wrap" style="background:rgba(16,185,129,0.12);">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#34d399" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M12 18v-5.25m0 0a6.01 6.01 0 0 0 1.5-.189m-1.5.189a6.01 6.01 0 0 1-1.5-.189m3.75 7.478a12.06 12.06 0 0 1-4.5 0m3.75 2.383a14.406 14.406 0 0 1-3 0M14.25 18v-.192c0-.983.658-1.823 1.508-2.316a7.5 7.5 0 1 0-7.517 0c.85.493 1.509 1.333 1.509 2.316V18"/></svg>
                </div>
                <div>
                    <p class="text-gray-900 dark:text-gray-100" style="font-weight:700; font-size:0.9rem; margin:0;">Nyalakan Spotlight</p>
                    <p style="color:#6b7280; font-size:0.75rem; margin:0.2rem 0 0;">Lampu sorot darurat</p>
                </div>
            </button>
        </div>

        <!-- Activity Bar -->
        <div class="qa-log-bar">
            <span style="font-size:0.75rem; color:#6b7280;" id="qa-last-action">Belum ada tindakan dieksekusi sesi ini</span>
            <span style="font-size:0.7rem; color:#374151; font-weight:500;">LOG TINDAKAN</span>
        </div>
    </x-filament::section>

    <script>
        let currentAction = null;

        function qaConfirm(title, desc, color, action) {
            currentAction = action;
            document.getElementById('qa-overlay-title').textContent = title;
            document.getElementById('qa-overlay-desc').textContent = desc;
            document.getElementById('qa-overlay-icon').style.background = color + '22';
            document.getElementById('qa-confirm-btn').style.background = color;
            document.getElementById('qa-confirm-btn').textContent = 'Ya, Eksekusi Sekarang';
            document.getElementById('qa-overlay').classList.add('active');
        }

        function qaCancel() {
            document.getElementById('qa-overlay').classList.remove('active');
            currentAction = null;
        }

        function qaExecute() {
            const title = document.getElementById('qa-overlay-title').textContent;
            const now = new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
            document.getElementById('qa-last-action').textContent = `[${now}]  ${title} — dieksekusi oleh Petugas Keamanan`;
            qaCancel();
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') qaCancel();
        });
    </script>
</x-filament-widgets::widget>
