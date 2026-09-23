<x-filament-panels::page>
    <style>
        .pos-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 24px;
        }

        @media (max-width: 1024px) {
            .pos-grid {
                grid-template-columns: 1fr;
            }
        }

        .pos-card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 16px;
        }

        .pos-search {
            width: 100%;
            padding: 10px 14px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 14px;
        }

        .pos-section-title {
            font-size: 13px;
            font-weight: 600;
            color: #374151;
            margin: 16px 0 8px;
            text-transform: uppercase;
            letter-spacing: .05em;
        }

        .pos-items {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
        }

        @media (max-width: 768px) {
            .pos-items {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        .pos-item-btn {
            text-align: left;
            border: 1px solid #e5e7eb;
            background: #fff;
            border-radius: 10px;
            padding: 12px;
            cursor: pointer;
            transition: .15s;
        }

        .pos-item-btn:hover {
            border-color: #f59e0b;
            box-shadow: 0 2px 8px rgba(0, 0, 0, .08);
            transform: translateY(-1px);
        }

        .pos-item-name {
            font-size: 14px;
            font-weight: 600;
            color: #111827;
        }

        .pos-item-price {
            font-size: 12px;
            color: #6b7280;
            margin-top: 4px;
        }

        .pos-label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 6px;
        }

        .pos-select,
        .pos-input {
            width: 100%;
            padding: 9px 12px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 14px;
            background: #fff;
        }

        .pos-cart-list {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .pos-cart-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 8px;
            font-size: 14px;
        }

        .pos-qty-btn {
            border: none;
            background: #f3f4f6;
            border-radius: 6px;
            width: 26px;
            height: 26px;
            cursor: pointer;
            font-weight: 700;
        }

        .pos-qty-btn.danger {
            background: #fee2e2;
            color: #dc2626;
        }

        .pos-totals {
            border-top: 1px solid #e5e7eb;
            margin-top: 12px;
            padding-top: 12px;
            font-size: 14px;
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .pos-totals-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .pos-totals-row.grand {
            font-weight: 700;
            font-size: 16px;
        }

        .pos-checkout {
            width: 100%;
            margin-top: 16px;
            background: #f59e0b;
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 12px;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
        }

        .pos-checkout:hover {
            background: #d97706;
        }

        .pos-empty {
            color: #9ca3af;
            font-size: 14px;
        }

        .pos-mb {
            margin-bottom: 12px;
        }
    </style>

    <div class="pos-grid">
        {{-- KOLOM KIRI: KATALOG --}}
        <div>
            <input class="pos-search" wire:model.live.debounce.300ms="search" type="search" placeholder="Cari jasa atau sparepart..." />

            <div class="pos-section-title">Jasa Servis</div>
            <div class="pos-items">
                @forelse ($this->services as $service)
                <button class="pos-item-btn" wire:click="addItem('service', {{ $service->id }})">
                    <div class="pos-item-name">{{ $service->name }}</div>
                    <div class="pos-item-price">Rp {{ number_format($service->price, 0, ',', '.') }}</div>
                </button>
                @empty
                <div class="pos-empty">Tidak ada jasa ditemukan.</div>
                @endforelse
            </div>

            <div class="pos-section-title">Sparepart</div>
            <div class="pos-items">
                @forelse ($this->parts as $part)
                <button class="pos-item-btn" wire:click="addItem('part', {{ $part->id }})">
                    <div class="pos-item-name">{{ $part->name }}</div>
                    <div class="pos-item-price">Rp {{ number_format($part->sell_price, 0, ',', '.') }} · Stok: {{ $part->stock }}</div>
                </button>
                @empty
                <div class="pos-empty">Tidak ada sparepart ditemukan.</div>
                @endforelse
            </div>
        </div>

        {{-- KOLOM KANAN: KERANJANG & PEMBAYARAN --}}
        <div>
            <div class="pos-card pos-mb">
                <label class="pos-label">Pelanggan</label>
                <select class="pos-select pos-mb" wire:model.live="customerId">
                    <option value="">-- Pilih Pelanggan --</option>
                    @foreach ($this->customers as $customer)
                    <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                    @endforeach
                </select>

                <label class="pos-label">Kendaraan</label>
                <select class="pos-select" wire:model="vehicleId">
                    <option value="">-- Pilih Kendaraan --</option>
                    @foreach ($this->vehicles as $vehicle)
                    <option value="{{ $vehicle->id }}">{{ $vehicle->plate_number }} ({{ $vehicle->brand }} {{ $vehicle->model }})</option>
                    @endforeach
                </select>
            </div>

            <div class="pos-card">
                <div class="pos-section-title" style="margin-top: 0">Keranjang</div>

                @if (empty($this->cart))
                <p class="pos-empty">Keranjang masih kosong.</p>
                @else
                <ul class="pos-cart-list">
                    @foreach ($this->cart as $key => $item)
                    <li class="pos-cart-item">
                        <div>
                            <div style="font-weight: 600">{{ $item['name'] }}</div>
                            <div style="font-size: 12px; color: #6b7280">Rp {{ number_format($item['price'], 0, ',', '.') }} × {{ $item['qty'] }}</div>
                        </div>
                        <div style="display: flex; gap: 4px">
                            <button class="pos-qty-btn" wire:click="changeQty('{{ $key }}', -1)">-</button>
                            <button class="pos-qty-btn" wire:click="changeQty('{{ $key }}', 1)">+</button>
                            <button class="pos-qty-btn danger" wire:click="removeItem('{{ $key }})">×</button>
                        </div>
                    </li>
                    @endforeach
                </ul>
                @endif

                <div class="pos-totals">
                    <div class="pos-totals-row"><span>Subtotal</span><span>Rp {{ number_format($this->subtotal, 0, ',', '.') }}</span></div>
                    <div class="pos-totals-row">
                        <span>Diskon</span>
                        <input class="pos-input" style="width: 110px; text-align: right" type="number" wire:model.live="discount" />
                    </div>
                    <div class="pos-totals-row grand"><span>Total</span><span>Rp {{ number_format($this->total, 0, ',', '.') }}</span></div>
                </div>

                <div style="margin-top: 12px">
                    <label class="pos-label">Uang Diterima</label>
                    <input class="pos-input" type="number" wire:model.live="paid" />
                    <div class="pos-totals-row" style="margin-top: 6px">
                        <span>Kembalian</span>
                        <strong>Rp {{ number_format(max(0, (float) $this->paid - $this->total), 0, ',', '.') }}</strong>
                    </div>
                </div>

                <button class="pos-checkout" wire:click="checkout" wire:loading.attr="disabled">
                    Bayar & Simpan Transaksi
                </button>
            </div>
        </div>
    </div>
</x-filament-panels::page>