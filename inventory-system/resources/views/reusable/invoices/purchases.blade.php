<head>
    <title>Invoice to: {{ $purchase->customer?->name }}</title>
 </head>

 <div class="max-w-4xl mx-auto p-6 bg-white text-gray-600 shadow-md rounded mt-10 font-sans">
     <!-- Header -->
     <div class="border-b pb-6 mb-6 flex items-center">
         <div class="flex-1">
             <img src="/your-logo.png" alt="Logo" class="h-10 w-auto mb-2">
             <p class="text-sm text-gray-600">{{ $settings['Tenant Name'] }}</p>
             <p class="text-sm text-gray-600">{{ $settings['Address'] }}</p>
             <p class="text-sm text-gray-600">{{ $settings['Email'] }}</p>
         </div>
         <div class="text-right">
             <h1 class="text-3xl font-bold text-gray-600 uppercase">Invoice</h1>
             <p class="text-sm text-gray-600">Invoice #: {{ $purchase->invoice_no }}</p>
             <p class="text-sm text-gray-600">Date: {{ $purchase->purchase_date }}</p>
         </div>
     </div>

     <!-- Billed To -->
     <div class="mb-6">
         <h2 class="text-lg font-semibold text-gray-600 mb-1">Billed To:</h2>
         <p class="text-gray-600">{{ $purchase->customer?->name }}</p>
         <p class="text-sm text-gray-600">{{ $purchase->customer?->address }}</p>
         <p class="text-sm text-gray-600">Email: {{ $purchase->customer?->email }}</p>
     </div>

     <!-- Table -->
     <table class="w-full table-auto border border-gray-600 mb-8 text-gray-600">
         <thead class="bg-gray-100">
             <tr>
                 <th class="border px-3 py-2 text-left">Item</th>
                 <th class="border px-3 py-2 text-left">Qty</th>
                 <th class="border px-3 py-2 text-left">Unit Price</th>
                 <th class="border px-3 py-2 text-right">Total</th>
             </tr>
         </thead>
         <tbody>
             @forelse ($purchase->products as $product)
                 <tr>
                     <td class="border px-3 py-2">{{ $product->product->name }}</td>
                     <td class="border px-3 py-2">{{ $product->quantity }}</td>
                     <td class="border px-3 py-2">Ksh {{ number_format($product->price, 2) }}</td>
                     <td class="border px-3 py-2 text-right">Ksh {{ number_format($product->price * $product->quantity, 2) }}</td>
                 </tr>
             @empty
                 <tr>
                     <td colspan="4" class="text-center py-4 border text-gray-600">No products sold.</td>
                 </tr>
             @endforelse

             <!-- Totals -->
             <tr>
                 <td colspan="3" class="text-right border px-3 py-2 font-medium">Subtotal</td>
                 <td class="border px-3 py-2 text-right">Ksh {{ number_format($purchase->total, 2) }}</td>
             </tr>
             <tr>
                 <td colspan="3" class="text-right border px-3 py-2 font-medium">Discount</td>
                 <td class="border px-3 py-2 text-right">Ksh {{ number_format($purchase->discount, 2) }}</td>
             </tr>
             <tr class="font-bold">
                 <td colspan="3" class="text-right border px-3 py-2">Total Due</td>
                 <td class="border px-3 py-2 text-right">Ksh {{ number_format($purchase->total - $purchase->discount, 2) }}</td>
             </tr>
         </tbody>
     </table>

     <!-- Footer -->
     <div class="text-sm text-gray-600 border-t pt-6">
         <p>Please make payment as soon as Possible.</p>
         <p>Bank Account: 123456789 | MPESA Till: 123456</p>
         <p class="font-semibold mt-2">Thank you for your business!</p>
     </div>
 </div>
