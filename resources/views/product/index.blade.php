@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                  Customer 
                  <button style="float: right" type="button" class="btn btn-primary" onclick="openModal()">
                    Add New
                  </button>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <select class="form-control" id="filterProructByType">
                                @foreach ($product_types as $type)
                                    <option value="{{$type->product_types}}">{{$type->product_types}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                  <div id="createTaskMessage"></div>

                  <table class="table table-hover" id="productTable">
                    <thead>
                      <tr>
                        <th scope="col">Name</th>
                        <th scope="col">Price</th>
                        <th scope="col">Quantity</th>
                        <th scope="col">Type</th>
                        <th scope="col">Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      {{-- table data will be append here --}}
                      @include('product.tbl_data')
                    </tbody>
                    <input type="hidden" name="hidden_page" id="hidden_page" value="1" />

                  </table> 


                </div>


                {{-- start modal --}}

                
                
                <div class="modal fade" id="productModal" tabindex="-1" role="dialog" aria-labelledby="productModalLabel" aria-hidden="true">
                  <div class="modal-dialog" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="productModalLabel"></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">
                        <div id="formError">
                          
                        </div>
                        <form id="productForm">

                          <input type="text" name="product_name" value="" class="form-control" placeholder="Name"><br>
                          <input type="text" name="price" value="" class="form-control" placeholder="E-mail"><br>
                          <input type="text" name="quantity" value="" class="form-control" placeholder="Phone"><br>

                          <select class="form-control" name="product_types">
                                <option value="decidous">Deciduous</option>
                                <option value="indecious">Indigestible</option>
                          </select>
                          <button type="submit" class="btn btn-primary">Submit</button>
                          <button type="reset" class="btn btn-secoundry">Reset</button>
                        </form>
                      </div>
                    </div>
                  </div>
                </div>

                {{-- end modal --}}
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script>

// Modal Open
function openModal(){
    $('#productModal').modal('show')
    $('#productModalLabel').html('Product Modal')
}


// Ajax setup
$(function () {
  
  $.ajaxSetup({
    headers : {
      'X-CSRF-TOKEN' : '{{ csrf_token() }}'
    }
  });

})



$(document).ready(function(){

function fetch_data(page){
    $.ajax({
        type : 'GET',
        url : '/product?page='+page,
        success : function (products){
            $('#productTable tbody').html('')
            console.log(products)
            $('#productTable tbody').html(products)
        },
        error : error=>{
            console.log(error)
        }
    })
}




$(document).on('click', '.pagination a',function(event)
{

    event.preventDefault();

    $('li').removeClass('active');

    $(this).parent('li').addClass('active');

    var myurl = $(this).attr('href');

    var page=$(this).attr('href').split('page=')[1];
    $('#hidden_page').val(page);
    console.log(page);
    fetch_data(page);
    
});

// insert data


$('#productForm').submit(function (event) {
  event.preventDefault()
  let product_name = $('#productForm input[name="product_name"]')
  let price = $('#productForm input[name="price"]')
  let quantity = $('#productForm input[name="quantity"]')
  let product_types = $('#productForm select[name="product_types"]')

  let msg = $('#createTaskMessage')
  let errmsg = $('#formError')

  let formData = {
    product_name : $(product_name).val(),
    price : $(price).val(),
    quantity : $(quantity).val(),
    product_types : $(product_types).val()
  }

//   console.log(formData)

  $.ajax({
    type : "POST",
    url : '{{ route("product.store") }}',
    data : formData,
    success : res => {
      $(msg).html('')
      let m = '<div class="alert alert-success">'+ res.message +'</div>'
      $(msg).append(m)
      $('#productModal').modal('hide')
      setTimeout(()=>{
        $(msg).html('')
      },3000)
    fetch_data(1);

    $(product_name).val('')
    $(price).val('')
    $(quantity).val('')

    },
    error : res => {
      $(errmsg).html('')
      let errors = res.responseJSON.errors;
      for(error in errors){
        console.log(errors[error][0])
        $(errmsg).append('<li class="alert alert-danger">'+errors[error][0]+'</li>')
        
      }
    }
  })

});


function fetch_data_all(page,filter){
    console.log(filter)
    $.ajax({
        url : '/product/fetch_data_all/'+filter,
        success : function (products){
            $('#productTable tbody').html('')
            console.log(products)
            $('#productTable tbody').html(products)
        }
    })
}

$('#filterProructByType').on('change',function(){
    // console.log(this.value)
   var page = $('#hidden_page').val()
   var filter = this.value
    
    fetch_data_all(page,filter)

})






});













</script>
    
@endpush


