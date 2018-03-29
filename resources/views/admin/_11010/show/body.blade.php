<ul class="list-inline ">
    <li>
        <a href="{{ $permissaoMenu->ALTERAR ? route('_11010.edit', $id) : '#' }}" class="btn btn-primary btn-hotkey btn-alterar" {{ $permissaoMenu->ALTERAR ? '' : 'disabled' }}>
            <span class="glyphicon glyphicon-edit"></span>
             {{ Lang::get('master.alterar') }}
        </a>
    </li>
    <li>
        <a href="#" class="btn btn-danger btn-hotkey btn-excluir {{ $permissaoMenu->EXCLUIR ? 'deletar' : '' }}" {{ $permissaoMenu->EXCLUIR ? '' : 'disabled' }}>
            <span class="glyphicon glyphicon-trash"></span>
             {{ Lang::get('master.excluir') }}
        </a>
    </li>
    <li>
        <button class="btn btn-default {{ $permissaoMenu->ALTERAR ? 'resetar-senha-web' : '' }}" data-iduser="{{$id}}" {{ $permissaoMenu->ALTERAR ? '' : 'disabled' }}>
                <span class="glyphicon glyphicon-asterisk"></span>
                Resetar Senha (GCWEB)
        </button>
    </li>
    <li>
        <button class="btn btn-default {{ $permissaoMenu->ALTERAR ? 'criar-usuario-db' : '' }}" data-iduser="{{$id}}" data-username="{{ $usuario->USUARIO }}" {{ $permissaoMenu->ALTERAR ? '' : 'disabled' }}>
                <span class="glyphicon glyphicon-asterisk"></span>
                Criar Usuário (DB)
        </button>
    </li>
    <li>
        <button class="btn btn-warning {{ $permissaoMenu->ALTERAR ? 'entrar-como-usuario' : '' }}" data-iduser="{{$id}}" data-username="{{ $usuario->USUARIO }}" {{ $permissaoMenu->ALTERAR ? '' : 'disabled' }}>
                <span class="glyphicon glyphicon-transfer"></span>
                Entrar como Este Usuário
        </button>
    </li>
</ul>  
<fieldset readonly>
    <div class="row">
        <legend>Informações gerais</legend>
        <div class="form-group">
            <label for="id">Código:</label>
            <input type="text" id="id" class="form-control input-menor input-bold" value="{{ $id }}" readonly required />
        </div>
        <div class="form-group">
            <label for="usuario">Usuário:</label>
            <input type="text" id="usuario" class="form-control input-medio" value="{{ $usuario->USUARIO }}"  autocomplete="off" required readonly />
        </div>
        <div class="form-group">
            <label for="usuario">Nome:</label>
            <input type="text" id="nome" class="form-control input-maior" value="{{ $usuario->NOME }}"  autocomplete="off" required readonly />
        </div>
        <div class="form-group">
            <label for="usuario">E-mail:</label>
            <input type="text" id="email" class="form-control input-maior" value="{{ $usuario->EMAIL }}"  autocomplete="off" required readonly />
        </div>
    </div>
</fieldset>
<fieldset class="tab-container">
    <ul id="tab" class="nav nav-tabs" role="tablist"> 
        <li role="presentation">
            <a href="#parametro-container" data-iduser="{{$id}}" id="parametro-tab" role="tab" data-toggle="tab" aria-controls="parametro-container" aria-expanded="false">
                Parametros e Permissões
                <span class="btn btn-xs btn-default glyphicon glyphicon-refresh"></span>
            </a>
        </li> 
        <li role="presentation" class="active">
            <a href="#menu-usuario-container" data-iduser="{{$id}}" id="menu-usuario-tab" role="tab" data-toggle="tab" aria-controls="menu-usuario-container" aria-expanded="true">
                Menus do Usuário
                <span class="btn btn-xs btn-default glyphicon glyphicon-refresh"></span>
            </a>
        </li> 
        <li role="presentation">
            <a href="#ccusto-container" data-iduser="{{$id}}" id="ccusto-tab" role="tab" data-toggle="tab" aria-controls="ccusto-container" aria-expanded="true">
                Centros de Custo
                <span class="btn btn-xs btn-default glyphicon glyphicon-refresh"></span>
            </a>
        </li> 
        <li role="presentation">
            <a href="#perfil-container" data-iduser="{{$id}}" id="perfil-tab" role="tab" data-toggle="tab" aria-controls="perfil-container" aria-expanded="true">
                Perfil
                <span class="btn btn-xs btn-default glyphicon glyphicon-refresh"></span>
            </a>
        </li> 
        <li role="presentation">
            <a href="#relatorio-container" data-iduser="{{$id}}" id="relatorio-tab" role="tab" data-toggle="tab" aria-controls="relatorio-container" aria-expanded="true">
                Relatórios
                <span class="btn btn-xs btn-default glyphicon glyphicon-refresh"></span>
            </a>
        </li> 
    </ul>
    <div id="tab-content" class="tab-content">
        <div role="tabpanel" class="tab-pane fade" id="parametro-container" aria-labelledby="parametro-tab">
            
        </div>
        <div role="tabpanel" class="tab-pane fade active in" id="menu-usuario-container" aria-labelledby="menu-usuario-tab">
            @include('admin._11010.show.menu-usuario')
        </div>
        <div role="tabpanel" class="tab-pane fade" id="ccusto-container" aria-labelledby="ccusto-tab">
            
        </div>
        <div role="tabpanel" class="tab-pane fade" id="perfil-container" aria-labelledby="perfil-tab">
            
        </div>
        <div role="tabpanel" class="tab-pane fade" id="relatorio-container" aria-labelledby="relatorio-tab">
            
        </div>
    </div>
</fieldset>