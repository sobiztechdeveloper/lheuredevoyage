@props([
    'user',
    'uploadId' => null,
])

@php
    $inputId = $uploadId ?? 'profile-avatar-'.uniqid();
@endphp

<form
    method="POST"
    action="{{ route('my-profile.avatar') }}"
    enctype="multipart/form-data"
    class="profile-avatar-form"
>
    @csrf
    <div class="user-profile-img">
        <img src="{{ $user->avatarUrl() }}" alt="{{ $user->name }}" class="profile-avatar-preview">
        <button type="button" class="profile-img-btn"><i class="far fa-camera"></i></button>
        <input
            type="file"
            name="avatar"
            id="{{ $inputId }}"
            class="profile-img-file"
            accept="image/jpeg,image/png,image/webp"
        >
    </div>
</form>
