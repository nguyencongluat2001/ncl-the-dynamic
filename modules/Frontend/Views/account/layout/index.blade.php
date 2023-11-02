@extends('Frontend::layouts.index')

@section('style')
<style>
    #account_sidebar .nav-link {
        color: var(--color-black);
    }

    #account_sidebar .nav-link.active {
        color: #0d6efd;
        background-color: var(--color-white);
    }
    
    #account_sidebar li a span {
        font-size: 18px
    }

    .account-title {
        font-size: 18px
    }
</style>
    @yield('style-child')
@endsection

@section('script')
    @yield('script-child')
@endsection

@section('content')
    <div class="container mt-3">
        <div class="row flex-nowrap">
            <div class="col-auto col-md-4 col-xl-3 col-xxl-2 px-0">
                @include('Frontend::account.layout.sidebar')
            </div>
            <div class="col py-3">
                @yield('content-child')
            </div>
        </div>
    </div>
@endsection
