<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{

    /**
     * Dashboard administrativo
     */
    public function dashboard()
    {
        // Estatísticas gerais
        $stats = [
            'total_users' => User::count(),
            'common_users' => User::role('common-user')->count(),
            'merchant_users' => User::role('merchant')->count(),
            'admin_users' => User::role('admin')->count(),
            'support_users' => User::role('support')->count(),
            'total_transactions' => Transaction::count(),
            'completed_transactions' => Transaction::where('status', 'completed')->count(),
            'failed_transactions' => Transaction::where('status', 'failed')->count(),
            'total_volume' => Transaction::where('status', 'completed')->sum('amount'),
            'pending_transactions' => Transaction::where('status', 'pending')->count(),
        ];

        // Transações recentes
        $recentTransactions = Transaction::with(['sender', 'payee'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Usuários recentes
        $recentUsers = User::with('roles')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentTransactions', 'recentUsers'));
    }

    /**
     * Lista de usuários
     */
    public function users(Request $request)
    {
        $query = User::with('roles', 'wallet');

        // Filtro por busca (nome ou email)
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filtro por tipo de usuário (role)
        if ($request->filled('type')) {
            $type = $request->get('type');
            $query->whereHas('roles', function($q) use ($type) {
                $q->where('name', $type);
            });
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(20);

        // Manter os parâmetros de filtro na paginação
        $users->appends($request->query());

        return view('admin.users.index', compact('users'));
    }

    /**
     * Detalhes do usuário
     */
    public function userShow(User $user)
    {
        $user->load('roles', 'wallet', 'sentTransactions.payee', 'receivedTransactions.sender');
        
        $transactions = Transaction::where('sender_id', $user->id)
            ->orWhere('payee_id', $user->id)
            ->with(['sender', 'payee'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.users.show', compact('user', 'transactions'));
    }

    /**
     * Login como usuário
     */
    public function loginAsUser(User $user)
    {
        // Verificar se o usuário atual é admin
        if (!auth()->user()->hasRole('admin')) {
            return redirect()->back()->with('error', 'Acesso negado.');
        }

        // Armazenar o ID do admin na sessão antes de fazer logout
        $adminId = auth()->id();
        session(['admin_id' => $adminId]);

        // Fazer logout do admin e login como o usuário
        auth()->logout();
        auth()->login($user);

        return redirect()->route('dashboard')
            ->with('success', "Logado como {$user->name}. Use o botão 'Voltar ao Admin' para retornar.");
    }

    /**
     * Voltar ao usuário admin
     */
    public function backToAdmin()
    {
        $adminId = session('admin_id');
        
        if (!$adminId) {
            return redirect()->route('login')->with('error', 'Sessão de admin não encontrada.');
        }

        $admin = User::find($adminId);
        
        if (!$admin || !$admin->hasRole('admin')) {
            return redirect()->route('login')->with('error', 'Admin não encontrado.');
        }

        // Fazer logout do usuário atual e login como admin
        auth()->logout();
        auth()->login($admin);

        // Limpar a sessão de admin
        session()->forget('admin_id');

        return redirect()->route('admin.dashboard')
            ->with('success', 'Voltou para a conta de admin');
    }

    /**
     * Relatórios
     */
    public function reports(Request $request)
    {
        // Obter filtros do request
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        $transactionType = $request->get('transaction_type');

        // Query base para transações
        $transactionQuery = Transaction::query();

        // Aplicar filtros de data
        if ($startDate) {
            $transactionQuery->whereDate('created_at', '>=', $startDate);
        }
        if ($endDate) {
            $transactionQuery->whereDate('created_at', '<=', $endDate);
        }

        // Aplicar filtro de tipo de transação
        if ($transactionType) {
            $transactionQuery->where('status', $transactionType);
        } else {
            $transactionQuery->where('status', 'completed');
        }

        // Relatório de transações por período
        $transactionsByMonth = (clone $transactionQuery)
            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count, SUM(amount) as total')
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->limit(12)
            ->get();

        // Relatório de usuários por tipo
        $usersByRole = DB::table('model_has_roles')
            ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->select('roles.name', DB::raw('COUNT(*) as count'))
            ->groupBy('roles.name')
            ->get();

        // Top usuários por volume de transações (aplicando filtros de data)
        $topUsersQuery = User::with('wallet');

        if ($startDate || $endDate) {
            $topUsersQuery->whereHas('sentTransactions', function($query) use ($startDate, $endDate, $transactionType) {
                if ($startDate) {
                    $query->whereDate('created_at', '>=', $startDate);
                }
                if ($endDate) {
                    $query->whereDate('created_at', '<=', $endDate);
                }
                if ($transactionType) {
                    $query->where('status', $transactionType);
                } else {
                    $query->where('status', 'completed');
                }
            })->orWhereHas('receivedTransactions', function($query) use ($startDate, $endDate, $transactionType) {
                if ($startDate) {
                    $query->whereDate('created_at', '>=', $startDate);
                }
                if ($endDate) {
                    $query->whereDate('created_at', '<=', $endDate);
                }
                if ($transactionType) {
                    $query->where('status', $transactionType);
                } else {
                    $query->where('status', 'completed');
                }
            });
        }

        $topUsers = $topUsersQuery
            ->withCount(['sentTransactions as sent_count', 'receivedTransactions as received_count'])
            ->withSum('sentTransactions as sent_amount', 'amount')
            ->withSum('receivedTransactions as received_amount', 'amount')
            ->orderBy('sent_amount', 'desc')
            ->limit(10)
            ->get();

        // Dados para os filtros
        $filters = [
            'start_date' => $startDate,
            'end_date' => $endDate,
            'transaction_type' => $transactionType,
        ];

        return view('admin.reports', compact('transactionsByMonth', 'usersByRole', 'topUsers', 'filters'));
    }

    /**
     * Criar usuário
     */
    public function createUser()
    {
        $roles = \Spatie\Permission\Models\Role::all();
        return view('admin.users.create', compact('roles'));
    }

    /**
     * Salvar usuário
     */
    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'document' => 'required|string|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|exists:roles,name',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'document' => $request->document,
            'password' => bcrypt($request->password),
        ]);

        $user->assignRole($request->role);

        // Criar wallet para o usuário
        $user->wallet()->create(['balance' => 0.00]);

        return redirect()->route('admin.users.show', $user)
            ->with('success', 'Usuário criado com sucesso!');
    }

    /**
     * Editar usuário
     */
    public function editUser(User $user)
    {
        $roles = \Spatie\Permission\Models\Role::all();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Atualizar usuário
     */
    public function updateUser(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'document' => 'required|string|unique:users,document,' . $user->id,
            'role' => 'required|exists:roles,name',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'document' => $request->document,
        ]);

        // Atualizar role
        $user->syncRoles([$request->role]);

        return redirect()->route('admin.users.show', $user)
            ->with('success', 'Usuário atualizado com sucesso!');
    }

    /**
     * Excluir usuário
     */
    public function deleteUser(User $user)
    {
        // Verificar se é o próprio admin
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Você não pode excluir sua própria conta.');
        }

        // Verificar se o usuário tem transações
        $hasTransactions = $user->sentTransactions()->exists() || $user->receivedTransactions()->exists();
        
        if ($hasTransactions) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Não é possível excluir usuário que possui transações.');
        }

        // Excluir wallet se existir
        if ($user->wallet) {
            $user->wallet->delete();
        }

        // Excluir usuário
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Usuário excluído com sucesso!');
    }
}