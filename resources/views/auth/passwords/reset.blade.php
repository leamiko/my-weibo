@extends('layouts.default')
@section('title', '更新密码')

@section('content')
    <div class="col-md-8 offset-md-2">
        <div class="card">
            <div class="card-header">
                <h5>更新密码</h5>
            </div>
            <div class="card-body">
                <form action="{{route('password.update')}}" method="POST">
                    {{ csrf_field() }}
                    <input type="hidden" name="token" value="{{$token}}">
                    <div class="from-group row {{ $errors->has('email')? ' has-error' : '' }}">
                        <label for="email" class="col-md-4 col-from-label text-md-right">Email地址</label>
                        <div class="col-md-6">
                            <input type="email" id="email" class="from-control" name="email" value="{{ old('email') }}" />
                            @if($errors->has('email'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{$errors->first('email')}}</strong>
                            </span>
                            @endif
                        </div>
                    </div>

                    <div class="from-group row {{ $errors->has('password')? ' has-error' : '' }}">
                        <label for="password" class="col-md-4 col-from-label text-md-right">密码</label>
                        <div class="col-md-6">
                            <input type="password" id="password" class="from-control" name="password" value="" />
                            @if($errors->has('password'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{$errors->first('password')}}</strong>
                            </span>
                            @endif
                        </div>
                    </div>

                    <div class="from-group row">
                        <label for="password-confirm" class="col-md-4 col-from-label text-md-right">密码确认</label>
                        <div class="col-md-6">
                            <input type="password" id="password-confirm" class="from-control" name="password_confirmation" value="" />
                        </div>
                    </div>

                    <div class="from-group row mb-0">
                        <div class="col-md-6 offset-md-4">
                            <button type="submit" class="btn btn-primary">
                                重置密码
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection