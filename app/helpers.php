<?php

use App\Helpers\TranslationHelper;

if (!function_exists('t')) {
    /**
     * Função helper para tradução automática
     */
    function t($key, $replace = [], $locale = null)
    {
        return TranslationHelper::translate($key, $replace, $locale);
    }
}

if (!function_exists('t_role')) {
    /**
     * Função helper para traduzir roles
     */
    function t_role($roleName)
    {
        return TranslationHelper::translateRole($roleName);
    }
}

if (!function_exists('t_permission')) {
    /**
     * Função helper para traduzir permissões
     */
    function t_permission($permissionName)
    {
        return TranslationHelper::translatePermission($permissionName);
    }
}
