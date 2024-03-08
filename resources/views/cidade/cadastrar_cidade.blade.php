<x-layout>
    <div class="container h-100 cadastro-container">

        <form method="POST" action="/storecidade" class="cadastro-cidade cadastro">
            @csrf
            <input type="text" id="nome" name="nome" placeholder="nome"><br>
            <button type="submit">Cadastrar</button>
        </form>

    </div>
</x-layout>