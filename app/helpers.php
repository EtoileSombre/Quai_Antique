<?php

use App\Core\Csrf;

/**
 * Génère un champ input hidden pour le token CSRF
 */
function csrf_field(): string
{
    return Csrf::field();
}

/**
 * Récupère le token CSRF actuel
 */
function csrf_token(): string
{
    return Csrf::getToken();
}

/**
 * Vérifie le token CSRF
 */
function csrf_verify(): bool
{
    return Csrf::verify();
}
