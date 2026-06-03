@if(!empty($masterRelationConfig))
<div class="col-12">
    <div class="admin-form-card">
        <div class="admin-form-card-header">
            <h2><i class="far fa-tags"></i> Master Data</h2>
        </div>
        <div class="admin-form-card-body">
            <div class="row g-3">
                @foreach($masterRelationConfig as $relation => $cfg)
                    @php
                        $options = $masterRelations[$relation] ?? collect();
                        $selected = $masterSelected[$relation] ?? [];
                    @endphp
                    <div class="col-md-6">
                        <label class="admin-field-label">{{ $cfg['label'] }}</label>
                        <select name="{{ $cfg['param'] }}[]" class="form-select master-data-multiselect" multiple
                            data-placeholder="Select {{ $cfg['label'] }}">
                            @foreach($options as $option)
                                <option value="{{ $option->id }}" @selected(in_array($option->id, $selected))>{{ $option->name }}</option>
                            @endforeach
                        </select>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endif
