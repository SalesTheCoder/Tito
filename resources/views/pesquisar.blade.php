<x-layout>

    <div class="container h-100 cadastro-container">

        <form method="POST" action="/gerar" class="busca cadastro">
            @csrf
            <input type="text" id="nome" name="nome" placeholder="nome"><br>

            <input type="date" name="data_inicio" id="data_inicio">
            <input type="date" name="data_fim" id="data_fim"><br>

            <input type="text" name="cidade" id="cidade" placeholder="cidade"><br>

            <button type="submit" id="gerar_txt" name="gerar_txt" value="txt">Gerar Txt</button>
            <button type="submit" id="gerar_excel" name="gerar_excel" value="excel">Gerar Excel</button>
            <button type="submit" id="gerar_pdf" name="gerar_pdf" value="pdf">Gerar PDF</button>
        </form>

    </div>

    <script src="{{asset('js/geradores.js')}}"></script>
</x-layout>