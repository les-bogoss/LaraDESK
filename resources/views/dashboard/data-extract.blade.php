<head>
    <link rel="stylesheet" href="{{ public_path('css/app.css') }}">
</head>

<body>
    <div class="data-extract-layout">
        <div class="data-extract-section">
            <h1>Categories</h1>
            <table>
                @foreach ($ticket_categories as $category)
                    <tr>
                        <td>{{ $category->label }}</td>
                        <td> {{ $tickets->where('category_id', $category->id)->count() }}</td>
                    </tr>
                @endforeach
            </table>
        </div>
        <div class="data-extract-section">
            <h1>Status</h1>
            <table>
                @foreach ($ticket_statuses as $status)
                    <tr>
                        <td> {{ $status->name }} : </td>
                        <td> {{ $tickets->where('status_id', $status->id)->count() }}</td>
                    </tr>
                @endforeach
            </table>
        </div>
        <div class="data-extract-section">
            <h1>Tickets this week</h1>
            <table>
                <th>date</th>
                <th>opened</th>
                <th>closed</th>

                @for ($i = 1; $i <= 7; $i++)
                    <tr>
                        @php
                            $date = Carbon\Carbon::now()
                                ->subDays($i)
                                ->StartOfDay();
                            $sub_date = Carbon\Carbon::now()
                                ->subDays($i - 1)
                                ->StartOfDay();
                        @endphp
                        <td> {{ $date->format('d-m-y l') }},</td>
                        <td>{{ $tickets->where('created_at', '>=', $date)->where('created_at', '<', $sub_date)->count() }}
                        </td>
                        <td>{{ $tickets->where('status_id', 5)->where('updated_at', '>=', $date)->where('updated_at', '<', $sub_date)->count() }}
                        </td>

                    </tr>
                @endfor
            </table>
        </div>
        <div class="data-extract-section">
            <h1>Satisfaction</h1>
            @php
                $total = $tickets->where('rating', '>', '0')->count();
            @endphp
            <table>
                <tr>
                    <td>"Moyen"</td>
                    <td> {{ round(($tickets->where('rating', 1)->count() / $total) * 100, 1) . ' %' }}</td>
                </tr>
                <tr>
                    <td> "Bon"</td>
                    <td> {{ round(($tickets->where('rating', 2)->count() / $total) * 100, 1) . ' %' }}</td>
                </tr>
                <tr>
                    <td>"Excellent"</td>
                    <td> {{ round(($tickets->where('rating', 3)->count() / $total) * 100, 1) . ' %' }}
                    </td>
                </tr>

            </table>
        </div>
    </div>
</body>
