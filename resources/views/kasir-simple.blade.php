<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kasir {{ config('app.name') }}</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f5f5;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
        }

        /* Header */
        .header {
            background: #1A3D64;
            color: white;
            padding: 15px 20px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header h1 {
            font-size: 24px;
            margin: 0;
        }

        .header-info {
            display: flex;
            gap: 20px;
            align-items: center;
        }

        .live-badge {
            background: #ff4444;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }

        /* Alerts */
        .alert {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 15px;
            border: none;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .alert-warning {
            background: #fff3cd;
            color: #856404;
            border: 1px solid #ffeaa7;
        }

        /* Main Layout */
        .main-layout {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 20px;
        }

        /* Left Section - Products */
        .products-section {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .search-bar {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }

        .search-bar input {
            flex: 1;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
        }

        .search-bar button {
            padding: 12px 24px;
            background: #ffff;
            color: ;
            border: 2px solid #333;
            border-radius: 10px;
            cursor: pointer;
            font-weight: 600;
        }

        .search-bar button:hover {
            background: #1A3D64;
            color: #fff;
        }

        /* Categories */
        .categories-container {
            margin-bottom: 20px;
        }

        .categories-title {
            margin-bottom: 10px;
            font-weight: 600;
            color: #333;
        }

        .categories {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .category-btn {
            padding: 8px 16px;
            background: #f0f0f0;
            border: 1px solid #ddd;
            border-radius: 20px;
            cursor: pointer;
            font-size: 13px;
            transition: all 0.2s;
            border: none;
        }

        .category-btn:hover, .category-btn.active {
            background: #1A3D64;
            color: white;
            border-color: #1A3D64;
        }

        /* Products Grid */
        .products-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #f0f0f0;
        }

        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 15px;
            max-height: 600px;
            overflow-y: auto;
            padding-right: 5px;
        }

        .product-card {
            background: #f9f9f9;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 15px;
            cursor: pointer;
            transition: all 0.2s;
            border: none;
            text-align: left;
            width: 100%;
        }

        .product-card:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            transform: translateY(-2px);
            border-color: #1A3D64;
        }

        .product-name {
            font-size: 15px;
            font-weight: 600;
            color: #333;
            margin-bottom: 5px;
        }

        .product-code {
            font-size: 12px;
            color: #888;
            margin-bottom: 10px;
        }

        .product-price {
            font-size: 16px;
            font-weight: bold;
            color: #1A3D64;
            margin-bottom: 8px;
        }

        .product-stock {
            font-size: 12px;
            color: #666;
            background: #e8e8e8;
            padding: 3px 8px;
            border-radius: 4px;
            display: inline-block;
        }

        .empty-products {
            text-align: center;
            padding: 60px 20px;
            color: #999;
        }

        /* Right Section - Cart */
        .cart-section {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            padding: 20px;
            max-height: 85vh;
            display: flex;
            flex-direction: column;
        }

        .cart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f0f0f0;
        }

        .cart-header h3 {
            margin: 0;
            font-size: 20px;
            color: #333;
        }

        .cart-badge {
            background: #1A3D64;
            color: white;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
        }

        .cart-total-top {
            background: #f0f9ff;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .cart-total-top strong {
            font-size: 20px;
            color: #1A3D64;
        }

        /* Empty Cart */
        .empty-cart {
            text-align: center;
            padding: 60px 20px;
            color: #999;
        }

        .empty-cart-icon {
            font-size: 64px;
            margin-bottom: 15px;
            opacity: 0.3;
        }

        .empty-cart h4 {
            color: #666;
            margin: 10px 0;
        }

        /* Cart Items Container */
        .cart-items-container {
            flex: 1;
            overflow-y: auto;
            margin-bottom: 15px;
            padding-right: 5px;
        }

        .cart-items-container::-webkit-scrollbar {
            width: 6px;
        }

        .cart-items-container::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .cart-items-container::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 10px;
        }

        /* Cart Item */
        .cart-item {
            background: #f9f9f9;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 12px;
            transition: all 0.2s;
        }

        .cart-item:hover {
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            border-color: #1A3D64;
        }

        .cart-item-info {
            margin-bottom: 12px;
        }

        .cart-product-name {
            font-size: 16px;
            font-weight: 600;
            color: #333;
            margin: 0 0 5px 0;
        }

        .cart-product-code {
            font-size: 12px;
            color: #888;
            background: #e8e8e8;
            padding: 2px 8px;
            border-radius: 4px;
            display: inline-block;
        }

        .price-per-unit {
            font-size: 13px;
            color: #666;
            margin-top: 5px;
        }

        /* Cart Item Controls */
        .cart-item-controls {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
        }

        .quantity-control {
            display: flex;
            align-items: center;
            gap: 8px;
            background: white;
            border: 1px solid #ddd;
            border-radius: 6px;
            padding: 4px;
        }

        .qty-btn {
            width: 30px;
            height: 30px;
            border: none;
            background: #1A3D64;
            color: white;
            border-radius: 4px;
            cursor: pointer;
            font-size: 18px;
            font-weight: bold;
            transition: background 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .qty-btn:hover:not(:disabled) {
            background: #45a049;
        }

        .qty-btn:disabled {
            background: #ccc;
            cursor: not-allowed;
        }

        .qty-display {
            min-width: 30px;
            text-align: center;
            font-weight: 600;
            font-size: 15px;
        }

        .item-subtotal {
            flex: 1;
            text-align: right;
        }

        .item-subtotal strong {
            color: #1A3D64;
            font-size: 16px;
        }

        .btn-remove {
            background: #ff4444;
            border: none;
            color: white;
            padding: 8px 12px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
            transition: background 0.2s;
        }

        .btn-remove:hover {
            background: #cc0000;
        }

        /* Cart Summary */
        .cart-summary {
            border-top: 2px solid #e0e0e0;
            padding-top: 15px;
            margin-bottom: 15px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 16px;
            padding: 8px 0;
        }

        .summary-row strong {
            color: #1A3D64;
            font-size: 20px;
        }

        /* Payment Section */
        .payment-section {
            background: #f9f9f9;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 15px;
        }

        .payment-section label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
        }

        .payment-section input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 16px;
            margin-bottom: 10px;
        }

        .change-info {
            padding: 10px;
            background: #e8f5e9;
            border-radius: 6px;
            text-align: center;
            font-weight: 600;
            color: #2e7d32;
        }

        .insufficient-info {
            padding: 10px;
            background: #ffebee;
            border-radius: 6px;
            text-align: center;
            font-weight: 600;
            color: #c62828;
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 10px;
        }

        .btn {
            flex: 1;
            padding: 14px;
            border: none;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-clear {
            background: #ff4444;
            color: white;
        }

        .btn-clear:hover {
            background: #cc0000;
        }

        .btn-process {
            background: #1A3D64;
            color: white;
        }

        .btn-process:hover {
            background: #45a049;
        }

        .btn:disabled {
            background: #ccc;
            cursor: not-allowed;
        }

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }

        .modal.show {
            display: flex;
        }

        .modal-content {
            background: white;
            padding: 30px;
            border-radius: 12px;
            text-align: center;
            max-width: 400px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.3);
        }

        .modal-content h3 {
            color: #1A3D64;
            margin-bottom: 15px;
        }

        .modal-buttons {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }

        .modal-buttons button {
            flex: 1;
            padding: 12px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .main-layout {
                grid-template-columns: 1fr;
            }

            .cart-section {
                max-height: none;
            }
        }

        @media (max-width: 768px) {
            .products-grid {
                grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            }

            .cart-item-controls {
                flex-wrap: wrap;
            }
            
            .item-subtotal {
                width: 100%;
                text-align: left;
                margin-top: 8px;
            }

            .header {
                flex-direction: column;
                gap: 10px;
                text-align: center;
            }

            .header-info {
                flex-wrap: wrap;
                justify-content: center;
            }
        }

        /* Product Forms */
        .product-form {
            margin: 0;
            padding: 0;
        }

        /* Custom badge styles */
        .badge-custom {
            background: #1A3D64;
            color: white;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>🏪 Kasir Warung PKK</h1>
        <div class="header-info">
            <span>{{ now()->format('d M Y, H:i') }}</span>
            <span class="live-badge">● Live</span>
            <span>👤 {{ auth()->user()->name }}</span>
            <form method="POST" action="{{ route('kasir.logout') }}" style="display: inline;">
                @csrf
                <button type="submit" style="background: transparent; border: 1px solid white; color: white; padding: 4px 8px; border-radius: 4px; cursor: pointer;">
                    🚪 Logout
                </button>
            </form>
        </div>
    </div>

    <div class="container">
        <!-- Alerts -->
        @if(session('success'))
            <div class="alert alert-success">
                <i class="bi bi-check-circle-fill me-2"></i>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">
                <i class="bi bi-exclamation-circle-fill me-2"></i>
                {{ session('error') }}
            </div>
        @endif

        @if(session('warning'))
            <div class="alert alert-warning">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                {{ session('warning') }}
            </div>
        @endif

        <!-- Main Layout -->
        <div class="main-layout">
            <!-- Left Section - Products -->
            <div class="products-section">
                <!-- Search Bar -->
                <form method="GET" action="{{ route('kasir.simple') }}" class="search-bar">
                    <input 
                        type="text" 
                        name="search"
                        value="{{ $searchQuery }}"
                        placeholder="🔍 Cari produk..."
                        autocomplete="off"
                    >
                    <button type="submit">Cari</button>
                </form>

                <!-- Categories -->
                <div class="categories-container">
                    <div class="categories-title">
                        Kategori Produk 
                        <span class="badge-custom">
                            @if(isset($categories) && $categories->count())
                                {{ $categories->count() }} kategori
                            @else
                                0 kategori
                            @endif
                        </span>
                    </div>
                    <div class="categories">
                        <button class="category-btn active" data-category="all">Semua</button>
                        @if(isset($categories) && $categories->count())
                            @foreach($categories as $category)
                            <button class="category-btn" data-category="{{ $category->id }}">{{ $category->nama }}</button>
                            @endforeach
                        @endif
                    </div>
                </div>

                <!-- Products List -->
                <div class="products-header">
                    <h3>📦 Daftar Produk</h3>
                    <span class="badge-custom">{{ $products->count() }} item</span>
                </div>

                @if($products->isEmpty())
                    <div class="empty-products">
                        <div style="font-size: 64px; opacity: 0.3;">📦</div>
                        <h4>Tidak ada produk ditemukan</h4>
                    </div>
                @else
                    <div class="products-grid" id="productsGrid">
                        @foreach($products as $product)
                            <form method="POST" action="{{ route('kasir.simple') }}" class="product-form" data-category="{{ $product->kategori_id }}">
                                @csrf
                                <input type="hidden" name="action" value="tambah_produk">
                                <input type="hidden" name="produk_id" value="{{ $product->id }}">
                                <input type="hidden" name="jumlah" value="1">
                                <button type="submit" class="product-card">
                                    <div class="product-name">{{ $product->nama }}</div>
                                    <div class="product-code">{{ $product->kode_barang }}</div>
                                    <div class="product-price">Rp {{ number_format($product->harga_jual, 0, ',', '.') }}</div>
                                    <div class="product-stock">📊 Stok: {{ $product->stok }}</div>
                                </button>
                            </form>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Right Section - Cart -->
            <div class="cart-section">
                <div class="cart-header">
                    <h3>🛒 Keranjang Belanja</h3>
                    <span class="cart-badge">{{ count($keranjang) }} item</span>
                </div>

                <div class="cart-total-top">
                    <span>Total</span>
                    <strong>Rp {{ number_format($totals['total'], 0, ',', '.') }}</strong>
                </div>

                @if(empty($keranjang))
                    <div class="empty-cart">
                        <div class="empty-cart-icon">🛒</div>
                        <h4>Keranjang Kosong</h4>
                        <p>Pilih produk untuk memulai transaksi</p>
                    </div>
                @else
                    <!-- Cart Items -->
                    <div class="cart-items-container">
                        @foreach($keranjang as $item)
                        <div class="cart-item">
                            <!-- Info Produk -->
                            <div class="cart-item-info">
                                <h4 class="cart-product-name">{{ $item['nama'] }}</h4>
                                <span class="cart-product-code">{{ $item['kode_barang'] }}</span>
                                <div class="price-per-unit">
                                    @ Rp {{ number_format($item['harga_jual'], 0, ',', '.') }}
                                </div>
                            </div>

                            <!-- Kontrol Quantity -->
                            <div class="cart-item-controls">
                                <div class="quantity-control">
                                    <form method="POST" action="{{ route('kasir.simple') }}" style="display: inline;">
                                        @csrf
                                        <input type="hidden" name="action" value="update_jumlah">
                                        <input type="hidden" name="produk_id" value="{{ $item['id'] }}">
                                        <input type="hidden" name="jumlah" value="{{ $item['jumlah'] - 1 }}">
                                        <button type="submit" class="qty-btn" {{ $item['jumlah'] <= 1 ? 'disabled' : '' }}>−</button>
                                    </form>

                                    <span class="qty-display">{{ $item['jumlah'] }}</span>

                                    <form method="POST" action="{{ route('kasir.simple') }}" style="display: inline;">
                                        @csrf
                                        <input type="hidden" name="action" value="update_jumlah">
                                        <input type="hidden" name="produk_id" value="{{ $item['id'] }}">
                                        <input type="hidden" name="jumlah" value="{{ $item['jumlah'] + 1 }}">
                                        <button type="submit" class="qty-btn" {{ $item['jumlah'] >= $item['stok'] ? 'disabled' : '' }}>+</button>
                                    </form>
                                </div>

                                <!-- Subtotal Item -->
                                <div class="item-subtotal">
                                    <strong>Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</strong>
                                </div>

                                <!-- Tombol Hapus -->
                                <form method="POST" action="{{ route('kasir.simple') }}">
                                    @csrf
                                    <input type="hidden" name="action" value="hapus_produk">
                                    <input type="hidden" name="produk_id" value="{{ $item['id'] }}">
                                    <button type="submit" class="btn-remove" title="Hapus item">🗑️</button>
                                </form>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Summary -->
                    <div class="cart-summary">
                        <div class="summary-row">
                            <span>Subtotal ({{ array_sum(array_column($keranjang, 'jumlah')) }} items)</span>
                            <strong>Rp {{ number_format($totals['total'], 0, ',', '.') }}</strong>
                        </div>
                    </div>

                    <!-- Payment Section -->
                    <form method="POST" action="{{ route('kasir.simple') }}" id="paymentForm">
                        @csrf
                        <input type="hidden" name="action" value="proses_transaksi">
                        <input type="hidden" name="metode_pembayaran" value="tunai">
                        
                        <div class="payment-section">
                            <label>💵 Uang Dibayar</label>
                            <input 
                                type="number" 
                                name="uang_dibayar"
                                value="{{ old('uang_dibayar', 0) }}"
                                placeholder="Masukkan jumlah uang" 
                                min="0" 
                                id="paymentInput"
                                autocomplete="off"
                            >
                            
                            @php
                                $uangDibayar = old('uang_dibayar', 0);
                                $kembalian = max(0, $uangDibayar - $totals['total']);
                            @endphp
                            
                            <div id="kembalianDisplay">
                                @if($kembalian > 0)
                                <div class="change-info">
                                    💰 Kembali: Rp {{ number_format($kembalian, 0, ',', '.') }}
                                </div>
                                @elseif($uangDibayar > 0 && $uangDibayar < $totals['total'])
                                <div class="insufficient-info">
                                    ⚠️ Kurang: Rp {{ number_format($totals['total'] - $uangDibayar, 0, ',', '.') }}
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="action-buttons">
                            <button type="submit" form="clearForm" class="btn btn-clear" {{ empty($keranjang) ? 'disabled' : '' }}>
                                🗑️ Kosongkan
                            </button>
                            <button type="submit" class="btn btn-process" id="processBtn"
                                    {{ empty($keranjang) || (old('uang_dibayar', 0) < $totals['total']) ? 'disabled' : '' }}>
                                ✓ Proses Transaksi
                            </button>
                        </div>
                    </form>

                    <!-- Hidden Clear Form -->
                    <form id="clearForm" action="{{ route('kasir.simple') }}" method="POST" style="display: none;">
                        @csrf
                        <input type="hidden" name="action" value="reset_keranjang">
                    </form>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal Print -->
    @if(session('show_print_modal') && session('last_transaction_id'))
    <div class="modal show" id="printModal">
        <div class="modal-content">
            <h3>✓ Transaksi Berhasil</h3>
            <p style="font-size: 18px; font-weight: 600; color: #1A3D64; margin: 15px 0;">
                {{ session('last_transaction_code') }}
            </p>
            <p>Struk siap dicetak</p>
            <div class="modal-buttons">
                <button onclick="cetakStruk({{ session('last_transaction_id') }})" style="background: #1A3D64; color: white;">
                    🖨️ Cetak Struk
                </button>
                <button onclick="tutupModal()" style="background: #666; color: white;">
                    Lanjutkan
                </button>
            </div>
        </div>
    </div>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.querySelector('.search-bar input');
            const paymentInput = document.getElementById('paymentInput');
            const processBtn = document.getElementById('processBtn');
            const kembalianDisplay = document.getElementById('kembalianDisplay');
            const categoryBtns = document.querySelectorAll('.category-btn');
            const productForms = document.querySelectorAll('.product-form');
            const productsGrid = document.getElementById('productsGrid');
            
            // Category filter functionality
            categoryBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    // Remove active class from all buttons
                    categoryBtns.forEach(b => b.classList.remove('active'));
                    // Add active class to clicked button
                    this.classList.add('active');
                    
                    const selectedCategory = this.dataset.category;
                    filterProducts(selectedCategory);
                });
            });
            
            function filterProducts(category) {
                let visibleCount = 0;
                
                productForms.forEach(form => {
                    if (category === 'all' || form.dataset.category === category) {
                        form.style.display = 'block';
                        visibleCount++;
                    } else {
                        form.style.display = 'none';
                    }
                });
                
                // Update product count
                document.querySelector('.products-header .badge-custom').textContent = `${visibleCount} item`;
                
                // Show empty state if no products
                if (visibleCount === 0) {
                    if (!document.querySelector('.empty-state')) {
                        const emptyState = document.createElement('div');
                        emptyState.className = 'empty-products empty-state';
                        emptyState.innerHTML = `
                            <div style="font-size: 64px; opacity: 0.3;">📦</div>
                            <h4>Tidak ada produk dalam kategori ini</h4>
                        `;
                        productsGrid.appendChild(emptyState);
                    }
                } else {
                    const emptyState = document.querySelector('.empty-state');
                    if (emptyState) {
                        emptyState.remove();
                    }
                }
            }
            
            // Payment validation
            if (paymentInput && processBtn) {
                paymentInput.addEventListener('input', function() {
                    const uangDibayar = parseFloat(this.value) || 0;
                    const total = {{ $totals['total'] }};
                    const isCartEmpty = {{ empty($keranjang) ? 'true' : 'false' }};
                    
                    updateKembalianDisplay(uangDibayar, total);
                    
                    const isValid = uangDibayar >= total && !isCartEmpty;
                    processBtn.disabled = !isValid;
                });
            }
            
            function updateKembalianDisplay(uangDibayar, total) {
                const newKembalian = uangDibayar - total;
                
                if (uangDibayar <= 0) {
                    kembalianDisplay.innerHTML = '';
                } else if (newKembalian > 0) {
                    kembalianDisplay.innerHTML = `
                        <div class="change-info">
                            💰 Kembali: Rp ${new Intl.NumberFormat('id-ID').format(newKembalian)}
                        </div>
                    `;
                } else {
                    kembalianDisplay.innerHTML = `
                        <div class="insufficient-info">
                            ⚠️ Kurang: Rp ${new Intl.NumberFormat('id-ID').format(Math.abs(newKembalian))}
                        </div>
                    `;
                }
            }
            
            // Auto focus on desktop
            if (window.innerWidth > 768) {
                setTimeout(() => {
                    if (!document.getElementById('printModal')) {
                        searchInput.focus();
                    }
                }, 500);
            }
        });
        
        function cetakStruk(transaksiId) {
            if (transaksiId) {
                const url = '{{ route("kasir.cetak-struk", ":id") }}'.replace(':id', transaksiId) + '?auto_print=1';
                window.open(url, '_blank');
            }
            tutupModal();
        }
        
        function tutupModal() {
            const modal = document.getElementById('printModal');
            if (modal) {
                modal.classList.remove('show');
                setTimeout(() => {
                    window.location.href = '{{ route("kasir.simple") }}';
                }, 300);
            }
        }
    </script>
</body>
</html>