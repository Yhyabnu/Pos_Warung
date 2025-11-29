<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Transaksi {{ $transaksi->kode_transaksi }}</title>
    <!-- Tambahkan library JsBarcode -->
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
    <style>
        /* Reset dan base style */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Style khusus untuk printer */
        @media print {
            @page {
                size: 80mm 297mm;
                margin: 2mm;
                padding: 0;
            }
            
            body {
                margin: 0;
                padding: 0;
                font-family: 'Courier New', monospace, 'Arial Narrow';
                font-size: 11px;
                width: 76mm;
                line-height: 1.2;
            }
            
            .no-print {
                display: none !important;
            }
            
            .struk-container {
                width: 76mm;
                padding: 8px;
                box-sizing: border-box;
            }

            /* Optimasi barcode untuk printer thermal */
            .barcode-svg {
                filter: contrast(1.5) !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
        }
        
        @media screen {
            .struk-container {
                width: 76mm;
                margin: 20px auto;
                padding: 15px;
                border: 1px solid #ccc;
                font-family: 'Courier New', monospace;
                font-size: 11px;
                background: white;
                line-height: 1.2;
            }
        }
        
        /* Header Struk */
        .struk-header {
            text-align: center;
            margin-bottom: 8px;
            padding-bottom: 8px;
            border-bottom: 1px dashed #000;
        }
        
        .company-name {
            font-weight: bold;
            font-size: 13px;
            margin-bottom: 3px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .company-address {
            font-size: 9px;
            margin-bottom: 3px;
            line-height: 1.1;
        }
        
        /* Info Transaksi */
        .transaction-info {
            margin-bottom: 8px;
            padding-bottom: 8px;
            border-bottom: 1px dashed #000;
        }
        
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 2px;
        }
        
        /* Tabel Items */
        .items-table {
            width: 100%;
            margin-bottom: 8px;
            border-bottom: 1px dashed #000;
            padding-bottom: 8px;
        }
        
        .table-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 4px;
            padding-bottom: 3px;
            border-bottom: 1px solid #000;
            font-weight: bold;
        }
        
        .item-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 3px;
            padding-bottom: 2px;
        }
        
        .col-item {
            flex: 3;
            word-break: break-word;
        }
        
        .col-qty {
            flex: 1;
            text-align: center;
            min-width: 20px;
        }
        
        .col-price {
            flex: 2;
            text-align: right;
            min-width: 40px;
        }
        
        .col-total {
            flex: 2;
            text-align: right;
            min-width: 45px;
        }
        
        /* Summary dan Pembayaran */
        .summary-section {
            margin-bottom: 8px;
            padding-bottom: 8px;
            border-bottom: 1px dashed #000;
        }
        
        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 2px;
        }
        
        .total-row {
            font-weight: bold;
            font-size: 12px;
            margin-top: 3px;
            padding-top: 3px;
            border-top: 1px solid #000;
        }
        
        .highlight {
            font-weight: bold;
            background: #f0f0f0;
            padding: 2px 4px;
            border-radius: 2px;
        }
        
        /* Footer */
        .footer {
            text-align: center;
            font-size: 9px;
            margin-top: 10px;
            line-height: 1.1;
        }
        
        /* Style untuk barcode container */
        .barcode-container {
            text-align: center;
            margin: 6px 0;
            padding: 4px;
        }
        
        .barcode-svg {
            width: 100%;
            max-width: 200px;
            height: auto;
        }
        
        .barcode-text {
            font-family: 'Courier New', monospace;
            font-size: 10px;
            margin-top: 3px;
            font-weight: bold;
        }
        
        /* Print Buttons */
        .print-buttons {
            text-align: center;
            margin: 15px 0;
            padding: 10px;
        }
        
        .btn {
            padding: 8px 16px;
            margin: 0 5px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
            font-family: Arial, sans-serif;
        }
        
        .btn-print {
            background: #28a745;
            color: white;
        }
        
        .btn-back {
            background: #6c757d;
            color: white;
        }
        
        .btn-close {
            background: #dc3545;
            color: white;
        }
        
        /* Utility Classes */
        .text-center {
            text-align: center;
        }
        
        .text-right {
            text-align: right;
        }
        
        .dashed-line {
            border-bottom: 1px dashed #000;
            margin: 6px 0;
        }
        
        .spacer {
            height: 5px;
        }
        
        /* Styling untuk jumlah besar */
        .large-text {
            font-size: 12px;
        }
        
        /* Garis pemisah */
        .separator {
            border-bottom: 1px solid #000;
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <div class="print-buttons no-print">
        <button onclick="window.print()" class="btn btn-print">🖨️ Cetak Struk</button>
        <button onclick="window.history.back()" class="btn btn-back">← Kembali</button>
        <button onclick="window.close()" class="btn btn-close">❌ Tutup</button>
        <div style="margin-top: 8px; font-size: 10px; color: #666;">
            Shortcut: Ctrl+P (Cetak) • ESC (Kembali)
        </div>
    </div>

    <div class="struk-container">
        <!-- Header Perusahaan -->
        <div class="struk-header">
            <div class="company-name">Warung PKK</div>
            <div class="company-address">
                Jl. Adityatama, Kota Baru Selatan<br>
                Martapura, OKU Timur<br>
                Telp: (021) 123-4567
            </div>
        </div>
        
        <!-- Info Transaksi -->
        <div class="transaction-info">
            <div class="info-row">
                <span>No. Transaksi:</span>
                <span class="highlight">{{ $transaksi->kode_transaksi }}</span>
            </div>
            <div class="info-row">
                <span>Tanggal/Waktu:</span>
                <span>{{ $transaksi->created_at->format('d/m/Y H:i:s') }}</span>
            </div>
            <div class="info-row">
                <span>Kasir:</span>
                <span>{{ $transaksi->user->name }}</span>
            </div>
            @if($transaksi->nama_pelanggan)
            <div class="info-row">
                <span>Pelanggan:</span>
                <span>{{ $transaksi->nama_pelanggan }}</span>
            </div>
            @endif
        </div>
        
        <div class="separator"></div>
        
        <!-- Daftar Item -->
        <div class="items-table">
            <div class="table-header">
                <div class="col-item">ITEM</div>
                <div class="col-qty">QTY</div>
                <div class="col-price">HARGA</div>
                <div class="col-total">SUBTOTAL</div>
            </div>
            
            @foreach($transaksi->details as $item)
            <div class="item-row">
                <div class="col-item">{{ Str::limit($item->produk->nama ?? $item->nama_produk, 20) }}</div>
                <div class="col-qty">{{ $item->jumlah }}x</div>
                <div class="col-price">{{ number_format($item->harga_satuan, 0, ',', '.') }}</div>
                <div class="col-total">{{ number_format($item->subtotal, 0, ',', '.') }}</div>
            </div>
            @endforeach
        </div>
        
        <div class="separator"></div>
        
        <!-- Summary -->
        <div class="summary-section">
            <div class="summary-row">
                <span>Subtotal:</span>
                <span>Rp {{ number_format($transaksi->subtotal, 0, ',', '.') }}</span>
            </div>
            @if($transaksi->diskon > 0)
            <div class="summary-row">
                <span>Diskon:</span>
                <span>- Rp {{ number_format($transaksi->diskon, 0, ',', '.') }}</span>
            </div>
            @endif
            @if($transaksi->pajak > 0)
            <div class="summary-row">
                <span>Pajak ({{ $transaksi->pajak / $transaksi->subtotal * 100 }}%):</span>
                <span>Rp {{ number_format($transaksi->pajak, 0, ',', '.') }}</span>
            </div>
            @endif
            <div class="summary-row total-row large-text">
                <span>TOTAL:</span>
                <span>Rp {{ number_format($transaksi->total, 0, ',', '.') }}</span>
            </div>
        </div>
        
        <!-- Pembayaran -->
        <div class="summary-section">
            <div class="summary-row">
                <span>Bayar ({{ strtoupper($transaksi->metode_pembayaran) }}):</span>
                <span>Rp {{ number_format($transaksi->uang_dibayar, 0, ',', '.') }}</span>
            </div>
            <div class="summary-row total-row large-text">
                <span>KEMBALI:</span>
                <span>Rp {{ number_format($transaksi->kembalian, 0, ',', '.') }}</span>
            </div>
        </div>
        
        <!-- Barcode -->
        <div class="barcode-container">
            <svg class="barcode-svg"
                 jsbarcode-format="CODE128"
                 jsbarcode-value="{{ $transaksi->kode_transaksi }}"
                 jsbarcode-textmargin="0"
                 jsbarcode-fontoptions="bold"
                 jsbarcode-font="Courier"
                 jsbarcode-height="30"
                 jsbarcode-width="1.5"
                 jsbarcode-displayvalue="true">
            </svg>
            <div class="barcode-text">{{ $transaksi->kode_transaksi }}</div>
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <div class="dashed-line"></div>
            <div style="margin: 4px 0;">** TERIMA KASIH **</div>
            <div>Barang yang sudah dibeli</div>
            <div>tidak dapat ditukar/dikembalikan</div>
            <div style="margin-top: 6px; font-style: italic;">
                "Selamat berbelanja kembali"
            </div>
        </div>
    </div>

    <script>
        // Fungsi untuk generate barcode
        function generateBarcode() {
            try {
                JsBarcode(".barcode-svg", "{{ $transaksi->kode_transaksi }}", {
                    format: "CODE128",
                    width: 1.5,
                    height: 30,
                    displayValue: true,
                    textMargin: 0,
                    fontOptions: "bold",
                    font: "Courier",
                    fontSize: 10,
                    margin: 0
                });
            } catch (error) {
                console.error('Error generating barcode:', error);
                // Fallback jika barcode gagal
                document.querySelector('.barcode-container').innerHTML = `
                    <div style="border: 1px dashed #ccc; padding: 10px; text-align: center;">
                        <div style="font-weight: bold; margin-bottom: 5px;">{{ $transaksi->kode_transaksi }}</div>
                        <div style="font-size: 8px; color: #666;">[Barcode tidak tersedia]</div>
                    </div>
                `;
            }
        }

        // Auto print jika diinginkan
        @if(request()->has('auto_print'))
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 500);
        }
        @endif
        
        // Handle setelah print
        window.onafterprint = function() {
            // Optional: Auto close setelah 2 detik
            setTimeout(function() {
                // window.close(); // Uncomment jika ingin auto close
            }, 2000);
        };
        
        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // Ctrl+P untuk print
            if ((e.ctrlKey || e.metaKey) && e.key === 'p') {
                e.preventDefault();
                window.print();
            }
            // ESC untuk kembali
            if (e.key === 'Escape') {
                window.history.back();
            }
            // Ctrl+W untuk tutup
            if ((e.ctrlKey || e.metaKey) && e.key === 'w') {
                e.preventDefault();
                window.close();
            }
        });

        // Optimasi untuk printer thermal
        document.addEventListener('DOMContentLoaded', function() {
            // Generate barcode saat halaman dimuat
            generateBarcode();
            
            // Force black and white untuk printer thermal
            const style = document.createElement('style');
            style.innerHTML = `
                @media print {
                    * {
                        color: black !important;
                        background: white !important;
                        -webkit-print-color-adjust: exact !important;
                        print-color-adjust: exact !important;
                    }
                    .barcode-svg {
                        filter: contrast(1.5) !important;
                    }
                }
            `;
            document.head.appendChild(style);
        });

        // Handle error barcode
        window.addEventListener('error', function(e) {
            if (e.target.tagName === 'svg') {
                console.log('Barcode error, using fallback');
                document.querySelector('.barcode-container').innerHTML = `
                    <div style="border: 1px dashed #ccc; padding: 10px; text-align: center;">
                        <div style="font-weight: bold; margin-bottom: 5px; font-size: 10px;">{{ $transaksi->kode_transaksi }}</div>
                        <div style="font-size: 8px; color: #666;">[Barcode Area]</div>
                    </div>
                `;
            }
        });
    </script>
</body>
</html>