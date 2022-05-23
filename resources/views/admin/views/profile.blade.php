@extends("admin.layouts.layout")

@section("title", "Profile")

@section("page_style")

@endsection

@section("content")
    <div class="row">
        <div class="col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb pl-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin-dashboard') }}"><i class="fas fa-home"></i> Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Profile
                    </li>
                </ol>
            </nav>
        </div>
    </div>
    <div class="ms-profile-overview">
        <div class="ms-profile-cover">
            @if(isset($user->profile_image) && !empty($user->profile_image) && $user->profile_image !=null)
                <img class="ms-profile-img" src="{{ $user->profile_image }}" alt="people">
            @else
                <img class="ms-profile-img" src="{{URL::to('uploads/staff/default.png')}}" alt="people">
            @endif
            <div class="ms-profile-user-info">
                <h1 class="ms-profile-username">{{ $user->name ?? '' }}</h1>
                <h2 class="ms-profile-role">Administrator</h2>
            </div>
            <div class="ms-profile-user-buttons">
            </div>
        </div>
        <ul class="ms-profile-navigation nav nav-tabs tabs-bordered" role="tablist">
            <li role="presentation"><a href="#tab1" aria-controls="tab1" class="active show" role="tab" data-toggle="tab"> Overview </a></li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane" id="tab1">
            </div>
            <div class="tab-pane" id="tab2">
            </div>
            <div class="tab-pane" id="tab3">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-5 col-md-12">
            <div class="ms-panel ms-panel-fh">
                <div class="ms-panel-body">
                    <table class="table">
                        <tbody>
                            <tr>
                                <th scope="row">Full Name</th>
                                <td>{{ $user->name ?? '' }} </td>
                            </tr>
                            <tr>
                                <th scope="row">Email Address</th>
                                <td>{{ $user->email ?? '' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-xl-7 col-md-12">
        </div>
    </div>
@endsection

@section("page_vendors")
@endsection

@section("page_script")
@endsection