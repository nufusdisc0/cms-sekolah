<div>
    <div class="card">
        <div class="card-header"><h5 class="mb-0"><i class="fa fa-wrench me-2"></i>{{ $title }}</h5></div>
        <div class="card-body">
            @if(session()->has('message'))
                <div class="alert alert-success alert-dismissible fade show"><i class="fa fa-check-circle me-1"></i> {{ session('message') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
            @endif
            <form wire:submit="save">
                @foreach($settingRows as $row)
                <div class="mb-3">
                    <label class="form-label fw-semibold">{{ ucwords(str_replace('_', ' ', $row->setting_variable)) }}</label>
                    @if($row->setting_type === 'textarea')
                        <textarea wire:model="settings.{{ $row->setting_variable }}" class="form-control" rows="3">{{ $settings[$row->setting_variable] ?? '' }}</textarea>
                    @elseif($row->setting_type === 'enum')
                        <select wire:model="settings.{{ $row->setting_variable }}" class="form-select">
                            @foreach(explode(',', $row->setting_options ?? 'true,false') as $opt)
                                <option value="{{ trim($opt) }}">{{ ucfirst(trim($opt)) }}</option>
                            @endforeach
                        </select>
                    @elseif($row->setting_default_value === 'image')
                        @if(!empty($settings[$row->setting_variable]))
                            <div class="mb-2">
                                <img src="{{ asset('storage/media_library/images/' . $settings[$row->setting_variable]) }}" class="img-thumbnail" style="max-height: 100px;">
                            </div>
                        @endif
                        <input type="file" wire:model="uploads.{{ $row->setting_variable }}" class="form-control" accept="image/jpeg,image/png,image/gif">
                        @error('uploads.'.$row->setting_variable) <span class="text-danger small">{{ $message }}</span> @enderror
                    @else
                        <input type="text" wire:model="settings.{{ $row->setting_variable }}" class="form-control" value="{{ $settings[$row->setting_variable] ?? '' }}">
                    @endif
                    @if($row->setting_description)<small class="text-muted">{{ $row->setting_description }}</small>@endif
                </div>
                @endforeach
                <button type="submit" class="btn btn-primary"><i class="fa fa-save me-1"></i> Simpan Pengaturan</button>
            </form>
        </div>
    </div>
</div>
