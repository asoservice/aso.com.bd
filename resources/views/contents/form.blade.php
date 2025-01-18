<div class="col-12">
    <div class="card tab2-card">
        <div class="card-header">
            <h5>{{ 'Add New Faq Category' }}</h5>
        </div>
        <form action="{{ $action }}" id="categoryForm" @submit.prevent="onsubmit" method="POST" enctype="multipart/form-data">
            @csrf 
            @if (isset($method))
                @method($media)
            @endif
            <div class="card-body">
                @isset($fields)
                    {!! $fields !!}
                @endisset
                <div class="footer">
                    <button id='cancelBtn' type="button" class="btn btn-danger spinner-btn">{{ __('Cancel') }}</button>
                    <button id='submitBtn' type="submit" class="btn btn-warning spinner-btn">{{ __('Update') }}</button>
                </div>
            </div>
        </form>
    </div>
</div>