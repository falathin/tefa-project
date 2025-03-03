<x-layout></x-layout>
@if (session('status'))
    <div class="alert alert-light alert-dismissible fade show" role="alert">
        {{ session('status') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
<x-nav-auth>Hapus Akun {{ Auth::user()->jurusan }}</x-nav-auth>
<form class="d-flex w-50 mt-5 container" method="POST" action="{{ route('hapusAkunUser') }}">
    @csrf
    <input class="form-control me-2" type="number" placeholder="Masukkan Id akun" id="id" name="id"
        autofocus required>
    <button class="btn btn-outline-danger w-25 fw-bold" type="submit">Hapus akun</button>
</form>

<table class="table mt-5 container" border="2">
    <thead>
        <tr>
            <th scope="col">No</th>
            <th scope="col">Id</th>
            <th scope="col">Username</th>
            <th scope="col">Email</th>
            <th scope="col">Level</th>
            <th scope="col">Jurusan</th>
        </tr>
    </thead>
    <tbody>
        @php
            $i = 1;
        @endphp
        @foreach ($users->sortBy('id') as $user)
            @if (!Gate::allows('isEngineer'))
                @if ($user->level == 'engineer')
                    @continue
                @endif
            @endif
            @if ($user->jurusan != Auth::user()->jurusan)
                @continue
            @endif
            <tr>
                <th scope="row">{{ $i }}</th>
                <td>{{ $user->id }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->level }}</td>
                <td>{{ $user->jurusan }}</td>
            </tr>
            @php
                $i++;
            @endphp
        @endforeach

    </tbody>
</table>
