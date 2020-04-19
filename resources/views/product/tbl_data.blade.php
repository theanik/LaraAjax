@foreach ($products as $product)
<tr>
    <td>{{ $product->product_name }}</td>
    <td>{{ $product->price }}</td>
    <td>{{ $product->quantity }}</td>
    <td><button>Edit</button> <button>delete</button></td>
</tr>
    
@endforeach

<tr>
    <td colspan="3">
        {!! $products->render() !!}
    </td>
</tr>