<x-filament-panels::page>
    <div class="bg-gray-100 min-h-screen py-8">

        <div class="bg-gray-200 shadow-lg rounded-lg p-8 max-w-4xl mx-auto">

            <!-- Header -->
            <div class="bg-gradient-to-r from-blue-500 to-purple-600 text-gray-500 rounded-lg p-6 mb-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-4xl font-extrabold">Invoice</h1>
                        <p class="text-lg font-medium mt-2">Invoice #{{$purchase->invoice_no}}</p>
                        <p class="text-sm">Date: {{$purchase->purchase_date }}</p>
                    </div>
                    <div class="text-right">
                        <img src="/path-to-your-logo.png" alt="Company Logo" class="h-16">
                    </div>
                </div>
            </div>

            <!-- Divider -->
            <div class="border-t-4 border-blue-500 mt-2 mb-6"></div>

            <!-- Billing Information -->
            <div class="mt-8 grid grid-cols-2 gap-6">
                <div class=" text-gray-600 p-4 rounded-lg">
                    <h2 class="font-bold text-blue-800 text-lg">Billed From</h2>
                    <p>{{ $purchase->provider?->name}}</p>
                    <p>{{ $purchase->provider?->address }}</p>
                    <p>Email:{{ $purchase->provider->email }}</p>
                </div>
                <div class="bg-blue-50 p-4  text-gray-600 rounded-lg text-right">
                    <h2 class="font-bold text-lg">Company</h2>
                    <p>Your Company</p>
                    <p>123 Business Ave</p>
                    <p>Nairobi, Kenya</p>
                    <p>Email: info@yourcompany.com</p>
                </div>
            </div>

            <!-- Item Table -->
            <div class="mt-10">
                <table class="w-full text-sm table-auto border border-gray-200 rounded overflow-hidden">
                    <thead class="bg-gray-100 text-gray-600">
                        <tr>
                            <th class="px-4 py-2 text-left">Item</th>
                            <th class="px-4 py-2 text-right">Qty</th>
                            <th class="px-4 py-2 text-right">Unit Price</th>
                            <th class="px-4 py-2 text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($purchase->products as $product)
                        <tr class="border-t text-gray-600">
                            <td class="px-4 py-2">{{ $product->product->name }}</td>
                            <td class="px-4 py-2 text-right">{{ $product->quantity }}</td>
                            <td class="px-4 py-2 text-right">{{ $product->price }}</td>
                            <td class="px-4 py-2 text-right">{{ number_format($product->quantity * $product->price, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-gray-500 py-4">
                                No products found for this invoice.
                            </td>
                        </tr>
                    @endforelse

                    </tbody>
                </table>
            </div>

            <!-- Totals -->
            <div class="text-right mt-6 text-gray-600">
                <p class="text-sm mb-1">Subtotal: <span class="font-semibold">Ksh {{$purchase->total}}</span></p>
                <p class="text-sm mb-1">Discount: <span class="font-semibold">Ksh{{ $purchase->discount }}</span></p>
                <p class="text-lg font-bold">Total: <span class="text-green-600">{{ $purchase->total - $purchase->discount  }}</span></p>
            </div>

            <!-- Footer -->
            <div class="mt-8 text-sm text-gray-600 text-center">
                <p>Thank you for your business!</p>
                <p class="mt-1">Please make payment by the due date indicated.</p>
            </div>

        </div>
    </div>
</x-filament-panels::page>
