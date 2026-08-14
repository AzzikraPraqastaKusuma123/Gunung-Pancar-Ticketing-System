<x-filament-panels::page>
    <style>
        .inbox-container { display: flex; height: calc(100vh - 12rem); width: 100%; gap: 1rem; overflow: hidden; margin-top: -1rem; font-family: inherit; }
        .inbox-col-left { width: 25%; min-width: 280px; background: #fff; border-radius: 0.75rem; box-shadow: 0 1px 2px 0 rgba(0,0,0,0.05); border: 1px solid #e5e7eb; display: flex; flex-direction: column; overflow: hidden; }
        .inbox-col-center { flex: 1; background: #fff; border-radius: 0.75rem; box-shadow: 0 1px 2px 0 rgba(0,0,0,0.05); border: 1px solid #e5e7eb; display: flex; flex-direction: column; overflow: hidden; position: relative; }
        .inbox-col-right { width: 30%; min-width: 280px; background: #fff; border-radius: 0.75rem; box-shadow: 0 1px 2px 0 rgba(0,0,0,0.05); border: 1px solid #e5e7eb; display: flex; flex-direction: column; overflow-y: auto; padding: 1rem; }
        
        .inbox-search-wrap { padding: 1rem; border-bottom: 1px solid #f3f4f6; background: rgba(249, 250, 251, 0.5); }
        .inbox-search-input { width: 100%; padding: 0.5rem 1rem 0.5rem 2.25rem; border: 1px solid #e5e7eb; border-radius: 0.5rem; font-size: 0.875rem; outline: none; }
        .inbox-search-input:focus { border-color: #3b82f6; box-shadow: 0 0 0 1px #3b82f6; }
        
        .inbox-filters { display: flex; gap: 0.5rem; margin-top: 0.75rem; overflow-x: auto; padding-bottom: 0.25rem; }
        .inbox-filter-pill { padding: 0.25rem 0.75rem; font-size: 0.75rem; font-weight: 500; border-radius: 9999px; cursor: pointer; white-space: nowrap; }
        .inbox-filter-active { background: #eff6ff; color: #2563eb; }
        .inbox-filter-default { background: #f3f4f6; color: #4b5563; }
        .inbox-filter-danger { background: #fef2f2; color: #dc2626; }
        
        .inbox-chat-list { flex: 1; overflow-y: auto; }
        .inbox-chat-item { padding: 0.75rem; border-bottom: 1px solid #f3f4f6; cursor: pointer; transition: background 0.15s; border-left: 4px solid transparent; }
        .inbox-chat-item:hover { background: #f9fafb; }
        .inbox-chat-item-active { border-left-color: #3b82f6; background: rgba(239, 246, 255, 0.5); }
        
        .inbox-chat-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 0.25rem; }
        .inbox-chat-name { font-weight: 600; color: #111827; font-size: 0.875rem; }
        .inbox-chat-time { font-size: 0.625rem; color: #6b7280; }
        .inbox-chat-preview { font-size: 0.75rem; color: #4b5563; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; padding-right: 0.5rem; }
        
        .inbox-badge { display: inline-flex; padding: 0.125rem 0.5rem; font-size: 0.5625rem; font-weight: 500; border-radius: 0.25rem; margin-top: 0.5rem; }
        .inbox-badge-yellow { background: #fef3c7; color: #b45309; }
        .inbox-badge-green { background: #d1fae5; color: #047857; }
        .inbox-badge-red { background: #fee2e2; color: #b91c1c; }
        
        .inbox-center-header { padding: 1rem; border-bottom: 1px solid #f3f4f6; display: flex; justify-content: space-between; align-items: center; background: #fff; z-index: 10; }
        .inbox-avatar { width: 2.5rem; height: 2.5rem; border-radius: 9999px; background: #e5e7eb; display: flex; align-items: center; justify-content: center; color: #6b7280; font-weight: 700; }
        
        .inbox-messages { flex: 1; overflow-y: auto; padding: 1rem; display: flex; flex-direction: column; gap: 1rem; background-color: #e5ddd5; background-image: url('https://user-images.githubusercontent.com/15075759/28719144-86dc0f70-73b1-11e7-911d-60d70fcded21.png'); background-blend-mode: overlay; }
        
        .inbox-msg-row { display: flex; align-items: flex-start; gap: 0.5rem; max-width: 75%; }
        .inbox-msg-row.self-start { align-self: flex-start; }
        .inbox-msg-row.self-end { align-self: flex-end; flex-direction: row-reverse; }
        
        .inbox-msg-bubble { padding: 0.5rem 1rem; font-size: 0.875rem; color: #1f2937; box-shadow: 0 1px 2px 0 rgba(0,0,0,0.05); }
        .inbox-msg-bubble.left { background: #fff; border-radius: 1rem 1rem 1rem 0; }
        .inbox-msg-bubble.right { background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 1rem 1rem 0 1rem; text-align: left; }
        .inbox-msg-bubble.ai { background: #fff; border: 1px solid #dbeafe; border-radius: 1rem 1rem 1rem 0; position: relative; }
        
        .inbox-msg-meta { font-size: 0.5625rem; color: #6b7280; margin-top: 0.25rem; }
        
        .inbox-input-area { padding: 0.75rem; background: #fff; border-top: 1px solid #f3f4f6; display: flex; align-items: flex-end; gap: 0.5rem; }
        .inbox-textarea { flex: 1; background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 0.75rem; padding: 0.625rem 1rem; font-size: 0.875rem; outline: none; resize: none; min-height: 44px; max-height: 128px; font-family: inherit; }
        .inbox-textarea:focus { border-color: #60a5fa; box-shadow: 0 0 0 1px #60a5fa; }
        .inbox-btn-send { padding: 0.625rem; background: #2563eb; color: #fff; border-radius: 9999px; border: none; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: background 0.15s; }
        .inbox-btn-send:hover { background: #1d4ed8; }
        
        .inbox-btn { padding: 0.375rem 0.75rem; font-size: 0.75rem; font-weight: 500; border-radius: 0.5rem; border: none; cursor: pointer; }
        .inbox-btn-light { background: #f3f4f6; color: #4b5563; }
        .inbox-btn-success { background: #16a34a; color: #fff; }
    </style>

    <div class="inbox-container">
        
        <!-- LEFT COLUMN: Conversation List -->
        <div class="inbox-col-left">
            <div class="inbox-search-wrap">
                <div style="position: relative;">
                    <input type="text" placeholder="Search conversations..." class="inbox-search-input">
                    <svg style="width: 1rem; height: 1rem; position: absolute; left: 0.75rem; top: 0.75rem; color: #9ca3af;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <div class="inbox-filters">
                    <span class="inbox-filter-pill inbox-filter-active">All</span>
                    <span class="inbox-filter-pill inbox-filter-default">Unread</span>
                    <span class="inbox-filter-pill inbox-filter-danger">Escalated</span>
                </div>
            </div>
            
            <div class="inbox-chat-list">
                <!-- Chat Item 1 (Active) -->
                <div class="inbox-chat-item inbox-chat-item-active">
                    <div class="inbox-chat-header">
                        <span class="inbox-chat-name">Budi Santoso</span>
                        <span class="inbox-chat-time">10:31 AM</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <p class="inbox-chat-preview">Baik Kak, saya bantu cek status pemesanannya ya.</p>
                        <span style="flex-shrink: 0; width: 1rem; height: 1rem; background: #3b82f6; color: #fff; border-radius: 9999px; display: flex; align-items: center; justify-content: center; font-size: 0.5625rem; font-weight: 700;">2</span>
                    </div>
                    <div class="inbox-badge inbox-badge-yellow">HUMAN_HANDLING</div>
                </div>

                <!-- Chat Item 2 -->
                <div class="inbox-chat-item">
                    <div class="inbox-chat-header">
                        <span class="inbox-chat-name">Siti Aminah</span>
                        <span class="inbox-chat-time">09:15 AM</span>
                    </div>
                    <p class="inbox-chat-preview">Terima kasih atas informasinya, sangat membantu.</p>
                    <div class="inbox-badge inbox-badge-green">AI_HANDLING</div>
                </div>
                
                <!-- Chat Item 3 -->
                <div class="inbox-chat-item">
                    <div class="inbox-chat-header">
                        <span class="inbox-chat-name">Ahmad Fauzi</span>
                        <span class="inbox-chat-time">Yesterday</span>
                    </div>
                    <p class="inbox-chat-preview">Saya ingin komplain mengenai pesanan saya yang belum sampai.</p>
                    <div class="inbox-badge inbox-badge-red">ESCALATED</div>
                </div>
            </div>
        </div>

        <!-- CENTER COLUMN: Chat Interface -->
        <div class="inbox-col-center">
            <!-- Chat Header -->
            <div class="inbox-center-header">
                <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <div class="inbox-avatar">BS</div>
                    <div>
                        <h3 style="font-weight: 600; color: #111827; margin: 0; font-size: 1rem;">Budi Santoso</h3>
                        <div style="display: flex; align-items: center; gap: 0.5rem; margin-top: 0.125rem;">
                            <span style="width: 0.5rem; height: 0.5rem; border-radius: 9999px; background: #22c55e;"></span>
                            <span style="font-size: 0.75rem; color: #6b7280;">Online (WhatsApp)</span>
                        </div>
                    </div>
                </div>
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <button class="inbox-btn inbox-btn-light">Return to AI</button>
                    <button class="inbox-btn inbox-btn-success">Resolve</button>
                </div>
            </div>

            <!-- Chat Messages Area -->
            <div class="inbox-messages">
                <!-- Date Separator -->
                <div style="display: flex; justify-content: center;">
                    <span style="background: rgba(255,255,255,0.9); color: #4b5563; font-size: 0.625rem; padding: 0.25rem 0.75rem; border-radius: 0.375rem; box-shadow: 0 1px 2px rgba(0,0,0,0.05);">Today</span>
                </div>

                <!-- Customer Message -->
                <div class="inbox-msg-row self-start">
                    <div class="inbox-avatar" style="width: 1.5rem; height: 1.5rem; font-size: 0.625rem; color: #fff; background: #d1d5db;">BS</div>
                    <div style="display: flex; flex-direction: column;">
                        <div class="inbox-msg-bubble left">
                            Halo, saya mau nanya soal paket tur ke Bali untuk bulan depan. Apa masih ada slot?
                        </div>
                        <span class="inbox-msg-meta" style="margin-left: 0.25rem;">10:28 AM</span>
                    </div>
                </div>

                <!-- AI Message -->
                <div class="inbox-msg-row self-start" style="margin-top: 0.5rem;">
                    <div class="inbox-avatar" style="width: 1.5rem; height: 1.5rem; font-size: 0.625rem; background: #dbeafe; border: 1px solid #bfdbfe;">🤖</div>
                    <div style="display: flex; flex-direction: column;">
                        <div class="inbox-msg-bubble ai">
                            Halo Kak Budi! 👋<br><br>
                            Untuk paket tur ke Bali bulan depan, kami masih memiliki beberapa slot tersedia. Bisa diinformasikan rencana tanggal keberangkatannya dan untuk berapa orang?
                            <div style="position: absolute; left: -0.5rem; top: 0.5rem; width: 0.375rem; height: 1.5rem; background: #60a5fa; border-radius: 0.25rem;"></div>
                        </div>
                        <span class="inbox-msg-meta" style="margin-left: 0.25rem;">10:28 AM • AI Assistant</span>
                    </div>
                </div>

                <!-- Customer Message -->
                <div class="inbox-msg-row self-start">
                    <div class="inbox-avatar" style="width: 1.5rem; height: 1.5rem; font-size: 0.625rem; color: #fff; background: #d1d5db;">BS</div>
                    <div style="display: flex; flex-direction: column;">
                        <div class="inbox-msg-bubble left">
                            Sekitar tanggal 15 Agustus, untuk 4 orang. Bisa minta diskon gak kalau ambil berempat? Trus kalau dicancel bisa direfund?
                        </div>
                        <span class="inbox-msg-meta" style="margin-left: 0.25rem;">10:30 AM</span>
                    </div>
                </div>

                <!-- AI Internal Note (Escalation) -->
                <div style="display: flex; justify-content: center; margin: 0.5rem 0;">
                    <div style="background: #fef2f2; border: 1px solid #fee2e2; padding: 0.375rem 0.75rem; border-radius: 0.5rem; display: flex; align-items: center; gap: 0.5rem; box-shadow: 0 1px 2px rgba(0,0,0,0.05); color: #b91c1c; font-size: 0.75rem;">
                        <svg style="width: 0.875rem; height: 0.875rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        <span>AI Confidence Low (65%) - Intent: Negotiation & Refund Policy. Auto-escalated to Human Agent.</span>
                    </div>
                </div>

                <!-- Agent Message (Me) -->
                <div class="inbox-msg-row self-end">
                    <div class="inbox-avatar" style="width: 1.5rem; height: 1.5rem; font-size: 0.625rem; color: #fff; background: #2563eb;">CS</div>
                    <div style="display: flex; flex-direction: column; align-items: flex-end;">
                        <div class="inbox-msg-bubble right">
                            Halo Kak Budi, dengan Andi (Customer Service) di sini. Saya bantu cek terlebih dahulu ya Kak untuk ketersediaan slot tanggal 15 Agustus beserta penawaran diskonnya. Mohon ditunggu sebentar. 🙏
                        </div>
                        <span class="inbox-msg-meta" style="margin-right: 0.25rem;">10:31 AM • You (Andi)</span>
                    </div>
                </div>
            </div>

            <!-- Input Area -->
            <div class="inbox-input-area">
                <button style="padding: 0.5rem; color: #9ca3af; background: none; border: none; cursor: pointer;">
                    <svg style="width: 1.25rem; height: 1.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                </button>
                <textarea rows="1" placeholder="Ketik pesan..." class="inbox-textarea"></textarea>
                <button class="inbox-btn-send">
                    <svg style="width: 1rem; height: 1rem; margin-left: 0.125rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                </button>
            </div>
            
            <!-- Agent status bar -->
            <div style="background: #f9fafb; padding: 0.375rem 1rem; font-size: 0.625rem; color: #6b7280; display: flex; justify-content: space-between; border-top: 1px solid #f3f4f6;">
                <div style="display: flex; align-items: center; gap: 0.25rem;">
                    <span style="width: 0.375rem; height: 0.375rem; border-radius: 9999px; background: #eab308;"></span>
                    Human Handling Active (You)
                </div>
                <div>Press Enter to send, Shift+Enter for new line</div>
            </div>
        </div>

        <!-- RIGHT COLUMN: Info Sidebar -->
        <div class="inbox-col-right">
            <!-- Customer Profile -->
            <div style="display: flex; flex-direction: column; align-items: center; border-bottom: 1px solid #f3f4f6; padding-bottom: 1rem;">
                <div style="width: 5rem; height: 5rem; border-radius: 9999px; background: linear-gradient(to top right, #60a5fa, #2563eb); color: #fff; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; font-weight: 700; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); margin-bottom: 0.75rem;">BS</div>
                <h3 style="font-weight: 700; color: #111827; font-size: 1.125rem; margin: 0;">Budi Santoso</h3>
                <p style="font-size: 0.875rem; color: #6b7280; font-weight: 500; margin: 0.25rem 0 0;">+62 812-3456-7890</p>
                <div style="display: flex; gap: 0.5rem; margin-top: 0.75rem;">
                    <span style="padding: 0.25rem 0.625rem; background: #f3f4f6; color: #4b5563; font-size: 0.625rem; border-radius: 0.375rem; font-weight: 500; border: 1px solid #e5e7eb;">Customer Baru</span>
                    <span style="padding: 0.25rem 0.625rem; background: #f0fdf4; color: #15803d; font-size: 0.625rem; border-radius: 0.375rem; font-weight: 500; border: 1px solid #bbf7d0;">VIP Prospect</span>
                </div>
            </div>

            <!-- AI Summary Block -->
            <div style="margin-top: 1rem; background: rgba(239, 246, 255, 0.5); border-radius: 0.75rem; border: 1px solid #dbeafe; padding: 0.75rem; position: relative; overflow: hidden;">
                <div style="position: absolute; top: 0; right: 0; padding: 0.375rem; opacity: 0.2;">
                    <svg style="width: 4rem; height: 4rem; color: #3b82f6;" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2a10 10 0 100 20 10 10 0 000-20zM9 13a1 1 0 110-2 1 1 0 010 2zm6 0a1 1 0 110-2 1 1 0 010 2z"></path></svg>
                </div>
                <div style="display: flex; align-items: center; gap: 0.375rem; margin-bottom: 0.5rem; position: relative; z-index: 10;">
                    <span style="font-size: 1.125rem;">✨</span>
                    <h4 style="font-weight: 700; color: #1e40af; font-size: 0.875rem; margin: 0;">AI Chat Summary</h4>
                </div>
                <div style="position: relative; z-index: 10; font-size: 0.75rem; display: flex; flex-direction: column; gap: 0.5rem;">
                    <div style="display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 0.25rem;">
                        <span style="color: #6b7280; font-weight: 500;">Intent:</span>
                        <span style="grid-column: span 2 / span 2; color: #1f2937; font-weight: 600;">Tanya Ketersediaan & Refund</span>
                    </div>
                    <div style="display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 0.25rem;">
                        <span style="color: #6b7280; font-weight: 500;">Produk:</span>
                        <span style="grid-column: span 2 / span 2; color: #1f2937;">Paket Tur Bali (15 Agustus)</span>
                    </div>
                    <div style="display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 0.25rem;">
                        <span style="color: #6b7280; font-weight: 500;">Qty:</span>
                        <span style="grid-column: span 2 / span 2; color: #1f2937;">4 Orang</span>
                    </div>
                    <div style="display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 0.25rem;">
                        <span style="color: #6b7280; font-weight: 500;">Action Req:</span>
                        <span style="grid-column: span 2 / span 2; color: #dc2626; font-weight: 700; background: #fee2e2; padding: 0 0.375rem; border-radius: 0.25rem; display: inline-block; width: max-content;">Check Diskon & Refund</span>
                    </div>
                </div>
                <button style="margin-top: 0.75rem; font-size: 0.625rem; font-weight: 600; color: #2563eb; width: 100%; text-align: center; background: #fff; border: 1px solid #bfdbfe; padding: 0.25rem; border-radius: 0.25rem; cursor: pointer; box-shadow: 0 1px 2px rgba(0,0,0,0.05);">Update Summary</button>
            </div>

            <!-- Quick Tools (CRM Data) -->
            <div style="margin-top: 1.25rem;">
                <h4 style="font-weight: 600; color: #1f2937; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.75rem;">CRM Context</h4>
                
                <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                    <div style="padding: 0.75rem; border: 1px solid #e5e7eb; border-radius: 0.5rem; background: #f9fafb;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.25rem;">
                            <span style="font-weight: 600; color: #374151; font-size: 0.75rem;">Previous Order</span>
                            <span style="font-size: 0.625rem; color: #6b7280;">2 months ago</span>
                        </div>
                        <p style="font-size: 0.75rem; color: #4b5563; margin: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">Tur Bromo (2 Pax)</p>
                        <a href="#" style="font-size: 0.625rem; color: #2563eb; font-weight: 500; text-decoration: none; margin-top: 0.25rem; display: block;">View Booking #INV-8821</a>
                    </div>
                    
                    <div style="padding: 0.75rem; border: 1px solid #e5e7eb; border-radius: 0.5rem; cursor: pointer; position: relative; overflow: hidden; display: flex; align-items: center; gap: 0.5rem; background: #fff;">
                        <svg style="width: 1rem; height: 1rem; color: #2563eb;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                        <span style="font-size: 0.75rem; font-weight: 600; color: #1d4ed8;">Create New Booking (Draft)</span>
                    </div>
                </div>
            </div>

            <!-- Notes -->
            <div style="margin-top: 1.25rem; flex: 1; display: flex; flex-direction: column;">
                <h4 style="font-weight: 600; color: #1f2937; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem;">Agent Notes</h4>
                <textarea style="width: 100%; flex: 1; min-height: 100px; border: 1px solid #e5e7eb; border-radius: 0.5rem; background: rgba(254, 252, 232, 0.5); padding: 0.75rem; font-size: 0.75rem; color: #374151; outline: none; resize: none; font-family: inherit;" placeholder="Add private notes here (not visible to customer)..."></textarea>
            </div>
        </div>
    </div>
</x-filament-panels::page>
