<div x-data="aiAssistant()" 
     class="fixed z-[100]"
     :style="`left: ${pos.x}px; top: ${pos.y}px; right: auto; bottom: auto;`"
     @keydown.escape.window="isOpen = false">
    
    <!-- Floating Button -->
    <button @mousedown="startDrag($event)" 
            @touchstart="startDrag($event)"
            @click.prevent="buttonClick()"
            class="w-14 h-14 rounded-full bg-gradient-to-br from-purple-600 to-blue-600 text-white shadow-lg shadow-purple-500/30 hover:shadow-purple-500/50 hover:scale-110 transition-all duration-300 flex items-center justify-center relative group touch-none cursor-move"
            :class="isDragging ? 'scale-110 !shadow-xl !shadow-purple-500/40 opacity-90' : 'active:scale-95'">
        <template x-if="!isOpen">
            <svg class="w-6 h-6 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
            </svg>
        </template>
        <template x-if="isOpen">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </template>
        
        <!-- Tooltip -->
        <span x-show="!isDragging" class="absolute right-full mr-4 px-3 py-1.5 bg-slate-900 text-white text-[11px] font-bold rounded-lg opacity-0 group-hover:opacity-100 pointer-events-none transition-opacity whitespace-nowrap uppercase tracking-widest border border-white/10">
            Ask AI Assistant
        </span>
    </button>

    <!-- Slide-in Panel -->
    <template x-teleport="body">
        <div x-show="isOpen" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="translate-x-full"
             x-transition:enter-end="translate-x-0"
             x-transition:leave="transition ease-in duration-300"
             x-transition:leave-start="translate-x-0"
             x-transition:leave-end="translate-x-full"
             class="fixed top-0 right-0 h-full w-full sm:w-[400px] z-[110] bg-slate-950/40 backdrop-blur-2xl border-l border-white/10 shadow-2xl flex flex-col overflow-hidden"
             x-cloak>
            
            <!-- Header -->
            <div class="p-6 border-b border-white/10 flex items-center justify-between bg-white/[0.02]">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-purple-600 to-blue-600 flex items-center justify-center shadow-lg shadow-purple-500/20">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-white font-black uppercase tracking-widest text-sm">AI Assistant</h3>
                        <div class="flex items-center gap-1.5 mt-0.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter">Neural Network Online</span>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <!-- Clear History Button -->
                    <button @click="confirmClearHistory()" 
                            class="p-2 rounded-lg text-slate-400 hover:text-red-400 hover:bg-red-400/10 transition-all"
                            title="Clear Chat History"
                            x-show="messages.length > 0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </button>
                    
                    <button @click="isOpen = false" class="p-2 rounded-lg text-slate-400 hover:text-white hover:bg-white/10 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
            </div>

            <!-- Chat Area -->
            <div x-ref="chatArea" class="flex-1 overflow-y-auto p-6 flex flex-col gap-8 custom-scrollbar scroll-smooth">
                <template x-for="(msg, index) in messages" :key="index">
                    <div :class="msg.role === 'user' ? 'flex justify-end' : 'flex justify-start'">
                        <div :class="msg.role === 'user' 
                                    ? 'bg-blue-600 text-white rounded-2xl rounded-tr-none' 
                                    : 'bg-white/5 border border-white/10 text-slate-200 rounded-2xl rounded-tl-none'"
                             class="max-w-[85%] p-4 shadow-sm group relative">
                            <div class="chat-content prose prose-invert max-w-none text-[14px] leading-relaxed font-medium" x-html="renderMarkdown(msg.message)"></div>
                            <div class="flex items-center justify-between mt-2">
                                <span class="block text-[9px] font-bold opacity-40 uppercase tracking-tighter" 
                                      x-text="formatTime(msg.created_at)"></span>
                                
                                <!-- Copy Button for AI Messages -->
                                <template x-if="msg.role === 'assistant'">
                                    <button @click="copyToClipboard(msg.message, $event)" 
                                            class="p-1 rounded bg-white/5 hover:bg-white/10 text-slate-400 hover:text-white transition-all opacity-0 group-hover:opacity-100 focus:opacity-100"
                                            title="Copy message">
                                        <svg x-show="!msg.copied" class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"></path></svg>
                                        <svg x-show="msg.copied" x-cloak class="w-3 h-3 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                    </button>
                                </template>
                            </div>
                        </div>
                    </div>
                </template>

                <!-- Loading / Typing Indicator -->
                <div x-show="isTyping" class="flex justify-start">
                    <div class="bg-white/5 border border-white/10 p-4 rounded-2xl rounded-tl-none">
                        <div class="flex gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-bounce"></span>
                            <span class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-bounce [animation-delay:-0.15s]"></span>
                            <span class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-bounce [animation-delay:-0.3s]"></span>
                        </div>
                    </div>
                </div>

                <div x-show="messages.length === 0 && !isTyping" class="h-full flex flex-col items-center justify-center text-center opacity-40 py-12">
                    <div class="w-16 h-16 rounded-full border-2 border-dashed border-white/20 mb-4 flex items-center justify-center">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                    </div>
                    <p class="text-sm font-bold uppercase tracking-widest text-white">No Transmission Logs</p>
                    <p class="text-[10px] mt-1">Initiate protocol to begin assistance</p>
                </div>
            </div>

            <!-- Input Area -->
            <div class="p-6 bg-white/[0.02] border-t border-white/10">
                <form @submit.prevent="sendMessage()" class="relative">
                    <input type="text" 
                           x-model="newMessage"
                           placeholder="Enter protocol command..."
                           class="w-full h-14 bg-white/5 border border-white/10 rounded-2xl px-5 pr-14 text-[14px] font-medium text-white placeholder:text-slate-500 focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 transition-all"
                           :disabled="isTyping">
                    
                    <button type="submit" 
                            class="absolute right-2 top-2 w-10 h-10 rounded-xl bg-gradient-to-br from-purple-600 to-blue-600 text-white flex items-center justify-center shadow-lg hover:scale-105 active:scale-95 transition-all disabled:opacity-50 disabled:grayscale"
                            :disabled="!newMessage.trim() || isTyping">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                    </button>
                </form>
                <p class="text-[9px] font-bold text-slate-500 uppercase tracking-widest mt-4 text-center opacity-50">Secure Channel — End-to-End Encrypted</p>
            </div>
        </div>
    </template>
</div>

<script>
function aiAssistant() {
    return {
        isOpen: false,
        isTyping: false,
        messages: [],
        newMessage: '',

        pos: { x: window.innerWidth - 80, y: window.innerHeight - 80 },
        isDragging: false,
        dragStartPos: { x: 0, y: 0 },
        dragOffset: { x: 0, y: 0 },
        wasDragged: false,
        
        renderMarkdown(text) {
            if (typeof marked === 'undefined') return text;
            return marked.parse(text);
        },
        
        async init() {
            this.$watch('isOpen', value => {
                if (value && this.messages.length === 0) {
                    this.fetchHistory();
                }
                if (value) {
                    this.$nextTick(() => this.scrollToBottom());
                }
            });

            // Set initial position immediately if window is available
            if (window.innerWidth) {
                this.pos.x = window.innerWidth - 80;
                this.pos.y = window.innerHeight - 80;
            }
            
            window.addEventListener('resize', () => {
                if (!this.wasDragged) {
                    this.pos.x = window.innerWidth - 80;
                    this.pos.y = window.innerHeight - 80;
                } else {
                    this.pos.x = Math.max(0, Math.min(this.pos.x, window.innerWidth - 60));
                    this.pos.y = Math.max(0, Math.min(this.pos.y, window.innerHeight - 60));
                }
            });

            // Drag event listeners globally to catch movements outside the button
            document.addEventListener('touchmove', (e) => {
                if (this.isDragging) {
                    e.preventDefault();
                    this.onDrag(e, true);
                }
            }, { passive: false });
            
            document.addEventListener('mousemove', (e) => {
                if (this.isDragging) {
                    this.onDrag(e, false);
                }
            });

            const endDrag = () => { 
                // Slight delay to distinguish mouseup from click
                setTimeout(() => { this.isDragging = false; }, 50); 
            };
            document.addEventListener('touchend', endDrag);
            document.addEventListener('mouseup', endDrag);
        },

        startDrag(e) {
            if (this.isOpen) return; // Disallow dragging if panel is open 
            this.isDragging = true;
            this.wasDragged = false;
            
            const clientX = e.type.includes('mouse') ? e.clientX : e.touches[0].clientX;
            const clientY = e.type.includes('mouse') ? e.clientY : e.touches[0].clientY;
            
            this.dragStartPos.x = clientX;
            this.dragStartPos.y = clientY;
            
            this.dragOffset.x = clientX - this.pos.x;
            this.dragOffset.y = clientY - this.pos.y;
        },

        onDrag(e, isTouch = false) {
            if (!this.isDragging) return;
            
            const clientX = isTouch ? e.touches[0].clientX : e.clientX;
            const clientY = isTouch ? e.touches[0].clientY : e.clientY;
            
            // Allow a small threshold to differentiate clicks from drags
            if (Math.abs(clientX - this.dragStartPos.x) > 5 || Math.abs(clientY - this.dragStartPos.y) > 5) {
                this.wasDragged = true;
            }

            if (this.wasDragged) {
                let newX = clientX - this.dragOffset.x;
                let newY = clientY - this.dragOffset.y;

                // Restrict boundaries
                this.pos.x = Math.max(0, Math.min(newX, window.innerWidth - 60));
                this.pos.y = Math.max(0, Math.min(newY, window.innerHeight - 60));
            }
        },

        buttonClick() {
            if (!this.wasDragged) {
                this.togglePanel();
            }
        },

        togglePanel() {
            this.isOpen = !this.isOpen;
        },

        async fetchHistory() {
            try {
                const response = await fetch('/dashboard/api/chat/history');
                const data = await response.json();
                this.messages = data;
                this.$nextTick(() => this.scrollToBottom());
            } catch (error) {
                console.error('Failed to fetch chat history:', error);
            }
        },

        confirmClearHistory() {
            Swal.fire({
                title: 'Hapus Riwayat Chat?',
                text: "Semua percakapan Anda dengan asisten AI akan dihapus permanen.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Hapus Semua',
                cancelButtonText: 'Batal',
                background: document.documentElement.classList.contains('dark') ? '#1e293b' : '#ffffff',
                color: document.documentElement.classList.contains('dark') ? '#f1f5f9' : '#0f172a',
            }).then((result) => {
                if (result.isConfirmed) {
                    this.clearHistory();
                }
            });
        },

        async clearHistory() {
            try {
                const response = await fetch('/dashboard/api/chat/history', {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                if (response.ok) {
                    this.messages = [];
                    Swal.fire({
                        title: 'Terhapus!',
                        text: 'Riwayat chat telah dibersihkan.',
                        icon: 'success',
                        timer: 1500,
                        showConfirmButton: false,
                        background: document.documentElement.classList.contains('dark') ? '#1e293b' : '#ffffff',
                        color: document.documentElement.classList.contains('dark') ? '#f1f5f9' : '#0f172a',
                    });
                }
            } catch (error) {
                console.error('Failed to clear history:', error);
            }
        },

        async sendMessage() {
            if (!this.newMessage.trim() || this.isTyping) return;

            const text = this.newMessage;
            this.newMessage = '';
            
            // Add user message to UI immediately
            this.messages.push({
                role: 'user',
                message: text,
                created_at: new Date().toISOString()
            });
            
            this.isTyping = true;
            this.$nextTick(() => this.scrollToBottom());

            try {
                const response = await fetch('/dashboard/api/chat', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ message: text })
                });

                if (!response.ok) {
                    const errorData = await response.json().catch(() => ({}));
                    throw new Error(errorData.message || 'API Error');
                }

                const data = await response.json();
                this.messages.push(data);
            } catch (error) {
                console.error('AI Assistant Error:', error);
                let displayError = error.message;
                if (displayError.includes('quota') || displayError.includes('Rate limit')) {
                    displayError = 'Maaf, kuota asisten AI sudah habis atau sedang sibuk. Silakan coba lagi nanti.';
                }
                
                this.messages.push({
                    role: 'assistant',
                    message: 'SYSTEM ERROR: ' + displayError,
                    created_at: new Date().toISOString()
                });
            } finally {
                this.isTyping = false;
                this.$nextTick(() => this.scrollToBottom());
            }
        },

        scrollToBottom() {
            const chat = this.$refs.chatArea;
            if (chat) {
                chat.scrollTop = chat.scrollHeight;
            }
        },

        formatTime(dateString) {
            const date = new Date(dateString);
            return date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
        },

        async copyToClipboard(text, event) {
            try {
                await navigator.clipboard.writeText(text);
                
                // Find message object and show temporary success state
                const msg = this.messages.find(m => m.message === text);
                if (msg) {
                    msg.copied = true;
                    setTimeout(() => msg.copied = false, 2000);
                }
            } catch (err) {
                console.error('Failed to copy text: ', err);
            }
        }
    }
}
</script>

<style>
    /* Markdown Styles in Chat */
    .chat-content p { margin-bottom: 0.75rem; }
    .chat-content p:last-child { margin-bottom: 0; }
    .chat-content ul, .chat-content ol { margin-left: 1.25rem; margin-bottom: 0.75rem; list-style-type: disc !important; }
    .chat-content ol { list-style-type: decimal !important; }
    .chat-content li { margin-bottom: 0.25rem; }
    .chat-content strong { font-weight: 800; color: #fff; }
    .chat-content code { background: rgba(255,255,255,0.1); padding: 0.2rem 0.4rem; border-radius: 0.4rem; font-family: monospace; font-size: 0.85em; }
    .chat-content pre { background: rgba(0,0,0,0.3); padding: 1rem; border-radius: 0.75rem; margin-bottom: 0.75rem; overflow-x: auto; border: 1px solid rgba(255,255,255,0.1); }
    .chat-content a { color: #60a5fa; text-decoration: underline; }
</style>
