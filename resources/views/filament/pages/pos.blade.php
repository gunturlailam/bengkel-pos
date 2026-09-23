<x-filament-panels::page>
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        {{-- KOLOM KIRI: KATALOG --}}
        <div class="xl:col-span-2 space-y-4">
            <input
                wire:model.live.debounce.300ms="search"
                type="search"
                placeholder="Cari jasa atau sparepart..."
                class="block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm" />

            <h3 class="text-sm font-semibold text-gray-700">Jasa Servis</h3>
            <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                @foreach ($this->services as $service)
                <button wire:click="addItem('service', {{ $service->id }})"
                    class="rounded-xl border border-gray-200 bg-white p-3 text-left hover:border-primary-500 hover:shadow">
                    <div class="text-sm font-medium">{{ $service->name }}</div>
                    <div class="text-xs text-gray-500">Rp {{ number_format($service->price, 0, ',', '.') }}</div>
                </button>
                @endforeach
            </div>

            <h3 class="text-sm font-semibold text-gray-700">Sparepart</h3>
            <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                @foreach ($this->parts as $part)
                <button wire:click="addItem('part', {{ $part->id }})"
                    class="rounded-xl border border-gray-200 bg-white p-3 text-left hover:border-primary-500 hover:shadow">
                    <div class="text-sm font-medium">{{ $part->name }}</div>
                    <div class="text-xs text-gray-500">Rp {{ number_format($part->sell_price, 0, ',', '.') }} · Stok: {{ $part->stock }}</div>
                </button>
                @endforeach
            </div>
        </div>

        {{-- KOLOM KANAN: KERANJANG & PEMBAYARAN --}}
        <div class="space-y-4">
            <div class="rounded-xl border border-gray-200 bg-white p-4 space-y-3">
                <label class="text-sm font-medium">Pelanggan</label>
                <select wire:model.live="customerId" class="block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
                    <option value="">-- Pilih Pelanggan --</option>
                    @foreach ($this->customers as $customer)
                    <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                    @endforeach
                </select>

                <label class="text-sm font-medium">Kendaraan</label>
                <select wire:model="vehicleId" class="block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
                    <option value="">-- Pilih Kendaraan --</option>
                    @foreach ($this->vehicles as $vehicle)
                    <option value="{{ $vehicle->id }}">{{ $vehicle->plate_number }} ({{ $vehicle->brand }} {{ $vehicle->model }})</option>
                    @endforeach
                </select>
            </div>

            <div class="rounded-xl border border-gray-200 bg-white p-4">
                <h3 class="mb-3 text-sm font-semibold">Keranjang</h3>

                @if (empty($this->cart))
                <p class="text-sm text-gray-400">Keranjang masih kosong.</p>
                @else
                <ul class="space-y-2">
                    @foreach ($this->cart as $key => $item)
                    <li class="flex items-center justify-between gap-2 text-sm">
                        <div>
                            <div class="font-medium">{{ $item['name'] }}</div>
                            <div class="text-xs text-gray-500">Rp {{ number_format($item['price'], 0, ',', '.') }} × {{ $item['qty'] }}</div>
                        </div>
                        <div class="flex items-center gap-1">
                            <button wire:click="changeQty('{{ $key }}', -1)" class="rounded bg-gray-100 px-2 py-1">-</button>
                            <button wire:click="changeQty('{{ $key }}', 1)" class="rounded bg-gray-100 px-2 py-1">+</button>
                            <button wire:click="removeItem('{{ $key }}')" class="rounded bg-red-100 px-2 py-1 text-red-600">×</button>
                        </div>
                    </li>
                    @endforeach
                </ul>
                @endif

                <div class="mt-4 space-y-1 border-t pt-3 text-sm">
                    <div class="flex justify-between"><span>Subtotal</span><span>Rp {{ number_format($this->subtotal, 0, ',', '.') }}</span></div>
                    <div class="flex items-center justify-between gap-2">
                        <span>Diskon</span>
                        <input wire:model.live="discount" type="number" class="w-24 rounded border border-gray-300 px-2 py-1 text-right text-sm" />
                    </div>
                    <div class="flex justify-between font-semibold"><span>Total</span><span>Rp {{ number_format($this->total, 0, ',', '.') }}</span></div>
                </div>

                <div class="mt-4 space-y-2">
                    <label class="text-sm font-medium">Uang Diterima</label>
                    <input wire:model.live="paid" type="number" class="block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm" />
                    <div class="flex justify-between text-sm">
                        <span>Kembalian</span>
                        <span class="font-semibold">Rp {{ number_format(max(0, (float) $this->paid - $this->total), 0, ',', '.') }}</span>
                    </div>
                </div>

                <button wire:click="checkout" wire:loading.attr="disabled"
                    class="mt-4 w-full rounded-lg bg-primary-600 px-4 py-2 text-sm font-semibold text-white hover:bg-primary-700 disabled:opacity-50">
                    Bayar & Simpan Transaksi
                </button>
            </div>
        </div>
    </div>
</x-filament-panels::page>