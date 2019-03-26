@extends('Shared.Layouts.Master')

@section('title')
    @parent
    @lang("Organiser.organiser_categories")
@stop

@section('page_title')
    @lang("Organiser.organiser_name_categories", ["name"=>$organiser->name])
@stop

@section('top_nav')
    @include('ManageOrganiser.Partials.TopNav')
@stop

@section('menu')
    @include('ManageOrganiser.Partials.Sidebar')
@stop

@section('page_header')
    <div class="col-md-9">
        <div class="btn-toolbar">
            <div class="btn-group btn-group-responsive">
                <a
                    href="#"
                    data-modal-id="CreateCategory"
                    data-href="{{route('showCreateCategory', ['organiser_id' => $organiser->id])}}"
                    class="btn btn-success loadModal"
                >
                    <i class="ico-plus"></i> @lang("Category.create_category")
                </a>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        &nbsp;
    </div>
@stop

@section('content')
    <div class="panel">
        <div class="table-responsive">
            <table class="table">
                <thead>
                <tr>
                    <th>
                        @lang('Category.name')
                    </th>
                    <th>
                        @lang('Category.category_events')
                    </th>
                </tr>
                </thead>
                <tbody>
                @foreach($categories as $category)
                    <tr>
                        <td>{{{$category->name}}}</td>
                        <td>{{count($category->events)}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@stop
