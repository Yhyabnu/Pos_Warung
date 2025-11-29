@script
<script>
    document.addEventListener('livewire:initialized', () => {
        const searchInput = document.querySelector('input[wire\\:model="kodeBarangInput"]');
        
        // Auto focus ke search input
        if (searchInput) {
            searchInput.focus();
            
            Livewire.on('clear-search', () => {
                searchInput.value = '';
                searchInput.focus();
            });
        }

        // Enhanced Keyboard Shortcuts
        document.addEventListener('keydown', (e) => {
            // Ctrl + B untuk toggle bulk input
            if (e.ctrlKey && e.key === 'b') {
                e.preventDefault();
                @this.toggleBulkInput();
            }
            
            // Ctrl + R untuk reset keranjang
            if (e.ctrlKey && e.key === 'r') {
                e.preventDefault();
                if (confirm('Yakin ingin mengosongkan keranjang?')) {
                    @this.resetKeranjang();
                }
            }
            
            // Escape untuk clear search
            if (e.key === 'Escape') {
                if (searchInput) {
                    searchInput.value = '';
                    searchInput.focus();
                }
            }
            
            // F2 untuk focus ke uang dibayar - PERBAIKI SELECTOR INI
            if (e.key === 'F2') {
                e.preventDefault();
                const paymentInput = document.querySelector('input[wire\\:model="uangDibayar"]');
                if (paymentInput) {
                    paymentInput.focus();
                    paymentInput.select();
                }
            }

            // F1 untuk focus ke search - PERBAIKI SELECTOR INI
            if (e.key === 'F1') {
                e.preventDefault();
                if (searchInput) {
                    searchInput.focus();
                    searchInput.select();
                }
            }
        });

        // Smooth animations untuk bulk input
        Livewire.on('bulk-input-toggled', () => {
            const bulkSection = document.querySelector('[wire\\:key="bulk-input-section"]');
            if (bulkSection) {
                setTimeout(() => {
                    bulkSection.scrollIntoView({ 
                        behavior: 'smooth', 
                        block: 'start' 
                    });
                }, 100);
            }
        });

        // Auto calculate when cart changes
        Livewire.on('refresh-cart', () => {
            // Trigger calculation updates
            console.log('Cart updated, recalculating...');
        });

        // Loading states management
        Livewire.on('processing-started', () => {
            document.body.style.cursor = 'wait';
        });

        Livewire.on('processing-finished', () => {
            document.body.style.cursor = 'default';
        });
    });

    // CSS Animations untuk smooth transitions
    const style = document.createElement('style');
    style.textContent = `
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in {
            animation: fadeIn 0.3s ease-out;
        }
        
        @keyframes slideIn {
            from { opacity: 0; transform: translateX(-20px); }
            to { opacity: 1; transform: translateX(0); }
        }
        .animate-slide-in {
            animation: slideIn 0.2s ease-out;
        }
        
        .resize-vertical {
            resize: vertical;
        }
        
        .line-clamp-1 {
            display: -webkit-box;
            -webkit-line-clamp: 1;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        /* Smooth transitions for all interactive elements */
        button, input, select, textarea {
            transition: all 0.2s ease-in-out;
        }
        
        /* Loading animation */
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
        .animate-pulse {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        /* Pastikan tombol selalu terlihat */
        button:disabled {
            opacity: 0.6 !important;
            cursor: not-allowed !important;
        }
    `;
    document.head.appendChild(style);

    // Utility functions - PERBAIKI SELECTOR DI SINI JUGA
    window.kasirUtils = {
        focusSearch() {
            const searchInput = document.querySelector('input[wire\\:model="kodeBarangInput"]');
            if (searchInput) {
                searchInput.focus();
                searchInput.select();
            }
        },
        
        focusPayment() {
            const paymentInput = document.querySelector('input[wire\\:model="uangDibayar"]');
            if (paymentInput) {
                paymentInput.focus();
                paymentInput.select();
            }
        },
        
        quickAdd(productCode, quantity = 1) {
            @this.cariProduk(productCode);
        }
    };
</script>
@endscript