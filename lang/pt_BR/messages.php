<?php

return [
    // Mensagens de sucesso
    'success' => [
        'transfer_completed' => 'Transferência realizada com sucesso!',
        'deposit_completed' => 'Depósito realizado com sucesso! Seu saldo foi atualizado.',
        'user_created' => 'Usuário criado com sucesso!',
        'user_updated' => 'Usuário atualizado com sucesso!',
        'user_deleted' => 'Usuário excluído com sucesso!',
        'permission_updated' => 'Permissões atualizadas com sucesso!',
        'login_successful' => 'Login realizado com sucesso!',
        'logout_successful' => 'Logout realizado com sucesso!',
    ],

    // Mensagens de erro
    'error' => [
        'user_not_found' => 'Usuário não encontrado com este e-mail.',
        'wrong_password' => 'Senha incorreta. Tente novamente.',
        'insufficient_balance' => 'Saldo insuficiente para realizar a transferência.',
        'cannot_transfer_to_self' => 'Você não pode transferir para si mesmo.',
        'merchant_cannot_transfer' => 'Lojistas não podem realizar transferências.',
        'admin_cannot_deposit' => 'Administradores não podem realizar depósitos.',
        'admin_cannot_transfer' => 'Administradores não podem realizar transferências.',
        'transfer_failed' => 'Erro ao processar a transferência. Tente novamente.',
        'deposit_failed' => 'Erro ao processar o depósito. Tente novamente.',
        'unauthorized' => 'Você não tem permissão para acessar esta funcionalidade.',
        'user_not_authenticated' => 'Usuário não autenticado. Faça login para continuar.',
    ],

    // Mensagens de validação
    'validation' => [
        'required' => 'O campo :attribute é obrigatório.',
        'email' => 'Digite um email válido.',
        'numeric' => 'O valor deve ser um número válido.',
        'min' => 'O valor mínimo para :attribute é :min.',
        'max' => 'O valor máximo para :attribute é :max.',
        'exists' => 'Usuário não encontrado com este email.',
        'different' => 'Você não pode transferir para si mesmo.',
        'amount_required' => 'O valor do depósito é obrigatório.',
        'amount_numeric' => 'O valor deve ser um número válido.',
        'amount_min' => 'O valor mínimo para depósito é R$ 0,01.',
        'amount_max' => 'O valor máximo para depósito é R$ 10.000,00.',
        'payee_email_required' => 'O email do recebedor é obrigatório.',
        'payee_email_email' => 'Digite um email válido.',
        'payee_email_exists' => 'Usuário não encontrado com este email.',
        'payee_email_different' => 'Você não pode transferir para si mesmo.',
    ],

    // Mensagens de informação
    'info' => [
        'admin_access_restricted' => 'Como administrador, você pode visualizar este formulário, mas não pode realizar operações.',
        'admin_impersonating' => 'Você está acessando como outro usuário.',
        'return_to_admin' => 'Voltar ao Admin',
        'dashboard_welcome' => 'Gerencie suas transferências de forma simples e rápida',
        'no_transactions' => 'Nenhuma transação encontrada.',
        'loading' => 'Carregando...',
        'processing' => 'Processando...',
        'transfer_description' => 'Envie dinheiro para outros usuários',
        'deposit_description' => 'Adicione dinheiro à sua conta',
        'transfer_summary' => 'Transferir :amount para :recipient',
        'deposit_summary' => 'Depositar :amount. Novo saldo: :new_balance',
        'unknown_recipient' => 'Destinatário desconhecido',
        'view_only' => 'Este formulário é apenas para visualização.',
    ],

    // Títulos e labels
    'titles' => [
        'dashboard' => 'Dashboard',
        'transfer' => 'Transferência',
        'deposit' => 'Depósito',
        'admin' => 'Administração',
        'users' => 'Usuários',
        'permissions' => 'Permissões',
        'reports' => 'Relatórios',
        'profile' => 'Perfil',
        'settings' => 'Configurações',
        'app_name' => 'Transferências Light',
        'welcome' => 'Bem-vindo ao :app',
        'login_to_access' => 'Faça login para acessar sua conta',
        'profile_information' => 'Informações do Perfil',
        'reset_password' => 'Redefinir Senha',
        'forgot_password' => 'Esqueceu a Senha?',
        'update_profile_description' => 'Atualize as informações do perfil e endereço de email da sua conta.',
        'remember_me' => 'Lembrar de mim',
        'name' => 'Nome',
        'email' => 'E-mail',
        'password' => 'Senha',
        'confirm_password' => 'Confirmar Senha',
        'current_password' => 'Senha Atual',
        'new_password' => 'Nova Senha',
        'update_password' => 'Atualizar Senha',
        'resend_verification_email' => 'Reenviar E-mail de Verificação',
        'email_password_reset_link' => 'Enviar Link de Redefinição de Senha',
    ],

    // Botões
    'buttons' => [
        'transfer' => 'Transferir',
        'deposit' => 'Depositar',
        'save' => 'Salvar',
        'cancel' => 'Cancelar',
        'edit' => 'Editar',
        'delete' => 'Excluir',
        'create' => 'Criar',
        'back' => 'Voltar',
        'login' => 'Entrar',
        'logout' => 'Sair',
        'register' => 'Registrar',
        'reset' => 'Limpar',
        'search' => 'Buscar',
        'filter' => 'Filtrar',
        'export' => 'Exportar',
        'import' => 'Importar',
    ],

    // Formulários
    'forms' => [
        'email' => 'E-mail',
        'password' => 'Senha',
        'name' => 'Nome',
        'amount' => 'Valor',
        'payee_email' => 'E-mail do Recebedor',
        'remember_me' => 'Lembrar de mim',
        'forgot_password' => 'Esqueceu a senha?',
        'confirm_password' => 'Confirmar Senha',
        'current_password' => 'Senha Atual',
        'new_password' => 'Nova Senha',
        'transfer_amount' => 'Valor da Transferência',
        'deposit_amount' => 'Valor do Depósito',
        'current_balance' => 'Saldo Atual',
        'balance' => 'Saldo',
        'user_type' => 'Tipo',
        'function' => 'Função',
    ],

    // Navegação
    'navigation' => [
        'home' => 'Início',
        'dashboard' => 'Dashboard',
        'transfer' => 'Transferir',
        'deposit' => 'Depositar',
        'history' => 'Histórico',
        'admin' => 'Admin',
        'users' => 'Usuários',
        'permissions' => 'Permissões',
        'reports' => 'Relatórios',
        'profile' => 'Perfil',
        'settings' => 'Configurações',
        'logout' => 'Sair',
        'back_to_dashboard' => 'Voltar ao Dashboard',
        'make_deposit' => 'Fazer Depósito',
        'back_to_admin' => 'Voltar ao Admin',
    ],

    // Ações gerais
    'logout' => 'Sair',

    // Filtros e Busca
    'filters' => [
        'search_by_name_or_email' => 'Buscar por nome ou email',
        'filter_by_type' => 'Filtrar por tipo',
        'type_to_search' => 'Digite para buscar...',
        'all_types' => 'Todos os tipos',
        'filter' => 'Filtrar',
        'clear' => 'Limpar',
        'search' => 'Buscar',
        'no_results' => 'Nenhum resultado encontrado',
        'results_found' => 'resultados encontrados',
        'try_different_filters' => 'Tente usar filtros diferentes',
        'no_users_yet' => 'Ainda não há usuários cadastrados',
    ],

    // Campos de tabela
    'table' => [
        'user' => 'Usuário',
        'type' => 'Tipo',
        'balance' => 'Saldo',
        'created_at' => 'Criado em',
        'actions' => 'Ações',
    ],

    // Status
    'status' => [
        'completed' => 'Concluída',
        'pending' => 'Pendente',
        'failed' => 'Falhou',
        'cancelled' => 'Cancelada',
        'processing' => 'Processando',
    ],

    // Roles
    'roles' => [
        'admin' => 'Administrador',
        'support' => 'Suporte',
        'common_user' => 'Usuário Comum',
        'common-user' => 'Usuário Comum',
        'merchant' => 'Lojista',
        'user' => 'Usuário Básico',
    ],

    // Avisos e Alertas
    'alerts' => [
        'admin_access' => 'Acesso Administrativo',
        'admin_cannot_transfer' => 'Como administrador, você pode visualizar este formulário, mas não pode realizar transferências.',
        'admin_cannot_deposit' => 'Como administrador, você pode visualizar este formulário, mas não pode realizar depósitos.',
        'merchant_cannot_transfer' => 'Lojistas só podem receber transferências, não podem enviar dinheiro.',
        'user_info' => 'Suas Informações',
        'balance' => 'Saldo',
        'restriction' => 'Restrição',
        'restricted_access' => 'Acesso Restrito',
        'error' => 'Erro',
    ],

    // Permissões
    'permissions' => [
        'dashboard_view' => 'Visualizar Dashboard',
        'transfer_create' => 'Criar Transferência',
        'transfer_view' => 'Visualizar Transferência',
        'transfer_history' => 'Histórico de Transferências',
        'deposit_create' => 'Criar Depósito',
        'deposit_view' => 'Visualizar Depósito',
        'admin_users_view' => 'Visualizar Usuários',
        'admin_users_create' => 'Criar Usuários',
        'admin_users_edit' => 'Editar Usuários',
        'admin_users_delete' => 'Excluir Usuários',
        'admin_transactions_view' => 'Visualizar Transações',
        'admin_transactions_manage' => 'Gerenciar Transações',
        'admin_permissions_manage' => 'Gerenciar Permissões',
        'admin_reports_view' => 'Visualizar Relatórios',
    ],

    // Descrições das Roles
    'role_descriptions' => [
        'admin' => 'Administrador do sistema com acesso a todas as funcionalidades',
        'support' => 'Equipe de suporte com acesso a visualização de dados',
        'common_user' => 'Usuário comum que pode transferir e depositar dinheiro',
        'merchant' => 'Lojista que pode receber pagamentos mas não transferir',
        'user' => 'Usuário básico com acesso limitado ao sistema',
    ],

    // Descrições das Permissões
    'permission_descriptions' => [
        'dashboard_view' => 'Permite visualizar o painel principal do sistema',
        'transfer_create' => 'Permite criar e realizar transferências de dinheiro',
        'transfer_view' => 'Permite visualizar formulários e informações de transferência',
        'transfer_history' => 'Permite visualizar o histórico de transferências',
        'deposit_create' => 'Permite criar e realizar depósitos de dinheiro',
        'deposit_view' => 'Permite visualizar formulários e informações de depósito',
        'admin_users_view' => 'Permite visualizar lista de usuários do sistema',
        'admin_users_create' => 'Permite criar novos usuários no sistema',
        'admin_users_edit' => 'Permite editar informações de usuários existentes',
        'admin_users_delete' => 'Permite excluir usuários do sistema',
        'admin_transactions_view' => 'Permite visualizar todas as transações do sistema',
        'admin_transactions_manage' => 'Permite gerenciar e modificar transações',
        'admin_permissions_manage' => 'Permite gerenciar permissões e roles do sistema',
        'admin_reports_view' => 'Permite visualizar relatórios e estatísticas do sistema',
    ],
];
