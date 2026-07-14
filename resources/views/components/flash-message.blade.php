@if(session('success'))
    <div class="flash-message flash-success" id="flashMessage">
        <span>✅</span>
        <span>{{ session('success') }}</span>
        <button class="close-flash" onclick="this.parentElement.remove()">✕</button>
    </div>
@endif

@if(session('error'))
    <div class="flash-message flash-error" id="flashMessage">
        <span>❌</span>
        <span>{{ session('error') }}</span>
        <button class="close-flash" onclick="this.parentElement.remove()">✕</button>
    </div>
@endif

@if($errors->any())
    <div class="flash-message flash-error">
        <span>⚠️</span>
        <div>
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
        <button class="close-flash" onclick="this.parentElement.remove()">✕</button>
    </div>
@endif
