<div role="dialog" class="modal fade" style="display: none;">
    {!! Form::open(array('url' => route('postEditCategory', ['organiser_id' => $organiser->id, 'category_id' => $category->id]), 'class' => 'ajax')) !!}
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h3 class="modal-title">
                        <i class="ico-cabinet"></i>
                        @lang("Category.edit_category")</h3>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                {!! Form::label('name', trans("Category.category_name"), ['class' => 'control-label required']) !!}
                                {!! Form::text('name', $category->name, ['class' => 'form-control']) !!}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    {!! Form::button(trans("basic.cancel"), ['class' => 'btn modal-close btn-danger', 'data-dismiss' => 'modal']) !!}
                    {!! Form::submit(trans("Category.create_category"), ['class' => "btn btn-success"]) !!}
                </div>
            </div>
        </div>
    {!! Form::close() !!}
</div>
