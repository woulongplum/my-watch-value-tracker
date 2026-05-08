{{-- resources/views/my-watches/index.blade.php --}}

<h1>所有時計一覧</h1>
<ul>
    @foreach ($myWatches as $watch)
        <li>
            ブランド: {{ $watch->brand->name }} / 
            モデル: {{ $watch->model_name }} / 
            購入価格: ¥{{ number_format($watch->purchase_price) }} / 
            市場平均: ¥{{ number_format($watch->market_average) }} / 
            損益: {{ $watch->profit_loss >= 0 ? '+' : '' }}¥{{ number_format($watch->profit_loss) }}
        </li>
    @endforeach
</ul>
