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
                    <th>
                        @lang('Category.actions')
                    </th>
                </tr>
                </thead>
                <tbody>
                @foreach($categories as $category)
                    <tr>
                        <td>{{{$category->name}}}</td>
                        <td>{{count($category->events)}}</td>
                        <td>
                            <div class="btn-group" role="group" >
                                <form style="float:left;" action="{{ route('deleteCategory', [
                                    'organiser_id' => $organiser->id,
                                    'category_id' => $category->id
                                ])

                                }}" method="POST">
                                    @csrf
                                    {{ method_field('DELETE') }}

                                    <button type="submit" class="btn btn-light" onclick="return confirm('@lang('Category.delete_confirm', ['name' => $category->name])')" >
                                        <i class="ico-trash"></i> @lang('Category.delete')
                                    </button>
                                </form>
                                <a
                                        href="#"
                                        data-modal-id="EditCategory"
                                        data-href="{{route('showEditCategory', [
                                            'organiser_id' => $organiser->id,
                                            'category_id' => $category->id
                                        ])}}"
                                        class="btn btn-light loadModal"
                                >
                                    <i class="ico-pencil"></i> @lang("Category.rename")
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@stop
