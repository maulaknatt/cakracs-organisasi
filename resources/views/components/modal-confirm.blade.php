@props(['id' => 'modal-confirm', 'title' => 'Konfirmasi', 'message' => 'Yakin ingin menghapus data ini?', 'confirmText' => 'Hapus', 'cancelText' => 'Batal'])
<div x-data="{ open: false, form: null }" 
     x-show="open" 
     x-cloak
     x-transition:enter="transition ease-out duration-200"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-150"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     id="{{ $id }}" 
     class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm"
     @click.self="open = false"
     @keydown.escape.window="open = false"
     style="display: none;">
    <div x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="bg-white dark:bg-slate-800 rounded-xl shadow-2xl border border-slate-200 dark:border-slate-700 p-4 w-full max-w-md">
        <div class="flex items-start gap-4 mb-4">
            <div class="flex-shrink-0 h-8 w-8 rounded-full bg-red-500/20 flex items-center justify-center">
                <svg class="h-4 w-4 text-red-600 dark:text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <div class="flex-1">
                <h2 class="text-lg font-semibold text-slate-900 dark:text-white mb-2">{{ $title }}</h2>
                <p class="text-sm text-slate-600 dark:text-slate-400">{{ $message }}</p>
            </div>
        </div>
        <div class="flex justify-end gap-3 pt-4 border-t border-slate-200 dark:border-slate-700">
            <button @click="open = false" class="px-4 py-2 text-sm font-medium text-slate-700 dark:text-slate-300 bg-slate-100 dark:bg-slate-700 rounded-lg hover:bg-slate-200 dark:hover:bg-slate-600 transition-colors">
                {{ $cancelText }}
            </button>
            <button @click="if(form) { form.submit(); } open = false;" class="px-4 py-2 text-sm font-medium text-white bg-red-600 dark:bg-red-500 rounded-lg hover:bg-red-700 dark:hover:bg-red-600 transition-colors">
                {{ $confirmText }}
            </button>
        </div>
    </div>
</div>
<script>
    // Simple and reliable modal confirm function
    window.showModalConfirm = function(form) {
        if (!form) {
            console.error('Form tidak ditemukan');
            return;
        }
        
        const modalId = '{{ $id }}';
        const modal = document.getElementById(modalId);
        
        if (!modal) {
            console.error('Modal dengan ID "' + modalId + '" tidak ditemukan');
            // Fallback ke confirm browser
            if (confirm('{{ $message }}')) {
                form.submit();
            }
            return;
        }
        
        // Access Alpine data
        let alpineData = null;
        
        // Try to get Alpine data - multiple methods
        if (modal.__x && modal.__x.$data) {
            alpineData = modal.__x.$data;
        } else if (typeof Alpine !== 'undefined') {
            try {
                if (Alpine.$data) {
                    alpineData = Alpine.$data(modal);
                } else if (Alpine.store && Alpine.store('modal')) {
                    alpineData = Alpine.store('modal');
                }
            } catch(e) {
                console.warn('Error accessing Alpine data:', e);
            }
        }
        
        if (alpineData) {
            alpineData.form = form;
            alpineData.open = true;
        } else {
            // Fallback: use browser confirm
            console.warn('Alpine data tidak ditemukan, menggunakan browser confirm');
            if (confirm('{{ $message }}')) {
                form.submit();
            }
        }
    };
</script>
