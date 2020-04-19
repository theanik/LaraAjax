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
                  <div id="createTaskMessage"></div>

                  <table class="table table-hover" id="customerTable">
                    <thead>
                      <tr>
                        <th scope="col">#</th>
                        <th scope="col">Name</th>
                        <th scope="col">Email</th>
                        <th scope="col">Phone</th>
                        <th scope="col">Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      {{-- table data will be append here --}}
                    </tbody>
                  </table> 


                </div>


                {{-- start modal --}}

                
                
                <div class="modal fade" id="customerModal" tabindex="-1" role="dialog" aria-labelledby="customerModalLabel" aria-hidden="true">
                  <div class="modal-dialog" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="customerModalLabel"></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">
                        <div id="formError">
                          
                        </div>
                        <form id="customerForm">

                          <input type="text" name="name" value="" class="form-control" placeholder="Name"><br>
                          <input type="text" name="email" value="" class="form-control" placeholder="E-mail"><br>
                          <input type="text" name="phone" value="" class="form-control" placeholder="Phone"><br>

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
function openModal(){
  $('#customerModal').modal('show')
  $('#customerModalLabel').html('Customer Model')
 }


 function getData(){
  let customerTable = $('#customerTable');
   $.ajax({
     type : 'GET',
     url : '/customer/getallcustomer',
     success : res => {
        let customers = res.customer.data;
        // console.log(customers)
        let html = ''
        customers.forEach(element => {
          
          html += '<tr>'
          html +='<td scope="row">'+element.id+'</td>'
          html +='<td>'+element.name+'</td>'
          html +='<td>'+element.email+'</td>'
          html +='<td>'+element.phone+'</td>'
          html +='<td><button type="button" class="btn btn-danger btn-sm">Delete</button></td>'
          html += '</tr>'
          // console.log(element.name)
        });

        $('#customerTable tbody').html(html)
     }
   });
 }

 getData()

$(function () {
  
  $.ajaxSetup({
    headers : {
      'X-CSRF-TOKEN' : '{{ csrf_token() }}'
    }
  });

  $('#submit').click( () => {
  console.log('cki')
})

    

$('#customerForm').submit(function (event) {
  event.preventDefault()
  let name = $('#customerForm input[name="name"]')
  let email = $('#customerForm input[name="email"]')
  let phone = $('#customerForm input[name="phone"]')

  let msg = $('#createTaskMessage')
  let errmsg = $('#formError')

  let formData = {
    name : $(name).val(),
    email : $(email).val(),
    phone : $(phone).val(),
  }

  $.ajax({
    type : "POST",
    url : '/customer/store',
    data : formData,
    success : res => {
      $(msg).html('')
      let m = '<div class="alert alert-success">'+ res.message +'</div>'
      $(msg).append(m)
      $('#customerModal').modal('hide')
      setTimeout(()=>{
        $(msg).html('')
      },3000)
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






});





 

 



</script>
    
@endpush


