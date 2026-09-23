<?php

namespace App\Filament\Pages;

use App\Models\Customer;
use App\Models\Part;
use App\Models\Service;
use App\Models\Vehicle;
use App\Models\WorkOrder;
use BackedEnum;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\Auth;
use UnitEnum;

class Pos extends Page
{
    protected string $view = 'filament.pages.pos';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedShoppingBag;
    protected static ?string $navigationLabel = 'Kasir (POS)';
    protected static string|UnitEnum|null $navigationGroup = 'Transaksi';
    protected static ?string $title = 'Transaksi Kasir';

    public ?int $customerId = null;
    public ?int $vehicleId = null;
    public string $search = '';
    public array $cart = [];
    public $discount = 0;
    public $paid = 0;

    public function updatedCustomerId()
    {
        $this->vehicleId = null;
    }

    #[Computed]
    public function customers()
    {
        return Customer::orderBy('name')->get();
    }

    #[Computed]
    public function vehicles()
    {
        return Vehicle::where('customer_id', $this->customerId)->get();
    }

    #[Computed]
    public function services()
    {
        return Service::where('is_active', true)
            ->when($this->search, fn($q) => $q->where('name', 'like', "%{$this->search}%"))
            ->limit(12)->get();
    }

    #[Computed]
    public function parts()
    {
        return Part::where('is_active', true)
            ->when($this->search, fn($q) => $q->where('name', 'like', "%{$this->search}%"))
            ->limit(12)->get();
    }

    #[Computed]
    public function subtotal(): float
    {
        return collect($this->cart)->sum(fn($i) => $i['price'] * $i['qty']);
    }

    #[Computed]
    public function total(): float
    {
        return max(0, $this->subtotal - (float) $this->discount);
    }

    public function addItem(string $type, int $id)
    {
        if ($type === 'service') {
            $item = Service::find($id);
            $price = (float) $item?->price;
            $stock = null;
        } else {
            $item = Part::find($id);
            $price = (float) $item?->sell_price;
            $stock = $item?->stock;
        }

        if (! $item) return;

        $key = $type . '-' . $id;
        $qtyInCart = $this->cart[$key]['qty'] ?? 0;

        if ($stock !== null && $qtyInCart + 1 > $stock) {
            Notification::make()->title('Stok tidak mencukupi!')->warning()->send();
            return;
        }

        $this->cart[$key] = [
            'type' => $type,
            'id' => $id,
            'name' => $item->name,
            'price' => $price,
            'qty' => $qtyInCart + 1,
        ];
    }

    public function changeQty(string $key, int $delta)
    {
        if (! isset($this->cart[$key])) return;

        $newQty = $this->cart[$key]['qty'] + $delta;

        if ($newQty <= 0) {
            unset($this->cart[$key]);
            return;
        }

        if ($this->cart[$key]['type'] === 'part') {
            $stock = Part::find($this->cart[$key]['id'])?->stock ?? 0;
            if ($newQty > $stock) {
                Notification::make()->title('Stok tidak mencukupi!')->warning()->send();
                return;
            }
        }

        $this->cart[$key]['qty'] = $newQty;
    }

    public function removeItem(string $key)
    {
        unset($this->cart[$key]);
    }

    public function checkout()
    {
        $this->validate([
            'customerId' => 'required|exists:customers,id',
            'vehicleId' => 'required|exists:vehicles,id',
            'cart' => 'required|array|min:1',
        ]);

        if ((float) $this->paid < $this->total) {
            Notification::make()->title('Uang pembayaran kurang!')->danger()->send();
            return;
        }

        $invoice = DB::transaction(function () {
            $workOrder = WorkOrder::create([
                'invoice_number' => $this->generateInvoiceNumber(),
                'customer_id' => $this->customerId,
                'vehicle_id' => $this->vehicleId,
                'user_id' => Auth::id(),
                'status' => 'pending',
                'subtotal' => $this->subtotal,
                'discount' => $this->discount,
                'total' => $this->total,
            ]);

            foreach ($this->cart as $item) {
                $workOrder->items()->create([
                    'part_id' => $item['type'] === 'part' ? $item['id'] : null,
                    'service_id' => $item['type'] === 'service' ? $item['id'] : null,
                    'name' => $item['name'],
                    'qty' => $item['qty'],
                    'price' => $item['price'],
                    'subtotal' => $item['price'] * $item['qty'],
                ]);

                if ($item['type'] === 'part') {
                    Part::whereKey($item['id'])->decrement('stock', $item['qty']);
                }
            }

            return $workOrder->invoice_number;
        });

        Notification::make()
            ->title('Transaksi berhasil disimpan!')
            ->body('Nomor Invoice: ' . $invoice)
            ->success()
            ->send();

        $this->reset(['cart', 'discount', 'paid', 'customerId', 'vehicleId', 'search']);
    }

    private function generateInvoiceNumber(): string
    {
        $date = now()->format('Ymd');
        $count = WorkOrder::whereDate('created_at', today())->count() + 1;

        return 'WO-' . $date . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
    }
}
