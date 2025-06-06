@props(['class' => 'btn btn-outline-light'])

<form method="POST" action="{{ route('logout') }}" class="d-inline">
    @csrf
    <button type="submit" class="{{ $class }}" onclick="return confirm('Are you sure you want to logout?')" title="Logout">
        <i class="bi bi-box-arrow-right"></i> Logout
    </button>
</form>