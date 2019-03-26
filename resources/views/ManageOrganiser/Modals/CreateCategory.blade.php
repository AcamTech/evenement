<div role="dialog"  class="modal fade" style="display: none;">
    {!! Form::open(array('url' => route('postCreateCategory', ['organiser_id' => $organiser->id]), 'class' => 'ajax')) !!}
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header text-center">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h3 class="modal-title">
                    <i class="ico-calendar"></i>
                    @lang("Category.create_category")</h3>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <p>Hello, world!</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <p>Hello, world!</p>
            </div>
        </div>
        {!! Form::close() !!}
    </div>
</div>
