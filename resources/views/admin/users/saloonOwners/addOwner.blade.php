@extends('layouts.admin')
@section('page_header')
    <ul class="navbar-nav flex-row">
        <li>
            <div class="page-header">
                <nav class="breadcrumb-one" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{url('/admin')}}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page"><span>Users</span></li>
                    </ol>
                </nav>
            </div>
        </li>
    </ul>
@endsection
@section('content')
    @php $required_span ='<span style="color:red">*</span>'; @endphp
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">
            <div class="col-xl-12 col-lg-12 col-md-12 col-12 layout-spacing ">
                <div class="statbox widget box box-shadow">
                    <div class="widget-header">
                        <div class="row">
                            <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                                <h4>New Saloon Owner</h4>
                            </div>
                        </div>
                    </div>
                    <div class="widget-content widget-content-area p-4">
                        <div class="widget-one">
                            <form method="POST" action="{{ route('admin.DoAddOwner') }}" class="form-horizontal add_owner">
                                @csrf
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="username">{{trans('label.Username')}}:<?=$required_span;?></label>
                                            <input type="text" class="form-control" name="username" id="username" value="{{old('username')}}">
                                        </div>
                                    </div>
                                    <div class="col-lg-2 justify-content-center">
                                        <label for="activated">Activated:</label>
                                        <div class="form-group">
                                            <label class="switch s-info">
                                                <input id="status" name="status" type="checkbox" class="switch s-info  mt-4 mr-2" checked value="1">
                                                <span class="slider round"></span>
                                            </label>
                                            {!! $errors->first('status', '<label class="error">:message</label>') !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="name" >{{trans('label.Name')}}:<?=$required_span;?></label>
                                            <input type="text" class="form-control" name="name" id="name" value="{{old('name')}}">
                                        </div>
                                        <div class="form-group">
                                            <label for="email">{{trans('label.Email')}}:<?=$required_span;?></label>
                                            <input type="email" class="form-control" name="email" id="email" value="{{old('email')}}">
                                        </div>
                                        <div class="form-group">
                                            <label for="mobile">{{trans('label.Mobile')}}:</label>
                                            <input type="number" class="form-control" name="mobile" id="mobile" value="{{old('mobile')}}">
                                        </div>
                                        <div class="form-group">
                                            <label for="password">{{trans('label.Password')}}:<?=$required_span;?></label>
                                            <input type="password" class="form-control" name="password" id="password" value="{{old('password')}}">
                                        </div>
                                        <div class="form-group">
                                            <label for="cpassword">{{trans('label.Confirm Password')}} :<?=$required_span;?></label>
                                            <input type="password" class="form-control" name="cpassword" id="cpassword" value="{{old('cpassword')}}">
                                        </div>

{{--                                        <div class="form-group">--}}
{{--                                            <label for="role" >Role:<?=$required_span;?></label>--}}
{{--                                            <select class="form-control" name="role" id="role" required>--}}
{{--                                                <option value="">-- {{trans('label.Select')}} {{trans('label.Role')}} --</option>--}}
{{--                                                <?php--}}
{{--                                                foreach($roles as $row_role){--}}
{{--                                                    echo'<option value="'.$row_role->id.'">'.$row_role->name.'</option>';--}}
{{--                                                }--}}
{{--                                                ?>--}}
{{--                                            </select>--}}
{{--                                        </div>--}}
                                    </div>
                                    <!-- col-lg-6 -->
                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <div class="col-sm-9 col-sm-offset-3">
                                                <button type="submit" class="btn btn-primary btn-flat">{{trans('label.Save')}}</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('script')
    <script>
        $('.add_owner').validate({
            rules:{
                name:'required',
                password : {
                    required:true,
                },
                cpassword : {
                    required:true,
                    equalTo : "#password"
                },
                email:{
                    required:true,
                    email:true,
                    remote:{
                        url:'{{url("admin/CheckDuplicateUser")}}',
                        type:'post',
                        data:{
                            email: function(){return $('#email').val();},
                            id: function(){return ''}
                        }
                    }
                },
                username:{
                    required:true,
                    remote:{
                        url:'{{url("admin/CheckDuplicateUsername")}}',
                        type:'post',
                        data:{
                            username: function(){return $('#username').val();},
                            id: function(){return ''}
                        }
                    }
                },
                mobile:{
                    number:true
                }
            },
            messages:{
                name:'Name can not be empty',
                // role:'Role can not be empty',
                email:{
                    required:'Email is required',
                    email:'Email is not in correct format',
                    remote:'Email already exist',
                },
                username:{
                    required:'Username is required',
                    remote:'Username already exist',
                },
                mobile:{
                    number:'Mobile number is not correct'
                },
                cpassword:{
                    required:'Confirm Password is required',
                    equalTo:'Confirm Password & Password does not match',
                }
            },
            submitHandler(form){
                $(".submit").attr("disabled", true);
                var form_owners = $('.add_owner')[0];
                let form1 = new FormData(form_owners);
                $.ajax({
                    type: "POST",
                    url: '{{route('admin.DoAddOwner')}}',
                    data: form1,
                    contentType: false,
                    processData: false,
                    success: function( response ) {
                        if(response.error == 0){
                            toastr.success(response.msg);
                            setTimeout(function(){
                                location.href = '{{url('admin/saloonOwners')}}';
                            },2000);
                        }else{
                            $(".submit").attr("disabled", false);
                            toastr.error(response.msg);
                        }
                    },
                    error: function(data){
                        $(".submit").attr("disabled", false);
                        var errors = data.responseJSON;
                        $.each( errors.errors, function( key, value ) {
                            var ele = "#"+key;
                            $(ele).addClass('error');
                            $('<label class="error">'+ value +'</label>').insertAfter(ele);
                        });
                    }
                })
                return false;
            }


        });


    </script>
@stop
