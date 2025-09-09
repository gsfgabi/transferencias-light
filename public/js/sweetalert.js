// Funções SweetAlert2 para confirmações

// Confirmação para exclusão de usuário
function confirmDeleteUser(userName) {
    return Swal.fire({
        title: 'Tem certeza?',
        text: `Deseja realmente excluir o usuário "${userName}"?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Sim, excluir!',
        cancelButtonText: 'Cancelar',
        reverseButtons: true
    });
}

// Confirmação para login como usuário
function confirmLoginAsUser(userName) {
    return Swal.fire({
        title: 'Fazer login como usuário',
        text: `Deseja fazer login como "${userName}"?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#10b981',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Sim, fazer login',
        cancelButtonText: 'Cancelar',
        reverseButtons: true
    });
}

// Confirmação para transferência
function confirmTransfer(amount, recipientName) {
    return Swal.fire({
        title: 'Confirmar Transferência',
        html: `Deseja transferir <strong>R$ ${amount}</strong> para <strong>${recipientName}</strong>?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3b82f6',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Sim, transferir',
        cancelButtonText: 'Cancelar',
        reverseButtons: true
    });
}

// Confirmação para depósito
function confirmDeposit(amount) {
    return Swal.fire({
        title: 'Confirmar Depósito',
        html: `Deseja depositar <strong>R$ ${amount}</strong> em sua conta?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#10b981',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Sim, depositar',
        cancelButtonText: 'Cancelar',
        reverseButtons: true
    });
}

// Sucesso genérico
function showSuccess(title, text) {
    return Swal.fire({
        title: title,
        text: text,
        icon: 'success',
        confirmButtonColor: '#10b981'
    });
}

// Erro genérico
function showError(title, text) {
    return Swal.fire({
        title: title,
        text: text,
        icon: 'error',
        confirmButtonColor: '#ef4444'
    });
}

// Aviso genérico
function showWarning(title, text) {
    return Swal.fire({
        title: title,
        text: text,
        icon: 'warning',
        confirmButtonColor: '#f59e0b'
    });
}

// Informação genérica
function showInfo(title, text) {
    return Swal.fire({
        title: title,
        text: text,
        icon: 'info',
        confirmButtonColor: '#3b82f6'
    });
}
