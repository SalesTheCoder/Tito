<x-layout>
    <div class="container h-100 cadastro-container">
        @if(!empty($sucesso))
            <p class="cadastro-msg msg success-msg">{{$sucesso}}</p>
        @endif

        @if(!empty($erro))
            <p class="cadastro-msg error-msg">{{$erro}}</p>
            
        @endif
        <form method="POST" action="/storepessoa" class="cadastro-pessoa cadastro">
            @csrf
            <input type="text" id="nome" name="nome" placeholder="nome"><br>
            <input type="number" id="idade" name="idade" placeholder="idade"><br>
            <input type="date" id="dataNascimento" name="dataNascimento">

            <select name="cidade" id="cidade">
                @foreach($cidades as $cidade)
                    <option value="{{$cidade->id}}">{{$cidade->nome}}</option>
                @endforeach
            </select>

            <button type="submit">Cadastrar</button>
        </form>

    </div>
</x-layout>