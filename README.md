# SJ4WEB - Patch requête commandes

Ce module PrestaShop corrige une requête SQL lourde dans la **liste des commandes du back-office**, en optimisant la détection du champ "nouveau client".

## 🚀 Objectif

Accélérer le chargement de la grille des commandes (`Sell > Orders`) dans PrestaShop 8.x en patchant dynamiquement la requête Doctrine, sans override de classe.

## 🔧 Fonctionnement

Le module utilise le hook `actionOrderGridQueryBuilderModifier` pour :
- Intercepter la requête SQL générée pour la liste des commandes.
- Remplacer la sous-requête lente `SELECT COUNT()` utilisée pour déterminer si un client est "nouveau" par une requête optimisée utilisant `LIMIT 1`.

## ✅ Avantages

- Aucun override ni modification du cœur PrestaShop.
- Patch propre, modulaire et réversible.
- Compatible PrestaShop 8.0+.

## 📦 Installation

1. Copier le dossier du module dans `modules/sj4web_patchorderquery`.
2. Installer le module depuis le back-office ou via CLI.
3. C’est tout ! Le patch est actif tant que le module est activé.

## 🧪 Debug (optionnel)

Si le mode développeur (`_PS_MODE_DEV_`) est activé, un message est ajouté dans le journal PrestaShop lors de l’activation du patch.

## 🛠️ Compatibilité

- PrestaShop ≥ 8.0.0
- PHP ≥ 7.4

## ✍️ Auteur

SJ4WEB.FR  
https://www.sj4web.fr
