<x-layout></x-layout>
<x-nav-auth>Hapus Akun</x-nav-auth>
@if (session('status'))
    <div class="alert alert-light alert-dismissible fade show" role="alert">
        {{ session('status') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
<form class="d-flex w-50 mt-5 container" method="POST" action="{{ route('hapusAkunUser') }}">
    @csrf
    <input class="form-control me-2" type="number" placeholder="Masukkan ID akun" id="id" name="id"
        autofocus required>
    <button class="btn btn-outline-danger w-25 fw-bold" type="submit">Hapus akun</button>
</form>

<table class="table mt-4 container" border="2">
    <thead>
        <tr>
            <th scope="col">No</th>
            <th scope="col">Id</th>
            <th scope="col">Username</th>
            <th scope="col">Email</th>
            <th scope="col">Level</th>
        </tr>
    </thead>
    <tbody>
        @php
            $i = 1;
        @endphp
        @foreach ($users->sortBy('id') as $user)
            <tr>
                <th scope="row">{{ $i }}</th>
                <td>{{ $user->id }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->level }}</td>
            </tr>
            @php
                $i++;
            @endphp
        @endforeach

    </tbody>
</table>
