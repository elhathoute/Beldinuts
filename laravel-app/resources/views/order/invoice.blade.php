<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('messages.invoice') }} #{{ $order->id }}</title>
    <style>
        @page {
            margin: 20mm;
        }
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 12px;
            color: #333;
            line-height: 1.6;
        }
        .header {
            display: table;
            width: 100%;
            margin-bottom: 30px;
            border-bottom: 2px solid #8B4513;
            padding-bottom: 20px;
        }
        .header-left {
            display: table-cell;
            width: 60%;
            vertical-align: middle;
        }
        .header-right {
            display: table-cell;
            width: 40%;
            text-align: right;
            vertical-align: middle;
        }
        .logo {
            max-width: 120px;
            max-height: 120px;
        }
        .invoice-title {
            font-size: 28px;
            font-weight: bold;
            color: #8B4513;
            margin: 10px 0;
        }
        .invoice-number {
            font-size: 16px;
            color: #666;
            margin-top: 10px;
        }
        .info-section {
            margin-bottom: 30px;
        }
        .info-section h3 {
            font-size: 14px;
            font-weight: bold;
            color: #8B4513;
            margin-bottom: 10px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }
        .info-row {
            margin-bottom: 5px;
        }
        .info-label {
            font-weight: bold;
            display: inline-block;
            width: 120px;
        }
        .two-columns {
            display: table;
            width: 100%;
            margin-bottom: 30px;
        }
        .column {
            display: table-cell;
            width: 50%;
            vertical-align: top;
            padding-right: 20px;
        }
        .column:last-child {
            padding-right: 0;
            padding-left: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table th {
            background-color: #8B4513;
            color: white;
            padding: 10px;
            text-align: left;
            font-weight: bold;
            font-size: 11px;
        }
        table td {
            padding: 8px 10px;
            border-bottom: 1px solid #ddd;
            font-size: 11px;
        }
        table tfoot td {
            font-weight: bold;
            border-top: 2px solid #8B4513;
            border-bottom: none;
            padding-top: 10px;
            padding-bottom: 10px;
        }
        .text-right {
            text-align: right;
        }
        .total-amount {
            font-size: 18px;
            color: #8B4513;
        }
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-left">
            @if($logo)
                <img src="{{ $logo }}" alt="BeldiNuts Logo" class="logo">
            @endif
            <div class="invoice-title">BeldiNuts</div>
            <div class="invoice-number">{{ __('messages.invoice') }} #{{ $order->id }}</div>
        </div>
        <div class="header-right">
            <div style="font-size: 14px; color: #666;">
                <strong>{{ __('messages.date') }}:</strong> {{ $order->created_at->format('d/m/Y H:i') }}<br>
                <strong>{{ __('messages.status') }}:</strong> {{ __('messages.status_' . $order->status) }}
            </div>
        </div>
    </div>

    <div class="two-columns">
        <div class="column">
            <div class="info-section">
                <h3>{{ __('messages.shipping_info') }}</h3>
                <div class="info-row">
                    <span class="info-label">{{ __('messages.customer') }}:</span>
                    {{ $order->user->name ?? __('messages.guest') }}
                </div>
                <div class="info-row">
                    <span class="info-label">{{ __('messages.phone') }}:</span>
                    {{ $order->phone }}
                </div>
                <div class="info-row">
                    <span class="info-label">{{ __('messages.address') }}:</span>
                    {{ $order->address }}
                </div>
            </div>
        </div>
        <div class="column">
            <div class="info-section">
                <h3>{{ __('messages.company_info') }}</h3>
                <div class="info-row">
                    <span class="info-label">{{ __('messages.company') }}:</span>
                    BeldiNuts
                </div>
                <div class="info-row">
                    <span class="info-label">{{ __('messages.email') }}:</span>
                    beldinuts@gmail.com
                </div>
                <div class="info-row">
                    <span class="info-label">{{ __('messages.phone') }}:</span>
                    +212 615 919 437
                </div>
            </div>
        </div>
    </div>

    <div class="info-section">
        <h3>{{ __('messages.order_items') }}</h3>
        <table>
            <thead>
                <tr>
                    <th>{{ __('messages.product') }}</th>
                    <th class="text-right">{{ __('messages.quantity') }}</th>
                    <th class="text-right">{{ __('messages.price') }}</th>
                    <th class="text-right">{{ __('messages.subtotal') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                <tr>
                    <td>{{ $item->product->name }}</td>
                    <td class="text-right">
                        @if($item->quantity_pieces)
                            {{ $item->quantity_pieces }} {{ __('messages.pieces') }}
                        @else
                            {{ $item->quantity_grams }}g
                        @endif
                    </td>
                    <td class="text-right">{{ number_format($item->unit_price, 2) }} DH</td>
                    <td class="text-right">
                        @if($item->quantity_pieces)
                            {{ number_format($item->unit_price * $item->quantity_pieces, 2) }} DH
                        @else
                            {{ number_format($item->unit_price * $item->quantity_grams, 2) }} DH
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" class="text-right"><strong>{{ __('messages.total') }}:</strong></td>
                    <td class="text-right total-amount">{{ number_format($order->total, 2) }} DH</td>
                </tr>
            </tfoot>
        </table>
    </div>

    @if($order->tracking)
    <div class="info-section">
        <h3>{{ __('messages.tracking_info') }}</h3>
        <div class="info-row">
            <span class="info-label">{{ __('messages.tracking') }}:</span>
            {{ $order->tracking }}
        </div>
    </div>
    @endif

    <div class="footer">
        <p><strong>BeldiNuts</strong> - {{ __('messages.thank_you_message') }}</p>
        <p>{{ __('messages.invoice_generated') }}: {{ now()->format('d/m/Y H:i') }}</p>
    </div>
</body>
</html>

