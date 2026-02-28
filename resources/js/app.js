import './bootstrap';
import './echo';

import * as Turbo from '@hotwired/turbo';
import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';
import voiceStore from './stores/voiceStore';

window.Alpine = Alpine;
Alpine.plugin(collapse);
Alpine.store('voice', voiceStore);
Alpine.start();

// Inisialisasi store voice agar restore session & Echo berjalan otomatis di seluruh app
Alpine.store('voice').init();

// Optional: Debug Turbo
// document.addEventListener("turbo:click", () => console.log("Turbo Click"));
