<?php

namespace App\Helpers;

class TranslationHelper
{
    /**
     * Traduz automaticamente uma chave ou retorna a chave se não encontrar tradução
     */
    public static function translate($key, $replace = [], $locale = null)
    {
        // Se a chave já começa com 'messages.', usa diretamente
        if (str_starts_with($key, 'messages.')) {
            return __($key, $replace, $locale);
        }

        // Tenta diferentes padrões de tradução
        $patterns = [
            'messages.titles.' . $key,
            'messages.forms.' . $key,
            'messages.buttons.' . $key,
            'messages.navigation.' . $key,
            'messages.roles.' . $key,
            'messages.alerts.' . $key,
            'messages.status.' . $key,
            'messages.permissions.' . $key,
            'messages.' . $key,
        ];

        foreach ($patterns as $pattern) {
            $translation = __($pattern, $replace, $locale);
            if ($translation !== $pattern) {
                // Se a tradução retornou um array, pega o primeiro valor
                if (is_array($translation)) {
                    return $translation[0] ?? $key;
                }
                return $translation;
            }
        }

        // Se não encontrar tradução, retorna a chave original formatada
        $formatted = ucwords(str_replace(['_', '-'], ' ', $key));
        
        // Se a tradução retornou um array, pega o primeiro valor
        if (is_array($formatted)) {
            return $formatted[0] ?? $key;
        }
        
        return $formatted;
    }

    /**
     * Traduz roles automaticamente
     */
    public static function translateRole($roleName)
    {
        $roleMap = [
            'common-user' => 'messages.roles.common_user',
            'merchant' => 'messages.roles.merchant',
            'admin' => 'messages.roles.admin',
            'support' => 'messages.roles.support',
            'user' => 'messages.roles.user',
        ];

        $key = $roleMap[$roleName] ?? 'messages.roles.' . str_replace('-', '_', $roleName);
        return __($key);
    }

    /**
     * Traduz permissões automaticamente
     */
    public static function translatePermission($permissionName)
    {
        $permissionMap = [
            'dashboard.view' => 'messages.permissions.dashboard_view',
            'transfer.create' => 'messages.permissions.transfer_create',
            'transfer.view' => 'messages.permissions.transfer_view',
            'transfer.history' => 'messages.permissions.transfer_history',
            'deposit.create' => 'messages.permissions.deposit_create',
            'deposit.view' => 'messages.permissions.deposit_view',
            'admin.users.view' => 'messages.permissions.admin_users_view',
            'admin.users.create' => 'messages.permissions.admin_users_create',
            'admin.users.edit' => 'messages.permissions.admin_users_edit',
            'admin.users.delete' => 'messages.permissions.admin_users_delete',
            'admin.transactions.view' => 'messages.permissions.admin_transactions_view',
            'admin.transactions.manage' => 'messages.permissions.admin_transactions_manage',
            'admin.permissions.view' => 'messages.permissions.admin_permissions_view',
            'admin.permissions.manage' => 'messages.permissions.admin_permissions_manage',
            'admin.reports.view' => 'messages.permissions.admin_reports_view',
        ];

        $key = $permissionMap[$permissionName] ?? 'messages.permissions.' . str_replace('.', '_', $permissionName);
        return __($key);
    }
}
